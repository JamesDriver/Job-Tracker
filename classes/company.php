<?php
class Company {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $address;
    private $tier;
    private $stripeId;
    private $dailyEmail;
    private $preJobNumber;
    private $emailCount;
    private $textCount;
    private $customerId;
    //private $maxEmailCount;
    //private $maxTextCount;
    private $fileSum;
    private $stripeSession;
    private $isEnabled;
    private $current_period_end;
    private $inspectionEmail;
    private $permitEmail;

    //private $maxFileSum;
    public function setId(             $var) { $this->id                 = $var; }
    public function setName(           $var) { $this->name               = $var; }
    public function setEmail(          $var) { $this->email              = $var; }
    public function setPhone(          $var) { $this->phone              = $var; }
    public function setAddress(        $var) { $this->address            = $var; }
    public function setTier(           $var) { $this->tier               = $var; }
    public function setStripeId(       $var) { $this->stripeId           = $var; }
    public function setDailyEmail(     $var) { $this->dailyemail         = $var; }
    public function setPreJn(          $var) { $this->preJobNumber       = $var; }
    public function setEmailCount(     $var) { $this->emailCount         = $var; }
    public function setTextCount(      $var) { $this->textCount          = $var; }
    public function setFileSum(        $var) { $this->fileSum            = $var; }
    public function setStripeSession(  $var) { $this->stripeSession      = $var; }
    public function setCustomerId(     $var) { $this->customerId         = $var; }
    public function setEnabled(        $var) { $this->isEnabled          = $var; }
    public function setPeriodEnd(      $var) { $this->current_period_end = $var; }
    public function setInspectionEmail($var) { $this->inspectionEmail    = $var; }
    public function setPermitEmail(    $var) { $this->permitEmail        = $var; }

    public function getId(             ) { return $this->id              ; }
    public function getName(           ) { return noHTML($this->name)    ; }
    public function getEmail(          ) { return noHTML($this->email)   ; }
    public function getPhone(          ) { return noHTML($this->phone)   ; }
    public function getAddress(        ) { return noHTML($this->address) ; }
    public function getTier(           ) { return $this->tier            ; }
    public function getStripeId(       ) { return $this->stripeId        ; }
    public function getDailyEmail(     ) { return noHTML($this->dailyemail)  ; }
    public function getPreJn(          ) { return noHTML($this->preJobNumber); }
    public function getEmailCount(     ) {
        if (!$this->emailCount) {
            $this->setEmailCount(getCurrentEmails($this->getId()));
        }
        return $this->emailCount;
    }
    public function getTextCount(      ) {
        if (!$this->textCount) {
            $this->setTextCount(getCurrentTexts($this->getId()));
        }
        return $this->textCount;
    }
    public function getStripeSession(  ) { return $this->stripeSession     ; }
    public function getCustomerId(     ) { return $this->customerId        ; }
    public function getFileSum(        ) {
        if (!$this->fileSum) {
            $monthSize = getUploadedSize($this->getId())*.000000001;
            if ($monthSize > .01) {
                $monthSize = round($monthSize, 2);
            } else {
                $monthSize = '<0.01';
            }
            $this->setFileSum($monthSize);
        }
        return $this->fileSum;
    }
    public function isEnabled(         ) { return $this->isEnabled         ; }
    public function getPeriodEnd(      ) { return $this->current_period_end; }
    public function getInspectionEmail() { return $this->inspectionEmail   ; }
    public function getPermitEmail(    ) { return $this->permitEmail       ; }


    public function __CONSTRUCT($companyArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($companyArray) {
            $this->setId(              $companyArray['id']                           );
            $this->setName(            $companyArray['name']                         );
            $this->setEmail(           $companyArray['email']                        );
            $this->setPhone(           $companyArray['phone']                        );
            $this->setAddress(         $companyArray['address']                      );
            $this->setStripeId(        $companyArray['stripeId']                     );
            $this->setCustomerId(      $companyArray['customerId']                   );
            $this->setDailyEmail(      $companyArray['dailyEmail']                   );
            $this->setPreJn(           $companyArray['preJobNumber']                 );
            $this->setTier(getTierById($companyArray['tier'])                        );
            $this->setStripeSession(   $companyArray['stripeSession']                );
            $this->setPermitEmail(     $companyArray['permitEmail']                  );
            $this->setInspectionEmail( $companyArray['inspectionEmail']              );
            $this->setEnabled(         (date_parse($companyArray['current_period_end']) > date(format::$time)) ? true : false );
        }
    }
    public function create() {
        $end = new DateTime();
        $end->modify('+31 day');
        $parameters = array(
            ':name'          => $this->name,
            ':email'         => $this->email,
            ':phone'         => $this->phone,
            ':address'       => $this->address,
            ':customerId'    => $this->getCustomerId(),
            ':tier'          => ($this->getTier())->getId(),
            ':created'       => date(format::$time),
            ':stripeSession' => $this->getStripeSession(),
            ':periodEnd' => $end->format(format::$time)
        );
        if (run(sql::$companyCreate, $parameters)) {
            $company = getCompanyById($this->conn->lastInsertId());
            return $company;
        }
    }
    public function update() {
        $parameters = array(
            ':id'        => $this->getId(),
            ':name'      => $this->name,
            ':email'     => $this->email,
            ':phone'     => $this->phone,
            ':address'   => $this->address,
            ':customerId'=> $this->getCustomerId(),
            ':tier'      => ($this->getTier())->getId(),
            ':periodEnd' => $this->getPeriodEnd(),
            ':stripeId'  => $this->getStripeId()
        );
        if (run(sql::$companyUpdate, $parameters)) {
            $backup = new Backup();
            $backup->setObjectId($this->getId());
            $backup->setObjectName($this->getName());
            $backup->setObjectType('Company');
            $backup->setAction('Update');
            $backup->save();
            return true;
        } return false;
    }
    public function delete() {

    }
}

function createCompanies($returns) {
    $companies = array();
    foreach($returns as $return) {
        $company = new Company($return);
        array_push($companies,$company);
    }
    return $companies;
}

function getCompanies() {
    if (getCompanyId($_COOKIE['']) == 0) {
        $companies = createCompanies(run(sql::$getCompanies));
        return $companies;
    }
}

function getCompanyById($id) {
    $stmt = run(sql::$getCompany, array(':id' => $id));
    $company = createCompanies($stmt);
    return $company[0];
}

function getCompanyBySession($session) {
    $stmt = run(sql::$getCompanyBySession, array(':stripeSession' => $session));
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $company = new Company($response);
    return $company;
}
function getCompanyBySubscription($subscription) {
    $stmt = run(sql::$getCompanyBySubscription, array(':stripeSubscription' => $subscription));
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $company = new Company($response);
    return $company;
}
function getCompanyByCustomerId($customerId) {
    $stmt = run(sql::$getCompanyByCustomerId, array(':customerId' => $customerId));
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $company = new Company($response);
    return $company;
}

function companyCreate($companyArray) {
    $company = new Company();
    $address = $companyArray['companyAddress'] . ', ' . $companyArray['companyCity'] . ' ' . $companyArray['companyState'];
    $company->setName(            $companyArray['companyName']  );
    $company->setEmail(           $companyArray['companyEmail'] );
    $company->setPhone(           $companyArray['companyPhone'] );
    $company->setAddress(         $address                      );
    $company->setTier(getTierById($companyArray['companyTier']) );
    $company->setStripeSession(   $companyArray['stripeSession']);
    $company = $company->create();
    return $company;
}

class Tier {
    private $id;
    private $maxFiles;
    private $maxTexts;
    private $maxEmails;
    private $price_code;
    public function setId($var)        { $this->id          = $var; }
    public function setMaxFiles($var)  { $this->maxFiles    = $var; }
    public function setMaxTexts($var)  { $this->maxTexts    = $var; }
    public function setMaxEmails($var) { $this->maxEmails   = $var; }
    public function setPriceCode($var) { $this->price_code  = $var; }

    public function getId()        { return $this->id; }
    public function getMaxFiles()  { return $this->maxFiles; }
    public function getMaxTexts()  { return $this->maxTexts; }
    public function getMaxEmails() { return $this->maxEmails; }
    public function getPriceCode() { return $this->price_code; }
    public function __CONSTRUCT($tierArray = NULL) {
        if ($tierArray) {
            $this->setId(        $tierArray["tier_id"]              );
            $this->setPriceCode( $tierArray["tier_price_code"] );
            $this->setMaxFiles(  $tierArray["tier_max_files"]  );
            $this->setMaxTexts(  $tierArray["tier_max_texts"]  );
            $this->setMaxEmails( $tierArray["tier_max_emails"] );
        }
    }
}
function getTierById($id) {
    $stmt = run(sql::$getTierById, array(':id'=>$id));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $tier = new Tier($data);
    return $tier;
}
function getTiers() {
    $stmt = run(sql::$getTiers);
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($datas as $data) {
        $tier = new Tier($data);
        $tiers[$data['tier_price_code']] = $tier;
    }
    return $tiers;
}
