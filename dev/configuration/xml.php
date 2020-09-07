<?php
ob_start();
session_start();
require "../users/".$_SESSION['User']."/config.php";
//require "classes.php";

// $_POST['UserInfo']

if(isset($_POST['UserInfo']) && !empty($_POST['UserInfo'])) 
{
	//require "class.data.php";
	$Data = new _Data();
	if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_Data.xml')) 
	{ }
	else 
	{
		$Data->GetAll($_POST['UserInfo']);
	}

	//$Data->GetAll($_POST['UserInfo']);
/*
	if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_RegionsInfo.xml')) 
	{ }
	else 
	{
		$Accounts = new _Accounts();
		$Accounts->GetRegions($_POST['UserInfo'], null);
	}

	if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AccountsInfo.xml')) 
	{ }
	else 
	{
		$Accounts = new _Accounts();
		$Accounts->GetLocations($_POST['UserInfo'], null);
	}
*/
	if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AdvertisersInfo.xml')) 
	{ }
	else 
	{
		$Advertisers = new _Advertisers();
		$Advertisers->GetAdvertisers($_POST['UserInfo'], null);
	}

	$XML = simplexml_load_file(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AdvertisersInfo.xml');
	$AdvertiserArray = json_decode(json_encode($XML),true);
/*
	for($a=0; $a<count($AdvertiserArray['Advertiser']); $a++) 
	{
		if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_'.$AdvertiserArray['Advertiser'][$a]['IA_Advertisers_ID'].'_AdsInfo.xml')) 
		{ }
		else 
		{
			$Advertisements = new _Advertisements();
			$Advertisements->GetAds($_POST['UserInfo'], $AdvertiserArray['Advertiser'][$a]['IA_Advertisers_ID']);
		}
	}
*/
		if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AdLibraryInfo.xml')) 
		{ }
		else 
		{
			$Advertisements = new _Advertisements();
			for($a=0; $a<count($AdvertiserArray['Advertiser']); $a++) 
			{
				$Advertisements->GetAdLibrary($UserInfo, null);
			}
		}

	if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AdTypesInfo.xml')) 
	{ }
	else 
	{
		$Advertisements = new _Advertisements();
		$Advertisements->GetAdTypes($_POST['UserInfo'], null);
	}
	/*
	$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_AccountsInfo.xml'));
	$AccountArray = json_decode(json_encode($XML),true);
	
	for($a=0; $a<count($AccountArray['Account']); $a++) 
	{
		if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_'.$AccountArray['Account'][$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
		{ }
		else 
		{
			$Panels = new _Panels();
			$Panels->GetPanels($_POST['UserInfo'], null, $AccountArray['Account'][$a]['IA_Accounts_ID'], null);
		}
	}
	*/
}
?>