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
	
// User Account - Edit
	if (isset($_POST['EditUserButton'])) 
	{
		$_SESSION['ModeType'] = "EditUserAccount";
		header ("Location: index.php");
	}
	else 
	{ }

// Accounts
// Accounts - Add Account
	$Accounts = new _Accounts();
	
	if (isset($_POST['InsertLocationButton'])) 
	{
		if ($Accounts->Validate($_POST) && $Accounts->AddRecord($UserInfo, $_POST))
		//if ($Accounts->AddRecord($UserInfo, $_POST))
		{
			unset($_SESSION['RequiredFields']);
			unset($_SESSION['ModeType']);
		}
		else
		{ header ("Location: locations.php?ModeType=AddAccount"); }
	}

// Accounts - Update Account
	if (isset($_POST['UpdateLocationButton'])) 
	{
		if($Accounts->Validate($_POST) && $Accounts->UpdateRecord($UserInfo, $_POST, $_REQUEST['AccountID']))
		{
			unset($_SESSION['RequiredFields']);
		}
		else 
		{ header ("Location: locations.php?ModeType=AddAccount"); }
	}
	else 
	{ }

// Standard Cancel
/*
	if (isset($_POST['CancelButton'])) 
	{
		unset($_SESSION['ModeType']);
	}
*/
	switch ($UserInfo['IA_Users_Type'])
	{
		case 4:
			break;
		default:
			$PageTitle = '<div id="PageTitle">'.$UserInfo['IA_Users_BusinessName'].'</div>';
			break;
	}
?>
<form name="AccountForm" action="<?php echo $_SERVER['PHP_SELF'].'?FilterByOptions='.$_REQUEST[FilterByOptions].'&FilterBy='.$_REQUEST[FilterBy].'&OrderBy='.$_REQUEST[OrderBy].'&Page='.$_GET['Page']; ?>" method="post" enctype="multipart/form-data">
<div border="0" style="width:100%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<?php echo $PageTitle; ?>
<?php 
	if ($UserInfo['IA_Users_Type'] <> 4)	
	{
		// Locations
		// Add Location
		if ($_REQUEST['ModeType'] == 'AddAccount' || $_REQUEST['ModeType'] == 'EditAccount')
		{
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
			{ }
			else 
			{
				$Accounts->GetLocations($UserInfo['UserParentID'], null);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			$LocationsInfo = json_decode(json_encode($XML),true);
			
			if(isset($LocationsInfo['Account'][0])) 
			{
				for($a=0; $a<count($LocationsInfo['Account']); $a++) 
				{  
					
					if($LocationsInfo['Account'][$a]['IA_Accounts_ID'] == $_REQUEST['AccountID']) 
					{
						$LocationInfo[] = array_filter($LocationsInfo['Account'][$a]);
						break;
					}
				}
			}
			else 
			{
				if(isset($LocationsInfo['Account']) && !empty($LocationsInfo['Account'])) 
				{ $LocationInfo[] = array_filter($LocationsInfo['Account']); }
				else 
				{ $LocationInfo = null; }
			}
		
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
			{ }
			else 
			{
				$Accounts->GetRegions($UserInfo['UserParentID'], null);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
			$RegionsInfo = json_decode(json_encode($XML),true);
			
			if(isset($RegionsInfo['Region'][0])) 
			{
				for($a=0; $a<count($RegionsInfo['Region']); $a++) 
				{  $RegionInfo[] = array_filter($RegionsInfo['Region'][$a]); }
			}
			else 
			{
				if(isset($RegionsInfo['Region']) && !empty($RegionsInfo['Region'])) 
				{ $RegionInfo[] = array_filter($RegionsInfo['Region']); }
				else 
				{ $RegionInfo = null; }
			}
			/*
			$XML = new DOMDocument();
			$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
			$RegionsInfo = $XML->getElementsByTagName("Region");
			$a = 0;
			foreach ($RegionsInfo as $Array) 
			{
				foreach($Array->childNodes as $n) 
				{
					if($n->nodeName != '#text') 
					{  $RegionInfo[$a][$n->nodeName] .= $n->nodeValue; }
				}
				$a++;
			}
			*/
			echo $Accounts->BuildAccountForm($UserInfo, $LocationInfo, $RegionInfo, $_REQUEST['AccountID'], $_REQUEST['ModeType']);
		}
		else
		{ 
			echo $Accounts->AccountList($UserInfo, $_REQUEST['AccountID'], $_REQUEST['RegionID']);
		}
		// Locations List	
		/*
		echo '<tr><td>';
		echo $Accounts->BuildAccountList($_SESSION['UserParentID'], $_SESSION['UserType'], $_REQUEST['AccountID'], $_REQUEST['OrderBy'], $_SESSION['FilterByOptions'], $_SESSION['FilterBy'], $_REQUEST['ModeType'], $_GET['Page'], 10);
		echo '</td></tr>';
		*/
	}
	else
	{
		
	}
?>
</div>
</form>
<?php
	include "configuration/footer.php";
?>