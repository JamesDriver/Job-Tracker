<?php 
require_once '/var/www/globals.php';

if ($id) {
    $file = getFileById($id);
    $filename = $file->getName(true);
    $path = $file->getUrl();

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    while (@ob_end_clean());
    readfile($path);
    exit;
}

?>