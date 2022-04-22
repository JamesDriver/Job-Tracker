<?php
$start = microtime(true);
$error = 'false';
$username = isset($_POST['username']) ? $_POST['username'] : NULL;
$password = isset($_POST['password']) ? $_POST['password'] : NULL;
$remember = isset($_POST['remember']) ? $_POST['remember'] : NULL;
if (login($username, $password, $remember)) {
    error_log('here');
    header('Location: /'.$id);
} else {
    error_log('there');
    if ($username) {
        sleep(1.5);
        $error = 'true';
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@latest/dist/tailwind-ui.min.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js" defer></script>
    <title>login</title>

</head>

<div class="min-h-screen flex justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" x-data='{ error: <?=$error?> }'>
    <div class="max-w-md w-full">
        <div>
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>
        <form class="mt-8" method="POST">
            <input type="hidden" name="remember" value="true" />
            <div class="rounded-md shadow-sm">
                <div>
                    <input value="<?=$username?>" aria-label="Username" name="username" type='text' required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Username" />
                </div>
                <div class="-mt-px">
                    <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password" />
                </div>
            </div>


            <div class="rounded-md bg-red-50 p-4" x-show='error'>
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-medium text-red-800">
                            Incorrect Login
                        </h3>
                        <div class="mt-2 text-sm leading-5 text-red-700">
                            <ul class="list-disc pl-5">
                                <li>
                                    The entered information does not match our records
                                </li>
                                <li class="mt-1">
                                    Please try again
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remembers" type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" checked/>
                    <label for="remember" class="ml-2 block text-sm leading-5 text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm leading-5">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>


<?php
function login($username, $password, $remember) {
    if (!isset($username) || !isset($password)) {
        return false;
    }
    require_once '/var/www/classes/data.php';
    require_once '/var/www/classes/database.php';
    require_once '/var/www/classes/user.php';
    global $databaseConnection;
    $databaseConnection = new Database(db::$dbName);
    $user = getUserByUsername($username);
    if (!$user)                { return false; }
    if ($user['disabled'] = 0) { return false; }
    if (!password_verify($password, $user[userdata::$password])) { return false; }
    $cookie = randomString(20);
    $time = (isset($remember)) ? time() + (86400 * 30) : 0;
    $date = new DateTime('now');
    (isset($remember)) ? $date->modify("+31 day") : $date->modify("+2 day");
    setcookie("", $cookie, $time, "/", NULL, true, true);
    setcookie("", $user[userdata::$id], $time, "/", NULL, true, true);
    saveCookie($user[userdata::$id], $cookie, $date);
    loginLog($user[userdata::$id], date(format::$time), getClientIP());
    return true;
}

function loginLog($userId, $date, $userIp) {
    $parameters = array(':user' => $userId, ':date' => $date, ':ip' => $userIp);
    run(sql::$loginLog, $parameters);
}

function getClientIP() {
    $ipaddress = 'UNKNOWN';$keys=array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');
    foreach($keys as $k) {
        if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
            $ipaddress = $_SERVER[$k];break;
        }
    }
    return $ipaddress;
}


?>
