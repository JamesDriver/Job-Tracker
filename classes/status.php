<?php
class Status {
    private $conn;
    private $id;
    private $order;
    private $name;
    private $function;
    private $color;
    private $emails = array();
    private $allowedUserTypes = array();
    private $inspectionsRequired = false;

    public function getId()       { return $this->id;       }
    public function getOrder()    { return $this->order;    }
    public function getFunction() { return $this->function; }
    public function getColor()    { return $this->color;    }
    public function getEmails()   { return $this->emails;   }
    public function getName($local = NULL) { return (!$local) ? noHTML($this->name) : $this->name; }
    public function getAllowedUserTypes()  { return $this->allowedUserTypes; }
    public function inspectionsRequired() { return $this->inspectionsRequired; }

    public function setId($var)       { $this->id       = $var; }
    public function setOrder($var)    { $this->order    = $var; }
    public function setName($var)     { $this->name     = $var; }
    public function setFunction($var) { $this->function = $var; }
    public function setColor($var)    { $this->color    = $var; }
    public function setInspection($var) { $this->inspectionsRequired = $var; }
    public function addEmail($var)    { array_push($this->emails, $var); }
    public function addAllowedUserType($var) {
        if (is_a($var, 'UserType')) { 
            $this->allowedUserTypes[$var->getId()] = $var;
        } else {
            error_log('hereaa');
            $var = getUserTypeById($var);
            error_log('hereac');
            $this->allowedUserTypes[$var->getId()] = $var;
        }
    }
    public function canBeSetBy($user) {
        $typeId = $user->getType()->getId();
        if (isset($this->allowedUserTypes[$typeId])) {
            return true;
        } elseif($typeId == 0) {
            return true;
        } return false;
    }

    public function __CONSTRUCT($statusArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($statusArray) {
            $this->setId(      $statusArray[statusData::$id]      );
            $this->setOrder(   $statusArray[statusData::$order]   );
            $this->setName(    $statusArray[statusData::$name]    );
            $this->setFunction($statusArray[statusData::$function]);
            $this->setColor(   $statusArray[statusData::$color]   );
            $this->setInspection($statusArray['inspectionRequired']);
        }
    }

    public function create() {
        global $permissions2;
        if ($permissions2->settingsCustomization) {
            global $companyId;
            if (!$this->name)             { log::error(errors::$noNameOnStatusCreate);     die; }
            if (!$this->color)            { log::error(errors::$noColorOnStatusCreate);    die; }
            if (!$this->function)         { log::error(errors::$noFuncOnStatusCreate);     die; }
            if (!$this->allowedUserTypes) { log::error(errors::$noOkTypesOnStatusCreate);  die; }
            $parameters = array(':company'=>$companyId, ':position'=>$this->getOrder(), ':name'=>$this->name, ':color'=>$this->color, 'function'=>$this->function, ':inspection'=>$this->inspectionsRequired());
            if (run(sql::$createStatus, $parameters)) {
                $status = $this->conn->lastInsertId();
                if ($this->emails) {
                    foreach($this->emails as $email) {
                        run(sql::$createStatusEmail, array(':status'=>$status, ':email'=>$email));
                    }
                }
                if ($this->allowedUserTypes) {
                    foreach($this->allowedUserTypes as $type) {
                        run(sql::$createStatusAllowedType, array(':status'=>$status, ':type'=>$type->getId()));
                    }
                }
                $status = getStatusById($status);
                $backup = new Backup();
                $backup->setObjectId($status->getId());
                $backup->setObjectName($status->getName());
                $backup->setObjectType('Status');
                $backup->setAction('Create');
                $backup->save();
                return true;
            } else {
                return false;
            }
        }
    }
    public function update() {
        global $permissions2;
        if ($permissions2->settingsCustomization) {
            global $companyId;
            if (!$this->id)       { log::error(errors::$noIdOnStatusUpdate);    die; }
            if (!$this->name)     { log::error(errors::$noNameOnStatusUpdate);  die; }
            if (!$this->color)    { log::error(errors::$noColorOnStatusUpdate); die; }
            if (!$this->function) { log::error(errors::$noFuncOnStatusUpdate);  die; }
            if (!$this->allowedUserTypes) { log::error(errors::$noOkTypesOnStatusCreate);  die; }
            $tx = new Transaction();
            $old = getStatusById($this->id);
            if (!$old) { error_log('failed to create status ' . $this->id); die;}
            $parameters = array(':id'=>$this->id, ':name'=>$this->name, ':color'=>$this->color, ':function'=>$this->function, ':position'=>$this->getOrder(), ':inspection'=>$this->inspectionsRequired(), ':company'=>$companyId);
            $tx->addQuery(array('query'=>sql::$updateStatus, 'parameters'=>$parameters));
            if ($this->getEmails() != $old->getEmails()) {

                $tx->addQuery(array('query'=>sql::$deleteStatusEmails, 'parameters'=>array(':status'=>$this->getId())));

                foreach($this->getEmails() as $email) {
                    $query = sql::$createStatusEmail;
                    $parameters = array(':status'=>$this->getId(), ':email'=>$email);
                    $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
                }
            }
            if ($this->getAllowedUserTypes() != $old->getAllowedUserTypes()) {
                $tx->addQuery(array('query'=>sql::$deleteStatusAllowedType, 'parameters'=>array(':status'=>$this->getId())));
                foreach($this->getAllowedUserTypes() as $type) {
                    $query = sql::$createStatusAllowedType;
                    $parameters = array(':status'=>$this->getId(), ':type'=>$type->getId());
                    $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
                }
            }
            if ($tx->run()) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Status');
                $backup->setAction('Update');
                $backup->save();
                return true;
            } else {
                return false;
            }
        }
    }
    public function delete() {
        global $permissions2;
        if ($permissions2->settingsCustomization) {
            global $companyId;
            if (run(sql::$deleteStatus, array(':id'=>$this->id, ':company'=>$companyId))) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Status');
                $backup->setAction('Delete');
                $backup->save();
                return true;
            } else {
                return false;
            }
        }
    }
}
function saveStatusPost($datas) {
    $order = 1;
    $statuses = getStatuses();
    $datas = json_decode($datas['status']);
    if (count($datas) < 1) {
        error_log('more than 0 statuses required');
        http_response_code(405);
        die;
    }
    foreach($statuses as $status) {
        $exists = false;
        foreach($datas as $data) {
            if (isset($data->{'id'})) {
                if ($status->getId() == $data->{'id'}) {
                    $exists = true;
                }
            }
        }
        if (!$exists) {
            $status->delete();
        }
    }
    foreach($datas as $data) {
        $status = new Status();
        $status->setOrder($order);
        $status->setFunction($data->{'type'});
        $status->setName($data->{'name'});
        $status->setColor($data->{'color'});
        $status->setInspection($data->{'reqInspec'});
        foreach($data->{'emails'} as $email) {
            $status->addEmail($email);
        }
        foreach($data->{'userTypes'} as $type) {
            $status->addAllowedUserType($type);
        }
        if ($data->{'id'}) {
            $status->setId($data->{'id'});
            $status->update();
        } else {
            $status->create();
        }
        $order++;
    }

}
function getStatusEmails() {
    global $companyId;
    $stmt = run(sql::$getStatusEmails, array(':company'=>$companyId));
    $emailsArr = array();
    foreach($stmt as $email) {
        $emailsArr[$email['status']] = $email['email'];
    }
    return $emailsArr;
}
function getStatusEmailById($id) {
    $stmt = run(sql::$getEmailByStatus, array(':id'=>$id));
    foreach($stmt as $data) {
        return $data['email'];
    }
}
function createStatuses($returns) {
    $statuses = array();
    $userTypeTmp = getUserTypes();
    $userTypeArr = array();
    foreach($userTypeTmp as $userType) {
        $userTypeArr[$userType->getId()] = $userType;
    }
    foreach ($returns as $return) {
        $status = new Status($return);

        $emails = explode(',', $return['emails']);
        if (isset($emails)) {
            foreach($emails as $email) {
                $status->addEmail($email);
            }
        }

        $types = explode(',', $return['types']);
        if (isset($types)) {
            foreach($types as $type) {
                if (isset($userTypeArr[$type])) {
                    $status->addAllowedUserType($userTypeArr[$type]);
                }
            }
        }
        
        array_push($statuses, $status);
    }
    return $statuses;
}
function getStatuses() {
    global $gottenStatuses;
    if ($gottenStatuses) {
        return $gottenStatuses;
    }
    global $companyId;
    $parameters = array(':company' => $companyId);
    $stmt = run(sql::$getStatuses, $parameters);
    $statuses = createStatuses($stmt->fetchAll(PDO::FETCH_ASSOC));
    $gottenStatuses = $statuses;
    return $statuses;
}
function getStatusById($id) {
    global $gottenStatuses;
    $gottenStatuses = NULL;
    $statuses = getStatuses();
    foreach($statuses as $status) {
        if ($status->getId() == $id) {
            return $status;
        }
    }
    return false;
}
function getStatusesByFunc($var) {
    global $gottenStatuses;
    if ($gottenStatuses) {
        $returnArr = array();
        foreach($gottenStatuses as $status) {
            if ($status->getFunction() == $var) {
                array_push($returnArr, $status);
            }
        }
        return $returnArr;
    }
    global $companyId;
    $parameters = array(':company' => $companyId, ':function' => $var);
    $stmt = run(sql::$getStatusesByFunc, $parameters);
    $statuses = createStatuses($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $statuses;
}
function nextStatusPosition() {
    $statuses = getStatuses();
    $maxPos = 0;
    foreach($statuses as $status) {
        $maxPos = ($status->getOrder() > $maxPos) ? $status->getOrder() : $maxPos;
    }
    return $maxPos + 1;
}
