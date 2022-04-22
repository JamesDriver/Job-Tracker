<?php
global $companyId;




if ($companyId == 20) {
  foreach($userData as $user) {
    createNewUser($user);
  }
  echo 'true';
  
} else {
  echo 'false';
}




