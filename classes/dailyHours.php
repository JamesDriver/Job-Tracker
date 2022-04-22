<?php 
class DailyHours {
    private $daily;
    private $worker;
    private $hours;
    private $type;
    public function setDailyId($var){ $this->daily  = $var; }
    public function setWorker($var) { $this->worker = $var; }
    public function setHours($var)  { $this->hours  = $var; }
    public function setType($var)   { $this->type   = $var; }
    public function getWorker() { return $this->worker; }
    public function getHours()  { return $this->hours;  }
    public function getType()   { return $this->type; }
    public function __CONSTRUCT($hoursArray = NULL) {
        if ($hoursArray) {
            $this->setWorker(getUserById($hoursArray['worker']));
            $this->setHours($hoursArray['hours']);
            $this->setType($hoursArray['workType']);
        }
    }
}
function createDailyHours($returns) {
    $dailyHours = array();
    foreach($returns as $return) {
        $dailyHour = new DailyHours($return);
        array_push($dailyHours, $dailyHour);
    }
    return $dailyHours;
}
function getHoursByDaily($dailyId) {
    $parameters = array(':daily' => $dailyId);
    $returns = run(sql::$getHoursByDaily, $parameters);
    $dailyHours = createDailyHours($returns);
    return $dailyHours;
}
function getHoursByDailyWithUsers($dailyId, $users) {
    $parameters = array(':daily' => $dailyId);
    $returns = run(sql::$getHoursByDaily, $parameters);
    $dailyHours = createDailyHours($returns);
    return $dailyHours;
}
function createDailyHoursWithUsers($returns, $users) {
    $dailyHours = array();
    $userArr = array();
    foreach($users as $user) {
        $userArr[$user->getId()] == $user;
    }
    foreach($returns as $return) {
        $dailyHour = new DailyHours();
        $dailyHour->setHours($return['hours']);
        if (isset($userArr[$return['worker']])) {
            $dailyHour->setWorker($user);
        }
        //foreach($users as $user) {
        //    if ($user->getId() == $return['worker']) {
        //        $dailyHour->setWorker($user);
        //    }
        //}
        array_push($dailyHours, $dailyHour);
    }
    return $dailyHours;
}
