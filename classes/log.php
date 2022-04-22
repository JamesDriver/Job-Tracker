<?php
//require_once '/var/www/design/mail.php';
class log {
    protected $conn;
    protected $startTime;
    protected $timeEnd;
    protected $company;

    public function __CONSTRUCT() {
        global $databaseConnection;
        global $companyId;
        $this->conn      = $databaseConnection->conn;
        $this->startTime = microtime(true);
        $this->company   = $companyId;
    }

    public static function error($error, $info = NULL) {
        echo "ERROR CODE:".$error['errorCode']."<br />".$error['errorMessage'];
        error_log("ERROR CODE:".$error['errorCode'] . " " . $error['errorMessage']);
        global $currentUser;
        email('adminEmail', "ERROR:".$error['errorCode'], "ERROR:".$error['errorCode'] . " at time " . date('Y-m-d H:i:s') . ' for user '.$currentUser->getName() . ' INFO: ' . $info);
        $query = sql::$logError;
        global $companyId;
        $parameters = [':errorCode' => $error['errorCode'], ':time' => date(format::$time), ':details' => $error['errorMessage'], ':company' => $companyId];
        run($query, $parameters);
        return $error['errorMessage'];
    }
}
//log::error(errors::$databaseConnectionError);

class functionRun extends log {
    private $functionName;

    public function log() {
        $query = sql::$functionRun;
        //$parameters = array(':functionName' => $this->functionName, ':company' => $this->company, ':timeRun' => date(format::$time), ':timeToRun' => (microtime(true)-($this->startTime)));
        //if (run($query, $parameters)) { return true; }
        return true;
    }

    function __CONSTRUCT($function) {
        parent::__CONSTRUCT();
        $this->functionName = $function;
    }
}
//start of page
//$pageLoad = new PageLoad('jobs');

//end of page
//$pageLoad->log();

class pageLoad extends log {
    private $pageUrl;

    public static function log($page) {
        $query = sql::$pageLoad;
        global $companyId;
        global $currentUser;
        $timeRun = '';
        $timeToRun = '';
        $parameters = array(':page' => $page, ':company' => $companyId, ':time' => date(format::$time), ':user' => $currentUser->getId());
        if (run($query, $parameters)) { return true; }  else {
            error_log('failed to log page');
        }
    }

    function __CONSTRUCT($page) {
        parent::__CONSTRUCT();
        $this->pageUrl = $page;
    }
}
class Backup {
    private $id;
    private $userId;
    private $companyId;
    private $userName;
    private $time;

    private $objectId   = '';
    private $objectName = '';
    private $objectType = '';
    private $action     = '';
    //create, update, delete
    //user '(getUserById()->name()) else $username '$action' $objectType $objectName(ie number for job, name for user, name for status)
    public function __CONSTRUCT() {
        global $currentUser;
        global $companyId;
        if ($currentUser) {
            $this->userName  = $currentUser->getName();
            $this->userId    = $currentUser->getId();
        } else {
            $this->userName = '';
            $this->userId = 99999999;
        }
        $this->time      = date('Y-m-d H:i:s');
        $this->companyId = $companyId;
        if (isset($_COOKIE['switchedUser']) && ( ($currentUser->getId() == 57) || ($currentUser->getId() == 58) ))  {
            $this->userId    = $_COOKIE['switchedUser'];
        }
    }
    public function setObjectId(  $var) { $this->objectId   = $var; }
    public function setObjectName($var) { $this->objectName = $var; }
    public function setObjectType($var) { $this->objectType = $var; }
    public function setAction(    $var) { $this->action     = $var; }

    public function save() {
        $parameters = array(
            ':userId'     => $this->userId,
            ':userName'   => $this->userName,
            ':time'       => $this->time,
            ':companyId'  => $this->companyId,
            ':objectId'   => $this->objectId,
            ':objectName' => $this->objectName,
            ':objectType' => $this->objectType,
            ':action'     => $this->action
        );
        $query = '';
        run(sql::$backup, $parameters);
    }
}
function getEvents($start = NULL, $end = NULL) {
    global $companyId;
    if (isset($start) && isset($end)) {
        return run(sql::$getLimitedEvents, array(':companyId'=>$companyId, ':start'=>$start, ':end'=>$end))->fetchAll();
    }
    return run(sql::$getEvents, array(':companyId'=>$companyId))->fetchAll();
}
//start of function
//$functionRun = new functionRun('getjobs');
//end of function
//$functionRun->log();
