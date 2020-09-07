<?php
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	if((round((memory_get_usage() / $megabyte), 0) * 2) >= (int) str_replace('M', '', ini_get('memory_limit'))) 
	{
		ini_set('memory_limit', (round((memory_get_usage() / $megabyte), 0) * 3).'M');
	}
	else 
	{
		ini_set('memory_limit', '128M');
	}

	/*
	$RegionInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['RegionInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$LocationInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AccountInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$PanelInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['PanelInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$AdvertiserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AdvertiserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$AdInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AdsInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	*/
	//$XML = new DOMDocument();
/*
	if((!isset($_SESSION['RegionInfo']) && empty($_SESSION['RegionInfo'])) && (isset($UserInfo) && !empty($UserInfo))) 
	{
		$Accounts = new _Accounts();
		$Accounts->GetRegions($UserInfo['UserParentID'], null);
	}

	if((!isset($_SESSION['AccountInfo']) && empty($_SESSION['AccountInfo'])) && (isset($UserInfo) && !empty($UserInfo))) 
	{
		$Accounts = new _Accounts();
		$Accounts->GetLocations($UserInfo['UserParentID'], null);
	}
*/
	/* Moved to Reports
	if((!isset($_SESSION['PanelInfo']) && empty($_SESSION['PanelInfo'])) && (isset($UserInfo) && !empty($UserInfo))) 
	{
		$Panels = new _Panels();
		$Panels->GetPanels($UserInfo['UserParentID'], null, null);
	}
	*/
/*
	if((!isset($_SESSION['AdvertiserInfo']) && empty($_SESSION['AdvertiserInfo'])) && (isset($UserInfo) && !empty($UserInfo))) 
	{
		$Advertisers = new _Advertisers();
		$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
	}

	if((!isset($_SESSION['AdsInfo']) && empty($_SESSION['AdsInfo'])) && (isset($UserInfo) && !empty($UserInfo))) 
	{
		$Advertisements = new _Advertisements();
		$Advertisements->GetAds($UserInfo['UserParentID']);
	}
*/
	

	//$RegionInfo = $_SESSION['RegionInfo'];
	//$LocationInfo = $_SESSION['AccountInfo'];
//$PanelInfo = $_SESSION['PanelInfo'];
	//$AdvertiserInfo = $_SESSION['AdvertiserInfo'];
	//$AdInfo = $_SESSION['AdsInfo'];
	
	//unset($_SESSION['RegionInfo']);
	//unset($_SESSION['AccountInfo']);
	//unset($_SESSION['AdvertiserInfo']);
	//unset($_SESSION['AdsInfo']);
	//$Ads = new _Advertisements();
	//print("<pre>". print_r($Ads->AdInfoArray,true) ."</pre>");
	//$AdInfo = $Ads->AdInfoArray;
function IsMobile() 
{
	$tablet_browser = 0;
	$mobile_browser = 0;
	 
	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $tablet_browser++;
	}
	 
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $mobile_browser++;
	}
	 
	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	    $mobile_browser++;
	}
	 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda ','xda-');
	 
	if (in_array($mobile_ua,$mobile_agents)) {
	    $mobile_browser++;
	}
	 
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
	    $mobile_browser++;
	    //Check for tablets on opera mini alternative headers
	    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
	    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
	      $tablet_browser++;
	    }
	}
	 
	if ($mobile_browser > 0 || $tablet_browser > 0) {
	   // do something for mobile devices
	   $Mobile = true;
	}
	else {
	   // do something for everything else
	   $Mobile = false;
	}
	return $Mobile;
}
	
function StyleSheet($IsMobile) 
{
	if (!isset($IsMobile) && empty($IsMobile)) 
	{ 
		$MobileDetect = new Mobile_Detect;
		$IsMobile = $MobileDetect->isMobile();
		$IsTablet = $MobileDetect->isTablet();
		$iOS = $MobileDetect->isiOS();
		$Android = $MobileDetect->isAndroidOS();
		
		$tablet_browser = 0;
		$mobile_browser = 0;
		 
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		    $tablet_browser++;
		}
		 
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		    $mobile_browser++;
		}
		 
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		    $mobile_browser++;
		}
		 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
		    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		    'newt','noki','palm','pana','pant','phil','play','port','prox',
		    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		    'wapr','webc','winw','winw','xda ','xda-');
		 
		if (in_array($mobile_ua,$mobile_agents)) {
		    $mobile_browser++;
		}
		 
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		    $mobile_browser++;
		    //Check for tablets on opera mini alternative headers
		    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
		      $tablet_browser++;
		    }
		}
		 
		if ($mobile_browser > 0 || $tablet_browser > 0) {
		   // do something for mobile devices
		   $IsMobile = true;
		   $IsMobile = $IsTablet;
			$CSS .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
			$CSS .= '<meta name="viewport" content="target-densitydpi=device-dpi">'."\n";
			$CSS .= '<meta name="viewport" content="width=320">'."\n";
			$CSS .= '<meta name="HandheldFriendly" content="true" />'."\n";
			$CSS .= '<meta name="MobileOptimized" content="320" />'."\n";
			$CSS .= '<link rel="stylesheet" type="text/css" href="css/mobilestylesheet.css?v='. rand(1, 10) .'" media="only screen and (max-width: 400px)" />'."\n";
		   print 'is mobile';
		}
		else {
		   // do something for everything else
		   $IsMobile = false;
		   $CSS .= '<link rel="stylesheet" type="text/css" href="css/desktopstylesheet.css?v='. rand(1, 10) .'" media="only screen and (min-width: 401px)" />'."\n";
		   print 'is desktop';
		}
	}
	else 
	{ }

	$CSS = "\n".'<link rel="stylesheet" type="text/css" href="css/stylesheet.css?v=<?php echo rand(1, 10);?>" />'."\n";
	
	/*
	if($IsMobile || $IsTablet || $iOS || $Android) 
	{
		//$CSS .= 'Mobile';
		$IsMobile = $IsTablet;
		$CSS .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
		$CSS .= '<meta name="viewport" content="target-densitydpi=device-dpi">'."\n";
		$CSS .= '<meta name="viewport" content="width=320">'."\n";
		$CSS .= '<meta name="HandheldFriendly" content="true" />'."\n";
		$CSS .= '<meta name="MobileOptimized" content="320" />'."\n";
		$CSS .= '<link rel="stylesheet" type="text/css" href="css/mobilestylesheet.css?v='. rand(1, 10) .'" media="only screen and (max-width: 400px)" />'."\n";
	}
	else 
	{
		//$CSS .= 'Desktop';
		$CSS .= '<link rel="stylesheet" type="text/css" href="css/desktopstylesheet.css?v='. rand(1, 10) .'" media="only screen and (min-width: 401px)" />'."\n";
	}
	*/
	$CSS .= "\n".'<link rel="stylesheet" type="text/css" href="css/print.css?v='. rand(1, 10) .'" />'."\n";
	$_SESSION['CSS'] = $IsMobile;
	return $CSS;
}



// Error Tracing

function ListFields($Fields)
{
	foreach ($Fields as $name => $value)
	{
		$FieldValues .= $name.'='.$value.'<br />';
	}
	return $FieldValues;
}

// Page Tracking

function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}
$_SESSION['CurrentURL'] = trim(full_url());

if($_SESSION['Back1URL'] != $_SESSION['CurrentURL']) 
{
	if($_SESSION['Back2URL'] != $_SESSION['Back1URL'] && $_SESSION['Back1URL'] != $_SESSION['CurrentURL']) 
	{
		if($_SESSION['Back3URL'] != $_SESSION['Back2URL'] && $_SESSION['Back2URL'] != $_SESSION['Back1URL']) 
		{
			$_SESSION['Back3URL'] = $_SESSION['Back2URL'];
		}
		$_SESSION['Back2URL'] = $_SESSION['Back1URL'];
	}
	$_SESSION['Back1URL'] = $_SESSION['CurrentURL'];
}

// echo $_SESSION['CurrentURL'].'<br />'.$_SESSION['Back1URL'].'<br />'.$_SESSION['Back2URL'].'<br />'.$_SESSION['Back3URL'];

// Send e-Mails
function SendEmail($To, $Subject, $Message)
{
	$Headers  = 'MIME-Version: 1.0' . "\n";
	$Headers .= 'Content-type: text/html; charset=utf-8' . "\n";
	$Headers .= 'To: '.$To."\n";
	$Headers .= 'From: '.ADMIN_EMAIL."\n";
	$Headers .= 'Reply-To: '.ADMIN_EMAIL.">\n";
	//$Headers .= 'Cc: '.ADMIN_EMAIL.'\n';
	//$Headers .= 'Bcc: '.ADMIN_EMAIL."\n";
	$Headers .= 'X-Mailer: PHP/' . phpversion();
	$eMail = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body style="text-align:left">';
	$eMail .= '<img src="https://itsadvertising.c6.ixsecure.com/adhound/images/AdHound_Logo.gif" />';
	$eMail .= $Message.'<p>'.COPYRIGHT.'</p>';
	$eMail .= '</body></html>';
	
	return mail($To, $Subject, $eMail, $Headers);
}

function SendAdminEmail($From, $Subject, $Message)
{
	$Headers  = 'MIME-Version: 1.0' . "\n";
	$Headers .= 'Content-type: text/html; charset=utf-8' . "\n";
	$Headers .= 'To: '.ADMIN_EMAIL."\n";
	$Headers .= 'From: '.$From."\n";
	$Headers .= 'Reply-To: '.$From.">\n";
	$Headers .= 'X-Mailer: PHP/' . phpversion();
	$eMail = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body style="text-align:left">';
	$eMail .= '<img src="https://itsadvertising.c6.ixsecure.com/adhound/images/AdHound_Logo.gif" />';
	$eMail .= $Message.'<p>'.COPYRIGHT.'</p>';
	$eMail .= '</body></html>';
	
	return mail(ADMIN_EMAIL, $Subject, $eMail, $Headers);
}

function CreateRandomPassword()
{
	$Characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	for ($p = 0; $p < 7; $p++) 
	{
		$RandomPassword .= $Characters[mt_rand(0, strlen($Characters))];
	}
	return $RandomPassword;
}

function Day_Dropdown($Day)
{
	//$Date .= "\n".'<option value="'.$Day.'">'.$Day.'</option>'."\n";
	for ($Days = 1; $Days <= 31; $Days++) 
	{
		if($Days == $Day) 
		{
			$Date .= '<option value="'.$Days.'" selected>'.$Days.'</option>'."\n";
		}
		else 
		{
			$Date .= '<option value="'.$Days.'">'.$Days.'</option>'."\n";
		}
	}
	return $Date;
}

function Month_Dropdown($Month)
{
	$MonthNames = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	//$Date .= "\n".'<option value="'.$Month.'">'.$MonthNames[$Month].'</option>'."\n";
	for ($Months = 1; $Months <= 12; $Months++) 
	{
		if($Months == $Month) 
		{
			$Date .= '<option value="'.$Months.'" selected>'.$MonthNames[$Months].'</option>'."\n";
		}
		else 
		{
			$Date .= '<option value="'.$Months.'">'.$MonthNames[$Months].'</option>'."\n";
		}
	}
	return $Date;
}

function Year_Dropdown($Year)
{
	$PreviousYears = $Year - 3;
	for ($Years = $PreviousYears;  $Years < date("Y"); $Years++) 
	{
		if($Years == $Year) 
		{
			$Date .= '<option value="'.$Years.'" selected>'.$Years.'</option>'."\n";
		}
		else 
		{
			$Date .= '<option value="'.$Years.'">'.$Years.'</option>'."\n";
		}
	}
	for ($Years = date("Y"); $Years <= (date("Y") + 5); $Years++) 
	{
		if($Years == $Year) 
		{
			$Date .= '<option value="'.$Years.'" selected>'.$Years.'</option>'."\n";
		}
		else 
		{
			$Date .= '<option value="'.$Years.'">'.$Years.'</option>'."\n";
		}
	}	
	return $Date;
}
/*
// Arrays Start
if(!isset($_SESSION['Locations']) && isset($_SESSION['UserParentID'])) 
{
	$Accounts = new _Accounts();
	$_SESSION['Locations'] = $Accounts->GetLocations($_SESSION['UserParentID'], null);
}

if(!isset($_SESSION['Regions']) && isset($_SESSION['UserParentID'])) 
{
	$Accounts = new _Accounts();
	$_SESSION['Regions'] = $Accounts->GetRegions($_SESSION['UserParentID'], null);
}
*/
// Arrays End

function CalculateNumberOfIncrements($Increment, $StartDate, $EndDate) 
{
	$StartDate = strtotime($StartDate);
	$EndDate = strtotime($EndDate);
	$StartYear = date('Y', $StartDate);
	$EndYear = date('Y', $EndDate);
	$StartMonth = date('n', $StartDate);
	$EndMonth = date('n', $EndDate);
	$StartWeek = date('W', $StartDate);
	$EndWeek = date('W', $EndDate);
	$StartDay = date('j', $StartDate);
	$EndDay = date('j', $EndDate);
	
	switch($Increment) 
	{
		case 52: // Weekly
		case 26: // Biweekly
			$IncrementCount = (($EndYear - $StartYear) * $Increment) + (($EndWeek - $StartWeek) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0));
			break;
		case 12: // Monthly
			$IncrementCount = (($EndYear - $StartYear) * $Increment) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0));
			break;
		case 6: // Biannualy
		case 3: // Quarterly
			$IncrementCount = ((($EndYear - $StartYear) * 12) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0))) / $Increment;
			break;
		case 1: // Annually
			$IncrementCount = ceil(((($EndYear - $StartYear) * 12) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0))) / 12);
			break;
		default:
			$IncrementCount = 1;
			break;
	}

	return $IncrementCount;
}

function CalculateDateIncrement($IncrementCount, $StartDate, $EndDate) 
{
	return (strtotime($EndDate) - strtotime($StartDate)) / $IncrementCount;
}

function ValidateIncrementDate($IncrementDate, $StartDate, $EndDate) 
{
	$IncrementDate = strtotime($IncrementDate);
	$StartDate = strtotime($StartDate);
	$EndDate = strtotime($EndDate);
	
	$DateValid = false;
	if($IncrementDate >= $StartDate && $IncrementDate <= $EndDate) 
	{
		$DateValid = true;
	}
	
	return $DateValid;
}

function ValidateDate($StartDate, $EndDate, $Date) 
{
	$Date = strtotime($Date);
	$StartDate = strtotime($StartDate);
	$EndDate = strtotime($EndDate);
	
	$DateValid = false;
	if($Date >= $StartDate && $Date <= $EndDate) 
	{
		$DateValid = true;
	}
	
	return $DateValid;
}

function ValidateDateRange($StartDate, $EndDate, $AdStartDate, $AdEndDate) 
{
	$DateRangeValid = false;
	for($d=strtotime($AdStartDate); $d<=strtotime($AdEndDate); $d++) 
	{
		if($d >= strtotime($StartDate) && $d <= strtotime($EndDate)) 
		{
			$DateRangeValid = true;
			break;
		}
	/*
		if((date('Y', strtotime($AdEndDate)) - date('Y', $d)) > 0) 
		{
			$d = strtotime('+1 year', $d);
			echo 'Y:'. date('Y-m-d', strtotime('+1 year', $d)).'<br />';
		}
		else
		{}
		if((date('m', strtotime($AdEndDate)) - date('m', $d)) > 0 && (date('Y', strtotime($AdEndDate)) - date('Y', $d)) == 0) 
		{
			$d = strtotime('+1 month', $d);
			echo 'M:'. date('Y-m-d', strtotime('+1 month', $d)).'<br />';
		}
		else 
		{
			
		}
		if((date('d', strtotime($AdEndDate)) - date('d', $d)) > 0 && (date('m', strtotime($AdEndDate)) - date('m', $d)) == 0 && (date('Y', strtotime($AdEndDate)) - date('Y', $d)) == 0)
		{
			$d = strtotime('+1 day', $d);
			echo 'D:'. date('Y-m-d', strtotime('+1 day', $d)).'<br />';
			//$d = $d + 86400;
		}
		*/
		
		if((date('Y', strtotime($AdEndDate)) - date('Y', $d)) > 0 && strtotime($StartDate) < $d) 
		{
			$d = strtotime('+1 year', $d);
			//echo 'Y:'. date('Y-m-d', strtotime('+1 year', $d)).'<br />';
		}
		elseif((date('m', strtotime($AdEndDate)) - date('m', $d)) > 0 && strtotime($StartDate) < $d) 
		{
			$d = strtotime('+1 month', $d);
			//echo 'M:'. date('Y-m-d', strtotime('+1 month', $d)).'<br />';
		}
		else 
		{
			$d = strtotime('+1 day', $d);
			//echo 'D:'. date('Y-m-d', strtotime('+1 day', $d)).'<br />';
			//$d = $d + 86400;
		}
		
		//$d = $d + 86400;
	}

	return $DateRangeValid;
}

function CalculateIncrementPayment() 
{
	
}

class _Global extends _Validation
{
	function ScaleBy($Width, $Height) 
	{
		if($Width >= 17) 
		{
			
		}
		
		if($Height > 792) // 792 = 11"
		{
			$ScalePercentage = .08;
			
		}
		else 
		{
			$ScalePercentage = .2;
		}
		
		
		
		$this->PixelWidth = number_format(($Width * $ScalePercentage), 0);
		$this->PixelHeight = number_format(($Height * $ScalePercentage), 0);
		
		return $ScalePercentage;
	}

	
/*
	public function Validate($Fields)
	{
		foreach ($Fields as $name => $value)
		{
			//$Confirmation .= $name.'='.$value.'<br />';

			if (strpos($name, 'Required'))
			{
				if (empty($value))
				{
					//$_SESSION['ErrorPlace'] = "Error1";
					//$_SESSION['ErrorPlace'] .= $name.'='.$value.'<br />';
					$Confirmation = false;
					$_SESSION['RequiredFields'] = ' class="required" ';
					break;
				}
				else
				{
					$Confirmation = true;
				}
			}
		}
		//$_SESSION['Error'] = 'Validated:'.settype($Confirmation, "string");
		
		return $Confirmation;
	}
*/
}

// Field Validation
class _Validation
{
	public function CleanInput($Input)
	{
	  $Input = trim($Input);
	  $Input = stripslashes($Input);
	  $Input = htmlspecialchars($Input);
	  return $Input;
	}
	
	public function Validate($Fields)
	{
		foreach ($Fields as $name => $value)
		{
			//$Confirmation .= $name.'='.$value.'<br />';
			if(strpos($name, 'Required'))
			{
				if(empty($value))
				{
					//$_SESSION['ErrorPlace'] = "Error1";
					//$_SESSION['ErrorPlace'] .= $name.'='.$value.'<br />';
					$Confirmation = false;
					$_SESSION['RequiredFields'] = ' class="required" ';
					break;
				}
				else
				{ $Confirmation = true; }
			}
		}
		//$_SESSION['Error'] = 'Validated:'.settype($Confirmation, "string");
		return $Confirmation;
	}
}
/*
require "class.users.php";
require "class.locations.php";
require "class.panels.php";
require "class.advertisers.php";
require "class.ads.php";
require "class.reports.php";
include "Mobile-Detect-2.7.6/Mobile_Detect.php";

$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
*/

?>
