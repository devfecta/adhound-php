<?php
// Custom Config File
(@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));
define("DATABASE_TYPE", "MySQL");        
define("TTY", "");
define("OPTIONS", "");
define("PORT", "");
//define("VERSION", "v2.0");
  
define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound/dev");
$SELF = pathinfo($_SERVER['PHP_SELF']);
if(isset($_SERVER['HTTPS']))
{
	if ($_SERVER["HTTPS"] == "on") 
	{ define("DIRECTORY", 'https://'.$_SERVER["HTTP_HOST"].$SELF['dirname']); }
}
else 
{
	define("DIRECTORY", 'http://'.$_SERVER["HTTP_HOST"].$SELF['dirname']);
}

define("HOSTNAME", "mysql301.ixwebhosting.com");
define("DATABASE", "petemcw_adhoundDEV");
define("LOGIN", "petemcw_its");
define("PASSWORD", "Holstein12");
//define('STRIPE_PRIVATE_KEY', 'sk_test_FLJLxsxIGrjemyvHrevvecTT');
//define('STRIPE_PUBLIC_KEY', 'pk_test_ucfn4FoNlG5G0R4BQd3ZpCCf');
//define('STRIPE_PRIVATE_KEY', 'sk_live_aneWMFpOP1PG0mAMxiAHZLjd');
//define('STRIPE_PUBLIC_KEY', 'pk_live_pC33VL9jiL3gpUnN8TBspU72');
define("CONN", mysql_connect(HOSTNAME, LOGIN, PASSWORD));
$db = mysql_select_db(DATABASE, CONN) or die(mysql_error());

define("TABLE_PREFIX", "");
define("SQL_ERROR", "Test Error");
//define("DIR_PATH", "/home/hookadea/public_html/test/");
//define("HTTP_PATH", "http://localhost/ikelm/");
define("ADMIN_EMAIL", "kdkelm@itsadvertising.com");
define("PAGE_TITLE", "AdHound&trade; - It's Advertising, LLC");
define("COPYRIGHT", 'Copyright &copy; '.date ("Y").' <a href="http://www.itsadvertising.com" class="FooterLink" title="It\'s Advertising, LLC">It\'s Advertising, LLC</a> ~ All rights reserved. ~ <b>Phone</b>: (800) ITS-3883');

require ROOT."/configuration/classes.php";

?>
