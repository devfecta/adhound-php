<?php
require "config.php";
require "classes.php";

$File = '../users/'.$_REQUEST['UserID'].'/reports/'.$_REQUEST['File'];

if (file_exists($File)) 
{
	echo 'test';
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($File));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($File));
    ob_clean();
    flush();
    readfile($File);
    exit;
}
/*
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-type: application/ms-excel");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=".$FileName.".xls ");
header("Content-Transfer-Encoding: binary ");
print $CSV;
exit();
*/
?>