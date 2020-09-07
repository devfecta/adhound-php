<?php
ob_start();
session_start();
require "class.global.php";
require "class.users.php";
require "class.data.php";
require "class.locations.php";
require "class.panels.php";
require "class.advertisers.php";
require "class.ads.php";
require "class.reports.php";
//include "Mobile-Detect-2.7.6/Mobile_Detect.php";

$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
//$_SESSION['User'] = $UserInfo['UserParentID'];

?>
