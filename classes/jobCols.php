<?php
require_once '/var/www/globals.php';
require_once '/var/www/classes/userType.php';
require_once '/var/www/classes/data.php';
class JobColumns {
    //job permissions
    public $number;
    public $name;
    public $status;
    public $client;
    public $location;
    public $type;
    public $fieldworker;
    public $description;
    public $ponumber;
    public $bid;

    public $edit;
    public $view;
    public $dispatch;
    public $deletes;
    public $download;
    public $daily;
    public function __CONSTRUCT( $colsArray = NULL) {  
        if ($colsArray) {
            $colsArray = array_reverse($colsArray);
            $colsArray = array_pop($colsArray);
            $this->number      = ($colsArray[colsData::$number]      == 1) ? true : false;
            $this->name        = ($colsArray[colsData::$name]        == 1) ? true : false;
            $this->status      = ($colsArray[colsData::$status]      == 1) ? true : false;
            $this->client      = ($colsArray[colsData::$client]      == 1) ? true : false;
            $this->location    = ($colsArray[colsData::$location]    == 1) ? true : false;
            $this->type        = ($colsArray[colsData::$type]        == 1) ? true : false;
            $this->fieldworker = ($colsArray[colsData::$fieldworker] == 1) ? true : false;
            $this->description = ($colsArray[colsData::$description] == 1) ? true : false;
            $this->ponumber    = ($colsArray[colsData::$ponumber]    == 1) ? true : false;
            $this->bid         = ($colsArray[colsData::$bid]         == 1) ? true : false;
            $this->view        = ($colsArray[colsData::$view]        == 1) ? true : false;
            $this->edit        = ($colsArray[colsData::$edit]        == 1) ? true : false;
            $this->dispatch    = ($colsArray[colsData::$dispatch]    == 1) ? true : false;
            $this->deletes     = ($colsArray[colsData::$deletes]     == 1) ? true : false;
            $this->download    = ($colsArray[colsData::$download]    == 1) ? true : false;
            $this->daily       = ($colsArray[colsData::$daily]       == 1) ? true : false;
        }
    }
}


function getJobColumns() {
    global $currentUser;
    $parameters = array(':user' => $currentUser->getId());
    $stmt = run(sql::$getJobColumns, $parameters);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) < 1) {
        return createJobColumn();
    }
    $columns = new JobColumns($data);
    return $columns;
}

function createJobColumn() {
    global $currentUser;
    $type = ($currentUser->getType())->getId();
    run(sql::$jobColCreateAdministrator, array(':user'=>$currentUser->getId()));
    return getJobColumns();
}
