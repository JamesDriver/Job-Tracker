<?php
class Permissions2 {
    public $id;
    public $jobReadAny  = false;
    public $jobDispatch = false;
    public $jobUpdate   = false;
    public $jobCreate   = false;
    public $jobDownload = false;
    public $jobReport   = false;
    public $jobDelete   = false;

    //viewClientsTable
    public $clientsRead  = false;
    public $clientUpdate = false;
    public $clientCreate = false;
    public $clientDelete = false;
    
    public $userRead       = false;
    public $userUpdate     = false;
    public $userCreate     = false;
    public $userDisable    = false;
    public $userDelete     = false;
    public $timecardAll    = false;

    public $settingsConsole       = false;
    public $settingsFiles         = false;
    public $settingsEvents        = false;
    public $settingsImport        = false;
    public $settingsExport        = false;
    public $settingsUserType      = false;
    public $settingsCustomization = false;
    public $settingsBilling       = false;

    public $materials      = false;
    public $materialsAdd   = false;
    public $materialUpdate = false;
    public $materialCreate = false;
    public $materialDelete = false;

    public $daily          = false;
    public $dailyCreate    = false;
    public $dailyUpdateOwn = false;
    public $dailyRead      = false;
    public $dailyUpdate    = false;
    public $dailyDelete    = false;

    public $permitsRead        = false;
    public $permitsRequest     = false;
    public $permitsUpload      = false;
    public $permitsDelete      = false;

    public $inspectionRead    = false;
    public $inspectionRequest = false;
    public $inspectionUpload  = false;
    public $inspectionDelete  = false;

    public function setAll( $val = true ) {
        $vars = get_class_vars(get_class($this));
        foreach($vars as $key => $var) {
            if ($key != 'id') {
                $this->{$key} = $val;
            }
        }
    }

    private function getVariables() {
        return (array)$this;
    }

    public function create() {
        $parameters = $this->getVariables();
        unset($parameters['id']);
        $sql = 'INSERT INTO permissions (';
        foreach($parameters as $key => $param) {
            $sql .= $key . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ') VALUES (';
        foreach($parameters as $key => $param) {
            $sql .= ':'.$key.',';
            unset($parameters[$key]);
            $parameters[':'.$key] = $param;
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';
        global $databaseConnection;
        $conn = $databaseConnection->conn;
        if (run($sql, $parameters)) {
            $id = $conn->lastInsertId();
            $backup = new Backup();
            $backup->setObjectId($id);
            $backup->setObjectName($id);
            $backup->setObjectType('Permission');
            $backup->setAction('Create');
            $backup->save();
            return $id;
        } return false;
    }

    public function update() {
        $parameters = $this->getVariables();
        unset($parameters['id']);
        $sql = 'UPDATE permissions SET ';
        foreach($parameters as $key => $param) {
            $sql .= $key . ' = :' . $key . ', ';
            unset($parameters[$key]);
            $parameters[':'.$key] = ($param) ? 1 : 0;
        }
        $sql = substr($sql, 0, -1);
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id = :id';
        $parameters[':id'] = $this->id;

        if (run($sql, $parameters)) {
            $backup = new Backup();
            $backup->setObjectId($this->id);
            $backup->setObjectName($this->id);
            $backup->setObjectType('Permission');
            $backup->setAction('Update');
            $backup->save();
            return true;
        } return false;
    }

    public function delete() {
        if (run(sql::$deletePermission, array(':id'=>$this->id))) {
            $backup = new Backup();
            $backup->setObjectId($this->id);
            $backup->setObjectName($this->id);
            $backup->setObjectType('Permission');
            $backup->setAction('Delete');
            $backup->save();
            return true;
        } return false;
    }

    public function __CONSTRUCT( $permissionArray = NULL ) {  
        if ($permissionArray) {
            $vars = get_class_vars(get_class($this));
            if (!isset($permissionArray[array_key_last($vars)])) {
                $permissionArray = array_reverse($permissionArray);
                $permissionArray = array_pop($permissionArray);
            }
            if (isset($permissionArray["permissions"])) {
                $this->id = $permissionArray["permissions"];
            }
            if (isset($permissionArray["id"])) {
                $this->id = $permissionArray["id"];
            }
            foreach($vars as $key => $var) {
                if ($key != 'id') {
                    $this->{$key} = $permissionArray[permissionsData2::${$key}];
                }
            }
        }
    }
}





function setPermissions2() {
    global $currentUser;
    $parameters = array(':id' => /*1);//*/($currentUser->getType())->getId());
    $stmt = run(sql::$getPermissions2, $parameters);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    global $permissions2;
    global $fields;
    $permissions2 = new Permissions2($data);
    $fields = new Fields($data);
}
function getPermissionsByType($userTypeId) {
    global $companyId;
    $parameters = array(':id'=>$userTypeId, ':company'=>$companyId);
    $stmt = run(sql::$getPermissionsByType, $parameters);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $permissions2 = new Permissions2($data);
    $fields = new Fields($data);
    return array('permissions'=>$permissions2, 'fields'=>$fields);
}
function getPermissionsById($id) {
    $parameters = array(':id'=>$id);
    $stmt = run(sql::$getPermissionsById, $parameters);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $permissions = new Permissions2($data);
    return $permissions;
}


/*


function createPermission($permission) {
            $parameters = $this->getVariables();

    $sql = 'INSERT INTO permissions (';
    foreach($parameters as $key => $param) {
        $sql .= $key . ',';
    }
    $sql = substr($sql, 0, -1);
    $sql .= ') VALUES (';
    foreach($parameters as $key => $param) {
        $sql .= ':'.$key.',';
        unset($parameters[$key]);
        $parameters[':'.$key] = $param;
    }
    $sql = substr($sql, 0, -1);
    $sql .= ')';
    global $databaseConnection;
    $conn = $databaseConnection->conn;
    if (run($sql, $parameters)) {
        return $conn->lastInsertId();
    } return false;
}
function updatePermission($permission, $id) {
    $parameters = array(
        'jobReadAny'           => $permission->jobReadAny,
        'jobDispatch'          => $permission->jobDispatch,
        'jobUpdate'            => $permission->jobUpdate,
        'jobCreate'            => $permission->jobCreate,
        'jobDownload'          => $permission->jobDownload,
        'jobReport'            => $permission->jobReport,
        'jobDelete'            => $permission->jobDelete,
        'clientsRead'          => $permission->clientsRead,
        'clientUpdate'         => $permission->clientUpdate,
        'clientCreate'         => $permission->clientCreate,
        'clientDelete'         => $permission->clientDelete,
        'userRead'             => $permission->userRead,
        'userUpdate'           => $permission->userUpdate,
        'userCreate'           => $permission->userCreate,
        'userDisable'          => $permission->userDisable,
        'userDelete'           => $permission->userDelete,
        'settingsConsole'      => $permission->settingsConsole,
        'settingsFiles'        => $permission->settingsFiles,
        'settingsEvents'       => $permission->settingsEvents,
        'settingsImport'       => $permission->settingsImport,
        'settingsExport'       => $permission->settingsExport,
        'settingsUserType'     => $permission->settingsUserType,
        'settingsCustomization'=> $permission->settingsCustomization,
        'settingsBilling'      => $permission->settingsBilling,
        'materials'            => $permission->materials,
        'materialsAdd'         => $permission->materialsAdd,
        'materialUpdate'       => $permission->materialUpdate,
        'materialCreate'       => $permission->materialCreate,
        'materialDelete'       => $permission->materialDelete,
        'daily'                => $permission->daily,
        'dailyCreate'          => $permission->dailyCreate,
        'dailyUpdateOwn'       => $permission->dailyUpdateOwn,
        'dailyRead'            => $permission->dailyRead,
        'dailyUpdate'          => $permission->dailyUpdate,
        'dailyDelete'          => $permission->dailyDelete,
        'permitsRead'          => $permission->permitsRead,
        'permitsRequest'       => $permission->permitsRequest,
        'permitsUpload'        => $permission->permitsUpload,
        'permitsDelete'        => $permission->permitsDelete,
        'inspectionRead'       => $permission->inspectionRead,
        'inspectionRequest'    => $permission->inspectionRequest,
        'inspectionUpload'     => $permission->inspectionUpload,
        'inspectionDelete'     => $permission->inspectionDelete,
        'timecardAll'          => $permission->timecardAll
    );

    $sql = 'UPDATE permissions SET ';
    foreach($parameters as $key => $param) {
        $sql .= $key . ' = :' . $key . ', ';
        unset($parameters[$key]);
        $parameters[':'.$key] = $param;
    }
    $sql = substr($sql, 0, -1);
    $sql = substr($sql, 0, -1);
    $sql .= ' WHERE id = :id';
    $parameters[':id'] = $id;
    if (run($sql, $parameters)) {
        return true;
    } return false;
}

function deletePermission($id) {
    if (run(sql::$deletePermission, array(':id'=>$id))) {
        return true;
    } return false;
}*/