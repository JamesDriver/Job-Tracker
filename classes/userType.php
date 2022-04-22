<?php
class UserType {
    private $conn;
    private $id;
    private $name;
    private $permission;
    private $fields;

    public function getId()                { return $this->id;         }
    public function getName($local = NULL) { return (!$local) ? noHTML($this->name) : $this->name; }
    public function getPermissions()       { return $this->permission; }
    public function getFields()            { return $this->fields;     }

    private function setId($var)         { $this->id         = $var; return $this; }
    public function setName($var)        { if (strlen($var) < lengths::$userTypeName) { $this->name = $var; } return $this; }
    public function setPermissions($var) { $this->permission = $var; return $this; } 
    public function setFields($var)      { $this->fields     = $var; return $this; } 

    public function create() {
        global $companyId;
        $parameters = array(
            ':name'       =>$this->name,
            ':permissions'=>$this->permission,
            ':fields'     =>$this->fields,
            ':company'    =>$companyId,
        );
        if (run(sql::$createUserType, $parameters)) {
            $id = $this->conn->lastInsertId();
            $backup = new Backup();
            $backup->setObjectId($id);
            $backup->setObjectName($this->getName());
            $backup->setObjectType('UserType');
            $backup->setAction('Create');
            $backup->save();
            return $id;
        } else {
            log::error(errors::$userTypeCreateFailure);
            return false;
        }
    }
    public function update() {
        $userTypes = getUserTypes();
        foreach($userTypes as $userType) {
            if ($userType->getId() == $_GET['id']) {
                $oldType = $userType;
                break;
            }
        }
        if ($this->getName(true) != $oldType->getName(true)) {
            if (run(sql::$updateUserType, array(':name' => $this->getName(true), ':id' => $this->getId()))) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('UserType');
                $backup->setAction('Update');
                $backup->save();
                return true;
            }
        } else {
            return true;
        }
        return false;
    }

    public function delete() {
        global $companyId;
        if (!run(sql::$deleteUserType, array(':id'=>$this->getId(), ':company'=>$companyId))) {
            return false;
        } 
        $backup = new Backup();
        $backup->setObjectId($this->getId());
        $backup->setObjectName($this->getName());
        $backup->setObjectType('UserType');
        $backup->setAction('Delete');
        $backup->save();
        $var = getPermissionsByType($this->getId());
        if ((!$var['permissions']->delete()) || (!$var['fields']->delete())) {
            return false;
        }
        return true;
    }
    
    public function __CONSTRUCT($userTypeArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($userTypeArray)
        {  
            if (($var = $userTypeArray[userTypeData::$id]) > -1) { $this->setId($var);     }
            if ($var = $userTypeArray[userTypeData::$name]) { $this->setName($var);        }
            if ($var = $userTypeArray['permissions'])       { $this->setPermissions($var); }
            if ($var = $userTypeArray['fields'])            { $this->setFields($var);      }
        }
    }
}
function createUserTypes($returns) {
    $userTypes = array();
    foreach($returns as $return) {
        $type = new UserType($return);
        array_push($userTypes, $type);
    }
    return $userTypes;
}
function getUserTypes() {
    global $companyId;
    $stmt = run(sql::$getUserTypes, array(':company' => $companyId));
    $types = createUserTypes($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $types;
}
function createUserType($returns) {
    $types = createUserTypes($returns);
    if (!empty($types)) {
        return $types[0];
    } return false;
}
function getUserTypeById($id) { 
    global $companyId;
    $stmt = run(sql::$getUserTypeById, array(':id'=>$id, ':company' => $companyId));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $type = createUserType($data);
    if ($type) {
        return $type;
    }
    //echo $var;
    //echo 'here';
    //log::error(errors::$noUserTypeByGivenId);
    return false;
}


function userTypeCreateDefaults($companyId) {
    //create a user type and user permissions and user fields that 
    //are copies of 1000, 1001, and 1002. 
    $types = array(1000, 1001, 1002);
    $userTypes = array();
    foreach($types as $type) {
        $fields = getFieldById($type);
        $permissions = getPermissionsById($type);
        $userType = getUserTypeById($type);
        $fields = getFieldById($fields->create());
        $permissions = getPermissionsById($permissions->create());
        $userType->setFields($fields->id);
        $userType->setPermissions($permissions->id);
        $userType->create();
        if ($type == 1000) {
            $userTypes['admin'] = $userType;
        } elseif ($type == 1001) {
            $userTypes['office'] = $userTypes;
        } elseif ($type == 1002) {
            $userTypes['field'] = $userTypes;
        }
    }
    return $userTypes;
}