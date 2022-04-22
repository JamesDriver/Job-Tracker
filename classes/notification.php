<?php
//add heading
class Notification {
    private $id;
    private $user;
    private $header;
    private $text;
    private $read;
    private $link;
    private $conn;
    private function setId($var    ) { $this->id     = $var; }
    private function setUser($var  ) { $this->user   = $var; }
    private function setHeader($var) { $this->header = $var; }
    public  function setText($var  ) { $this->text   = $var; }
    private function setRead($var  ) { $this->read   = $var; }
    private function setLink($var  ) { $this->link   = $var; }

    public function getId(  )              { return $this->id;   }
    public function getUser()              { return $this->user; }
    public function isRead( )              { return $this->read; }
    public function getText($local = NULL) { return (!$local) ? noHTML($this->text) : $this->text; }
    public function getLink()              { return $this->link; }

    public function read(   ) { 
        if (run(sql::$markAsRead, array(':id'=>$this->getId()))) {
          return true;
        } return false;
    }
    public function __CONSTRUCT($data = NULL) {
        global $databaseConnection;
        $this->conn = $databaseConnection->conn;
        if ($data) {
            $this->setId(    $data['id']    );
            $this->setUser(  $data['user']  );
            $this->setHeader($data['header']);
            $this->setText(  $data['text']  );
            $this->setRead(( $data['isRead'] == 0) ? false : true);
            $this->setLink(  $data['link']  );
        }
    }
    public function display() {
        echo ($this->read) ? '<li>' : "<li class='unread'>";
        echo "
        <div class='row'>
          <div class='col-9'>
            <h3>{$this->header}</h3>
            <a href='/test'>
              <p>{$this->text}</p>
            </a>
          </div>
          <div class='col-3 readme' id='notification{$this->id}'onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>";
    }
}

function getNotificationById($id) {
  global $currentUser;
  $parameters = array(':id' => $id);
  $stmt = run(sql::$getNotificationById, $parameters);
  $notifications = createNotifications($stmt->fetchAll(PDO::FETCH_ASSOC));
  return array_pop($notifications);
}
function getNotifications() {
    global $currentUser;
    $parameters = array(':user' => $currentUser->getId());
    $stmt = run(sql::$getNotifications, $parameters);
    $notifications = createNotifications($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $notifications;
}
function getUnreadNotifications() {
    global $currentUser;
    $parameters = array(':user' => $currentUser->getId());
    $stmt = run(sql::$getUnreadNotifications, $parameters);
    $notifications = createNotifications($stmt->fetchAll(PDO::FETCH_ASSOC));
    return $notifications;
}
function createNotifications($data) {
    $notifications = array();
    foreach($data as $notify) {
        array_push($notifications, new Notification($notify));
    }
    return $notifications;
}
function displayNotifications() {
  $notifications = getNotifications();
  echo "<ul class='notification-block' style='display:block;position:static;max-height:100%;'>";

  foreach($notifications as $notification) {
      $notification->display();
  }
  echo "    
  </ul>";
}
function displayNavNotifications() {
    $notifications = getUnreadNotifications();
    $notify = '';
    if (count($notifications) > 0) {
      $notify = ' notify';
    }
    echo "
    <div class='notification{$notify}' >
        <img class onclick='notificate()' src='https://s3.amazonaws.com/codecademy-content/projects/2/feedster/bell.svg'>
        <ul class='notification-menu'>";
    $i = 0;
    $max = 6;
    foreach($notifications as $notification) {
        $i++;
        if ($i > $max) {
            break;
        }
        $notification->display();
    }
    $seeAll = "";
    if (count($notifications) > 6) {
      $seeAll = "(" . (count($notifications)-6) . " More)";
    }
    if (count($notifications) == 0) {
      echo "<p style='text-align:center;padding:10px;'>You're all caught up!</p>";
    }
    echo "    
    <a href='/notifications'>
        <li style='text-align:center'>
        <h3>See All {$seeAll}</h3>
        </li>
    </a>
    </li>
    </ul>
    </div>";




    $test = "
    <div class='notification notify' >
    <img class onclick='notificate()' src='https://s3.amazonaws.com/codecademy-content/projects/2/feedster/bell.svg'>
    <ul class='notification-menu'>
      <li class='unread'>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
      <li class='unread'>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
      <li>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
      </li>
      <li>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
      </li>
      <li>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
      </li>
      <li>
        <div class='row'>
          <div class='col-9'>
            <h3>Faheem Najm</h3>
            <p>All I do is winnnnnnaa aaa f aafaweifa aewfajwi ether asdfe</p>
          </div>
          <div class='col-3 readme' onclick='markasread(this)'>
            Mark as Read
          </div>
        </div>
      </li>
        <a href='/notifications'>
          <li style='text-align:center'>
            <h3>See All</h3>
          </li>
        </a>
      </li>
    </ul>
  </div>";
}