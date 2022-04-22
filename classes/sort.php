<?php
require_once "/var/www/globals.php";

class Sort {
    public $workers = array();
    public $statuses = array();
    public $types = array();
    public $clients = array();
    public $removedStatuses = array();
    public $startDate; //placeholder
    public $endDate;   //placeholder
    public $search;
    public function isNull() {
        if ($this->getWorkers() || $this->getStatuses() || $this->getTypes() || $this->getClients()) {
            return false;
        }
        return true;
    }
    public function getWorkers()       { return $this->workers;  }
    public function getStatuses()      { return $this->statuses; }
    public function getTypes()         { return $this->types;    }
    public function getClients()       { return $this->clients;  }
    public function setStatuses($var)  { if (is_array($var)) { $this->statuses = $var; } }
    public function setTypes($var)     { if (is_array($var)) { $this->types    = $var; } }
    public function setWorkers($var)   { if (is_array($var)) { $this->workers  = $var; } }
    public function addWorker($var)    { if (is_numeric($var)) { array_push($this->workers,        $var); } else { return false; } }
    public function addStatus($var)    { if (is_numeric($var)) { array_push($this->statuses,       $var); } else { return false; } }
    public function addtype($var)      { if (is_numeric($var)) { array_push($this->types,          $var); } else { return false; } }
    public function addClient($var)    { if (is_numeric($var)) { array_push($this->clients,        $var); } else { return false; } }
    public function removeStatus($var) { if (is_numeric($var)) { array_push($this->removedStatuses,$var); } else { return false; } }
    public function search($var)       { $this->search = $var; }
    public function run($unsortedjobs, $pageLength = NULL, $page = NULL) {
        global $permissions2;
        global $currentUser;
        $jobs = array();
        foreach($unsortedjobs as $job) {
            $okay = true;
            if (!$permissions2->jobReadAny) {
                $okay = false;
                if ($job->getWorkers() != NULL) {
                    foreach($job->getWorkers() as $worker) {
                        if ($worker->getId() == $currentUser->getId()) {
                            $okay =  true;
                            break;
                        }
                    } 
                }
            }
            if ($okay) {
                if ($this->statusOkay($job) && 
                    $this->typeOkay($job) && 
                    $this->workerOkay($job) &&
                    $this->clientOkay($job)) {
                        //if ($job->isStarred()) {
                        //    array_unshift($jobs, $job);
                        //} else {
                            array_push($jobs, $job);
                        //}
                }
            }
        }
        return $jobs;
    }
    public function clientOkay($job) {
        if (!$this->clients) { return true; }
        if ($job->getClient()) {
            if (in_array(($job->getClient())->getId(), $this->clients)) {
                return true;
            } return false;
        } else {
            return false;
        }
    }
    private function statusOkay($job) {
        if (!$this->statuses && !$this->removedStatuses) { return true; }
        if ($this->removedStatuses) {
            if (in_array(($job->getStatus())->getId(), $this->removedStatuses)) {
                return false;
            } else {
                return true;
            }
        } elseif ($this->statuses) {
            if (in_array(($job->getStatus())->getId(), $this->statuses)) {
                return true;
            } return false;
        } return true;
    }
    private function typeOkay($job) {
        if (!$this->types) { return true; }
        if (in_array(($job->getType())->getId(), $this->types)) {
            return true;
        } return false;
    }
    private function workerOkay($job) {
        if (!$this->workers) { return true; }
        if ($job->getWorkers()) {
            foreach($job->getWorkers() as $worker) {
                if (in_array($worker->getId(), $this->workers)) {
                    return true;
                } 
            }
        }
        if ($job->getManagers()) {
            foreach($job->getManagers() as $worker) {
                if (in_array($worker->getId(), $this->workers)) {
                    return true;
                } 
            } 
        }
        return false;
    }
}