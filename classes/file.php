<?php 
//require_once '/var/www/classes/user.php';
//require_once '/var/www/classes/companyTier.php';
//require_once '/var/www/classes/data.php';
//require_once '/var/www/design/cards.php';
class File {
    private $id;
    private $url;
    private $name;
    private $size;
    private $date;
    private $uploader;
    private $conn;
    private $requiredLevel;
    public function setId($var)   { $this->id       = $var; }
    public function setUrl($var)  { $this->url      = $var; }
    public function setName($var) { $this->name     = $var; }
    public function setSize($var) { $this->size     = $var; }
    public function setRequiredLevel($var) { $this->requiredLevel = $var; }
    public function getId()                { return $this->id;   }
    public function getName($local = NULL) { return (!$local) ? noHTML($this->name) : $this->name; }
    public function getUrl()               { return $this->url;  }
    public function getSize()              { return $this->size; }
    public function getRequiredLevel()     { return $this->requiredLevel; }
    public function __CONSTRUCT($file = NULL, $users = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($file) {
            if (!isset($file['id'])) {
                $file = $file[0];
            }
            $this->id       = $file['id'];
            $this->url      = '/var/www'.$file['url'];
            $this->name     = $file['name'];
            $this->size     = $file['size'];
            $this->date     = $file['date'];
            if (isset($file['requiredLevel'])) {
                $this->requiredLevel = $file['requiredLevel'];
            }
            if ($users) {
                if (isset($users[$file['uploader']])) {
                    $this->uploader = $users[$file['uploader']];
                }
            } else {
                $this->uploader = getUserById($file['uploader']);
            }
        }
    }
    public function upload() {
        global $currentUser;
        global $companyId;
        $this->date = date(format::$time);
        $this->uploader = $currentUser;
        $parameters = array(
            ':url'     =>$this->url,
            ':name'    =>$this->name,
            ':size'    =>$this->size,
            ':date'    =>$this->date,
            ':uploader'=>($this->uploader)->getId(),
            ':company' =>$companyId
        );
        if (run(sql::$uploadFile, $parameters)) {
            $file = getFileById($this->conn->lastInsertId());
            $backup = new Backup();
            $backup->setObjectId($file->getId());
            $backup->setObjectName($file->getName());
            $backup->setObjectType('File');
            $backup->setAction('Upload');
            $backup->save();
            return $file;
        } 
        log::error(errors::$fileUploadFailure);
        return false;
    }
    public function delete() {
        if (file_exists($this->url)) {
            if (unlink($this->url)) {
                echo 'success';
            } else { echo 'failure'; }
        } else { log::error(errors::$noFileToDeleteError); }
        global $companyId;
        $parameters = array(':id'=>$this->id, ':company'=>$companyId);
        if (run(sql::$fileDeleteFailure, $parameters)) {
            $backup = new Backup();
            $backup->setObjectId($this->getId());
            $backup->setObjectName($this->getName());
            $backup->setObjectType('File');
            $backup->setAction('Delete');
            $backup->save();
            return true;
        } else {
            log::error(errors::$fileDeleteFailure);
        }
    }
}

function getUploadedSize($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $parameters = array(':company'=>$company);
    $data = run(sql::$getUploadedSize, $parameters);
    $data = $data->fetchAll();
    return $data[0]["SUM(size)"];
}

function getMonthUploadedSize($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $parameters = array(':month'=>date('m'), ':year'=>'20'.date('y'), ':company'=>$company);
    $data = run(sql::$getMonthUploadedSize, $parameters);
    $data = $data->fetchAll();
    return $data[0]['SUM(size)'];
}

function getFileById($var) {
    global $companyId;
    $parameters = array(':id'=>$var, ':company'=>$companyId);
    $data = run(sql::$getFileById, $parameters);
    $file = new File($data->fetchAll());
    return $file;
}

function getPublicFileById($var) {
    $parameters = array(':id'=>$var);
    $data = run(sql::$getPublicFileById, $parameters);
    $file = new File($data->fetchAll());
    return $file;
}

function getJobFiles($userArr = NULL) {
    global $companyId;
    $parameters = array(':company'=>$companyId);
    $data = run(sql::$getJobFiles, $parameters);
    $files = array();
    if (!$userArr) { 
        $users = getUsers();
        $userArr = array();
        foreach($users as $user) {
            $userArr[$user->getId()] = $user;
        }
    }
    foreach($data as $fd) {
        $file = new File($fd, $userArr);
        array_push($files, $file);
    }
    return $files;
}

function getDailyfilesById($dailyId) {
    $data = run(sql::$getDailyFiles, array(':daily' => $dailyId));
    $files = array();
    $users = getUsers();
    $userArr = array();
    foreach($users as $user) {
        $userArr[$user->getId()] = $user;
    }
    foreach($data as $fd) {
        $file = new File($fd, $userArr);
        array_push($files, $file);
    }
    return $files;
}


function getFileSizeByDaily($daily) {
    global $companyId;
    $data = run(sql::$getFileSizeByDaily, array(':daily'=>$daily->getId()));
    $data = $data->fetch();
    return $data[0];
}


function deleteFile($var) {
    $file = getFileById($var);
    if ($file) {
        if ($file->delete()) {
            return true;
        }
    } return false;
}

function fileUpload2($files) {
    $names = '';
    if (count(array_filter($files['file']['name'])) > 0) {
        foreach ($files['file']['name'] as $f => $name) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') { $ext = 'jpg';}
            $pictureName = randomString(100) . "." . $ext;
            //$pathtofile = $_SERVER["DOCUMENT_ROOT"] . "/efs/ANCHOR/{$pictureName}";
            $pathtofile =  "/var/www/{$pictureName}";
            $names = $names . "/files/{$pictureName}" . ';';
            $size = $files['file']['size'][$f];
            $fileObj = new File();
            $fileObj->setUrl();
            $fileObj->setName();
            $fileObj->setSize();

            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
                compress_image($_FILES["file"]["tmp_name"][$f], $pathtofile, 50);
            } else {
                $content = file_get_contents($files['file']["tmp_name"][$f]);
                $file = fopen($pathtofile, "w");
                if((fwrite($file, $content) == false) && (!file_exists($pathtofile))) {
                    fclose($file);
                    log::error(errors::$fileWriteFailure);
                } else {
                    fclose($file);
                }
            }
        }
    return $names;
    }
}


function jsFileUpload($data) {
    $fileObjs = array();
    foreach($data as $key => $var) {
        if ((strpos($key, '.') !== false) || (strpos($key, '.') !== false)) {
            //hack likely
        } else {
            $location = $key;
            $extension = $var;
            $storageName = randomString(100) . "." . $extension;
            $oldName = "/var/www/efs/tmp/{$location}.{$extension}";
            $newName = "/var/www/efs/ANCHOR/$storageName";
            if (file_exists($oldName)) {
                if (rename($oldName, $newName)) {
                    $fileObj = new File();
                    $fileObj->setUrl('/efs/ANCHOR/'.$storageName);
                    $fileObj->setName($storageName);
                    $fileObj->setSize(filesize($newName));
                    $uploadedFile = $fileObj->upload();
                    array_push($fileObjs, $uploadedFile);
                }
            }
        }
    }
    return $fileObjs;
}


function singleUpload($file) {
    if ($file) {
        $ext = getExt($file['file']['name']);
        $name = $file['file']['name'];
        $storageName = randomString(100) . "." . $ext;
        $pathtofile =  "/var/www/efs/ANCHOR/{$storageName}";
        $fileObj = new File();
        $fileObj->setUrl('/efs/ANCHOR/'.$storageName);
        $fileObj->setName($name);
        $fileObj->setSize($file['file']['size']);
        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
            compress_image($_FILES["file"]["tmp_name"], $pathtofile, 50);
        } else {
            $content = file_get_contents($file['file']["tmp_name"]);
            $file    = fopen($pathtofile, "w");
            if((fwrite($file, $content) == false) && (!file_exists($pathtofile))) {
                fclose($file);
                log::error(errors::$fileWriteFailure);
            } else {
                fclose($file);
            }
        }
        $uploadedFile = $fileObj->upload();
        return $uploadedFile;
    } return false;
}

function fileUpload($files) {
    $fileObjs = array();
    if (count(array_filter($files['file']['name'])) > 0) {
        foreach ($files['file']['name'] as $f => $name) {
            $ext = getExt($name);
            $storageName = randomString(100) . "." . $ext;
            $pathtofile =  "/var/www/efs/ANCHOR/{$storageName}";
            $fileObj = new File();
            $fileObj->setUrl('/efs/ANCHOR/'.$storageName);
            $fileObj->setName($name);
            $size = $files['file']['size'][$f];
            if ($size < 10) { log::error(errors::$fileWriteSizeFailure); }
            $fileObj->setSize($files['file']['size'][$f]);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
                compress_image($_FILES["file"]["tmp_name"][$f], $pathtofile, 50);
            } else {
                $content = file_get_contents($files['file']["tmp_name"][$f]);
                $file    = fopen($pathtofile, "w");
                if ((fwrite($file, $content) == false) || (!file_exists($pathtofile))) {
                    fclose($file);
                    log::error(errors::$fileWriteFailure);
                } else {
                    fclose($file);
                }
            }
            $uploadedFile = $fileObj->upload();
            array_push($fileObjs, $uploadedFile);
        }
    }
    return $fileObjs;
}

function getExt($name) {
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') { $ext = 'jpg'; }
    return $ext;
}

function compress_image($source_url, $destination_url, $quality) 
{
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg') {
        $exif = exif_read_data($source_url);
        $image = imagecreatefromjpeg($source_url); }
    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);
    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);
    if(isset($exif) && isset($exif['Orientation'])) {
        $orientation = $exif['Orientation'];
        if($orientation != 1){
            $deg = 0;
            switch ($orientation) {
                case 3:
                $deg = 180;
                break;
                case 6:
                $deg = 270;
                break;
                case 8:
                $deg = 90;
                break;
            }
            if ($deg) {
                $image = imagerotate($image, $deg, 0);        
            }
        }
    }
    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

function canUploadFiles() {
    if (getMaxFiles() < (getMonthUploadedSize()* .000000001)) {
        return false;
    } return true;
}

function sort_jobs_by_fileSize($a, $b) {
	if($a->getFileSize() == $b->getFileSize()){ return 0 ; }
	return ($a->getFileSize() < $b->getFileSize()) ? 1 : -1;
}

function downloadDaily($dailyId) {
    $daily = getDailyById($dailyId);
    $job = $daily->getJob();
    $date = (new Datetime($daily->getDate()))->format('m-d-Y');
    $zip = new ZipArchive();
    $tmpFile = @tempnam("/var/www/design/tmp", "zip");
    $zip_name = 'daily-' . $job->getNumber() . '-' . $date . '-' . $daily->getId() . '.zip';
    $zip->open($tmpFile,  ZipArchive::OVERWRITE);
    foreach ($daily->getFiles() as $file) {
        $path = $file->getUrl();
        if(file_exists($path)){
            $zip->addFromString($file->getName(true),  file_get_contents($path));  
        } else {
            error_log($file);
            error_log("file does not exist");
        }
    }
    $fileName = 'daily-' . $job->getNumber() . '-' . $date . '-' . $daily->getId() . '.pdf';
    $zip->addFromString($fileName,  dailyReport($dailyId)->output('S'));  
    $zip->close();
    
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zip_name);
    header('Content-Length: ' . filesize($tmpFile));
    ob_end_clean();
    readfile($tmpFile);
}

function downloadDailiesByJob($jobId) {
    $job = getJobById($jobId);
    $dailies = getDailysByJobId($jobId, true);
    $name = 'dailiesJob'.$job->getNumber().'.zip';
    $tmpFile = zipDailies($dailies);
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$name);
    header('Content-Length: ' . filesize($tmpFile));
    ob_end_clean();
    readfile($tmpFile);
}

function downloadDailiesByUser($userId) {
    $user = getUserById($userId);
    $dailies = getDailysByUserId($userId, true);
    $name = 'dailiesUser'.preg_replace('/\s+/', '', $user->getName()).'.zip';
    $tmpFile = zipDailies($dailies);
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$name);
    header('Content-Length: ' . filesize($tmpFile));
    ob_end_clean();
    readfile($tmpFile);
}

function zipDailies($dailies) {
    $tmpFile = @tempnam("/var/www/design/tmp", "zip");
    $zip = new ZipArchive();
    $zip->open($tmpFile,  ZipArchive::OVERWRITE);
    foreach($dailies as $daily) {

        $job = $daily->getJob();
        $date = (new Datetime($daily->getDate()))->format('m-d-Y');
        $dir = $job->getNumber() . '-' . $date;
        $zip->addEmptyDir($dir);
        foreach ($daily->getFiles() as $file) {
            $path = $file->getUrl();
            if(file_exists($path)){
                $zip->addFromString($dir.'/'.$file->getName(true),  file_get_contents($path));  
            } else {
                error_log($path);
                error_log("file does not exist");
            }
        }
        $pdf = dailyReport($daily->getId());
        $fileName = 'daily-' . $job->getNumber() . '-' . $date . '-' . $daily->getId() . '.pdf';
        $zip->addFromString($dir.'/'.$fileName,  $pdf->output('S')); 
    }
    $zip->close();
    return $tmpFile;
}


function materialsReport($jobId) {
    $jobMaterials = getMaterialsByJob($jobId);
    if (count($jobMaterials) <= 0) {
        return false;
    }
    $global_price_total = 0;
    $data = array();
    foreach($jobMaterials as $jobMaterial) {
        $row = array();
        if ($jobMaterial->isCustom()) {
            $quantity = $jobMaterial->getQuantity();
            $local_price_total = (double)$quantity * (float)$jobMaterial->getPrice();
            $global_price_total += (float)$local_price_total;
            $row['NUMBER'] =         '';
            $row['NAME'] =           $jobMaterial->getName();
            $row['INVENTORY'] =      '';
            $row['NON-INVENTORY'] =  '';
            $row['QUANTITY'] =       $quantity;
            $row['PRICE_PER_UNIT'] = (float)$jobMaterial->getPrice();
            $row['TOTAL_PRICE'] =    (float)$local_price_total;
        } else {
            $quantity = $jobMaterial->getInventory() + $jobMaterial->getNonInventory();
            $local_price_total = $quantity * (float)$jobMaterial->getPrice();
            $global_price_total += $local_price_total;
            $row['NUMBER'] =         $jobMaterial->getMaterial()->getNumber();
            $row['NAME'] =           $jobMaterial->getName();
            $row['INVENTORY'] =      (float)$jobMaterial->getInventory();
            $row['NON-INVENTORY'] =  (float)$jobMaterial->getNonInventory();
            $row['QUANTITY'] =       $quantity;
            $row['PRICE_PER_UNIT'] = (float)$jobMaterial->getPrice();
            $row['TOTAL_PRICE'] =    (float)$local_price_total;
        }
        array_push($data, $row);
    }
    $row['NUMBER'] =         "TOTALS";
    $row['NAME'] =           "";
    $row['INVENTORY'] =      "";
    $row['NON-INVENTORY'] =  "";
    $row['QUANTITY'] =       "";
    $row['PRICE_PER_UNIT'] = "";
    $row['TOTAL_PRICE'] =    (float)$global_price_total;
    array_push($data, $row);

    $flag = false;
    $matXl = "";
    foreach($data as $row) {
        if(!$flag) {
            $matXl .= implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        array_walk($row, 'filterData');
        $matXl .= implode("\t", array_values($row)) . "\n";
    }
    return $matXl;
}



function downloadJob($jobId) {
    //pdf of job
    //dailies
    //files
    //hours
    //materials
    $job = getJobById($jobId);
    $tmpFile = @tempnam("/var/www/design/tmp", "zip");
    $zip = new ZipArchive();
    $zip->open($tmpFile,  ZipArchive::OVERWRITE);
    //job pdf
    $zip->addFromString('job'.$job->getNumber().'.pdf', jobReport($job)->output('S'));
    //dailies
    $dailies = getDailysByJobId($jobId, true);
    foreach($dailies as $daily) {
        $date = (new Datetime($daily->getDate()))->format('m-d-Y');
        $dir = 'daily' . '-' . $date . '-' . $daily->getId();
        $zip->addEmptyDir($dir);
        foreach ($daily->getFiles() as $file) {
            $path = $file->getUrl();
            if(file_exists($path)){
                $zip->addFromString($dir.'/'.$file->getName(true),  file_get_contents($path));  
            } else {
                error_log($path);
                error_log("file does not exist");
            }
        }
        $pdf = dailyReport($daily->getId());
        $fileName = 'dailyReport.pdf';
        $zip->addFromString($dir.'/'.$fileName,  $pdf->output('S')); 
    }
    //files
    $files = $job->getFiles();
    if (is_array($files)) {
        $zip->addEmptyDir('Files');
        foreach($files as $file) {
            $path = $file->getUrl();
            if(file_exists($path)){
                $zip->addFromString('Files'.'/'.$file->getName(true),  file_get_contents($path));  
            } else {
                error_log($path);
                error_log("file does not exist");
            }
        }
    }
    $materials = materialsReport($job->getId());
    if ($materials) {
        $zip->addFromString('Materials'.$job->getNumber().'.xls', materialsReport($job->getId()));
        $zip->addEmptyDir('Receipts');
        foreach($materials as $jm) {
            if (!empty($jm->getPhoto())) {
                $file = $jm->getPhoto();
                $path = $file->getUrl();
                if(file_exists($path)){
                    $zip->addFromString('Receipts'.'/'.$file->getName(true),  file_get_contents($path));  
                } else {
                    error_log($path);
                    error_log("file does not exist");
                }
            }
        }
    }
    $rows = array();
    $dailies = getDailysByJobId($jobId);
    if (count($dailies) > 0) {
        $data = array();
        $workTypes = getWorkTypes();
        $jobCol = 'Job ' . $job->getNumber();
        $totals = array();
        $totals[$jobCol] = 'Totals:';
        $totals['User'] = '';
        foreach($workTypes as $wt) {
            $totals[$wt->getName()] = 0;
        }
        $totals['Other'] = 0;
        $totals['Totals:'] = 0;
        foreach($dailies as $daily) {
            foreach($daily->getWorkers() as $workHours) {
                $workerName = ($workHours->getWorker())->getName();
                if (!isset($data[$workerName])) {
                    $rowTotal = 0;
                    $row = array();
                    $row[$jobCol] = '';
                    $row['User'] = $workerName;
                    $set = false;
                    foreach($workTypes as $wt) {
                        $row[$wt->getName()] = '';
                        if ($workHours->getType() == $wt->getId()) {
                            $set = true;
                            $row[$wt->getName(true)] = $workHours->getHours();
                            $rowTotal = $workHours->getHours();
                            $totals[$wt->getName(true)] += $workHours->getHours();
                            $totals['Totals:'] += $workHours->getHours();
                        }
                    }
                    if (!$set) {
                        $row['Other'] = $workHours->getHours();
                        $rowTotal = $workHours->getHours();
                        $totals['Other'] += $workHours->getHours();
                        $totals['Totals:'] += $workHours->getHours();
                    } else {
                        $row['Other'] = '';
                    }
                    $row["Totals:"] = $rowTotal;
                } else {
                    $row = $data[$workerName];
                    $set = false;
                    foreach($workTypes as $wt) {
                        if ($workHours->getType() == $wt->getId()) {
                            $set = true;
                            $row[$wt->getName(true)] = (float)$row[$wt->getName()] + (float)$workHours->getHours();
                            $row["Totals:"] += $workHours->getHours();
                            $totals[$wt->getName(true)] += $workHours->getHours();
                            $totals['Totals:'] += $workHours->getHours();
                            break;
                        }
                    }
                    if (!$set) {
                        $row["Other"] = (float)$row["Other"] + (float)$workHours->getHours();
                        $row["Totals:"] += $workHours->getHours();
                        $totals["Other"] += $workHours->getHours();
                        $totals['Totals:'] += $workHours->getHours();
                    }
                }
                $data[$workerName] = $row;
            }
        }
        array_push($data, $totals);
        $fileName = "jobReport" . $job->getNumber() . ".xls";
        $flag = false;
        $jReport = "";
        foreach($data as $row) {
            if(!$flag) {
                $jReport .= implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }
            array_walk($row, 'filterData');
            $jReport .= implode("\t", array_values($row)) . "\n";
        }
        $zip->addFromString($fileName, $jReport);
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename="job-'.$job->getNumber().'.zip"');
    header('Content-Length: ' . filesize($tmpFile));
    ob_end_clean();
    readfile($tmpFile);
}


function sort_dailies_by_fileSize($a, $b) {
	if($a->getFileSize() == $b->getFileSize()){ return 0 ; }
	return ($a->getFileSize() < $b->getFileSize()) ? 1 : -1;
}

function filterData(&$str) 
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function dailyFileList() {
    $dailies = getDailiesWithFiles();
    $accDailies = array();
    $totalSize = 0;
    foreach($dailies as $daily) {

        if ($daily->getFileSize()) {
            array_push($accDailies, $daily);
            $totalSize += $daily->getFileSize();
        }
    }

    usort($accDailies, 'sort_dailies_by_fileSize');
    $i = 0;
    $totalSize = round($totalSize * .000001, 2);
    echo "<h3> Dailies </h3>
    <h5> Total Size : {$totalSize} MB</h5>";
    foreach($accDailies as $daily) {
        $job = $daily->getJob();
        $size = round($daily->getFileSize() * .000001, 2);
        echo "
        <div class='card'>
            <div class='card-header'>
                <h5 style='display:inline-block;text-align:left' class='mb-0' style=''>
                    <a href='/daily?id={$daily->getId()}'>Daily</a> 
                    for Job 
                    <a href='/job?id={$job->getId()}'>
                    {$job->getName(true)} 
                    </a> 
                </h5>
                <a style='display:inline-block;' href='/job?id={$job->getId()}'>
                    <h5 style='display:inline-block;' style='text-align:left'>
                    </h5>
                </a>
                <p style='display:inline-block;float:right' >
                    {$size} MB
                </p>
            </div>
        </div>";
        $i++;
    }
}


function jobFileList() {
    $jobs = getJobs();
    $accJobs = array();
    $totalSize = 0;
    foreach($jobs as $job) {
        if ($job->getFileSize()) {
            //echo $job->getFileSize();
            //echo '<br />';
            array_push($accJobs, $job);
            $totalSize += $job->getFileSize();
        }
    }
    usort($accJobs, 'sort_jobs_by_fileSize');
    $i = 0;
    $totalSize = round($totalSize * .000001, 2);
    echo "<h3> Jobs </h3>
    <h5> Total Size : {$totalSize} MB</h5>";
    foreach($accJobs as $job) {
        $size = round($job->getFileSize() * .000001, 2);
        echo "
        <div class='card'>
            <div class='card-header'>
                <a href='/job?id={$job->getId()}';
                    <button class='btn btn-link'>
                        <h5 style='display:inline-block;' class='mb-0' style='text-align:left'>
                            {$job->getNumber()} 
                        </h5>
                    </button>
                </a>
                <p style='display:inline-block;float:right' >
                    {$size} MB
                </p>
            </div>
        </div>";
        $i++;
    }
}



function jobHoursReport($jobId) {
    $rows = array();
    $dailies = getDailysByJobId($jobId);
    $job = getJobById($jobId);
    $data = array();
    $workTypes = getWorkTypes();
    $jobCol = 'Job ' . $job->getNumber();

    //build totals array
    $totals = array();
    $totals[$jobCol] = 'Totals:';
    $totals['User'] = '';
    foreach($workTypes as $wt) {
        $totals[$wt->getName()] = 0;
    }
    $totals['Other'] = 0;
    $totals['Totals:'] = 0;

    //build data arrays
    foreach($dailies as $daily) {
        foreach($daily->getWorkers() as $workHours) {
            $workerName = ($workHours->getWorker())->getName();
            //if user row doesn't exist:
            if (!isset($data[$workerName])) {
                $rowTotal = 0;
                $row = array();
                $row[$jobCol] = '';
                $row['User'] = $workerName;
                $set = false;
                foreach($workTypes as $wt) {
                    $row[$wt->getName(true)] = '';
                    if ($workHours->getType() == $wt->getId()) {
                        $set = true;
                        $row[$wt->getName(true)] = $workHours->getHours();
                        $rowTotal = $workHours->getHours();
                        $totals[$wt->getName(true)] += $workHours->getHours();
                        $totals['Totals:'] += $workHours->getHours();
                    }
                }
                if (!$set) {
                    $row['Other'] = $workHours->getHours();
                    $rowTotal = $workHours->getHours();
                    $totals['Other'] += $workHours->getHours();
                    $totals['Totals:'] += $workHours->getHours();
                } else {
                    $row['Other'] = '';
                }
                $row["Totals:"] = $rowTotal;
            //if the row does exist, update vals
            } else {
                $row = $data[$workerName];
                $set = false;
                foreach($workTypes as $wt) {
                    if ($workHours->getType() == $wt->getId()) {
                        $set = true;
                        $row[$wt->getName(true)] = (float)$row[$wt->getName()] + (float)$workHours->getHours();
                        $row["Totals:"] += $workHours->getHours();
                        $totals[$wt->getName(true)] += $workHours->getHours();
                        $totals['Totals:'] += $workHours->getHours();
                        break;
                    }
                }
                if (!$set) {
                    $row["Other"] = (float)$row["Other"] + (float)$workHours->getHours();
                    $row["Totals:"] += $workHours->getHours();
                    $totals["Other"] += $workHours->getHours();
                    $totals['Totals:'] += $workHours->getHours();
                }
            }
            $data[$workerName] = $row;
        }
    }
    array_push($data, $totals);
    //$fileName = "jobReport" . $job->getNumber() . ".xls";

    $final = '';
    $flag = false;
    foreach($data as $row) {
        if(!$flag) {
            // display column names as first row
            $final .= implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        // filter data
        array_walk($row, 'filterData');
        $final .= implode("\t", array_values($row)) . "\n";
    }
    return $final;
}



function jobImport($jobId) {
    $job = getJobById($jobId);
    $totalHours = 0;
    $totalMileage = 0;
    $totalMaterial = 0;
    $data = array();
    foreach($jobMaterials as $jobMaterial) {
        $row = array();
        if ($jobMaterial->isCustom()) {
            $quantity = $jobMaterial->getQuantity();
            $local_price_total = (double)$quantity * (float)$jobMaterial->getPrice();
            $global_price_total += (float)$local_price_total;
            $row['NUMBER'] =         '';
            $row['NAME'] =           $jobMaterial->getName();
            $row['INVENTORY'] =      '';
            $row['NON-INVENTORY'] =  '';
            $row['QUANTITY'] =       $quantity;
            $row['PRICE_PER_UNIT'] = (float)$jobMaterial->getPrice();
            $row['TOTAL_PRICE'] =    (float)$local_price_total;
        } else {
            $quantity = $jobMaterial->getInventory() + $jobMaterial->getNonInventory();
            $local_price_total = $quantity * (float)$jobMaterial->getPrice();
            $global_price_total += $local_price_total;
            $row['NUMBER'] =         $jobMaterial->getMaterial()->getNumber();
            $row['NAME'] =           $jobMaterial->getName();
            $row['INVENTORY'] =      (float)$jobMaterial->getInventory();
            $row['NON-INVENTORY'] =  (float)$jobMaterial->getNonInventory();
            $row['QUANTITY'] =       $quantity;
            $row['PRICE_PER_UNIT'] = (float)$jobMaterial->getPrice();
            $row['TOTAL_PRICE'] =    (float)$local_price_total;
        }
        array_push($data, $row);
    }
    $row['NUMBER'] =         "TOTALS";
    $row['NAME'] =           "";
    $row['INVENTORY'] =      "";
    $row['NON-INVENTORY'] =  "";
    $row['QUANTITY'] =       "";
    $row['PRICE_PER_UNIT'] = "";
    $row['TOTAL_PRICE'] =    (float)$global_price_total;
    array_push($data, $row);

    $flag = false;
    $matXl = "";
    foreach($data as $row) {
        if(!$flag) {
            $matXl .= implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        array_walk($row, 'filterData');
        $matXl .= implode("\t", array_values($row)) . "\n";
    }
    return $matXl;
}