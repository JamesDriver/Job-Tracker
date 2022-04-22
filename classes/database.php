<?php
// ('deleteJob.php?id=31916',3,'2019-11-26 15:36:55',58)
// november 26@8:37
//if you run into issues with global variables, make sure to call globals.php
class Database{
    private $host     = $_ENV['db_host'];
    private $username = $_ENV['db_username'];
    private $password = $_ENV['db_password'];

    public  $user;
    public  $conn;

 // get the database connection
    private function getConnection($db_name = NULL)
    {
        $this->conn = null;
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        ];
        try
        {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $db_name, $this->username, $this->password, $options);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception)
        {
            log::error(errors::$databaseConnection);
            exit();
        }
        return $this->conn;
    }
    public function __CONSTRUCT($db_name = NULL) {
        //$function = new functionRun(functions::$constructDb);
        $this->getConnection($db_name);
        //$function->log();
    }
}

function fetch($query, $parameters = NULL, $class) {
    if ($return = run($query, $parameters)) {
        $return->setFetchMode(PDO::FETCH_CLASS, $class);
        if ($return = $return->fetch()) {
            return $return;
        }
    }
    return false;
}

function fetchAll($query, $parameters = NULL, $class) {
    if ($return = run($query, $parameters)) {
        $return = $return->fetchAll(PDO::FETCH_CLASS,$class);
        return $return;
    }
    //handle errors in whatever function calls fetch
    return false;
}

function run($query, $parameters = NULL) {
    global $databaseConnection;
    global $count;
    $count++;
    /*
    error_log($query);
    error_log(print_r($parameters,true));
    var_dump($query);
    echo '<br />';
    var_dump($parameters);
    echo '<br />';
    echo '<br />';*/
    if (!$databaseConnection->conn) {
        error_log($query);
        error_log(print_r($parameters, true));
    }
    $stmt = $databaseConnection->conn->prepare($query);
    if ($stmt == false) { return false; }
    try {
        $stmt->execute($parameters);
        return $stmt;
    } catch(Exception $e) {
        error_log($query);
        error_log(print_r($parameters, true));
    }
    return FALSE;
}

function validate($minimumUserLevel) {
    global $globalUserLevel;
    if (!isset($globalUserLevel)) {
        $query = "SELECT userLevel FROM user WHERE userCookie = :userCookie";
        $parameters = array(':userCookie' => $_COOKIE['']);
        $queryReturn = run($query, $parameters);
        $userLevel = $queryReturn->fetch();
        $globalUserLevel = intval($userLevel['userLevel']);
    }
    if (intval($globalUserLevel) <= $minimumUserLevel) {
        return TRUE;
    } else {
        return FALSE;
    }
}
function randomString($length) {
    $characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuv';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    return $randomString;
}

function getStatusFunctions() {
    $stmt = run(sql::$getStatusFunc);
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $response;
}

function isValidCookie($userId, $cookie) {
    $date = new DateTime('now');
    $stmt = run(sql::$getCookiesByUser, array(':user' => $userId, ':date' => $date->format('Y-m-d H:i:s')));
    $data = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    $count = 0;
    foreach($data as $uCookie) {
        $count++;
        if (password_verify($cookie, $uCookie['cookie'])) {
            if ($count > 5) {
                error_log('pass-ct: ' . $count);
            }
            return true;
        }
    }
    return false;
}

function saveCookie($userId, $cookie, $date) {
    $parameters = array(
        ':user' => $userId,
        ':cookie' => password_hash($cookie, PASSWORD_BCRYPT, ['cost' => 8,]),
        ':expiration' => $date->format('Y-m-d H:i:s'),
    );
    if (run(sql::$saveCookie, $parameters)) {
        return true;
    }
    return false;
}

function getCompany() {
    global $companyId;
    $stmt = run(sql::$getCompany, array(':id' => $companyId));
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    return $response;
}

function getCompanyId($cookie) {
    global $userCompanyIdFromCookie;
    if (isset($userCompanyIdFromCookie[$cookie])) {
        return $userCompanyIdFromCookie[$cookie];
    }
    if (is_numeric($cookie)) {
        $data = run(sql::$getUserCompany, array(':id' => $cookie));
        $company = $data->fetch();
        if (isset($company['company'])) {
            $userCompanyIdFromCookie[$cookie] = $company['company'];
            return $company['company'];
        }
    }
    return false;
}

function noHTML($input, $encoding = 'UTF-8')
{
    return /*$input;//*/htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
}
