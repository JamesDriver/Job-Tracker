<?php

require_once "/var/www/globals.php";
require_once "/var/www/classes/status.php";
require_once "/var/www/classes/type.php";
require_once "/var/www/classes/client.php";
require_once "/var/www/classes/file.php";
require_once "/var/www/classes/jobCols.php";
require_once "/var/www/classes/jobMaterial.php";
//require_once "/var/www/design/mail.php";
//require_once "/var/www/design/table.php";
//require_once "/var/www/design/form.php";
require_once '/var/www/fpdf/fpdf.php';

class Job {
    public $conn;
    public $preJn;
    public $id;
    public $number;
    public $cleanNumber;
    //object
    public $creator;
    public $name;
    // class reference
    public $client;
    // class reference
    public $workers = array();
    // class reference
    public $type;
    // class reference
    public $status;
    public $location;
    public $description;
    public $poNumber;
    public $createDate;
    public $bid;
    public $notes;
    public $files = array();
    public $changeOrder;
    public $fileSize;
    public $materials = array();
    public $filePermissions = array();
    public $managers = array();
    public $starred = false;
    private $openInspections;
    // no files on job, not worth it for processing cost, and not shown most of time. better to
    //double call db on individual job load, rather than have to handle for all files for all jobs
    public function getId(             ) { if($this->id)                { return $this->id;              } else { return false; } }
    //public function getNumber(         ) { if($this->number)            { return $this->number;          } else { return false; } }
    public function getNumber() {
    if($this->number) {
        global $companyId;
        if ($companyId == 1 || $companyId == 3) {
            if ($this->id == 32618) {
                return 'Office';
            } elseif ($this->id == 32619) {
                return 'Shop';
            } elseif ($this->id == 32801) {
                return 'Overhead';
            } elseif ($this->id == 32802) {
                return 'Informal Training';
            } elseif ($this->id == 32803) {
                return 'Inventory';
            } elseif ($this->id == 32804) {
                return 'Marketing/Trade Shows';
            } elseif ($this->id == 32805) {
                return 'Office Maint';
            } elseif ($this->id == 32806) {
                return 'Test Equip Maint';
            } elseif ($this->id == 32807) {
                return 'Vehicle Maint';
            } elseif ($this->id == 32808) {
                return 'Warehouse Maint';
            }
        }
        return $this->number;
        } else {
            return false;
        }
    }
    public function getCreator(        ) { if($this->creator)           { return $this->creator;         } else { return false; } }
    public function getClient(         ) { if($this->client)            { return $this->client;          } else { return false; } }
    public function getWorkers(        ) { return $this->workers;  }
    public function getManagers(       ) { return $this->managers; }
    public function getType(           ) { if($this->type)              { return $this->type;            } else { return false; } }
    public function getStatus(         ) { if($this->status)            { return $this->status;          } else { return false; } }
    public function getName(       $local = NULL) { if($this->name)        { return (!$local) ? noHTML($this->name)        : $this->name;        } else { return false; } }
    public function getLocation(   $local = NULL) { if($this->location)    { return (!$local) ? noHTML($this->location)    : $this->location;    } else { return false; } }
    public function getDescription($local = NULL) { if($this->description) { return (!$local) ? noHTML($this->description) : $this->description; } else { return false; } }
    public function getPoNumber(   $local = NULL) { if($this->poNumber)    { return (!$local) ? noHTML($this->poNumber)    : $this->poNumber;    } else { return false; } }
    public function getBid(        $local = NULL) { if($this->bid)         { return (!$local) ? noHTML($this->bid)         : $this->bid;         } else { return false; } }
    public function getNotes(      $local = NULL) { if($this->notes)       { return (!$local) ? noHTML($this->notes)       : $this->notes;       } else { return false; } }
    public function getCreateDate(     ) { if($this->createDate)        { return $this->createDate;      } else { return false; } }
    public function getFiles(          ) { if($this->files)             { return $this->files;           } else { return false; } }
    public function getChangeOrder(    ) { if($this->changeOrder)       { return $this->changeOrder;     } else { return false; } }
    public function getCleanNumber(    ) { if($this->cleanNumber)       { return $this->cleanNumber;     } else { return false; } }
    public function getFileSize(       ) { if($this->fileSize)          { return $this->fileSize;        } else { return false; } }
    public function getMaterials(      ) { if($this->materials)         { return $this->materials;       } else { return false; } }
    public function getFilePermissions() { if($this->filePermissions)   { return $this->filePermissions; } else { return false; } }
    public function isStarred(         ) { return $this->starred; }
    public function canSetStatus($status) {
        if (!$status->inspectionsRequired()) {
            return true;
        }
        if ($this->openInspections > 0) {
            return false;
        }
        return true;
    }


    public function setId($var)             { $this->id         = $var; return true; }
    public function setNumber($var)         { $this->number     = ($this->getChangeOrder()) ? ($this->preJn) . $var . '-' . $this->getChangeOrder() : ($this->preJn) . $var; return true; }
    private function setCreateDate($var)    { $this->createDate = $var; return true; }
    public function setCreator($var)        { $this->creator    = $var; return true; }
    public function setType($var)           { $this->type       = $var; return true; }
    public function setClient($var)         { if (is_a($var, 'Client')) { $this->client = $var; return true; } else { return false; } }
    public function setName($var)           { if (/*!preg_match('/"/',$var) && */strlen($var) < 250) { $this->name        = $var; return true; } else { return false; } }
    //maybe handle these differently. probably should be private? ^
    public function setLocation($var)       { if (/*!preg_match('/"/',$var) && */strlen($var) < 250) { $this->location    = $var; return true; } else { return false; } }
    public function setPoNumber($var)       { if (/*!preg_match('/"/',$var) && */strlen($var) < 250) { $this->poNumber    = $var; return true; } else { return false; } }
    public function setBid($var)            { if (/*!preg_match('/"/',$var) && */strlen($var) < 250) { $this->bid         = $var; return true; } else { return false; } }
    public function setDescription($var)    { if (!isset($var[20000]))                           { $this->description = $var; return true; } else { return false; } }
    public function setNotes($var)          { if (!isset($var[20000]))                           { $this->notes       = $var; return true; } else { return false; } }
    public function addWorker($var)         { array_push($this->workers, $var); }
    public function addFile($var)           { array_push($this->files, $var);   }
    public function removeWorkers()         { $this->workers = array(); }
    public function removeManagers()        { $this->managers = array(); }
    public function addManager($var)        { array_push($this->managers, $var); }
    public function setChangeOrder($var)    { $this->changeOrder = $var; return true; }
    public function setCleanNumber($var)    { $this->cleanNumber = $var; return true; }
    public function setFileSize($var)       { $this->fileSize    = $var; return true; }
    public function setMaterials($var)      { $this->materials   = $var; return true; }
    public function addMaterial($var)       { array_push($this->materials, $var); return true; }
    public function addFilePermissions($name, $var){ $this->filePermissions[$name] = $var; return true; }
    public function star()   { $this->starred = true; }
    public function unStar() { $this->starred = false; }

    public function setStatus($var) {
        //if ($var instanceof Status) {
        //    $status = $var;
        //} elseif (is_int($var)) {
        //    $status = getStatusById($var);
        //} else {
        //    error_log('status does not exist');
        //    error_log(print_r($var, true));
        //    return false;
        //}
        //global $currentUser;
        //if ($status->canBeSetBy($currentUser)) {
        //    if ($this->canSetStatus($status)) {
                $this->status = $var;
                return true;
        //    }
        //}
        //return false;
    }

    public function dispatch() {

    }


    public function containsWorker($user) {
        $workers = $this->getWorkers();
        if ($workers) {
            foreach($workers as $worker) {
                if ($worker->getId() == $user->getId()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function __CONSTRUCT($jobArray = NULL) {
        global $preJn;
        $this->preJn = ($preJn) ? $preJn : '';
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($jobArray)
        {
            if (isset($jobArray[jobData::$changeOrder])) {
                if (!$this->setChangeOrder($jobArray[jobData::$changeOrder]))
                    { echo jobData::$constructFailure; die;
                }
            }
            if (!$this->setId(         $jobArray[jobData::$id])        ) { echo jobData::$constructFailure; die;}
            if (!$this->setCreateDate( $jobArray[jobData::$createDate])) { echo jobData::$constructFailure; die;}
            if (!$this->setNumber(     $jobArray[jobData::$number])    ) { echo jobData::$constructFailure; die;}
            if (!$this->setCleanNumber($jobArray[jobData::$number])    ) { echo jobData::$constructFailure; die;}
            if (!$this->setName(       $jobArray[jobData::$name])      ) { echo jobData::$constructFailure; die;}
            //these are required ^
            if (isset($jobArray[jobData::$location]))    { if (!$this->setLocation(   $jobArray[jobData::$location])       ) { echo jobData::$constructFailure; die; } }
            if (isset($jobArray[jobData::$poNumber]))    { if (!$this->setPoNumber(   $jobArray[jobData::$poNumber])       ) { echo jobData::$constructFailure; die; } }
            if (isset($jobArray[jobData::$bid]))         { if (!$this->setBid(        $jobArray[jobData::$bid])            ) { echo jobData::$constructFailure; die; } }
            if (isset($jobArray[jobData::$description])) { if (!$this->setDescription($jobArray[jobData::$description])    ) { echo jobData::$constructFailure; die; } }
            if (isset($jobArray[jobData::$notes]))       { if (!$this->setNotes(      $jobArray[jobData::$notes])          ) { echo jobData::$constructFailure; die; } }
            if (isset($jobArray['openInspections'])) { $this->openInspections = $jobArray['openInspections']; }
        }
    }
    public function create() {
        $functionRun = new FunctionRun(functions::$createJob);
        global $permissions2;
        if ($permissions2->jobCreate) {
            $paramters = array();
            if (!$this->getStatus())   { $var = $functionRun->error(errors::$noJobStatusOnCreate); die;}
            if (!$this->getType())     { $var = $functionRun->error(errors::$noJobTypeOnCreate);   die;}
            if (!$this->getName(true)) { $var = $functionRun->error(errors::$noJobNameOnCreate);   die;}
            global $currentUser; global $companyId;
            $transaction = new Transaction();
            $parameters = array();
            $parameters[':'.jobData::$number]     = ($this->getCleanNumber()) ? $this->getCleanNumber() : ((nextJobNumber()) ? nextJobNumber() : 1000);
            $parameters[':'.jobData::$name]       = ($this->getName(true));
            $parameters[':'.jobData::$creator]    = $currentUser->getId();
            $parameters[':'.jobData::$type]       = ($this->getType())->getId();
            $parameters[':'.jobData::$status]     = ($this->getStatus())->getId();
            $parameters[':'.jobData::$createDate] = date(format::$time);
            $parameters[':'.jobData::$company]    = $companyId;
            if ($this->getChangeOrder()) {
                $parameters[':'.jobData::$changeOrder] = $this->getChangeOrder();
            }
            $transaction->addQuery(array('query'=>sql::$txGetJobId));
            if ($this->getChangeOrder()) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobChangeOrder,'parameters'=>$parameters));
            } else {
                $transaction->addQuery(array('query'=>sql::$txCreateJob, 'parameters'=>$parameters));
            }
            if ($var = $this->getPoNumber(true)) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobPO, 'parameters'=>array(':'.jobData::$poNumber => $var)));
            }
            if ($var = $this->getLocation(true)) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobLocation, 'parameters'=>array(':'.jobData::$location => $var)));
            }
            if ($var = $this->getNotes(true)) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobNote, 'parameters'=>array(':'.jobData::$notes => $var)));
            }
            if ($var = $this->getbid(true)) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobBid, 'parameters'=>array(':'.jobData::$bid => $var)));
            }
            if ($var = $this->getDescription(true)) {
                $transaction->addQuery(array('query'=>sql::$txCreateJobDescription,'parameters'=>array(':'.jobData::$description => $var)));
            }
            if ($var = $this->getClient()) {
                $transaction->addQuery(array('query'=>sql::$txCreateClientJob, 'parameters'=>array(':'.jobData::$client => $var->getId())));
            }
            if ($var = $this->getWorkers()) {
                foreach ($var as $worker) {
                    $transaction->addQuery(array('query'=>sql::$txCreateUserJob, 'parameters'=>array(':'.userJobData::$user => $worker->getId())));
                }
            }
            if ($var = $this->getManagers()) {
                foreach ($var as $manager) {
                    $transaction->addQuery(array('query'=>sql::$txCreateJobManager, 'parameters'=>array(':'.userJobData::$user => $manager->getId())));
                }
            }
            if ($var = $this->getFiles()) {
                foreach ($var as $file) {
                    //if ($file->getRequiredLevel() < ($currentUser->getType())->getId()) {$file->setRequiredLevel(($currentUser->getType())->getId()); }
                    $filePermissions = $this->getFilePermissions();
                    $filePermissions = $filePermissions[$file->getId()];
                    $transaction->addQuery(array('query'=>sql::$txCreateJobFile, 'parameters'=>array(':'.jobData::$file => $file->getId())));
                    foreach($filePermissions as $fp) {
                        $transaction->addQuery(array('query'=>sql::$txCreateJobFilePermission, 'parameters'=>array(':'.jobData::$file => $file->getId(), ':allowed' => $fp)));
                    }
                }
            }
            if ($transaction->run()) {
                $job = getJobById(getLastInsertedJob());
                $backup = new Backup();
                $backup->setObjectId($job->getId());
                $backup->setObjectName($job->getNumber());
                $backup->setObjectType('Job');
                $backup->setAction('Create');
                $backup->save();
                $functionRun->log();
                return true;
            } else {
                $functionRun->error(errors::$jobCreateFail);
                return false;
            }
            return true;
        } else {
            $functionRun->error(errors::$permissionError);
            return false;
        }
    }
    public function update() {
        global $permissions2;
        global $currentUser;
        if ($permissions2->jobUpdate && canViewJob($this)) {
            $functionRun = new FunctionRun(functions::$updateJob);
            if (!$this->getName(true))    { $this->setName('tmp name'); }//$functionRun->error(errors::$jobUpdateFailName);   }
            if (!$this->getNumber())  { $functionRun->error(errors::$jobUpdateFailNumber); }
            if (!$this->getStatus())  { $functionRun->error(errors::$jobUpdateFailStatus); }
            if (!$this->getType())    { $functionRun->error(errors::$jobUpdateFailType);     }
            $old = getJobById($this->id);
            //make sure old exists, else throw error, at some point.
            $queryOptions = array();
            $queryOptionsNoDuplicate = array();
            $tx = new Transaction();
            $wCol='whereColumn';$wVal='whereVal';$tbl='table';$col='column';$data='data';
            if ($old->getName(true) != $this->getName(true)) {
                array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'job', $col=>jobData::$name, $data=>$this->name ));
            }
            if ($old->getStatus() != $this->getStatus()) {
                array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'job', $col=>jobData::$status, $data=>($this->getStatus())->getId()));
            }
            if ($old->getType() != $this->getType()) {
                array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'job', $col=>jobData::$type, $data=>($this->getType())->getId()));
            }
            if ($old->getBid(true) != $this->getbid(true)) {
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'jobBid', $col=>jobData::$bid, $data=>$this->getbid(true)));
            }
            if ($old->getNotes(true) != $this->getNotes(true)) {
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'jobNote', $col=>jobData::$notes, $data=>$this->getNotes(true)));
            }
            if ($old->getLocation(true) != $this->getLocation(true)) {
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'jobLocation', $col=>jobData::$location, $data=>$this->getLocation(true)));
            }
            if ($old->getDescription(true)!= $this->getDescription(true)){
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'jobDescription',$col=>jobData::$description,$data=>$this->getDescription(true)));
            }
            if ($old->getPoNumber(true) != $this->getPoNumber(true)) {
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'jobPoNumber', $col=>jobData::$poNumber, $data=>$this->getPoNumber(true)));
            }
            if ($old->getClient() != $this->getClient()) {
                array_push($queryOptions,array($wCol=>'job',$wVal=>$this->getId(),$tbl=>'clientJob', $col=>jobData::$client, $data=>($this->getClient())->getId()));
            }
            if ($old->getWorkers() !== $this->getWorkers()) {
                $tx->addQuery(array('query'=>'DELETE FROM userJob WHERE job = :job', 'parameters'=>array(':job'=>$this->getId())));
                foreach($this->getWorkers() as $worker) {
                    array_push($queryOptionsNoDuplicate,
                            array(
                            $wCol=>'job',
                            $wVal=>$this->getId(),
                            $tbl=>'userJob',
                            $col=>'user',
                            'data'=>$worker->getId()
                        )
                    );
                }
            }
            if ($old->getManagers() !== $this->getManagers()) {
                $tx->addQuery(array('query'=>'DELETE FROM jobManagers WHERE job = :job', 'parameters'=>array(':job'=>$this->getId())));
                foreach($this->getManagers() as $manager) {
                    array_push($queryOptionsNoDuplicate,
                            array(
                            $wCol=>'job',
                            $wVal=>$this->getId(),
                            $tbl=>'jobManagers',
                            $col=>'manager',
                            'data'=>$manager->getId()
                        )
                    );
                }
            }
                //don't have to handle for deleting because delete is handled on the page itself
            if ($this->getFiles() && $this->getFilePermissions()) {
                foreach( $this->getFiles() as $file ) {
                    //check for permission changes
                    $hasChanged = false;
                    $exists = false;
                    if ( $old->getFiles() ) {
                        foreach ( $old->getFiles() as $oldFile ) {
                            if ($file->getId() == $oldFile->getId()) {
                                $permissionsNew = $this->getFilePermissions()[$file->getId()];
                                $permissionsOld = getJobFileLevels($file->getId());
                                $permissionsOldClean = array();
                                foreach($permissionsNew as $key => $var) {
                                    $permissionsNew[$key] = (int)$var;
                                }
                                foreach($permissionsOld as $po) {
                                    array_push($permissionsOldClean, $po['allowed']);
                                }
                                if ($permissionsOldClean != $permissionsNew) {
                                    //update required values for existing files
                                    foreach($permissionsNew as $newPms) {
                                        if (is_array($permissionsOldClean)) {
                                            if (!in_array($newPms, $permissionsOldClean)) {
                                                $tx->addQuery(array('query'=>sql::$txCreateJobFilePermission, 'parameters'=>array(':'.jobData::$file => $file->getId(), ':allowed' => $newPms)));
                                            }
                                        }
                                    }
                                    foreach($permissionsOldClean as $oldPms) {
                                        if (is_array($permissionsNew)) {
                                            if (!in_array($oldPms, $permissionsNew)) {
                                                $tx->addQuery(array('query'=>sql::$txDeleteJobFilePermission, 'parameters'=>array(':'.jobData::$file => $file->getId(), ':allowed' => $oldPms)));
                                            }
                                        }
                                    }
                                }
                                $exists = true;
                                break;
                            }
                        }
                    }
                    if (!$exists) {
                        $permissionsNew = $this->getFilePermissions()[$file->getId()];
                        foreach($permissionsNew as $key => $var) {
                            $permissionsNew[$key] = (int)$var;
                        }
                        array_push($queryOptions,array($wCol=>'job', $wVal=>$this->getId(),$tbl=>'jobFile',$col=>jobData::$file,$data=>$file->getId()));
                        foreach($permissionsNew as $fp) {
                            $tx->addQuery(array('query'=>sql::$txCreateJobFilePermission, 'parameters'=>array(':'.jobData::$file => $file->getId(), ':allowed' => $fp)));
                        }
                    }
                }
            }
            foreach($queryOptions as $option) {

                $query = "INSERT INTO {$option[$tbl]} ({$option[$wCol]},{$option[$col]}) VALUES (:id,:{$option[$col]}) ON DUPLICATE KEY UPDATE {$option[$col]}=:{$option[$col]}a ";
                //added the a onto the end of the third parameter to differentiate. need three parameters to go in even though two are duplicates. idk.
                $parameters = array(":{$option[$col]}"=>$option[$data], ":id"=>$option[$wVal], ":{$option[$col]}a"=>$option[$data]);
                $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
            }
            foreach($queryOptionsNoDuplicate as $option) {
                $query = "INSERT INTO {$option[$tbl]} ({$option[$wCol]},{$option[$col]}) VALUES (:id,:{$option[$col]})";
                $parameters = array(":{$option[$col]}"=>$option[$data], ":id"=>$option[$wVal]);
                $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
            }
            if ($tx->run()) {
                if ($old->getStatus() != $this->getStatus()) {
                    if ($status = $this->getStatus()) {
                        global $currentUser;
                        $subject = "Job {$this->getNumber()} marked {$status->getName()}";
                        $message = "Job {$this->getNumber()} marked {$status->getName()} by {$currentUser->getName()}";
                        foreach($status->getEmails() as $email) {
                            if ($email) {
                                email($email, $subject, $message);
                            }
                        }
                        //if ($status->getEmail()) {
                        //    if (strlen($status->getEmail()) > 5) {
                        //        global $currentUser;
                        //        $subject = "Job {$this->getNumber()} marked {$status->getName()}";
                        //        $message = "Job {$this->getNumber()} marked {$status->getName()} by {$currentUser->getName()}";
                        //        $emails = explode(';', $status->getEmail());
                        //        foreach($emails as $email) {
                        //            if (strlen($email) > 5) {
                        //                email($email, $subject, $message);
                        //            }
                        //        }
                        //    }
                        //}
                        if (($this->getType())->getId() == 6 && ($this->getStatus())->getId() == 4) {
                            global $currentUser;
                            $subject = "Job {$this->getNumber()} marked {$status->getName()}";
                            $message = "Job {$this->getNumber()} marked {$status->getName()} by {$currentUser->getName()}";
                            email('', $subject, $message);
                        }
                    }
                }
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getNumber());
                $backup->setObjectType('Job');
                $backup->setAction('Update');
                $backup->save();
                $functionRun->log();
                return true;
            } else {
                $functionRun->error(errors::$jobUpdateFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$permissionError);
            return false;
        }
    }
    public function delete() {
        $functionRun = new FunctionRun(functions::$deleteJob);
        global $permissions2;
        if (canViewJob($this)) {
            if ($permissions2->jobDelete) {
                global $companyId;
                run(sql::$deleteJob, array(':'.jobData::$id=>$this->id, ':'.jobData::$company=>$companyId));
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getNumber());
                $backup->setObjectType('Job');
                $backup->setAction('Delete');
                $backup->save();
                return true;
            } else {
                $functionRun->error(errors::$permissionError);
                return false;
            }
        } return false;
    }

}


function getPostedArray($name) {
    //returns array from posted data with the values as indexes for easy (if isset)
    $tmp = array();
    if (isset($_POST[$name])) {
        $arr = explode(',', $_POST[$name]);
        foreach($arr as $var) {
            $tmp[$var] = $var;
        }
    }
    return $tmp;
}
function getSortedJobs($jobs, $postedStatuses, $postedTypes, $postedWorkers) {
    if (isset($_GET['rev'])) {
        $jobs = array_reverse($jobs);
    }
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    $sort = new Sort();
    global $userSortValue;
    if (!empty($postedStatuses) || !empty($postedTypes) || !empty($postedWorkers) || isset($_GET['worker']) || isset($_GET['status'])|| isset($_POST['search'])) {
        if (!empty($postedStatuses)) {
            foreach($postedStatuses as $status) {
                $sort->addStatus($status);
            }
        }
        if (isset($_GET['status'])) {
            $sort->addStatus($_GET['status']);
        }
        if (isset($postedTypes)) {
            foreach($postedTypes as $type) {
                $sort->addType($type);
            }
        }
        if (isset($postedWorkers)) {
            foreach($postedWorkers as $worker) {
                $sort->addWorker($worker);
            }
        }
        if (isset($_GET['worker'])) {
            $sort->addWorker($_GET['worker']);
        }
        if (isset($_GET['func'])) {
            $statuses = getStatusesByFunc($_GET['func']);
            foreach($statuses as $status) {
                $sort->addStatus($status->getId());
            }
        }
    } elseif (isset($userSortValue)) {
        $sort = $userSortValue;
    } elseif (isset($_GET['all'])) {
        $sort = new Sort();
    } else {
        $statuses = getStatusesByFunc(4);
        foreach($statuses as $status) {
            $sort->removeStatus($status->getId());
        }
    }
    $jobs = $sort->run($jobs);
    return $jobs;
}
function getStarred() {
    global $currentUser;
    if (!$currentUser) {
        return array();
    }
    $starred = run(sql::$getStarredJobs, array(':user'=>$currentUser->getId()));
    //error_log(print_r($starred->fetchAll(PDO::FETCH_ASSOC), true));
    $return = array();
    foreach($starred->fetchAll(PDO::FETCH_ASSOC) as $star) {
        $return[$star['job']] = true;
    }
    return $return;
    //return array();
}

function createJobs($returns) {
    //$startTime = microtime(true);
    $users    = getUsers();
    $clients  = getClients();
    $statuses = getStatuses();
    $types    = getTypes();
    $starred  = getStarred();
    $userArr = array();
    $clientArr = array();
    $statusArr = array();
    $typeArr = array();
    $filesArr = array();
    $starredArr = array();
    foreach($users as $user) {
        $userArr[$user->getId()] = $user;
    }
    foreach($clients as $client) {
        $clientArr[$client->getId()] = $client;
    }
    foreach($statuses as $status) {
        $statusArr[$status->getId()] = $status;
    }
    foreach($types as $type) {
        $typeArr[$type->getId()] = $type;
    }
    $files = getJobFiles($userArr);
    foreach($files as $file) {
        $filesArr[$file->getId()] = $file;
    }
    $jobs = array();
    $time = array();
    //error_log(microtime(true) - $startTime);
    foreach ($returns as $return) {
        $job = new Job($return);
        $job->setStatus($statusArr[$return[jobData::$status]]);
        $job->setType($typeArr[$return[jobData::$type]]);
        if (isset($starred[$job->getId()])) {
            $job->star();
        }
        if (is_numeric($return[jobData::$creator])) {
            if (isset($userArr[$return[jobData::$creator]])) {
                $job->setCreator($userArr[$return[jobData::$creator]]);
            }
        }
        if (is_numeric($return[jobData::$client])) {
            $job->setClient($clientArr[$return[jobData::$client]]);
        }
        $workers = explode(',', $return[jobData::$workers]);
        if (isset($workers)) {
            foreach($workers as $worker) {
                if (isset($userArr[$worker])) {
                    $job->addWorker($userArr[$worker]);
                }
            }
        }
        $managers = explode(',', $return[jobData::$managers]);
        if (isset($managers)) {
            foreach($managers as $manager) {
                if (isset($userArr[$manager])) {
                    //error_log($manager);
                    $job->addManager($userArr[$manager]);
                }
            }
        }
        if (isset($return[jobData::$files])) {
            $files = explode(',', $return[jobData::$files]);
            if (isset($files)) {
                $size = 0;
                foreach ($files as $file) {
                    if (isset($filesArr[$file])) {
                        $fileVar = $filesArr[$file];
                        $job->addFile($fileVar);
                        $size = $size + $fileVar->getSize();
                    }
                }
                $job->setFileSize($size);
            }
        }
        array_push($jobs, $job);
    }
    return $jobs;
}




function createJob($returns) {
    $job = createJobs($returns);
    if (isset($job[0])) {
        $job = $job[0];
        if (method_exists($job, 'getId')) {
            //if ($materials = getMaterialsByJob($job->getId())) {
            //    $job->setMaterials($materials);
            //}
            return $job;
        }
    }
    return false;
}
function getFileRequiredLevel($file) {
    $files = run(sql::$getFileReqLevel, array(':file'=>$file));
    return ($files->fetch(PDO::FETCH_ASSOC))['requiredLevel'];
}
function getJobs() {
    global $companyId;

    $parameters = array(':'.jobData::$company => $companyId);
    $returns = run(sql::$getJobs, $parameters);

    $jobs = createJobs($returns);
    return $jobs;
}
function getJobById($var) {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId, ':'.jobData::$id => $var);
    $returns = run(sql::$getJobById, $parameters);
    $job = createJob($returns);
    return $job;
}
function nextJobNumber() {
    $functionRun = new functionRun(functions::$nextJobNumber);
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId);
    $returns = run(sql::$nextJobNumber, $parameters);
    $return = ($returns->fetchAll(PDO::FETCH_ASSOC))[0]['maxNum'];
    $functionRun->log();
    return $return;
}

function newJob($data) {
    global $permissions2;
    if (!$permissions2->jobCreate) {
        log::error(errors::$notPermittedJobCreate);
        return false;
    }
    $functionRun = new functionRun(functions::$newJob);
    $types = getTypes();$statuses = getStatuses();
    foreach($types as $type)      { if ($type->getId()   == $data['type'])   { break; } }
    foreach($statuses as $status) { if ($status->getId() == $data['status']) { break; } }
    $job = new Job();
    if (isset($data['name']))        { $job->setName(                $data['name']); } else { $functionRun->error(errors::$noJobNameOnCreate,   'job ' . $this->getId());   }
    if (isset($data['status']))      { $job->setStatus(              $status);       } else { $functionRun->error(errors::$noJobStatusOnCreate, 'job ' . $this->getId());   }
    if (isset($data['type']))        { $job->setType(                $type);         } else { $functionRun->error(errors::$noJobTypeOnCreate,   'job ' . $this->getId());   }
    if (isset($data['location']))    { $job->setLocation(            $data['location'   ]    ); }
    if (isset($data['client'][0]))   { $job->setClient(getClientById($data['client'     ][0])); }
    if (isset($data['poNumber']))    { $job->setPoNumber(            $data['poNumber'   ]    ); }
    if (isset($data['bid']))         { $job->setBid(                 $data['bid'        ]    ); }
    if (isset($data['description'])) { $job->setDescription(         $data['description']    ); }
    if (isset($data['notes']))       { $job->setNotes(               $data['notes'      ]    ); }
    if (isset($data['workers'])) {
        foreach($data['workers'] as $id) {
            $job->addWorker(getUserById($id));
        }
    }
    if (isset($data['managers'])) {
        foreach($data['managers'] as $id) {
            $job->addManager(getUserById($id));
        }
    }
    if ($_FILES) {
        $files = fileUpload($_FILES);
        $i=0;
        foreach($files as $file) {
            if (isset($data['fileTmpInput'])) {
                $levels = array();
                foreach($data['fileTmpInput'] as $key => $level) {
                    foreach($level as $lv) {
                        array_push($levels, $lv);
                    }
                    unset($data['fileTmpInput'][$key]);
                    break;
                }
                $job->addFilePermissions($file->getId(), $levels);
                //$file->setRequiredLevel($data['fileTmpInput'][$i]);
            } else {
                log::error(errors::$noLevelOnFile, 'file '.$file->getId());
            }
            $i++;
            $job->addFile($file);
        }
    }
    if (isset($data['changeOrder'])) {
        $oldJob = getJobById($data['changeOrderJob']);
        $job->setChangeOrder($data['changeOrder']);
        $job->setCleanNumber($oldJob->getCleanNumber());
    }
    if ($job->create()) {
        $functionRun->log();
        return true;
    } else {
        $functionRun->error(errors::$jobCreateFail);
        return false;
    }
    $functionRun->log();
}

function getNextChangeOrder($job) {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId, ':number'=>$job->getCleanNumber());
    $returns = run(sql::$getMaxChangeOrder, $parameters);
    $jobs = createJobs($returns);
    $changeOrder = 0;
    foreach($jobs as $job) {
        $changeOrder = ($job->getChangeOrder() > $changeOrder) ? $job->getChangeOrder() : $changeOrder;
    }
    return $changeOrder+1;
}

function saveJob($data, $job) {
    global $permissions2;
    if (!$permissions2->jobUpdate) {
        log::error(errors::$notPermittedJobUpdate);
        return false;
    }
    if ($_FILES) {
        $files = fileUpload($_FILES);
        $i = 0;
        foreach($files as $file) {
            if (isset($data['fileTmpInput'])) {
                $levels = array();
                foreach($data['fileTmpInput'] as $key => $level) {
                    foreach($level as $lv) {
                        array_push($levels, $lv);
                    }
                    unset($data['fileTmpInput'][$key]);
                    break;
                }
                $job->addFilePermissions($file->getId(), $levels);
                //$file->setRequiredLevel($data['fileTmpInput'][$i]);
            } else {
                log::error(errors::$noLevelOnFile, 'file '.$file->getId());
            }
            $i++;
            $job->addFile($file);
        }
        //foreach($files as $file) {
        //    if (isset($data['fileTmpInput'][$i])) {
        //        $file->setRequiredLevel($data['fileTmpInput'][$i]);
        //    } else {
        //        $file->setRequiredLevel(4);
        //        log::error(errors::$noLevelOnFile);
        //    }
        //    $i++;
        //    $job->addFile($file);
        //}
    }
    $types = getTypes();$statuses = getStatuses();
    if (isset($data['name']))        { $job->setName($data['name']); }
    if (isset($data['status']))      {
        foreach($statuses as $status) {
            if ($status->getId() == $data['status']) { $job->setStatus($status); break; }
        }
    }
    if (isset($data['type']))      {
        foreach($types as $type) {
            if ($type->getId() == $data['type']) { $job->setType($type); break; }
        }
    }
    $data['description'] = str_replace("\r", '', $data['description']);
    if (isset($data['type']))        { $job->setType(                $type);         }
    if (isset($data['location']))    { $job->setLocation(            $data['location'   ]    ); }
    if (!($data['client']) == "")    { $job->setClient(getClientById($data['client'     ])); }
    if (isset($data['poNumber']))    { $job->setPoNumber(            $data['poNumber'   ]    ); }
    if (isset($data['bid']))         { $job->setBid(                 $data['bid'        ]    ); }
    if (isset($data['description'])) { $job->setDescription(str_replace("\r",'',$data['description'])); }
    if (isset($data['notes']))       { $job->setNotes(      str_replace("\r",'',$data['notes']      )); }
    if (isset($data['workers']) || $job->getWorkers()) {
        $job->removeWorkers();
        foreach($data['workers'] as $id) {
            $job->addWorker(getUserById($id));
        }
    }
    if (isset($data['managers']) || $job->getManagers()) {
        $job->removeManagers();
        foreach($data['managers'] as $id) {
            $job->addManager(getUserById($id));
        }
    }
    if ($job->getFiles()) {
        foreach($job->getFiles() as $file) {
            if (isset($data['fileName'.$file->getId()])) {
                $levels = array();
                foreach($data['fileName'.$file->getId()] as $key => $level) {
                    array_push($levels, $level);
                }
                $job->addFilePermissions($file->getId(), $levels);
                //$file->setRequiredLevel($data['fileName'.$file->getId()]);
            }
        }
    }
    if ($job->update()) {
        return true;
    } else {
        return false;
    }
}

function jobButtonsRow($job) {
    $jobCols = getJobColumns();
    global $permissions2;
    $editButton = new Button();
    $editButton->setStyle('background-color:#007bff;color:white;');
    $editButton->setText('<span class="fa fa-pencil"></span>');
    $editButton->setHoverText('Edit');
    $dispatchButton = new Button();
    $dispatchButton->setStyle('background-color:#5bc0de;color:white;');
    $dispatchButton->setText('Dispatch');
    $dispatchButton->setHoverText('Dispatch');
    $deleteButton = new Button();
    $deleteButton->setStyle('background-color:#f44336;color:white;');
    $deleteButton->setText('<span class="fa fa-trash-o"></span>');
    $deleteButton->setOther('data-toggle="modal" data-target="#deleteModal"');
    $deleteButton->setClass('btn btn-default deleteButton');
    $deleteButton->setHoverText('Delete');
    $downloadButton = new Button();
    $downloadButton->setStyle('background-color:#2ECC71;color:white;');
    $downloadButton->setText('<span class="fa fa-download"></span>');
    $downloadButton->setHoverText('Download');
    $viewButton = new Button();
    $viewButton->setStyle('background-color:#5bc0de;color:white;');
    $viewButton->setText('<span class="fa fa-eye"></span>');
    $viewButton->setHoverText('View');
    $dailyButton = new Button();
    $dailyButton->setStyle('background-color:#2ECC71;color:white;');
    $dailyButton->setText('+ <span class="fa fa-file-o"></span>');
    $dailyButton->setHoverText('Daily Report');

    $buttons2 = array();
    $editButton->setLink("/job?id={$job->getId()}");
    $dispatchButton->setLink("/dispatch?id={$job->getId()}");
    $deleteButton->setId($job->getId());
    $downloadButton->setLink("/actions/downloadJob?id={$job->getId()}");
    $viewButton->setLink( "/job?id={$job->getId()}");
    $dailyButton->setLink("/daily?job={$job->getId()}");
    if ($permissions2->jobUpdate && $jobCols->edit) {
        //array_push($buttons2, clone $editButton);
    }
    if ($permissions2->jobDispatch && $jobCols->dispatch) {
        array_push($buttons2, clone $dispatchButton);
    }
    if ($permissions2->jobDelete && $jobCols->deletes) {
        array_push($buttons2, clone $deleteButton);
    }
    if ($permissions2->jobDownload && $jobCols->download) {
        array_push($buttons2, clone $downloadButton);
    }
    if ($jobCols->view) {
        array_push($buttons2, clone $viewButton);
    }
    if ($jobCols->daily && $permissions2->dailyCreate) {
        array_push($buttons2, clone $dailyButton);
    }
    return $buttons2;
}

function displayJob($jobId = NULL, $co = false) {
    global $permissions2;
    global $fields;
    global $currentUser;
    $job = ($jobId) ? getJobById($jobId) : new Job();
    $clients   = getClients();
    $users     = getUsers();
    $statuses  = getStatuses();
    $types     = getTypes();
    $userTypes = getUserTypes();
    $jobCols   = getJobColumns();
    include '/var/www/html/test.php';
}

function displayJob1($jobId = NULL, $co = false) {
    global $permissions2;
    global $fields;
    global $currentUser;
    if (!$permissions2->jobCreate && (!$jobId || $co)) {
        log::error(errors::$notPermittedJobUpdate);
        die;
    }
    if ($jobId) {
        if (!canViewJob(getJobById($jobId))) {
            log::error(errors::$notPermittedJobUpdate);
            die;
        }
    }
    if (isset($_POST['change']))  {
        echo "<script>window.location.replace('/job?id={$_GET['id']}&co=true')</script>"; die;
        die;
    }
    if (isset($_POST['daily']))  {
        echo "<script>window.location.replace('/daily?job={$jobId}')</script>";
        die;
    }
    $submitOrDispatch = (isset($_POST['submit']) || isset($_POST['dispatch'])) ? true : false;
    if (($submitOrDispatch && !$jobId) || ($submitOrDispatch && $co)) {
        if (newJob($_POST)) {
            $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "https";
            if (isset($_POST['dispatch'])) {
                $jobId = getLastInsertedJob();
                echo "<script>window.location.replace('{$link}://{$_SERVER['SERVER_NAME']}/dispatch?id={$jobId}')</script>"; die;
            } else {
            echo "<script>window.location.replace('{$link}://{$_SERVER['SERVER_NAME']}')</script>"; die;
            }
        }
   } elseif ($submitOrDispatch) {
       saveJob($_POST, getJobById($jobId));

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "https";
        if (isset($_POST['dispatch'])) {
            echo "<script>window.location.replace('{$link}://{$_SERVER['SERVER_NAME']}/dispatch?id={$jobId}')</script>"; die;
        } else {
        echo "<script>window.location.replace('{$link}://{$_SERVER['SERVER_NAME']}/job?id={$jobId}')</script>"; die;
        }
    }
    $form = new Form();
    $types    = getTypes();
    $statuses = getStatuses();
    $workers  = getUsers();
    $clients  = getClients();

    if ($co) {
        $oldJob = getJobById($jobId);
        $job     = new Job();
        $coNum = getNextChangeOrder($oldJob);
        $job->setType($oldJob->getType());
        $job->setStatus($oldJob->getStatus());
        if ($oldJob->getClient()) {
            $job->setClient($oldJob->getClient());
        } else {
            $client = new Client();
        }
        if ($oldJob->getWorkers()) {
            $oldWorkers = $oldJob->getWorkers();
            foreach($oldWorkers as $worker) {
                $job->addWorker($worker);
            }
        }
        if ($oldJob->getLocation()) {
            $job->setLocation($oldJob->getLocation());
        }
        $user    = new User();
        $creator = new User();
        $disabled = ($permissions2->jobUpdate) ? '' : 'disabled';
        global $preJn;
        $form->addTitle($preJn . $oldJob->getCleanNumber() . '-' . $coNum);
        $form->addHiddenInput('changeOrder',$coNum);
        $form->addHiddenInput('changeOrderJob',$jobId);
    } elseif(!$jobId) {
        $job     = new Job();
        $job->setType($types[0]);
        foreach($statuses as $status) {
            if ($status->canBeSetBy($currentUser)) {
                $job->setStatus($status);
                break;
            }
        }
        if (!$job->getStatus()) {
            echo 'You do not have permission to set any statuses. Please contact your administrator if you think this is an error';
            error_log('status set permission fail user ' . $currentUser->getId());
            die;
        }
        $client  = new Client();
        $user    = new User();
        $creator = new User();
        $disabled = ($permissions2->jobCreate) ? '' : 'disabled';
    } elseif (!$job = getJobById($jobId)) {
        $job     = new Job();
        $job->setType($types[0]);
        $job->setStatus($statuses[0]);
        $client  = new Client();
        $user    = new User();
        $creator = new User();
        $disabled = ($permissions2->jobUpdate) ? '' : 'disabled';
    } else {
        $form->addTitle($job->getNumber());
        if ($job->getCreator()) {
            $creator = $job->getCreator();
            $form->addSubTitle("Creator: <a href='/user?id={$creator->getId()}'>{$creator->getName()}</a>");
            if ($job->getCreateDate() !== NULL) {
                $form->addSubTitle(substr($job->getCreateDate(), 0, -9));
            }
        }
        $reportBtn = new LinkButton('Job Report');
        $reportBtn->setUrl('/actions/jobReport?id='.$job->getId());
        $reportBtn->addClass('btn');
        $reportBtn->addClass('btn-primary');
        $reportBtn->setStyle('margin-left:30px;');
        if ($permissions2->jobReport) {
            $form->addReport($reportBtn);
        }
        $buttonsRow = jobButtonsRow($job);
        foreach($buttonsRow as $br) {
            $form->addReport($br);
        }
        $disabled = ($permissions2->jobUpdate) ? '' : 'disabled';
    }
    if (!$permissions2->jobUpdate && $jobId && !$co) {
        $disabled = 'disabled';
    }
    echo '<br />';
    $typeSelectOptions   = array();
    $statusSelectOptions = array();
    $workerSelectOptions = array();
    $clientSelectOptions = array();
    foreach($types    as $type  ) { $typeSelectOptions[$type->getId()]     = array($type->getName(),   (($job->getType()  )->getId() == $type->getId()  ) ? true : false ); }
    foreach($statuses as $status) { $statusSelectOptions[$status->getId()] = array($status->getName(), (($job->getStatus())->getId() == $status->getId()) ? true : false, (!$status->canBeSetBy($currentUser) ? 'disabled' : NULL) ); }
    $workerMultiSelect = new FormMultiSelect('Field Worker(s)', 'workers', $disabled);
    foreach($workers  as $worker) {
        $selected = false;
        if (is_array($job->getWorkers())) {
            foreach($job->getWorkers() as $jobWorker) {
                if ($worker->getId() == $jobWorker->getId()) { $selected = true; break; }
            }
        }
        $workerMultiSelect->addOption($worker->getId(), $worker->getName(), $selected, $worker->isDisabled());
    }
    $managerMultiSelect = new FormMultiSelect('Project Manager(s)', 'managers', $disabled);
    foreach($workers  as $worker) {
        $selected = false;
        if (is_array($job->getManagers())) {
            foreach($job->getManagers() as $manager) {
                if ($worker->getId() == $manager->getId()) { $selected = true; break; }
            }
        }
        $managerMultiSelect->addOption($worker->getId(), $worker->getName(), $selected, $worker->isDisabled());
    }

    $clientSingleSelect = new ClientSingleSelect('Client', 'client', $disabled);
    foreach($clients  as $client) {
        $selected = false;
        if ($job->getClient()) { $selected = (($job->getClient())->getId() == $client->getId()) ? true : false; }
        $clientSingleSelect->addOption($client->getId(), $client->getName(), $selected);
    }
    $leftColumn  = new FormColumn();
        $leftColumn->addRow(new FormTextInput( 'Job Name',     'name',        $job->getName(),       lengths::$jobName,     $disabled));
        //$leftColumn->addRow(new FormTextInput( 'Job Manager',  'managers',    $job->getName(),       lengths::$jobName,     $disabled, 'required'));
        $leftColumn->addRow(new FormSelect2(    'Job Status',   'status',      $statusSelectOptions,  $disabled, 'required'));
        $leftColumn->addRow(new FormSelect(    'Job Type',     'type',        $typeSelectOptions,    $disabled, 'required'));
        $leftColumn->addRow($managerMultiSelect);
        if ($fields->jobClient) {
            $leftColumn->addRow($clientSingleSelect);
            if ($job->getClient()) { $leftColumn->addRow(new ClientInfo($job->getClient())); }
        }
        if ($fields->jobWorkers) {
            $leftColumn->addRow($workerMultiSelect);
        }

        $leftColumn->addRow(new FormTextInput( 'Job Location', 'location',    $job->getLocation(),   lengths::$jobLocation, $disabled, NULL, 'location'));
        $leftColumn->addRow(new FormTextInput( 'Po Number',    'poNumber',    $job->getPoNumber(),   lengths::$jobPo,       $disabled));
        if ($fields->jobBid) {
            $leftColumn->addRow(new FormTextInput( 'Bid',          'bid',         $job->getBid(),        lengths::$jobBid,      $disabled));
        }
    $rightColumn = new FormColumn();
        if ($fields->jobDescription) {
            $rightColumn->addRow(new LongTextInput('Description',  'description', $job->getDescription(),lengths::$jobDescription, $disabled));
        }
        if ($fields->jobNotes) {
            $rightColumn->addRow(new LongTextInput('Notes',        'notes',       $job->getNotes(),      lengths::$jobNotes,       $disabled));
        }
    $form->addColumns(array($leftColumn, $rightColumn));
    $fileRow = new FormRow();
    $fileRow->setId('fileInputId');
    if (canUploadFiles()) {
        $fileRow->addField(new FileInput($disabled, 'Files'));
    } else {
        $fileRow->addField(new FileInput('disabled', 'Files'));
    }
    $submitButton = new FormButton('Submit',       'submit',   'btn-primary', NULL,          true);
    $dispatch     = new FormButton('Dispatch',     'dispatch', 'btn-info',    NULL,          true);
    if ($jobId && !$co) {
        $changeOrder  = new FormButton('Change Order', 'change',   'btn-dark',    '/job?co', false);
        $daily        = new FormButton('Daily Report', 'daily',    'btn-success', '/daily',      false);
    }
    if ($permissions2->jobUpdate) {
        $buttonRow = new FormRow();
            $buttonColumnLeft  = new FormColumn();
                $leftButtons = new FormButtons();
                    $tmpBtns = array();
                    if ($jobId) {
                        if ($permissions2->jobUpdate) {
                            array_push($tmpBtns, $submitButton);
                        }
                    } elseif ($permissions2->jobCreate) {
                        array_push($tmpBtns, $submitButton);
                    }
                    if ($permissions2->jobDispatch) {
                        array_push($tmpBtns, $dispatch);
                    }
                    $leftButtons->addButtons($tmpBtns);
                $buttonColumnLeft->addRow($leftButtons);
            $buttonRow->addField($buttonColumnLeft);
        if ($jobId && !$co) {
            $buttonColumnRight = new FormColumn();
                $rightButtons = new FormButtons();
                    $tmpBtns = array();
                    if ($permissions2->jobCreate) {
                        array_push($tmpBtns, $changeOrder);
                    }
                    if ($permissions2->dailyCreate) {
                        array_push($tmpBtns, $daily);
                    }
                    $rightButtons->addButtons($tmpBtns);
                $buttonColumnRight->addRow($rightButtons);
                $buttonRow->addField($buttonColumnRight);
        }
        if ($fields->jobFiles) {
            $form->addRow($fileRow);
            if ($job->getFiles()) {
                $fileLabelRow = new FileLabel();
                $form->addRow($fileLabelRow);
                foreach($job->getFiles() as $file) {
                    if ($file) {
                        $filesRow = new FormRow();
                        $filesRow->style('');
                        $filesRow->setId('file'.$file->getId());
                        $filesList = new FilesList($file);
                        $filesList->addUserLevelInput();
                        $filesRow->addField($filesList);
                        $form->addRow($filesRow);
                    }
                }
            }
        }
        $form->addRow($buttonRow);
    } elseif ($permissions2->dailyCreate) {
        $buttonRow = new FormRow();
        $buttonRow->addField($daily);
        $form->addRow($buttonRow);
    }
    $form->display();
    $table = new Table();
    $table->addStart(new DeleteModal('job'));
    $table->display();
}

function getJobFileLevels($fileId) {
    $stmt = run(sql::$getJobFileLevels, array(':file' => $fileId));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function displayJobs($jobs, $page = NULL, $length = NULL) {
    $page = 1;
    if (isset($_GET['rev'])) {
        $jobs = array_reverse($jobs);
    }
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    $sort = new Sort();
    global $userSortValue;
    if (isset($_POST['status']) || isset($_POST['type']) || isset($_POST['workers']) || isset($_GET['worker']) || isset($_GET['status'])|| isset($_POST['search'])) {
        if (isset($_POST['status'])) {
            foreach($_POST['status'] as $status) {
                $sort->addStatus($status);
            }
        }
        if (isset($_GET['status'])) {
            $sort->addStatus($_GET['status']);
        }
        if (isset($_POST['type'])) {
            foreach($_POST['type'] as $type) {
                $sort->addType($type);
            }
        }
        if (isset($_POST['workers'])) {
            foreach($_POST['workers'] as $worker) {
                $sort->addWorker($worker);
            }
        }
        if (isset($_GET['worker'])) {
            $sort->addWorker($_GET['worker']);
        }
        if (isset($_GET['func'])) {
            $statuses = getStatusesByFunc($_GET['func']);
            foreach($statuses as $status) {
                $sort->addStatus($status->getId());
            }
        }
    } elseif (isset($userSortValue)) {
        $sort = $userSortValue;
    } elseif (isset($_GET['all'])) {
        $sort = new Sort();
    } else {
        $statuses = getStatusesByFunc(4);
        foreach($statuses as $status) {
            $sort->removeStatus($status->getId());
        }
    }
    if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) == '/index' ||
        parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) == '/') {
        echo '<form method="post" action="/jobs" id="sortForm">';
    } else {
        echo '<form method="post" action="'.$_SERVER["REQUEST_URI"].'" id="sortForm">';
    }
    echo '<input type="hidden" id="hiddenStatus">';
    echo '<input type="hidden" id="hiddenType">';
    echo '<input type="hidden" id="hiddenWorker">';
    if (isset($_POST['search'])) {
        echo "<input type='hidden' name='search' value='{$_POST['search']}'>";
    }
    $statuses = getStatuses();
    $workers  = getUsers();
    $types    = getTypes();
    $colStatus  = new MultiSelectColumn('Status',          'status' );
    $colWorkers = new MultiSelectColumn('Field Worker(s)', 'workers');
    $colType    = new MultiSelectColumn('Type(s)',         'type'   );
    foreach($statuses as $status) {
        $add = (in_array($status->getId(), $sort->getStatuses())) ? true : false;
        $colStatus->addOption($status->getId(), $status->getName(), $add);
    }
    foreach($types as $type) {
        $add = (in_array($type->getId(), $sort->getTypes())) ? true : false;
        $colType->addOption($type->getId(), $type->getName(), $add);
    }
    foreach($workers as $worker) {
        $disabled = '';
        if ($worker->isDisabled()) {
            $disabled = '(disabled)';
        }
        $add = (in_array($worker->getId(), $sort->getWorkers())) ? true : false;
        $colWorkers->addOption($worker->getId(), $worker->getName()." ". $disabled, $add);
    }
    $jobCols = getJobColumns();
    $table = new Table();
    $table->addStart(new DeleteModal('job'));
    global $fields;
    global $permissions2;
    if ($jobCols->number) {
        $table->addColumn( $colNumber = new Column('Job Number'));
    }
    if ($jobCols->name) {
        $table->addColumn( $colName = new Column('Job Name'));
    }
    if ($jobCols->status) {
        $table->addColumn( $colStatus );
    }
    if ($jobCols->client && $fields->jobClient) {
        $table->addColumn( $colClient = new Column('Client') );
    }
    if ($jobCols->location && $fields->jobLocation) {
        $table->addColumn( $colLocation = new Column('Location') );
    }
    if ($jobCols->type) {
        $table->addColumn( $colType );
    }
    if ($jobCols->fieldworker && $fields->jobWorkers) {
        $table->addColumn( $colWorkers );
    }
    if ($jobCols->description && $fields->jobDescription) {
        $table->addColumn( $colDescription = new Column('Job Description'));
    }
    if ($jobCols->ponumber && $fields->jobPoNumber) {
        $table->addColumn( $colPoNumber= new Column('PO Number'));
    }
    if ($jobCols->bid && $fields->jobBid) {
        $table->addColumn( $colBid = new Column('Bid'));
    }
    $table->addColumn( $colActions = new Column('Actions'));
    $buttons = array();

    $editButton = new Button();
    $editButton->setClass('btn btn-default jobEditButton');
    $editButton->setText('<span class="fa fa-pencil"></span>');
    $editButton->setHoverText('Edit');
    array_push($buttons, $editButton);

    $dispatchButton = new Button();
    $dispatchButton->setClass('btn btn-default jobDispatchButton');
    $dispatchButton->setText('Dispatch');
    $dispatchButton->setHoverText('Dispatch');
    array_push($buttons, $dispatchButton);

    $deleteButton = new Button();
    $deleteButton->setText('<span class="fa fa-trash-o"></span>');
    $deleteButton->setOther('data-toggle="modal" data-target="#deleteModal"');
    $deleteButton->setClass('btn btn-default deleteButton jobDeleteButton');
    $deleteButton->setHoverText('Delete');
    array_push($buttons, $deleteButton);

    $downloadButton = new Button();
    $downloadButton->setClass('btn btn-default jobDownloadButton');
    $downloadButton->setText('<span class="fa fa-download"></span>');
    $downloadButton->setHoverText('Download');
    array_push($buttons, $downloadButton);

    $viewButton = new Button();
    $viewButton->setClass('btn btn-default jobViewButton');
    $viewButton->setText('<span class="fa fa-eye"></span>');
    $viewButton->setHoverText('View');
    array_push($buttons, $viewButton);

    $dailyButton = new Button();
    $dailyButton->setClass('btn btn-default jobDailyButton');
    $dailyButton->setText('+ <span class="fa fa-file-o"></span>');
    $dailyButton->setHoverText('Daily Report');
    array_push($buttons, $dailyButton);

    $rows = array();
    $i = 0;
    $jobCount = 0;
    //$pageLength = $userSettings->getPageLength();
    $pageLength = ($length) ? $length : 100;
    $jobs = $sort->run($jobs);
    $numberJobs = count($jobs);
    foreach($jobs as $job) {
        if ($job) {
            if (canViewJob($job)) {
                if ((($page-1)*$pageLength)-1 < $i && ($page)*$pageLength > $i) {
                    $jobCount += 1; $statusColor = NULL; $typeColor = NULL;
                    $type    = $job->getType();
                    $status  = $job->getStatus();
                    $workers = $job->getWorkers();
                    $client  = $job->getClient();
                    $workerList = array();
                    if ($workers) {
                        foreach($workers as $worker) {
                            $workerLink = new Href($worker->getName(), "/user?id={$worker->getId()}");
                            array_push($workerList, $workerLink);
                        }
                    }
                    $createDate = substr($job->getCreateDate(), 0, -9);
                    if ($var = $status->getColor()) { $statusColor = 'style="background-color:'.$var.'77"'; }
                    if ($type->getColor() ==  '#ff8c00' || $type->getColor() == '#FBFB70') {
                        if ($var = $type->getColor())   { $typeColor   = 'style="background-color:'.$var.'AA"'; }
                    } else {
                        if ($var = $type->getColor())   { $typeColor   = 'style="background-color:'.$var.'77"'; }
                    }
                    $row = new Row('job'.$job->getId());
                    $buttons2 = array();
                    $editButton->setLink("/job?id={$job->getId()}");
                    $dispatchButton->setLink("/dispatch?id={$job->getId()}");
                    $deleteButton->setId($job->getId());
                    $downloadButton->setLink("/actions/downloadJob?id={$job->getId()}");
                    $viewButton->setLink( "/job?id={$job->getId()}");
                    $dailyButton->setLink("/daily?job={$job->getId()}");
                    if ($permissions2->jobUpdate && $jobCols->edit) {
                        array_push($buttons2, clone $editButton);
                    }
                    if ($permissions2->jobDispatch && $jobCols->dispatch) {
                        array_push($buttons2, clone $dispatchButton);
                    }
                    if ($permissions2->jobDelete && $jobCols->deletes) {
                        array_push($buttons2, clone $deleteButton);
                    }
                    if ($permissions2->jobDownload && $jobCols->download) {
                        array_push($buttons2, clone $downloadButton);
                    }
                    if ($jobCols->view) {
                        array_push($buttons2, clone $viewButton);
                    }
                    if ($jobCols->daily && $permissions2->dailyCreate) {
                        array_push($buttons2, clone $dailyButton);
                    }
                    if ($jobCols->number) {
                        $row->addData($colNumber, new Paragraph("<a href='/job?id={$job->getId()}'>{$job->getNumber()}</a><br />{$createDate}"));
                    }
                    if ($jobCols->name) {
                        $row->addData($colName, new Paragraph($job->getName(), "/job?id={$job->getId()}"));
                    }
                    if ($jobCols->status) {
                        $row->addData($colStatus, new StatusSelect($statuses, $job), $statusColor);
                    }
                    if ($jobCols->client && $fields->jobClient) {
                        ($client) ? $row->addData($colClient, new Href($client->getName(), "/client?id={$client->getId()}")) : $row->addData($colClient,new Paragraph(''));
                    }
                    if ($jobCols->location && $fields->jobLocation) {
                        $row->addData($colLocation, new Paragraph($job->getLocation()), 'style="max-width:18em"');
                    }
                    if ($jobCols->type) {
                        $row->addData($colType, new Paragraph(($job->getType())->getName()),$typeColor);
                    }
                    if ($jobCols->fieldworker && $fields->jobWorkers) {
                        $row->addData($colWorkers, $workerList);
                    }
                    if ($jobCols->description && $fields->jobDescription) {
                        $row->addData($colDescription, new Paragraph($job->getDescription()));
                    }
                    if ($jobCols->ponumber && $fields->jobPoNumber) {
                        $row->addData($colPoNumber, new Paragraph($job->getPoNumber()));
                    }
                    if ($jobCols->bid && $fields->jobBid) {
                        $row->addData($colBid, new Paragraph($job->getBid()));
                    }
                    $row->addData($colActions, $buttons2, 'nowrap="nowrap"');
                    $table->addRow(           $row);
                }
            $i += 1;
            }
        }
    }
    $paginateId = 'paginateJobFormSubmit';
    $table->addFooter(new paginate($numberJobs, $pageLength, $page, $paginateId));
    $table->display();
    echo '</form>';

}

function canViewJob($job) {
    global $permissions2;
    global $currentUser;
    if ($permissions2->jobReadAny) {
        return true;
    }
    $workers = array();
    if ($job->getWorkers()) {
        foreach($job->getWorkers() as $worker) {
            array_push($workers, $worker->getId());
        }
        if (in_array($currentUser->getId(), $workers)) {
            return true;
        }
    }
    return false;
}

function deleteJob($id) {
    $functionRun = new functionRun(functions::$deleteJob);
    if (is_numeric($id)) {
        $job = getJobById($id);
        if ($job->delete()) {
            $functionRun->log();
            return true;
        } else {
            $functionRun->error(errors::$jobDeleteFail);
            return false;
        }
    } return false;
}
function deleteJobFile($fileId) {
    global $permissions2;
    global $fields;
    if ($permissions2->jobUpdate && $fields->jobFiles) {
        $file = getFileById($fileId);
        if (is_numeric($file->getId())) {
            $parameters = array(':file'=>$file->getId());
            if (run(sql::$deleteJobFile, $parameters)) {
                return true;
            }
        }
    } return false;
}
function searchJobs($search) {
    global $databaseConnection;
    $conn = $databaseConnection->conn;
    $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, true );
    $stmt = $conn->prepare(sql::$searchClients);
    $stmt->execute(array(':search' => $search));
    $stmt->execute();
    $clients = $stmt->fetchAll();
    $clientNums = "";
    foreach($clients as $key=>$client) {
        $clientNums .= $client['id'];
        if ($key != array_key_last($clients)) {
            $clientNums .= ',';
        }
    }
    global $companyId;
    $parameters = array(':search'=>$search, ':clientNumbers'=>$clientNums, ':company'=>$companyId);
    $stmt = $conn->prepare(sql::$searchJobs);
    $stmt->execute($parameters);
    $jobs = createJobs($stmt);
    return $jobs;
}
function getLastInsertedJob() {
    global $companyId;
    $parameters = array(':company'=>$companyId);
    $data = run(sql::$getLastInsertedJob, $parameters);
    $data = $data->fetchAll();
    return $data[0]['jobId'];
}



function jobReport($job) {
    global $permissions2;
    if (!$permissions2->jobReport) {
        log::error(errors::$notPermittedJobReport);
        return false;
    } else {
        $date = substr($job->getCreateDate(), 0, -9);
        $pdf = new FPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(0,20,'Job: '. $job->getNumber());
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,20,'Name: '. $job->getName(true));
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,20,'Created: ' . $date);
        $pdf->Ln(10);
        if ($user = $job->getCreator()) {
            $pdf->Cell(40,20,'Creator: ' . $user->getName(true));
            $pdf->Ln(15);
        }
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(70,20,'Status');
        $pdf->Cell(70,20,'Type');
        $pdf->Cell(70,20,'Po Number');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(70,20,($job->getStatus())   ? ($job->getStatus())->getName(true) : '(none)');
        $pdf->Cell(70,20,($job->getType())     ? ($job->getType())->getName(true)   : '(none)');
        $pdf->Cell(70,20,($job->getPoNumber()) ? $job->getPoNumber(true)            : '(none)');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,20,'Workers');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        if ($job->getWorkers()) {
            foreach($job->getWorkers() as $worker) {
                $pdf->Cell(40,20,$worker->getName(true));
                $pdf->Ln(10);
            }
        } else {
            $pdf->Cell(40,20,'(none)');
            $pdf->Ln(10);
        }
        if ($job->getClient() != NULL) {
            $client = $job->getClient();
            //client title
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(80,20,'Client');
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80,20, ($client->getName())    ? $client->getName(true)    : '(none)' );
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(30,20,'Phone');
            $pdf->Cell(75,20,'Email');
            $pdf->Cell(75,20,'Address');
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(30,20, ($client->getPhone())   ? $client->getPhone(true)   : '(none)' );
            $pdf->Cell(75,20, ($client->getEmail())   ? $client->getEmail(true)   : '(none)' );
            $pdf->Cell(75,20, ($client->getAddress()) ? $client->getAddress(true) : '(none)' );
            $pdf->Ln(10);
        }
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,20,'Bid');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,20,($job->getBid()) ? $job->getBid(true) : '(none)');
        $pdf->Ln(10);
        $pdf = pdfDailyAdd($pdf, 'Location',        $job->getLocation(true));
        $pdf = pdfDailyAdd($pdf, 'Description', $job->getDescription(true));
        $pdf = pdfDailyAdd($pdf, 'Notes', $job->getNotes(true));
        return $pdf;
    } return false;
}



function starJob($data) {
    if (isset($data['job'])) {
        $id = $data['job'];
    } else {
        return false;
    }
    require_once '/var/www/globals.php';
    global $currentUser;
    $job = getJobById($id);
    if (!$job) {
        return false;
    }
    if ($job->isStarred()) {
        run(sql::$removeStar, array('user'=>$currentUser->getId(), 'job'=>$job->getId()));
    } else {
        run(sql::$addStar, array('user'=>$currentUser->getId(), 'job'=>$job->getId()));
    }
    return true;
}
