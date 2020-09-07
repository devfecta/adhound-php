<?php

(@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));
define("DATABASE_TYPE",   "MySQL");        
define("TTY",             "");
define("OPTIONS",         "");
define("PORT",            "");
define("VERSION",            "v1.6");

$TestMode = true;
if ($TestMode)
{
	define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound/dev");
	define("HOSTNAME",        "");
	define("DATABASE",        "");
	define("LOGIN",           "");
	define("PASSWORD",        "");
}
else 
{
	define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound");
	define("HOSTNAME",        "");
	define("DATABASE",        "");
	define("LOGIN",           "");
	define("PASSWORD",        "");
}

define("TABLE_PREFIX",    "");
define("SQL_ERROR",    "Test Error");
//define("DIR_PATH",        "/home/hookadea/public_html/test/");
//define("HTTP_PATH",       "http://localhost/ikelm/");
define("ADMIN_EMAIL",     "kdkelm@itsadvertising.com");
define("PAGE_TITLE",     "AdHound&trade; - It's Advertising, LLC");
define("COPYRIGHT", 	'Copyright &copy; '.date ("Y").' <a href="http://www.itsadvertising.com" class="FooterLink" title="It\'s Advertising, LLC">It\'s Advertising, LLC</a> ~ All rights reserved. ~ <b>Phone</b>: (800) ITS-3883');
define("CONN", mysql_connect(HOSTNAME, LOGIN, PASSWORD));
$Global_Db = mysql_select_db(DATABASE, CONN) or die(mysql_error());

?>
