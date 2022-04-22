<?php
require_once '/var/www/globals.php';
global $currentUser;
$job = getJobById($id);
if ($job->isStarred()) {
    run(sql::$removeStar, array('user'=>$currentUser->getId(), 'job'=>$job->getId()));
} else {
    run(sql::$addStar, array('user'=>$currentUser->getId(), 'job'=>$job->getId()));
}