<?php
ob_start();
session_start();
include "config.php";
//include "classes.php";
$XML = new DOMDocument();
//$RegionInfo = $_SESSION['RegionInfo'];
//$LocationInfo = $_SESSION['AccountInfo'];
//$PanelInfo = $_SESSION['PanelInfo'];
//$AdvertiserInfo = $_SESSION['AdvertiserInfo'];
//$AdInfo = $_SESSION['AdsInfo'];

//include "configuration/classes.php";

switch ($_POST['FunctionType'])
{
	case 'ActivateUser':
		$Update = "UPDATE Users SET ";
		$Update .= "Users_Active='".$_POST['Activate']."' ";
		$Update .= "WHERE Users_ID=".$_POST['UserID'];
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Users = mysql_query("SELECT * FROM Users WHERE Users_ID=".$_POST['UserID'], CONN);
			while ($User = mysql_fetch_assoc($Users))
			{
				$Subject = 'AdHound(TM) Account Activation';
				$Message = '<p>Hello '.$User['Users_FirstName'].' '.$User['Users_LastName'].',<br />';
				if($User['Users_Active'] == 0) 
				{
					$Message .= 'Your has been deactivated. Please call It\'s Advertising, LLC at (800) ITS-3883 to address an issue with your account.';
				}
				else 
				{
					$Message .= 'Your account has been activated, and is ready to use. ';
					$Message .= '<a href="http://adhound.itsadvertising.com" target="_blank">Account Login</a>';
				}
				$Message .= '</p>';
				$Message .= '<p>';
				$Message .= 'Thank you for your business,<br />It\'s Advertising, LLC and AdHound(TM) Team';		
				$Message .= '</p>';
				
				$Confirmation = SendEmail($User['Users_Email'], $Subject, $Message);
				if(isset($User['Users_SecondEmail']) && !empty($User['Users_SecondEmail'])) 
				{ $Confirmation = SendEmail($User['Users_SecondEmail'], $Subject, $Message); }
				break;
			}
		}
		break;
	case 'ValidateCard':
		$Update = "UPDATE Users SET ";
		$Update .= "Users_Active='1', ";
		$Update .= "Users_ValidCard='".$_POST['Validate']."' ";
		$Update .= "WHERE Users_ID=".$_POST['UserID'];
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Users = mysql_query("SELECT * FROM Users WHERE Users_ID=".$_POST['UserID'], CONN);
			while ($User = mysql_fetch_assoc($Users))
			{
				$Subject = 'AdHound(TM) Credit Card Validation';
				$Message = '<p>Hello '.$User['Users_FirstName'].' '.$User['Users_LastName'].',<br />';
				if($User['Users_ValidCard'] == 0) 
				{
					$Message .= 'Your credit card has expired and/or is invalid. Please call It\'s Advertising, LLC at (800) ITS-3883 to update your credit card information.';
				}
				else 
				{
					$Message .= 'Your credit card has been validated, and your account is ready to use. ';
					$Message .= '<a href="http://adhound.itsadvertising.com" target="_blank">Account Login</a>';
				}
				$Message .= '</p>';
				$Message .= '<p>';
				$Message .= 'Thank you for your business,<br />It\'s Advertising, LLC and AdHound(TM) Team';		
				$Message .= '</p>';
				
				$Confirmation = SendEmail($User['Users_Email'], $Subject, $Message);
				if(isset($User['Users_SecondEmail']) && !empty($User['Users_SecondEmail'])) 
				{ $Confirmation = SendEmail($User['Users_SecondEmail'], $Subject, $Message); }
				break;
			}
		}
		break;
	case 'CheckUsername':
		$UsersInfo = mysql_query("SELECT * FROM Users WHERE Users_Username='".$_POST["User"]."'", CONN);
		$UserCount = mysql_num_rows($UsersInfo);
		if($UserCount > 0) 
		{
			echo "Username already exsists.";
		}
		else 
		{
			echo "Username OK to use.";
		}
		break;

	case 'DeleteUser':
		$Users = new _Users();
		if ($Users->DeleteUser($_POST['User'])) 
		{
			$Users->GetUserInfo($_POST['ParentUser']);
			$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
			echo $Users->GetUsers($UserInfo);
		}
		else
		{ }
		break;
	default:
		echo $_POST['FunctionType'];
		break;
}
?>