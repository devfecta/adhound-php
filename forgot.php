<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	
	$Message = null;

	if (isset($_POST['SendPasswordButton'])) 
	{
		$Users = new _Users();
		if ($Users->Validate($_POST) && $Users->ForgotPassword($_POST))
		{
			unset($_SESSION['RequiredFields']);
			$Message = $Users->ComfirmationMessage;
		}
		else
		{ }
	}
	else 
	{ }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC - Forgot Password</title>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<link rel="short icon" href="favicon.ico" type="image/x-icon" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel=stylesheet type="text/css" href="css/stylesheet.css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" >
	google.load("jquery", "1.3.1");
</script>
<script type="text/javascript" src="javascripts/jquery.js"></script>
</head>
<body style="margin-top:10%">
<?php include_once("configuration/analyticstracking.php") ?>
<table style="box-shadow:5px 5px 5px #000940; background-color:#ffffff; width:500px" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td style="background: url(images/table_background.png) repeat-x; height:20px">
&nbsp;
</td>
</tr>
<tr style="height:60px; vertical-align:middle" class="NavLinks">
<td style="text-align:center; vertical-align:middle; height:60px; background-color:#ffffff;">
	<img src="images/AdHound_Logo.gif" border="0" alt="AdHound&trade; - It's Advertising, LLC" />
</td></tr>
<tr><td>
<form name="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<table align="center" border="0" style="background-color:#ffffff; width:85%;" cellpadding="3" cellspacing="0">
<tr>
<th colspan="2" style="text-align:left;">
	<h1 style="margin-left:0px">Forgot Password</h1>
</th>
</tr>
<tr style="vertical-align:top">
<td colspan="2" style="text-align:left">
<?php 
	echo $Message;
?>
</td>
</tr>
<tr style="vertical-align:middle; height:30px">
<td style="width:30%; text-align:right">Username:</td>
<td style="width:70%">
	<input type="text" name="UsernameTextBoxRequired" size="25" maxlength="30" <?php echo $_SESSION['RequiredFields'] ?> value="<?php echo $_POST["UsernameTextBoxRequired"].$_REQUEST['Username']; ?>" /> 
</td>
</tr>
<tr>
<td colspan="2" style="text-align:right; vertical-align:middle; height:40px">
	<input type="submit" id="SendPasswordButton" name="SendPasswordButton" value="Send Password" />
	<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel" /> 
</td>
</tr>
</table>
</form>
</td>
</tr>
<tr>
<td colspan="3" class="footer">
	<?php 
		echo COPYRIGHT;
	?>
</td></tr>
</table>
</body>
</html>
<?php ob_flush(); ?>