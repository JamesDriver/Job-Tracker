<?php
require_once('/var/www/globals.php');
require_once '/var/www/vendor/autoload.php';
  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TimecardsPdf extends Timecards {
    private $pdf;
    public function __CONSTRUCT($start, $end) {
        parent::__CONSTRUCT($start, $end);
        $this->pdf = new TimecardPdf();
        $this->pdf->setStart($this->start);
        $this->pdf->setEnd($this->end);
        $this->writePdf();
    }

    private function writePdf() {
        foreach($this->userHours as $key => $userData) {
            $user = getUserById($key);
            $this->pdf->writeTimecard($user, $userData);
        }
    }

    public function output() {
        $this->pdf->Output('D');
    }
}

class TimecardTotals {
    private $data;
    private $dailyTotals;
    private $jobTotals;
    private $worktypeTotals;
    private $totalHours;
    public function getDailyTotals()    { return $this->dailyTotals;    }
    public function getJobTotals()      { return $this->jobTotals;      }
    public function getWorktypeTotals() { return $this->worktypeTotals; }
    public function getTotalHours()     { return $this->totalHours;     }

    public function __CONSTRUCT($data) {
        $this->setDailyTotals($data);
        $this->setJobTotals($data);
        $this->setWorktypeTotals($data);
        $this->setTotalHours($data);
        $this->data = $data;
    }
    private function setDailyTotals($data) {
        foreach($data as $jobData) {
            foreach($jobData as $wtData) {
                foreach($wtData as $hours) {
                    $date = new DateTime($hours['date']);
                    $var = &$this->dailyTotals[$date->format('m-d')];
                    $var = (isset($var)) ? $var + $hours['hours'] : $hours['hours'];
                }
            }
        }
    }
    private function setJobTotals($data) {
        foreach($data as $jobId => $jobData) {
            foreach($jobData as $wtData) {
                foreach($wtData as $hours) {
                    $var = &$this->jobTotals[$jobId];
                    $var = (isset($var)) ? $var + $hours['hours'] : $hours['hours'];
                }
            }
        }
    }
    private function setWorktypeTotals($data) {
        foreach($data as $jobData) {
            foreach($jobData as $wtId => $wtData) {
                foreach($wtData as $hours) {
                    $var = &$this->worktypeTotals[$wtId];
                    $var = (isset($var)) ? $var + $hours['hours'] : $hours['hours'];
                }
            }
        }
    }
    private function setTotalHours($data) {
        $this->totalHours = 0;
        foreach($data as $jobData) {
            foreach($jobData as $wtId => $wtData) {
                foreach($wtData as $hours) {
                    $this->totalHours += $hours['hours'];
                }
            }
        }
    }
    public function getJobWorkTypeTotal($jobId, $worktypeId) {
        $final = 0;
        foreach($this->data as $jId => $jobData) {
            if ($jId == $jobId) {
                foreach($jobData as $wtId => $wtData) {
                    if ($wtId == $worktypeId) {
                        foreach($wtData as $hours) {
                            $final += $hours['hours'];
                        }
                    }
                }
            }
        }
        return $final;
    }
}

class TimecardCell {
    private $width;
    private $height;
    private $x;
    private $y;
    public function __CONSTRUCT($width, $height, $x, $y) {
        $this->width = $width;
        $this->height = $height;
        $this->x = $x;
        $this->y = $y;
    }
    public function getWidth()  { return $this->width;  }
    public function getHeight() { return $this->height; }
    public function getX()      { return $this->x; }
    public function getY()      { return $this->y; }
}

class TimecardPdf extends FPDF {
    private $start;
    private $end;
    private $user;
    private $timecardData;
    private $defaultLineHeight = 5;
    private $hourMinWidth = 10;
    private $hourMaxWidth = 25;
    private $workTypes = array();
    private $jobs = array();
    private $dateCells;


    private $worktypeWidth = 35;
    private $jobWidth = 25;
    private $totalWidth = 15;

    private $totals;

    public function setStart($start) { $this->start = $start; }
    public function setEnd($end) { $this->end = $end; }

    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
        $tmpWt = getWorkTypes();
        foreach($tmpWt as $wt) {
            $this->workTypes[$wt->getId()] = $wt;
        }
    }

    function writeTimecard($user, $timecard) {
        $this->user = $user;
        $this->timecardData = $timecard;
        $this->totals = new TimecardTotals($timecard);
        $this->AddPage('L');
        $this->PageTitle($user->getName());
        $this->timecardBody($timecard);
    }

    function timecardBody($timeData) {
        $this->addHeader();
        foreach($timeData as $jobKey => $jobData) {

            $job = getJobById($jobKey);
            $this->Cell($this->jobWidth,$this->defaultLineHeight,$job->getNumber(),1,0,'L',false);
            $wtCount = 0;
            foreach($jobData as $workTypeKey => $wtData) {
                $wtCount++;
                if ($wtCount > 1) {
                    $this->Cell($this->jobWidth,$this->defaultLineHeight,'',1,0,'L',false);
                }
                $workType = $this->workTypes[$workTypeKey];
                $this->Cell($this->worktypeWidth,$this->defaultLineHeight,$workType->getName(),1,0,'L',false);
                $this->Cell($this->totalWidth,$this->defaultLineHeight,$this->totals->getJobWorkTypeTotal($jobKey, $workTypeKey),1,0,'L',false);
                $tmp = array();

                foreach($wtData as $hours) {

                    $date = new DateTime($hours['date']);
                    if (isset($tmp[$date->format('m-d')])) {
                        $tmp[$date->format('m-d')] += $hours['hours'];
                    } else {
                        $tmp[$date->format('m-d')] = $hours['hours'];
                    }
                }
                foreach($this->dateCells as $key => $var) {
                    $cellVal = (isset($tmp[$key])) ? $tmp[$key] : '';
                    $this->Cell($var->getWidth(),$this->defaultLineHeight,$cellVal,1,0,'L',false);
                }
                $this->ln();
            }
        }
        $this->addFooter();
    }

    function addHeader() {
        $this->Cell($this->jobWidth,$this->defaultLineHeight,'Job',1,0,'L',false);
        $this->Cell($this->worktypeWidth,$this->defaultLineHeight,'WorkType',1,0,'L',false);
        $this->Cell($this->totalWidth,$this->defaultLineHeight,'Totals',1,0,'L',false);
        $interval = DateInterval::createFromDateString('1 day');
        $this->end->setTime(0,0,1);
        $period = new DatePeriod($this->start, $interval, $this->end);
        $once = true;
        $dayCount = 0;
        foreach ($period as $dt) {
            $dayCount++;
            //if (($this->pageWidth() < ($this->getX() + $this->hourWidth)) && $once) {
            //
            //    //$this->timecardBody($this->user, $this->truncateData($dt));
            //    $once = false;
            //    break;
            //}
            //$this->Cell($this->hourWidth,$this->defaultLineHeight,$dt->format("m-d"),1,0,'L',false);
        }
        //if ($this->pageWidth() < $this->getX() + ($dayCount * $this->hourMinWidth)) {
        //    error_log('nope');
        //} else {
            $width = ($this->pageWidth() - $this->getX())/$dayCount;
            foreach($period as $dt) {
                $cell = new TimecardCell($width, $this->defaultLineHeight, $this->getX(), $this->getY());
                $this->Cell($width,$this->defaultLineHeight,$dt->format("m-d"),1,0,'L',false);
                $this->dateCells[$dt->format("m-d")] = $cell;
            }
        //}
        $this->ln();
    }

    function addFooter() {
        $this->Cell($this->jobWidth,$this->defaultLineHeight,'Totals:',1,0,'L',false);
        $this->Cell($this->worktypeWidth,$this->defaultLineHeight,'',1,0,'L',false);
        $this->Cell($this->totalWidth,$this->defaultLineHeight,$this->totals->getTotalHours(),1,0,'L',false);
        $interval = DateInterval::createFromDateString('1 day');
        $this->end->setTime(0,0,1);
        $period = new DatePeriod($this->start, $interval, $this->end);
        $once = true;
        $dayCount = 0;
        foreach($this->dateCells as $key => $var) {
            $cellVal = (isset($this->totals->getDailyTotals()[$key])) ? $this->totals->getDailyTotals()[$key] : '';
            $this->Cell($var->getWidth(),$this->defaultLineHeight,$cellVal,1,0,'L',false);
        }
        $this->ln();
    }

    function truncateData($startDate) {
        $newData = $this->timecardData;
        $returnData = $this->timecardData;
        foreach($newData as $jdKey => $jobData) {
            foreach($jobData as $key => $wtData) {
                foreach($wtData as $hours) {
                    $tmpDate = new DateTime($hours['date']);
                    if ($tmpDate < $startDate) {
                        unset($returnData[$jdKey][$key]);
                        if (empty($returnData[$jdKey])) {
                            unset($returnData[$jdKey]);
                        }
                    }
                }
            }
        }
        return $returnData;
    }

    function pageWidth() {
        $width = $this->w;
        $leftMargin = $this->lMargin;
        $rightMargin = $this->rMargin;
        return $width-$rightMargin-$leftMargin;
    }
    function PageTitle($label) {
        // Title
        $this->SetFont('Arial','',24);
        $this->SetFillColor(200,220,255);
        $this->Cell(0,10,"$label",0,1,'L',true);
        $this->Ln(4);
        $this->SetFont('Arial','',12);
        // Save ordinate
        $this->y0 = $this->GetY();
    }
}


class TimecardsExcel extends Timecards {
    private $spreadsheet;
    private $positions;
    private $sheetPointer;
    public function __CONSTRUCT($start, $end) {
        parent::__CONSTRUCT($start, $end);
        $this->spreadsheet = new Spreadsheet();
        $this->createSheet();
    }

    private function setupSheet(&$sheet) {
        $sheet->setCellValue('A1', 'Job');
        $this->positions['job'] = 'A';
        $sheet->setCellValue('B1', 'Work Type');
        $this->positions['worktype'] = 'B';
        $sheet->setCellValue('C1', 'Totals');
        $this->positions['totals'] = 'C';
        $countPd = 'D';
        $interval = DateInterval::createFromDateString('1 day');
        $this->end->setTime(0,0,1);
        $period = new DatePeriod($this->start, $interval, $this->end);
        foreach ($period as $dt) {
            $sheet->setCellValue($countPd . $this->sheetPointer, $dt->format("m-d"));
            $this->positions[$dt->format("m-d")] = $countPd;
            ++$countPd;
        }
        $this->positions['description'] = $countPd;
        $this->sheetPointer = 2;
    }

    private function setWorkTypes() {
        $tmp = getWorkTypes();
        $workTypes = array();
        //default for null
        $tmpWt = new WorkType();
        $tmpWt->setName('(unset)');
        $workTypes[''] = $tmpWt;
        foreach($tmp as $wt) {
            $workTypes[$wt->getId()] = $wt;
        }
        return $workTypes;
    }

    private function createSheet() {
        $i = 0;
        $workTypes = $this->setWorkTypes();
        foreach($this->userHours as $key => $userData) {
            $this->sheetPointer = 1;
            $user = getUserById($key);
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->setTitle($user->getName());
            $this->setupSheet($sheet);
            foreach($userData as $key => $jobData) {
                $job = getJobById($key);
                $sheet->setCellValue($this->positions['job'].$this->sheetPointer, $job->getNumber());
                foreach($jobData as $key => $workTypeData) {
                    $tmpTotal = 0;
                    $workType = $workTypes[$key];
                    $coord = $this->positions['worktype'] . $this->sheetPointer;
                    $sheet->setCellValue($coord, $workType->getName());
                    //get worktype;
                    foreach($workTypeData as $hourData) {
                        $tmpDate = new DateTime($hourData['date']);
                        $coord = $this->positions[$tmpDate->format('m-d')] . $this->sheetPointer;
                        $tmpVal = $sheet->getCell($coord)->getValue() + $hourData['hours'];
                        $sheet->setCellValue($coord, $tmpVal);
                        $tmpTotal += $hourData['hours'];
                        $coord = $this->positions['description'] . $this->sheetPointer;
                        if (empty($sheet->getCell($coord)->getValue())) {
                            $tmpVal = $hourData['description'];
                        } else {
                            $tmpVal = $sheet->getCell($coord)->getValue() . "\n" . $hourData['description'];
                        }
                        $sheet->setCellValue($coord, $tmpVal);
                        $sheet->getStyle($coord)->getAlignment()->setWrapText(true);
                    }
                    $coord = $this->positions['totals'] . $this->sheetPointer;
                    $sheet->setCellValue($coord, $tmpTotal);

                    $this->sheetPointer++;
                }
            }
            //create new sheet
            //set new sheet to active
            $this->addColTotals($sheet, $userData);
            $this->sheetPointer++;
            $this->sheetPointer++;
            $this->addWorkTypeTotals($sheet, $userData);

            $i++;
            if ($i < $this->userCount) {
                $this->spreadsheet->createSheet();
                $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex()+1);
            }
            $this->format($sheet);
        }
    }

    private function addColTotals(&$sheet, $userData) {
        $coord = $this->positions['job'] . $this->sheetPointer;
        $sheet->setCellValue($coord, 'Totals:');

        $totals = array();
        foreach($userData as $key => $jobData) {
            foreach($jobData as $key => $workTypeData) {
                foreach($workTypeData as $hourData) {
                    $tmpDate = new DateTime($hourData['date']);
                    if (isset($totals[$tmpDate->format('m-d')])) {
                        $totals[$tmpDate->format('m-d')] += $hourData['hours'];
                    } else {
                        $totals[$tmpDate->format('m-d')] = $hourData['hours'];
                    }
                }
            }
        }
        $finTotal = 0;
        foreach($totals as $key => $total) {
            $coord = $this->positions[$key] . $this->sheetPointer;
            $sheet->setCellValue($coord, $total);
            $finTotal += $total;
        }
        $coord = $this->positions['totals'] . $this->sheetPointer;
        $sheet->setCellValue($coord, $finTotal);
    }
    private function addWorkTypeTotals(&$sheet, $userData) {
        $workTypes = $this->setWorkTypes();
        $workTypeHours = array();
        foreach($userData as $key => $jobData) {
            foreach($jobData as $key => $workTypeData) {
                foreach($workTypeData as $hourData) {
                    if (isset($workTypeHours[$key])) {
                        $workTypeHours[$key] += $hourData['hours'];
                    } else {
                        $workTypeHours[$key] = $hourData['hours'];
                    }
                }
            }
        }
        foreach($workTypeHours as $key => $wth) {
            $coord = $this->positions['worktype'] . $this->sheetPointer;
            $sheet->setCellValue($coord, $workTypes[$key]->getName());
            $coord = $this->positions['totals'] . $this->sheetPointer;
            $sheet->setCellValue($coord, $wth);
            $this->sheetPointer++;
        }
    }

    private function format(&$sheet) {
        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    public function output() {
        ob_clean();
        ob_start();
        $writer = new Xlsx($this->spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="timecards.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
    }
    public function getSheet() {
        return $this->spreadsheet;
    }
}

class Timecards {
    protected $userHours;
    protected $start;
    protected $end;
    protected $userCount = 0;
    public function __CONSTRUCT($start, $end) {
        $this->start = new DateTime($start);
        $this->end = new DateTime($end);
        $this->userHours = $this->getUserHours($start, $end);

    }

    private function getUserHours($start, $end) {
        global $companyId;
        $data = run(sql::$getWorkerHours, array(':start'=>$start, ':end'=>$end, ':company' => $companyId));
        $hours = $data->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($hours);
        $startTime = microtime(true);
        $users = array();
        
        //init user array
        foreach($hours as $hour) {
            if (is_int($hour['worker'])) {
                $users[$hour['worker']] = array();
            }
        }
        $this->userCount = count($users);


        //init user-job array
        foreach($hours as $hour) {
            if (is_int($hour['worker']) && is_int($hour['job'])) {
                $users[$hour['worker']][$hour['job']] = array();
            } else {
            }
        }

        //init user-job-worktype array
        foreach($hours as $hour) {
            if (is_int($hour['worker']) && is_int($hour['job'])) {
                $users[$hour['worker']][$hour['job']][$hour['workType']] = array();
            }
        }
        
        //append user-job-worktype to include hour/date;
        foreach($hours as $hour) {
            if (is_int($hour['worker']) && is_int($hour['job'])) {
            array_push(
                $users[$hour['worker']][$hour['job']][$hour['workType']],
                array(
                    'date' => $hour['date'], 
                    'hours' => $hour['hours'],
                    'description' => $hour['completed']
                ));
            }
        }
        return $users;
    }
}