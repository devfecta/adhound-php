<?php
	include "configuration/header.php";
?>
<form name="TestForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<table border="0" style="width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr><td>
<?php
echo $_SERVER["DOCUMENT_ROOT"].'<br />';
echo ROOT.'<br />';
$Dir = ROOT.'/users/'.$UserInfo['UserParentID'];
/*
foreach(glob($Dir.'/*') as $Tier1) 
{
	if(is_dir($Tier1))
	{
		foreach(glob($Tier1.'/*') as $Tier2) 
		{
			if(is_dir($Tier2))
			{
				foreach(glob($Tier2.'/*') as $Tier3) 
				{
					if(is_dir($Tier3))
					{
						echo 'Tier3 Directory rmdir:'.$Tier3.'<br />';
						//rmdir($Tier3);
					}
					else 
					{
						echo 'Tier3 unlink:'.$Tier3.'<br />';
						// Files in users/ UserID /images/ads
						// Files in users/ UserID /images/highres
						// Files in users/ UserID /images/lowres
						//unlink($Tier3);
					}
				}
				echo 'Tier2 Directory rmdir:'.$Tier2.'<br />';
				// Directory users/ UserID /images/ads
				// Directory users/ UserID /images/highres
				// Directory users/ UserID /images/lowres
				//rmdir($Tier2);
			}
			else 
			{
				echo 'Tier2 unlink:'.$Tier2.'<br />';
				// Files in users/ UserID /data
				// Files in users/ UserID /images
				//unlink($Tier2);
			}
		}
		echo 'Tier1 Directory rmdir:'.$Tier1.'<br />';
		// Directory users/ UserID /data
		// Directory users/ UserID /images
		//rmdir($Tier1);
	}
	else
	{
		echo 'Tier1 unlink:'.$Tier1.'<br />';
		// Files in users/ UserID /data
		//unlink($Tier1);
	}
	
}
//rmdir($Dir);
echo 'Directory rmdir:'.$Dir;
*/
/*
if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_240_AdsInfo.xml')) 
{ }
else 
{
	$Advertisements = new _Advertisements();
	//$Advertisements->GetAds($UserInfo['UserParentID'], 27); 
}
$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_240_AdsInfo.xml'));
$Ad = json_decode(json_encode($XML),true);
if(isset($Ad['Ad'][0])) 
{
	for($a=0; $a<count($Ad['Ad']); $a++) 
	{ $AdInfo[] = $Ad['Ad'][$a]; }
}
else 
{ $AdInfo[] = $Ad['Ad']; }

$Accounts = array();
$Panels = new _Panels();
for($ad=0; $ad<count($AdInfo); $ad++) 
{
	$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
	// Duplicate Account ID Check
	for($a=0; $a<count($Accounts); $a++) 
	{
		if($Accounts[$a] != $AdInfo[$ad]['IA_Ads_AccountID']) 
		{
			
			//$Panels->GetPanels($UserInfo['UserParentID'], null, $Ad[$ad]['IA_Ads_AccountID'], null);
			break;
		}
		else 
		{ unset($Accounts[$a]); }
	}
}
print("<pre>". print_r($Accounts,true) ."</pre>");
*/
/*
$XML = new DOMDocument();
$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_27_AdsInfo.xml');
$AdsInfo = $XML->getElementsByTagName("Ad");

$a = 0;
foreach ($AdsInfo as $Array) 
{
	
	foreach($Array->childNodes as $n) 
	{
		if($n->nodeName != '#text') 
		{  $AdInfo[$a][$n->nodeName] .= $n->nodeValue; }
	}
	$a++;
	
}
*/
//print("<pre>". print_r($AdInfo,true) ."</pre>");

/*
for($a=0; $a<count($AdInfo); $a++) 
{
	if($AdInfo[$a]['IA_AdLibrary_ID'] == 335) 
	{
		foreach($AdInfo[$a] as $key => $value)
		{
			//if(preg_match('/IA_AdLibrary/', $key) || preg_match('/IA_Advertisers/', $key))
			//{
			//	$Ad[$key] = $value;
			//}
			$Ad[$key] = $value;
		}
		break;
	}
}
*/


/*
$XML = simplexml_load_string(file_get_contents('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml'));
$Array = json_decode(json_encode($XML),true);

echo $Array['Advertiser'][0]['IA_Advertisers_ID'];

print("<pre>". print_r($Array,true) ."</pre>");
*/
$Users = new _Users();
$Accounts = new _Accounts();
$Advertisers = new _Advertisers();
$Advertisements = new _Advertisements();

//// Gets User's Advertiser and Advertiser Pricing (Can be Advertiser specific)
//print("<pre>". print_r($Advertisers->GetAdvertisers($_SESSION['UserParentID'], null),true) ."</pre>");
//// Gets User Info
print("<pre>". print_r($UserInfo,true) ."</pre>");
//// Gets User's Regions
//print("<pre>". print_r($RegionInfo,true) ."</pre>");
//// Gets User's Locations
//print("<pre>". print_r($_SESSION['AccountInfo'][0],true) ."</pre>");
//// Gets User's Panels
//print("<pre>". print_r($PanelInfo,true) ."</pre>");
//// Gets User's Advertisers
//print("<pre>". print_r($_SESSION['AdvertiserInfo'][0],true) ."</pre>");
//// Gets User's Advertiser's Ads
//print("<pre>". print_r($AdInfo, true) ."</pre>");


//print("<pre>". print_r($Users->UserInfoArray,true) ."</pre>");

//$Users->GetUserInfo(1);
//$_SESSION['User'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($Users->UserInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
// $EncryptArray = base64_encode($Users->UserInfoArray);
//$DecryptArray = array();	
//$UserDecrypted = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
//echo '<br />'.$UserDecrypted['IA_Users_BusinessName'].'<br />';
//$DecryptArray = array_map("decrypt", $Users->UserInfoArray);
//$DecryptArray = base64_decode($EncryptArray);

//print("<pre>". print_r($Users->UserInfoArray,true) ."</pre>");

/*
$AdvertiserInfo = $Advertisers->GetAdvertisers($_SESSION['UserParentID'], 27);
for($a=0; $a<count($AdvertiserInfo['Advertisers']); $a++) 
{
	$AdvertiserInfo['Advertisers'][$a]['Ads'] = $Advertisements->GetAds($_SESSION['UserParentID'], $AdvertiserInfo['Advertisers'][$a]['IA_Advertisers_ID']);
}

print("<pre>". print_r($AdvertiserInfo,true) ."</pre>");
*/





/*
$AdvertisersArray = array();
$ArrayID = 0;

$Advertisers = mysql_query("SELECT IA_Advertisers.* FROM IA_Advertisers, IA_Ads WHERE IA_Ads_AccountID=1 AND IA_Advertisers_ID=IA_Ads_AdvertiserID GROUP BY IA_Ads_AdvertiserID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
while ($Advertiser = mysql_fetch_assoc($Advertisers))
{
	$AdvertisersArray['Advertisers'][$ArrayID] = $Advertiser;
	
	$Pricings = mysql_query("SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC", CONN);
	while ($Pricing = mysql_fetch_assoc($Pricings))
	{
		$AdvertisersArray['Advertisers'][$ArrayID]['Pricing'][] = $Pricing;
	}
	
	$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID ORDER BY IA_Ads_AccountID ASC", CONN);
	while ($Ad = mysql_fetch_assoc($Ads))
	{
		$AdvertisersArray['Advertisers'][$ArrayID]['Ads'][] = $Ad;
	}
	$ArrayID++;
}

$Array = serialize($AdvertisersArray);
$Array = gzcompress($Array);
print($Array);
$Array = gzuncompress($Array);
$Array = unserialize($Array);
print("<pre>". print_r($AdvertisersArray,true) ."</pre>");
*/
/*
for($a=0; $a<count($AdvertisersArray); $a++) 
{
	for($i=0; $i<count($AdvertisersArray[$a][key($AdvertisersArray[(int)$a])]); $i++) 
	{
		if($AdvertiserID != $AdvertisersArray[$a][key($AdvertisersArray[(int)$a])][$i]['IA_Advertisers_ID']) 
		{
			echo 'A:'. $AdvertisersArray[$a][key($AdvertisersArray[(int)$a])][$i]['IA_Advertisers_ID'] .'<br />';
			$AdvertiserID = key($AdvertisersArray[(int)$a]);
		}
		
		if($AdvertiserID == $AdvertisersArray[$a][key($AdvertisersArray[(int)$a])][$i]['IA_Advertisers_ID']) 
		{
			echo $AdvertisersArray[$a][key($AdvertisersArray[(int)$a])][$i]['IA_Ads_ID'].'<br />';
		}
	}
}
*/



/*
$AdStartDate = '2013-08-01';
$AdEndDate = '2013-08-31';
$StartDate = '2013-01-01';
$EndDate = '2014-03-31';

//echo date("Y", strtotime($AdEndDate)) - date("Y", strtotime($AdStartDate)).'<br />';
for($d=strtotime($AdStartDate); $d<=strtotime($AdEndDate); $d++) 
{
	if($d >= strtotime($StartDate) && $d <= strtotime($EndDate)) 
	{
		//$DateRangeValid = true;
		echo 'Valid Range';
		//break;
	}
	else 
	{
		//$DateRangeValid = true;
		echo 'NOT Valid Range';
	}
	
	
	if((date('Y', strtotime($AdEndDate)) - date('Y', $d)) > 0) 
	{
		$d = strtotime('+1 year', $d);
		echo date('Y-m-d', $d) .'<br />';
	}
	elseif((date('m', strtotime($AdEndDate)) - date('m', $d)) > 0) 
	{
		$d = strtotime('+1 month', $d);
		echo date('Y-m-d', $d) .'<br />';
	}
	else 
	{
		$d = strtotime('+1 day', $d);
		echo date('Y-m-d', $d) .'<br />';
	}
}
*/
/*
echo $Accounts->AccountList($_SESSION['UserParentID'], $_SESSION['UserType'], $_REQUEST['AccountID'], $_REQUEST['RegionID'], $_REQUEST['OrderBy'], $_SESSION['FilterByOptions'], $_SESSION['FilterBy'], $_REQUEST['ModeType'], $_GET['Page'], 10);

echo $Accounts->LocationRow;
*/
/*
foreach($Accounts->GetLocations($_SESSION['UserParentID'], null) as $AccountInfo)
{
	echo $AccountInfo['IA_Accounts_BusinessName'].'<br />';
}
*/
/*
foreach($Advertisers->GetAdvertisers($_SESSION['UserParentID'], null) as $AdvertiserInfo)
{
	echo $AdvertiserInfo['IA_Advertisers_BusinessName'].'<br />';
}
*/
/*
foreach($Advertisements->GetAds($_SESSION['UserParentID'], null, null, null, null) as $AdInfo)
{
	echo $AdInfo['IA_Advertisers_BusinessName'].'<br />';
}
*/
?>
</td></tr>
</table>
</form>
<?php
	include "configuration/footer.php";
?>