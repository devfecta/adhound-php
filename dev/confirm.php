<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	
	if((isset($_REQUEST['ID']) && !empty($_REQUEST['ID'])) && (isset($_REQUEST['Username']) && !empty($_REQUEST['Username']))) 
	{
		$Update = "UPDATE Users SET Users_Active=1 WHERE Users_ID=".$_REQUEST['ID'];
		$Update .= " AND Users_Username='".$_REQUEST['Username']."'";
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			//header ('Location: login.php');
			header ('Location: login.php?Username='.$_REQUEST['Username']);
		}
		else
		{
			echo 'Invalid Account Information';
		}
	}
	else 
	{
		echo 'Invalid Account Information';
	}
?>