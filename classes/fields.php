<?php
class Fields {
    public $id;
    public $jobClient      = false;
    public $jobLocation    = false;
    public $jobWorkers     = false;
    public $jobDescription = false;
    public $jobPoNumber    = false;
    public $jobBid         = false;
    public $jobNotes       = false;
    public $jobFiles       = false;

    public $clientName    = false;
    public $clientEmail   = false;
    public $clientPhone   = false;
    public $clientAddress = false;

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
        $sql = 'INSERT INTO fields (';
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
            $backup->setObjectType('Field');
            $backup->setAction('Create');
            $backup->save();
            return $id;
        } return false;
    }

    public function update() {
        $parameters = $this->getVariables();
        unset($parameters['id']);
        $sql = 'UPDATE fields SET ';
        foreach($parameters as $key => $param) {
            $sql .= $key . ' = :' . $key . ', ';
            unset($parameters[$key]);
            $parameters[':'.$key] = $param;
        }
        $sql = substr($sql, 0, -1);
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id = :id';
        $parameters[':id'] = $this->id;
        if (run($sql, $parameters)) {
            $backup = new Backup();
            $backup->setObjectId($this->id);
            $backup->setObjectName($this->id);
            $backup->setObjectType('Field');
            $backup->setAction('Update');
            $backup->save();
            return true;
        } return false;
    }
    
    public function delete() {
        if (run(sql::$deleteField, array(':id'=>$this->id))) {
            $backup = new Backup();
            $backup->setObjectId($this->id);
            $backup->setObjectName($this->id);
            $backup->setObjectType('Field');
            $backup->setAction('Delete');
            $backup->save();
            return true;
        } return false;
    }

    public function __CONSTRUCT( $columnArray = NULL) {  
        if ($columnArray) {
            $vars = get_class_vars(get_class($this));
            if (!isset($columnArray[array_key_last($vars)])) {
                $columnArray = array_reverse($columnArray);
                $columnArray = array_pop($columnArray);
            }
            if (isset($columnArray["fields"])) {
                $this->id = $columnArray["fields"];
            }
            if (isset($columnArray["id"])) {
                $this->id = $columnArray["id"];
            }
            foreach($vars as $key => $var) {
                if ($key != 'id') {
                    if (isset($columnArray[$key])) {
                        $this->{$key} = $columnArray[$key];
                    }
                }
            }
        }
    }
}

function getFieldById($id) {
    $parameters = array(':id'=>$id);
    $stmt = run(sql::$getFieldById, $parameters);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $field = new Fields($data);
    return $field;
}



/*
function deleteField($id) {
    if (run(sql::$deleteField, array(':id'=>$id))) {
        return true;
    } return false;
}

/*
function createFields($field) {
    $parameters = array(
        'jobClient'     => $field->jobClient,
        'jobLocation'   => $field->jobLocation,
        'jobWorkers'    => $field->jobWorkers,
        'jobDescription'=> $field->jobDescription,
        'jobPoNumber'   => $field->jobPoNumber,
        'jobBid'        => $field->jobBid,
        'jobNotes'      => $field->jobNotes,
        'jobFiles'      => $field->jobFiles,
        'clientName'    => $field->clientName,
        'clientEmail'   => $field->clientEmail,
        'clientPhone'   => $field->clientPhone,
        'clientAddress' => $field->clientAddress
    );
    $sql = 'INSERT INTO fields (';
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

function updateFields($field, $id) {
    $parameters = array(
        'jobClient'     => $field->jobClient,
        'jobLocation'   => $field->jobLocation,
        'jobWorkers'    => $field->jobWorkers,
        'jobDescription'=> $field->jobDescription,
        'jobPoNumber'   => $field->jobPoNumber,
        'jobBid'        => $field->jobBid,
        'jobNotes'      => $field->jobNotes,
        'jobFiles'      => $field->jobFiles,
        'clientName'    => $field->clientName,
        'clientEmail'   => $field->clientEmail,
        'clientPhone'   => $field->clientPhone,
        'clientAddress' => $field->clientAddress
    );

    $sql = 'UPDATE fields SET ';
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

function deleteField($id) {
    if (run(sql::$deleteField, array(':id'=>$id))) {
        return true;
    } return false;
}
*/