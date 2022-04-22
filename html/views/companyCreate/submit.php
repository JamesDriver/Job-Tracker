<?php
require_once '/var/www/all/design/stripe.php';
require_once '/var/www/classes/company.php';
require_once '/var/www/classes/data.php';
require_once '/var/www/classes/database.php';
require_once '/var/www/classes/userType.php';
require_once '/var/www/classes/fields.php';
require_once '/var/www/classes/permissions.php';
require_once '/var/www/classes/user.php';
require_once '/var/www/classes/workType.php';
require_once '/var/www/classes/type.php';
require_once '/var/www/classes/status.php';

global $databaseConnection;
$databaseConnection = new Database(db::$dbName);
$stripe = new Stripe();
error_log(1);
$session = $stripe->newCompanySession($_POST['price']);
error_log(3);

//company creation
$company = new Company();
$company->setName($_POST['company_name']);
$company->setEmail($_POST['company_email']);
$company->setPhone($_POST['company_phone']);
$address = $_POST['company_address'] . ', ' . $_POST['company_city'] . ' ' . $_POST['company_state'] . ', ' . $_POST['company_zip'] . ', ' . $_POST['company_country'];
$company->setAddress($address);
$company->setStripeSession($session['id']);
$tiers = getTiers();
if (!$tiers['price_'.$_POST['price']]) {
    echo 'please select a valid tier';
    die;
}
$company->setTier($tiers['price_'.$_POST['price']]);

global $permissions2;
$permissions2 = new Permissions2();
$permissions2->setAll();
$company = $company->create();
global $companyId;
$companyId = $company->getId();
//user types creation

//this creates permissions and fields for three default userTypes
$userTypes = userTypeCreateDefaults($company->getId());
$admin = $userTypes['admin'];
$userTypes = getUserTypes();

//work type creation
$workType = new WorkType();
$workType->setName('(none)');
$workType->create();
//types creation
$type = new Type();
$type->setOrder(0);
$type->setName('Residential');
$type->setColor('#f2f2f2');
$type->create();
$type = new Type();
$type->setOrder(1);
$type->setName('Commercial');
$type->setColor('#5bc0de');
$type->create();
//statuses creation
$status = new Status();
$status->setOrder(0);
$status->setName('Not Dispatched');
$status->setFunction(2);
$status->setColor('#8f0000');
foreach($userTypes as $type) {
    $status->addAllowedUserType($type);
}
$status->create();
$status = new Status();
$status->setOrder(1);
$status->setName('Dispatched');
$status->setFunction(3);
$status->setColor('#3cb371');
$usertypes = getUserTypes();
foreach($userTypes as $type) {
    $status->addAllowedUserType($type);
}
$status->create();
$status = new Status();
$status->setOrder(2);
$status->setName('Completed');
$status->setFunction(1);
$status->setColor('#f5c242');
$usertypes = getUserTypes();
foreach($userTypes as $type) {
    $status->addAllowedUserType($type);
}
$status->create();
//admin user creation
$user = new User();
$user->setName($_POST['first_name'] . ' ' . $_POST['last_name']);
$user->setUsername($_POST['username']);
$user->setEmail($_POST['email']);
$user->setPhone($_POST['phone']);
$user->setPassword($_POST['password']);
$user->setType($admin);
$user->enable();
$user->create();

?>
<script src="https://js.stripe.com/v3/"></script>
<script>

var stripe = Stripe('');
stripe.redirectToCheckout({
// Make the id field from the Checkout Session creation API response
// available to this file, so you can provide it as parameter here
// instead of the {{CHECKOUT_SESSION_ID}} placeholder.
sessionId: '<?php echo $session['id']; ?>'
}).then(function (result) {
// If `redirectToCheckout` fails due to a browser or network
// error, display the localized error message to your customer
// using `result.error.message`.
});
</script>
