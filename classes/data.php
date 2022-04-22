<?php
require_once "/var/www/classes/database.php";
require_once "/var/www/classes/log.php";

class style {
    public static $cssMain = '/style/main.css';
}
class lengths {
    public static $userTypeName   = 40;
    public static $jobName        = 250;
    public static $jobLocation    = 250;
    public static $jobPo          = 250;
    public static $jobBid         = 250;
    public static $jobDescription = 30000;
    public static $jobNotes       = 30000;
    public static $textLen        = 160;
    public static $userName       = 40;
    public static $username       = 40;
    public static $email          = 40;
    public static $phone          = 40;
    public static $password       = 40;
}
class db {
    public static $dbName = 'anchorJobs';
}
class format {
    public static $time = 'Y-m-d H:i:s';
    public static $fileLocation = '/var/www/files/test/';
}
class errors {

    public static $permissionError          = ['errorCode' => '0-00000', 'errorMessage' => 'You do not have permission to complete that action'];
    public static $tooManyTexts             = ['errorCode' => '0-00001', 'errorMessage' => 'You have used up your alotted amount of texts. If you wish to send more, please upgrade your company tier or call our Support line '];
    public static $tooManyEmails            = ['errorCode' => '0-00002', 'errorMessage' => 'You have used up your alotted amount of emails. If you wish to send more, please upgrade your company tier or call our Support line '];
    //db
    public static $databaseConnectionError  = ['errorCode' => '00001', 'errorMessage' => 'Server Connection failed. Please Try again or call our Support line '];
    public static $objectFetchError         = ['errorCode' => '00002', 'errorMessage' => 'Failed to fetch requested item. Please Try again or call our Support line '];
    public static $objectFetchAllError      = ['errorCode' => '00003', 'errorMessage' => 'Failed to fetch requested items. Please Try again or call our Support line '];
    public static $sqlRunError              = ['errorCode' => '00004', 'errorMessage' => 'Failed to execute query. Please Try again or call our Support line '];
    public static $functionLogError         = ['errorCode' => '00005', 'errorMessage' => 'Failed to log func. Please Try again or call our Support line '];
    public static $logPageAccessError       = ['errorCode' => '00006', 'errorMessage' => 'Server Connection failed. Please Try again or call our Support line '];
    //job
    public static $noJobNameOnCreate        = ['errorCode' => '10001', 'errorMessage' => 'Job cannot be created without name. Please try again or call our Support line '];
    public static $noJobStatusOnCreate      = ['errorCode' => '10002', 'errorMessage' => 'Job cannot be created without status. Please try again or call our Support line '];
    public static $noJobTypeOnCreate        = ['errorCode' => '10003', 'errorMessage' => 'Job cannot be created without type. Please try again or call our Support line '];
    public static $jobUpdateFailName        = ['errorCode' => '10004', 'errorMessage' => 'Failed to update Job. Please try again later or call our Support line '];
    public static $jobUpdateFailNumber      = ['errorCode' => '10005', 'errorMessage' => 'Failed to update Job. Please try again later or call our Support line '];
    public static $jobUpdateFailStatus      = ['errorCode' => '10006', 'errorMessage' => 'Failed to update Job. Please try again later or call our Support line '];
    public static $jobUpdateFailType        = ['errorCode' => '10007', 'errorMessage' => 'Failed to update Job. Please try again later or call our Support line '];
    public static $jobCreateFail            = ['errorCode' => '10009', 'errorMessage' => 'Failed to Create Job. Please try again later or call our Support line '];
    public static $jobUpdateFail            = ['errorCode' => '10010', 'errorMessage' => 'Failed to update Job. Please try again later or call our Support line '];
    public static $jobDeleteFail            = ['errorCode' => '10011', 'errorMessage' => 'Failed to delete Job. Please try again later or call our Support line '];
    public static $noLevelOnFile            = ['errorCode' => '10012', 'errorMessage' => 'Failed to create Job. Please try again later or call our Support line '];
    public static $notPermittedJobReport    = ['errorCode' => '10013', 'errorMessage' => 'You cannot view this job report. Please try again later or call our Support line '];
    public static $notPermittedJobCreate    = ['errorCode' => '10014', 'errorMessage' => 'Failed to create Job. Please try again later or call our Support line '];
    public static $notPermittedJobUpdate    = ['errorCode' => '10015', 'errorMessage' => 'Failed to create Job. Please try again later or call our Support line '];
    public static $notPermittedJobRead      = ['errorCode' => '10016', 'errorMessage' => 'Failed to create Job. Please try again later or call our Support line '];


    //client
    public static $clientCreateFail         = ['errorCode' => '20000', 'errorMessage' => 'Failed to create Client. Please try again later or call our Support line '];
    public static $clientUpdateFail         = ['errorCode' => '20001', 'errorMessage' => 'Failed to update Client. Please try again later or call our Support line '];
    public static $clientDeleteFail         = ['errorCode' => '20002', 'errorMessage' => 'Failed to delete Client. Please try again later or call our Support line '];
    public static $noClientCreatePermission = ['errorCode' => '20003', 'errorMessage' => 'You do not have permission to create a client. '];
    public static $noClientUpdatePermission = ['errorCode' => '20004', 'errorMessage' => 'You do not have permission to update a client. '];
    public static $noClientDeletePermission = ['errorCode' => '20005', 'errorMessage' => 'You do not have permission to delete a client. '];
    public static $noClientNameOnCreate     = ['errorCode' => '20006', 'errorMessage' => 'Client cannot be created without name. Please try again or call our Support line '];
    public static $noClientNameOnUPdate     = ['errorCode' => '20007', 'errorMessage' => 'Client cannot be updated without name. Please try again or call our Support line '];
    public static $noClientReadPermission   = ['errorCode' => '20008', 'errorMessage' => 'You do not have permission to view this resource. Please try again or call our Support line '];

    //status
    public static $statusDNE                = ['errorCode' => '30000', 'errorMessage' => 'That status does not exist. Please try again later or call our Support line '];
    public static $noNameOnStatusCreate     = ['errorCode' => '30001', 'errorMessage' => 'Status cannot be created without name. Please try again later or call our Support line '];
    public static $noColorOnStatusCreate    = ['errorCode' => '30002', 'errorMessage' => 'Status cannot be created without color. Please try again later or call our Support line '];
    public static $noFuncOnStatusCreate     = ['errorCode' => '30003', 'errorMessage' => 'Status cannot be created without function. Please try again later or call our Support line '];
    public static $noIdOnStatusUpdate       = ['errorCode' => '30004', 'errorMessage' => 'Status cannot be updated without id. Please try again later or call our Support line '];
    public static $noNameOnStatusUpdate     = ['errorCode' => '30005', 'errorMessage' => 'Status cannot be updated without name. Please try again later or call our Support line '];
    public static $noColorOnStatusUpdate    = ['errorCode' => '30006', 'errorMessage' => 'Status cannot be updated without color. Please try again later or call our Support line '];
    public static $noFuncOnStatusUpdate     = ['errorCode' => '30007', 'errorMessage' => 'Status cannot be updated without function. Please try again later or call our Support line '];
    public static $noOkTypesOnStatusCreate  = ['errorCode' => '30008', 'errorMessage' => 'Status cannot be created without an allowed user type. Please try again later or call our Support line '];

    //type
    public static $noNameOnTypeCreate       = ['errorCode' => '40001', 'errorMessage' => 'Type cannot be created without name. Please try again later or call our Support line '];
    public static $noColorOnTypeCreate      = ['errorCode' => '40002', 'errorMessage' => 'Type cannot be created without color. Please try again later or call our Support line '];
    public static $noIdOnTypeUpdate         = ['errorCode' => '40003', 'errorMessage' => 'Type cannot be updated Please try again later or call our Support line '];
    public static $noNameOnTypeUpdate       = ['errorCode' => '40004', 'errorMessage' => 'Type cannot be updated without name. Please try again later or call our Support line '];
    public static $noColorOnTypeUpdate      = ['errorCode' => '40005', 'errorMessage' => 'Type cannot be updated without color. Please try again later or call our Support line '];
    public static $atLeastOneTypeRequired   = ['errorCode' => '40006', 'errorMessage' => 'You must have at least one type. Please try again later or call our Support line '];

    //user
    public static $userDisableFailed         = ['errorCode' => '50000', 'errorMessage' => 'User could not be disabled. Please try again or call our Support line '];
    public static $noUsernameOnCreate        = ['errorCode' => '50001', 'errorMessage' => 'User cannot be created without username. Please try again or call our Support line '];
    public static $noNameOnCreate            = ['errorCode' => '50002', 'errorMessage' => 'User cannot be created without name. Please try again or call our Support line '];
    public static $noEmailOnCreate           = ['errorCode' => '50003', 'errorMessage' => 'User cannot be created without email. Please try again or call our Support line '];
    public static $noPhoneOnCreate           = ['errorCode' => '50004', 'errorMessage' => 'User cannot be created without phone number. Please try again or call our Support line '];
    public static $noPasswordOnCreate        = ['errorCode' => '50005', 'errorMessage' => 'User cannot be created without password. Please try again or call our Support line '];
    public static $noTypeOnCreate            = ['errorCode' => '50006', 'errorMessage' => 'User cannot be created without type. Please try again or call our Support line '];
    public static $administrativetypeError   = ['errorCode' => '50007', 'errorMessage' => 'Only administrators can create administrative users. Please try again or call our Support line '];
    public static $userCreateFail            = ['errorCode' => '50008', 'errorMessage' => 'User could not be created. Please try again or call our Support line '];
    public static $userUpdateFail            = ['errorCode' => '50009', 'errorMessage' => 'User could not be Updated. Please try again or call our Support line '];
    public static $noUserUpdatePermission    = ['errorCode' => '50010', 'errorMessage' => 'User could not be created. Please try again or call our Support line '];
    public static $userDeleteFail            = ['errorCode' => '50011', 'errorMessage' => 'User could not be deleted. Please try again or call our Support line '];
    public static $noUserDeletePermission    = ['errorCode' => '50012', 'errorMessage' => 'You do not have permission to delete that user. Please try again or call our Support line '];
    public static $noUserTypeByGivenId       = ['errorCode' => '50013', 'errorMessage' => 'Cannot construct user. Please try again or call our Support line '];
    public static $invalidUsername           = ['errorCode' => '50014', 'errorMessage' => 'Invalid Username. Please try again or call our Support line '];
    public static $invalidName               = ['errorCode' => '50015', 'errorMessage' => 'Invalid Name. Please try again or call our Support line '];
    public static $invalidEmail              = ['errorCode' => '50016', 'errorMessage' => 'Invalid Email. Please try again or call our Support line '];
    public static $invalidPhone              = ['errorCode' => '50017', 'errorMessage' => 'Invalid Phone Number. Please try again or call our Support line '];
    public static $invalidPassword           = ['errorCode' => '50018', 'errorMessage' => 'Invalid Password. Please try again or call our Support line '];

    //file
    public static $noFileToDeleteError       = ['errorCode' => '60000', 'errorMessage' => 'File to be deleted does not exist. Please try again or call our Support line '];
    public static $fileDeleteFailure         = ['errorCode' => '60001', 'errorMessage' => 'Could not delete record for file. Please try again or call our Support line '];
    public static $fileWriteFailure          = ['errorCode' => '60002', 'errorMessage' => 'Failed to save file. Please try again or call our Support line '];
    public static $fileUploadFailure         = ['errorCode' => '60003', 'errorMessage' => 'Failed to wite record to database. Please try again or call our Support line '];

    //daily
    public static $noJobOnDaily              = ['errorCode' => '70000', 'errorMessage' => 'Daily could not be created. Please try again or call our Support line '];
    public static $noDateOnDaily             = ['errorCode' => '70001', 'errorMessage' => 'Daily could not be created without date. Please try again or call our Support line '];
    public static $noCompletedTodayOnDaily   = ['errorCode' => '70002', 'errorMessage' => 'Daily could not be created without "Completed Today". Please try again or call our Support line '];
    public static $noWorkerOnDaily           = ['errorCode' => '70003', 'errorMessage' => 'Daily could not be created without worker. Please try again or call our Support line '];
    public static $dailyCreateFail           = ['errorCode' => '70004', 'errorMessage' => 'Daily could not be created. Please try again or call our Support line '];
    public static $noJobOrDailyId            = ['errorCode' => '70005', 'errorMessage' => 'No daily or job specified. Please try again or call our Support line '];
    public static $noDailyUpdatePermissions  = ['errorCode' => '70006', 'errorMessage' => 'You do not have permission to update this daily. Please try again or call our Support line '];
    public static $dailyUpdateFailure        = ['errorCode' => '70007', 'errorMessage' => 'Failed to update daily. Please try again or call our Support line '];
    public static $dailyPermissionCreateFail = ['errorCode' => '70008', 'errorMessage' => 'You do not have permission to create a daily. Please try again or call our Support line '];
    public static $dailyPermissionUpdateFail = ['errorCode' => '70009', 'errorMessage' => 'You do not have permission to update this daily. Please try again or call our Support line '];
    public static $dailyPermissionReadFail   = ['errorCode' => '70010', 'errorMessage' => 'You do not have permission to read this daily. Please try again or call our Support line '];
    public static $dailyDeleteFail           = ['errorCode' => '70011', 'errorMessage' => 'Failed to delete daily. Please try again or call our Support line '];
    //workType
    public static $atLeastOneWorkTypeRequired= ['errorCode' => '80001', 'errorMessage' => 'You must have at least one work type. Please try again later or call our Support line '];

    //material
    public static $materialCreateFailure     = ['errorCode' => '900000', 'errorMessage' => 'Material could not be created. Please try again later or call our Support line '];
    public static $materialUpdateFailure     = ['errorCode' => '900001', 'errorMessage' => 'Material could not be created. Please try again later or call our Support line '];
    public static $materialDeleteFailure     = ['errorCode' => '900002', 'errorMessage' => 'Material could not be created. Please try again later or call our Support line '];
    public static $invalidNumber             = ['errorCode' => '900003', 'errorMessage' => 'Invalid Number. Please try again later or call our Support line '];
    public static $invalidDescription        = ['errorCode' => '900004', 'errorMessage' => 'Invalid Description. Please try again later or call our Support line '];
    public static $invalidInventory          = ['errorCode' => '900005', 'errorMessage' => 'Invalid Inventory. Please try again later or call our Support line '];
    public static $invalidPrice              = ['errorCode' => '900006', 'errorMessage' => 'Invalid Price. Please try again later or call our Support line '];

    //userType
    public static $userTypeCreateFailure     = ['errorCode' => '10-00000', 'errorMessage' => 'Failed to create user type. Please try again later or call our Support line '];
    public static $noUserTypeExists          = ['errorCode' => '10-00001', 'errorMessage' => 'That user type does not exist. Please try again later or call our Support line '];
    public static $userTypeInUse             = ['errorCode' => '10-00002', 'errorMessage' => 'You cannot delete a user type that has users assigned to it. Please remove this type from all users and try again'];
    public static $userTypeDeleteFailure     = ['errorCode' => '10-00003', 'errorMessage' => 'Something went wrong. Please try again later or call our Support line '];
}

class functions {
    //database
    public static $fetch           = 'fetch';
    public static $fetchAll        = 'fetchAll';
    public static $constructDb     = 'constructDb';
    public static $validate        = 'validate';
    public static $randomString    = 'randomString';
    //jobs
    public static $getJobById      = 'getJobs';
    public static $getJobsByClient = 'getJobsByClient';
    public static $getJobs         = 'getJobs';
    public static $searchJobs      = 'searchJobs';
    public static $createJob       = 'job->create';
    public static $updateJob       = 'job->update';
    public static $deleteJob       = 'job->delete';
    public static $displayJobs     = 'displayJobs';
    public static $nextJobNumber   = 'nextJobNumber';
    public static $newJob          = 'newJob';
    public static $saveJob         = 'saveJob';
    //clients
    public static $createClient    = 'client->create';
    public static $updateClient    = 'client->update';
    public static $deleteClient    = 'client->delete';
    public static $getClients      = 'getClients';
    public static $getClientById   = 'getClientById';
    public static $displayClient   = 'displayClient';
    //users
    public static $createUser      = 'user->create';
    public static $updateUser      = 'user->update';
    public static $deleteUser      = 'user->delete';
    public static $getUsers        = 'getUsers';
    public static $getUserById     = 'getUserById';
    public static $getCurrentUser  = 'getCurrentUser';
    public static $getUserByUsername = "getUserByUsername";
    public static $displayUsers     = 'displayUsers';
    public static $displayUser      = 'displayUser';

    //types
    public static $createType      = 'type->create';
    public static $updateType      = 'type->update';
    public static $deleteType      = 'type->delete';
    public static $getTypes        = 'getTypes';
    //statuses
    public static $createStatus     = 'status->create';
    public static $updateStatus     = 'status->update';
    public static $deleteStatus     = 'status->delete';
    public static $getStatuses      = 'getStatuses';
    public static $getStatusById    = 'getStatusById';
    public static $getStatusesByFunc= 'getStatusesByFunc';
    //company
    //userTypes
    public static $getUserTypes   = 'getUserTypes';
    public static $createUserType = 'usertype->create';
    public static $updateUserType = 'usertype->update';
    public static $deleteUserType = 'usertype->delete';
    //permissions
    public static $getPermissions = 'getPermissions';
}
class pages {
    public static $job        = 'job.php';
    public static $jobs       = 'jobs.php';
    public static $client     = 'client.php';
    public static $clients    = 'clients.php';
    public static $settings   = 'settings.php';
    public static $user       = 'user.php';
    public static $users      = 'users.php';
    public static $dispatch   = 'dispatch.php';
    public static $daily      = 'daily.php';
    public static $dailyPrint = 'dailyPrint.php';
    public static $logOut     = 'logout.php';
    public static $login      = 'login.php';
}
class jobData {
    public static $constructFailure = "Failure to load Jobs. Please try again later";
    public static $id          = 'id';
    public static $number      = 'number';
    public static $creator     = 'creator';
    public static $name        = 'name';
    public static $client      = 'client';
    public static $workers     = 'workers';
    public static $files       = 'files';
    public static $file        = 'file';
    public static $fileRequired= 'requiredLevel';
    public static $changeOrder = 'changeOrder';
    public static $materials   = 'materials';
    public static $managers    = 'managers';

    public static $type        = 'type';
    public static $status      = 'status';
    public static $location    = 'location';
    public static $description = 'description';
    public static $poNumber    = 'poNumber';
    public static $createDate  = 'createDate';
    public static $bid         = 'bid';
    public static $notes       = 'note';
    public static $company     = 'company';
}
class dailyData {
    public static $constructFailure = "Failure to load Daily. Please try again later";
    public static $id          = 'id';
    public static $created     = 'created';
    public static $creator     = 'creator';
    public static $updated     = 'updated';
    public static $date        = 'date';
    public static $job         = 'job';
    public static $mileage     = 'mileage';
    public static $completed   = 'completed';
    public static $material    = 'material';
    public static $sowChanges  = 'sowChanges';
    public static $equipment   = 'equipment';
    public static $goals       = 'goals';
    public static $notes       = 'notes';
    public static $issues      = 'issues';
    public static $worker      = 'worker';
    public static $hours       = 'hours';
    public static $file        = 'file';
    public static $isPublic    = 'isPublic';
    public static $workType    = 'workType';
}
class userJobData {
    public static $user        = 'user';
    public static $job         = 'job';
}
class jobFileData {
    public static $fileName     = 'fileName';
    public static $fileLocation = 'filePath';
    public static $fileSize     = 'fileSize';
}
class userData {
    public static $id          = 'id';
    public static $name        = 'name';
    public static $username    = 'username';
    public static $email       = 'email';
    public static $phone       = 'phone';
    public static $password    = 'password';
    public static $type        = 'type2';
    public static $cookie      = 'cookie';
    public static $disabled    = 'disabled';
    public static $mobileTable = 'mobileTable';
}
class clientData {
    public static $id            = 'id';
    public static $name          = 'name';
    public static $address       = 'address';
    public static $phone         = 'phone';
    public static $email         = 'email';
    public static $marketingOkay = 'marketingOkay';
    public static $company       = 'company';
}
class userTypeData {
    public static $id      = 'id';
    public static $name    = 'name';
    public static $company = 'company';
}
class typeData {
    public static $id    = 'id';
    public static $order = 'position';
    public static $name  = 'name';
    public static $color = 'color';
    public static $managers = 'managers';
}
class statusData {
    public static $id       = 'id';
    public static $order    = 'position';
    public static $name     = 'name';
    public static $function = 'function';
    public static $color    = 'color';
    public static $emails   = 'emails';
}
class permissionsData {
    public static $id             = 'id';
    public static $jobCreate      = 'jobCreate';
    public static $jobUpdate      = 'jobUpdate';
    public static $jobDelete      = 'jobDelete';
    public static $jobDispatch    = 'jobDispatch';
    public static $userCreate     = 'userCreate';
    public static $userUpdate     = 'userUpdate';
    public static $userDelete     = 'userDelete';
    public static $clientCreate   = 'clientCreate';
    public static $clientUpdate   = 'clientUpdate';
    public static $clientDelete   = 'clientDelete';
    public static $settingsUpdate = 'settingsUpdate';
    public static $changeLogView  = 'changeLogView';
    public static $onlyViewMyJobs = 'onlySeeMyJobs';
    public static $companyId      = 'company';
    public static $viewBid        = 'viewBid';
    public static $createMaterial = 'createMaterial';
    public static $updateMaterial = 'updateMaterial';
    public static $deleteMaterial = 'deleteMaterial';
}
class permissionsData2 {
    public static $jobReadOwn            = "jobReadOwn";
    public static $jobReadAny            = "jobReadAny";
    public static $jobDispatch           = "jobDispatch";
    public static $jobUpdate             = "jobUpdate";
    public static $jobCreate             = "jobCreate";
    public static $jobDownload           = "jobDownload";
    public static $jobReport             = "jobReport";
    public static $jobDelete             = "jobDelete";
    public static $jobNumber             = "jobNumber";
    public static $jobName               = "jobName";
    public static $jobStatus             = "jobStatus";
    public static $jobClient             = "jobClient";
    public static $jobLocation           = "jobLocation";
    public static $jobType               = "jobType";
    public static $jobWorkers            = "jobWorkers";
    public static $jobDescription        = "jobDescription";
    public static $jobPoNumber           = "jobPoNumber";
    public static $jobBid                = "jobBid";
    public static $jobNotes              = "jobNotes";
    public static $jobFiles              = "jobFiles";
    public static $clientsRead           = "clientsRead";
    public static $clientUpdate          = "clientUpdate";
    public static $clientCreate          = "clientCreate";
    public static $clientDelete          = "clientDelete";
    public static $userUpdateSelf        = "userUpdateSelf";
    public static $userRead              = "userRead";
    public static $userUpdate            = "userUpdate";
    public static $userCreate            = "userCreate";
    public static $userDisable           = "userDisable";
    public static $userDelete            = "userDelete";
    public static $userHours             = "userHours";
    public static $settingsConsole       = "settingsConsole";
    public static $settingsFiles         = "settingsFiles";
    public static $settingsEvents        = "settingsEvents";
    public static $settingsImport        = "settingsImport";
    public static $settingsExport        = "settingsExport";
    public static $settingsUserType      = "settingsUserType";
    public static $settingsCustomization = "settingsCustomization";
    public static $settingsBilling       = "settingsBilling";
    public static $materials             = "materials";
    public static $materialsAdd          = "materialsAdd";
    public static $materialUpdate        = "materialUpdate";
    public static $materialCreate        = "materialCreate";
    public static $materialDelete        = "materialDelete";
    public static $daily                 = "daily";
    public static $dailyReadOwn          = "dailyReadOwn";
    public static $dailyCreate           = "dailyCreate";
    public static $dailyUpdateOwn        = "dailyUpdateOwn";
    public static $dailyRead             = "dailyRead";
    public static $dailyUpdate           = "dailyUpdate";
    public static $dailyDelete           = "dailyDelete";
    public static $permitsInspections    = "permitsInspections";
    public static $permitsRead           = "permitsRead";
    public static $permitsRequest        = "permitsRequest";
    public static $permitsUpload         = "permitsUpload";
    public static $permitsDelete         = "permitsDelete";
    public static $inspectionRead        = "inspectionRead";
    public static $inspectionRequest     = "inspectionRequest";
    public static $inspectionUpload      = "inspectionUpload";
    public static $inspectionDelete      = "inspectionDelete";
    public static $timecardAll           = "timecardAll";
}
class colsData {
    public static $number      = 'number';
    public static $name        = 'name';
    public static $status      = 'status';
    public static $client      = 'client';
    public static $location    = 'location';
    public static $type        = 'type';
    public static $fieldworker = 'fieldworker';
    public static $description = 'description';
    public static $ponumber    = 'ponumber';
    public static $bid         = 'bid';
    public static $view        = 'view';
    public static $edit        = 'edit';
    public static $dispatch    = 'dispatch';
    public static $deletes     = 'deletes';
    public static $download    = 'download';
    public static $daily       = 'daily';
}
class materialData {
    public static $id          = 'id';
    public static $number      = 'number';
    public static $description = 'description';
    public static $inventory   = 'inventory';
    public static $price       = 'price';
}
class jobMaterialData {
    public static $job       = 'job';
    public static $material  = 'material';
    public static $amount    = 'amount';
    public static $inventory = 'inventory';
}



class sql {

    //general
    public static $getUserCompany         = "SELECT company FROM user WHERE id = :id";

    //permits & inspections
    public static $getPermitById            = "SELECT * FROM permits WHERE id = :id";
    public static $getInspectionById        = "SELECT * FROM inspection WHERE id = :id";
    public static $getPermits               = "SELECT * FROM permits WHERE job = :job";
    public static $getInspections           = "SELECT * FROM inspection WHERE job = :job";
    public static $requestPermit            = "INSERT INTO permits (description, job, pending, requestDate, requestedBy) VALUES (:description, :job, :pending, :date, :requester)";
    public static $requestInspection        = "INSERT INTO inspection (details, job, status, requestedDate) VALUES (:details, :job, :status, :requestedDate)";
    public static $deletePermit             = "DELETE FROM permits WHERE id = :id AND (SELECT company FROM job WHERE id = :job) = :company";
    public static $deleteInspection         = "DELETE FROM inspection WHERE id = :id AND (SELECT company FROM job WHERE id = :job) = :company";
    public static $createPermit             = "INSERT INTO permits (description, file, job, pending, uploadedBy, uploadDate) VALUES (:description, :file, :job, :pending, :uploadedBy, :uploadDate)";
    public static $updatePermit             = "UPDATE permits SET description = :description WHERE id = :id";
    public static $updatePermitWithFile     = "UPDATE permits SET description = :description, file = :file, pending = :pending, uploadedBy = :uploadedBy, uploadDate = :uploadDate WHERE id = :id";
    public static $updateInspection         = "UPDATE inspection set details = :details, status = :status, requestedDate = :requestedDate, scheduledDate = :scheduledDate, completedDate = :completedDate WHERE id = :id";
    public static $updateInspectionWithFile = "UPDATE inspection set details = :details, file = :file, status = :status, requestedDate = :requestedDate, scheduledDate = :scheduledDate, completedDate = :completedDate WHERE id = :id";
    public static $createInspection         = "INSERT INTO inspection (details, file, job, status, scheduledDate, completedDate) VALUES (:details, :file, :job, :status, :scheduledDate, :completedDate)";

    //jobMaterial
    public static $getMaterialsByJob      = "SELECT
                                                jobMaterial.job, jobMaterial.material, jobMaterial.inventory as invtQty, jobMaterial.nonInventory as nInvtQty,
                                                materials.id, materials.number, materials.description, materials.inventory, materials.price
                                                FROM jobMaterial
                                                LEFT JOIN materials on jobMaterial.material = materials.id
                                                WHERE job = :job";

    public static $getJobCustomMaterials  = "SELECT * FROM jobMaterialCustom WHERE job = :job";
    public static $getJobNormalMaterials  = "SELECT * FROM jobMaterialNormal WHERE job = :job";
    public static $createJobCustomMaterial= "INSERT INTO jobMaterialCustom (job, name, quantity, price, photo) VALUES (:job, :name, :quantity, :price, :photo)";
    public static $createJobNormalMaterial= "INSERT INTO jobMaterialNormal (job, material, inventory, nonInventory, price, photo) VALUES (:job, :material, :inventory, :nonInventory, :price, :photo)";
    public static $deleteJobCustomMaterials = "DELETE FROM jobMaterialCustom WHERE job = :jobId";
    public static $deleteJobNormalMaterials = "DELETE FROM jobMaterialNormal WHERE job = :jobId";
    //public static $createJobMaterial      = "INSERT INTO jobMaterial (job, material, inventory, nonInventory) VALUES (:job, :material, :inventory, :nonInventory)";
    //public static $updateJobMaterial      = "UPDATE jobMaterial SET inventory = :inventory, nonInventory = :nonInventory WHERE job = :job and material = :material";
    //public static $deleteJobMaterial      = "DELETE FROM jobMaterial WHERE job = :job and material = :material";




    //special materials
    public static $getSpecialMaterialsByJob = "SELECT * FROM specialMaterials WHERE job = :job";
    public static $updateSpecialMaterial    = "UPDATE specialMaterials SET inventory = :inventory, nonInventory = :nonInventory, pricePerUnit = :pricePerUnit WHERE id = :id";
    public static $createSpecialMaterial    = "INSERT INTO specialMaterials (job, name, inventory, nonInventory, pricePerUnit) VALUES (:job, :name, :inventory, :nonInventory, :pricePerUnit)";
    public static $deleteSpecialMaterial    = "DELETE FROM specialMaterials WHERE id = :id and job = :job";

    //material
    public static $getMaterials           = "SELECT * FROM materials WHERE company = :company ORDER BY number";
    public static $exportMaterials        = "SELECT number, description, inventory, price FROM materials WHERE company = :company ORDER BY number";

    public static $getLimitedMaterials    = "SELECT * FROM materials WHERE company = :company ORDER BY number limit :start, :end";
    public static $getMaterialsById       = "SELECT * FROM materials WHERE id = :id AND company = :company";
    public static $searchMaterials        = "SELECT * FROM materials WHERE (LOCATE(:search, number)>0 OR LOCATE(:search, description)>0) AND company = :company";
    public static $createMaterial         = "INSERT INTO materials (number, description, inventory, price, company) VALUES (:number, :description, :inventory, :price, :company)";
    public static $deleteMaterial         = "DELETE FROM materials WHERE id = :id and company = :company";
    public static $checkMaterialNum       = "SELECT count(id) as counted FROM materials WHERE number = :number and company = :company";
    public static $nextMaterialNum        = "SELECT max(number) as max from materials WHERE company = :company";

    //job page sort
    public static $getSortByUser          = "SELECT * FROM sort WHERE user = :user";
    public static $createSort             = "INSERT INTO sort (user, sortcol, sortval) VALUES (:user, :sortcol, :sortval)";
    public static $deleteSorts            = "DELETE FROM sort WHERE user = :user";

    //advanced cookies
    public static $getCookiesByUser       = "SELECT * FROM cookies WHERE user = :user and expiration > :date;";
    public static $saveCookie             = "INSERT INTO cookies (user, cookie, expiration) VALUES (:user, :cookie, :expiration);";

    //notification
    public static $getNotificationById    = "SELECT * FROM alerts WHERE id = :id";
    public static $markAsRead             = "UPDATE alerts SET isRead = 1 WHERE id = :id";
    public static $getNotifications       = "SELECT * FROM alerts WHERE user = :user ORDER BY id DESC";
    public static $getUnreadNotifications = "SELECT * FROM alerts WHERE user = :user AND isRead = 0 ORDER BY id DESC";

    //console
    public static $getFileCounts          = "SELECT SUM(size) as count FROM file WHERE            DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year";
    public static $getEmailCounts         = "SELECT COUNT(id) as count FROM email WHERE           DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year";
    public static $getTextCounts          = "SELECT COUNT(messageSid) as count FROM text WHERE    DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year";
    public static $getPageViewCounts      = "SELECT COUNT(page) as count FROM pageAccessLog WHERE DAY(time) = :day and MONTH(time)       = :month and YEAR(time)       = :year AND company != 2 AND company != 0";
    public static $getUniquePageView      = "SELECT COUNT(DISTINCT user) as count FROM pageAccessLog WHERE DAY(time) = :day and MONTH(time)       = :month and YEAR(time)       = :year AND company != 2 AND company != 0";
    public static $getCompFileCounts      = "SELECT SUM(size) as count FROM file WHERE            DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year AND company = :company";
    public static $getCompEmailCounts     = "SELECT COUNT(id) as count FROM email WHERE           DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year AND company = :company";
    public static $getCompTextCounts      = "SELECT COUNT(messageSid) as count FROM text WHERE    DAY(date) = :day and MONTH(date)       = :month and YEAR(date)       = :year AND company = :company";
    public static $getCompJobCounts       = "SELECT COUNT(id) as count FROM job WHERE       DAY(createDate) = :day and MONTH(createDate) = :month and YEAR(createDate) = :year AND company = :company";
    public static $getJobCounts           = "SELECT COUNT(id) as count FROM job WHERE       DAY(createDate) = :day and MONTH(createDate) = :month and YEAR(createDate) = :year AND company != 2 AND company != 0";
    public static $companyCounts          = "SELECT COUNT(id) as count FROM company WHERE created <= :date";

    //logging
    public static $functionRun            = "INSERT INTO functionLog (functionName,companyId,time,runtime) VALUES (:functionName,:company,:timeRun,:timeToRun)";
    public static $pageLoad               = "INSERT INTO pageAccessLog (page,company,time,user) VALUES (:page,:company,:time,:user)";
    public static $logError               = "INSERT INTO errorLog (errorCode,time,details,company) values (:errorCode,:time,:details,:company)";
    public static $loginLog         = "INSERT INTO loginLog (user, date, ip) VALUES (:user, :date, :ip)";
    public static $bugReport              = "INSERT INTO bugReport (subject, message, user, company) VALUES (:subject, :message, :user, :company)";
    public static $supportTicket          = "INSERT INTO supportTicket (description, contact, user, company) VALUES (:description, :contact, :user, :company)";
    public static $featureRequest         = "INSERT INTO featureRequest (description, contact, user, company) VALUES (:description, :contact, :user, :company)";
    public static $backup                 = "INSERT INTO backup (userId, userName, time, companyId, objectId, objectName, objectType, action) VALUES (:userId, :userName, :time, :companyId, :objectId, :objectName, :objectType, :action)";
    public static $getEvents              = "SELECT * FROM backup WHERE companyId = :companyId ORDER BY id DESC LIMIT 1000";
    public static $getLimitedEvents       = "SELECT * FROM backup WHERE companyId = :companyId ORDER BY id DESC LIMIT :start, :end";





    //workType
    public static $createWorkType   = "INSERT INTO workType (company, name) VALUES (:company, :name)";
    public static $updateWorkType   = "UPDATE workType SET name = :name WHERE id = :id and company = :company";
    public static $deleteWorkType   = "DELETE FROM workType WHERE id = :id and company = :company";


    //console
    public static $getMaxFiles      = "SELECT max_files_month_GB as maxFiles  FROM companyTier WHERE id = (SELECT tier from company WHERE id = :company)";
    public static $getMaxTexts      = "SELECT max_texts_user     as maxTexts  FROM companyTier WHERE id = (SELECT tier from company WHERE id = :company)";
    public static $getCurrentTexts  = "SELECT COUNT(*) FROM text WHERE company = :company AND MONTH(date) = :month and YEAR(date) = :year";
    public static $getMaxEmails     = "SELECT max_emails_user    as maxEmails FROM companyTier WHERE id = (SELECT tier from company WHERE id = :company)";
    public static $getCurrentEmails = "SELECT COUNT(*) FROM email WHERE company = :company AND MONTH(date) = :month and YEAR(date) = :year";

    //passwordReset
    public static $resetCode        = "INSERT INTO passwordReset (user, resetCode, time) VALUES (:user, :resetCode, :time)";
    public static $resetCodeExists  = "SELECT * FROM passwordReset WHERE resetCode = :resetCode AND time > DATE_ADD(:date, INTERVAL -100 MINUTE) AND used = 0";
    public static $updatePassword   = "UPDATE user SET password = :password WHERE id = :id";
    public static $resetUsed        = "UPDATE passwordReset SET used = 1 WHERE resetCode = :resetCode";


    public static $updateDailyEmail       = "UPDATE company set dailyEmail = :email WHERE id = :id";
    public static $updatePreJobNum        = "UPDATE company set preJobNumber = :preJn WHERE id = :id";
    public static $updatePermitEmail      = "UPDATE company set permitEmail = :permitEmail WHERE id = :id";
    public static $updateInspectionEmail  = "UPDATE company set inspectionEmail = :inspectionEmail WHERE id = :id";
    public static $getCompanies           = "SELECT * FROM company WHERE id > 0";
    public static $getFileReqLevel        = "SELECT * FROM jobFile WHERE file = :file LIMIT 1";

    //invite
    public static $getInvite          = "SELECT * FROM invite WHERE code = :code";
    public static $deleteInvite       = "DELETE FROM invite WHERE code = :code";

    //job columns selection
    public static $jobColCreateSuperAdmin    = "INSERT INTO viewJobCols (user, number, name, status, client, location, type, fieldworker, description, ponumber, bid, view, edit, dispatch, deletes, download, daily) VALUES (:user, 1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,0)";
    public static $jobColCreateAdministrator = "INSERT INTO viewJobCols (user, number, name, status, client, location, type, fieldworker, description, ponumber, bid, view, edit, dispatch, deletes, download, daily) VALUES (:user, 1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,0)";
    public static $jobColCreateOfficeStaff   = "INSERT INTO viewJobCols (user, number, name, status, client, location, type, fieldworker, description, ponumber, bid, view, edit, dispatch, deletes, download, daily) VALUES (:user, 1,1,1,1,1,1,1,1,1,0,0,1,1,0,1,0)";
    public static $jobColCreateForeman       = "INSERT INTO viewJobCols (user, number, name, status, client, location, type, fieldworker, description, ponumber, bid, view, edit, dispatch, deletes, download, daily) VALUES (:user, 1,1,1,1,1,1,1,1,1,0,0,1,1,0,0,1)";
    public static $jobColCreateFieldWorker   = "INSERT INTO viewJobCols (user, number, name, status, client, location, type, fieldworker, description, ponumber, bid, view, edit, dispatch, deletes, download, daily) VALUES (:user, 1,1,1,1,1,1,1,1,1,0,1,0,0,0,0,1)";
    public static $jobColUpdate           = "UPDATE viewJobCols set :col = :val WHERE user = :user";
    public static $getJobColumns          = "SELECT * FROM viewJobCols WHERE user = :user";

    //company
    public static $getCompanyId             = "SELECT company FROM user WHERE cookie = :cookie";
    public static $getCompany               = "SELECT * FROM company WHERE id = :id";
    public static $getCompanyBySession      = "SELECT * FROM company WHERE stripeSession = :stripeSession";
    public static $getCompanyBySubscription = "SELECT * FROM company WHERE stripeId = :stripeSubscription";
    public static $getCompanyByCustomerId   = "SELECT * FROM company WHERE customerId = :customerId";
    public static $companyCreate            = "INSERT INTO company (name, email, phone, address, tier, created, current_period_end, stripeSession, customerId) VALUES (:name, :email, :phone, :address, :tier, :created, :periodEnd, :stripeSession, :customerId)";
    public static $companyUpdate            = "UPDATE company SET name = :name, email = :email, phone = :phone, address = :address, tier = :tier, current_period_end = :periodEnd, stripeId = :stripeId, customerId = :customerId WHERE id = :id";
    public static $updateCurrentPeriodEnd   = "UPDATE company SET current_period_end = :current_period_end WHERE id = :id";
    //jobs
    public static $getStarredJobs   = "SELECT * from starJobs WHERE user = :user";
    public static $removeStar       = "DELETE FROM starJobs WHERE user = :user AND job = :job";
    public static $addStar          = "INSERT INTO starJobs (user, job) VALUES (:user, :job)";
    public static $getJobs          = "SELECT
                                       job.id, job.number, job.name, job.creator, job.type, job.status, job.createDate, job.changeOrder,
                                       GROUP_CONCAT(DISTINCT userJob.user) As workers,
                                       GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                       jobDescription.description,
                                       jobBid.bid,
                                       GROUP_CONCAT(DISTINCT jobFile.file) AS files,
                                       jobLocation.location,
                                       jobNote.note,
                                       jobPoNumber.poNumber,
                                       count(inspection.id) as openInspections,
                                       clientJob.client
                                       FROM job
                                       LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                       LEFT JOIN userJob        ON job.id = userJob.job
                                       LEFT JOIN jobDescription ON job.id = jobDescription.job
                                       LEFT JOIN jobBid 		ON job.id = jobBid.job
                                       LEFT JOIN jobFile 		ON job.id = jobFile.job
                                       LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                       LEFT JOIN jobNote 	    ON job.id = jobNote.job
                                       LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                       LEFT JOIN clientJob      ON job.id = clientJob.job
                                       LEFT JOIN inspection     ON job.id = inspection.job AND (inspection.status = 1 OR inspection.status = 2)
                                       WHERE job.company = :company
                                       GROUP BY job.id
                                       ORDER BY job.number DESC, job.id DESC";
    public static $getLimitedJobs   = "SELECT
                                        job.id, job.number, job.name, job.creator, job.type, job.status, job.createDate, job.changeOrder,
                                        GROUP_CONCAT(DISTINCT userJob.user) As workers,
                                        GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                        jobDescription.description,
                                        jobBid.bid,
                                        GROUP_CONCAT(DISTINCT jobFile.file) AS files,
                                        jobLocation.location,
                                        jobNote.note,
                                        jobPoNumber.poNumber,
                                        count(inspection.id) as openInspections,
                                        clientJob.client
                                        FROM job
                                        LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                        LEFT JOIN userJob        ON job.id = userJob.job
                                        LEFT JOIN jobDescription ON job.id = jobDescription.job
                                        LEFT JOIN jobBid 		 ON job.id = jobBid.job
                                        LEFT JOIN jobFile 		 ON job.id = jobFile.job
                                        LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                        LEFT JOIN jobNote 	     ON job.id = jobNote.job
                                        LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                        LEFT JOIN clientJob      ON job.id = clientJob.job
                                        LEFT JOIN inspection     ON job.id = inspection.job AND (inspection.status = 1 OR inspection.status = 2)
                                        WHERE job.company = :company
                                        GROUP BY job.id
                                        ORDER BY job.number DESC, job.id DESC
                                        LIMIT :start, :size";
    public static $getJobById       = "SELECT
                                       job.id, job.number, job.name, job.creator, job.type, job.status, job.createDate, job.changeOrder,
                                       GROUP_CONCAT(DISTINCT userJob.user) AS workers,
                                       GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                       jobDescription.description,
                                       jobBid.bid,
                                       GROUP_CONCAT(DISTINCT jobFile.file) AS files,
                                       jobLocation.location,
                                       jobNote.note,
                                       jobPoNumber.poNumber,
                                       count(inspection.id) as openInspections,
                                       clientJob.client
                                       FROM job
                                       LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                       LEFT JOIN userJob        ON job.id = userJob.job
                                       LEFT JOIN jobDescription ON job.id = jobDescription.job
                                       LEFT JOIN jobBid 		ON job.id = jobBid.job
                                       LEFT JOIN jobFile 		ON job.id = jobFile.job
                                       LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                       LEFT JOIN jobNote 	    ON job.id = jobNote.job
                                       LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                       LEFT JOIN clientJob      ON job.id = clientJob.job
                                       LEFT JOIN inspection     ON job.id = inspection.job AND (inspection.status = 1 OR inspection.status = 2)
                                       WHERE job.company = :company AND job.id = :id
                                       GROUP BY job.number";
    public static $getMaxChangeOrder= "SELECT
                                        job.id, job.number, job.name, job.creator, job.type, job.status, job.createDate, job.changeOrder,
                                        GROUP_CONCAT(DISTINCT userJob.user) AS workers,
                                        GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                        jobDescription.description,
                                        jobBid.bid,
                                        GROUP_CONCAT(DISTINCT jobFile.file) AS files,
                                        jobLocation.location,
                                        jobNote.note,
                                        jobPoNumber.poNumber,
                                        clientJob.client
                                        FROM job
                                        LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                        LEFT JOIN userJob        ON job.id = userJob.job
                                        LEFT JOIN jobDescription ON job.id = jobDescription.job
                                        LEFT JOIN jobBid 		 ON job.id = jobBid.job
                                        LEFT JOIN jobFile 		 ON job.id = jobFile.job
                                        LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                        LEFT JOIN jobNote 	     ON job.id = jobNote.job
                                        LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                        LEFT JOIN clientJob      ON job.id = clientJob.job
                                        WHERE job.company = :company AND job.number = :number
                                        GROUP BY job.id";
    public static $searchVar        = "SET @search = :search;";
    public static $searchJobs       =" SELECT
                                        job.id, job.number, job.name, job.creator, job.type, job.status, job.createDate, job.changeOrder,
                                        GROUP_CONCAT(DISTINCT userJob.user) As workers,
                                        GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                        jobDescription.description,
                                        jobBid.bid,
                                        jobLocation.location,
                                        jobNote.note,
                                        jobPoNumber.poNumber,
                                        count(inspection.id) as openInspections,
                                        clientJob.client,
                                        LOCATE(:search,job.number),
                                        LOCATE(:search,job.name),
                                        LOCATE(:search,jobLocation.location),
                                        LOCATE(:search,jobDescription.description)
                                        FROM job
                                        LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                        LEFT JOIN userJob        ON job.id = userJob.job
                                        LEFT JOIN jobDescription ON job.id = jobDescription.job
                                        LEFT JOIN jobBid 		 ON job.id = jobBid.job
                                        LEFT JOIN jobFile 		 ON job.id = jobFile.job
                                        LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                        LEFT JOIN jobNote 	     ON job.id = jobNote.job
                                        LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                        LEFT JOIN clientJob      ON job.id = clientJob.job
                                        LEFT JOIN inspection     ON job.id = inspection.job AND (inspection.status = 1 OR inspection.status = 2)
                                        WHERE
                                        job.company = :company AND
                                        (LOCATE(:search,job.number)>0 OR
                                        LOCATE(:search,job.name)>0 OR
                                        LOCATE(:search,jobLocation.location)>0 OR
                                        LOCATE(:search,jobDescription.description)>0 OR
                                        clientJob.client IN (:clientNumbers))
                                        GROUP BY job.id
                                        ORDER BY job.number DESC, job.id DESC";
    public static $searchClients    = "SELECT id, LOCATE(:search,name) from client WHERE LOCATE(:search,name)>0";
    public static $getJobsByClient  = " WHERE clientJob.client = :id";
    public static $nextJobNumber    = "SELECT MAX(number)+1 as maxNum FROM job WHERE company = :company";
    public static $deleteJob        = "DELETE FROM job WHERE id = :id AND company = :company";
    public static $jobExport        = "SELECT
                                        job.id, job.number, job.name, (SELECT name from user WHERE job.creator = id) as creator, (SELECT name from type WHERE id = job.type) as type, (SELECT name from status WHERE id = job.status) as status, job.createDate, job.changeOrder,
                                        GROUP_CONCAT(DISTINCT userJob.user) As workers,
                                        GROUP_CONCAT(DISTINCT jobManagers.manager) As managers,
                                        GROUP_CONCAT(DISTINCT (SELECT name from user WHERE userJob.user = id)) AS workerNames,
                                        jobDescription.description,
                                        jobBid.bid,
                                        jobLocation.location,
                                        jobNote.note,
                                        jobPoNumber.poNumber,
                                        clientJob.client
                                        FROM job
                                        LEFT JOIN jobManagers    ON job.id = jobManagers.job
                                        LEFT JOIN userJob        ON job.id = userJob.job
                                        LEFT JOIN jobDescription ON job.id = jobDescription.job
                                        LEFT JOIN jobBid 		 ON job.id = jobBid.job
                                        LEFT JOIN jobFile 		 ON job.id = jobFile.job
                                        LEFT JOIN jobLocation    ON job.id = jobLocation.job
                                        LEFT JOIN jobNote 	     ON job.id = jobNote.job
                                        LEFT JOIN jobPoNumber    ON job.id = jobPoNumber.job
                                        LEFT JOIN clientJob      ON job.id = clientJob.job
                                        WHERE job.company = :company
                                        GROUP BY job.id
                                        ORDER BY job.number DESC";
    public static $getLastInsertedJob = "SELECT MAX(id) as jobId FROM job WHERE company = :company";


    //tiers
    public static $getTiers              = "SELECT * FROM tier";
    public static $getTierById           = "SELECT * FROM tier WHERE tier_id = :id";


    //location
    public static $clientLocations  = "SELECT DISTINCT location from jobLocation WHERE job IN (SELECT job from clientJob WHERE client = :client);";

    //users
    public static $getUsers         = "SELECT *  FROM user WHERE company  = :company";
    public static $getEnabledUsers  = "SELECT *  FROM user WHERE company  = :company and disabled = 0";
    public static $checkUsername    = "SELECT id FROM user WHERE username = :username";
    public static $checkCookie      = "SELECT id FROM user WHERE cookie   = :cookie";
    public static $getCurrentUser   = "SELECT *  FROM user WHERE cookie   = :cookie   LIMIT 1";
    public static $getUserByUsername= "SELECT *  FROM user WHERE username = :username LIMIT 1";
    public static $getUserByIdPassReset = "SELECT *  FROM user WHERE id = :id LIMIT 1";
    public static $getUserById      = "SELECT *  FROM user WHERE id       = :id       AND company  = :company LIMIT 1";
    public static $createUser       = "INSERT INTO user (name, username, email, phone, password, type2, disabled, company) VALUES (:name, :username, :email, :phone, :password, :type2, :disabled, :company)";
    public static $deleteUser       = "DELETE FROM user WHERE id = :id AND company = :company";
    public static $userExport       = "SELECT id, username, name, email, phone FROM user WHERE company = :company";
    public static $disableMobileTable="UPDATE user SET mobileTable = 0 WHERE id = :id and company = :company";
    public static $enableMobileTable= "UPDATE user SET mobileTable = 1 WHERE id = :id and company = :company";

    //userTypes
    public static $getUserTypes     = "SELECT * FROM userTypes WHERE company = :company";
    public static $getUserTypeById  = "SELECT * FROM userTypes WHERE id = :id AND (company = :company OR company = 0)";
    public static $createUserType   = "INSERT INTO userTypes (name, permissions, fields, company) values (:name, :permissions, :fields, :company)";
    public static $deleteField      = "DELETE FROM fields WHERE id = :id";
    public static $deletePermission = "DELETE FROM permissions WHERE id = :id";
    public static $deleteUserType   = "DELETE FROM userTypes WHERE id = :id AND company = :company";
    public static $updateUserType   = "UPDATE userTypes set name = :name WHERE id = :id";

    //clients
    public static $getClients       = "SELECT * FROM client WHERE company = :company ORDER BY name";
    public static $getClientById    = "SELECT * FROM client WHERE company = :company AND id = :id limit 1";
    public static $createClient     = "INSERT INTO client (name, address, phone, email, marketingOkay, company) VALUES (:name, :address, :phone, :email, :marketingOkay, :company)";
    public static $deleteClient     = "DELETE FROM client WHERE id = :id AND company = :company";
    public static $clientExport     = "SELECT id, name, address, phone, email FROM client WHERE company = :company";
    //statuses
    public static $getStatuses = "SELECT status.*,
                                        GROUP_CONCAT(DISTINCT statusEmail.email) As emails,
                                        GROUP_CONCAT(DISTINCT statusAllowedTypes.userType) As types
                                        FROM status
                                        LEFT JOIN statusEmail        ON status.id = statusEmail.status
                                        LEFT JOIN statusAllowedTypes ON status.id = statusAllowedTypes.status
                                        WHERE company = :company
                                        GROUP BY status.id
                                        ORDER BY position";
    public static $getStatusesByFunc = "SELECT status.*,
                                        GROUP_CONCAT(DISTINCT statusEmail.email) As emails,
                                        GROUP_CONCAT(DISTINCT statusAllowedTypes.userType) As types
                                        FROM status
                                        LEFT JOIN statusEmail        ON status.id = statusEmail.status
                                        LEFT JOIN statusAllowedTypes ON status.id = statusAllowedTypes.status
                                        WHERE company = :company and function = :function
                                        GROUP BY status.id";
    public static $getEmailByStatus = "SELECT * FROM statusEmail WHERE status = :id LIMIT 1";
    public static $getStatusFunc    = "SELECT * FROM statusFunction";
    public static $createStatus     = "INSERT INTO status (company, position, name, function, color, inspectionRequired) VALUES (:company, :position, :name, :function, :color, :inspection)";
    public static $updateStatus     = "UPDATE status SET name = :name, color = :color, function = :function, position=:position, inspectionRequired=:inspection WHERE id = :id AND company = :company";
    public static $deleteStatus     = "DELETE FROM status WHERE id = :id and company = :company";
    public static $updateStatusEmail= "INSERT INTO statusEmail (status, email) VALUES (:status, :email) ON DUPLICATE KEY UPDATE email = :emaila";
    public static $deleteStatusEmail= "DELETE FROM statusEmail WHERE id = :id";
    public static $getStatusEmails  = "SELECT statusEmail.*, status.company FROM statusEmail
                                        LEFT JOIN status on status.id = statusEmail.status
                                        WHERE status.company = :company";

    public static $createStatusEmail       = "INSERT INTO statusEmail (status, email) VALUES (:status, :email)";
    public static $createStatusAllowedType = "INSERT INTO statusAllowedTypes (status, userType) VALUES (:status, :type)";
    public static $deleteStatusEmails      = "DELETE FROM statusEmail WHERE status = :status";
    public static $deleteStatusAllowedType = "DELETE FROM statusAllowedTypes WHERE status = :status";

    //types
    public static $getTypes           = "SELECT type.*,
                                          GROUP_CONCAT(DISTINCT typeManagers.manager) As managers
                                          FROM type
                                          LEFT JOIN typeManagers ON type.id = typeManagers.type
                                          WHERE company = :company
                                          group by id
                                          ORDER BY position";
    public static $updateType         = "UPDATE type SET name = :name, color = :color, position = :position WHERE id = :id AND company = :company";
    public static $createType         = "INSERT INTO type (company, position, name, color) VALUES (:company, :position, :name, :color)";
    public static $deleteType         = "DELETE FROM type WHERE company = :company and id = :id";
    public static $createTypeManager  = "INSERT INTO typeManagers (type, manager) VALUES (:type, :manager)";
    public static $deleteTypeManagers = "DELETE FROM typeManagers WHERE type = :type";

    //permissions
    public static $getFieldById       = "SELECT * FROM fields WHERE id = :id";
    public static $getPermissionsById = "SELECT * FROM permissions WHERE id = :id";
    public static $getPermissions   = "SELECT * FROM userType WHERE id = :id";
    public static $getPermissions2  = "SELECT userTypes.*, permissions.*, fields.* FROM userTypes
                                        LEFT JOIN permissions on permissions.id = userTypes.permissions
                                        LEFT JOIN fields on fields.id = userTypes.fields
                                        WHERE userTypes.id = :id";
    public static $getPermissionsByType  = "SELECT userTypes.*, permissions.*, fields.* FROM userTypes
                                            LEFT JOIN permissions on permissions.id = userTypes.permissions
                                            LEFT JOIN fields on fields.id = userTypes.fields
                                            WHERE userTypes.id = :id and userTypes.company = :company";
    //transactions
    public static $txGetJobId                = "SELECT @idVar:=MAX(id)+1 FROM job;";
    public static $txCreateJob               = "INSERT INTO job (number, name, creator, type, status, createDate, company) VALUES (:number,:name,:creator,:type,:status,:createDate,:company);";
    public static $txCreateJobChangeOrder    = "INSERT INTO job (number, name, creator, type, status, createDate, company, changeOrder) VALUES (:number,:name,:creator,:type,:status,:createDate,:company, :changeOrder);";
    public static $txCreateJobPO             = "INSERT INTO jobPoNumber        (job, poNumber)    VALUES (@idVar,:poNumber);";
    public static $txCreateJobLocation       = "INSERT INTO jobLocation        (job, location)    VALUES (@idVar,:location);";
    public static $txCreateJobNote           = "INSERT INTO jobNote            (job, note)        VALUES (@idVar,:note);";
    public static $txCreateJobBid            = "INSERT INTO jobBid             (job, bid)         VALUES (@idVar,:bid);";
    public static $txCreateJobDescription    = "INSERT INTO jobDescription     (job, description) VALUES (@idVar,:description);";
    public static $txCreateClientJob         = "INSERT INTO clientJob          (job, client)      VALUES (@idVar,:client);";
    public static $txCreateUserJob           = "INSERT INTO userJob            (job, user)        VALUES (@idVar,:user);";
    public static $txCreateJobManager        = "INSERT INTO jobManagers        (job, manager)     VALUES (@idVar,:user);";
    public static $txCreateJobFile           = "INSERT INTO jobFile            (job, file)        VALUES (@idVar,:file);";
    public static $txCreateJobFilePermission = "INSERT INTO jobFilePermissions (file, allowed)    VALUES (:file, :allowed)";
    public static $txDeleteJobFilePermission = "DELETE FROM jobFilePermissions WHERE file = :file and allowed = :allowed";

    //job file permissions
    public static $getJobFileLevels   = "SELECT * FROM jobFilePermissions WHERE file = :file";


    //messaging
    public static $textLog                = "INSERT INTO text (messageSid, toUser, content, date, deliveryStatus, company, number) VALUES (:sid, :user, :content, :date, :delivery, :company, :number);";
    public static $textStatusUpdate       = "UPDATE text SET deliveryStatus = :delivery WHERE messageSid = :messageSid;";
    public static $checkMessageDelivery   = "SELECT deliveryStatus FROM text WHERE messageSid = :messageSid";
    public static $emailLog               = "INSERT INTO email (company, subject, date, email) VALUES (:company, :subject, :date, :email);";

    //files
    public static $getUploadedSize        = "SELECT SUM(size) FROM file WHERE company = :company";
    public static $getMonthUploadedSize   = "SELECT SUM(size) FROM file WHERE MONTH(date) = :month AND YEAR(date) = :year AND company = :company";
    public static $getFileById            = "SELECT * FROM file WHERE id = :id and company = :company";
    public static $getPublicFileById      = "SELECT * FROM file WHERE id = :id";
    public static $getJobFiles            = "SELECT jobFile.*, file.*
                                                FROM jobFile
                                                LEFT JOIN file ON jobFile.file = file.id
                                                WHERE file.company = :company";

    public static $getDailyFiles          = "SELECT dailyFile.*, file.*
                                                FROM dailyFile
                                                LEFT JOIN file ON dailyFile.file = file.id
                                                WHERE dailyFile.daily = :daily";
    public static $fileDeleteFailure      = "DELETE FROM file WHERE id = :id and company = :company";
    public static $uploadFile             = "INSERT INTO file (url, name, size, date, uploader, company) VALUES (:url, :name, :size, :date, :uploader, :company)";
    public static $insertJobFile          = "INSERT INTO jobFile(job, file) VALUES (:job,:file);";
    public static $deleteJobFile          = "DELETE FROM jobFile WHERE file = :file";
    public static $getFileSizeByDaily     = "SELECT
                                            SUM(file.size) as size
                                            FROM dailyFile
                                            LEFT JOIN file ON file.id = dailyFile.file
                                            WHERE daily = :daily";
    //daily
    public static $getWorkerHours         = 'SELECT daily.job, daily.date, daily.completed, dailyWorkers.worker, dailyWorkers.hours, dailyWorkers.workType FROM daily
                                                LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily
                                                LEFT JOIN job ON daily.job = job.id
                                                WHERE daily.date between :start and :end AND job.company = :company';
    public static $deleteDaily            = "DELETE FROM daily WHERE id = :id";
    public static $txGetDailyId           = "SELECT @dailyVar:=MAX(id)+1 FROM daily;";
    public static $txSetDailyId           = "SELECT @dailyVar:=:dailyId FROM daily;";
    public static $txCreateDaily          = "INSERT INTO daily (job, date, created, creator, mileage, completed, material, sowChanges, equipment, goals, notes, issues) VALUES (:job, :date, :created, :creator, :mileage, :completed, :material, :sowChanges, :equipment, :goals, :notes, :issues);";
    public static $txCreateDailyFile      = "INSERT INTO dailyFile (daily, file) VALUES (@dailyVar, :file);";
    public static $txDeleteDailyFile      = "DELETE FROM dailyFile WHERE daily = @dailyVar AND file = :file);";
    public static $txCreateDailyWorker    = "INSERT INTO dailyWorkers (daily, worker, hours, workType) VALUES (@dailyVar, :worker, :hours, :workType)";
    public static $getDailyCountByJob     = "SELECT id FROM daily WHERE job = :job";
    public static $getDailyById           = "SELECT
                                            daily.*,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            WHERE daily.id = :id
                                            GROUP BY daily.id";
    public static $getDailyByJobId        = "SELECT
                                            daily.*,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files,
                                            SUM(DISTINCT dailyWorkers.hours) as hours
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily
                                            WHERE daily.job = :job
                                            GROUP BY daily.id
                                            ORDER BY daily.date DESC";
    public static $getDailysByUserId      = "SELECT
                                            daily.*,
                                            job.company as company,
                                            job.number as number,
                                            job.name as name,
                                            SUM(DISTINCT dailyWorkers.hours) as hours,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            LEFT JOIN job          ON daily.job = job.id
                                            LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily
                                            WHERE daily.creator = :creator
                                            GROUP BY daily.id
                                            ORDER BY daily.date DESC";
    public static $getDailyHours          = "SELECT
                                            daily.*,
                                            job.company as company,
                                            job.number as number,
                                            job.name as name,
                                            job.changeOrder as co,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files,
                                            dailyWorkers.*
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            LEFT JOIN job          ON daily.job = job.id
                                            LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily
                                            WHERE dailyWorkers.worker = :id
                                            GROUP BY daily.id
                                            ORDER BY daily.date DESC";
    public static $getDailies            = "SELECT
                                            daily.*,
                                            job.company as company,
                                            job.number as number,
                                            job.name as name,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files,
                                            SUM(DISTINCT dailyWorkers.hours) as hours
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            LEFT JOIN job          ON daily.job = job.id
                                            LEFT JOIN dailyWorkers ON daily.id = dailyWorkers.daily
                                            WHERE job.company = :company
                                            GROUP BY daily.id
                                            ORDER BY daily.date DESC";
    public static $getDailiesByDates     = "SELECT
                                            daily.*,
                                            job.company as company,
                                            job.number as number,
                                            job.name as name,
                                            GROUP_CONCAT(DISTINCT dailyFile.file) As files
                                            FROM daily
                                            LEFT JOIN dailyFile    ON daily.id = dailyFile.daily
                                            LEFT JOIN job          ON daily.job = job.id
                                            WHERE job.company = :company AND
                                            daily.date >= :start AND daily.date <= :end
                                            GROUP BY daily.id
                                            ORDER BY daily.date DESC";

    public static $getHoursByDaily       = "SELECT * FROM dailyWorkers WHERE daily = :daily";
    public static $invite                = "INSERT INTO invite (level, code, email, company) VALUES (:level, :code, :email, :company)";
    public static $getAnyDaily            = "SELECT daily.isPublic, job.company FROM anchorJobs.daily LEFT JOIN job ON daily.job = job.id WHERE daily.id = :id";
    public static $dailyShare             = "UPDATE daily LEFT JOIN job on daily.job = job.id SET daily.isPublic = 1 WHERE daily.id = :id and job.company = :company";
    public static $dailyUnShare           = "UPDATE daily LEFT JOIN job on daily.job = job.id SET daily.isPublic = 0 WHERE daily.id = :id and job.company = :company";

    //Work Types
    public static $getWorkTypes     = "SELECT * FROM workType WHERE company = :company";

    public static $getMessages      = "SELECT * FROM messages WHERE senderId = :sender OR receiverId = :receiver";
}


class Transaction {
    private $queries = array();

    public function run() {
        global $databaseConnection;
        $conn = $databaseConnection->conn;
        if (empty($this->queries)) {
            return true;
        }
        try {
            $conn->beginTransaction();
            foreach($this->queries as $query) {
                $stmt = $conn->prepare($query['query']);
                $parameters = (isset($query['parameters'])) ? $query['parameters'] : NULL;
                /*echo '<br />';
                echo $query['query'];
                echo '<br />';
                var_dump($parameters);
                echo '<br />';*/

                $stmt->execute($parameters);
            }
            $conn->commit();
            return true;
        }catch (Exception $e){
            $conn->rollBack();
            return false;
        }
    }
    public function addQuery($query) {
        array_push($this->queries, $query);
    }
}

class TransactionReturnId {
    private $queries = array();

    public function run() {
        global $databaseConnection;
        $conn = $databaseConnection->conn;
        if (empty($this->queries)) {
            return true;
        }
        try {
            $conn->beginTransaction();
            $var = 0;
            foreach($this->queries as $query) {
                $stmt = $conn->prepare($query['query']);
                if ($var == 0) {
                    $var = $conn->lastInsertId();
                }

                $parameters = (isset($query['parameters'])) ? $query['parameters'] : NULL;
                $stmt->execute($parameters);
            }
            $conn->commit();
            return $var;
        }catch (Exception $e){
            $conn->rollBack();
            return false;
        }
    }
    public function addQuery($query) {
        array_push($this->queries, $query);
    }
}
