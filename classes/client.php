<?php
require_once '/var/www/globals.php';
require_once "/var/www/classes/job.php";
//require_once "/var/www/design/table.php";
//require_once "/var/www/design/form.php";
require_once '/var/www/classes/sort.php';

class client {
    private $conn;
    private $id;
    private $name;
    private $address;
    private $phone;
    private $email;
    private $marketingOkay;

    public function getId()            { return $this->id; }
    public function getName(   $local = NULL) { return (!$local) ? noHTML($this->name)    : $this->name; }
    public function getAddress($local = NULL) { return (!$local) ? noHTML($this->address) : $this->address; }
    public function getPhone(  $local = NULL) { return (!$local) ? noHTML($this->phone)   : $this->phone; }
    public function getEmail(  $local = NULL) { return (!$local) ? noHTML($this->email)   : $this->email; }
    public function getMarketingOkay() { return $this->marketingOkay; }

    private function setId($var)           { $this->id = $var; return true; }
    public function setName($var)          { if (strlen($var) < 255) { $this->name          = $var; return true; } else { return false; } }
    public function setAddress($var)       { if (strlen($var) < 255) { $this->address       = $var; return true; } else { return false; } }
    public function setPhone($var)         { if (strlen($var) < 255) { $this->phone         = $var; return true; } else { return false; } }
    public function setEmail($var)         { if (strlen($var) < 255) { $this->email         = $var; return true; } else { return false; } }
    public function setMarketingOkay($var) { if (is_numeric($var))   { $this->marketingOkay = $var; return true; } else { return false; } }

    public function __CONSTRUCT($clientArray = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($clientArray) {
            $this->setId(           $clientArray[clientData::$id]);
            $this->setName(         $clientArray[clientData::$name]);
            $this->setAddress(      $clientArray[clientData::$address]);
            $this->setPhone(        $clientArray[clientData::$phone]);
            $this->setEmail(        $clientArray[clientData::$email]);
            $this->setmarketingOkay($clientArray[clientData::$marketingOkay]);
        }
    }
    public function create() {
        $functionRun = new FunctionRun(functions::$createClient);
        global $permissions2;
        if ($permissions2->clientCreate) {
            global $companyId;
            $paramters = array();
            if (!$this->getName(true)) { $var = $functionRun->error(errors::$noClientNameOnCreate);   die;}
            $parameters[':'.clientData::$name]  = $this->getName(true);
            $parameters[':'.clientData::$address]       = ($var = $this->getAddress(true))       ? $var : "";
            $parameters[':'.clientData::$phone]         = ($var = $this->getPhone(true))         ? $var : "";
            $parameters[':'.clientData::$email]         = ($var = $this->getEmail(true))         ? $var : ""; 
            $parameters[':'.clientData::$marketingOkay] = ($var = $this->getMarketingOkay()) ? $var : 0;
            $parameters[':'.clientData::$company]       = $companyId;
            if ($stmt = run(sql::$createClient, $parameters)) {
                $client = getClientById($this->conn->lastInsertId());
                $backup = new Backup();
                $backup->setObjectId($client->getId());
                $backup->setObjectName($client->getName());
                $backup->setObjectType('Client');
                $backup->setAction('Create');
                $backup->save();
                return $client;
            } else {
                $functionRun->error(errors::$clientCreateFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$noClientCreatePermission);
            return false;
        }
    }

    public function update() {
        global $permissions2;
        $functionRun = new FunctionRun(functions::$updateClient);
        if ($permissions2->clientUpdate) {
            if (!$this->getName(true))    { $functionRun->error(errors::$noClientNameOnUpdate); die; }
            $old = getClientById($this->id);$tx = new Transaction();$wCol='whereColumn';$wVal='whereVal';$tbl='table';$col='column';$data='data';$queryOptions = array();
            if ($old->getName(true)      != $this->getName(true))      { array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'client',$col=>clientData::$name,         $data=>$this->getName(true)         ));}
            if ($old->getAddress(true)   != $this->getAddress(true))   { array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'client',$col=>clientData::$address,      $data=>$this->getAddress(true)      ));}
            if ($old->getPhone(true)     != $this->getPhone(true))     { array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'client',$col=>clientData::$phone,        $data=>$this->getPhone(true)        ));}
            if ($old->getEmail(true)     != $this->getEmail(true))     { array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'client',$col=>clientData::$email,        $data=>$this->getEmail(true)        ));}
            if ($old->getMarketingOkay() != $this->getMarketingOkay()) { array_push($queryOptions,array($wCol=>'id', $wVal=>$this->getId(),$tbl=>'client',$col=>clientData::$marketingOkay,$data=>$this->getMarketingOkay()));}
            foreach($queryOptions as $option) {
                $query = "UPDATE {$option[$tbl]} SET {$option[$col]} = :{$option[$col]}  WHERE {$option[$wCol]} = :{$option[$wCol]}";
                $parameters = array(":{$option[$col]}"=>$option[$data], ":{$option[$wCol]}"=>$option[$wVal]);
               $tx->addQuery(array('query'=>$query, 'parameters'=>$parameters));
            }
            if ($tx->run()) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Client');
                $backup->setAction('Update');
                $backup->save();
                $functionRun->log();
                return true;
            } else {
                $functionRun->error(errors::$clientUpdateFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$noClientUpdatePermission);
            return false;
        }
    }
    public function delete() {
        $functionRun = new FunctionRun(functions::$deleteClient);
        global $permissions2;
        if ($permissions2->clientDelete) {
            global $companyId;
            if (run(sql::$deleteClient, array(':id'=>$this->getId(), ':company'=>$companyId))) {
                $backup = new Backup();
                $backup->setObjectId($this->getId());
                $backup->setObjectName($this->getName());
                $backup->setObjectType('Client');
                $backup->setAction('Delete');
                $backup->save();
                $functionRun->log();
                return true;
            } else {
                $functionRun->error(errors::$clientDeleteFail);
                return false;
            }
        } else {
            $functionRun->error(errors::$noClientDeletePermission);
            return false;
        }
    }
}


function deleteClient($id) {
    $functionRun = new functionRun(functions::$deleteClient);
    if (is_numeric($id)) {
        $client = getClientById($id);
        if ($client->delete()) {
            $functionRun->log();
            return true;
        } else {
            $functionRun->error(errors::$clientDeleteFail);
            return false;
        }
    } return false;
}

function createClients($returns) {
    $clients = array();
    foreach($returns as $return) {
        $client = new Client($return);
        array_push($clients, $client);
    }
    return $clients;
}
function createClient($returns) {
    $clients = (createClients($returns));
    if (isset($clients[0])) {
        return $clients[0];
    } else {
        return false;
    }
}
function getClients() {
    global $companyId;
    $parameters = array(':'.clientData::$company => $companyId);
    $stmt = run(sql::$getClients, $parameters);
    $clients = createClients($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $clients;
}
function getClientById($id) {
    $functionRun = new functionRun(functions::$getClientById);
    global $companyId;
    $parameters = array(':'.clientData::$company => $companyId, ':'.clientData::$id => $id);
    $stmt = run(sql::$getClientById, $parameters);
    $client = createClient($stmt->fetchAll(PDO::FETCH_ASSOC));
    $functionRun->log();
    return $client;
}
function displayClients($clients) {
    $table = new Table();
    $table->addColumn( $colName      = new Column('Name')         );
    $table->addColumn( $colEmail     = new Column('Email')        ); 
    $table->addColumn( $colPhone     = new Column('Phone Number') );
    $table->addColumn( $colAddress   = new Column('Address')      );  
    //$table->addColumn( $colMarketing = new Column('Marketing')    );  
    $table->addColumn( $colActions   = new Column('Actions')      );  
    $buttons = array();
    global $permissions2;
    if (!$permissions2->clientsRead) {
        log::error(errors::$noClientReadPermission); 
        return false;
    }
    if ($permissions2->clientUpdate) {
        $editButton = new Button();
        $editButton->setStyle('background-color:#007bff;color:white;');
        $editButton->setText('<span class="fa fa-pencil"></span>');
        array_push($buttons, $editButton);
    }
    if ($permissions2->clientDelete) { 
        $deleteButton = new Button();
        $deleteButton->setStyle('background-color:#f44336;color:white;');
        $deleteButton->setText('<span class="fa fa-trash-o"></span>');
        $deleteButton->setOther('data-toggle="modal" data-target="#deleteClientModal"');
        $deleteButton->setClass('btn btn-default deleteButton');
        array_push($buttons, $deleteButton); 
    }  else {
        $viewButton = new Button();
        $viewButton->setStyle('background-color:#5bc0de;color:white;');
        $viewButton->setText('<span class="fa fa-eye"></span>');
        array_push($buttons, $viewButton);
    }
    $table->addStart(new DeleteModal('client'));
    foreach($clients as $client) {
        $row = new Row('client'.$client->getId());
        $buttons2 = array();
        if (isset($editButton))   { $editButton->setLink("/client?id={$client->getId()}");  array_push($buttons2, clone $editButton);     }
        if (isset($deleteButton)) { $deleteButton->setId($client->getId());    array_push($buttons2, clone $deleteButton);   } 
        else {
            $viewButton->setLink( "/client?id={$client->getId()}");   array_push($buttons2, clone $viewButton);
        }
        $row->addData($colName,     new Href($client->getName(),"/client?id={$client->getId()}"));
        $row->addData($colEmail,    new Paragraph($client->getEmail()));
        $row->addData($colPhone,    new Paragraph($client->getPhone()));
        $row->addData($colAddress,  new Paragraph($client->getAddress()));
        //$row->addData($colMarketing,new Paragraph($client->getMarketingOkay() ? 'Okay' : 'Not Okay'));
        $row->addData($colActions, $buttons2, 'nowrap="nowrap"');
        $table->addRow($row);
    }
    $table->display();
}
function updateExistingClient($data, $client) {
    if ($data['name']      != $client->getName())          { $client->setName(         $data['name']);      } 
    if ($data['address']   != $client->getAddress())       { $client->setAddress(      $data['address']);   } 
    if ($data['phone']     != $client->getPhone())         { $client->setPhone(        $data['phone']);     } 
    if ($data['email']     != $client->getEmail())         { $client->setEmail(        $data['email']);     } 
    if ($data['marketing'] != $client->getMarketingOkay()) { $client->setMarketingOkay($data['marketing']); } 
    $client->update();
}
function createNewClient($data) {
    $client = new Client();
    if (!$data['name']) { log::error(errors::$noClientNameOnCreate); die; } 
                             $client->setName(         $data['name']); 
    if ($data['address']  ){ $client->setAddress(      $data['address']);   } 
    if ($data['phone']    ){ $client->setPhone(        $data['phone']);     } 
    if ($data['email']    ){ $client->setEmail(        $data['email']);     } 
    if ($data['marketing']){ $client->setMarketingOkay($data['marketing']); } 
    $client->create();
}
function displayClient($clientId = NULL) {
    $functionRun = new functionRun(functions::$displayClient);
    if (isset($_POST['name'])) {
        if ($clientId) {
            updateExistingClient($_POST, getClientById($clientId));
        } else {
            createNewClient($_POST);
        }
    }
    global $permissions2;
    global $fields;
    $form = new Form();
    if(!$clientId) {
        $form->addTitle('New Client');
        $client   = new client();
        $disabled = ($permissions2->clientCreate) ? '' : 'disabled';
    } elseif (!$client = getClientById($clientId)) {
        $form->addTitle('New Client');
        $client   = new client();
        $disabled = ($permissions2->clientUpdate) ? '' : 'disabled';
    } else {
        $form->addTitle($client->getName());
        $disabled = ($permissions2->clientUpdate) ? '' : 'disabled';
    }
    $leftColumn  = new FormColumn();
        if ($fields->clientName) {
            $leftColumn->addRow(new FormTextInput('Client Name',  'name',  $client->getName(), lengths::$jobName,$disabled));
        }
        if ($fields->clientPhone) {
            $leftColumn->addRow(new FormTextInput('Client Phone', 'phone', $client->getPhone(),lengths::$jobName,$disabled));
        }
        if ($fields->clientEmail) {
            $leftColumn->addRow(new FormTextInput('Client Email', 'email', $client->getEmail(),lengths::$jobName,$disabled));
        }
    $rightColumn = new FormColumn();
        if ($fields->clientAddress) {
            $rightColumn->addRow(new FormTextInput('Address',  'address',  $client->getAddress(),      lengths::$jobPo, $disabled));
        }
        //$rightColumn->addRow(new FormTextInput('Marketing','marketing',$client->getMarketingOkay(),lengths::$jobBid,$disabled));
    $form->addColumns(array($leftColumn, $rightColumn));

    $submitButton = new FormButton('Submit','submit','btn-primary', NULL,true);
    if ($permissions2->jobUpdate) {
        $buttonRow = new FormRow();
        $buttonRow->addField($submitButton);
        $form->addRow($buttonRow);
    }
    $form->display();
    if ($clientId) {
        $sort = new Sort();
        $sort->addClient($client->getId());
        global $userSortValue;
        $userSortValue = $sort;
        $jobs = getJobs();
        $jobs = $sort->run($jobs);
        displayJobs($jobs);
        echo '</form>';
    }
    $functionRun->log();
}