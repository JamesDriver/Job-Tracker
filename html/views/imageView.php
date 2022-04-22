<style>
body {
    width:100%;
    height:100%;
    background-color: #000;
}
img {
    max-width: 95%;
    max-height: 95% !important;
    position: absolute;
    margin: auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}
a {
  text-decoration: none;
  display: inline-block;
  padding: 8px 16px;
}
a:hover {
  background-color: #ddd;
  color: black;
}
.previous {
  background-color: #f1f1f1;
  color: black;
}
.next {
    background-color: #f1f1f1;
    color: black;
}
.round {
  border-radius: 50%;
}
.containerRight {
    height: 10%;
	
	position:absolute;
    right: 0;
    top:0; bottom:0;
	margin:auto;
	
	max-width:100%;
	max-height:100%;
	overflow:auto;
}
.containerLeft {
    height: 10%;
	
	position:absolute;
    left: 0;
    top:0; bottom:0;
	margin:auto;
	
	max-width:100%;
	max-height:100%;
	overflow:auto;
}
</style>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<?php

$var = 'daily';
$dailyVars = getAnyDailyById($daily->getId());
if ($dailyVars['isPublic'] == 1) {
    global $companyId;
    if (!$companyId == $dailyVars['company']) {
        $var = 'publicDaily';
    }
    $companyId = $dailyVars['company'];
} elseif ($dailyVars['company'] != $companyId) {
    echo '<p style="color:white">You do not have permission to view this resource</p>';
    die;
}

//$im    = file_get_contents($file->getUrl());
$next     = NULL;
$previous = NULL;

$files = $daily->getFiles();
for ($i = 0;$i<count($files);$i++) {
    if ($files[$i]->getId() == $fileId) {
        if (isset($files[$i+1])) {
            $next = $files[$i+1];
        }
        if (isset($files[$i-1])) {
            $previous = $files[$i-1];
        }
        $file = $files[$i];
        break;
    }
}
$im = file_get_contents($file->getUrl()); 
echo "<img src='data:image/jpg;base64,".base64_encode($im)."'>";

echo "<a href='/{$var}/view/{$daily->getId()}' class='previous round'>&#8249;</a>";

if ($previous) {
    echo "
    <div class='containerLeft'>
        <a href='/image/{$previous->getId()}/daily/{$daily->getId()}' class='previous round'>&#8249;</a>
    </div>";
}
if ($next) {
    echo "
    <div class='containerRight'>
        <a href='/image/{$next->getId()}/daily/{$daily->getId()}' class='next round'>&#8250;</a>
    </div>";
}

?>