<?php
class WorkType {
    private $conn;
    private $id;
    private $name;
    private $default = false;
    
    public function setId(  $var) { $this->id   = $var; }
    public function setName($var) { $this->name = $var; }
    //public function setAsDefault()   { $this->default = true;  }
    //public function setNotDefault()  { $this->default = false; }
    
    public function getName($local = NULL) { return (!$local) ? noHTML($this->name) : $this->name; }
    public function getId()                { return $this->id; }

    public function __CONSTRUCT($workArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($workArray) {
            $this->id   = $workArray['id'];
            $this->name = $workArray['name'];
            //if ($workArray['default']) {
            //    $this->setAsDefault();
            //}
        }
    }

    public function create() {
        global $companyId;
        $parameters = array(
            ':name' => $this->name,
            ':company' => $companyId
        );
        if (run(sql::$createWorkType, $parameters)) {
            return true;
        } return false;
    }
    public function update() {
        global $companyId;
        $parameters = array(
            ':id' => $this->id,
            ':name' => $this->name,
            ':company' => $companyId
        );
        if (run(sql::$updateWorkType, $parameters)) {
            return true;
        } return false;
    }

    public function delete() {
        global $companyId;
        $parameters = array(
            ':id' => $this->id,
            ':company' => $companyId
        );
        if (run(sql::$deleteWorkType, $parameters)) {
            return true;
        } return false;
    }
}

function getWorkTypes() {
    global $companyId;
    $parameters = array(':company' => $companyId);
    $stmt = run(sql::$getWorkTypes, $parameters);
    $workTypes = array();
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $arr) {
        array_push($workTypes, new WorkType($arr));
    }
    return $workTypes;
}

function saveWorkTypePost($datas) {
    $order = 1;
    $workTypes = getWorkTypes();
    $datas = json_decode($datas['workType']);
    if (count($datas) < 1) {
        error_log('more than 0 work types required');
        http_response_code(405);
        die;
    }
    foreach($workTypes as $workType) {
        $exists = false;
        foreach($datas as $data) {
            if (isset($data->{'id'})) {
                if ($workType->getId() == $data->{'id'}) {
                    $exists = true;
                }
            }
        }
        if (!$exists) {
            $workType->delete();
        }
    }
    foreach($datas as $data) {
        $workType = new workType();
        $workType->setName($data->{'name'});
        if ($data->{'id'}) {
            $workType->setId($data->{'id'});
            $workType->update();
        } else {
            $workType->create();
        }
        $order++;
    }

}