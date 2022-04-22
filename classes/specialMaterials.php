<?php

class SpecialMaterial {
    private $id;
    private $jobId;
    private $name;
    private $inventory;
    private $nonInventory;
    private $pricePerUnit;

    public function getId() {
        return $this->id;
    }
    public function getJobId() { 
        return $this->jobId; 
    }
    public function getName($escaped = NULL) {
        return ($escaped) ? $this->name : noHTML($this->name);
    }
    public function getInventory($escaped = NULL) {
        return $this->inventory;
    }
    public function getNonInventory($escaped = NULL) {
        return $this->nonInventory;
    }
    public function getPrice($escaped = NULL) {
        return $this->pricePerUnit;
    }
    public function isSpecial() {
        return true;
    }

    public function setId($var)           { $this->id           = $var; }
    public function setJobId($var)        { $this->jobId        = $var; }
    public function setName($var)         { $this->name         = $var; }
    public function setInventory($var)    { $this->inventory    = $var; }
    public function setNonInventory($var) { $this->nonInventory = $var; }
    public function setPrice($var)        { $this->pricePerUnit = $var; }

    public function __CONSTRUCT($jmArray = NULL) {
        if ($jmArray) {
            $this->setId(           $jmArray['id']          );
            $this->setName(         $jmArray['name']        );
            $this->setJobId(        $jmArray['job']         );
            $this->setInventory(    $jmArray['inventory']   );
            $this->setNonInventory( $jmArray['nonInventory']);
            $this->setPrice(        $jmArray['pricePerUnit']);
        }
    }

    public function create() {
        $parameters = array(
            ':name'         => $this->getName(true),
            ':job'          => $this->getJobId(),
            ':inventory'    => $this->getInventory(),
            ':nonInventory' => $this->getNonInventory(),
            ':pricePerUnit' => $this->getPrice()
        );
        run(sql::$createSpecialMaterial, $parameters);
    }
    public function update() {
        $parameters = array(
            ':id'           => $this->getId(),
            ':inventory'    => $this->getInventory(),
            ':nonInventory' => $this->getNonInventory(),
            ':pricePerUnit' => $this->getPrice()
        );
        run(sql::$updateSpecialMaterial, $parameters);

    }
    public function delete() {
        run(sql::$deleteSpecialMaterial, array(':id'=>$this->getId(), ':job' => $this->getJobId()));
    }

}

function createSpecialMaterials($returns) {
    $materials = array();
    foreach($returns as $data) {
        $material = new SpecialMaterial($data);
        array_push($materials, $material);
    }
    return $materials;
}

function getSpecialMaterialsByJob($jobId) {
    $parameters = array(':job' => $jobId);
    $returns    = run(sql::$getSpecialMaterialsByJob, $parameters);
    $materials  = createSpecialMaterials($returns);
    return $materials;
}