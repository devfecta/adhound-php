<?php
	ob_start();
	session_start();
	//mysql_close();
	include "configuration/config.php";
	include "configuration/classes.php";
	
	if(IsMobile()) 
	{ header ("Location: mobile/login.php"); }
	else 
	{ }
	
	$ErrorMessage = null;
/*	
$kilobyte = 1024;
$megabyte = $kilobyte * 1024;
echo 'Allocated:' . str_replace('M', '', ini_get('memory_limit')) .'Mb<br />';
echo 'Peak:' . round((memory_get_peak_usage(false) / $megabyte), 0) .'Mb<br />';
echo 'Usage:'. round((memory_get_usage() / $megabyte), 0) .'Mb<br />';
echo 'Size:' . round((strlen(serialize( $_SESSION )) / $megabyte), 0) . 'Mb<br />';
*/
	if(isset($_POST['LoginButton'])) 
	{
		$Users = new _Users();
		if ($Users->Validate($_POST))
		{
			$LoginMessage = $Users->Login($_POST);

			//echo $Users->Usage;
			switch($LoginMessage) 
			{
				case 'LoggedIn':
					$ErrorMessage = "Already Logged In";
					break;
				case 'Invalid':
					$ErrorMessage = "Invalid Username/Password";
					break;
				case 'Inactive':
					$ErrorMessage = "Please check your e-mail to activate your account before you login.";
					break;
				default:
					unset($_SESSION['RequiredFields']);
					header ("Location: index.php");
					break;
			}
		}
		else
		{ 
			$ErrorMessage = "Invalid Username/Password";
			
		}
		//echo $Users->Message;
	}
	else 
	{
		$ErrorMessage = "Account Login";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC - Login</title>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<link rel="short icon" href="favicon.ico" type="image/x-icon" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php
	//echo StyleSheet($_SESSION['CSS']);
	echo '<link rel="stylesheet" type="text/css" href="css/desktopstylesheet.css?v='. rand(1, 10) .'" media="only screen and (min-width: 401px)" />'."\n";
	echo '<link rel="stylesheet" type="text/css" href="css/stylesheet.css?v='. rand(1, 10) .'" />';
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" >
	google.load("jquery", "1.3.1");
</script>
<script type="text/javascript" src="javascripts/jquery.js"></script>
</head>
<body style="margin-top:10%">
<?php include_once("configuration/analyticstracking.php") ?>
<form name="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div id="Login" style="background-color:#ffffff">
<?php
$image = imagecreatefromgif('images/AdHound_LogoV.gif');
$color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 1, 13, 1, VERSION, $color);
imagegif($image, 'images/AdHound_Logo.gif');
imagedestroy($image);
?>
<img src="images/AdHound_Logo.gif" style="padding:10px;" border="0" alt="AdHound&trade; - It's Advertising, LLC" />

	<h1 style="margin-left:10px"><?php echo $ErrorMessage; ?></h1>
	<div style="display:inline-block; float:left; width:20%; padding:5px 5px 5px 10px; text-align:right">
		Username:
	</div>
	<div style="display:inline-block; width:auto; padding:5px 5px 5px 10px; text-align:left">
		<input type="text" name="UsernameTextBoxRequired" size="30" maxlength="30" <?php echo $_SESSION['RequiredFields'] ?> value="<?php echo $_POST["UsernameTextBoxRequired"].$_REQUEST['Username']; ?>" />
	</div>
	<div style="clear:both"></div>

	<div style="display:inline-block; float:left; width:20%; padding:5px 5px 5px 10px; text-align:right">
		Password:
	</div>
	<div style="display:inline-block; width:auto; padding:5px 5px 5px 10px; text-align:left">
		<input type="password" name="PasswordTextBoxRequired" size="30" maxlength="30" <?php echo $_SESSION['RequiredFields'] ?> />
	</div>
	<div style="clear:both"></div>

	<div style="display:inline-block; float:left; width:20%; padding:5px 5px 5px 10px;">
		&nbsp;
	</div>
	<div style="display:inline-block; width:auto; padding:5px 5px 5px 10px; white-space:nowrap; text-align:right">
		<a href="register.php" id="RegisterLink" name="RegisterLink" title="Register AdHound Account">[Register]</a> | 
		<a href="forgot.php" id="ForgotPasswordLink" name="ForgotPasswordLink" title="Forgot AdHound Password">[Forgot password?]</a> 
		<input type="submit" id="LoginButton" name="LoginButton" style="width:70px; height:30px;" value="Login" />
	</div>

	<div style="display:block; padding:10px; width:auto; padding:5px 5px 5px 10px; text-align:center">
		For the best user experience use<br /><a href="http://www.microsoft.com" target="_blank">Internet Explorer 9+</a>, <a href="http://www.mozilla.org" target="_blank">Firefox 10+</a>, or <a href="http://www.google.com/chrome/" target="_blank">Chrome</a>
	</div>
	<div style="display:block; width:auto; background-color:#000000; padding:5px 5px 5px 10px; color:#ffffff; text-align:center">
		<?php 
			echo COPYRIGHT;
		?>
		<br />~ <a href="http://www.itsadvertising.com/copyright.php" class="FooterLink" title="It's Advertising, LLC - Copyright Policy">Copyright Policy</a>
		~ <a href="http://www.itsadvertising.com/privacy.php" class="FooterLink" title="It's Advertising, LLC - Privacy Policy">Privacy Policy</a>
	</div>
</div>
</form>
</body>
</html>
<?php ob_flush(); ?>