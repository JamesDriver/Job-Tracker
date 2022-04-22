<?php 

class Message {
    private $id;
    private $sender;
    private $receiver;
    private $sendDate;
    private $isRead;
    private $readDate;
    private $content;
    private $otherPerson;
    public function setId($var)          { $this->id       = $var; }
    public function setSender($var)      { $this->sender   = $var; }
    public function setReceiver($var)    { $this->receiver = $var; }
    public function setSendDate($var)    { $this->sendDate = $var; }
    public function setIsRead($var)      { $this->isRead   = $var; }
    public function setReadDate($var)    { $this->readDate = $var; }
    public function setContent($var)     { $this->content  = $var; }
    public function setOtherPerson($var) { $this->otherPerson = $var; }

    public function getId()          { return $this->id;       }
    public function getSender()      { return $this->sender;   }
    public function getReceiver()    { return $this->receiver; }
    public function getOtherPerson() { return $this->otherPerson; }
    public function getSendDate()    { return $this->sendDate; }
    public function isRead()         { return $this->isRead;   }
    public function getContent()     { return $this->content;  }

    public function __construct($return = NULL) {
        if ($return) {
            $this->id       = $return['messageId'];
            $this->sender   = $return['senderId'];
            $this->receiver = $return['receiverId'];
            $this->sendDate = $return['sendDate'];
            $this->isRead   = $return['read'];
            $this->readDate = $return['readDate'];
            $this->content  = $return['content'];
        }
    }
    public function read() {
        
    }
    public function getReadDate() {
        return $this->dateTimeFormatter($this->readDate); 
    }
    public function getSideDate() {
        return $this->dateDayFormatter($this->sendDate);
    }
    public function dateTimeFormatter($date) {
        $formatted = date('F d', strtotime($date));
        if (strtotime($date) > strtotime('-1 day')) {
            $formatted = 'Yesterday at ' . date("H:i A",strtotime($date));
        } elseif (strtotime($date) > strtotime('-7 day')) {
            $formatted = date("L",strtotime($date)) . ' at ' . date("H:i A",strtotime($date));
        }
        return $formatted;
    }
    public function dateDayFormatter($date) {
        if (!$date) { return ''; }
        try {
            $db_date = new DateTime($date);
            $today = new DateTime();
            $interval = $db_date->diff($today);
            $tmp = $interval->format('%a');
            if ($tmp == '0') {
                return date('h:i A', strtotime($date));
            } elseif ($tmp == '1') {
                return $tmp . ' day';
            } else {
                return $tmp . ' days';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

class Messages {
    //holds all messages to currentuser
    private $messages = array();
    private $users;
    public function __construct() {
        $this->users = getUsers();
    }
    public function addMessage($message) {
        array_push($this->messages, $message);
    }
    public function getMessagesBy($sender) {
        $sender = $this->getId($sender);
        $receiver = $this->getId($sender);
        $final = array();
        foreach($this->messages as $message) {
            if ($this->getId($message->getSender()) == $sender) {
                array_push($final, $message);
            }
        }
        return $final;
    }
    function getUnreadMessages() {

    }
    private function getId($user) {
        if (is_numeric($user)) {
            return $user;
        } else {
            return $user->getId();
        }
    }
    public function getSideMessages() {
        $sideMessages = array();
        $unOrdered = array();
        foreach($this->messages as $message)  {
            $otherPerson = $message->getOtherPerson();
            if (isset($unOrdered[$otherPerson->getId()])) {
                if ($unOrdered[$otherPerson->getId()]->getSendDate() < $message->getSendDate()) {
                    $unOrdered[$otherPerson->getId()] = $message;
                }
            } else {
                $unOrdered[$otherPerson->getId()] = $message;
            }
        }
        foreach($this->users as $user) {
            if (!isset($unOrdered[$user->getId()])) {
                $tmp = new Message();
                $tmp->setOtherPerson($user);
                $unOrdered[$user->getId()] = $tmp;
            }
        }
        usort($unOrdered, array( $this, "dateComp" ) );
        return array_reverse($unOrdered);
    }
    private function dateComp($a, $b) {
        return strcmp($a->getSendDate(), $b->getSendDate());
    }
}


class MessageFactory {
    private $users = array();

    public function __construct() {
        $users = getUsers();
        foreach($users as $user) {
            $this->users[$user->getId()] = $user;
        }
    }
    public function newMessage($return) {
        global $currentUser;
        $message = new Message();
        $message->setId($return['messageId']);
        $message->setSender($this->users[$return['senderId']]);
        $message->setReceiver($this->users[$return['receiverId']]);
        $message->setSendDate($return['sendDate']);
        $message->setIsRead($return['read']);
        $message->setReadDate($return['readDate']);
        $message->setContent($return['content']);
        $message->setOtherPerson(
            ($return['senderId'] == $currentUser->getId()) 
                ? $this->users[$return['receiverId']]
                : $this->users[$return['senderId']]
        );
        return $message;
    }
}

function getMessages() {
    $messages = new Messages();
    $factory = new MessageFactory();
    global $currentUser;
    global $companyId;
    $parameters = array(':sender' => $currentUser->getId(), ':receiver' => $currentUser->getId());
    $returns = run(sql::$getMessages, $parameters);
    foreach($returns as $return) {
        $messages->addMessage($factory->newMessage($return));
    }
    return $messages;
}