<?php
	include "configuration/config.php";
	include "configuration/classes.php";
	//$Users = new _Users();
	//$Users->ClearAllVars();
	//unset($_SESSION['UserID']);
	session_start();
	
	$DeleteSession = "DELETE FROM Sessions WHERE Sessions_SessionID='". session_id()."'";
	if (mysql_query($DeleteSession, CONN) or die(mysql_error())) 
	{
		
	}
	session_unset();
	session_destroy();
	
	header ('Location: login.php');
?>