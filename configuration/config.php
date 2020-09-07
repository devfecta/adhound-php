<?php
        /**********************************************************************************
        * File              : config.php
        * File Started      : Sat, Jan 27, 2007
        * File Modified     : Sat, Feb 03, 2007
        * CVS Tag           : $Id: $
        * CVS Code Key      : filename, revision, date, author
        **********************************************************************************/

        /**
        * Database & System Config
        *
        * DATABASE_TYPE:   Your Database Server type. Supported servers are:
        *                  MySQL, mSQL, MsSQL, PostgreSQL, and Sybase
        *                  Be sure to write it exactly as above, case SeNsItIvE!
        * HOSTNAME:        SQL Database Hostname
        * TTY:             PostgreSQL option (leave blank if none)
        * OPTIONS:         PostgreSQL option (leave blank if none)
        * PORT:            PostgreSQL option (leave blank if none)
        * DATABASE:        SQL Database Name
        * LOGIN:           SQL Username
        * PASSWORD:        SQL Password
        * TABLE_PREFIX:    Your Database tables prefix
        *                  If you dumped your tables with the prefix of "had_"
        *                  then you would put in "had_" in the quotes
        *
        * DIR_PATH:        The direct system path to the installation (i.e. /var/www/firefighter_emt/)
        * HTTP_PATH:       The http path to the installation (i.e. http://127.0.1.1/firefighter_emt/)
        *                  ***Both of these paths need the forward slash (/) at the end!***
        * ADMIN_EMAIL      The administrators email, This is where the database crashes/info will
        *                  be send when someone hits a database error page
        */

        //session_start();
        (@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));
        define("DATABASE_TYPE",   "MySQL");        
        define("TTY",             "");
        define("OPTIONS",         "");
        define("PORT",            "");
        define("VERSION",            "v1.5");
        
        $RunLocal = false;
        $TestMode = false;
        if ($RunLocal)
        {
        	define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound/v2");
        	define("HOSTNAME",        "");
        	define("DATABASE",        "");
        	define("LOGIN",           "");
        	define("PASSWORD",        "");
        	define('STRIPE_PRIVATE_KEY', '');
			define('STRIPE_PUBLIC_KEY', '');
        }
        else
        {
        		if ($TestMode)
        		{
        			define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound/v2");
		        	define("HOSTNAME",        "");
		        	define("DATABASE",        "");
		        	define("LOGIN",           "");
		        	define("PASSWORD",        "");
		        	define('STRIPE_PRIVATE_KEY', '');
					define('STRIPE_PUBLIC_KEY', '');
        		}
        		else 
        		{
        			define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/adhound");
		        	define("HOSTNAME",        "");
		        	define("DATABASE",        "");
		        	define("LOGIN",           "");
		        	define("PASSWORD",        "");
        		}
        }
        
        define("TABLE_PREFIX",    "");
        define("SQL_ERROR",    "Test Error");
        

        //define("DIR_PATH",        "/home/hookadea/public_html/test/");
        //define("HTTP_PATH",       "http://localhost/ikelm/");
        define("ADMIN_EMAIL",     "kdkelm@itsadvertising.com");
        define("PAGE_TITLE",     "AdHound&trade; - It's Advertising, LLC");
        define("COPYRIGHT", 	'Copyright &copy; '.date ("Y").' <a href="http://www.itsadvertising.com" class="FooterLink" title="It\'s Advertising, LLC">It\'s Advertising, LLC</a> ~ All rights reserved. ~ <b>Phone</b>: (800) ITS-3883');
       //$_SESSION['username'] = ;

       	define("CONN", mysql_connect(HOSTNAME, LOGIN, PASSWORD));
       
       // Constant Connection TIMED OUT
       //define("CONN", mysql_pconnect(HOSTNAME, LOGIN, PASSWORD));
       // Temp Connection may need to use mysql_close(CONN)
       
       if (isset($_SESSION['username']))
       {
       session_start();
           $username = $_SESSION['username'];
       }
       else
       {
           $username = null;
       }
       define("USER_TYPE",     $username);

       $db = mysql_select_db(DATABASE, CONN) or die(mysql_error());
?>
