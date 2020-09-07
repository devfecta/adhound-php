<?php
	include "configuration/header.php";
?>
<form name="TestForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<table border="0" style="width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr><td>
<?php
$Users = new _Users();
$Accounts = new _Accounts();
$Advertisers = new _Advertisers();
$Advertisements = new _Advertisements();


$XML = new DOMDocument('1.0', 'UTF-8');
$XML->preserveWhiteSpace = false;
$XML->formatOutput = true;
$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
$xPath = new DOMXpath($XML);

$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
$AdNodes = $Data->xpath('/Data/State/Regions/Region[@id="61"]/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');

foreach($AdNodes as $a) 
{
	echo '<p>'.$a->IA_Ads_Placement.'</p>';
}		

/*
//$AdNodes = $xPath->query('/Data/State/Regions/Region[@id="61"]/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad/IA_Ads_Placement');

//$Ads = json_decode(json_encode($Node),true);
for ($i = 0; $i < $AdNodes->length; $i++) 
{
	echo '<p>'.$AdNodes->item($i)->nodeValue.'</p>';
}
*/







	
/*
$AdLibraryID = 750;

if(isset($AdLibraryID) && !empty($AdLibraryID)) 
{
	$RootNodes = $xPath->query('/Data/State/Regions/Region/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad[IA_Ads_AdLibraryID="'.$AdLibraryID.'"]');
}
else 
{ }

for ($i = 0; $i < $RootNodes->length; $i++) 
{
	echo '<p>'.$RootNodes->item($i)->nodeValue.'</p>';
}

foreach ($RootNodes as $Node) 
{
	$Ads = json_decode(json_encode($Node),true);
	print("<pre>AdsInfo". print_r($Ads,true) ."</pre>");
	//$Node->parentNode->removeChild($Node);
}
*/




/*
$XML = new DOMDocument('1.0', 'UTF-8');
$XML->preserveWhiteSpace = false;
$XML->formatOutput = true;
$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
// Modifiable lookup in XML the place to put new data
$xPath = new DOMXpath($XML);

$AdNodes = $xPath->query('/Data/State/Regions/Region/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad[IA_Ads_AdvertiserID=277]');
for ($i = 0; $i < $AdNodes->length; $i++) 
{
	echo '<p>'.$AdNodes->item($i)->nodeValue.'</p>';
}
*/
/*
print("<pre>AdsInfo". print_r($AdNodes,true) ."</pre>");

foreach ($AdNodes as $Node) 
{
	$Ads = json_decode(json_encode($Node),true);
	print("<pre>AdsInfo". print_r($Ads,true) ."</pre>");
	//$Node->parentNode->removeChild($Node);
}
*/

/*
echo $_SERVER["DOCUMENT_ROOT"].'<br />';
echo 'Path:'.$_SERVER["HTTP_HOST"].'<br />';

$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
//$Accounts = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$_REQUEST['AccountID'].'"]');
$Ads = $Data->xpath('/Data/State/Regions/Region/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');
$Ads = json_decode(json_encode($Ads),true);
print("<pre>Ads". print_r($Ads,true) ."</pre>");
$AdvertiserID = 277;
if(isset($Ads[0])) 
{
	for($a=0; $a<count($Ads); $a++) 
	{
		if($Ads[$a]['IA_Ads_AdvertiserID'] == $AdvertiserID) 
		{ $AdsInfo[] = $Ads[$a]; }
	}
}
else 
{
	//$AdsInfo[] = $Ads;
	if($Ads['IA_Ads_AdvertiserID'] == $AdvertiserID) 
	{ $AdsInfo[] = $Ads; }
}
print("<pre>AdsInfo". print_r($AdsInfo,true) ."</pre>");
*/




/*
$path_parts = pathinfo($_SERVER['PHP_SELF']);
print_r($path_parts);
echo $path_parts['dirname'];
echo $path_parts['basename'];
echo $path_parts['extension'];
echo $path_parts['filename'];
*/
/*
echo 'Self:'.DIRECTORY.'<br />';
echo ROOT.'<br />';
$Dir = ROOT.'/users/'.$UserInfo['UserParentID'];
*/
/*
//print("<pre>". print_r($UserInfo,true) ."</pre>");

$AdInfo['IA_Ads_AdLibraryID'] = 1;
$AdInfo['IA_Ads_PanelsID'] = 2;
$AdInfo['IA_Ads_PanelSectionID'] = 1;
$AdInfo['IA_Ads_AccountID'] = 4;

$AdInfo['AccountDropdownRequired'] = 795;
$AdInfo['PanelLocationDropdown'] = '9648-533-4-1697';

//print("<pre>Ad:". print_r($Advertisements->UpdatePanelAdXML($UserInfo, $AdInfo),true) ."</pre>");

$XML = new DOMDocument('1.0', 'UTF-8');
$XML->preserveWhiteSpace = false;
$XML->formatOutput = true;
$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
$xPath = new DOMXpath($XML);


$PanelInfo = explode("-", '9648-533-4-1697');
echo 'test:'. $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="795"]/Panels/Areas[@id="'.$PanelInfo[1].'"]/Rooms[@id="'.$PanelInfo[2].'"]/Walls[@id="'.$PanelInfo[3].'"]/Panel[@id="'.$PanelInfo[0].'"]/Ads/Ad/IA_Ads_PanelSectionID')->item(0)->nodeValue;


		
$PanelInfo = explode("-", '9648-533-4-1697');

$AdsColumns = mysql_query("SHOW COLUMNS FROM IA_Ads", CONN);
while ($AdsColumn = mysql_fetch_assoc($AdsColumns))
{
	$Columns[$AdsColumn['Field']] = null;
}

$Columns['IA_Ads_AdLibraryID'] = 'test';
foreach ($Columns as $c => $v)
{
	echo $c.'='.$v.'<br />';
}

$AdsNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="795"]/Panels/Areas[@id="'.$PanelInfo[1].'"]/Rooms[@id="'.$PanelInfo[2].'"]/Walls[@id="'.$PanelInfo[3].'"]/Panel[@id="'.$PanelInfo[0].'"]/Ads/Ad/IA_Ads_PanelSectionID');
foreach ($AdsNode as $Node) 
{
	echo $Node->parentNode->getAttribute('id') .'-';
}

$AdsNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="795"]/Panels/Areas[@id="'.$PanelInfo[1].'"]/Rooms[@id="'.$PanelInfo[2].'"]/Walls[@id="'.$PanelInfo[3].'"]/Panel[@id="'.$PanelInfo[0].'"]/Ads/Ad');
foreach ($AdsNode as $AdNode) 
{
	foreach ($AdNode->childNodes as $Node) 
	{
		echo '<br />'.$Node->nodeName.'='.$Node->nodeValue;
	}
}
*/



/*
$Panels = mysql_query("SELECT * FROM IA_Panels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_AdPanels WHERE IA_Panels_ID=9602 AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID", CONN);
while($Panel = mysql_fetch_assoc($Panels))
{
	$PanelsNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels');
	if($xPath->evaluate('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]')->length == 0) 
	{
		foreach ($PanelsNode as $Node) 
		{
			$NewNode = $XML->createElement("Areas");
	 		$NewNode = $Node->appendChild($NewNode);
			$NewNode->setAttribute("id", $Panel['IA_LocationAreas_ID']);
			foreach($Panel as $Name => $Value)
			{
				if(preg_match('/IA_LocationAreas/', $Name))
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $NewNode->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}
			}
		}
	}

	$AreasNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]');
	if($xPath->evaluate('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]/Rooms[@id="'.$Panel['IA_LocationRooms_ID'].'"]')->length == 0) 
	{
		foreach ($AreasNode as $Node) 
		{
			$NewNode = $XML->createElement("Rooms");
	 		$NewNode = $Node->appendChild($NewNode);
			$NewNode->setAttribute("id", $Panel['IA_LocationRooms_ID']);
			foreach($Panel as $Name => $Value)
			{
				if(preg_match('/IA_LocationRooms/', $Name))
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $NewNode->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}
			}
		}
	}

	$RoomsNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]/Rooms[@id="'.$Panel['IA_LocationRooms_ID'].'"]');
	if($xPath->evaluate('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]/Rooms[@id="'.$Panel['IA_LocationRooms_ID'].'"]/Walls[@id="'.$Panel['IA_AdLocations_ID'].'"]')->length == 0) 
	{
		foreach ($RoomsNode as $Node) 
		{
			$NewNode = $XML->createElement("Walls");
	 		$NewNode = $Node->appendChild($NewNode);
			$NewNode->setAttribute("id", $Panel['IA_AdLocations_ID']);
			foreach($Panel as $Name => $Value)
			{
				if(preg_match('/IA_AdLocations/', $Name))
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $NewNode->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}
			}
		}
	}
	
	$WallsNode = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]/Rooms[@id="'.$Panel['IA_LocationRooms_ID'].'"]/Walls[@id="'.$Panel['IA_AdLocations_ID'].'"]');
	if($xPath->evaluate('/Data/State/Regions/Region/Locations/Location[@id="'.$Panel['IA_Panels_AccountID'].'"]/Panels/Areas[@id="'.$Panel['IA_LocationAreas_ID'].'"]/Rooms[@id="'.$Panel['IA_LocationRooms_ID'].'"]/Walls[@id="'.$Panel['IA_AdLocations_ID'].'"]/Panel[@id="'.$Panel['IA_Panels_ID'].'"]')->length == 0) 
	{
		foreach ($WallsNode as $Node) 
		{
			$NewNode = $XML->createElement("Panel");
	 		$NewNode = $Node->appendChild($NewNode);
			$NewNode->setAttribute("id", $Panel['IA_Panels_ID']);
			foreach($Panel as $Name => $Value)
			{
				if(preg_match('/IA_Panels/', $Name) || preg_match('/IA_AdPanels/', $Name))
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $NewNode->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}
			}
		}
	}
	break;
}

print_r($XML->saveXML());
*/





/*
$XML = new DOMDocument('1.0', 'UTF-8');
$XML->preserveWhiteSpace = false;
$XML->formatOutput = true;
$Root = $XML->createElement('Data');
$Root = $XML->appendChild($Root);

$Data = new _Data();

$XMLFragment = new DOMDocument('1.0', 'UTF-8');
$XMLFragment->loadXML($Data->GetStates(49));
*/
//$Fragment = $XMLFragment->getElementsByTagName('State');
/*
foreach ($XMLFragment->childNodes as $Node => $NodeValue)
{
	$ParentState = $XML->createElement($NodeValue->nodeName);
	$ParentState = $Root->appendChild($ParentState);
	$ParentState->setAttribute("id", $NodeValue->getElementsByTagName('IA_States_ID')->item(0)->nodeValue);
	
	
	foreach ($NodeValue->childNodes as $Name => $Value)
	{
		if(strpos($Value->nodeName, '#') === false) 
		{
			$NodeName = $XML->createElement($Value->nodeName);
			$NodeName = $ParentState->appendChild($NodeName);
			$NodeValue = $XML->createTextNode($Value->nodeValue);
			$NodeValue = $NodeName->appendChild($NodeValue);
		}
	}
}
*/
/*
$States = simplexml_load_string($Data->GetStates());
$States = $States->xpath('/Data/State');
$States = json_decode(json_encode($States),true);
//print("<pre>". print_r($State,true) ."</pre>");

for($s=0; $s<count($States); $s++) 
{
	foreach (array_filter($States[$s]) as $Name => $Value)
	{
		if($Name == '@attributes') 
		{
			$ParentState = $XML->createElement('State');
			$ParentState = $Root->appendChild($ParentState);
			$ParentState->setAttribute("id", $Value['id']);
		}
		else 
		{
			$NodeName = $XML->createElement($Name);
			$NodeName = $ParentState->appendChild($NodeName);
			$NodeValue = $XML->createTextNode($Value);
			$NodeValue = $NodeName->appendChild($NodeValue);
		}
	}
	$ParentRegion = $XML->createElement('Regions');
	$ParentRegion = $ParentState->appendChild($ParentRegion);
}

$Region = simplexml_load_string($Data->GetRegion(61));
$Region = $Region->xpath('/Region');
$Region = json_decode(json_encode($Region),true);
print("<pre>". print_r($Region,true) ."</pre>");

echo '<br /><br />';
//echo (string) $Data->GetRegion(61)->saveXML();
echo '<br /><br />';
//echo (string) $Data->GetLocation(795)->saveXML();

echo $XML->saveXML();
*/
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

//// Gets User's Advertiser and Advertiser Pricing (Can be Advertiser specific)
//print("<pre>". print_r($Advertisers->GetAdvertisers($_SESSION['UserParentID'], null),true) ."</pre>");
//// Gets User Info
//print("<pre>". print_r($UserInfo,true) ."</pre>");
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