<?php
require_once '/var/www/vendor/autoload.php';
require_once('/var/www/classes/data.php');
require_once('/var/www/classes/database.php');
require_once('/var/www/classes/company.php');
require_once('/var/www/design/stripe.php');
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey('');

// If you are testing your webhook locally with the Stripe CLI you
// can find the endpoint's secret by running `stripe listen`
// Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
$endpoint_secret = '';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}



// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        //update company on creation
        error_log('checkout.session.completed');
        global $databaseConnection;
        $databaseConnection = new Database(db::$dbName);
        $subscription = $event->data->object->subscription;
        $session      = $event->data->object->id;
        $customer     = $event->data->object->customer;
        $company->setCustomerId($customer);
        $company = getCompanyBySession($session);
        $company->setStripeId($subscription);
        $company->update();
        break;
    case 'invoice.payment_succeeded':
        //update company currentPeriodEnd on payment success
        error_log('invoice.payment_succeeded');
        global $databaseConnection;
        $databaseConnection = new Database(db::$dbName);
        $subscription = $event->data->object->subscription;
        error_log($subscription);
        $company = getCompanyBySubscription($subscription);
        $company->setStripeId($subscription);
        if (!$company->getCustomerId()) {
            $customerId = $event->data->object->customer;
            $company->setCustomerId($customerId);
        }
        $date = new DateTime('now');
        $date->modify('+1 month');
        $date->modify('+5 day');
        $date = $date->format('Y-m-d h:i:s');
        $company->setPeriodEnd($date);
        $company->update();
        break;
    case 'charge.succeeded':
        //update company currentPeriodEnd on payment success
        error_log('charge.succeeded');
        //global $databaseConnection;
        //$databaseConnection = new Database(db::$dbName);
        //$customer = $event->data->object->customer;
        //$company = getCompanyByCustomerId($customer);
        //if (!$company->getCustomerId()) {
        //    $company->setCustomerId($customer);
        //}
        //error_log($customer);
        //$date = new DateTime('now');
        //$date->modify('+1 month'); // or you can use '-90 day' for deduct
        //$date->modify('+5 day');
        //$date = $date->format('Y-m-d h:i:s');
        //$company->setPeriodEnd($date);
        //$company->update();
        //break;
    case 'invoice.upcoming':
        error_log('invoice.upcoming');
        break;
    case 'customer.subscription.trial_will_end':
        error_log('customer.subscription.trial_will_end');
        break;
    case 'customer.subscription.past_due':
        error_log('customer.subscription.past_due');
        break;
    case 'customer.subscription.canceled':
        error_log('customer.subscription.canceled');
        break;
    case 'customer.subscription.unpaid':
        error_log('customer.subscription.unpaid');
        break;
    case 'customer.subscription.updated':
        error_log('customer.subscription.updated');
        break;
    default:
        error_log($event->type);
        // Unexpected event type
        http_response_code(200);
        exit();
}
http_response_code(200);
