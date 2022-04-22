<?php
require_once('/var/www/globals.php');
require_once '/var/www/vendor/autoload.php';
  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function getJobXlsById($var) {
    global $companyId;
    $parameters = array(':'.jobData::$company => $companyId, ':'.jobData::$id => $var);
    $returns = run(sql::$getJobById, $parameters);
    $job = createXlsJob($returns);
    return $job;
}

function createXlsJob($returns) {
    $job = createXlsJobs($returns);
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


function createXlsJobs($returns) {
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
        $job = new JobXls($return);
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

class JobXls extends Job {
    private $spreadsheet;
    private $positions;
    private $sheetPointer;
    public function __CONSTRUCT($jobArray = NULL) {
        parent::__CONSTRUCT($jobArray);
        $this->spreadsheet = new Spreadsheet();

    }

    private function setupJobSheet(&$sheet) {
        $sheet->setTitle('Job');
        $sheet->setCellValue('A1', 'NAME');
        $sheet->setCellValue('A2', $this->getName(true));
        $sheet->setCellValue('B1', 'NUMBER');
        $sheet->setCellValue('B2', $this->getNumber(true));
        $sheet->setCellValue('C1', 'CREATOR');
        if ($this->getCreator()) {
            $sheet->setCellValue('C2', $this->getCreator()->getName(true));
        }
        $sheet->setCellValue('D1', 'CREATE DATE');
        $sheet->setCellValue('D2', date('F d', strtotime($this->getCreateDate())));
        $sheet->setCellValue('E1', 'TYPE');
        $sheet->setCellValue('E2', $this->getType()->getName(true));
        $sheet->setCellValue('F1', 'WORKERS');
        $i = 2;
        foreach($this->getWorkers() as $worker) {
            $sheet->setCellValue('F'.$i, $worker->getName(true));
            $i++;
        }
        $sheet->setCellValue('G1', 'LOCATION');
        $sheet->setCellValue('G2', $this->getLocation(true));
        $sheet->setCellValue('H1', 'PO NUMBER');
        $sheet->setCellValue('H2', ($this->getPoNumber(true)) ?? '');
        $sheet->setCellValue('I1', 'BID');
        $sheet->setCellValue('I2', $this->getBid(true));
        $sheet->setCellValue('J1', 'DESCRIPTION');
        $sheet->setCellValue('J2', $this->getDescription(true));
        $sheet->setCellValue('K1', 'NOTES');
        $sheet->setCellValue('K2', $this->getNotes(true));
        $this->format($sheet);
    }
    private function setupClientSheet(&$sheet) {
        $client = $this->getClient() ?? new Client();
        $sheet->setTitle('Client');
        $sheet->setCellValue('A1', 'NAME');
        $sheet->setCellValue('A2', $client->getName(true));
        $sheet->setCellValue('B1', 'PHONE');
        $sheet->setCellValue('B2', $client->getPhone(true));
        $sheet->setCellValue('C1', 'EMAIL');
        $sheet->setCellValue('C2', $client->getEmail(true));
        $sheet->setCellValue('D1', 'ADDRESS');
        $sheet->setCellValue('D2', $client->getAddress(true));
        $this->format($sheet);
    }
    private function setupDailySheet(&$sheet) {
        $startTime = microtime(true);
        $sheet->setTitle('Daily Reports');
        $sheet->setCellValue('A2', 'TOTALS');
        $workTypes = getWorkTypes();
        $dailys = getDailysByJobId($this->getId());
        $wt = array();
        $dailyHours = array();
        foreach($dailys as $daily) {
            array_push($dailyHours, array($daily, getHoursByDaily($daily->getId())));
        }
        foreach($workTypes as $workType) {
            $totalHours = 0;
            foreach($dailyHours as $daily) {
                foreach($daily[1] as $hour) {
                    if ($hour->getType() == $workType->getId()) {
                        $totalHours += $hour->getHours();
                    }
                }
            }
            array_push($wt, array($workType, $totalHours));
        }
        $i = 'A';
        foreach($wt as $workType) {
            ++$i;
            $sheet->setCellValue($i . 1, $workType[0]->getName(true));
            $sheet->setCellValue($i . 2, $workType[1]);
        }
        ++$i;++$i;
        $totalMileage = 0;
        $sheet->setCellValue($i . 1, 'MILEAGE');
        foreach($dailys as $daily) {
            $totalMileage += (is_numeric($daily->getMileage())) ? $daily->getMileage() : 0;
        }
        $sheet->setCellValue($i . 2, $totalMileage);
        $this->format($sheet);
    }
    private function setupMaterialSheet(&$sheet) {
        $sheet->setTitle('Materials');
        $materials = getMaterialsByJob($this->getId());
        $sheet->setCellValue('A1', 'MATERIAL');
        $sheet->setCellValue('B1', 'UNIT COST');
        $sheet->setCellValue('C1', 'QUANTITY');
        $sheet->setCellValue('D1', 'TOTAL COST');
        $i = '2';
        $total = 0;
        foreach($materials as $material) {
            $sheet->setCellValue('A'.$i, $material->getName(true));
            $sheet->setCellValue('B'.$i, $material->getPrice());
            $quantity = ($material->isCustom()) ? $material->getQuantity() : $material->getInventory() + $material->getNonInventory();
            $sheet->setCellValue('C'.$i, $quantity);
            $priceTotal = $material->getPrice() * $quantity;
            $sheet->setCellValue('D'.$i, $priceTotal);
            $total+= $priceTotal;
            $i++;
        }
        $sheet->setCellValue('A' . $i, 'TOTAL:');
        $sheet->setCellValue('D' . $i, $total);
        $this->format($sheet);
    }

    public function output() {
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupJobSheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupClientSheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupDailySheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupMaterialSheet($sheet);
        ob_clean();
        ob_start();
        $writer = new Xlsx($this->spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="JobData.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
    }
    public function getSheet() {
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupJobSheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupClientSheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupDailySheet($sheet);

        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
        $sheet = $this->spreadsheet->getActiveSheet();
        $this->setupMaterialSheet($sheet);
        $writer = new Xlsx($this->spreadsheet);
        return $this->spreadsheet;
    }
    private function format(&$sheet) {
        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}

?>