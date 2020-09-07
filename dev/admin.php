<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	$Users = new _Users();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC - Admin</title>

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
<script type="text/javascript">

</script>
</head>
<body style="margin-top:1%">
<table style="box-shadow:5px 5px 5px #000940; background-color:#ffffff; width:800px" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td style="background: url(images/table_background.png) repeat-x; height:20px">
&nbsp;
</td>
</tr>
<tr style="height:60px; vertical-align:middle" class="NavLinks">
<td style="text-align:center; vertical-align:middle; height:60px; background-color:#ffffff;">
	<img src="images/AdHound_Logo.gif" border="0" alt="AdHound&trade; - It's Advertising, LLC" />
</td></tr>
<tr><td style="padding:10px; height:500px; vertical-align:top; text-align:left">
<form id="AdminForm" name="AdminForm" action="admin.php" method="post" enctype="multipart/form-data">
<input type="button" id="LogoutButton" name="LogoutButton" style="width:100px; height:30px" onclick="window.location='logout.php'" value="Logout" />
<?php
	echo '<h2>Admin Users:</h2>';
	echo '<ul>';
	$AdminUsers = mysql_query("SELECT * FROM Users WHERE Users_Type=2 ORDER BY Users_BusinessName, Users_LastName, Users_FirstName ASC", CONN) or die(mysql_error());
	while($AdminUser = mysql_fetch_assoc($AdminUsers))
	{
		echo '<li>'.$AdminUser['Users_LastName'].', '.$AdminUser['Users_FirstName'].'</li>';
	}
	echo '</ul>';
	echo '<h2>Dealer Users:</h2>';
	$DealerBusinesses = mysql_query("SELECT * FROM Users WHERE Users_Type=1 GROUP BY Users_BusinessName ORDER BY Users_Active, Users_ValidCard, Users_BusinessName ASC", CONN) or die(mysql_error());
	while($DealerBusiness = mysql_fetch_assoc($DealerBusinesses))
	{
		echo '<h3>'.$DealerBusiness['Users_BusinessName'].'</h3>';
		echo '<ul>';
		$DealerUsers = mysql_query("SELECT * FROM Users, IA_States WHERE Users_Type=1 AND Users_BusinessName='".$DealerBusiness['Users_BusinessName']."' AND IA_States_ID=Users_StateID ORDER BY Users_Active, Users_ValidCard, Users_LastName, Users_FirstName ASC", CONN) or die(mysql_error());
		while($DealerUser = mysql_fetch_assoc($DealerUsers))
		{
			if($DealerUser['Users_ValidCard'] == 0) 
			{
				echo '<li style="color:#ff0000">';
			}
			elseif($DealerUser['Users_Active'] == 0) 
			{
				echo '<li style="color:#daa520">';
			}
			else 
			{
				echo '<li>';
			}
			echo '<b>'.$DealerUser['Users_BusinessName'].'</b><br />';
			switch($DealerUser['Users_Tier']) 
			{
				case 0:
					$Tier = 'Free';
					break;
				case 1:
					$Tier = 'Chihuahua';
					break;
				case 2:
					$Tier = 'Beagle';
					break;
				case 3:
					$Tier = 'Blood Hound';
					break;
				case 4:
					$Tier = 'Great Dane';
					break;
				default:
					break;
			}
			echo '<b>Username: </b>'.$DealerUser['Users_Username'].' (<i>'.$Tier.'</i>)<br />';
			echo $DealerUser['Users_LastName'].', '.$DealerUser['Users_FirstName'].'<br />';
			echo $DealerUser['Users_Address'].'<br />';
			echo $DealerUser['Users_City'].', '.$DealerUser['IA_States_Abbreviation'].' '.$DealerUser['Users_Zipcode'].'<br />';
			echo '<b>Phone: </b>'.$DealerUser['Users_Phone'].' <b>Fax: </b>'.$DealerUser['Users_Phone'].'<br />';
			echo '<b>e-Mail: </b>'.$DealerUser['Users_Email'];
			echo '</li>';
			echo '<label>';
			if($DealerUser['Users_ValidCard'] == 0) 
			{
				echo '<input type="checkbox" id="ValidateCard'.$DealerUser['Users_ID'].'" name="ValidateCard'.$DealerUser['Users_ID'].'" onchange="ValidateCard(this.value)" value="'.$DealerUser['Users_ID'].'-1" /> Valid Card ';
			}
			else 
			{
				echo '<input type="checkbox" id="ValidateCard'.$DealerUser['Users_ID'].'" name="ValidateCard'.$DealerUser['Users_ID'].'" onchange="ValidateCard(this.value)" value="'.$DealerUser['Users_ID'].'-0" checked="checked" /> Valid Card ';
			}
			echo '</label>';
			echo '<label>';
			if($DealerUser['Users_Active'] == 0) 
			{
				echo '<input type="checkbox" id="ActivateUser'.$DealerUser['Users_ID'].'" name="ActivateUser'.$DealerUser['Users_ID'].'" onchange="ActivateUser(this.value)" value="'.$DealerUser['Users_ID'].'-1" /> Activated User ';
			}
			else 
			{
				echo '<input type="checkbox" id="ActivateUser'.$DealerUser['Users_ID'].'" name="ActivateUser'.$DealerUser['Users_ID'].'" onchange="ActivateUser(this.value)" value="'.$DealerUser['Users_ID'].'-0" checked="checked" /> Activated User ';
			}
			echo '</label>';
		}
		echo '</ul>';
	}
?>
</form>
</td></tr>
<tr>
<td class="footer">
	<?php 
		echo COPYRIGHT;
	?>
	<br />~ <a href="http://www.itsadvertising.com/copyright.php" class="FooterLink" title="It's Advertising, LLC - Copyright Policy">Copyright Policy</a>
	 ~ <a href="http://www.itsadvertising.com/privacy.php" class="FooterLink" title="It's Advertising, LLC - Privacy Policy">Privacy Policy</a>
</td></tr>
</table>
</body>
</html>
<?php ob_flush(); ?>