<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	
	$Message = null;

	if (isset($_POST['SendPasswordButton'])) 
	{
		$Users = new _Users();
		if ($Users->Validate($_POST) && $Users->UpdatePassword($_POST))
		{
			unset($_SESSION['RequiredFields']);
			$Message = '<p>'.$Users->ComfirmationMessage.'</p>';
			if(isset($Users->ComfirmationMessage) && !empty($Users->ComfirmationMessage)) 
			{
				$HideFields = ' display:none;';
			}
			else 
			{
				$HideFields = '';
			}
			
			if(isset($Users->UserType) && !empty($Users->UserType)) 
			{
				echo 'Type:'.$Users->UserType;
				if($Users->UserType == 1) 
				{
					session_unset();
					session_destroy();
				}
				else 
				{ }
			}
			else 
			{ }
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
<?php
	echo StyleSheet($_SESSION['CSS']);
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
<form name="LoginForm" action="<?php echo $_SERVER['PHP_SELF'].'?Username='.$_REQUEST['Username']; ?>" method="post" enctype="multipart/form-data">
<table align="center" border="0" style="background-color:#ffffff; width:70%;" cellpadding="3" cellspacing="0">
<tr>
<th colspan="2" style="text-align:left;">
	<h1 style="margin-left:0px">Reset Password</h1>
</th>
</tr>
<tr style="vertical-align:top">
<td colspan="2" style="text-align:left">
<?php 
	echo $Message;
?>
</td>
</tr>
<tr style="vertical-align:top;<?php echo $HideFields; ?>">
<td style="width:40%; text-align:right">Username:</td>
<td style="width:60%">
	<input type="text" name="UsernameTextBoxRequired" readonly="readonly" size="30" maxlength="30" <?php echo $_SESSION['RequiredFields'] ?> value="<?php echo $_REQUEST['Username']; ?>" />
</td>
</tr>
<tr style="vertical-align:top; vertical-align:middle;<?php echo $HideFields; ?>">
<td style="text-align:right">New Password:</td>
<td>
	<input type="password" name="PasswordTextBoxRequired" size="25" maxlength="30" <?php echo $_SESSION['RequiredFields'] ?> value="" />
</td>
</tr>
<tr style="<?php echo $HideFields; ?>">
<td style="text-align:right;">&nbsp;</td>
<td>
	<input type="submit" id="SendPasswordButton" name="SendPasswordButton" style="margin:5px; width:110px; height:30px;" value="Reset Password" />
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