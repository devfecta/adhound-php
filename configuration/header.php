<?php
	ob_start();
	session_start();
	include "config.php";
	include "configuration/classes.php";
	
	/*
	$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$RegionInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['RegionInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$LocationInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AccountInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$AdvertiserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AdvertiserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	$AdInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['AdsInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	*/
	// Intial check to view page.
	if (!isset($UserInfo['IA_Users_ID'])) 
	{ header ("Location: https://itsadvertising.c6.ixsecure.com/adhound/login.php"); }

	if($UserInfo['IA_Users_Type'] == 2) 
	{ header ("Location: admin.php"); }
	// User Account
// User Account - Edit
	if (isset($_POST['EditUserButton'])) {
		$_SESSION['ModeType'] = "EditUserAccount";
		header ("Location: index.php");
	}
	else 
	{ }
	
	if (isset($UserInfo['IA_Users_ID'])) 
	{
		$Username = null;
		$UserTier = null;
		$BusinessName = null;
		$FirstName = null;
		$LastName = null;
		$Address = null;
		$City = null;
		$StateID = null;
		$Zipcode = null;
		$Phone = null;
		$Fax = null;
		$Email = null;
		
		
		$Username = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Email'] : $UserInfo['IA_Users_Username'];
		$UserTier = isset($UserInfo['IA_Advertisers_ID']) ? 'null' : $UserInfo['IA_Users_Tier'];
		$BusinessName = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_BusinessName'] : $UserInfo['IA_Users_BusinessName'];
		$FirstName = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_FirstName'] : $UserInfo['IA_Users_FirstName'];
		$LastName = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_LastName'] : $UserInfo['IA_Users_LastName'];
		$Address = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Address'] : $UserInfo['IA_Users_Address'];
		$City = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_City'] : $UserInfo['IA_Users_City'];
		$StateID = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_StateID'] : $UserInfo['IA_Users_StateID'];
		$Zipcode = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Zipcode'] : $UserInfo['IA_Users_Zipcode'];
		$Phone = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Phone'] : $UserInfo['IA_Users_Phone'];
		$Fax = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Fax'] : $UserInfo['IA_Users_Fax'];
		$Email = isset($UserInfo['IA_Advertisers_ID']) ? $UserInfo['IA_Advertisers_Email'] : $UserInfo['IA_Users_Email'];
		
		switch ($UserInfo['IA_Users_Type']) 
		{
			case 4:
				break;
			default:
				if((!$UserInfo['ValidCard'] && basename($_SERVER["PHP_SELF"]) != 'index.php') || (!$UserInfo['ValidCard'] && $_SESSION['ModeType'] != "EditUserAccount")) 
				{
					if($UserInfo['IA_Users_Tier'] > 0) 
					{
						$_SESSION['ModeType'] = "EditUserAccount";
						header ("Location: index.php");
					}
				}
				break;
		}
		/*
		switch ($UserInfo['IA_Users_Type']) 
		{
			case 4:
				$Advertisers = new _Advertisers();
				if ($Advertisers->GetInfo($UserInfo['IA_Users_ID']))
				{
					$Username = $Advertisers->AdvertiserEmail;
					$BusinessName = $Advertisers->AdvertiserBusinessName;
					$FirstName = $Advertisers->AdvertiserFirstName;
					$LastName = $Advertisers->AdvertiserLastName;
					$Address = $Advertisers->AdvertiserAddress;
					$City = $Advertisers->AdvertiserCity;
					$StateID = $Advertisers->AdvertiserStateID;
					$Zipcode = $Advertisers->AdvertiserZipcode;
					$Phone = $Advertisers->AdvertiserPhone;
					$Fax = $Advertisers->AdvertiserFax;
					$Email = $Advertisers->AdvertiserEmail;
				}
				else
				{
					echo 'Couldn\'t get user information.';
				}
				break;
			default:
				$Users = new _Users();
				if ($Users->GetUserInfo($_SESSION['UserID']))
				{
					$Username = $Users->Username;
					$UserTier = $Users->UserTier;
					$BusinessName = $Users->BusinessName;
					$FirstName = $Users->FirstName;
					$LastName = $Users->LastName;
					$Address = $Users->Address;
					$City = $Users->City;
					$StateID = $Users->State;
					$Zipcode = $Users->Zipcode;
					$Phone = $Users->Phone;
					$Fax = $Users->Fax;
					$Email = $Users->Email;
					
					if((!$Users->ValidCard && basename($_SERVER["PHP_SELF"]) != 'index.php') || (!$Users->ValidCard && $_SESSION['ModeType'] != "EditUserAccount")) 
					{
						$_SESSION['ModeType'] = "EditUserAccount";
						header ("Location: index.php");
					}
				}
				else
				{
					echo 'Couldn\'t get user information.';
				}
				break;
		}
		*/
	}
	else 
	{
		header ('Location: login.php');
	}
	
	
	
/*
$pageURL = 'http';
if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
}
else
{
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
$_SESSION['PreviousPage'] = $pageURL;
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC</title>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<link rel="short icon" href="favicon.ico" type="image/x-icon" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php
	echo StyleSheet($_SESSION['CSS']);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" >
	google.load("jquery", "1.3.1");
</script>

<script type="text/javascript" src="javascripts/jquery.js?v=<?php echo rand(1, 10);?>"></script>
<!-- <script type="text/javascript" src="javascripts/jquery.js"></script> -->
<script type="text/javascript">

$(document).ready(function () {	
	
	$('#nav li').hover(
		function () {
			//show its submenu
			$('ul', this).stop().slideDown(100);

		}, 
		function () {
			//hide its submenu
			$('ul', this).stop().slideUp(50);			
		}
	);
	
});
</script>



</head>
<body onload="">
<?php include_once("analyticstracking.php") ?>
<div id="Header" style="background-color:#ffffff">
	<div id="Header_Logo">
		<a href="index.php" title="AdHound&trade;"><img src="images/AdHound_Logo.gif" border="0" alt="AdHound&trade; - It's Advertising, LLC" /></a>
		<?php
			/*
			$kilobyte = 1024;
			$megabyte = $kilobyte * 1024;
			echo '<span style="float:right; text-align:right">Allocated:' . str_replace('M', '', ini_get('memory_limit')) .'Mb<br />';
			echo 'Peak:' . round((memory_get_peak_usage(false) / $megabyte), 0) .'Mb<br />';
			echo 'Usage:'. round((memory_get_usage() / $megabyte), 0) .'Mb<br />';
			echo 'Size:' . round((strlen(serialize( $_SESSION )) / $megabyte), 0) . 'Mb</span>';
			*/
		?>
	</div>
	<div id="Header_Nav">
		<div id="Header_Nav_User">
			<?php 
			if (isset($UserInfo['IA_Users_ID'])) 
			{
				if (!empty($FirstName) && !empty($LastName)) 
				{
					echo '<form name="AccountForm" action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data">';
					echo 'Hello, <a href="index.php" title="My Account" class="HeaderLink">'.$FirstName.' '.$LastName.'</a>';
					switch ($UserInfo['IA_Users_Type']) 
					{
						case 1:
						case 2:
							echo ' <input type="submit" id="EditUserButton" name="EditUserButton" tabindex="11" value="Edit Account" />';
							break;
						default:
							break;
					}
				}
				else 
				{
					echo 'Hello, <a href="index.php" title="My Account" class="HeaderLink">'.$BusinessName.'</a>';
				}
				echo ' <a href="logout.php" title="Logout" class="HeaderLink">[Logout]</a>';
				echo '</form>';
			}
			else
			{
				echo '<a href="https://itsadvertising.c6.ixsecure.com/index.php" title="It\'s Advertising, LLC Account Login">Account Login</a>';
			}
			?>
		</div>
		<?php
			if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['ViewLocations']))	
			{
				echo '<div id="Header_Nav_Locations">';
					echo '<ul id="nav">';
					echo '<li><a href="locations.php" class="HeaderLink">Locations</a>';
						/*
						echo '<ul style="box-shadow:4px 4px 4px #475f94">';
						echo '<li><a href="locations.php" class="HeaderLink">View Locations</a></li>';
						if($UserInfo['IA_Users_Type'] == 1 || $UserInfo['IA_Users_Type'] == 3) 
						{ echo '<li><a href="locations.php?ModeType=AddAccount" class="HeaderLink">Add Location</a></li>'; }
						else 
						{ }
						
						echo '</ul>';
						*/
					echo '</li>';
					echo '</ul>';
				echo '</div>';
			}

			if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['ViewAdvertisers'])) 
			{
				echo '<div id="Header_Nav_Advertisers">';
					echo '<ul id="nav">';
					echo '<li><a href="advertisers.php?ModeType=AdvertiserAccounts" class="HeaderLink">Advertisers</a>';
						/*
						echo '<ul style="box-shadow:4px 4px 4px #475f94">';
						echo '<li><a href="advertisers.php?ModeType=AdvertiserAccounts" class="HeaderLink">View Advertisers</a></li>';
						if($UserInfo['IA_Users_Type'] == 5) 
						{ }
						else 
						{
							echo '<li><a href="advertisers.php?ModeType=AddAdvertiser" class="HeaderLink">Add Advertiser</a></li>';
						}			
						echo '</ul>';
						*/
					echo '</li>';
					echo '</ul>';
				echo '</div>';
			}
			
			if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['ViewAds'])) 
			{
				echo '<div id="Header_Nav_Advertisements">';
					echo '<ul id="nav">';
						echo '<li><a href="reports.php?ReportType=AdLibrary+'.$UserInfo['UserParentID'].'" class="HeaderLink">Advertisements</a>';
						/*
						echo '<ul style="box-shadow:4px 4px 4px #475f94">';
						echo '<li><a href="reports.php?ReportType=AdLibrary+'.$UserInfo['UserParentID'].'" class="HeaderLink">View Advertisements</a></li>';
						switch ($UserInfo['IA_Users_Type']) 
						{
							case 1:
							case 3:
								echo '<li><a href="ads.php?ModeType=AddAdvertisement" class="HeaderLink">Add Advertisement</a></li>';
								break;
							default:
								break;
						}
						echo '</ul>';
						*/
						echo '</li>';
					echo '</ul>';
				echo '</div>';
			}
			
			if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Users']['ViewUsers'])) 
			{
				echo '<div id="Header_Nav_Users">';
					echo '<ul id="nav">';
					echo '<li><a href="users.php" class="HeaderLink">Users</a>';
						//echo '<ul style="box-shadow:5px 5px 5px #475f94">';
						//echo '<li><a href="users.php" class="HeaderLink">View Users</a></li>';
						//echo '<li><a href="users.php?ModeType=AddUser" class="HeaderLink">Add User</a></li>';
						//echo '</ul>';
					echo '</li>';
					echo '</ul>';
				echo '</div>';
			}
			/*
			switch ($UserInfo['IA_Users_Type']) 
			{
				case 1:
				case 2:
					echo '<div id="Header_Nav_Users">';
						echo '<ul id="nav">';
						echo '<li><a href="users.php" class="HeaderLink">Users</a>';
							echo '<ul style="box-shadow:5px 5px 5px #475f94">';
							echo '<li><a href="users.php" class="HeaderLink">View Users</a></li>';
							echo '<li><a href="users.php?ModeType=AddUser" class="HeaderLink">Add User</a></li>';
							echo '</ul>';
						echo '</li>';
						echo '</ul>';
					echo '</div>';
					break;
				default:
					break;
			}
			*/
		
		?>
	</div>
</div>
<div style="clear:both"></div>
<div id="Content">
<div id="DataLoaded" name="DataLoaded"></div>
<div id="loading"  name="loading" style="text-align:center; vertical-align:middle; height:80px; width:100%; margin:0 auto; display:none"><img src="images/loading.gif" align="center" style="text-align:center; margin:15px" /></div>
	<div id="Content_Main">

