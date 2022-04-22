<?php
require_once '/var/www/classes/dailyHours.php';
require_once '/var/www/classes/job.php';
require_once '/var/www/classes/workType.php';
//require_once '/var/www/design/mail.php';
//require_once '/var/www/design/validatedForm.php';
//require_once '/var/www/fpdf/fpdf.php';
class Daily {
    private $conn;
    private $workers = array();
    private $files = array();
    private $id;
    private $job;
    private $date;
    private $created;
    private $creator;
    private $mileage;
    private $completed;
    private $material;
    private $sowChanges;
    private $equipment;
    private $goals;
    private $notes;
    private $issues;
    private $isPublic = false;
    private $fileSize;
    private $hours;
    private $worker;
    private $updated;
    public function setJob(        $var) {$this->job              = $var;  }
    public function setDate(       $var) {$this->date             = $var;  }
    public function setMileage(    $var) {$this->mileage          = $var;  }
    public function setCompleted(  $var) {$this->completed        = $var;  }
    public function setMaterial(   $var) {$this->material         = $var;  }
    public function setSowChanges( $var) {$this->sowChanges       = $var;  }
    public function setEquipment(  $var) {$this->equipment        = $var;  }
    public function setGoals(      $var) {$this->goals            = $var;  }
    public function setNotes(      $var) {$this->notes            = $var;  }
    public function setIssues(     $var) {$this->issues           = $var;  }
    public function setCreator(    $var) {$this->creator          = $var;  }
    public function setPublic(         ) {$this->isPublic         = true;  }
    public function setWorkers(    $var) {$this->workers          = $var;  }
    public function setFileSize(   $var) {$this->fileSize         = $var;  }
    public function addFile(       $var) {array_push($this->files,  $var); }
    public function addWorker(     $var) {array_push($this->workers,$var); }
    public function setHours(      $var) {$this->hours            = $var;  }
    public function setWorker(     $var) {$this->worker           = $var;  }
    public function setUpdated(    $var) {$this->updated          = $var;  }
    public function setCreated(    $var) {$this->created          = $var;  }
    
    public function getId()         { return $this->id;         }
    public function getJob()        { return $this->job;        }
    public function getDate()       { return $this->date;       }
    public function getMileage()    { return $this->mileage;    }
    public function getCreator()    { return $this->creator;    }
    public function getCompleted( $local = NULL) { return (!$local) ? noHTML($this->completed)  : $this->completed;  }
    public function getMaterial(  $local = NULL) { return (!$local) ? noHTML($this->material)   : $this->material;   }
    public function getSowChanges($local = NULL) { return (!$local) ? noHTML($this->sowChanges) : $this->sowChanges; }
    public function getEquipment( $local = NULL) { return (!$local) ? noHTML($this->equipment)  : $this->equipment;  }
    public function getGoals(     $local = NULL) { return (!$local) ? noHTML($this->goals)      : $this->goals;      }
    public function getNotes(     $local = NULL) { return (!$local) ? noHTML($this->notes)      : $this->notes;      }
    public function getIssues(    $local = NULL) { return (!$local) ? noHTML($this->issues)     : $this->issues;     }
    public function getWorkers()    { return $this->workers;    }
    public function getFiles()      { return $this->files;      }
    public function isPublic()      { return $this->isPublic;   }
    public function getFileSize()   { return $this->fileSize;   }
    public function getHours()      { return $this->hours;      }
    public function getWorker()     { return $this->worker;     }
    public function getUpdated()    { return $this->updated;    }
    public function getCreated()    { return $this->created;    }

    public function unsetFile($file_to_unset) {
        foreach($this->files as $key => $file) {
            if ($file->getId() == $file_to_unset->getId()) {
                $file->delete();
                unset($this->files[$key]);
            }
        }
    }

    public function create() {
        global $permissions2;
        if ($permissions2->dailyCreate) {
            if (!$this->job)         { log::error(errors::$noJobOnDaily);            die; }
            if (!$this->date)        { log::error(errors::$noDateOnDaily);           die; }
            if (!$this->completed)   { log::error(errors::$noCompletedTodayOnDaily); die; }
            if (!$this->workers)     { log::error(errors::$noWorkerOnDaily);         die; }
            global $currentUser; global $companyId;
            $transaction = new TransactionReturnId();
            $parameters = array();
            $parameters[':'.dailyData::$job]        = ($this->getJob())->getId();
            $parameters[':'.dailyData::$date]       = $this->getDate();
            $parameters[':'.dailyData::$completed]  = $this->getCompleted(true);
            $parameters[':'.dailyData::$created]    = date(format::$time);
            $parameters[':'.dailyData::$creator]    = $currentUser->getId();
            $parameters[':'.dailyData::$mileage]    = ($this->getMileage() != NULL)    ? $this->getMileage()    : '';
            $parameters[':'.dailyData::$material]   = ($this->getMaterial(true))   ? $this->getMaterial(true)   : '';
            $parameters[':'.dailyData::$sowChanges] = ($this->getSowChanges(true)) ? $this->getSowChanges(true) : '';
            $parameters[':'.dailyData::$equipment]  = ($this->getEquipment(true))  ? $this->getEquipment(true)  : '';
            $parameters[':'.dailyData::$goals]      = ($this->getGoals(true))      ? $this->getGoals(true)      : '';
            $parameters[':'.dailyData::$notes]      = ($this->getNotes(true))      ? $this->getNotes(true)      : '';
            $parameters[':'.dailyData::$issues]     = ($this->getIssues(true))     ? $this->getIssues(true)     : '';

            $transaction->addQuery(array('query'=>sql::$txGetDailyId));
            $transaction->addQuery(array('query'=>sql::$txCreateDaily,           'parameters'=>$parameters));
            if ($var = $this->getWorkers()) { 
                foreach ($var as $workerHours) {
                    $transaction->addQuery(array(
                        'query'=>sql::$txCreateDailyWorker, 
                        'parameters'=>array(
                            ':'.dailyData::$worker => 
                                ($workerHours->getWorker())->getId(), 
                            ':'.dailyData::$hours => 
                                $workerHours->getHours(),
                            ':'.dailyData::$workType =>
                                $workerHours->getType()
                            )));
                }
            }
            if ($var = $this->getFiles()) { 
                foreach ($var as $file) {
                    $transaction->addQuery(array('query'=>sql::$txCreateDailyFile, 'parameters'=>array(':'.dailyData::$file => $file->getId())));
                }
            }
            $var = $transaction->run();
            if ($var) {
                $daily = getDailyById($var);
                $backup = new Backup();
                $backup->setObjectId($daily->getId());
                $backup->setObjectName(($daily->getJob())->getName());
                $backup->setObjectType('Daily');
                $backup->setAction('Create');
                $backup->save();
                return $var;
            } else {
                log::error(errors::$dailyCreateFail);die;
                return false;
            }
            return true;
        } 
        log::error(errors::$dailyPermissionCreateFail);die;
        return false;
    }

    public function update() {
        if (canUpdateDaily($this)) {
            global $currentUser;
            //init check
            if (!$this->job)         { log::error(errors::$noJobOnDaily);            die; }
            if (!$this->date)        { log::error(errors::$noDateOnDaily);           die; }
            if (!$this->completed)   { log::error(errors::$noCompletedTodayOnDaily); die; }
            if (!$this->workers)     { log::error(errors::$noWorkerOnDaily);         die; }
            //initialize
            $old = getDailyById($this->getId());
            $tbl='table';$col='column';$data='data';$queryOptions = array();$tx = new Transaction();
            if ($old->getDate()           != $this->getDate())           {array_push($queryOptions,array($col=>'date',      $data=>$this->getDate()           )); }
            if ($old->getMileage()        != $this->getMileage())        {array_push($queryOptions,array($col=>'mileage',   $data=>$this->getMileage()        )); }
            if ($old->getCompleted(true)  != $this->getCompleted(true))  {array_push($queryOptions,array($col=>'completed', $data=>$this->getCompleted(true)  )); }
            if ($old->getMaterial(true)   != $this->getMaterial(true))   {array_push($queryOptions,array($col=>'material',  $data=>$this->getMaterial(true)   )); }
            if ($old->getSowChanges(true) != $this->getSowChanges(true)) {array_push($queryOptions,array($col=>'sowChanges',$data=>$this->getSowChanges(true) )); }
            if ($old->getEquipment(true)  != $this->getEquipment(true))  {array_push($queryOptions,array($col=>'equipment', $data=>$this->getEquipment(true)  )); }
            if ($old->getGoals(true)      != $this->getGoals(true))      {array_push($queryOptions,array($col=>'goals',     $data=>$this->getGoals(true)      )); }
            if ($old->getNotes(true)      != $this->getNotes(true))      {array_push($queryOptions,array($col=>'notes',     $data=>$this->getNotes(true)      )); }
            if ($old->getIssues(true)     != $this->getIssues(true))     {array_push($queryOptions,array($col=>'issues',    $data=>$this->getIssues(true)     )); }
            array_push($queryOptions,array($col=>'updated', $data=>date(format::$time)));
            foreach($queryOptions as $option) {
                $query = "UPDATE daily SET {$option[$col]} = :{$data} WHERE id = :id";
                $parameters = array(':'.$data=>$option[$data], ':id'=>$this->getId());
                $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
            }
            $tx->addQuery(array('query'=>sql::$txSetDailyId, 'parameters'=>array(':dailyId'=>$this->getId())));

            if ($old->getWorkers() != $this->getWorkers()) {
                if (count($this->getWorkers()) >= 1) { 

                    $tx->addQuery(array('query'=>'DELETE FROM dailyWorkers WHERE daily = :daily', 'parameters'=>array(':daily'=>$this->getId())));
                    foreach ($this->getWorkers() as $workerHours) {
                        $tx->addQuery(array(
                            'query'=>sql::$txCreateDailyWorker, 
                            'parameters'=>array(
                                ':'.dailyData::$worker   => ($workerHours->getWorker())->getId(), 
                                ':'.dailyData::$hours    => $workerHours->getHours(),
                                ':'.dailyData::$workType => $workerHours->getType()
                                )));
                    }
                }
            }
            if ($old->getFiles() != $this->getFiles()) {
                //if in new but not it old, create
                foreach ($this->getFiles() as $file) {
                    $exists = false;
                    foreach($old->getFiles() as $oldFile) {
                        if ($oldFile->getId() == $file->getId()) {
                            $exists = true;
                        }
                    }
                    if (!$exists) {
                        $tx->addQuery(array('query'=>sql::$txCreateDailyFile, 'parameters'=>array(':'.dailyData::$file => $file->getId())));
                    }
                }
                //if in old but not in new, delete
                //
                //unnecessary because file->delete deletes file which
                //is referenced to cascade delete in dailyfile
                //
                //foreach($old->getFiles() as $oldFile) {
                //    $exists = false;
                //    foreach($this->getFiles() as $file) {
                //        if ($oldFile->getId() == $file->getId()) {
                //            $exists = true;
                //        }
                //    }
                //    if (!$exists) {
                //        $tx->addQuery(array('query'=>sql::$txDeleteDailyFile, 'parameters'=>array(':'.dailyData::$file => $file->getId())));
                //    }
                //}
            }
            if ($tx->run()) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName(($this->getJob())->getName());
                $backup->setObjectType('Daily');
                $backup->setAction('Update');
                $backup->save();
                return true;
            } else {
                log::error(errors::$dailyUpdateFailure);
            }
        } 
        log::error(errors::$dailyPermissionUpdateFail);
        return false;
    }

    public function delete() {
        global $permissions2;
        global $currentUser;
        $creatorId = $this->getCreator()->getId();
        $currentUserId = $currentUser->getId();
        if (($permissions2->dailyUpdateOwn && $creatorId == $currentUserId) || $permissions2->dailyDelete) {
            foreach($this->getFiles() as $file) {
                error_log('delete file '.$file->getUrl());
                $file->delete();
            }
            if (run(sql::$deleteDaily, array(':id' => $this->getId()))) {
                error_log('deleted daily' . $this->getId());
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName(($this->getJob())->getName());
                $backup->setObjectType('Daily');
                $backup->setAction('Delete');
                $backup->save();
                return true;
            }
            log::error(errors::$dailyDeleteFail);
            return false;
        }
    }

    public function __CONSTRUCT($dailyArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($dailyArray) {  
            $this->id      = $dailyArray[dailyData::$id];
            $this->setCreated($dailyArray[dailyData::$created]);
            //$this->creator = getUserById($dailyArray[dailyData::$creator]);
            $this->date    = $dailyArray[dailyData::$date];
            //$this->setJob(       getJobById($dailyArray[dailyData::$job]));
            $this->setMileage(   $dailyArray[dailyData::$mileage]);
            $this->setCompleted( $dailyArray[dailyData::$completed]);
            if (isset($dailyArray[dailyData::$material]))   { $this->setMaterial(   $dailyArray[dailyData::$material]  ); }
            if (isset($dailyArray[dailyData::$sowChanges])) { $this->setSowChanges( $dailyArray[dailyData::$sowChanges]); }
            if (isset($dailyArray[dailyData::$equipment]))  { $this->setEquipment(  $dailyArray[dailyData::$equipment] ); }
            if (isset($dailyArray[dailyData::$goals]))      { $this->setGoals(      $dailyArray[dailyData::$goals]     ); }
            if (isset($dailyArray[dailyData::$notes]))      { $this->setNotes(      $dailyArray[dailyData::$notes]     ); }
            if (isset($dailyArray[dailyData::$issues]))     { $this->setIssues(     $dailyArray[dailyData::$issues]    ); }
            if (isset($dailyArray[dailyData::$updated]))    { $this->setUpdated(    $dailyArray[dailyData::$updated]   ); }
            if ($dailyArray[dailyData::$isPublic] == 1) {
                $this->setPublic();
            }
        }
    }
}

function createDailys($returns, $hours = false) {
    $dailys = array();
    $users = getUsers();
    foreach ($returns as $return) {
        $daily = new Daily($return);
        foreach ($users as $user) {
            if ($user->getId() == $return[dailyData::$creator]) {
                $daily->setCreator($user);
            }
        }
        $daily->setJob(getJobById($return[dailyData::$job]));

        if (isset($return[jobData::$files])) {
            $files = getDailyFilesById($return['id']);
            foreach($files as $file) {
                $daily->addFile($file);
            }
        }
        //if (isset($return[jobData::$files])) {
        //    $files = explode(',', $return[jobData::$files]);
        //    if (isset($files)) {
        //        foreach ($files as $file) {
        //            $daily->addFile(getFileById($file)); 
        //        }
        //    }
        //}
        $daily->setWorkers(getHoursByDaily($return[dailyData::$id]));
        if (isset($return['hours'])) {
            $daily->setHours($return['hours']);
        }
        array_push($dailys, $daily);

    }
    return $dailys;
}

function createDailysByDate($returns) {
    $dailys = array();
    $users = getUsers();
    foreach ($returns as $return) {
        $daily = new Daily($return);
        $daily->setJob(getJobById($return[dailyData::$job]));
        $daily->setWorkers(getHoursByDailyWithUsers($return[dailyData::$id], $users));
        array_push($dailys, $daily);
    }
    return $dailys;
}

function createDaily($returns) {
    $dailys = createDailys($returns);
    if (isset($dailys[0])) {
        return $dailys[0];
    }
    return false;
}

function getDailyById($var) {
    global $companyId;
    $parameters = array(':'.dailyData::$id => $var);
    $returns = run(sql::$getDailyById, $parameters);
    $daily = createDaily($returns);
    return $daily;
}

function newDaily($data) {
    global $permissions2;
    if ($permissions2->dailyCreate) {
        $daily = new Daily();
        $date = new DateTime();
        if (isset($data['date']))      { $daily->setDate(      $data['date']     ); }
        if (isset($_GET['job']))       { $daily->setJob(getJobById($_GET['job']) ); }
        if (isset($data['completed'])) { $daily->setCompleted( $data['completed']); }
        if (isset($data['mileage']))   { $daily->setMileage(   $data['mileage']  ); }
        if (isset($data['goals']))     { $daily->setGoals(     $data['goals']    ); }
        if (isset($data['issues']))    { $daily->setIssues(    $data['issues']   ); }
        if (isset($data['changes']))   { $daily->setSowChanges($data['changes']  ); }
        if (isset($data['material']))  { $daily->setMaterial(  $data['material'] ); }
        if (isset($data['equipment'])) { $daily->setEquipment( $data['equipment']); }
        if (isset($data['notes']))     { $daily->setNotes(     $data['notes']    ); }
        if (!is_numeric($data['mileage'])) {
            echo 'Mileage has to be a number. Please try again';
            die;
        }
        if (isset($data['worker']) && isset($data['hours'])) {
            foreach($data['hours'] as $hour) {
                if (!is_numeric($hour)) {
                    echo 'Hours has to be a number. Please try again';
                    die;
                }
            }
            if (count($data['worker']) == count($data['hours'])) {
                $dailyHours = array(); 
                $workers = $data['worker']; 
                $hours = $data['hours'];
                $workType = $data['workType'];
                $len = count($data['worker']);
                for ($i=0; $i<$len; $i++) {
                    $dailyHour = new DailyHours();
                    $dailyHour->setWorker(getUserById($workers[$i]));
                    $dailyHour->setHours( $hours[$i]  );
                    $dailyHour->setType($workType[$i]);
                    array_push($dailyHours, $dailyHour);
                }
                $daily->setWorkers($dailyHours);
            } else {
                echo 'Each worker must have hours.'; die;
            }
        }
        if ($_FILES) {
            $files = fileUpload($_FILES);
            foreach($files as $file) {
                $daily->addFile($file);
            }
        }
        if (isset($data['compFileInput'])) {
            $files = jsFileUpload($data['compFileInput']);
            foreach($files as $file) {
                $daily->addFile($file);
            }
        }

        $var = $daily->create();
        if ($var) {
            $daily = getDailyById($var);
            if ($var = getDailyEmail()) {
                $job = $daily->getJob();
                $creator = ($daily->getCreator());
                dailyEmail($var, $creator, $daily);
            }
        }
        return true;
    } return false;
}

function updateDaily($daily, $data) {
    if (canUpdateDaily($daily)) {
        foreach($daily->getFiles() as $file) {
            $file_still_exists = false;
            foreach($data['images'] as $image) {
                if ($file->getId() == $image) {
                    $file_still_exists = true;
                    break;
                }
            }
            if (!$file_still_exists) {
                $daily->unsetFile($file);
            } else {
                $daily->addFile($file);
            }
        }
        $daily->setDate(      $data['date']     );
        $daily->setCompleted( $data['completed']);
        $daily->setMileage(   $data['mileage']  );
        $daily->setGoals(     $data['goals']    );
        $daily->setIssues(    $data['issues']   );
        $daily->setSowChanges($data['changes']  );
        $daily->setMaterial(  $data['material'] );
        $daily->setEquipment( $data['equipment']);
        $daily->setNotes(     $data['notes']    );
        if (!is_numeric($data['mileage'])) {
            echo 'Mileage has to be a number. Please try again';die;
        }
        if (isset($data['worker']) && isset($data['hours'])) {
            foreach($data['hours'] as $hour) {
                if (!is_numeric($hour)) {
                    echo 'Hours has to be a number. Please try again';
                    die;
                }
            }
            if (count($data['worker']) == count($data['hours'])) {
                $dailyHours = array(); 
                $workers = $data['worker']; 
                $hours = $data['hours'];
                $workType = $data['workType'];
                $len = count($data['worker']);
                for ($i=0; $i<$len; $i++) {
                    $dailyHour = new DailyHours();
                    $dailyHour->setWorker(getUserById($workers[$i]));
                    $dailyHour->setHours( $hours[$i]  );
                    $dailyHour->setType($workType[$i]);
                    array_push($dailyHours, $dailyHour);
                }
                $daily->setWorkers($dailyHours);
            } else {
                echo 'Each worker must have hours.'; die;
            }
        }

        if ($_FILES) {
            $files = fileUpload($_FILES);
            foreach($files as $file) {
                $daily->addFile($file);
            }
        }
        if (isset($data['compFileInput'])) {
            $files = jsFileUpload($data['compFileInput']);
            foreach($files as $file) {
                $daily->addFile($file);
            }
        }
        $daily->update();
        return true;
    } return false;
}

function getDailyEmail() {
    global $companyId;
    $stmt = run(sql::$getCompany, array(':id' => $companyId));
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    return $response['dailyEmail'];
}
function getDailyCountByJob($id) {
    $data = run(sql::$getDailyCountByJob, array(':job'=>$id));
    return count($data->fetchAll(PDO::FETCH_ASSOC));
}
function displayDaily($dailyId = NULL, $jobId = NULL) {
    global $permissions2;
    if ($dailyId) {
        if (!canReadDaily(getDailyById($dailyId))) {
            log::error(errors::$dailyPermissionReadFail);
            return false;
        }
    } elseif (!$permissions2->dailyCreate) {
        log::error(errors::$dailyPermissionCreateFail);
        return false;
    }
    if (isset($_POST['completed'])) {
        if (isset($_GET['update'])) {
            $dl = getDailyById($dailyId);
            updateDaily($dl, $_POST);
            $jId = ($dl->getJob())->getId();
            echo "<script>window.location.replace('/job?id={$jId}')</script>"; die;
        } else {
            newDaily($_POST);
            echo "<script>window.location.replace('/job?id={$_GET['job']}')</script>"; die;
        }
    }
    $form = new Form(); $length = 40000;
    $form->setId('dailySubmitForm');
    $disabled = '';
    if ($jobId) {
        $daily = new Daily();
        $job = getJobById($jobId);
        $form->addTitle('Job '.$job->getNumber());
        $form->addSubTitle($job->getName());
    } elseif($dailyId) {
        if (isset($_GET['update'])) {
            $daily = getDailyById($dailyId);
            if (!canUpdateDaily($daily)) {
                log::error(errors::$dailyPermissionUpdateFail);
                return false;
            }
            $form->addTitle('Job '.($daily->getJob())->getNumber());
            $form->addSubTitle(($daily->getJob())->getName());
            $form->addRow(dailyButtonRow($daily));
        } else {
            $daily = getDailyById($dailyId);
            $form->addTitle('Job '.($daily->getJob())->getNumber());
            $form->addSubTitle(($daily->getJob())->getName());
            $disabled = 'disabled';
            $form->addRow(dailyButtonRow($daily));
        }
    } else {
        log::error(errors::$noJobOrDailyId);
        error_log(print_r(get_defined_vars(), true));
    }
    
    $leftColumn  = new FormColumn();
        $leftColumn->addRow(new DailyDateInput($daily, $disabled));
        workerHoursDisplay($leftColumn, $daily, $disabled);
        $leftColumn->addRow(new MileageInput( 'Mileage',                  'mileage',   $daily->getMileage(),   5,       $disabled, 'required'));
        $leftColumn->addRow(new LongTextInput('Completed Today',          'completed', $daily->getCompleted(), $length, $disabled, 'required'));
        $leftColumn->addRow(new LongTextInput('Tomorrow\'s Goals',        'goals',     $daily->getGoals(),     $length, $disabled));
        $leftColumn->addRow(new LongTextInput('Issues On Site',           'issues',    $daily->getIssues(),    $length, $disabled));
    $rightColumn = new FormColumn();
        $rightColumn->addRow(new LongTextInput('Changes to Scope of Work','changes',   $daily->getSowChanges(),$length, $disabled));
        $rightColumn->addRow(new LongTextInput('Material Needed',         'material',  $daily->getMaterial(),  $length, $disabled));
        $rightColumn->addRow(new LongTextInput('Equipment On Site',       'equipment', $daily->getEquipment(), $length, $disabled));
        $rightColumn->addRow(new LongTextInput('Additional Notes',        'notes',     $daily->getNotes(),     $length, $disabled));
    $form->addColumns(array($leftColumn, $rightColumn));
    if ($jobId) {
        $fileRow = new FormRow();
            $fileRow->addField(new FileInput($disabled, 'Pictures', 'Choose Pictures'));
        $form->addRow($fileRow);
        $submitButton = new FormButton('Submit', 'submit', 'btn-primary', NULL, true);
        $buttonRow = new FormRow();
            $buttonRow->addField($submitButton);
        $form->addRow($buttonRow);
    } elseif(isset($_GET['update'])) {
        $fileRow = new FormRow();
        $fileRow->addField(new FileInput($disabled, 'Pictures', 'Choose Pictures'));
        $form->addRow($fileRow);
        $submitButton = new FormButton('Update', 'submit', 'btn-primary', NULL, true);
        $buttonRow = new FormRow();
            $buttonRow->addField($submitButton);
        $form->addRow($buttonRow);
    } else {
        
    }
    if ($daily->getFiles()) {
        $picturesRow = new FormRow();
        $picturesList = new PicturesList($daily);
        $picturesRow->addField($picturesList);
        $form->addRow($picturesRow);
    }
    $form->display();
    $table = new Table();
    $table->addStart(new Modal('daily')); 
    $table->addStart(new Modal('dailySharing')); 
    $table->display();
}

function dailyButtonRow($daily) {
    global $permissions2;
    $viewButton = new Button();
    $viewButton->setStyle('background-color:#5bc0de;color:white;');
    $viewButton->setText('<span class="fa fa-eye"></span>');
    $viewButton->setLink(    "/daily?id={$daily->getId()}");
    $printButton = new Button();
    $printButton->setStyle('background-color:grey;color:white;');
    $printButton->setText('<span class="fa fa-print"></span>');
    $downloadButton = new Button();
    $downloadButton->setStyle('background-color:DodgerBlue;color:white;');
    $downloadButton->setText('<span class="fa fa-download"></span>');
    $shareButton = new Button();
    $shareButton->setClass('shareButton btn');
    $shareButton->setText('<span class="fa fa-share"></span>');
    $updateButton = new Button();
    $updateButton->setStyle('background-color:DodgerBlue;color:white;');
    $updateButton->setText('<span class="fa fa-pencil"></span>');
    $updateButton->setLink(  "/daily?id={$daily->getId()}&update=true"    );
    $printButton->setLink(   "/printDaily?id={$daily->getId()}"           );
    $downloadButton->setLink("/actions/downloadDaily?id={$daily->getId()}");
    if ($daily->isPublic()) {
        $shareButton->setOther('data-toggle="modal" data-target="#shareModal"');
    } else {
        $shareButton->setOther('data-toggle="modal" data-target="#sharingModal"');
    }
    $shareButton->setId('dailyShare'.$daily->getId());
    $color = ($daily->isPublic()) ? '#2ECC71' : 'lightgrey';
    $shareButton->setStyle("background-color:{$color};color:white;"); 
    $deleteButton = new Button();
    $deleteButton->setText('<span class="fa fa-trash-o"></span>');
    $deleteButton->setOther('data-toggle="modal" data-target="#deleteDailyModal"');
    $deleteButton->setClass('btn btn-default deleteButton jobDeleteButton');
    $deleteButton->setHoverText('Delete');
    $deleteButton->setId($daily->getId());



    $buttonRow = new FormRow();
        if (isset($_GET['update'])) {
            $buttonRow->addField($viewButton);
        } else {
            if (canUpdateDaily($daily)) {
                $buttonRow->addField($updateButton);
            }
        }
        $buttonRow->addField($printButton);
        $buttonRow->addField($downloadButton);
        $creator = ($daily->getCreator()) ? $daily->getCreator() : new User();
        global $currentUser;
        $currentUserId = $currentUser->getId();
        if (($permissions2->dailyUpdateOwn && $creator->getId() == $currentUserId) || $permissions2->dailyDelete) {
            $buttonRow->addField($deleteButton);
        }
        if (canUpdateDaily($daily)) {
            $buttonRow->addField($shareButton);
        }
    return $buttonRow;
}




function canUpdateDaily($daily) {
    global $permissions2;
    if ($permissions2->dailyUpdate) {
        return true;
    } elseif ($permissions2->dailyUpdateOwn) {
        global $currentUser;
        if (method_exists($daily->getCreator(),'getId')) {
            if ($daily->getCreator()->getId() == $currentUser->getId()) {
                return true;
            }
        }
    } return false;
}

function displayWorkerHours($daily, $disabled) {
    $types = getWorkTypes();
    $users = getUsers();
    if ($daily->getWorkers() != NULL) {
        $count = 0;
        foreach($daily->getWorkers() as $workerHours) {

            $input = new DailyHour($workerHours);
            $input->setWorkTypes($types);
            $input->setUsers($users);
            if ($disabled) {
                $input->disable();
            }
            if ($count == 0) {
                $input->isFirst();
            } $count++;
            $input->display();
        }
    } else {
        global $currentUser;
        $hours = new DailyHours();
        $hours->setWorker($currentUser);
        $input = new DailyHour($hours);
        $input->setWorkTypes($types);
        $input->setUsers($users);
        $input->isFirst();
        $input->display();
    }
}

function workerHoursDisplay($column, $daily, $disabled) {
    if ($daily->getWorkers() != NULL) {
        $count = 0;
        foreach($daily->getWorkers() as $workerHours) {
            $dui = new DailyUserInput($workerHours);
            if ($disabled) {
                $dui->disable();
            }
            if ($count == 0) {
                $dui->isFirst();
                $count++;
                $dui->setSelected($workerHours->getType());
                //$column->addRow($dui, 'workerHours');
            } else {
                $rng = randomString(5);
                $dui->setId($rng);
                $dui->setSelected($workerHours->getType());
                //$column->addRow($dui, 'workerHours'.$rng);
            }
        }
    } else {
        $dui = new DailyUserInput();
        $dui->isFirst();

        //$column->addRow($dui,'workerHours');
        //$column->addRow(new DailyUserPlus('duplicateWorker'));
    }
    if ($daily->getWorkers() && !$disabled) {
        //$column->addRow(new DailyUserPlus('duplicateWorker'));
    }
}

function getDailysByDates($start, $end) {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId, ':start' => $start, ':end' => $end);
    $returns = run(sql::$getDailiesByDates, $parameters);
    $dailys = createDailysByDate($returns);
    return $dailys;

}

function getDailysByJobId($jobId, $files = false) {
    global $companyId;
    $parameters = array(':'.dailyData::$job => $jobId);
    $returns = run(sql::$getDailyByJobId, $parameters);
    $dailys = createDailys($returns, $files);
    return $dailys;
}

function getDailysByUserId($userId, $files = false) {
    //files decides if the files should be grabbed from the DB
    global $companyId;
    $parameters = array(':creator' => $userId);
    $returns = run(sql::$getDailysByUserId, $parameters);
    $dailys = createDailies($returns, $files);
    return $dailys;
}

function getHoursByUserId($userId, $files = false) {
    //files decides if the files should be grabbed from the DB
    global $companyId;
    $parameters = array(':id' => $userId);
    $returns = run(sql::$getDailyHours, $parameters);
    $dailys = createHours($returns, $files);
    return $dailys;
}

function getDailies($full = false) {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId);
    $returns = run(sql::$getDailies, $parameters);
    $dailys = createDailies($returns, $full);
    return $dailys;
}

function getDailiesWithFiles() {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId);
    $returns = run(sql::$getDailies, $parameters);
    $dailys = createDailiesWithFiles($returns);
    return $dailys;
}

function createDailiesWithFiles($returns) {
    $dailys = array();
    $users = getUsers();
    foreach ($returns as $return) {
        $daily = new Daily($return);
        foreach ($users as $user) {
            if ($user->getId() == $return[dailyData::$creator]) {
                $daily->setCreator($user);
            }
        }
        $job = new Job();
        $job->setId($return['job']);
        $job->setNumber($return['number']);
        $job->setName($return['name']);
        $daily->setJob($job);
        if (isset($return[jobData::$files])) {
            $daily->setFileSize(getFileSizeByDaily($daily));
        }
        array_push($dailys, $daily);
    }
    return $dailys;
}

function createHours($returns) {
    $dailys = array();
    $users = getUsers();
    foreach ($returns as $return) {
        $daily = new Daily($return);
        foreach ($users as $user) {
            if ($user->getId() == $return['worker']) {
                $daily->setWorker($user);
            }
        }
        $job = new Job();
        $job->setId($return['job']);
        if (isset($return['co'])) {
            $job->setChangeOrder($return['co']);
        }
        $job->setNumber($return['number']);
        $job->setName($return['name']);
        $daily->setHours($return['hours']);
        $daily->setJob($job);
        array_push($dailys, $daily);
    }
    return $dailys;
}

function createDailies($returns, $fullFiles = false) {
    $dailys = array();
    $users = getUsers();
    foreach ($returns as $return) {
        $daily = new Daily($return);
        foreach ($users as $user) {
            if ($user->getId() == $return[dailyData::$creator]) {
                $daily->setCreator($user);
            }
        }
        $job = new Job();
        $job->setId($return['job']);
        $job->setNumber($return['number']);
        $job->setName($return['name']);
        $daily->setJob($job);
        if (isset($return[jobData::$files])) {
            $files = explode(',', $return[jobData::$files]);
            if (isset($files)) {
                $size = 0;
                foreach ($files as $file) {
                    if ($fullFiles) {
                        $tmpFile = getFileById($file);
                        $daily->addFile($tmpFile);
                        $size += $tmpFile->getSize();
                    } else {
                        $tmpFile = new File();
                        $tmpFile->setId($file);
                        $daily->addFile($tmpFile); 
                    }
                }
                $daily->setFileSize($size);
            }
        }
        if (isset($return['hours'])) {
            $daily->setHours($return['hours']);
        }
        array_push($dailys, $daily);
    }
    return $dailys;
}

function displayDailys($dailys, $hours = false) {
    global $permissions2;
    $table = new Table();
    $table->setStyle('top:0;');
    $table->addColumn( $colJob        = new Column('Job')             );
    $table->addColumn( $colDate       = new Column('Date')            );
    $table->addColumn( $colCreator    = new Column('Creator')         ); 
    $table->addColumn( $colHours      = new Column('Hours')           );  
    $table->addColumn( $colMileage    = new Column('Mileage')         );  
    $table->addColumn( $colCompleted  = new Column('Completed Today') );  
    $table->addColumn( $colFiles      = new Column('Pictures')        );  
    $table->addColumn( $colActions    = new Column('Actions')         ); 
    $table->addStart(new Modal('daily')); 
    $table->addStart(new Modal('dailySharing')); 

    $viewButton = new Button();
    $viewButton->setStyle('background-color:#5bc0de;color:white;');
    $viewButton->setText('<span class="fa fa-eye"></span>');
    $printButton = new Button();
    $printButton->setStyle('background-color:grey;color:white;');
    $printButton->setText('<span class="fa fa-print"></span>');
    $downloadButton = new Button();
    $downloadButton->setStyle('background-color:DodgerBlue;color:white;');
    $downloadButton->setText('<span class="fa fa-download"></span>');
    $shareButton = new Button();
    $shareButton->setClass('shareButton btn');
    $shareButton->setText('<span class="fa fa-share"></span>');
    $updateButton = new Button();
    $updateButton->setStyle('background-color:DodgerBlue;color:white;');
    $updateButton->setText('<span class="fa fa-pencil"></span>');
    $deleteButton = new Button();
    $deleteButton->setText('<span class="fa fa-trash-o"></span>');
    $deleteButton->setOther('data-toggle="modal" data-target="#deleteDailyModal"');
    $deleteButton->setClass('btn btn-default deleteButton jobDeleteButton');
    $deleteButton->setHoverText('Delete');
    if ($hours) {
        $totalHours = 0;
        $workTypes = getWorkTypes();
        $typeHours = array();
        foreach($workTypes as $workType) {
            $typeHours[$workType->getId()] = 0;
        }
        foreach($dailys as $daily) {
            $hrs = getHoursByDaily($daily->getId());
            foreach($hrs as $hour) {
                $totalHours += $hour->getHours();
                if (!isset($typeHours[$hour->getType()])) { $typeHours[$hour->getType()] = 0; }
                $typeHours[$hour->getType()] += $hour->getHours();
            }
        }
    }
    //delete if values

    global $currentUser;
    foreach($dailys as $daily) {
        if (canReadDaily($daily)) {
            $currentUserId = $currentUser->getId();
            $job = $daily->getJob();
            $creator = $daily->getCreator();
            $creator = (method_exists($creator, 'getName')) ? $creator : new User();
            $date = new Datetime($daily->getDate());
            $row = new Row('dailyRow'.$daily->getId());
            $row->addData($colJob,        new Href('Job '.$job->getNumber(),"/job?id={$job->getId()}"));
            $row->addData($colDate,       new Paragraph($date->format('m/d/Y')));
            $row->addData($colCreator,    new Href($creator->getName(), "/user?id={$creator->getId()}"));
            $row->addData($colHours,      new Paragraph($daily->getHours()));
            $row->addData($colMileage,    new Paragraph($daily->getMileage()));
            $row->addData($colCompleted,  new Paragraph($daily->getCompleted()));
            $row->addData($colFiles,      new Paragraph(count($daily->getFiles()) . ' pictures'));
            $buttons2 = array();
            $updateButton->setLink(  "/daily?id={$daily->getId()}&update=true"    );
            $viewButton->setLink(    "/daily?id={$daily->getId()}"                );
            $printButton->setLink(   "/printDaily?id={$daily->getId()}"           );
            $downloadButton->setLink("/actions/downloadDaily?id={$daily->getId()}");
            $deleteButton->setId($daily->getId());
            if (canUpdateDaily($daily)) {
                array_push($buttons2, clone $updateButton  );
            } else {
                $updateButton->disable();
                array_push($buttons2, clone $updateButton);
                $updateButton->enable();
            }
            array_push($buttons2, clone $viewButton    );
            array_push($buttons2, clone $printButton   );
            array_push($buttons2, clone $downloadButton);
            if (($permissions2->dailyUpdateOwn && $creator->getId() == $currentUserId) || $permissions2->dailyDelete) {
                array_push($buttons2, clone $deleteButton);
            }
            if ($daily->isPublic()) {
                $shareButton->setOther('data-toggle="modal" data-target="#shareModal"');
            } else {
                $shareButton->setOther('data-toggle="modal" data-target="#sharingModal"');
            }
            $shareButton->setId('dailyShare'.$daily->getId());
            $color = ($daily->isPublic()) ? '#2ECC71' : 'lightgrey';
            $shareButton->setStyle("background-color:{$color};color:white;"); 
            if (canUpdateDaily($daily)) {
                array_push($buttons2, clone $shareButton);
            }
            $row->addData($colActions, $buttons2, 'style="white-space: nowrap !important;"');
            $table->addRow($row);
        }
    }
    if ($hours && $permissions2->dailyRead) {
        echo "<h4>Hours: {$totalHours}</h4>";
        foreach($typeHours as $key=>$var) {
            if ($key) {
                foreach($workTypes as $type) {
                    if ($type->getId() == $key) {
                        echo "<p>{$type->getName()}: {$var}";
                        break;
                    }
                }
            } else {
                echo "<p>Other: {$var}";
            }
        }
    }
    $table->display();
    //share Modal

}

function displayHours($dailys) {
    $table = new Table();
    $table->addColumn( $colJob        = new Column('Job')             );
    $table->addColumn( $colDate       = new Column('Date')            );
    $table->addColumn( $colWorker     = new Column('Worker')         ); 
    $table->addColumn( $colHours      = new Column('Hours')           );  
    $table->addColumn( $colMileage    = new Column('Mileage')         );  
    $table->addColumn( $colCompleted  = new Column('Completed Today') );  
    $table->addColumn( $colActions    = new Column('Actions')         ); 
    $table->addStart(new Modal('daily')); 
    $table->addStart(new Modal('dailySharing')); 

    $viewButton = new Button();
    $viewButton->setStyle('background-color:#5bc0de;color:white;');
    $viewButton->setText('<span class="fa fa-eye"></span>');
    $printButton = new Button();
    $printButton->setStyle('background-color:grey;color:white;');
    $printButton->setText('<span class="fa fa-print"></span>');
    $downloadButton = new Button();
    $downloadButton->setStyle('background-color:DodgerBlue;color:white;');
    $downloadButton->setText('<span class="fa fa-download"></span>');
    $shareButton = new Button();
    $shareButton->setClass('shareButton');
    $shareButton->setText('<span class="fa fa-share"></span>');

    foreach($dailys as $daily) {
        $job = $daily->getJob();
        $worker = $daily->getWorker();
        $date = new Datetime($daily->getDate());
        $row = new Row('dailyRow'.$daily->getId());
        $row->addData($colJob,        new Href('Job '.$job->getNumber(),"/job?id={$job->getId()}"));
        $row->addData($colDate,       new Paragraph($date->format('m/d/Y')));
        $row->addData($colWorker,     new Href($worker->getName(), "/user?id={$worker->getId()}"));
        $row->addData($colHours,      new Paragraph($daily->getHours() . ' Hours'));
        $row->addData($colMileage,    new Paragraph($daily->getMileage()));
        $row->addData($colCompleted,  new Paragraph($daily->getCompleted()));
        $buttons2 = array();
        $viewButton->setLink( "/daily?id={$daily->getId()}");      array_push($buttons2, clone $viewButton);
        $printButton->setLink("/printDaily?id={$daily->getId()}"); array_push($buttons2, clone $printButton);
        $downloadButton->setLink("/actions/downloadDaily?id={$daily->getId()}"); array_push($buttons2, clone $downloadButton);
        if ($daily->isPublic()) {
            $shareButton->setOther('data-toggle="modal" data-target="#shareModal"');
        } else {
            $shareButton->setOther('data-toggle="modal" data-target="#sharingModal"');
        }
        $shareButton->setId('dailyShare'.$daily->getId());
        $color = ($daily->isPublic()) ? '#2ECC71' : 'lightgrey';
        $shareButton->setStyle("background-color:{$color};color:white;"); array_push($buttons2, clone $shareButton);
        $row->addData($colActions, $buttons2, 'style="white-space: nowrap !important;"');
        $table->addRow($row);
    }
    $table->display();
}

function canReadDaily($daily) {
    global $permissions2;
    if ($permissions2->dailyRead) {
        return true;
    }
    global $currentUser;
    if ($daily->getCreator()->getId() == $currentUser->getId()) {
        return true;
    } return false;
}

function dailyReport($dailyId) {
    $daily = getDailyById($dailyId);
    if (canReadDaily($daily)) {
        $date = (new Datetime($daily->getDate()))->format('m-d-Y');
        $job = $daily->getJob();
        $pdf = new FPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(40,20,'Job: '. ($daily->getJob())->getNumber());
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,20,'Name: '. ($daily->getJob())->getName());
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(40,20,$date);
        $pdf->Ln(15);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(60,20,"Created");
        $pdf->Cell(60,20,"Last Updated");
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(60,20,$daily->getCreated());
        $pdf->Cell(60,20,($daily->getUpdated()) ? $daily->getUpdated() : '(none)');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(60,20,'Field Worker(s)');
        $pdf->Cell(60,20,'Hours Worked');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        foreach($daily->getWorkers() as $workerHours) {
            $pdf->Cell(60,20,($workerHours->getWorker())->getName());
            $pdf->Cell(60,20,$workerHours->getHours());
            $pdf->Ln(10);
        }
        if ($daily->getMileage()) {
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(60,20,'Mileage');
            $pdf->Ln(10);
            $pdf->Cell(60,20,$daily->getMileage());
            $pdf->Ln(10);
        }
        //$pdf->SetFont('Arial','B',12);
        //$pdf->Cell(60,20,'Field Worker(s)');
        //$pdf->Cell(60,20,'Hours Worked');
        //$pdf->Ln(10);
        $pdf = pdfDailyAdd($pdf, 'Completed Today',       $daily->getCompleted(true));
        $pdf = pdfDailyAdd($pdf, 'Materials needed',      $daily->getMaterial(true));
        $pdf = pdfDailyAdd($pdf, 'Scope of Work Changes', $daily->getSowChanges(true));
        $pdf = pdfDailyAdd($pdf, 'Equipment on site',     $daily->getEquipment(true));
        $pdf = pdfDailyAdd($pdf, 'Tomorrow\'s goals',     $daily->getGoals(true));
        $pdf = pdfDailyAdd($pdf, 'Notes',                 $daily->getNotes(true));
        $pdf = pdfDailyAdd($pdf, 'Issues on site',        $daily->getIssues(true));

        $counts = 5;
        $ct = $counts;
        $y = $pdf->GetY();
        $size = 50;
        foreach($daily->getFiles() as $picture) 
        {
            if (file_exists($picture->getUrl())) {
                $ext = pathinfo($picture->getUrl(), PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') { 
                    if(($counts - ($size * 4)) == $ct) {
                        $pdf->Ln(1);
                        $y = $y + $size;
                        $counts = $counts - 200;
                    }
                    if ($y > 250) {
                        $pdf->AddPage();
                        $y = 5;
                        $counts = 5;
                    }


                    $h_img = fopen($picture->getUrl(), "rb");
                    $img = fread($h_img, filesize($picture->getUrl()));
                    fclose($h_img);
                    // prepare a base64 encoded "data url"
                    $pic = 'data://text/plain;base64,' . base64_encode($img);
                    // extract dimensions from image
                    $info = getimagesize($pic);
                    $pdf->Image($pic,$counts,$y,$size,$size,$ext,"https://{$_SERVER['HTTP_HOST']}/imageViewer?id={$picture->getId()}&daily={$daily->getId()}");
                    $counts = $counts + $size;
                }
            }
        }
        return $pdf;
        //$filename = 'daily' . $job->getNumber() . '-' . $date . '.pdf';
        //$pdf->output('I', $filename, true);
        //$pdf->Output('file.pdf','D');
    } return false;
}

function pdfDailyAdd($pdf, $name, $var) {
    if ($var) {
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(60,20,$name);
        $pdf->Ln(15);
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,5,$var);
    }
    return $pdf;
}

function getAnyDailyById($id) {
    if (is_numeric($id)) {
        return run(sql::$getAnyDaily, array(':id'=>$id))->fetch();
    }
}

function share($id) {
    global $companyId;
    $parameters = array(
        ':id' => $id,
        ':company' => $companyId
    );
    if (run(sql::$dailyShare, $parameters)) {
        return true;
    } return false;
}

function unShare($id) {
    global $companyId;
    $parameters = array(
        ':id' => $id,
        ':company' => $companyId
    );
    if (run(sql::$dailyUnShare, $parameters)) {
        return true;
    } return false;
}