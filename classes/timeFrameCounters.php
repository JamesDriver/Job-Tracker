<?php
class TypeCount {
    private $typeQuantities;

    public function getQuantity($type) {
        $return = isset($this->typeQuantities[$type->getId()]) ?
                    $this->typeQuantities[$type->getId()] : 
                    0;
        return $return;
    }
    public function __CONSTRUCT($period) {
        switch($period) {
            case 'month':
                $this->setPeriodMonthly();
                break;
            case 'year':
                $this->setPeriodYearly();
                break;
            case 'all':
                $this->setPeriodAll();
                break;
            default:
                $this->setPeriodMonthly();
        }
    }
    private function setPeriodMonthly() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    type 
                FROM job WHERE  
                    company = :company AND 
                    YEAR(date(createDate))=:year AND
                    MONTH(date(createDate))=:month
                GROUP BY type";
        $parameters = array(
            ':year'=>date("Y"),
            ':month'=>date("m"),
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->typeQuantities[$data['type']] = $data['quantity'];
        }
    }
    private function setPeriodYearly() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    type 
                FROM job WHERE  
                    company = :company AND 
                    YEAR(date(createDate))=:year
                GROUP BY type";
        $parameters = array(
            ':year'=>date("Y"),
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->typeQuantities[$data['type']] = $data['quantity'];
        }
    }
    private function setPeriodAll() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    type 
                FROM job WHERE  
                    company = :company
                    GROUP BY type";
        $parameters = array(
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->typeQuantities[$data['type']] = $data['quantity'];
        }
    }
}




class StatusCount {
    private $statusQuantities;

    public function getQuantity($status) {
        $return = isset($this->statusQuantities[$status->getId()]) ?
                    $this->statusQuantities[$status->getId()] : 
                    0;
        return $return;
    }
    public function __CONSTRUCT($period) {
        switch($period) {
            case 'month':
                $this->setPeriodMonthly();
                break;
            case 'year':
                $this->setPeriodYearly();
                break;
            case 'all':
                $this->setPeriodAll();
                break;
            default:
                $this->setPeriodMonthly();
        }
    }
    private function setPeriodAll() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    status 
                FROM job WHERE  
                    company = :company
                GROUP BY status";
        $parameters = array(
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->statusQuantities[$data['status']] = $data['quantity'];
        }
    }
    private function setPeriodYearly() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    status 
                FROM job WHERE  
                    company = :company AND 
                    YEAR(date(createDate))=:year
                GROUP BY status";
        $parameters = array(
            ':year'=>date("Y"),
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->statusQuantities[$data['status']] = $data['quantity'];
        }
    }
    private function setPeriodMonthly() {
        global $companyId;
        $query = "SELECT 
                    count(id) as quantity, 
                    status 
                FROM job WHERE  
                    company = :company AND 
                    YEAR(date(createDate))=:year AND
                    MONTH(date(createDate))=:month
                GROUP BY status";
        $parameters = array(
            ':year'=>date("Y"),
            ':month'=>date("m"),
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        foreach($datas as $data) {
            $this->statusQuantities[$data['status']] = $data['quantity'];
        }
    }
}


class JobCount {
    public $dates;
    public $data;
    private $months = array(
        '01'=>'Jan',
        '02'=>'Feb',
        '03'=>'Mar',
        '04'=>'Apr',
        '05'=>'May',
        '06'=>'Jun',
        '07'=>'Jul',
        '08'=>'Aug',
        '09'=>'Sep',
        '10'=>'Oct',
        '11'=>'Nov',
        '12'=>'Dec'
    );
    public function __CONSTRUCT($period) {
        switch($period) {
            case 'month':
                $this->setPeriodMonthly();
                break;
            case 'year':
                $this->setPeriodYearly();
                break;
            case 'all':
                $this->setPeriodAllTime();
                break;
            default: 
                $this->setPeriodYearly();
        }
    }
    private function setPeriodMonthly() {
        global $companyId;
        $date = new DateTime();
        $query = "SELECT 
                    count(id) as count, 
                    DAY(date(createDate)) as day
                FROM job WHERE 
                    company = :company AND 
                    MONTH(date(createDate)) = :month AND 
                    YEAR(date(createDate)) = :year 
                group by day";
        $parameters = array(
            ':month' => $date->format('m'),
            ':year' => $date->format('Y'),
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        $month = '';
        foreach($this->months as $key => $mnt) {
            if ($key == $date->format('m')) {
                $month = $mnt;
            }
        }
        foreach($datas as $data) {

            $this->dates .= '"' . $month . ' ' . $data['day'] . '"' . ', ';
            $this->data .= $data['count'] . ', ';
        }
    }
    private function setPeriodYearly() {
        global $companyId;
        $date = new DateTime();
        $date->modify('-11 month');

        $query = "SELECT 
                    count(id) as count, 
                    MONTH(date(createDate)) as month,
                    YEAR(date(createDate)) as year
                FROM job WHERE 
                    company = :company 
                group by month, year";
        $parameters = array(
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        $arr = array();
        foreach($datas as $data) {
            array_push($arr, $data);
        }
        for ($i = 0; $i<12; $i++) {
            $month = $date->format('m');
            $tmp = $this->months[$month];
            $this->dates .= "'{$tmp}',";
            foreach($arr as $data) {
                $final = 0;
                if ($data['year'] != $date->format('Y')) {
                    continue;
                }
                if ($data['month'] != $date->format('m')) {
                    continue;
                }
                $final = $data['count'];
                break;
                
            }
            $this->data .= $final . ', ';
            $date->modify('+1 month');
        }
    }
    private function setPeriodAllTime() {
        global $companyId;
        $date = new DateTime();
        $query = "SELECT 
                    count(id) as count, 
                    MONTH(date(createDate)) as month,
                    YEAR(date(createDate)) as year
                FROM job WHERE 
                    company = :company 
                group by month, year
                ORDER BY year, month";
        $parameters = array(
            ':company'=>$companyId
        );
        $datas = run($query, $parameters);
        $arr = array();
        foreach($datas as $data) {
            $month = '';
            foreach($this->months as $key => $mnt) {
                if ($data['month'] == $key) {
                    $month = $mnt;
                }
            }
            $this->dates .= "'{$month}-{$data['year']}',";
            $this->data .= $data['count'] . ', ';
        }
    }
}

class TopUsers {
    public $users = array();
    private $allUsers = array();
    private $start;
    private $end;
    public function __CONSTRUCT($period) {
        $tmpUsers = getUsers();
        foreach($tmpUsers as $user) {
            $this->allUsers[$user->getId()] = $user;
        //    $this->users[$user->getId()]['hours'] = 0;
        //    $this->users[$user->getId()]['user'] = $user;
        }
        switch($period) {
            case 'week':
                $this->setPeriodWeekly();
                break;
            case 'month':
                $this->setPeriodMonthly();
                break;
            case 'year':
                $this->setPeriodYearly();
                break;
            default: 
                $this->setPeriodYearly();
        }
    }
    public function top5() {
        $final = array();
        $i = 0;
        foreach($this->users as $user) {
            if ($i == 5) {
                break;
            } 
            array_push($final, $user);
            $i++;
        }
        return $final;
    }
    public function ranked() {
        return $this->users;
    }
    private function setPeriodWeekly() {
        $start = new DateTime();
        if ($start->format('D') != 'Monday') {
            $start->modify('last Monday');
        }
        $end = new DateTime();
        if ($end->format('D') != 'Sunday') {
            $end->modify('next Sunday');
        }
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function setPeriodMonthly() {
        $start = new DateTime();
        $start->modify('first day of this month');
        $end = new DateTime();
        $end->modify('last day of this month');
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function setPeriodYearly() {
        $start = new DateTime();
        $start->modify('first day of january this year');
        $end = new DateTime();
        $end->modify('last day of december this year');
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function process() {
        global $companyId;
        $start = $this->start;
        $end = $this->end;
        $query = 'SELECT dailyWorkers.worker, SUM(dailyWorkers.hours) as hours FROM daily 
                    LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily 
                    LEFT JOIN job ON daily.job = job.id
                    WHERE daily.date between :start and :end AND job.company = :company
                    GROUP BY worker ORDER BY hours DESC';
        $parameters = array(
            ':start' => $start->format("Y-m-d H:i:s"),
            ':end' => $end->format("Y-m-d H:i:s"),
            ':company' => $companyId
        );
        $stmt = run($query, $parameters);
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($responses as $response) {
            if ($response['worker']) {
                $this->users[$response['worker']]['hours'] = $response['hours'];
                $this->users[$response['worker']]['user'] = $this->allUsers[$response['worker']];
            }
        }
    }
}






class TopClients {
    public $clients = array();
    private $allClients = array();
    private $start;
    private $end;
    public function __CONSTRUCT($period) {
        $tmpClients = getClients();
        foreach($tmpClients as $client) {
            $this->allClients[$client->getId()] = $client;
        }
        switch($period) {
            case 'week':
                $this->setPeriodWeekly();
                break;
            case 'month':
                $this->setPeriodMonthly();
                break;
            case 'year':
                $this->setPeriodYearly();
                break;
            default: 
                $this->setPeriodYearly();
        }
    }
    public function ranked() {
        return $this->clients;
    }
    public function top5() {
        $final = array();
        $i = 0;
        foreach($this->clients as $client) {
            if ($i == 5) {
                break;
            } 
            array_push($final, $client);
            $i++;
        }
        return $final;
    }
    private function setPeriodWeekly() {
        $start = new DateTime();
        if ($start->format('D') != 'Monday') {
            $start->modify('last Monday');
        }
        $end = new DateTime();
        if ($end->format('D') != 'Sunday') {
            $end->modify('next Sunday');
        }
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function setPeriodMonthly() {
        $start = new DateTime();
        $start->modify('first day of this month');
        $end = new DateTime();
        $end->modify('last day of this month');
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function setPeriodYearly() {
        $start = new DateTime();
        $start->modify('first day of january this year');
        $end = new DateTime();
        $end->modify('last day of december this year');
        $this->start = $start;
        $this->end = $end;
        $this->process();
    }
    private function process() {
        global $companyId;
        $start = $this->start;
        $end = $this->end;
        $query = 'SELECT clientJob.client, SUM(dailyWorkers.hours) as hours FROM daily 
                    LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily 
                    LEFT JOIN job ON daily.job = job.id
                    LEFT JOIN clientJob ON daily.job = clientJob.job
                    WHERE daily.date between :start and :end AND job.company = :company
                    GROUP BY client ORDER BY hours DESC';
        $parameters = array(
            ':start' => $start->format("Y-m-d H:i:s"),
            ':end' => $end->format("Y-m-d H:i:s"),
            ':company' => $companyId
        );
        $stmt = run($query, $parameters);
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($responses as $response) {
            if ($response['client']) {
                $this->clients[$response['client']]['hours'] = $response['hours'];
                $this->clients[$response['client']]['client'] = $this->allClients[$response['client']];
            }
        }
    }
}