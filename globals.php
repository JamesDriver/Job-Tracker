<?php
//require_once 'namespace.php';
//global $time_start;
$time_start = microtime(true);
global $count;
global $longTime;
$longTime = 0;
global $longQuery;
global $userCompanyIdFromCookie;
global $url;
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$userCompanyIdFromCookie = array();
$count = 0;
require_once "/var/www/classes/database.php";
require_once "/var/www/classes/user.php";
require_once "/var/www/classes/data.php";
//require_once "/var/www/classes/permissions.php";
require_once "/var/www/classes/permissions.php";
require_once "/var/www/classes/fields.php";
require_once "/var/www/classes/company.php";
require_once "/var/www/classes/file.php";
require_once "/var/www/classes/daily.php";
//require_once "/var/www/design/table.php";
//require_once "/var/www/design/form.php";
global $databaseConnection;
$databaseConnection = new Database(db::$dbName);
if (isset($_COOKIE[''])) {
    if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) != '/login') {
        if (isValidCookie($_COOKIE[''], $_COOKIE[''])) {
            global $companyId;
            $companyId = getCompanyId($_COOKIE['']);
            global $currentUser;
            $currentUser = getUserById($_COOKIE['']);
            if ($currentUser->isDisabled()) {
                echo 'Your account has been disabled. If you believe this is a mistake, please contact your system administrator for help';
                die;
            }
            if ($companyId == 0) {
                if (isset($_COOKIE['companyIdSessionVar'])) {
                    $admin = true;
                    $companyId = $_COOKIE['companyIdSessionVar'];
                //} else {
                //    $companyId = 1;
                //}
                }
            }
            global $preJn;
            setPermissions2();
            $company = getCompanyById($companyId);
            $preJn = $company->getPreJn();
            if (!$company->isEnabled()) {
                if (!isset($admin)) {
                    if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) != '/settings/billing' &&
                        parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) != '/settings/noPayment') {
                        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";
                        $current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
                        if (($currentUser->getType())->getId() == 1) {
                            $url = $protocol . $current_host . '/settings/billing';
                            echo "<script>window.location.href = '{$url}';</script>";
                            die;
                        } else {
                            $url = $protocol . $current_host . '/settings/noPayment';
                            echo "<script>window.location.href = '{$url}';</script>";
                            die;
                        }
                    }
                }
            }
            pageLoad::log(basename($_SERVER['REQUEST_URI']));
        } else {
            pageAccessCheck();
        }
    }
} else {
    pageAccessCheck();
}

function pageAccessCheck() {
    $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if ($url != '/login'                      &&
        $url != '/join'                       &&
        $url != '/actions/passwordReset'      &&
        $url != '/actions/passwordUpdate'     &&
        $url != '/actions/readTwilioCallback' &&
        $url != '/payment'                    &&
        $url != '/actions/getNextStatus'      &&
        $url != '/actions/getNextType'        &&
        $url != '/actions/stripeWebhook'      &&
        $url != '/actions/usernameCheck'      &&
        $url != '/companyCreate'              &&
        !fnmatch('/image/*', $url)            &&
        !fnmatch('/publicImage/*', $url)      &&
        !fnmatch('/daily/public/*', $url)
    ) {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";
        $current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $url = $protocol . $current_host . '/login?url=' . $_SERVER['REQUEST_URI'];
        echo "<script>window.location.href = '{$url}';</script>";
        return false;
    } return true;
}
