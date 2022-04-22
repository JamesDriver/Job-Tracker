<?php
require_once '/var/www/classes/userType.php';
//require_once "/var/www/design/validatedForm.php";
//require_once '/var/www/design/stripe.php';

class User {
    private $conn;
    private $id;
    private $name;
    private $username;
    private $email;
    private $phone;
    private $password;
    private $type;
    private $cookie;
    private $disabled;
    private $mobileTable;
    private $sort;

    public function text($message) {
        $sid = text($this->phone, $message);
        global $companyId;
        $parameters = array(
            ':sid'       => $sid,
            ':user'      => $this->id,
            ':content'   => $message,
            ':date'      => date(format::$time),
            ':delivery'  => 'sending',
            ':company'   => $companyId,
            ':number'    => $this->phone
        );
        if (run(sql::$textLog, $parameters)) {
            return $sid;
        } return false;
    }
    public function email($subject, $message) {
        email($this->getEmail(true), $subject, $message);
        return true;
    }

    public function getId()              { return $this->id;          }
    public function getName(    $local = NULL) { return (!$local) ? noHTML($this->name)     : $this->name;     }
    public function getUsername($local = NULL) { return (!$local) ? noHTML($this->username) : $this->username; }
    public function getEmail(   $local = NULL) { return (!$local) ? noHTML($this->email)    : $this->email;    }
    public function getPhone(   $local = NULL) { return (!$local) ? noHTML($this->phone)    : $this->phone;    }
    public function getPassword()        { return $this->password;    }
    public function getType()            { return $this->type;        }
    public function getCookie()          { return $this->cookie;      }
    public function getMobileTableView() { return $this->mobileTable; }
    public function isDisabled()         { return ($this->disabled == 1) ? true : false; }
    public function getSort()            { return $this->sort;        }

    private function setId($var)            { $this->id          = $var; return true; }
    public function setName($var)           { $this->name        = $var; }//if (isUserNameValid($var))      { $this->name      = $var;                                                  return true; } else { echo "hey no"; return false; } }
    public function setUsername($var)       { $this->username    = $var; }//if (canSetUsername($var))       { $this->username  = $var;                                                  return true; } else { echo "hey no"; return false; } }
    public function setEmail($var)          { $this->email       = $var; }//if (isUserEmailValid($var))     { $this->email     = $var;                                                  return true; } else { echo "hey no"; return false; } }
    public function setPhone($var)          { $this->phone       = $var; }//if (isUserPhoneValid($var))     { $this->phone     = $var;                                                  return true; } else { echo "hey no"; return false; } }
    public function setPassword($var)       { $this->password    = $var; }//if (isUserPasswordValid($var))  { $this->password  = $var;                                                  return true; } else { echo "hey no"; return false; } }
    public function setMobileTableView($var){ $this->mobileTable = $var; }
    public function setSort($var)           { $this->sort        = $var; }
    //public function setPassword($var)       { if (isUserPasswordValid($var))  { $this->password  = password_hash($var, PASSWORD_BCRYPT, ['cost' => 12,]); return true; } else { return false; } }
    public function setType($var)           { $this->type = $var; }
    //hashed cookie, as variable to pass through url
    public function getValue()   { return md5($this->cookie);}
    //set the cookie
    private function setCookie() {
        $cookie = randomString(30);
        //$this->cookie = hashPass($cookie);
        //$user->update();
        //return $cookie;
    }
    //disable or enable the user
    public function disable() { $this->disabled = 1; }
    public function enable()  { $this->disabled = 0; }

    public function __CONSTRUCT($userArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($userArray) {
            $this->setId(      $userArray[userdata::$id]);
            $this->setName(    $userArray[userdata::$name]);
            $this->setUsername($userArray[userdata::$username]);
            $this->setEmail(   $userArray[userdata::$email]);
            $this->setPhone(   $userArray[userdata::$phone]);
            $this->password = ($userArray[userdata::$password]);
            if ($userArray[userData::$disabled] == 0) {
                $this->enable();
            } elseif ($userArray[userData::$disabled] == 1) {
                $this->disable();
            } else { log::error(errors::$userDisableFailed); }
            $this->setMobileTableView(($userArray[userdata::$mobileTable] == 1) ? true : false);
        }
    }

    public function create() {
        $functionRun = new FunctionRun(functions::$createUser);
        global $permissions2;
        global $companyId;
        global $currentUser;
        if ($permissions2->userCreate) {
            $types = getUserTypes();
            $lowestType = $types[0]->getId();
            foreach($types as $type) {
                if ($type->getId() < $lowestType) {
                    $lowestType = $type->getId();
                }
            }
            //username
            if (!$this->getUsername())
            {
                $functionRun->error(errors::$noUsernameOnCreate);
                die;
            } elseif (canSetUsername($this->getUsername(true)))  {
                $parameters[':'.userData::$username] = $this->getUsername(true);
            } else {
                $functionRun->error(errors::$invalidUsername);
            }

            //user name
            if (!$this->getName()) {
                $functionRun->error(errors::$noNameOnCreate);
                die;
            }
            elseif (isUserNameValid($this->getName(true))) {
                $parameters[':'.userData::$name] = $this->getName(true);
            } else {
                $functionRun->error(errors::$invalidName);
            }

            //user email
            if (!$this->getEmail()) {
                $functionRun->error(errors::$noEmailOnCreate);
                die;
            } elseif (isUserEmailValid($this->email)) {
                $parameters[':'.userData::$email] = $this->email;
            } else {
                $functionRun->error(errors::$invalidEmail);
            }

            //user phone
            if (!$this->getPhone()) {
                $functionRun->error(errors::$noPhoneOnCreate);
                die;
            } elseif (isUserPhoneValid($this->phone)) {
                $parameters[':'.userData::$phone] = $this->phone;
            } else {
                $functionRun->error(errors::$invalidPhone);
            }

            //password
            if (!$this->getPassword()) {
                $functionRun->error(errors::$noPasswordOnCreate);
                die;
            } elseif (isUserpasswordValid($this->getPassword())) {
                $parameters[':'.userData::$password] = hashPass($this->getPassword());
            } else {
                $functionRun->error(errors::$invalidPassword);
            }

            //type
            if (!$this->getType()) {
                $functionRun->error(errors::$noTypeOnCreate);
                die;
            } elseif (($this->getType())->getId() == $lowestType) {
                if (($currentUser->getType())->getId() == $lowestType) {
                    $parameters[':'.userData::$type]     = ($this->getType())->getId();
                } else {
                    $functionRun->error(errors::$administrativeTypeError);  die;
                }
            } else {
                $parameters[':'.userData::$type]     = ($this->getType())->getId();
            }

            $parameters[':company'] = $companyId;
            $this->enable();
            $parameters[':disabled'] = 0;
            run(sql::$createUser, $parameters);
            $user = getUserById($this->conn->lastInsertId());
            $backup = new Backup();
            $backup->setObjectId($user->getId());
            $backup->setObjectName($user->getName());
            $backup->setObjectType('User');
            $backup->setAction('Create');
            $backup->save();
            //$stripe = new Stripe();
            //updateUserCount();
            return $user;
        }
        $functionRun->log();
    }
    public function update() {
        $functionRun = new FunctionRun(functions::$updateUser);
        global $permissions2;
        global $currentUser;
        if ($permissions2->userUpdate || $this->getId()==$currentUser->getId()) {
            $old = getUserById($this->id);$tx = new Transaction();$wCol='whereColumn';$wVal='whereVal';$tbl='table';$col='column';$data='data';$queryOptions = array();
            if ($old->getName(true) != $this->getName(true)) {
                if (isUserNameValid($this->getName(true))) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$name,$data=>$this->getName(true)));
                }
            }
            if ($old->getUsername(true) != $this->getUsername(true)) {
                if (canSetUsername($this->getUsername(true))) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$username,$data=>$this->getUsername(true)));
                }
            }
            if ($old->getEmail(true) != $this->getEmail(true)) {
                if (isUserEmailValid($this->getEmail(true))) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$email,$data=>$this->getEmail(true)));
                }
            }
            if ($old->getPhone(true) != $this->getPhone(true)) {
                if (isUserPhoneValid($this->getPhone(true))) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$phone,$data=>$this->getPhone(true)));
                }
            }
            if ($old->isDisabled() != $this->isDisabled()) {
                if ($permissions2->userDisable) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$disabled,$data=>$this->isDisabled()));
                }
            }
            if (!password_verify($this->getPassword(), $old->getPassword())) {
                if (isUserpasswordValid($this->getPassword())) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$password,$data=>hashPass($this->getPassword())));
                }
            }
            if (($old->getType())->getId() != ($this->getType())->getId()) {
                if (canSetType($this->getType(),$this->getId())) {
                    array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'user',$col=>userData::$type, $data=>($this->getType())->getId()));
                }
            }
            foreach($queryOptions as $option) {
                $query = "UPDATE {$option[$tbl]} SET {$option[$col]} = :{$option[$col]} WHERE {$option[$wCol]} = :{$option[$wCol]}";
                $parameters = array(":{$option[$col]}"=>$option[$data], ":{$option[$wCol]}"=>$option[$wVal]);
                //echo $query;
                //echo '<br />';
                //var_dump($parameters);
                //echo '<br />';
                $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
            }
            if ($tx->run()) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('User');
                $backup->setAction('Update');
                $backup->save();
                $functionRun->log();
                updateUserCount();
                return true;
            } else {
                $functionRun->error(errors::$userUpdateFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$noUserUpdatePermission);
            return false;
        }
    }
    public function delete() {
        $functionRun = new FunctionRun(functions::$deleteUser);
        global $permissions2;
        if ($permissions2->userDelete) {
            global $companyId;
            if (run(sql::$deleteUser, array(':id'=>$this->getId(), ':company'=>$companyId))) {
                $functionRun->log();
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('User');
                $backup->setAction('Delete');
                $backup->save();
                updateUserCount();
                return true;
            } else {
                $functionRun->error(errors::$userDeleteFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$noUserDeletePermission);
            return false;
        }
    }
    public function getDailies($start = NULL, $end = NULL) {
        $dailies = getHoursByUserId($this->id);
        $finalDailies = array();
        foreach($dailies as $daily) {
            if ($daily->getDate() >= $start && $daily->getDate() <= $end) {
                array_push($finalDailies, $daily);
            }
        }
        return $finalDailies;
    }
    public function getAllDailies() {
        $dailies = getHoursByUserId($this->id);
        return $dailies;
    }
    public function notify($notification) {
        $notification->setUser($this->getId());
        $notification->send();
    }

    public function getMonthHours($date) {
        $start = $date->format(format::$time);
        $date->modify('last day of this month');
        $end = $date->format(format::$time);
        $dailies = $this->getDailies($start, $end);
        return $dailies;
    }
    public function getWeekHours($week) {
        $start = $week->format(format::$time);
        $week->modify("+6 days");
        $end = $week->format(format::$time);
        $dailies = $this->getDailies($start, $end);
        return $dailies;
    }
    public function getInitials() {
        $words = explode(" ", $this->getName(true));
        $initials = "";

        foreach ($words as $w) {
            $initials .= $w[0];
        }
        return $initials;
    }

}//
function hashPass($var) {
    return password_hash($var, PASSWORD_BCRYPT, ['cost' => 12,]);
}
function canSetType($type, $userId) {
    global $permissions2;
    global $currentUser;
    if ($permissions2->userUpdate) {
        return true;
    }
    return false;
}
function checkUsername($username, $user = NULL) {
    if (!$user) {
        if (canSetUsername($username)) {
            return true;
        } return false;
    }
    if (!preg_match('/[^A-Za-z0-9]/', $username) && strlen($username) > 6) {
        if ($username == $user->getUsername()) {
            return true;
        } elseif(canSetUsername($username)) {
            return true;
        }
    }
    return false;
}
function canSetUsername($username) {
    //returns true if the username is good to use
    //first checks if the username is valid(length, allowed chars)
    //checks to see if the user being updated has that username,
    //then checks if anyone else has that username,
    if (!preg_match('/[^A-Za-z0-9]/', $username) && strlen($username) > 6) {
        $parameters = array(userData::$username => $username);
        $stmt = run(sql::$checkUsername, $parameters);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($users) > 0) {
            return false;
        } else {
            return true;
        }
    }
    return false;
}
function isUserpasswordValid($var) {
    //allows a-z, A-Z, 0-9, some special chars
    if (!preg_match('/[^A-Za-z0-9!%@&*?]/', $var) && strlen($var) > 6) {
        return true;
    } else {
        return false;
    }
}
function isUserNameValid($var) {
    if (preg_match('/[a-zA-Z]/', $var)) {
        return true;
    } else {
        return false;
    }
}
function isUserEmailValid($var) {
    if (filter_var($var, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
function isUserPhoneValid($var) {
    $var = str_replace('(', '', $var);
    $var = str_replace(')', '', $var);
    $var = str_replace(' ', '', $var);
    if (preg_match('/[+]+[0-9]/', $var)) {
        return true;
    } else {
        return false;
    }
}
function isUsercookieValid($var) {
    if (preg_match('/[a-zA-Z0-9]/', $var)) {
        return true;
    } else {
        return false;
    }
}
function createUsers($returns) {
    $userTypes = getUserTypes();
    $typeArray = array();
    foreach($userTypes as $ut) {
        $typeArray[$ut->getId()] = $ut;
    }
    $users = array();
    foreach($returns as $return) {
        $user = new User($return);
        if (isset($typeArray[$return[userData::$type]])) {
            $user->setType($typeArray[$return[userData::$type]]);
        }
        array_push($users, $user);
    }
    return $users;
}
function createUser($returns) {
    $userTypes = getUserTypes();
    foreach($returns as $return) {
        $user = new User($return);
        $user->setType(getUserTypeById($return[userData::$type]));
        break;
    }
    if (isset($user)) {
        return $user;
    }
}
function getUsers($id = NULL) {
    global $allUsers;
    if ($allUsers) {
        return $allUsers;
    }
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $parameters = array(':company' => $company);
    $stmt = run(sql::$getUsers, $parameters);
    $users = createUsers($stmt->fetchAll(PDO::FETCH_ASSOC));
    $allUsers = $users;
    return $users;
}
function getEnabledUsers($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $parameters = array(':company' => $company);
    $stmt = run(sql::$getEnabledUsers, $parameters);
    $users = createUsers($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $users;
}

function getCurrentUser($cookie) {
    $functionRun = new functionRun(functions::$getCurrentUser);
    global $currentUser;
    if (isset($currentUser)) {
        return $currentUser;
    }
    if (isUserCookieValid($cookie)) {
        $parameters = array(':cookie' => $cookie);
        $stmt = run(sql::$getCurrentUser, $parameters);
        $user = createUser($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    $functionRun->log();
    return $user;
}

function getSortByUser($user) {
    $stmt = run(sql::$getSortByUser, array(':user' => $user->getId()));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sort = new Sort();
    foreach($data as $sortVal) {
        //worker
        if ($sortVal['sortcol'] == 'worker') {
            $sort->addWorker($sortVal['sortval']);
        }
        //status
        if ($sortVal['sortcol'] == 'status') {
            $sort->addStatus($sortVal['sortval']);
        }
        //type
        if ($sortVal['sortcol'] == 'type') {
            $sort->addType($sortVal['sortval']);
        }
    }
    return $sort;
}

function getViewingCols() {

}


function getUserByUsername($username) {
    //used for login
    $parameters = array(':username' => $username);
    $stmt = run(sql::$getUserByUsername, $parameters);
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (isset($user[0])) {
        return $user[0];
    } else {
        return false;
    }
}
function disableMobileTable($user) {
    global $companyId;
    run(sql::$disableMobileTable, array(':id'=>$user->getId(), ':company'=>$companyId));
}
function enableMobileTable($user) {
    global $companyId;
    run(sql::$enableMobileTable, array(':id'=>$user->getId(), ':company'=>$companyId));
}
function passwordReset($username) {
    $user = getUserByUsername($username);
    if ($user) {
        $resetCode = randomString(95);
        $parameters = array(
            ':user'=>   $user['id'],
            ':resetCode' => $resetCode,
            ':time' => date(format::$time)
        );
        run(sql::$resetCode, $parameters);
        $subject = 'Password Reset Request';
        $message = "https://{$_SERVER['SERVER_NAME']}/actions/passwordUpdate?id={$resetCode} is your reset link, and is good for 10 minutes. If you did not request this, please call us";
        email($user['email'], $subject, $message);
    }
}
function newPassword($reset, $password) {
    $parameters = array(':resetCode'=>$reset, ':date'=>date(format::$time));
    $stmt = run(sql::$resetCodeExists, $parameters);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = 0;
    if (is_array($rows)) {
        foreach($rows as $row) {
            $count++;
        }
    } else {
        return false;
    }
    if ($count == 1) {
        $stmt = run(sql::$getUserByIdPassReset, array(':id'=>$row['user']));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user['id'] == $row['user']) {
            return false;
        }
        $password = hashPass($password);
        $parameters = array(
            ':id'=>$row['user'],
            ':password' =>$password
        );
        if (run(sql::$updatePassword, $parameters)) {
            run(sql::$resetUsed, array(':resetCode'=>$reset));
            $subject = "Password Updated";
            $message = "Your password has been successfully updated. If you did not complete this action, call  immediately";
            email($user['email'], $subject, $message);
            return true;
        }
        return false;
    }
    echo $count;
    return false;
}
function getUserById($id) {
    //$functionRun = new functionRun(functions::$getUserById);
    global $companyId;
    $parameters = array(':id' => $id, ':company' => $companyId);
    $stmt = run(sql::$getUserById, $parameters);
    $user = createUser($stmt->fetchAll(PDO::FETCH_ASSOC));
    //$functionRun->log();
    return $user;
}
function deleteUser($id) {
    $functionRun = new functionRun(functions::$deleteUser);
    if (is_numeric($id)) {
        $user = getUserById($id);
        if ($user->delete()) {
            $functionRun->log();
            return true;
        } else {
            $functionRun->error(errors::$userDeleteFail);
            return false;
        }
    } return false;
}
function displayUsers($users) {
    $functionRun = new functionRun(functions::$displayUsers);
    $table = new Table();
    $table->addColumn( $colName     = new Column('Name')      );
    $table->addColumn( $colUsername = new Column('Username')  );
    $table->addColumn( $colEmail    = new Column('Email')     );
    $table->addColumn( $colPhone    = new Column('Phone')     );
    $table->addColumn( $colLevel    = new Column('UserLevel') );
    $table->addColumn( $colActions  = new Column('Actions')   );
    $buttons = array();
    global $permissions2;
    if ($permissions2->userUpdate) {
        $editButton = new Button();
        $editButton->setStyle('background-color:#007bff;color:white;');
        $editButton->setText('<span class="fa fa-pencil"></span>');
        $editButton->setHoverText('Edit');
    }
    if ($permissions2->userDisable) {
        $disableButton = new Button();
        $disableButton->setClass('btn btn-default');
    }
    if ($permissions2->userDelete) {
        $deleteButton = new Button();
        $deleteButton->setStyle('background-color:#f44336;color:white;');
        $deleteButton->setText('<span class="fa fa-trash-o"></span>');
        $deleteButton->setOther('data-toggle="modal" data-target="#deleteUserModal"');
        $deleteButton->setClass('btn btn-default deleteButton');
        $deleteButton->setHoverText('Delete');
    }  else {
        $viewButton = new Button();
        $viewButton->setStyle('background-color:#5bc0de;color:white;');
        $viewButton->setText('<span class="fa fa-eye"></span>');
        $viewButton->setHoverText('View');
    }
    $table->addStart(new DeleteModal('user'));
    foreach($users as $user) {
        $row = new Row('user'.$user->getId());
        if ($user->isDisabled()) { $row->addClass('grayout'); }
        $buttons = array();
        if (isset($editButton))   { $editButton->setLink("/user?id={$user->getId()}");  array_push($buttons, clone $editButton);     }
        if (isset($deleteButton)) { $deleteButton->setId($user->getId());               array_push($buttons, clone $deleteButton);   }

        else {
            $viewButton->setLink( "/user?id={$user->getId()}");   array_push($buttons, clone $viewButton);
        }
        if (isset($disableButton)) {
            $disableButton->setJsFunction("disableUser({$user->getId()}");
            if ($user->isDisabled()) {
                $disableButton->setText("<span class='fa fa-check' id='disable{$user->getId()}'></span>");
                $disableButton->setStyle('background-color:#ccc;color:green;');
                $disableButton->setHoverText('Enable');
            } else {
                $disableButton->setText("<span class='fa fa-ban' id='disable{$user->getId()}'></span>");
                $disableButton->setStyle('background-color:#ccc;color:#f44336;');
                $disableButton->setHoverText('Disable');
            }
            array_push($buttons, clone $disableButton);
        }
        $row->addData($colName,     new Href($user->getName(),"/user?id={$user->getId()}"));
        $row->addData($colUsername, new Paragraph($user->getUsername()));
        $row->addData($colEmail,    new Paragraph($user->getEmail()));
        $row->addData($colPhone,    new Paragraph($user->getPhone()));
        $row->addData($colLevel,    new Paragraph(($user->getType())->getName()));
        $row->addData($colActions,  $buttons, 'nowrap="nowrap"');
        $table->addRow($row);
    }
    $table->display();
}
function updateExistingUser($data, $user) {
    if (isset($data['name']))     { $user->setName($data['name']);               }
    if (isset($data['username'])) { $user->setUsername($data['username']);       }
    if (isset($data['password']) && $data['password'] != '********') { $user->setPassword($data['password']); }
    if (isset($data['email']))    { $user->setEmail($data['email']);             }
    if (isset($data['phone']))    { $user->setPhone('+'.preg_replace("/[^0-9]/", "", $data['phone']));  }
    if (isset($data['level']))    { $user->setType(getUserTypeById($data['level'])); }
    $user->update();
}
function createNewUser($data) {
    $user = new User();
    if (isset($data['name']))     { $user->setName($data['name']);               }
    if (isset($data['username'])) { $user->setUsername($data['username']);       }
    if (isset($data['password'])) { $user->setPassword($data['password']); }
    if (isset($data['email']))    { $user->setEmail($data['email']);             }
    if (isset($data['phone']))    { $user->setPhone('+'.preg_replace("/[^0-9]/", "", $data['phone']));  }
    if (isset($data['level']))    { $user->setType(getUserTypeById($data['level'])); }
    if ($user->create()) {
        return true;
    }
}
function createInvitedUser($data) {
    $stmt = run(sql::$getInvite, array(':code'=>$_GET['code']));
    $invite = $stmt->fetch();
    $user = new User();
    if (isset($data['name']))     { $user->setName($data['name']);               }
    if (isset($data['username'])) { $user->setUsername($data['username']);       }
    if (isset($data['password'])) { $user->setPassword($data['password']); }
    if (isset($data['email']))    { $user->setEmail($data['email']);             }
    if (isset($data['phone']))    { $user->setPhone('+'.preg_replace("/[^0-9]/", "", $data['phone']));  }
    if (isset($data['level']))    {
        $level = ($invite['level'] >= $data['level']) ? $invite['level'] : $data['level'];
        $user->setType(getUserTypeById($level));
    }
    if ($user->create()) {
        run(sql::$deleteInvite, array(':code'=>$_GET['code']));
        return true;
    }
}

function userButtons($user) {
    global $permissions2;
    $buttons = array();
    if ($permissions2->userUpdate) {
        $editButton = new Button();
        $editButton->setStyle('background-color:#007bff;color:white;');
        $editButton->setText('<span class="fa fa-pencil"></span>');
        $editButton->setHoverText('Edit');
        array_push($buttons, clone $editButton);
    }
    if ($permissions2->userDisable) {
        $disableButton = new Button();
        $disableButton->setClass('btn btn-default');
    }
    if ($permissions2->userDelete) {
        $deleteButton = new Button();
        $deleteButton->setStyle('background-color:#f44336;color:white;');
        $deleteButton->setText('<span class="fa fa-trash-o"></span>');
        $deleteButton->setOther('data-toggle="modal" data-target="#deleteUserModal"');
        $deleteButton->setClass('btn btn-default deleteButton');
        $deleteButton->setHoverText('Delete');
        array_push($buttons, clone $deleteButton);
    }  else {
        $viewButton = new Button();
        $viewButton->setStyle('background-color:#5bc0de;color:white;');
        $viewButton->setText('<span class="fa fa-eye"></span>');
        $viewButton->setHoverText('View');
        array_push($buttons, clone $viewButton);
    }
    if (isset($disableButton)) {
        $disableButton->setJsFunction("disableUser({$user->getId()}");
        if ($user->isDisabled()) {
            $disableButton->setText("<span class='fa fa-check' id='disable{$user->getId()}'></span>");
            $disableButton->setStyle('background-color:#ccc;color:green;');
            $disableButton->setHoverText('Enable');
        } else {
            $disableButton->setText("<span class='fa fa-ban' id='disable{$user->getId()}'></span>");
            $disableButton->setStyle('background-color:#ccc;color:#f44336;');
            $disableButton->setHoverText('Disable');
        }
        array_push($buttons, clone $disableButton);
    }
    return $buttons;
}


function displayUser($userId) {
    $functionRun = new functionRun(functions::$displayUser);
    $joining = false;
    if (isset($_POST['name'])) {
        if (isset($_GET['code'])) {
            createInvitedUser($_POST);
            echo "<script>window.location.replace('/login')</script>"; die;
        } elseif ($userId) {
            updateExistingUser($_POST, getUserById($userId));
        } //elseif (createNewUser($_POST)) {
          //  echo "<script>window.location.replace('/users')</script>"; die;
        //}
    }
    global $permissions2;
    $form = new Form();
    $form->setId('userForm12345');
    if(!$userId) {
        $form->addTitle('New User');
        $user   = new user();
    } elseif (!$user = getUserById($userId)) {
        $form->addTitle('New User');
        $user   = new user();
    } else {
        $form->addTitle($user->getName());
        $buttonsRow = userButtons($user);
        foreach($buttonsRow as $br) {
            $form->addReport($br);
        }
    }
    global $currentUser;
    $leftColumn  = new FormColumn();
        $leftColumn->addRow(userNameInput($user));
        $leftColumn->addRow(userUsernameInput($user));
        $leftColumn->addRow(userPasswordInput($user));
        if (isset($permissions2)) {
            $leftColumn->addRow(hiddenInput('canChangeLevel', ($permissions2->userUpdate) ? '1':'0'));
        } else {
            $leftColumn->addRow(hiddenInput('canChangeLevel', '0'));
        }
    $rightColumn = new FormColumn();
        $rightColumn->addRow(userEmailInput($user));
        $rightColumn->addRow(userPhoneInput($user));
        $rightColumn->addRow(userLevelInput($user, $joining));
        $rightColumn->addRow(hiddenInput('uId', $user->getId()));
    $form->addColumns(array($leftColumn, $rightColumn));
    if ($permissions2->userUpdate || $userId == $currentUser->getId()) {
        $buttonRow = new FormRow();
        $buttonRow->addField(submitButton());
        $form->addRow($buttonRow);
    }
    $form->display();


    if ($userId) {
        echo '<div class="tab" style="padding-top:50px">
        <button class="tablinks" onclick="openTab(\'Jobs\', \'Dailies\')">Jobs</button>
        <button class="tablinks" onclick="openTab(\'Dailies\', \'Jobs\')">Dailies</button>
      </div>';
        $sort = new Sort();
        $sort->addWorker($userId);
        global $userSortValue;
        $userSortValue = $sort;
        $jobs = getJobs();
        //$jobs = $sort->run($jobs);
        echo '<div id="Jobs" style="display:block">';
        displayJobs($jobs, NULL, 20);
        echo '</form>';
        echo '</div>';
        $dailys = getDailysByUserId($userId);
        echo '<div id="Dailies" style="display:none">';
        displayDailys($dailys);
        echo '</div>';
        echo '
        <script>
        function openTab(id, hide) {
            document.getElementById(id).style.display="block";
            document.getElementById(hide).style.display="none";
        }
        </script>';
    }
}
function userLevelInput($user = NULL, $disabled) {
    global $permissions2;
    global $currentUser;
    $types = getUserTypes();
    $userLevelInput = new ValidFormSelect();
    foreach($types as $type) {
        $option = new SelectOption();
        $option->setWord($type->getName());
        $option->setValue($type->getId());
        if ($user->getId() != NULL) {
            if (($user->getType())->getId() == $type->getId()) {
                $option->select();
            }
        }
        $userLevelInput->addOption($option);
    }
    $userLevelInput->setName('level');
    $userLevelInput->setId('level');
    $userLevelInput->setLabel('User Level');
    if (!$permissions2->userUpdate || !$disabled) {
        $userLevelInput->disable();
    }
    return $userLevelInput;
}
function userPhoneInput($user) {
    global $permissions2;
    global $currentUser;
    $userPhoneInput = new ValidFormTextInput($user->getPhone());
    $userPhoneInput->setName('phone');
    $userPhoneInput->setId('phone');
    $userPhoneInput->setLabel('Phone');
    $userPhoneInput->setClass('phone_us');
    $userPhoneInput->setLength(lengths::$phone);
    if (!$permissions2->userUpdate && $user->getId() != $currentUser->getId()) {
        $userPhoneInput->disable();
    }
    return $userPhoneInput;
}
function userEmailInput($user) {
    global $permissions2;
    global $currentUser;
    $userEmailInput = new ValidFormTextInput($user->getEmail());
    $userEmailInput->setName('email');
    $userEmailInput->setId('email');
    $userEmailInput->setLabel('Email');
    $userEmailInput->setLength(lengths::$email);
    if (!$permissions2->userUpdate && $user->getId() != $currentUser->getId()) {
        $userEmailInput->disable();
    }
    return $userEmailInput;
}
function userPasswordInput($user) {
    global $permissions2;
    global $currentUser;
    if ($user->getId() != NULL) {
        $userPasswordInput = new ValidFormTextInput('********');
    } else {
        $userPasswordInput = new ValidFormTextInput('');
    }
    $userPasswordInput->setName('password');
    $userPasswordInput->setId('password');
    $userPasswordInput->setLabel('Password');
    $userPasswordInput->setLength(lengths::$password);
    $userPasswordInput->setType('password');
    if (!$permissions2->userUpdate && $user->getId() != $currentUser->getId()) {
        $userPasswordInput->disable();
    }
    return $userPasswordInput;
}
function userUsernameInput($user) {
    global $permissions2;
    global $currentUser;
    $usernameInput = new ValidFormTextInput($user->getUsername());
    $usernameInput->setName('username');
    $usernameInput->setId('username');
    $usernameInput->setLabel('Username');
    $usernameInput->setLength(lengths::$username);
    if (!$permissions2->userUpdate && $user->getId() != $currentUser->getId()) {
        $usernameInput->disable();
    }
    return $usernameInput;
}
function userNameInput($user) {
    global $permissions2;
    global $currentUser;
    $nameInput = new ValidFormTextInput($user->getName());
    $nameInput->setName('name');
    $nameInput->setId('name');
    $nameInput->setLabel('Name');
    $nameInput->setLength(lengths::$userName);
    if (!$permissions2->userUpdate && $user->getId() != $currentUser->getId()) {
        $nameInput->disable();
    }
    return $nameInput;
}
function submitButton() {
    $button = new ValidButton();
    $button->setActionOnClick("validate();return false");
    $button->setName('submit1');
    $button->setClass('btn-primary');
    $button->setLabel('submit');
    return $button;
}
function hiddenInput($id, $value) {
    $input = new ValidFormTextInput($value);
    $input->setId($id);
    $input->setType('hidden');
    return $input;
}
function inviteButton() {
    $button = new ValidButton();
    $button->setName('submit1');
    $button->setType('submit');
    $button->setClass('btn-primary');
    $button->setLabel('Invite');
    return $button;
}
