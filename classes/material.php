<?php
class Material {
    private $conn;
    private $id;
    private $number;
    private $description;
    private $inventory;
    private $price;
    
    public function setId(         $var) { $this->id          = $var; }
    public function setDescription($var) { $this->description = $var; }
    public function setInventory(  $var) { $this->inventory   = $var; }
    public function setPrice(      $var) { $this->price       = $var; }
    public function setNumber(     $var) { $this->number      = $var; }
    
    public function getId(                      ) { return $this->id; }
    public function getNumber(     $local = NULL) { return (!$local) ? noHTML($this->number)      : $this->number;      }
    public function getDescription($local = NULL) { return (!$local) ? noHTML($this->description) : $this->description; }
    public function getInventory(  $local = NULL) { return (!$local) ? noHTML($this->inventory)   : $this->inventory;   }
    public function getPrice(      $local = NULL) { return $this->price; }
    
    public function __CONSTRUCT($materialArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($materialArray) {
            $this->id =           $materialArray[materialData::$id];
            $this->setNumber(     $materialArray[materialData::$number]     );
            $this->setDescription($materialArray[materialData::$description]);
            $this->setInventory(  $materialArray[materialData::$inventory]  );
            $this->setPrice(      $materialArray[materialData::$price]      );
        }
    }

    public function create() {
        global $permissions2;
        if (!$permissions2->materialCreate) {
            return false;
        }
        global $companyId;
        if (!$this->validNum())         { log::error(errors::$invalidNumber);      return false; }
        if (!$this->validDescription()) { log::error(errors::$invalidDescription); return false; }
        if (!$this->validInventory())   { log::error(errors::$invalidInventory);   return false; } 
        if (!$this->validPrice())       { log::error(errors::$invalidPrice);       error_log('start'); return false; }
        $parameters = array();
        $parameters[materialData::$number]      = $this->number;
        $parameters[materialData::$description] = $this->description;
        $parameters[materialData::$inventory]   = $this->inventory;
        $parameters[materialData::$price]       = $this->price;
        $parameters[':company']                 = $companyId;
        if (run(sql::$createMaterial, $parameters)) {
            $material = getMaterialById($this->conn->lastInsertId());
            $backup = new Backup();
            $backup->setObjectId($this->getId());
            $backup->setObjectName($this->getDescription());
            $backup->setObjectType('Material');
            $backup->setAction('Create');
            $backup->save();
            return $material;
        }
        log::error(errors::$materialCreateFailure);
        return false;
    }
    //I 
    public function update() {
        global $permissions2;
        if (!$permissions2->materialsAdd) {
            return false;
        }
        global $companyId;
        $old = getMaterialById($this->getId());
        $tbl='table';$col='column';$data='data';$queryOptions = array();$tx = new Transaction();
        if ($old->getNumber(true) != $this->getNumber(true)) { 
            if (!$this->validNum()) { 
                log::error(errors::$invalidNumber); 
                return false; 
            } else {
                array_push($queryOptions,array($col=>'number', $data=>$this->number )); 
            }
        }
        if ($old->getDescription(true) != $this->getDescription(true) && $permissions2->materialUpdate) { 
            if (!$this->validDescription()) { 
                log::error(errors::$invalidDescription); 
                return false; 
            } else {
                array_push($queryOptions,array($col=>'description', $data=>$this->description)); 
            }
        }
        if ($old->getInventory(true) != $this->getInventory(true)) { 
            if (!$this->validInventory())   { 
                log::error(errors::$invalidInventory);   
                return false; 
            } else {
                array_push($queryOptions,array($col=>'inventory', $data=>$this->inventory)); 
            }
        }
        if ($old->getPrice(true) != $this->getPrice(true) && $permissions2->materialUpdate) {
            if (!$this->validPrice()) {
                log::error(errors::$invalidPrice); 
                return false; 
            } else {
                array_push($queryOptions,array($col=>'price', $data=>$this->getPrice(true))); 
            }
        }
        
        foreach($queryOptions as $option) {
            $query = "UPDATE materials SET {$option[$col]} = :{$data} WHERE id = :id and company = {$companyId}";
            $parameters = array(':'.$data=>$option[$data], ':id'=>$this->getId());
            $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
        }
        if ($tx->run()) {
            $backup = new Backup();
            $backup->setObjectId($this->getId());
            $backup->setObjectName($this->getDescription());
            $backup->setObjectType('Material');
            $backup->setAction('Update');
            $backup->save();
            return true;
        } else {
            log::error(errors::$materialUpdateFailure);
            return false;
        }
    }

    public function delete() {
        global $permissions2;
        if (!$permissions2->materialDelete) {
            return false;
        }
        global $companyId;
        $parameters = array(
            ':id'      =>$this->id,
            ':company' => $companyId
        );
        if (run(sql::$deleteMaterial, $parameters)) {
            return true;
        } return false;
    }

    //validity checkers
    public function validPrice() {
        if (is_numeric($this->getPrice(true))) {
            return true;
        } return false;
    }
    public function validInventory() {
        if (is_numeric($this->inventory)) {
            return true;
        } return false;
    }
    public function validDescription() {
        if (isset($this->description)) {
            return true;
        } return false;
    }
    public function validNum() {
        global $companyId;
        $parameters = array(
            ':number'  => $this->number,
            ':company' => $companyId
        );
        if (is_numeric($this->number)) {
            $stmt = run(sql::$checkMaterialNum, $parameters);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (($data['counted']) > 0) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }
}
function nextMaterialNumber() {
    global $companyId;
    $stmt = run(sql::$nextMaterialNum, array(':company'=>$companyId));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    //var_dump($data);
    echo $data['max']+1;
    return $data['max'] + 1;
}
function constructMaterials($materialArray) {
    $materials = array();
    foreach($materialArray as $material) {
        array_push($materials, new Material($material));
    }
    return $materials;
}
function constructMaterial($materialArray) {
    return new Material($materialArray);
}

function getMaterials($start = NULL, $end = NULL) {
    global $companyId;
    if ($start && $end) {
        //$end = $end-$start;
        $stmt = run(sql::$getLimitedMaterials, array(':company'=>$companyId, ':start'=>$start, ':end'=>$end));
    } else {
        $stmt = run(sql::$getMaterials, array(':company'=>$companyId));
    }
    return constructMaterials($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getMaterialById($id) {
    global $companyId;
    $stmt = run(sql::$getMaterialsById, array(':company'=>$companyId, ':id'=>$id));
    return constructMaterial($stmt->fetch(PDO::FETCH_ASSOC));
}


function getMaterialsBySearch($search) {
    global $databaseConnection;
    global $companyId;
    $conn = $databaseConnection->conn;
    $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, true );
    $parameters = array(':company'=>$companyId, ':search'=>$search);
    $stmt = $conn->prepare(sql::$searchMaterials);
    $stmt->execute($parameters);
    return constructMaterials($stmt);
}

function displayMaterials($materials) {
    global $permissions2;
    $table = new Table();
    $table->setId('materialTable');
    $table->addColumn($colNumber      = new Column('Number')     );
    $table->addColumn($colDescription = new Column('Description'));
    $table->addColumn($colInventory   = new Column('Inventory')  );
    $table->addColumn($colPrice       = new Column('Price')      );
    $table->addColumn($colActions     = new Column('Actions')    );
    
    $editButton = new Button();
    $editButton->setStyle('background-color:#5bc0de;color:white;');
    $editButton->setText('<span class="fa fa-pencil"></span>');
    $deleteButton = new Button();
    $deleteButton->setStyle('background-color:#f44336;color:white;');
    $deleteButton->setText('<span class="fa fa-trash-o"></span>');

    foreach($materials as $material) {
        $row = new Row();
        $num   = new Paragraph($material->getNumber()     , 'matNum'  .$material->getId());
        $desc  = new Paragraph($material->getDescription(), 'matDesc' .$material->getId());
        $invt  = new MaterialInventory($material          , 'matInvt' .$material->getId());
        $price = new Paragraph($material->getPrice()      , 'matPrice'.$material->getId());
        $buttons = array();
        $row->addData($colNumber,      $num);
        $row->addData($colDescription, $desc);
        $row->addData($colInventory,   $invt, 'nowrap="nowrap"');
        $row->addData($colPrice,       $price);
        $editButton->setOther("onclick='materialEdit({$material->getId()})'");   
        $deleteButton->setOther("onclick='materialDeleteId={$material->getId()};$(\"#deleteMaterialModal\").modal(\"show\");'");

        if ($permissions2->materialUpdate) {
            array_push($buttons, clone $editButton);
        }
        if ($permissions2->materialCreate) {
            array_push($buttons, clone $deleteButton);
        }
        $row->addData($colActions, $buttons, 'nowrap="nowrap"');
        $table->addRow($row);
    }
    $table->display();
}