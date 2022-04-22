<?php
class Type {
    private $conn;
    private $id;
    private $order;
    private $name;
    private $managers = array();
    private $color;

    public function getId()    { return $this->id;    }
    public function getOrder() { return $this->order; }
    public function getName($local = NULL)  { return (!$local) ? noHTML($this->name) : $this->name; }
    public function getColor() { return $this->color; }
    public function getManagers() { return $this->managers; }

    public function setId($var)      { $this->id    = $var; }
    public function setOrder($var)   { $this->order = $var; }
    public function setName($var)    { $this->name  = $var; }
    public function setColor($var)   { $this->color = $var; }
    public function addManager($var) { array_push($this->managers, $var); }

    public function __CONSTRUCT($typeArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($typeArray) {
            $this->setId(   $typeArray[typeData::$id]);
            $this->setOrder($typeArray[typeData::$order]);
            $this->setName( $typeArray[typeData::$name]);
            $this->setColor($typeArray[typeData::$color]);
            if (isset($typeArray[typeData::$managers])) {
                $managers = explode(',', $typeArray[typeData::$managers]);
                foreach($managers as $manager) {
                    $this->addManager($manager);
                }
            }
        }
    }
    public function create() {
        global $permissions2;
        if ($permissions2->settingsCustomization) {
            global $companyId;
            if (!$this->name)  { log::error(errors::$noNameOnTypeCreate);  die; }
            if (!$this->color) { log::error(errors::$noColorOnTypeCreate); die; }
            $parameters = array(':company'=>$companyId, ':position'=>$this->order, ':name'=>$this->name, ':color'=>$this->color);
            if (run(sql::$createType, $parameters)) {
                $type = getTypeById($this->conn->lastInsertId());
                foreach($this->getManagers() as $manager) {
                    run(sql::$createTypeManager, array(':type'=>$type->getId(), ':manager'=>$manager));
                }
                $backup = new Backup();
                $backup->setObjectId($type->getId());
                $backup->setObjectName($type->getName());
                $backup->setObjectType('Type');
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
            if (!$this->id)    { log::error(errors::$noIdOnTypeUpdate);    die; }
            if (!$this->name)  { log::error(errors::$noNameOnTypeUpdate);  die; }
            if (!$this->color) { log::error(errors::$noColorOnTypeUpdate); die; }
            $parameters = array(':id'=>$this->id, ':name'=>$this->name, ':color'=>$this->color, ':position'=>$this->order, ':company'=>$companyId);

            if (run(sql::$updateType, $parameters)) {
                run(sql::$deleteTypeManagers, array(':type'=>$this->id));
                foreach($this->getManagers() as $manager) {
                    run(sql::$createTypeManager, array(':type'=>$this->id, ':manager'=>$manager));
                }
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Type');
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
            if (run(sql::$deleteType, array(':id'=>$this->getId(), ':company'=>$companyId))) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Type');
                $backup->setAction('Delete');
                $backup->save();
                return true;
            } else {
                return false;
            }
        }
    }
}
function createTypes($returns) {
    $types = array();
    foreach($returns as $return) {
        $type = new Type($return);
        array_push($types, $type);
    }
    return $types;
}
function getTypes() {
    //global $gottenTypes;
    //if (isset($gottenTypes)) {
    //    return $gottenTypes;
    //}
    global $companyId;
    $parameters = array(':company' => $companyId);
    $stmt = run(sql::$getTypes, $parameters);
    $types = createTypes($stmt->fetchAll(PDO::FETCH_ASSOC));
    //$gottenTypes = $types;
    return $types;
}
function getTypeById($id) {
    $types = getTypes();
    foreach($types as $type) {
        if ($type->getId() == $id) {
            return $type;
        }
    }
    return false;
}
function nextTypePosition() {
    $types = getTypes();
    $maxPos = 0;
    foreach($types as $type) {
        $maxPos = ($type->getOrder() > $maxPos) ? $type->getOrder() : $maxPos;
    }
    return $maxPos + 1;
}




function saveTypePost($datas) {
    $order = 1;
    $types = getTypes();
    $datas = json_decode($datas['type']);
    if (count($datas) < 1) {
        error_log('more than 0 statuses required');
        http_response_code(405);
        die;
    }
    foreach($types as $type) {
        $exists = false;
        foreach($datas as $data) {
            if (isset($data->{'id'})) {
                if ($type->getId() == $data->{'id'}) {
                    $exists = true;
                }
            }
        }
        if (!$exists) {
            $type->delete();
        }
    }
    foreach($datas as $data) {
        $type = new Type();
        $type->setOrder($order);
        $type->setName($data->{'name'});
        $type->setColor($data->{'color'});
        foreach($data->{'managers'} as $manager) {
            $type->addManager($manager);
        }
        if ($data->{'id'}) {
            $type->setId($data->{'id'});
            $type->update();
        } else {
            $type->create();
        }
        $order++;
    }

}