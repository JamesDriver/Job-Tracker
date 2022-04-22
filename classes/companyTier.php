<?php
function getMaxFiles($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $stmt = run(sql::$getMaxFiles, array(':company'=>$company));
    $ans = $stmt->fetchAll();
    return $ans[0]['maxFiles'];
}
function getMaxTexts($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $stmt = run(sql::$getMaxTexts, array(':company'=>$company));
    $ans = $stmt->fetchAll();
    $users = count(getEnabledUsers($company));
    $max = $ans[0]['maxTexts'];
    return $users * $max;
}
function getCurrentTexts($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $stmt = run(sql::$getCurrentTexts, array(':company'=>$company, ':month'=>date('m'), ':year'=>date('Y')));
    return $stmt->fetchColumn();
}
function getMaxEmails($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $stmt = run(sql::$getMaxEmails, array(':company'=>$company));
    $ans = $stmt->fetchAll();
    $users = count(getEnabledUsers($company));
    return $ans[0]['maxEmails'] * $users;
}
function getCurrentEmails($id = NULL) {
    global $companyId;
    $company = ($id) ? $id : $companyId;
    $stmt = run(sql::$getCurrentEmails, array(':company'=>$company, ':month'=>date('m'), ':year'=>date('Y')));
    return $stmt->fetchColumn();
}