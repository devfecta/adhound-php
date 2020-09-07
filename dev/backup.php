<?php
//require "configuration/config.php";
require "configuration/class.global.php";

/*
ROOT
/hsphere/local/home/petemcw/itsadvertising.com/adhound/v2
CRON Command Field
/hsphere/shared/php5/bin/php /hsphere/local/home/petemcw/itsadvertising.com/adhound/v2/backup.php
*/
define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound");
define("HOSTNAME",        "mysql301.ixwebhosting.com");
define("DATABASE",        "petemcw_adhound");
define("LOGIN",           "petemcw_its");
define("PASSWORD",        "Holstein12");
define("ADMIN_EMAIL",     "kdkelm@itsadvertising.com");
define("PAGE_TITLE",     "AdHound&trade; - It's Advertising, LLC");
define("COPYRIGHT", 	'Copyright &copy; '. date ("Y") .' <a href="http://www.itsadvertising.com" class="FooterLink" title="It\'s Advertising, LLC">It\'s Advertising, LLC</a> ~ All rights reserved. ~ <b>Phone</b>: (800) ITS-3883');
define("CONN", mysql_connect(HOSTNAME, LOGIN, PASSWORD));
$Db = mysql_select_db(DATABASE, CONN) or die(mysql_error());

$BackupFile = DATABASE.'_backup_'. date('ymd_h_i_s').'.sql';
if (!file_exists(ROOT.'/backups')) 
{
	mkdir(ROOT.'/backups', 0777, true);
}
else 
{ }

//$ExecuteBackup=exec('mysqldump '.DATABASE.' --password='.PASSWORD.' --user='.LOGIN.' --single-transaction >'.ROOT.'/backups/'.$BackupFile,$Output);
$ExecuteBackup=exec('mysqldump -h '.HOSTNAME.' -u '.LOGIN.' -p'.PASSWORD.' '.DATABASE.' >/hsphere/local/home/petemcw/itsadvertising.com/adhound/backups/'.$BackupFile);


if($Output=='')
{/* no output is good */
	$Subject = 'AdHound(TM) Backup Completed';
	$Message = '<p>AdHound(TM) backup was completed on: '. date('l jS \of F Y h:i:s A') .'</p>';
	//$Message .= '<p>'.$ExecuteBackup.'</p>';
	//$Message .= '<p>'.$BackupFile.'</p>';
	$Confirmation = SendEmail(ADMIN_EMAIL, $Subject, $Message);
}
else 
{/* we have something to log the output here*/
	$Subject = 'AdHound(TM) Backup Incompleted';
	$Message = '<p>AdHound(TM) backup didn\'t completed on: '. date('l jS \of F Y h:i:s A') .'</p>';
	//$Message .= '<p>'."<pre>". print_r($Output,true) ."</pre>".'</p>';
	//print("<pre>". print_r($Output,true) ."</pre>");
	$Confirmation = SendEmail(ADMIN_EMAIL, $Subject, $Message);
}
?>