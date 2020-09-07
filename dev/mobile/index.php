<?php
	include "configuration/header.php";
	
	$ErrorMessage = null;
	$RequiredField = null;
	
// Filter Options
	if (isset($_REQUEST['FilterByOptions']) && !empty($_REQUEST['FilterByOptions'])) 
	{
		$_SESSION['FilterByOptions'] = $_REQUEST['FilterByOptions'];
	}
	if (isset($_REQUEST['FilterBy']) && !empty($_REQUEST['FilterBy'])) 
	{
		$_SESSION['FilterBy'] = $_REQUEST['FilterBy'];
	}
	
	
	if (isset($_POST['FilterByClearButton'])) 
	{
		unset($_SESSION['FilterByOptions']);
		unset($_SESSION['FilterBy']);
		header ("Location: ".$_SERVER['PHP_SELF']."?Page=".$_GET['Page']);
	}
// Accounts

// Standard Cancel
/*
	if (isset($_POST['CancelButton'])) 
	{
		unset($_SESSION['ModeType']);
	}
*/
	switch ($UserInfo['Users_Type'])
	{
		case 4:
			break;
		default:
			$PageTitle = '<div id="PageTitle">'.$UserInfo['Users_BusinessName'].'</div>';
			break;
	}
?>
<form name="AccountForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div border="0" style="width:100%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<?php echo $PageTitle; ?>
<?php 
echo $_SESSION['Test'];
	if ($UserInfo['Users_Type'] <> 4)	
	{
		$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
		//$Advertisers = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
		//$Query = $Advertisers->xpath('/Advertisers/Advertiser[@id="277"]');
		//$Result = json_decode(json_encode($Query),true);
//print("Data<pre>". print_r($Result,true) ."</pre>");

		if(isset($_REQUEST['AccountID']) && !empty($_REQUEST['AccountID'])) 
		{
			$Locations = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$_REQUEST['AccountID'].'"]');
			$LocationInfo = json_decode(json_encode($Locations),true);
		}
		else 
		{
			$Locations = $Data->xpath('/Data/State/Regions/Region');
			$LocationInfo = json_decode(json_encode($Locations),true);
		}
//print("Data<pre>". print_r($LocationInfo,true) ."</pre>");
		// Locations
		$Accounts = new _Accounts();
		echo $Accounts->AccountList($UserInfo, $LocationInfo);
		// Locations List	
		/*
		echo '<tr><td>';
		echo $Accounts->BuildAccountList($_SESSION['UserParentID'], $_SESSION['UserType'], $_REQUEST['AccountID'], $_REQUEST['OrderBy'], $_SESSION['FilterByOptions'], $_SESSION['FilterBy'], $_REQUEST['ModeType'], $_GET['Page'], 10);
		echo '</td></tr>';
		*/
	}
	else
	{ }
?>
</div>
</form>
<?php
	include "configuration/footer.php";
?>