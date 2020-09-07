<?php
	include "configuration/header.php";
	$XML = new DOMDocument();

	$ErrorMessage = null;
	//$RequiredField = null;
	$ReadOnly = null;
	$ErrorMessage = "Advertiser Accounts";
	
	$Advertisers = new _Advertisers();
	
	$ErrorMessage = null;
	
	if (isset($_POST['InsertAdvertiserButton'])) 
	{
		/*
		if(!empty($_POST['EmailTextBox'])) 
		{
			$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_Email='".$_POST['EmailTextBox']."'", CONN);
			$AdvertiserCount = mysql_num_rows($AdvertiserInfo);
		}
		else 
		{
			$AdvertiserCount = 0;
		}
		
		if($AdvertiserCount > 0) 
		{
			$ErrorMessage = "An advertiser already uses the e-mail address you enter, please use a different e-mail address.";
		}
		else 
		{
			if ($Advertisers->Validate($_POST) && $Advertisers->AddAdvertiser($_POST, $_SESSION['UserParentID']))
			{
				unset($_SESSION['RequiredFields']);
				//unset($_SESSION['ModeType']);
				header ("Location: advertisers.php?ModeType=AdvertiserAccounts");
			}
			else
			{
				header ("Location: advertisers.php?ModeType=AddAdvertiser");
			}
		}
		*/
		if ($Advertisers->Validate($_POST) && $Advertisers->AddAdvertiser($_POST, $UserInfo['UserParentID']))
		{
			unset($_SESSION['RequiredFields']);
			//unset($_SESSION['ModeType']);
			header ("Location: advertisers.php?ModeType=AdvertiserAccounts");
		}
		else
		{
			//header ("Location: advertisers.php?ModeType=AddAdvertiser");
		}
	}
	else
	{ }

	if (isset($_POST['UpdateAdvertiserButton']))
	{
		if ($Advertisers->Validate($_POST) && $Advertisers->UpdateAdvertiser($UserInfo, $_POST))
		{
			unset($_SESSION['RequiredFields']);
			unset($_SESSION['ModeType']);
			unset($_SESSION['AdvertiserID']);
			header ("Location: advertisers.php?ModeType=AdvertiserAccounts");
		}
		else
		{
			
		}
	}
	else
	{ }
	/*
	if (isset($_POST['DeleteAdvertiserButton']))
	{
		if ($Advertisers->DeleteAdvertiser($_POST['AdvertiserID']))
		{
			header ("Location: advertisers.php?ModeType=AdvertiserAccounts");
		}
		else
		{ }
	}
	else
	{ }
*/
	$PageTitle = rtrim(preg_replace('#([A-Z][^A-Z]*)#', '$1 ', $_REQUEST['ModeType']));
?>

<form name="AdvertiserForm" action="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>" method="post" enctype="multipart/form-data">
<div style="display:block; vertical-align:top; text-align:left; white-space:nowrap; padding-top:10px">
<h1 style="margin:0px 0px 5px 0px"><?php echo $PageTitle; ?></h1>
<?php
	switch($UserInfo['IA_Users_Type']) 
	{
		case 1:
		case 3:
		case 5:
			/*
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{
				$Advertisers = new _Advertisers();
				$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
			}
			$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
			$AdvertisersInfo = $XML->getElementsByTagName("Advertiser");
			$a = 0;
			foreach ($AdvertisersInfo as $Array) 
			{
				foreach($Array->childNodes as $n) 
				{
					if($n->nodeName != '#text') 
					{  $AdvertiserInfo[$a][$n->nodeName] .= $n->nodeValue; }
				}
				$a++;
			}
			*/
			//echo '<tr><td>';
			//echo '<table id="AdvertiserTable" name="AdvertiserTable" border="0" align="center" style="width:100%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ 
				$Advertisers = new _Advertisers();
				$Advertisers->GetAdvertisers($UserInfo['UserParentID'], $AdvertiserID);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
			$AdvertisersInfo = json_decode(json_encode($XML),true);
			
			if(isset($AdvertisersInfo['Advertiser'][0])) 
			{
				for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
				{ $AdvertiserInfo[] = array_filter($AdvertisersInfo['Advertiser'][$a]); }
			}
			else 
			{
				if(isset($AdvertisersInfo['Advertiser']) && !empty($AdvertisersInfo['Advertiser'])) 
				{ $AdvertiserInfo[] = array_filter($AdvertisersInfo['Advertiser']); }
				else 
				{ $AdvertiserInfo = null; }
			}
	
			echo $Advertisers->BuildAdvertiserList($UserInfo, $AdvertiserInfo, $_REQUEST['AdvertiserID'], $_REQUEST['ModeType'], $_GET['Page'], 10);
			//echo '</table>';
			//echo '</td></tr>';
			break;
		default:
			break;
	}
?>
</div>
</form>
<?php
	include "configuration/footer.php";
?>