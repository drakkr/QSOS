<?php
require('conf.php');

if(isset($_GET['file'])){
    $file = $_GET['file'];
    
    /*
     *  Check if the name match with a file
     */
    if(is_file($incoming->getRepoPath() . $file)){
        $full_path = $incoming->getRepoPath() . $file;
    }elseif(is_file($master->getRepoPath() . $file)){
        $full_path = $master->getRepoPath() . $file;
    }else{
        exit();
    }
    
    $file_name = basename($full_path);
     
    ini_set('zlib.output_compression', 0);
    $date = gmdate(DATE_RFC1123);
     
    header('Pragma: public');
    header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
     
    header('Content-Tranfer-Encoding: none');
    header('Content-Length: '.filesize($full_path));
    header('Content-MD5: '.base64_encode(md5_file($full_path)));
    header('Content-Type: application/octetstream; name="'.$file_name.'"');
    header('Content-Disposition: attachment; filename="'.$file_name.'"');
     
    header('Date: '.$date);
    header('Expires: '.gmdate(DATE_RFC1123, time()+1));
    header('Last-Modified: '.gmdate(DATE_RFC1123, filemtime($full_path)));
     
    readfile($full_path);
    exit;
}
?>