<?php
use Router\Route;
// Include router class
include '/var/www/classes/route.php';


Route::add('/(.*).css', function($css) {
    include "css/$css.css";
});
Route::add('/(.*).js', function($js) {
    header('Content-type: application/javascript');
    include "js/$js.js";
});

Route::add('/favicon.ico', function() {
    header('Content-Type: image/x-icon');
    include 'favicon.ico';
});
Route::add('/logo.png', function() {
    header('Content-Type: image/png');
    include 'logo.png';
});
//front pages
Route::add('/companyCreate/submit', function() {
    include "views/companyCreate/submit.php";
},['get','post']);

Route::add('/companyCreate/(.*)', function($id) {
    $price = $id;
    include "views/companyCreate/dataEntry.php";
});

Route::add('/companyCreate', function() {
    include "views/companyCreate/priceSelect.php";
});

Route::add('/stripeWebhook', function() {
    include "/actions/stripe.php";
}, ['get', 'post']);

Route::add('/login(.*)', function($id) {
    include "views/main/login.php";
},['get', 'post']);

Route::add('/file/jobFile', function() {
    include "views/file/jobFile.php";
},['get', 'post']);

//views
Route::add('/jobs', function() {
    require_once('/var/www/globals.php');
    $title = 'Jobs';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/jobs/table.php';
    include 'views/main/endNav.php';
});
Route::add('/jobs/all', function() {
    include 'jobs/jobTable.php';
});
Route::add('/jobs/my-dispatched', function() {
    include 'jobs/jobTable.php';
});
Route::add('/jobs/my-jobs', function() {
    include 'jobs/jobTable.php';
});


Route::add('/job/create', function() {
    include 'jobs/view.php';
});
Route::add('/job/view/(.*)', function($id) {
    include 'jobs/view.php';
});
Route::add('/job/edit/(.*)', function($id) {
    require_once('/var/www/globals.php');
    $job = getJobById($id);//32910);
    $title = 'Job ' . $job->getNumber();
    $alpine = "{job: true, daily: false, material: false, permits: false,  editFile: false, deleteFile: false }";
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/jobs/job/edit.php';
    include 'views/main/endNav.php';
},['get']);

Route::add('/table/jobs', function() {
    require_once('/var/www/globals.php');
    $page = ($_POST['page']) ? $_POST['page'] : 0;
    $jobSort = new Sort();
    $jobSort->setStatuses(isset($_POST['statuses']) ? $_POST['statuses'] : null);
    $jobSort->setTypes(   isset($_POST['types'])    ? $_POST['types']    : null);
    $jobSort->setWorkers( isset($_POST['workers'])  ? $_POST['workers']  : null);

    include 'views/jobs/components/table.php';
},['post']);

Route::add('/table/dailys', function() {
    require_once('/var/www/globals.php');
    $page = ($_POST['page']) ? $_POST['page'] : 0;
    include 'views/dailys/components/table.php';
},['post']);
 

Route::add('/test', function() {
    require_once('/var/www/globals.php');
    include 'test.php';
},['get','post']);


Route::add('/daily/view/(.*)', function($id) {
    require_once('/var/www/globals.php');
    $daily = getDailyById($id);//32910);
    $title = 'Daily Report';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/dailys/daily/view.php';
    include 'views/main/endNav.php';
},['get']);

Route::add('/daily/public/(.*)', function($id) {
    require_once('/var/www/globals.php');
    $dailyVars = getAnyDailyById($id);
    if ($dailyVars['isPublic'] == 1) {
        global $companyId;
        $companyId = $dailyVars['company'];
    } else {
        echo 'You do not have permission to view this resource';
        die;
    }
    $daily = getDailyById($id);
    $title = 'Daily Report';
    include 'views/main/head.php';
    include 'views/dailys/daily/public.php';
},['get']);

Route::add('/job/edit/(.*)', function($id) {
    require_once '/var/www/globals.php';
    saveJob($_POST, getJobById($id));
    //new jobPostHandler('edit', $id, $_POST);
},['post']);


Route::add('/job/star', function() {
    require_once '/var/www/globals.php';
    if (starJob($_POST)) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
},['get', 'post']);
Route::add('/job/dispatch', function() {
    require_once '/var/www/globals.php';
    http_response_code(200);
},['get', 'post']);


Route::add('/daily-report/(.*)', function($id) {

});


Route::add('/timecard/(.*)/(.*)/(.*)', function($id, $year, $week) {
    require_once '/var/www/globals.php';
    $title = 'Timecard';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/timecard/timecard.php';
    include 'views/main/endNav.php';
});

Route::add('/timecard', function() {
    require_once '/var/www/globals.php';
    $id = $currentUser->getId();
    $title = 'Your Timecard';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/timecard/timecard.php';
    include 'views/main/endNav.php';
});

Route::add('/timecard/(.*)', function($id) {
    require_once '/var/www/globals.php';

    $title = 'Timecard';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/timecard/timecard.php';
    include 'views/main/endNav.php';
});




Route::add('/daily-reports', function() {
    require_once '/var/www/globals.php';
    $daily_reports = getDailies();
    $title = 'Daily Reports';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/dailys/table.php';
    include 'views/main/endNav.php';
});

Route::add('/home(.*)', function($id) {
    require_once('/var/www/globals.php');
    require_once('/var/www/classes/messages.php');
    $title = 'Home';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/main/endNav.php';
});

Route::add('/team', function() {
    require_once('/var/www/globals.php');
    require_once('/var/www/classes/messages.php');
    $title = 'Home';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/messages/messages.php';
    include 'views/main/endNav.php';
});

Route::add('/team/(.*)', function($id) {
    require_once('/var/www/globals.php');
    require_once('/var/www/classes/messages.php');
    $title = 'Home';
    error_log($id);
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/messages/messages.php';
    include 'views/main/endNav.php';
});

Route::add('/', function() {
    require_once('/var/www/globals.php');
    $title = 'Home';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/main/endNav.php';
});

Route::add('/team(.*)', function($id) {
    require_once('/var/www/globals.php');
    $title = 'Team';
    include 'views/main/head.php';
    include 'views/main/nav.php';
    include 'views/main/endNav.php';
});





//actions
Route::add('/file/download/(.*)', function($id) {
    include 'actions/file/download.php';
});

Route::add('/image/(.*)/daily/(.*)', function($id, $daily) {
    require_once('/var/www/globals.php');
    $fileId = ($id);
    $daily = getDailyById($daily);//32910);
    include 'views/imageView.php';
},['get']);

Route::add('/image/(.*)', function($id) {
    require_once('/var/www/globals.php');
    $file = getFileById($id);
    include 'views/image.php';
},['get']);

Route::add('/publicImage/(.*)', function($id) {
    require_once('/var/www/globals.php');
    $file = getPublicFileById($id);
    include 'views/image.php';
},['get']);


Route::add('/job/download/(.*)', function($id) {
    require_once('/var/www/globals.php');
    require_once('/var/www/classes/jobReports.php');
    include ('/var/www/all/actions/jobReport.php');
    //$job = getJobXlsById($id);
    //$job->output();
},['get']);



//misc
Route::pathNotFound(function($path) {
    header('HTTP/1.0 404 Not Found');
    echo 'Error 404 :-(<br>';
    echo 'The requested path "'.$path.'" was not found!';
});

// Add a 405 method not allowed route
Route::methodNotAllowed(function($path, $method) {
    header('HTTP/1.0 405 Method Not Allowed');
    echo 'Error 405 :-(<br>';
    //echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});

Route::run('/');