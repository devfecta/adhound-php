<?php
// Reports
	class _Reports
	{
		public function ClientAdListing($UserInfo, $AdvertiserID, $ModeType)
		{
			switch ($ModeType)
			{
				case 'ViewLocations':
					$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Ads_AdvertiserID=IA_Advertisers_ID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_Archived=0 ORDER BY IA_Ads_StartDate DESC", CONN);
					$AdvertisementCount = mysql_num_rows($AccountsInfo);
					if ($AdvertisementCount > 0)
					{
						//echo '<tr><td style="vertical-align:top; text-align:center">';
						echo '<table border="0" align="center" style="background-color:#ffffff; width:90%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
						
						$AdsInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_ID=".$AdvertiserID, CONN);
						while ($AdInfo = mysql_fetch_assoc($AdsInfo))
						{
							echo '<tr>';
							echo '<td style="vertical-align:top;">';
							
							echo '<h2>'.$AdInfo[IA_Advertisers_BusinessName].'</h2>';
							echo '<p>'.$AdInfo[IA_Advertisers_Address].'<br />';
							echo $AdInfo[IA_Advertisers_City].', ';
							$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$AdInfo[IA_Advertisers_StateID], CONN);
							while ($State = mysql_fetch_assoc($States))
							{
								echo $State[IA_States_Abbreviation];
							}
							echo ' '.$AdInfo[IA_Advertisers_Zipcode].'<br /><br />';
							//IA_Advertisers_BusinessName 	IA_Advertisers_FirstName 	IA_Advertisers_LastName IA_Advertisers_Phone 	IA_Advertisers_Fax
							echo '<b>Phone:</b> '.$AdInfo[IA_Advertisers_Phone].'<br />';
							echo '<b>Fax:</b> '.$AdInfo[IA_Advertisers_Fax].'<br />';
							echo '<b>e-Mail:</b> <a href="mailto:'.$AdInfo[IA_Advertisers_Email].'">'.$AdInfo[IA_Advertisers_Email].'</a></p>';
							echo '</td>';
							echo '<td style="font-size:14px; vertical-align:top;" colspan="2">';
							
							$AdLocations = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Ads_Archived=0 GROUP BY IA_Ads_AccountID", CONN);
							echo '<b>Total Locations:</b> '. mysql_num_rows($AdLocations) .'<br />';
							echo '<b>Total Ads Placed:</b> '.$AdvertisementCount;
							// List Different Ad Types
							echo '<ul>';
							$AdTypes = mysql_query("SELECT * FROM IA_Ads, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 GROUP BY IA_Ads_TypeID ORDER BY IA_AdTypes_Name ASC", CONN);
							while ($AdType = mysql_fetch_assoc($AdTypes))
							{
								$Ads = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Ads_TypeID=".$AdType[IA_AdTypes_ID]." AND IA_Ads_Archived=0", CONN);
								echo '<li><b>'.$AdType[IA_AdTypes_Name].' Ad(s):</b> '. mysql_num_rows($Ads) .'</li>';
							}
							echo '</ul>';
							echo '</td>';
							echo '</tr>';
						}
						
						
						echo '<tr style="vertical-align:middle; border-bottom:1px solid #cccccc">';
						echo '<td style="width:20%; border-bottom:1px solid #cccccc">Location</td>';
						echo '<td style="width:20%; border-bottom:1px solid #cccccc" colspan="2">Ad &amp; Panel Information</td>';
						echo '</tr>';
						while ($AccountInfo = mysql_fetch_assoc($AccountsInfo))
						{
							// IA_Accounts_BusinessName  	IA_Accounts_FirstName 	IA_Accounts_LastName 	IA_Accounts_Address 	IA_Accounts_City 	IA_Accounts_StateID 	IA_Accounts_Zipcode 	IA_Accounts_Phone 	IA_Accounts_Fax 	IA_Accounts_Email
							if ($RowCount == 0)
							{
								echo '<tr style="vertical-align:middle;">';
								$RowCount = 1;
							}
							else
							{
								echo '<tr style="background-color:#eeeeee; vertical-align:middle;">';
								$RowCount = 0;
							}
							echo '<td>';
							echo '<h3>'.$AccountInfo['IA_Accounts_BusinessName'].'</h3>';
							echo $AccountInfo['IA_Accounts_Address'].'<br />';
							echo $AccountInfo['IA_Accounts_City'].', ';
							$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$AccountInfo['IA_Accounts_StateID'], CONN);
							while ($State = mysql_fetch_assoc($States))
							{
								echo $State['IA_States_Abbreviation'];
							}
							echo ' '.$AccountInfo['IA_Accounts_Zipcode'];
							echo '</td>';
							echo '<td>';
							
							$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_ID=".$AccountInfo['IA_Ads_TypeID'], CONN);
							while ($AdType = mysql_fetch_assoc($AdTypes))
							{
								echo '<b>Ad Type:</b> '.$AdType['IA_AdTypes_Name'].'<br />';
							}
							echo '<b>Ad Dimensions:</b> '.$AccountInfo['IA_AdLibrary_Width'].'" x '.$AccountInfo['IA_AdLibrary_Height'].'"<br />';
							echo '<b>Start Date:</b> '.$AccountInfo['IA_Ads_StartDate'].'<br />';
							echo '<b>Expiration Date:</b> '.$AccountInfo['IA_Ads_ExpirationDate'].'<br />';
							
							$AccountPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountInfo[IA_Ads_AccountID]." AND IA_Panels_PanelID=".$AccountInfo[IA_Ads_PanelID]." AND IA_Panels_LocationID=".$AccountInfo[IA_Ads_LocationID]." AND IA_AdPanels_ID=IA_Panels_PanelID AND IA_AdLocations_ID=IA_Panels_LocationID", CONN);
							$Panels = new _Panels();
							while ($AccountPanel = mysql_fetch_assoc($AccountPanels))
							{
								echo '<b>'.$AccountPanel['IA_AdLocations_Location'].'\'s Panel: </b>'.$AccountPanel['IA_AdPanels_Name'].'<br />';
								
							}
							echo '</td>';
							echo '<td>';
							
							$SectionHeight = number_format((($AccountInfo["IA_AdLibrary_Height"] * 72) * .1), 0, '.', '');
							$SectionWidth = number_format((($AccountInfo["IA_AdLibrary_Width"] * 72) * .1), 0, '.', '');
							$AdHeight = $SectionHeight;
							$AdWidth = $SectionWidth;
							echo "\n".'<img id="Ad'.$AccountInfo['IA_Ads_ID'].'" name="Ad'.$AccountInfo['IA_Ads_ID'].'" onclick="" src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AccountInfo['IA_AdLibrary_ID'].'.jpg" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
							/*
							// Panel View Start
							$AccountPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountInfo[IA_Ads_AccountID]." AND IA_Panels_PanelID=".$AccountInfo[IA_Ads_PanelID]." AND IA_Panels_LocationID=".$AccountInfo[IA_Ads_LocationID]." AND IA_AdPanels_ID=IA_Panels_PanelID AND IA_AdLocations_ID=IA_Panels_LocationID", CONN);
							$Panels = new _Panels();
							while ($AccountPanel = mysql_fetch_assoc($AccountPanels))
							{
								echo '<b>'.$AccountPanel['IA_AdLocations_Location'].'\'s Panel: </b>'.$AccountPanel['IA_AdPanels_Name'].'<br />';
								echo 'Section: '.$AccountInfo['IA_Ads_PanelSectionID'].' - '.$AccountInfo['IA_AdLibrary_Width'].'" x '.$AccountInfo['IA_AdLibrary_Height'].'"<br />';
							
								echo $Panels->BuildPanel($_SESSION['UserType'], $AccountPanel['IA_Panels_ID'], $AccountInfo['IA_Ads_ID'], 'ImageOnly', .04);
							}
							// Panel View End
							*/
							echo '</td>';
							echo '</tr>';
						}
						echo '</table>';
						//echo '</td></tr>';
					}
					else 
					{
						echo '<div style="text-align:center;"><i>You Have No Advertisements For This Location</i></div>';
						//echo '<tr><td style="height:30px; text-align:center; vertical-align:middle"><i>You Have No Placed Advertisements</i></td></tr>';
					}
					break;
				default:
					$AdsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_Ads, IA_Advertisers WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Advertisers_ID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
					$AdvertisementCount = mysql_num_rows($AdsInfo);
					if ($AdvertisementCount > 0)
					{
						echo '<tr><td style="vertical-align:top; text-align:center">';
						echo '<table border="0" align="center" style="background-color:#ffffff; width:95%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
						echo '<tr>';
						echo '<td style="vertical-align:middle; border-bottom:1px solid #cccccc; width:30%">';
						echo 'Advertisers';
						echo '</td>';
						echo '<td style="vertical-align:middle; border-bottom:1px solid #cccccc; width:25%">';
						echo '# of Ad Placements';
						echo '</td>';
						echo '<td style="vertical-align:middle; text-align:right; border-bottom:1px solid #cccccc; width:10%">';
						echo 'Overall Cost';
						echo '</td>';
						echo '<td style="vertical-align:middle; text-align:right; border-bottom:1px solid #cccccc; width:15%">';
						echo 'Overall Payments';
						echo '</td>';
						echo '<td style="vertical-align:middle; text-align:right; border-bottom:1px solid #cccccc; width:20%" colspan="2">';
						echo 'Overall Outstanding Balance';
						echo '</td>';
						echo '</tr>';
						
						while ($AdInfo = mysql_fetch_assoc($AdsInfo))
						{
							if ($RowCount == 0)
							{
								echo '<tr style="vertical-align:middle;">';
								$RowCount = 1;
							}
							else
							{
								echo '<tr style="background-color:#eeeeee; vertical-align:middle;">';
								$RowCount = 0;
							}
							echo '<td style="vertical-align:middle">';
							echo '<h2>'.$AdInfo[IA_Advertisers_BusinessName].'</h2>';
							echo '<a href="reports.php?ReportType=ClientAdListing+'.$UserInfo['Users_ID'].'&AdvertiserID='.$AdInfo[IA_Advertisers_ID].'&ModeType=ViewLocations">[View Locations]</a>';
							//echo '<a href="reports.php?ReportType=AdLibrary+'.$_SESSION['UserID'].'&AdvertiserID='.$AdInfo[IA_Advertisers_ID].'&ModeType=AdLibrary">[View Ad Library]</a>';
							//ReportType=AdLibrary+1&AdvertiserID=4&ModeType=ViewAds
							echo '</td>';
							echo '<td style="vertical-align:middle">';
								$AdCount = mysql_query("SELECT IA_Ads_ID FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$AdInfo[IA_Advertisers_ID]." AND IA_Ads_Archived=0", CONN);
								echo mysql_num_rows($AdCount).' Placed Advertisement(s)';
							/*
							$AdPanelSections = mysql_query("SELECT * FROM IA_AdPanelSections, IA_AdPanels WHERE IA_AdPanelSections.IA_AdPanelSections_ID=".$AdInfo[IA_Ads_PanelSectionID]." AND IA_AdPanels.IA_AdPanels_ID=".$AdInfo[IA_Ads_PanelID], CONN);
							while ($AdPanelSection = mysql_fetch_assoc($AdPanelSections))
							{
								echo '<b>Panel: </b>'.$AdPanelSection[IA_AdPanels_Name].'<br />';
								echo $AdPanelSection[IA_AdPanelSections_Name].' '.$AdPanelSection[IA_AdPanelSections_Width].'" x '.$AdPanelSection[IA_AdPanelSections_Height].'"';
							}
							*/
							echo '</td>';
							echo '<td style="vertical-align:middle; text-align:right">';
								$AdCostsInfo = mysql_query("SELECT IA_Ads_Cost FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$AdInfo[IA_Advertisers_ID]." AND IA_Ads_Archived=0", CONN);
								$AdsTotalCost = 0;
								while ($AdCostInfo = mysql_fetch_assoc($AdCostsInfo))
								{
									$AdsTotalCost = $AdsTotalCost + $AdCostInfo[IA_Ads_Cost];
								}
								echo '$'.number_format($AdsTotalCost, 2, '.', ',');
							echo '</td>';
							echo '<td style="vertical-align:middle; text-align:right">';
								$AdPaymentsInfo = mysql_query("SELECT IA_Ads_ID FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$AdInfo[IA_Advertisers_ID]." AND IA_Ads_Archived=0", CONN);
								$AdOverallPaymentTotal = 0;
								while ($AdPaymentInfo = mysql_fetch_assoc($AdPaymentsInfo))
								{
									$Advertisements = new _Advertisements();
									if ($Advertisements->CalculateAdPayments($AdPaymentInfo[IA_Ads_ID]))
									{
										//echo '$'.$Advertisements->AdPayments;
										//echo '$'.number_format($Advertisements->AdPayments, 2, '.', ',');
										//$AdPaymentTotal = $Advertisements->AdPayments;
										$AdOverallPaymentTotal = $AdOverallPaymentTotal + $Advertisements->AdPayments;
									}	
								}
								echo '$'.number_format($AdOverallPaymentTotal, 2, '.', ',');
							echo '</td>';
							
							echo '<td style="width:10%; vertical-align:middle; text-align:right">';
							echo '$'.number_format(($AdsTotalCost - $AdOverallPaymentTotal), 2, '.', ',');
							echo '</td><td style="width:10%; vertical-align:middle; text-align:right">';
							switch($UserInfo['Users_Type']) 
							{
								case 0:
									break;
								default:
									echo ' <a href="billing.php?AdvertiserID='.$AdInfo[IA_Advertisers_ID].'&ModeType=ViewBill">[View Billing]</a>';
									break;
							}
							echo '</td>';
							
							echo '</tr>';
						}
						echo '</table>';
						echo '</td></tr>';
					}
					else
					{
						echo '<tr><td colspan="2" style="height:30px; text-align:center; vertical-align:middle"><i>You Have No Advertisements</i></td></tr>';
					}
					break;
			}
		}

		public function ProofOfPerformance($UserID, $AdvertiserID, $StartDate, $EndDate)
		{
			$XML = new DOMDocument();
			if(file_exists(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ 
				$Advertisers = new _Advertisers();
				$Advertisers->GetAdvertisers($UserID, null);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml');
			$Advertiser = json_decode(json_encode($XML),true);
			
			if(isset($Advertiser['Advertiser'][0])) 
			{
				for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
				{
					if($Advertiser['Advertiser'][$a]['IA_Advertisers_ID'] == $AdvertiserID) 
					{
						$AdvertiserInfo = $Advertiser['Advertiser'][$a];
						break;
					}
					
				}
			}
			else 
			{ $AdvertiserInfo = $Advertiser['Advertiser']; }
			if($AdvertiserInfo['IA_Advertisers_DateDependent'] == 1) 
			{
				$AdvertiserStartDate = $AdvertiserInfo['IA_Advertisers_StartDate'];
				$AdvertiserEndDate = $AdvertiserInfo['IA_Advertisers_ExpirationDate'];
			}
			else 
			{
				$AdvertiserStartDate = $StartDate;
				$AdvertiserEndDate = $EndDate;
			}
//print("<pre>". print_r($AdvertiserInfo,true) ."</pre>");
			// Get All Used Advertiser Ads
			$Data = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
			$AdsXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');
			$AdsXML = json_decode(json_encode($AdsXML),true);
			$OverallAdsCount = 0;
			if(isset($AdsXML[0])) 
			{
				for($a=0; $a<count($AdsXML); $a++) 
				{
					
					if($AdsXML[$a]['IA_Ads_AdvertiserID'] == $AdvertiserInfo['IA_Advertisers_ID'] && $Ads[$a]['IA_Ads_Archived'] == 0) 
					{
						$Ads[] = $AdsXML[$a];
						if($StartDate != null && $EndDate != null) 
						{
							/* Add back when preferences are done
							if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Ads_StartDate'], $Ads[$a]['IA_Ads_ExpirationDate']))
							{ $OverallAdsCount++; }
							*/
							if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
							{ $OverallAdsCount++; }
						}
						else 
						{ $OverallAdsCount++; }
					}
				}
			}
			else 
			{
				if($AdsXML['IA_Ads_AdvertiserID'] == $AdvertiserInfo['IA_Advertisers_ID'] && $Ads[$a]['IA_Ads_Archived'] == 0) 
				{
					$Ads[] = $AdsXML;
					if($StartDate != null && $EndDate != null) 
					{
						/* Add back when preferences are done
						if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Ads_StartDate'], $Ads[$a]['IA_Ads_ExpirationDate']))
						{ $OverallAdsCount++; }
						*/
						if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
						{ $OverallAdsCount++; }
					}
					else 
					{ $OverallAdsCount++; }
				}
			}		
/*
			for($a=0; $a<count($AdvertiserInfo); $a++) 
			{
				if(file_exists(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_'.$AdvertiserInfo['IA_Advertisers_ID'].'_AdsInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisements = new _Advertisements();
					$Advertisements->GetAds($UserID, $AdvertiserInfo['IA_Advertisers_ID']);
				}
				$AdsXML = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_'.$AdvertiserInfo['IA_Advertisers_ID'].'_AdsInfo.xml');
				$Ad = json_decode(json_encode($AdsXML),true);
//echo 'Count:'.count($Ad);
				if(isset($Ad['Ad'][0])) 
				{
					for($a=0; $a<count($Ad['Ad']); $a++) 
					{ $Ads[] = $Ad['Ad'][$a]; }
				}
				else 
				{ $Ads[] = $Ad['Ad']; }
				
				if(count($Ad) == 0) 
				{
					$Ads = null;
				}
				
				break;
			}
*/
//print("<pre>". print_r($Ads,true) ."</pre>");
			$this->POPReportRowsArray = $AdvertiserInfo;
			$this->POPReport = '<div style="display:block; clear:both; max-height:100%; clear:left; margin-top:15px; height:30px; vertical-align:middle; font-weight:bold; font-style:italic; font-size:14px">';
			if($StartDate != null && $EndDate != null) 
			{ $ReportDate = 'Ad Counts for '. date('F j, Y', strtotime($StartDate)) .' to '. date('F j, Y', strtotime($EndDate)) .'.'; }
			else 
			{ $ReportDate = 'Ad Counts for all ads.'; }
		
			$this->POPReport .= $ReportDate;
			$this->POPReportRowsArray['ReportDate'] = $ReportDate;
			
			$this->POPReport .= ' <input type="button" onclick="window.location=\'configuration/export.php?ReportType=ProofOfPerformanceReport&UserID='.$UserID.'&ReportView=Account&AdvertiserID='.$AdvertiserInfo['IA_Advertisers_ID'].'&StartDate='.$StartDate.'&EndDate='.$EndDate.'&SaveReport=true\'" id="SaveReport" name="SaveReport" value="Save Report">';
			$this->POPReport .= ' <input type="button" onclick="window.location=\'configuration/export.php?ReportType=ProofOfPerformanceReport&UserID='.$UserID.'&ReportView=Account&AdvertiserID='.$AdvertiserInfo['IA_Advertisers_ID'].'&StartDate='.$StartDate.'&EndDate='.$EndDate.'&SaveReport=false\'" id="ExportReport" name="ExportReport" value="Export Report">';
			$this->POPReport .= '</div>'."\n";

			$this->POPReport .= '<div style="margin:5px 10px 5px 10px; min-height:100%; display:inline; float:left; vertical-align:top; font-weight:normal; font-size:12px">';
			$this->POPReport .= '<b>Overall Ads:</b> ';
/*
			$OverallAdsCount = 0;
			//print("<pre>". print_r($Ads, true) ."</pre>");
			for($a=0; $a<count($Ads); $a++) 
			{
				if($Ads[$a]['IA_Ads_Archived'] == 0) 
				{
					if($StartDate != null && $EndDate != null) 
					{
						// Add back when preferences are done
						//if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Ads_StartDate'], $Ads[$a]['IA_Ads_ExpirationDate']))
						//{ $OverallAdsCount++; }
						
						if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
						{ $OverallAdsCount++; }
					}
					else 
					{ $OverallAdsCount++; }
				}
			}
*/
			$OverallAdsCount = $OverallAdsCount.' Ad(s)'."\n";
			$this->POPReport .= $OverallAdsCount;
			$this->POPReportRowsArray['OverallAdsCount'] = $OverallAdsCount;
			$this->POPReport .= '</div>'."\n";
			
			$this->POPReport .= '<div style="margin:5px 10px 5px 10px; float:left; display:inline; min-height:100%; vertical-align:top; font-weight:normal; font-size:12px">'."\n";
			$this->POPReport .= '<b>Overall Sizes:</b>';
			$this->POPReport .= '<ul>';
			$AdSizesArray = array();
			$AdSizesArrayKey = 0;
			$AdAccountsArray = array();
			$AdAccountsArrayKey = 0;
			$AdLocationsArray = array();
			$AdLocationsArrayKey = 0;
//print("<pre>Ads". print_r($Ads,true) ."</pre>");
			for($a=0; $a<count($Ads); $a++) 
			{
				if($Ads[$a]['IA_Ads_Archived'] == 0) 
				{
					$NewSize = false;
					if(count($AdSizesArray) > 0) 
					{
						for($s=0; $s<count($AdSizesArray); $s++) 
						{
							if(($AdSizesArray[$s]['IA_AdLibrary_Width'] .'x'. $AdSizesArray[$s]['IA_AdLibrary_Height']) == ($Ads[$a]['IA_AdLibrary_Width'] .'x'. $Ads[$a]['IA_AdLibrary_Height'])) 
							{ $NewSize = false; break; }
							else 
							{ $NewSize = true; }
						}
					}
					else 
					{ $NewSize = true; }
					
					if($NewSize) 
					{
						$AdSizesArray[$AdSizesArrayKey]['IA_AdLibrary_Width'] = $Ads[$a]['IA_AdLibrary_Width'];
						$AdSizesArray[$AdSizesArrayKey]['IA_AdLibrary_Height'] = $Ads[$a]['IA_AdLibrary_Height'];
						$AdSizesArrayKey++;
					}
					$NewAccount = false;
					if(count($AdAccountsArray) > 0) 
					{
						
						for($x=0; $x<count($AdAccountsArray); $x++) 
						{
							if($AdAccountsArray[$x]['IA_Ads_AccountID'] == $Ads[$a]['IA_Ads_AccountID']) 
							{ $NewAccount = false; break; }
							else 
							{ $NewAccount = true; }
						}
					}
					else 
					{ $NewAccount = true; }
					
					if($NewAccount) 
					{
						$AccountsXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$Ads[$a]['IA_Ads_AccountID'].'"]');
						$AccountsXML = json_decode(json_encode($AccountsXML[0]),true);
//print("<pre>AccountsXML". print_r($AccountsXML,true) ."</pre>");
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Ads_AccountID'] = $AccountsXML['IA_Accounts_ID'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_BusinessName'] = $AccountsXML['IA_Accounts_BusinessName'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_Address'] = $AccountsXML['IA_Accounts_Address'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_City'] = $AccountsXML['IA_Accounts_City'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_States_Abbreviation'] = $AccountsXML['IA_States_Abbreviation'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_Zipcode'] = $AccountsXML['IA_Accounts_Zipcode'];
						
						/*
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Ads_AccountID'] = $Ads[$a]['IA_Ads_AccountID'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_BusinessName'] = $Ads[$a]['Account']['IA_Accounts_BusinessName'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_Address'] = $Ads[$a]['Account']['IA_Accounts_Address'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_City'] = $Ads[$a]['Account']['IA_Accounts_City'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_States_Abbreviation'] = $Ads[$a]['Account']['IA_States_Abbreviation'];
						$AdAccountsArray[$AdAccountsArrayKey]['IA_Accounts_Zipcode'] = $Ads[$a]['Account']['IA_Accounts_Zipcode'];
						*/
						$AdAccountsArrayKey++;
					}
					
					$NewLocation = false;
					if(count($AdLocationsArray) > 0) 
					{
						for($l=0; $l<count($AdLocationsArray); $l++) 
						{
							if($AdLocationsArray[$l]['IA_LocationRooms_ID'] == $Ads[$a]['IA_LocationRooms_ID'] && $AdLocationsArray[$l]['IA_Ads_AccountID'] == $Ads[$a]['IA_Ads_AccountID']) 
							{ $NewLocation = false; break; }
							else 
							{ $NewLocation = true; }
						}
					}
					else 
					{ $NewLocation = true; }
					
					if($NewLocation) 
					{
						$PanelsXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$Ads[$a]['IA_Ads_AccountID'].'"]/Panels/Areas/Rooms/Walls/Panel[@id="'.$Ads[$a]['IA_Ads_PanelsID'].'"]');
						$PanelsXML = json_decode(json_encode($PanelsXML[0]),true);
//print("<pre>PanelsXML". print_r($PanelsXML,true) ."</pre>");
						$AreasXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$Ads[$a]['IA_Ads_AccountID'].'"]/Panels/Areas[@id="'.$PanelsXML['IA_Panels_AreaID'].'"]');
						$AreasXML = json_decode(json_encode($AreasXML[0]),true);
						$RoomsXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$Ads[$a]['IA_Ads_AccountID'].'"]/Panels/Areas/Rooms[@id="'.$PanelsXML['IA_Panels_RoomID'].'"]');
						$RoomsXML = json_decode(json_encode($RoomsXML[0]),true);
						$WallsXML = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$Ads[$a]['IA_Ads_AccountID'].'"]/Panels/Areas/Rooms/Walls[@id="'.$PanelsXML['IA_Panels_LocationID'].'"]');
						$WallsXML = json_decode(json_encode($WallsXML[0]),true);
						
						$Ads[$a]['IA_LocationAreas_ID'] = $AreasXML['IA_LocationAreas_ID'];
						$Ads[$a]['IA_LocationAreas_Area'] = $AreasXML['IA_LocationAreas_Area'];
						$Ads[$a]['IA_LocationRooms_ID'] = $RoomsXML['IA_LocationRooms_ID'];
						$Ads[$a]['IA_LocationRooms_Room'] = $RoomsXML['IA_LocationRooms_Room'];
						$Ads[$a]['IA_AdLocations_ID'] = $WallsXML['IA_AdLocations_ID'];
						$Ads[$a]['IA_AdLocations_Location'] = $WallsXML['IA_AdLocations_Location'];
						
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_Ads_AccountID'] = $Ads[$a]['IA_Ads_AccountID'];
						//$AdLocationsArray[$AdLocationsArrayKey]['IA_Accounts_BusinessName'] = $Ads[$a]['IA_Accounts_BusinessName'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_Ads_AccountID'] = $Ads[$a]['IA_Ads_AccountID'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_Accounts_BusinessName'] = $Ads[$a]['IA_Accounts_BusinessName'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_LocationAreas_ID'] = $Ads[$a]['IA_LocationAreas_ID'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_LocationAreas_Area'] = $Ads[$a]['IA_LocationAreas_Area'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_LocationRooms_ID'] = $Ads[$a]['IA_LocationRooms_ID'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_LocationRooms_Room'] = $Ads[$a]['IA_LocationRooms_Room'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_AdLocations_ID'] = $Ads[$a]['IA_AdLocations_ID'];
						$AdLocationsArray[$PanelsXML['IA_Panels_ID']]['IA_AdLocations_Location'] = $Ads[$a]['IA_AdLocations_Location'];
						
						/*
						$AdLocationsArray[$AdLocationsArrayKey]['IA_Ads_AccountID'] = $Ads[$a]['IA_Ads_AccountID'];
						//$AdLocationsArray[$AdLocationsArrayKey]['IA_Accounts_BusinessName'] = $Ads[$a]['IA_Accounts_BusinessName'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_Ads_AccountID'] = $Ads[$a]['IA_Ads_AccountID'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_Accounts_BusinessName'] = $Ads[$a]['IA_Accounts_BusinessName'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_LocationAreas_ID'] = $Ads[$a]['IA_LocationAreas_ID'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_LocationAreas_Area'] = $Ads[$a]['IA_LocationAreas_Area'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_LocationRooms_ID'] = $Ads[$a]['IA_LocationRooms_ID'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_LocationRooms_Room'] = $Ads[$a]['IA_LocationRooms_Room'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_AdLocations_ID'] = $Ads[$a]['IA_AdLocations_ID'];
						$AdLocationsArray[$AdLocationsArrayKey]['IA_AdLocations_Location'] = $Ads[$a]['IA_AdLocations_Location'];
						
						$AdLocationsArrayKey++;
						*/
					}
				}
			}
			$AdLocationsArray = array_values($AdLocationsArray);
//print("<pre>AdLocationsArray". print_r($AdLocationsArray,true) ."</pre>");
//print("<pre>AdSizesArray". print_r($AdSizesArray,true) ."</pre>");
			
			for($s=0; $s<count($AdSizesArray); $s++) 
			{
				$AdCount = 0;
				for($a=0; $a<count($Ads); $a++) 
				{
					//if($Ads[$a]['IA_Ads_Archived'] == 0) 
					//{
						if(($AdSizesArray[$s]['IA_AdLibrary_Width'] .'x'. $AdSizesArray[$s]['IA_AdLibrary_Height']) == ($Ads[$a]['IA_AdLibrary_Width'] .'x'. $Ads[$a]['IA_AdLibrary_Height'])) 
						{
							$AdCount++;
							/*
							if($StartDate != null && $EndDate != null) 
							{
								// Add back when preferences are done
								//if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Ads_StartDate'], $Ads[$a]['IA_Ads_ExpirationDate']))
								//{ $AdCount++; }
								if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
								{ $AdCount++; }
							}
							else 
							{ $AdCount++; }
							*/
						}
						else 
						{ }
					//}
				}
				if($AdCount > 0) 
				{
					$OverallSizes = '('.$AdCount.') '.$AdSizesArray[$s]['IA_AdLibrary_Width'] .'" x '. $AdSizesArray[$s]['IA_AdLibrary_Height'].'"';
					$this->POPReport .= '<li>'.$OverallSizes.'</li>';
					$this->POPReportRowsArray['OverallSizes'][] = $OverallSizes;
				}
			}
//print("<pre>". print_r($AdSizesArray,true) ."</pre>");
//print("<pre>". print_r($AdLocationsArray,true) ."</pre>");
			$this->POPReport .= '</ul>';
			$this->POPReport .= "\n".'</div>';
			$this->POPReport .= "\n".'<div style="clear:both" />';
			
			$this->POPReport .= "\n".'<div id="POPReportLayout" name="POPReportLayout" style="overflow-x:hidden; overflow-y:hidden; background-color:#ffffff">';
			$RowWidth = 730;
			$this->POPReport .= "\n".'<div style="float:left; display:inline-block; padding-top:5px; height:25px; width:730px; border-top:1px solid #142c61">';
			$this->POPReport .= '<h2 style="font-weight:bold; font-size:12px">Ad Location(s):</h2>';
			$this->POPReportRowsArray['Report'] = 'Ad Location(s):'."\t\t\t\t\t";
			$this->POPReport .= "\n".'</div>';
			$loc = 1;
			// Creates the Ad Location header
			for($x=0; $x<count($AdAccountsArray); $x++) 
			{
				for($l=0; $l<count($AdLocationsArray); $l++) 
				{
					$LocationExists = false;
					if(empty($LocationID)) 
					{
						$LocationID[$loc] = $AdLocationsArray[$l]['IA_LocationRooms_ID'];
						$loc++;
						$this->POPReport .= "\n".'<div style="float:left; display:inline-block; padding-top:5px; height:25px; width:120px; border-top:1px solid #142c61; text-align:center">';
						$this->POPReport .= $AdLocationsArray[$l]['IA_LocationRooms_Room'];
						$this->POPReportRowsArray['Report'] .= $AdLocationsArray[$l]['IA_LocationRooms_Room']."\t";
						$this->POPReport .= "\n".'</div>';
						$RowWidth = $RowWidth + 120;
					}
					else 
					{ }
				
					foreach($LocationID as $k=>$ID)
					{
						if($AdLocationsArray[$l]['IA_LocationRooms_ID'] == $ID) 
						{
							$LocationExists = true;
							break;
						}
						else 
						{ }
					}

					if(!$LocationExists) 
					{
						$LocationID[$loc] = $AdLocationsArray[$l]['IA_LocationRooms_ID'];
						$loc++;
						$this->POPReport .= "\n".'<div style="float:left; display:inline-block; padding-top:5px; height:25px; width:120px; border-top:1px solid #142c61; text-align:center">';
						$this->POPReport .= $AdLocationsArray[$l]['IA_LocationRooms_Room'];
						$this->POPReportRowsArray['Report'] .= $AdLocationsArray[$l]['IA_LocationRooms_Room']."\t";
						$this->POPReport .= "\n".'</div>';
						$RowWidth = $RowWidth + 120;
					}
				}
			}
			// Create Ad Sizes header
			for($x=0; $x<count($AdAccountsArray); $x++) 
			{
				for($l=0; $l<count($AdSizesArray); $l++) 
				{
					//$AdSizesArray[$l]['IA_AdLibrary_Width'] => 8.5
            	//$AdSizesArray[$l]['IA_AdLibrary_Height'] => 11
					$SizeExists = false;
					if(empty($LocationID)) 
					{
						$LocationID[$loc] = $AdSizesArray[$l]['IA_AdLibrary_Width'].'x'.$AdSizesArray[$l]['IA_AdLibrary_Height'];
						$loc++;
						$this->POPReport .= "\n".'<div style="float:left; display:inline-block; padding-top:5px; height:25px; width:120px; border-top:1px solid #142c61; text-align:center">';
						$this->POPReport .= $AdSizesArray[$l]['IA_AdLibrary_Width'].' x '.$AdSizesArray[$l]['IA_AdLibrary_Height'];
						$this->POPReportRowsArray['Report'] .= $AdSizesArray[$l]['IA_AdLibrary_Width'].' x '.$AdSizesArray[$l]['IA_AdLibrary_Height']."\t";
						$this->POPReport .= "\n".'</div>';
						$RowWidth = $RowWidth + 120;
					}
					else 
					{ }
				
					foreach($LocationID as $k=>$ID)
					{
						if($AdSizesArray[$l]['IA_AdLibrary_Width'].'x'.$AdSizesArray[$l]['IA_AdLibrary_Height'] == $ID) 
						{
							$SizeExists = true;
							break;
						}
						else 
						{ }
					}

					if(!$SizeExists) 
					{
						$LocationID[$loc] = $AdSizesArray[$l]['IA_AdLibrary_Width'].'x'.$AdSizesArray[$l]['IA_AdLibrary_Height'];
						$loc++;
						$this->POPReport .= "\n".'<div style="float:left; display:inline-block; padding-top:5px; height:25px; width:120px; border-top:1px solid #142c61; text-align:center">';
						$this->POPReport .= $AdSizesArray[$l]['IA_AdLibrary_Width'].' x '.$AdSizesArray[$l]['IA_AdLibrary_Height'];
						$this->POPReportRowsArray['Report'] .= $AdSizesArray[$l]['IA_AdLibrary_Width'].' x '.$AdSizesArray[$l]['IA_AdLibrary_Height']."\t";
						$this->POPReport .= "\n".'</div>';
						$RowWidth = $RowWidth + 120;
					}
				}
			}
//print("<pre>". print_r($LocationID,true) ."</pre>");
			$this->POPReportRowsArray['Report'] .= "\n";
			$this->POPReport .= "\n".'<div style="clear:both" />';
//print("<pre>AdAccountsArray". print_r($AdAccountsArray,true) ."</pre>");
//print("<pre>AdAccountsArray". print_r($AdLocationsArray,true) ."</pre>");
			// Creates location rows
			$LocationAdOverallCount = array(null);
			for($x=0; $x<count($AdAccountsArray); $x++) 
			{
				$LocationAdCount = array(null);
				$this->POPReport .= "\n".'<div style="min-width:'.$RowWidth.'px; overflow-x:auto; overflow-y:hidden; margin:5px 10px 5px 10px; display:block; white-space:nowrap; border-top:1px solid #dddddd">';
					$this->POPReport .= "\n".'<div style="margin:5px 0px 5px 10px; width:200px; display:inline-block; float:left; white-space:nowrap">';
					$this->POPReport .= '<a href="reports.php?ReportType=RunReport+'.$AdAccountsArray[$x]['IA_Ads_AccountID'].'">';
					$this->POPReport .= !empty($AdAccountsArray[$x]['IA_Accounts_BusinessName']) ? $AdAccountsArray[$x]['IA_Accounts_BusinessName'] : null;
					$this->POPReportRowsArray['Report'] .= (!empty($AdAccountsArray[$x]['IA_Accounts_BusinessName']) ? $AdAccountsArray[$x]['IA_Accounts_BusinessName'] : null) ."\t";
					$this->POPReport .= '</a></div>';
					
					$this->POPReport .= '<div style="margin:5px 0px 5px 10px; width:200px; display:inline-block; float:left; white-space:nowrap">';
					$this->POPReport .= !empty($AdAccountsArray[$x]['IA_Accounts_Address']) ? $AdAccountsArray[$x]['IA_Accounts_Address'] : null;
					$this->POPReportRowsArray['Report'] .= (!empty($AdAccountsArray[$x]['IA_Accounts_Address']) ? $AdAccountsArray[$x]['IA_Accounts_Address'] : null) ."\t";
					$this->POPReport .= '</div>';
					$this->POPReport .= '<div style="margin:5px 0px 5px 10px; width:120px; display:inline-block; float:left; white-space:nowrap">';
					$this->POPReport .= !empty($AdAccountsArray[$x]['IA_Accounts_City']) ? $AdAccountsArray[$x]['IA_Accounts_City'] : null;
					$this->POPReportRowsArray['Report'] .= (!empty($AdAccountsArray[$x]['IA_Accounts_City']) ? $AdAccountsArray[$x]['IA_Accounts_City'] : null) ."\t";
					$this->POPReport .= '</div>';
					$this->POPReport .= '<div style="margin:5px 0px 5px 10px; width:50px; display:inline-block; float:left; white-space:nowrap">';
					$this->POPReport .= !empty($AdAccountsArray[$x]['IA_States_Abbreviation']) ? $AdAccountsArray[$x]['IA_States_Abbreviation'] : null;
					$this->POPReportRowsArray['Report'] .= (!empty($AdAccountsArray[$x]['IA_States_Abbreviation']) ? $AdAccountsArray[$x]['IA_States_Abbreviation'] : null) ."\t";
					$this->POPReport .= '</div>';
					$this->POPReport .= '<div style="margin:5px 0px 5px 10px; width:100px; display:inline-block; float:left; white-space:nowrap">';
					$this->POPReport .= !empty($AdAccountsArray[$x]['IA_Accounts_Zipcode']) ? $AdAccountsArray[$x]['IA_Accounts_Zipcode'] : null;
					$this->POPReportRowsArray['Report'] .= (!empty($AdAccountsArray[$x]['IA_Accounts_Zipcode']) ? $AdAccountsArray[$x]['IA_Accounts_Zipcode'] : null) ."\t";
					$this->POPReport .= '</div>';
					$loc = 1;
					// Counts ads by Ad Locations
					for($l=0; $l<count($AdLocationsArray); $l++) 
					{
						$AdCount = 0;
						if($AdLocationsArray[$l]['IA_Ads_AccountID'] == $AdAccountsArray[$x]['IA_Ads_AccountID']) 
						{
							for($a=0; $a<count($Ads); $a++) 
							{
								if($AdLocationsArray[$l]['IA_Ads_AccountID'] == $Ads[$a]['IA_Ads_AccountID'] && $AdLocationsArray[$l]['IA_LocationRooms_ID'] == $Ads[$a]['IA_LocationRooms_ID']) 
								{
									if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
									{ $AdCount++; }
								}
							
								//if($Ads[$a]['IA_Ads_Archived'] == 0) 
								//{
									/*
									if($AdLocationsArray[$l]['IA_Ads_AccountID'] == $Ads[$a]['IA_Ads_AccountID'] && $AdLocationsArray[$l]['IA_LocationRooms_ID'] == $Ads[$a]['IA_LocationRooms_ID']) 
									{
										if($StartDate != null && $EndDate != null) 
										{
											if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['AdDateBasedPricing']))	
											{
												if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Ads_StartDate'], $Ads[$a]['IA_Ads_ExpirationDate']))
												{ $AdCount++; }
											}
											else 
											{
												if(ValidateDateRange($StartDate, $EndDate, $Ads[$a]['IA_Advertisers_StartDate'], $Ads[$a]['IA_Advertisers_ExpirationDate']))
												{ $AdCount++; }
											}
										}
										else 
										{ $AdCount++; }
									}
									else 
									{ }
									*/
									//if($AdLocationsArray[$l]['IA_Ads_AccountID'] == $Ads[$a]['IA_Ads_AccountID'] && $AdLocationsArray[$l]['IA_LocationRooms_ID'] == $Ads[$a]['IA_LocationRooms_ID']) 
									//{
									//	if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
									//	{ $AdCount++; }
									//}
									
								//}
							}
							foreach($LocationID as $Position=>$ID)
							{
								if($AdCount > 0) 
								{
									if($AdLocationsArray[$l]['IA_LocationRooms_ID'] == $ID) 
									{
										$LocationAdCount[$Position] = $AdCount;
										$LocationAdOverallCount[$Position] = $LocationAdOverallCount[$Position] + $AdCount;
										break;
									}
								}
								else 
								{ }
							}
						}
					}
					// Counts ads by Ad Sizes and Account
					for($l=0; $l<count($AdSizesArray); $l++) 
					{
						$AdCount = 0;
						for($a=0; $a<count($Ads); $a++) 
						{
							if($Ads[$a]['IA_Ads_AccountID'] == $AdAccountsArray[$x]['IA_Ads_AccountID'] && $Ads[$a]['IA_AdLibrary_Width'].'x'.$Ads[$a]['IA_AdLibrary_Height'] == $AdSizesArray[$l]['IA_AdLibrary_Width'].'x'.$AdSizesArray[$l]['IA_AdLibrary_Height']) 
							{
								$AdCount++;
							}
						}
						foreach($LocationID as $Position=>$ID)
						{
							//if($AdCount > 0) 
							//{
								if($AdSizesArray[$l]['IA_AdLibrary_Width'].'x'.$AdSizesArray[$l]['IA_AdLibrary_Height'] == $ID) 
								{
									$LocationAdCount[$Position] = $AdCount;
									$LocationAdOverallCount[$Position] = $LocationAdOverallCount[$Position] + $AdCount;
									break;
								}
							//}
							//else 
							//{ }
						}
					}
					// Places the number of ads based on the Ad Location and Size
					foreach($LocationID as $Position=>$ID)
					{
						$this->POPReport .= '<div style="margin:5px 0px 5px 0px; text-align:center; width:120px; display:inline-block; float:left; white-space:nowrap">';
							
						if(!empty($LocationAdCount[$Position])) 
						{
							$this->POPReport .= $LocationAdCount[$Position];
							$this->POPReportRowsArray['Report'] .= $LocationAdCount[$Position]."\t";
						}
						else 
						{
							$this->POPReport .= 'No Ads';
							$this->POPReportRowsArray['Report'] .= '0'."\t";
						}
						$this->POPReport .= '</div>';
					}
				
				$this->POPReport .= '</div>';
				$this->POPReportRowsArray['Report'] .= "\n";
				$this->POPReport .= "\n".'<div style="clear:both" />';
			}
			
			$this->POPReport .= "\n".'<div style="min-width:'.$RowWidth.'px; overflow-x:auto; overflow-y:hidden; margin:5px 10px 5px 10px; display:block; white-space:nowrap; border-top:1px solid #dddddd">';
			$this->POPReport .= '<div style="text-align:right; font-weight:bold; margin:5px 0px 5px 0px; width:720px; display:inline-block; float:left; white-space:nowrap">';
			$this->POPReport .= 'Totals:</div>';
			
			foreach($LocationID as $Position=>$ID)
			{
				$this->POPReport .= '<div style="margin:5px 0px 5px 0px; text-align:center; width:120px; display:inline-block; float:left; white-space:nowrap">';
				$this->POPReport .= $LocationAdOverallCount[$Position];
				/*
				if(!empty($LocationAdCount[$Position])) 
				{
					$this->POPReport .= $LocationAdCount[$Position];
					$this->POPReportRowsArray['Report'] .= $LocationAdCount[$Position]."\t";
				}
				else 
				{
					$this->POPReport .= 'No Ads';
					$this->POPReportRowsArray['Report'] .= '0'."\t";
				}
				*/
				$this->POPReport .= '</div>';
			}
			$this->POPReport .= '</div>';
			$this->POPReport .= "\n".'<div style="clear:both" />';
			
			$this->POPReport .= "\n".'</div>';
			$this->POPReport .= "\n".'<script type="text/javascript">document.getElementById(\'POPReportLayout\').style.width=\''.$RowWidth.'px\';</script>';
			
			return true;
		}
		
		public function BuildRunReport($UserInfo, $LocationInfo, $AccountID, $AreaID, $RoomID, $LocationID, $ModeType) 
		{
//print("Locations<pre>". print_r($LocationInfo,true) ."</pre>");
			$Regional = false;
			$PanelsClass = new _Panels();
			/*
			switch ($ModeType)
			{
				case 'RegionalRunReport':
					$RunReport .= "\n".'<div id="RegionalRunReport">'."\n";
					$RunReport .= '<div id="PageTitle" style="font-size:18px;">'.$LocationInfo[0]['IA_Regions_Name'].'\'s Regional Run Report</div>';
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Region\', '.$LocationInfo[0]['IA_Regions_ID'].')" value="Place All Ads">';
					}
					$RunReport .= "\n".'</div>'."\n";
					$Regional = true;
					break;
				default:
					break;
			}
			*/
			for($a=0; $a<count($LocationInfo); $a++) 
			{
				$RunReport .= "\n".'<div style="page-break-after:always;">'."\n";
				$RunReport .= "\n".'<div id="RunReport" style="font-size:10px;">'."\n";
				$RunReport .= '<div id="PageTitle">'.$LocationInfo[$a]['IA_Accounts_BusinessName'].'\'s Run Report</div>';
				$RunReport .= '<p>';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_FirstName']) ? $LocationInfo[$a]['IA_Accounts_FirstName'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_LastName']) ? $LocationInfo[$a]['IA_Accounts_LastName'] : null;
				$RunReport .= '<br />';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Address']) ? $LocationInfo[$a]['IA_Accounts_Address'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_City']) ? $LocationInfo[$a]['IA_Accounts_City'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($LocationInfo[$a]['IA_States_Abbreviation']) ? $LocationInfo[$a]['IA_States_Abbreviation'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Zipcode']) ? $LocationInfo[$a]['IA_Accounts_Zipcode'] : null;
				$RunReport .= '<br />Phone: ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Phone']) ? $LocationInfo[$a]['IA_Accounts_Phone'] : null;
				$RunReport .= ' Fax: ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Fax']) ? $LocationInfo[$a]['IA_Accounts_Fax'] : null;
				$RunReport .= ' e-Mail: ';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Email']) ? $LocationInfo[$a]['IA_Accounts_Email'] : null;
				$RunReport .= '<br /><b>Contract Term:</b> '. date('m-d-Y', strtotime($LocationInfo[$a]['IA_Accounts_StartDate'])) .' through '. date('m-d-Y', strtotime($LocationInfo[$a]['IA_Accounts_EndDate'])) .'<br />';
				$RunReport .= '</p>';
				$RunReport .= '<p><b>NOTES:</b> <span style="color:#ff0000">';
				$RunReport .= !empty($LocationInfo[$a]['IA_Accounts_Notes']) ? $LocationInfo[$a]['IA_Accounts_Notes'] : null;
				$RunReport .= '</span></p>';
				
				if(isset($LocationInfo[$a]['Panels']['Areas'][0])) 
				{ $Areas = $LocationInfo[$a]['Panels']['Areas']; }
				else 
				{ 
					if(isset($LocationInfo[$a]['Panels']['Areas']) && !empty($LocationInfo[$a]['Panels']['Areas'])) 
					{ $Areas[] = $LocationInfo[$a]['Panels']['Areas']; } 
					else 
					{ }
				}
				
				for($Area=0; $Area<count($Areas); $Area++) 
				{
					$Rooms = null;
					if(isset($Areas[$Area]['Rooms'][0])) 
					{ $Rooms = $Areas[$Area]['Rooms']; }
					else 
					{ 
						if(isset($Areas[$Area]['Rooms']) && !empty($Areas[$Area]['Rooms'])) 
						{ $Rooms[] = $Areas[$Area]['Rooms']; } 
						else 
						{ }
					}
					
					for($Room=0; $Room<count($Rooms); $Room++) 
					{
						$Walls = null;
						if(isset($Rooms[$Room]['Walls'][0])) 
						{ $Walls = $Rooms[$Room]['Walls']; }
						else 
						{ 
							if(isset($Rooms[$Room]['Walls']) && !empty($Rooms[$Room]['Walls'])) 
							{ $Walls[] = $Rooms[$Room]['Walls']; } 
							else 
							{ }
						}
						
						for($Wall=0; $Wall<count($Walls); $Wall++) 
						{
							$FilterByOptions .= "\n".'<option value="'.$Areas[$Area]['IA_LocationAreas_ID'].','.$Rooms[$Room]['IA_LocationRooms_ID'].','.$Walls[$Wall]['IA_AdLocations_ID'].'">'.$Areas[$Area]['IA_LocationAreas_Area'].' '.$Rooms[$Room]['IA_LocationRooms_Room'].' ('.$Walls[$Wall]['IA_AdLocations_Location'].')</option>';
							//$Panels = null;
							if(isset($Walls[$Wall]['Panel'][0])) 
							{ 
								//$Panels[$Area][$Room][$Wall] = $Walls[$Wall]['Panel']; 
								for($Panel=0; $Panel<count($Walls[$Wall]['Panel']); $Panel++) 
								{
									if(isset($AreaID) && isset($RoomID) && isset($LocationID)) 
									{
										if($Walls[$Wall]['Panel'][$Panel]['IA_Panels_AreaID'] == $AreaID && $Walls[$Wall]['Panel'][$Panel]['IA_Panels_RoomID'] == $RoomID && $Walls[$Wall]['Panel'][$Panel]['IA_Panels_LocationID'] == $LocationID) 
										{ $Panels[] = $Walls[$Wall]['Panel'][$Panel]; }
									}
									else 
									{ $Panels[] = $Walls[$Wall]['Panel'][$Panel]; }
								}
							}
							else 
							{ 
								if(isset($Walls[$Wall]['Panel']) && !empty($Walls[$Wall]['Panel'])) 
								{ 
									if(isset($AreaID) && isset($RoomID) && isset($LocationID)) 
									{
										if($Walls[$Wall]['Panel']['IA_Panels_AreaID'] == $AreaID && $Walls[$Wall]['Panel']['IA_Panels_RoomID'] == $RoomID && $Walls[$Wall]['Panel']['IA_Panels_LocationID'] == $LocationID) 
										{ $Panels[] = $Walls[$Wall]['Panel'];  }
									}
									else 
									{ $Panels[] = $Walls[$Wall]['Panel']; }
								} 
								else 
								{ }
							}
						}
					}
					$Panels = array_values($Panels);
				}
				
//print("Panels<pre>". print_r($Panels,true) ."</pre>");
				switch ($ModeType)
				{
					case 'RegionalRunReport':
						break;
					default:
						if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
						{
							$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$LocationInfo[$a]['IA_Accounts_ID'].'\'" value="Add Panel"> ';
							$RunReport .= '<input type="button" id="PanelListButton" name="PanelListButton" onclick="window.location=\'panels.php?AccountID='.$LocationInfo[$a]['IA_Accounts_ID'].'&AreaID='.$_REQUEST['AreaID'].'&RoomID='.$_REQUEST['RoomID'].'&AdLocationID='.$_REQUEST['AdLocationID'].'&ModeType=PanelList\'" value="Edit Panels"> ';
						}
						$RunReport .= '<select name="FilterByOptionsDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$LocationInfo[$a]['IA_Accounts_ID'].'&AreaID=\'+this.options[this.selectedIndex].value.split(\',\')[0]+\'&RoomID=\'+this.options[this.selectedIndex].value.split(\',\')[1]+\'&AdLocationID=\'+this.options[this.selectedIndex].value.split(\',\')[2];">';
						$RunReport .= '<option value="">Select Panel(s) Location</option>';
						$RunReport .= $FilterByOptions;
						$RunReport .= '</select>';
						$RunReport .= '<input type="button" name="ShowPanelsButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$LocationInfo[$a]['IA_Accounts_ID'].'&AdLocationID=All\'" value="Show All Panels"> ';
						if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
						{
							$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Location\', '.$LocationInfo[$a]['IA_Accounts_ID'].')" value="Place All Ads"> ';
						}
						break;
				}
				
				/*
				if(!$Regional) 
				{
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$LocationInfo[$a]['IA_Accounts_ID'].'\'" value="Add Panel"> ';
						$RunReport .= '<input type="button" id="PanelListButton" name="PanelListButton" onclick="window.location=\'panels.php?AccountID='.$LocationInfo[$a]['IA_Accounts_ID'].'&AreaID='.$_REQUEST['AreaID'].'&RoomID='.$_REQUEST['RoomID'].'&AdLocationID='.$_REQUEST['AdLocationID'].'&ModeType=PanelList\'" value="Edit Panels"> ';
					}
					$RunReport .= '<select name="FilterByOptionsDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$LocationInfo[$a]['IA_Accounts_ID'].'&AreaID=\'+this.options[this.selectedIndex].value.split(\',\')[0]+\'&RoomID=\'+this.options[this.selectedIndex].value.split(\',\')[1]+\'&AdLocationID=\'+this.options[this.selectedIndex].value.split(\',\')[2];">';
					$RunReport .= '<option value="">Select Panel(s) Location</option>';
					$RunReport .= $FilterByOptions;
					
					if(isset($LocationInfo[$a]['Panels']['Areas'][0])) 
					{ $Areas = $LocationInfo[$a]['Panels']['Areas']; }
					else 
					{ 
						if(isset($LocationInfo[$a]['Panels']['Areas']) && !empty($LocationInfo[$a]['Panels']['Areas'])) 
						{ $Areas[] = $LocationInfo[$a]['Panels']['Areas']; } 
						else 
						{ }
					}
					
					for($Area=0; $Area<count($Areas); $Area++) 
					{
						$Rooms = null;
						if(isset($Areas[$Area]['Rooms'][0])) 
						{ $Rooms = $Areas[$Area]['Rooms']; }
						else 
						{ 
							if(isset($Areas[$Area]['Rooms']) && !empty($Areas[$Area]['Rooms'])) 
							{ $Rooms[] = $Areas[$Area]['Rooms']; } 
							else 
							{ }
						}
						
						for($Room=0; $Room<count($Rooms); $Room++) 
						{
							$Walls = null;
							if(isset($Rooms[$Room]['Walls'][0])) 
							{ $Walls = $Rooms[$Room]['Walls']; }
							else 
							{ 
								if(isset($Rooms[$Room]['Walls']) && !empty($Rooms[$Room]['Walls'])) 
								{ $Walls[] = $Rooms[$Room]['Walls']; } 
								else 
								{ }
							}
							
							for($Wall=0; $Wall<count($Walls); $Wall++) 
							{
								$RunReport .= '<option value="'.$Areas[$Area]['IA_LocationAreas_ID'].','.$Rooms[$Room]['IA_LocationRooms_ID'].','.$Walls[$Wall]['IA_AdLocations_ID'].'">'.$Areas[$Area]['IA_LocationAreas_Area'].' '.$Rooms[$Room]['IA_LocationRooms_Room'].' ('.$Walls[$Wall]['IA_AdLocations_Location'].')</option>';
								//$Panels = null;
								if(isset($Walls[$Wall]['Panel'][0])) 
								{ 
									//$Panels[$Area][$Room][$Wall] = $Walls[$Wall]['Panel']; 
									$Panels = $Walls[$Wall]['Panel'];
								}
								else 
								{ 
									if(isset($Walls[$Wall]['Panel']) && !empty($Walls[$Wall]['Panel'])) 
									{ 
										//$Panels[$Area][$Room][$Wall][] = $Walls[$Wall]['Panel']; 
										$Panels[] = $Walls[$Wall]['Panel']; 
									} 
									else 
									{ }
								}
							}
						}
					}
					
					$RunReport .= '</select>';
					$RunReport .= '<input type="button" name="ShowPanelsButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$LocationInfo[$a]['IA_Accounts_ID'].'&AdLocationID=All\'" value="Show All Panels"> ';
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Location\', '.$LocationInfo[$a]['IA_Accounts_ID'].')" value="Place All Ads"> ';
					}
				}
				else 
				{ }
				*/
				$RunReport .= "\n".'</div>'."\n";
				$HeaderHeight = 200;
				
				if(count($Panels) > 0) 
				{
					for($p=0; $p<count($Panels); $p++)
					{
						$LocationID = null;
						if(number_format((($Panels[$p]['IA_Panels_Width'] * 72) * .1), 0, '.', '') > 540) 
						{ (float)$Scale = 540 / number_format(($Panels[$p]['IA_Panels_Width'] * 72), 0, '.', ''); }
						else 
						{ (float)$Scale = .1; }
						
						if(number_format((($Panels[$p]['IA_Panels_Height'] * 72) * .1), 0, '.', '') > 720) 
						{ (float)$Scale = 720 / number_format(($Panels[$p]['IA_Panels_Height'] * 72), 0, '.', ''); }
						else 
						{ (float)$Scale = .1; }
						
						if(number_format((($Panels[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', '') > $PageHeight) 
						{ $PageHeight = $PageHeight + number_format((($Panels[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
						else 
						{ $PageHeight = number_format((($Panels[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
						$PageHeight = $PageHeight + $HeaderHeight;
						
						$PageWidth = $PageWidth + number_format((($Panels[$p]['IA_Panels_Width'] * 72) * $Scale), 0, '.', '');
						/*
						if ($PageWidth > 540)
						{
							if ($PageHeight > 720)
							{
								$RunReport .= '<div id="Panel'.$AccountPanelsArray[$a]['IA_Panels_ID'].'" style="vertical-align:top; page-break-after:always; display:inline-block; margin:5px; page-break-inside:avoid;">';
								$PageHeight = 0;
								$HeaderHeight = 0;
							}
							else 
							{ $RunReport .= '<div id="Panel'.$AccountPanelsArray[$a]['IA_Panels_ID'].'" style="vertical-align:top; display:inline-block; margin:5px; page-break-inside:avoid;">'; }
							$PageWidth = 0;
							$Row++;
						}
						else 
						{ $RunReport .= '<div id="Panel'.$AccountPanelsArray[$a]['IA_Panels_ID'].'" style="vertical-align:top; display:inline-block; margin:5px; page-break-inside:avoid;">'; }
						*/
						$RunReport .= '<div id="Panel'.$Panels[$p]['IA_Panels_ID'].'" style="vertical-align:top; display:inline-block; margin:5px; page-break-inside:avoid;">';
						$RunReport .= $PanelsClass->BuildPanel($UserInfo, $LocationInfo[$a]['IA_Accounts_ID'], $Panels[$p]['IA_Panels_ID'], null, 'DetailView', $Scale);
						$RunReport .= '</div>';
						
						//if($PageHeight > 720 || $AccountPanelsArray[$a]['IA_LocationRooms_ID'] != $AccountPanelsArray[$a+1]['IA_LocationRooms_ID'] || $AccountPanelsArray[$a]['IA_AdLocations_ID'] != $AccountPanelsArray[$a+1]['IA_AdLocations_ID']) 
						if($PageHeight > 720) 
						{ $RunReport .= "\n".'<div id="PanelRowBreak"> </div>'."\n"; $PageHeight = 0; }
						else 
						{ }
					}
				}
				else 
				{
					$RunReport .= '<div style="padding:20px; text-align:center">';
					$RunReport .= '<i>'.$LocationInfo[$a]['IA_Accounts_BusinessName'].' Has No Panels</i><br />';
					switch ($ModeType)
					{
						case 'RegionalRunReport':
							break;
						case 'RunReport':
							if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
							{
								$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$LocationInfo[$a]['IA_Accounts_ID'].'\'" style="margin-top:10px; width:90px; height:30px;" value="Add Panel"> ';
							}
							break;
						default:
							break;
					}
					$RunReport .= "\n".'</div>'."\n";
				}
				$RunReport .= "\n".'</div>'."\n";
			}
			/*
			$Regional = false;
			$AccountPanelsArray = array();
			
			$Panels = new _Panels();
			$XML = new DOMDocument();
			$Accounts = new _Accounts();
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
			{ }
			else 
			{ 
				$Accounts->GetLocations($UserInfo['UserParentID'], null);
				//$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			}
			$AccountXML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			$Account = json_decode(utf8_decode(json_encode($AccountXML)),true);
			
			switch ($ModeType)
			{
				case 'RegionalRunReport':
					if(isset($Account['Account'][0])) 
					{
						for($a=0; $a<count($Account['Account']); $a++) 
						{
							if($Account['Account'][$a]['IA_Accounts_RegionID'] == $RegionAccountID) 
							{ $AccountsInfo[] = $Account['Account'][$a]; }
							else 
							{ }
						}
					}
					else 
					{
						if($Account['Account']['IA_Accounts_RegionID'] == $RegionAccountID) 
						{ $AccountsInfo[] = $Account['Account']; }
						else 
						{ $AccountsInfo = null; }
					}
//print("AccountInfo<pre>". print_r($AccountsInfo,true) ."</pre>");
//print("<pre>". print_r($AccountPanelsArray,true) ."</pre>");
					//$AccountPanels = mysql_query("SELECT * FROM IA_Regions, IA_Accounts, IA_States, IA_AdLocations, IA_Panels, IA_AdPanels WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_RegionID=".$RegionAccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Panels_AccountID=IA_Accounts_ID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_Regions_Name, IA_Accounts_BusinessName, IA_AdLocations_Location, IA_AdPanels_Name", CONN);
					$Regional = true;
					break;
				case 'RunReport':
					if(isset($Account['Account'][0])) 
					{
						for($a=0; $a<count($Account['Account']); $a++) 
						{
							if($Account['Account'][$a]['IA_Accounts_ID'] == $RegionAccountID) 
							{ $AccountsInfo[] = $Account['Account'][$a]; }
							else 
							{ }
						}
					}
					else 
					{
						if($Account['Account']['IA_Accounts_ID'] == $RegionAccountID) 
						{ $AccountsInfo[] = $Account['Account']; }
						else 
						{ $AccountsInfo = null; }
					}
					break;
				default:
					break;
			}

			$RunReport = null;
			$RegionID = null;
			$AccountID = null;

			if($Regional)
			{
				if($XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
				{ }
				else 
				{ 
					$Accounts->GetLocations($UserInfo['UserParentID'], null);
					$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
				$Region = json_decode(json_encode($XML),true);
				if(isset($Region['Region'][0])) 
				{
					for($r=0; $r<count($Region['Region']); $r++) 
					{
						if($Region['Region'][$r]['IA_Regions_ID'] == $RegionAccountID) 
						{ $RegionsInfo[] = $Region['Region'][$r]; break; }
						else 
						{ }
					}
				}
				else 
				{
					if($Region['Region']['IA_Regions_ID'] == $RegionAccountID) 
					{ $RegionsInfo[] = $Region['Region']; }
					else 
					{ $RegionsInfo = null; }
				}
				$RunReport .= "\n".'<div id="RegionalRunReport">'."\n";
				$RunReport .= '<div id="PageTitle" style="font-size:18px;">'.$RegionsInfo[0]['IA_Regions_Name'].'\'s Regional Run Report</div>';
				if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
				{
					$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Region\', '.$RegionsInfo[0]['IA_Regions_ID'].')" value="Place All Ads">';
				}
				$RunReport .= "\n".'</div>'."\n";
			}
			else 
			{ }
			
			for($a=0; $a<count($AccountsInfo); $a++) 
			{
			*/
				/*
				$PanelInfo = array();
				$RunReport .= "\n".'<div style="page-break-after:always;">'."\n";
				$RunReport .= "\n".'<div id="RunReport" style="font-size:10px;">'."\n";
				$RunReport .= '<div id="PageTitle">'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].'\'s Run Report</div>';
				$RunReport .= '<p>';
				//!empty() ?  : null;
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_FirstName']) ? $AccountsInfo[$a]['IA_Accounts_FirstName'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_LastName']) ? $AccountsInfo[$a]['IA_Accounts_LastName'] : null;
				$RunReport .= '<br />';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Address']) ? $AccountsInfo[$a]['IA_Accounts_Address'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_City']) ? $AccountsInfo[$a]['IA_Accounts_City'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_States_Abbreviation']) ? $AccountsInfo[$a]['IA_States_Abbreviation'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Zipcode']) ? $AccountsInfo[$a]['IA_Accounts_Zipcode'] : null;
				$RunReport .= '<br />Phone: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Phone']) ? $AccountsInfo[$a]['IA_Accounts_Phone'] : null;
				$RunReport .= ' Fax: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Fax']) ? $AccountsInfo[$a]['IA_Accounts_Fax'] : null;
				$RunReport .= ' e-Mail: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Email']) ? $AccountsInfo[$a]['IA_Accounts_Email'] : null;
				$RunReport .= '<br /><b>Contract Term:</b> '. date('m-d-Y', strtotime($AccountsInfo[$a]['IA_Accounts_StartDate'])) .' through '. date('m-d-Y', strtotime($AccountsInfo[$a]['IA_Accounts_EndDate'])) .'<br />';
				$RunReport .= '</p>';
				$RunReport .= '<p><b>NOTES:</b> <span style="color:#ff0000">';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Notes']) ? $AccountsInfo[$a]['IA_Accounts_Notes'] : null;
				$RunReport .= '</span></p>';

				if(!$Regional) 
				{
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$AccountsInfo[$a]['IA_Accounts_ID'].'\'" value="Add Panel"> ';
						$RunReport .= '<input type="button" id="PanelListButton" name="PanelListButton" onclick="window.location=\'panels.php?AccountID='.$AccountsInfo[$a]['IA_Accounts_ID'].'&AreaID='.$_REQUEST['AreaID'].'&RoomID='.$_REQUEST['RoomID'].'&AdLocationID='.$_REQUEST['AdLocationID'].'&ModeType=PanelList\'" value="Edit Panels"> ';
					}
					$RunReport .= '<select name="FilterByOptionsDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$AccountsInfo[$a]['IA_Accounts_ID'].'&AreaID=\'+this.options[this.selectedIndex].value.split(\',\')[0]+\'&RoomID=\'+this.options[this.selectedIndex].value.split(\',\')[1]+\'&AdLocationID=\'+this.options[this.selectedIndex].value.split(\',\')[2];">';
					$RunReport .= '<option value="">Select Panel(s) Location</option>';
					$LocationRooms = mysql_query("SELECT * FROM IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_Panels WHERE IA_Panels_AccountID=".$AccountsInfo[$a]['IA_Accounts_ID']." AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_LocationAreas_ID, IA_LocationRooms_ID, IA_AdLocations_ID ORDER BY IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location", CONN) or die(mysql_error());
					while ($LocationRoom = mysql_fetch_assoc($LocationRooms))
					{
						$RunReport .= '<option value="'.$LocationRoom['IA_LocationAreas_ID'].','.$LocationRoom['IA_LocationRooms_ID'].','.$LocationRoom['IA_AdLocations_ID'].'">'.$LocationRoom['IA_LocationAreas_Area'].' '.$LocationRoom['IA_LocationRooms_Room'].' ('.$LocationRoom['IA_AdLocations_Location'].')</option>';
					}
					$RunReport .= '</select>';
					$RunReport .= '<input type="button" name="ShowPanelsButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$AccountsInfo[$a]['IA_Accounts_ID'].'&AdLocationID=All\'" value="Show All Panels"> ';
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Location\', '.$AccountsInfo[$a]['IA_Accounts_ID'].')" value="Place All Ads"> ';
					}
				}
				$RunReport .= "\n".'</div>'."\n";
				
				$HeaderHeight = 200;
				
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
				{ }
				else 
				{ 
					$Panels = new _Panels();
					$Panels->GetPanels($UserInfo['UserParentID'], null, $AccountsInfo[$a]['IA_Accounts_ID'], null);
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml');
				$PanelInfo = array();
				$Panel = json_decode(json_encode($XML),true);
				if(isset($Panel['Panel'][0])) 
				{ $PanelInfo = $Panel; }
				else 
				{ 
					if(!empty($Panel['Panel']))
					{
						$PanelInfo['Panel'][] = $Panel['Panel']; 
					}
					else 
					{
						$PanelInfo = null;
					}
				}
				*/
				/*
				$XML = new DOMDocument();
				if($XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
				{
					$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')
				}
				else 
				{
					$Panels->GetPanels($UserInfo['UserParentID'], $RegionAccountID, null, null);
					$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml');
				}
				$PanelsInfo = $XML->getElementsByTagName("Panel");

				$px = 0;
				foreach ($PanelsInfo as $Array) 
				{
					foreach($Array->childNodes as $n) 
					{
						if($n->nodeName != '#text') 
						{  $PanelInfo[$px][$n->nodeName] .= $n->nodeValue; }
					}
					$px++;
				}
				*/
//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");
				/*
				$x = 0;
				$AccountPanelsArray = array();
				if(!empty($LocationID) && $LocationID != 'All' && !empty($RoomID))
				{
					for($l=0; $l<count($PanelInfo['Panel']); $l++) 
					{
						if($PanelInfo['Panel'][$l]['IA_Panels_AccountID'] == $AccountsInfo[$a]['IA_Accounts_ID'] && $PanelInfo['Panel'][$l]['IA_Panels_RoomID'] == $RoomID && $PanelInfo['Panel'][$l]['IA_Panels_LocationID'] == $LocationID)
						{
							foreach($PanelInfo['Panel'][$l] as $key => $value)
							{ $AccountPanelsArray[$x][$key] = $value; }
							$x++;
						}
					}
				}
				else 
				{
					for($l=0; $l<count($PanelInfo['Panel']); $l++) 
					{
						//echo $PanelInfo[$a]['IA_Panels_RoomID'] .'='. $PanelInfo[$a]['IA_Panels_LocationID'];
						foreach($PanelInfo['Panel'][$l] as $key => $value)
						{ $AccountPanelsArray[$x][$key] = $value; }
						$x++;
					}
				}
				*/
//print("AccountPanelsArray<pre>". print_r($AccountPanelsArray,true) ."</pre>");
//echo count($AccountPanelsArray).'-';
			/*
				if(count($AccountPanelsArray) > 0) 
				{
					for($p=0; $p<=count($AccountPanelsArray); $p++)
					{
						if($AccountPanelsArray[$p]['IA_Accounts_ID'] == $AccountsInfo[$a]['IA_Accounts_ID'])
						{
							$LocationID = null;
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Width'] * 72) * .1), 0, '.', '') > 540) 
							{ (float)$Scale = 540 / number_format(($AccountPanelsArray[$p]['IA_Panels_Width'] * 72), 0, '.', ''); }
							else 
							{ (float)$Scale = .1; }
							
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * .1), 0, '.', '') > 720) 
							{ (float)$Scale = 720 / number_format(($AccountPanelsArray[$p]['IA_Panels_Height'] * 72), 0, '.', ''); }
							else 
							{ (float)$Scale = .1; }
							
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', '') > $PageHeight) 
							{ $PageHeight = $PageHeight + number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
							else 
							{ $PageHeight = number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
							$PageHeight = $PageHeight + $HeaderHeight;
							
							$PageWidth = $PageWidth + number_format((($AccountPanelsArray[$p]['IA_Panels_Width'] * 72) * $Scale), 0, '.', '');
							
							$RunReport .= '<div id="Panel'.$AccountPanelsArray[$p]['IA_Panels_ID'].'" style="vertical-align:top; display:inline-block; margin:5px; page-break-inside:avoid;">';
							$RunReport .= $Panels->BuildPanel($UserInfo, $AccountPanelsArray[$p]['IA_Accounts_ID'], $AccountPanelsArray[$p]['IA_Panels_ID'], null, 'DetailView', $Scale);
							$RunReport .= '</div>';
							
							//if($PageHeight > 720 || $AccountPanelsArray[$a]['IA_LocationRooms_ID'] != $AccountPanelsArray[$a+1]['IA_LocationRooms_ID'] || $AccountPanelsArray[$a]['IA_AdLocations_ID'] != $AccountPanelsArray[$a+1]['IA_AdLocations_ID']) 
							if($PageHeight > 720) 
							{ $RunReport .= "\n".'<div id="PanelRowBreak"> </div>'."\n"; $PageHeight = 0; }
							else 
							{ }
						}
						else 
						{ }
					}
				}
				else 
				{
					$RunReport .= '<div style="padding:20px; text-align:center">';
					$RunReport .= '<i>'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].' Has No Panels</i><br />';
					switch ($ModeType)
					{
						case 'RegionalRunReport':
							break;
						case 'RunReport':
							if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
							{
								$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$RegionAccountID.'\'" style="margin-top:10px; width:90px; height:30px;" value="Add Panel"> ';
							}
							break;
						default:
							break;
					}
					$RunReport .= "\n".'</div>'."\n";
				}
				$RunReport .= "\n".'</div>'."\n";
			}
			*/
			return $RunReport;
		}
/* OLD
		public function BuildRunReport($UserInfo, $RegionAccountID, $AreaID, $RoomID, $LocationID, $ModeType) 
		{
			$Regional = false;
			$AccountPanelsArray = array();
			
			$Panels = new _Panels();
			$XML = new DOMDocument();
			$Accounts = new _Accounts();
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
			{ }
			else 
			{ 
				$Accounts->GetLocations($UserInfo['UserParentID'], null);
				//$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			}
			$AccountXML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			$Account = json_decode(utf8_decode(json_encode($AccountXML)),true);
			
			switch ($ModeType)
			{
				case 'RegionalRunReport':
					if(isset($Account['Account'][0])) 
					{
						for($a=0; $a<count($Account['Account']); $a++) 
						{
							if($Account['Account'][$a]['IA_Accounts_RegionID'] == $RegionAccountID) 
							{ $AccountsInfo[] = $Account['Account'][$a]; }
							else 
							{ }
						}
					}
					else 
					{
						if($Account['Account']['IA_Accounts_RegionID'] == $RegionAccountID) 
						{ $AccountsInfo[] = $Account['Account']; }
						else 
						{ $AccountsInfo = null; }
					}
//print("AccountInfo<pre>". print_r($AccountsInfo,true) ."</pre>");
//print("<pre>". print_r($AccountPanelsArray,true) ."</pre>");
					//$AccountPanels = mysql_query("SELECT * FROM IA_Regions, IA_Accounts, IA_States, IA_AdLocations, IA_Panels, IA_AdPanels WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_RegionID=".$RegionAccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Panels_AccountID=IA_Accounts_ID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_Regions_Name, IA_Accounts_BusinessName, IA_AdLocations_Location, IA_AdPanels_Name", CONN);
					$Regional = true;
					break;
				case 'RunReport':
					if(isset($Account['Account'][0])) 
					{
						for($a=0; $a<count($Account['Account']); $a++) 
						{
							if($Account['Account'][$a]['IA_Accounts_ID'] == $RegionAccountID) 
							{ $AccountsInfo[] = $Account['Account'][$a]; }
							else 
							{ }
						}
					}
					else 
					{
						if($Account['Account']['IA_Accounts_ID'] == $RegionAccountID) 
						{ $AccountsInfo[] = $Account['Account']; }
						else 
						{ $AccountsInfo = null; }
					}
					break;
				default:
					break;
			}

			$RunReport = null;
			$RegionID = null;
			$AccountID = null;

			if($Regional)
			{
				if($XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
				{ }
				else 
				{ 
					$Accounts->GetLocations($UserInfo['UserParentID'], null);
					$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
				$Region = json_decode(json_encode($XML),true);
				if(isset($Region['Region'][0])) 
				{
					for($r=0; $r<count($Region['Region']); $r++) 
					{
						if($Region['Region'][$r]['IA_Regions_ID'] == $RegionAccountID) 
						{ $RegionsInfo[] = $Region['Region'][$r]; break; }
						else 
						{ }
					}
				}
				else 
				{
					if($Region['Region']['IA_Regions_ID'] == $RegionAccountID) 
					{ $RegionsInfo[] = $Region['Region']; }
					else 
					{ $RegionsInfo = null; }
				}
				$RunReport .= "\n".'<div id="RegionalRunReport">'."\n";
				$RunReport .= '<div id="PageTitle" style="font-size:18px;">'.$RegionsInfo[0]['IA_Regions_Name'].'\'s Regional Run Report</div>';
				if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
				{
					$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Region\', '.$RegionsInfo[0]['IA_Regions_ID'].')" value="Place All Ads">';
				}
				$RunReport .= "\n".'</div>'."\n";
			}
			else 
			{ }
			
			for($a=0; $a<count($AccountsInfo); $a++) 
			{
				$PanelInfo = array();
				$RunReport .= "\n".'<div style="page-break-after:always;">'."\n";
				$RunReport .= "\n".'<div id="RunReport" style="font-size:10px;">'."\n";
				$RunReport .= '<div id="PageTitle">'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].'\'s Run Report</div>';
				$RunReport .= '<p>';
				//!empty() ?  : null;
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_FirstName']) ? $AccountsInfo[$a]['IA_Accounts_FirstName'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_LastName']) ? $AccountsInfo[$a]['IA_Accounts_LastName'] : null;
				$RunReport .= '<br />';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Address']) ? $AccountsInfo[$a]['IA_Accounts_Address'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_City']) ? $AccountsInfo[$a]['IA_Accounts_City'] : null;
				$RunReport .= ', ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_States_Abbreviation']) ? $AccountsInfo[$a]['IA_States_Abbreviation'] : null;
				$RunReport .= ' ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Zipcode']) ? $AccountsInfo[$a]['IA_Accounts_Zipcode'] : null;
				$RunReport .= '<br />Phone: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Phone']) ? $AccountsInfo[$a]['IA_Accounts_Phone'] : null;
				$RunReport .= ' Fax: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Fax']) ? $AccountsInfo[$a]['IA_Accounts_Fax'] : null;
				$RunReport .= ' e-Mail: ';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Email']) ? $AccountsInfo[$a]['IA_Accounts_Email'] : null;
				$RunReport .= '<br /><b>Contract Term:</b> '. date('m-d-Y', strtotime($AccountsInfo[$a]['IA_Accounts_StartDate'])) .' through '. date('m-d-Y', strtotime($AccountsInfo[$a]['IA_Accounts_EndDate'])) .'<br />';
				$RunReport .= '</p>';
				$RunReport .= '<p><b>NOTES:</b> <span style="color:#ff0000">';
				$RunReport .= !empty($AccountsInfo[$a]['IA_Accounts_Notes']) ? $AccountsInfo[$a]['IA_Accounts_Notes'] : null;
				$RunReport .= '</span></p>';

				if(!$Regional) 
				{
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$AccountsInfo[$a]['IA_Accounts_ID'].'\'" value="Add Panel"> ';
						$RunReport .= '<input type="button" id="PanelListButton" name="PanelListButton" onclick="window.location=\'panels.php?AccountID='.$AccountsInfo[$a]['IA_Accounts_ID'].'&AreaID='.$_REQUEST['AreaID'].'&RoomID='.$_REQUEST['RoomID'].'&AdLocationID='.$_REQUEST['AdLocationID'].'&ModeType=PanelList\'" value="Edit Panels"> ';
					}
					$RunReport .= '<select name="FilterByOptionsDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$AccountsInfo[$a]['IA_Accounts_ID'].'&AreaID=\'+this.options[this.selectedIndex].value.split(\',\')[0]+\'&RoomID=\'+this.options[this.selectedIndex].value.split(\',\')[1]+\'&AdLocationID=\'+this.options[this.selectedIndex].value.split(\',\')[2];">';
					$RunReport .= '<option value="">Select Panel(s) Location</option>';
					$LocationRooms = mysql_query("SELECT * FROM IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_Panels WHERE IA_Panels_AccountID=".$AccountsInfo[$a]['IA_Accounts_ID']." AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_LocationAreas_ID, IA_LocationRooms_ID, IA_AdLocations_ID ORDER BY IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location", CONN) or die(mysql_error());
					while ($LocationRoom = mysql_fetch_assoc($LocationRooms))
					{
						$RunReport .= '<option value="'.$LocationRoom['IA_LocationAreas_ID'].','.$LocationRoom['IA_LocationRooms_ID'].','.$LocationRoom['IA_AdLocations_ID'].'">'.$LocationRoom['IA_LocationAreas_Area'].' '.$LocationRoom['IA_LocationRooms_Room'].' ('.$LocationRoom['IA_AdLocations_Location'].')</option>';
					}
					$RunReport .= '</select>';
					$RunReport .= '<input type="button" name="ShowPanelsButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ReportType=RunReport+'.$AccountsInfo[$a]['IA_Accounts_ID'].'&AdLocationID=All\'" value="Show All Panels"> ';
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						$RunReport .= '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Location\', '.$AccountsInfo[$a]['IA_Accounts_ID'].')" value="Place All Ads"> ';
					}
				}
				$RunReport .= "\n".'</div>'."\n";
				
				$HeaderHeight = 200;
				
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
				{ }
				else 
				{ 
					$Panels = new _Panels();
					$Panels->GetPanels($UserInfo['UserParentID'], null, $AccountsInfo[$a]['IA_Accounts_ID'], null);
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml');
				$PanelInfo = array();
				$Panel = json_decode(json_encode($XML),true);
				if(isset($Panel['Panel'][0])) 
				{ $PanelInfo = $Panel; }
				else 
				{ 
					if(!empty($Panel['Panel']))
					{
						$PanelInfo['Panel'][] = $Panel['Panel']; 
					}
					else 
					{
						$PanelInfo = null;
					}
				}
				
//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");

				$x = 0;
				$AccountPanelsArray = array();
				if(!empty($LocationID) && $LocationID != 'All' && !empty($RoomID))
				{
					for($l=0; $l<count($PanelInfo['Panel']); $l++) 
					{
						if($PanelInfo['Panel'][$l]['IA_Panels_AccountID'] == $AccountsInfo[$a]['IA_Accounts_ID'] && $PanelInfo['Panel'][$l]['IA_Panels_RoomID'] == $RoomID && $PanelInfo['Panel'][$l]['IA_Panels_LocationID'] == $LocationID)
						{
							foreach($PanelInfo['Panel'][$l] as $key => $value)
							{ $AccountPanelsArray[$x][$key] = $value; }
							$x++;
						}
					}
				}
				else 
				{
					for($l=0; $l<count($PanelInfo['Panel']); $l++) 
					{
						//echo $PanelInfo[$a]['IA_Panels_RoomID'] .'='. $PanelInfo[$a]['IA_Panels_LocationID'];
						foreach($PanelInfo['Panel'][$l] as $key => $value)
						{ $AccountPanelsArray[$x][$key] = $value; }
						$x++;
					}
				}
			
//print("AccountPanelsArray<pre>". print_r($AccountPanelsArray,true) ."</pre>");
//echo count($AccountPanelsArray).'-';
				if(count($AccountPanelsArray) > 0) 
				{
					for($p=0; $p<=count($AccountPanelsArray); $p++)
					{
						if($AccountPanelsArray[$p]['IA_Accounts_ID'] == $AccountsInfo[$a]['IA_Accounts_ID'])
						{
							$LocationID = null;
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Width'] * 72) * .1), 0, '.', '') > 540) 
							{ (float)$Scale = 540 / number_format(($AccountPanelsArray[$p]['IA_Panels_Width'] * 72), 0, '.', ''); }
							else 
							{ (float)$Scale = .1; }
							
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * .1), 0, '.', '') > 720) 
							{ (float)$Scale = 720 / number_format(($AccountPanelsArray[$p]['IA_Panels_Height'] * 72), 0, '.', ''); }
							else 
							{ (float)$Scale = .1; }
							
							if(number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', '') > $PageHeight) 
							{ $PageHeight = $PageHeight + number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
							else 
							{ $PageHeight = number_format((($AccountPanelsArray[$p]['IA_Panels_Height'] * 72) * $Scale), 0, '.', ''); }
							$PageHeight = $PageHeight + $HeaderHeight;
							
							$PageWidth = $PageWidth + number_format((($AccountPanelsArray[$p]['IA_Panels_Width'] * 72) * $Scale), 0, '.', '');
							
							$RunReport .= '<div id="Panel'.$AccountPanelsArray[$p]['IA_Panels_ID'].'" style="vertical-align:top; display:inline-block; margin:5px; page-break-inside:avoid;">';
							$RunReport .= $Panels->BuildPanel($UserInfo, $AccountPanelsArray[$p]['IA_Accounts_ID'], $AccountPanelsArray[$p]['IA_Panels_ID'], null, 'DetailView', $Scale);
							$RunReport .= '</div>';
							
							//if($PageHeight > 720 || $AccountPanelsArray[$a]['IA_LocationRooms_ID'] != $AccountPanelsArray[$a+1]['IA_LocationRooms_ID'] || $AccountPanelsArray[$a]['IA_AdLocations_ID'] != $AccountPanelsArray[$a+1]['IA_AdLocations_ID']) 
							if($PageHeight > 720) 
							{ $RunReport .= "\n".'<div id="PanelRowBreak"> </div>'."\n"; $PageHeight = 0; }
							else 
							{ }
						}
						else 
						{ }
					}
				}
				else 
				{
					$RunReport .= '<div style="padding:20px; text-align:center">';
					$RunReport .= '<i>'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].' Has No Panels</i><br />';
					switch ($ModeType)
					{
						case 'RegionalRunReport':
							break;
						case 'RunReport':
							if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
							{
								$RunReport .= '<input type="button" id="AddPanelButton" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$RegionAccountID.'\'" style="margin-top:10px; width:90px; height:30px;" value="Add Panel"> ';
							}
							break;
						default:
							break;
					}
					$RunReport .= "\n".'</div>'."\n";
				}
				$RunReport .= "\n".'</div>'."\n";
			}

			return $RunReport;
		}
*/			
/*
		public function ProofOfPerformance($UserID, $UserType, $AdvertiserID, $AdLibraryID, $AdPlacement, $AdType, $DateRange)
		{
			switch($AdPlacement) 
			{
				case 'Placed':
					$AdPlacement = 'IA_Ads_Placement=1';
					break;
				case 'Unplaced':
					$AdPlacement = 'IA_Ads_Placement=0';
					break;
				default:
					$AdPlacement = 'IA_Ads_Placement>=0';
					break;
			}
			if(isset($AdType) && !empty($AdType) && isset($DateRange) && !empty($DateRange)) 
			{
				$Date = explode("+", $DateRange);
				if(isset($AdLibraryID) && !empty($AdLibraryID) && $AdLibraryID != null && $AdLibraryID != 'null') 
				{
					if($AdType > 0) 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_TypeID=".$AdType." AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
					}
					else 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
					}
				}
				else 
				{
					if($AdType > 0) 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_TypeID=".$AdType." AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
					}
					else 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
					}
				}
			}
			else 
			{
				if(isset($AdLibraryID) && !empty($AdLibraryID) && $AdLibraryID != null && $AdLibraryID != 'null') 
				{
					$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
				}
				else 
				{
					$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND ".$AdPlacement." ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
				}
			}
			
			$AdvertisementCount = mysql_num_rows($AccountsInfo);
			if ($AdvertisementCount > 0)
			{
				$RowCount = 0;
				$CellCount = 1;
				while ($AccountInfo = mysql_fetch_assoc($AccountsInfo))
				{
					$Advertisements = new _Advertisements();
					$Advertisements->GetInfo($AccountInfo['IA_Ads_ID']);
					if(ValidateDateRange($Advertisements->AdvertiserStartDate, $Advertisements->AdvertiserExpirationDate, $Advertisements->AdStartDate, $Advertisements->AdExpirationDate))
					{
						
						
						if($CellCount == 1) 
						{
							if ($RowCount == 0)
							{
								echo '<tr style="vertical-align:top; white-space:nowrap">';
								$RowCount = 1;
							}
							else
							{
								echo '<tr style="background-color:#eeeeee; vertical-align:top; white-space:nowrap">';
								$RowCount = 0;
							}
						}
						echo '<td style="width:50%; background: url(images/table_background.png); background-repeat:repeat-x;">';
						if(isset($AdLibraryID) && !empty($AdLibraryID) && $AdLibraryID != null && $AdLibraryID != 'null') 
						{ }
						else 
						{
							$AdHeight = number_format((($AccountInfo["IA_AdLibrary_Height"] * 72) * .15), 0, '.', '');
							$AdWidth = number_format((($AccountInfo["IA_AdLibrary_Width"] * 72) * .15), 0, '.', '');
		
							echo "\n".'<img id="Ad'.$AccountInfo['IA_Ads_ID'].'" name="Ad'.$AccountInfo['IA_Ads_ID'].'" onclick="" src="images/lowres/ad'.$AccountInfo['IA_AdLibrary_ID'].'.jpg" align="right" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px; display:none; margin:3px" border="0" />'."\n";
						}
						
						echo '<h3>'.$AccountInfo['IA_Accounts_BusinessName'].'</h3>';
						echo '<p>'.$AccountInfo['IA_Accounts_Address'].'<br />';
						echo $AccountInfo['IA_Accounts_City'].', ';
						$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$AccountInfo['IA_Accounts_StateID'], CONN);
						while ($State = mysql_fetch_assoc($States))
						{
							echo $State['IA_States_Abbreviation'];
						}
						echo ' '.$AccountInfo['IA_Accounts_Zipcode'].'</p>';
						echo '<ul>';

						echo '<li><b>Ad Dimensions:</b> '.$AccountInfo['IA_AdLibrary_Width'].'" x '.$AccountInfo['IA_AdLibrary_Height'].'"</li>';
						// echo '<li><b>Start Date:</b> '.$AccountInfo['IA_Ads_StartDate'].'</li>';
						// echo '<li><b>Expiration Date:</b> '.$AccountInfo['IA_Ads_ExpirationDate'].'</li>';
						
						$AccountPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountInfo['IA_Ads_AccountID']." AND IA_Panels_PanelID=".$AccountInfo[IA_Ads_PanelID]." AND IA_Panels_LocationID=".$AccountInfo[IA_Ads_LocationID]." AND IA_AdPanels_ID=IA_Panels_PanelID AND IA_AdLocations_ID=IA_Panels_LocationID", CONN);
						$Panels = new _Panels();
						while ($AccountPanel = mysql_fetch_assoc($AccountPanels))
						{
							echo '<li><b>'.$AccountPanel['IA_AdLocations_Location'].'\'s Panel: </b>'.$AccountPanel['IA_AdPanels_Name'].'</li>';
							
						}
						
						echo '<li>$'.$AccountInfo['IA_Ads_Cost'].'</li>';
						
						switch($AccountInfo['IA_Ads_Placement']) 
						{
							case 0:
								echo '<li><i>Ad Unplaced</i></li>';
								break;
							default:
								echo '<li><i>Ad Placed</i></li>';
								break;
						}
						
						echo '</ul>';

						echo '</td>';
						
						if ($CellCount == 2)
						{
							echo '</tr>';
							$CellCount = 1;
						}
						else
						{
							$CellCount++;
						}
						
						
						
						
						
					}
					
					
					
				}
				
			}
			else 
			{
				echo '<tr><td style="height:30px; text-align:center; vertical-align:middle"><i>You Have No Placed Advertisements</i></td></tr>';
			}
		}
*/
		public function BuildPanelList($UserType, $AccountID, $LocationID, $PanelID, $ModeType)
		{
			$Accounts = new _Accounts();
			$Accounts->GetInfo($AccountID);
			
			$this->PanelList = "\n".'<div id="RunReport">'."\n";
			$this->PanelList .= '<div id="PageTitle">'.$Accounts->AccountBusinessName.'\'s Site Openings</div>';
			$this->PanelList .= "\n".'</div>'."\n";
			
			switch ($ModeType)
			{
				case 'ViewLocations':
					$PanelsInfo = mysql_query("SELECT * FROM IA_Panels WHERE IA_Panels_AccountID=".$AccountID." AND IA_Panels_PanelID=".$PanelID, CONN);
					$PanelCount = mysql_num_rows($PanelsInfo);
					if ($PanelCount > 0)
					{
						
					}
					break;
				default:
					if(isset($LocationID) && !empty($LocationID)) 
					{
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountID." AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=".$LocationID." AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_Panels_LocationID, IA_Panels_PanelID ORDER BY IA_Panels_LocationID, IA_Panels_PanelID ASC", CONN);
					}
					else 
					{
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountID." AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=IA_AdLocations_ID GROUP BY IA_Panels_LocationID, IA_Panels_PanelID ORDER BY IA_Panels_LocationID, IA_Panels_PanelID", CONN);
					}
					$PanelCount = mysql_num_rows($PanelsInfo);
					$Advertisements = new _Advertisements();
					$Panels = new _Panels();
					if ($PanelCount > 0)
					{
						$this->PanelList .= '<select name="FilterByOptionsDropdown" style="margin-bottom:3px; width:auto; overflow:auto;" onchange="window.location=this.options[this.selectedIndex].value;">';
						$this->PanelList .= '<option value="">Select Panel(s) Location</option>';
						
						if (!empty($AccountID))
						{
							$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Panels, IA_States WHERE IA_Panels_AccountID=".$AccountID." AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID GROUP BY IA_AdLocations_ID ORDER BY IA_Accounts_BusinessName, IA_States_Abbreviation, IA_Accounts_City, IA_AdLocations_Location", CONN);
						}
						else 
						{
							$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Panels, IA_States WHERE IA_Accounts_ID=IA_Panels_AccountID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_States_ID=IA_Accounts_StateID GROUP BY IA_AdLocations_ID ORDER BY IA_Accounts_BusinessName, IA_States_Abbreviation, IA_Accounts_City, IA_AdLocations_Location", CONN);
						}
						
						while ($Account = mysql_fetch_assoc($Accounts))
						{
							$this->PanelList .= '<option value="'.$_SERVER['PHP_SELF'].'?ReportType=SiteOpenings+'.$Account['IA_Accounts_ID'].'&LocationID='.$Account['IA_AdLocations_ID'].'">'.$Account['IA_AdLocations_Location'].'</option>';
						}
						$this->PanelList .= '</select>';
						
						$LocationID = null;
						while ($PanelInfo = mysql_fetch_assoc($PanelsInfo))
						{
							$Panel = $Panels->BuildPanel($UserType, $PanelInfo['IA_Panels_ID'], null, 'ImageOnly', .1);
							if ($Panels->OpenSectionsCount > 0)
							{
								if($LocationID != $PanelInfo['IA_AdLocations_ID']) 
								{
									$PanelID = null;
									$this->PanelList .= "\n".'<div style="clear:both;"> </div>'."\n";
									$PanelLocation = '<div style="display:block; width:auto;"><h2><b>Panel Location:</b> '.$PanelInfo['IA_AdLocations_Location'].'</h2></div>';
									$LocationID = $PanelInfo['IA_AdLocations_ID'];
									$CurrentLocationID = $LocationID;
									$ColumnCount = 0;
								}
								else 
								{
									$PanelLocation = '';
								}
								if ($ColumnCount == 4 || $LocationID != $CurrentLocationID)
								{
									$this->PanelList .= "\n".'<div style="clear:both;"> </div>'."\n";
									$ColumnCount = 0;
								}
								else 
								{
									
								}
								
								$this->PanelList .= $PanelLocation;
								$this->PanelList .= "\n".'<div id="RunReportColumns">'."\n";
								$this->PanelList .= '<p>';
								$this->PanelList .= '<b>Panel '.$PanelInfo['IA_AdPanels_Name'].'</b> has '.$Panels->OpenSectionsCount.' openings.<br />';
								$this->PanelList .= 'Panel Dimensions: Width: '. (float)$PanelInfo['IA_Panels_Width'] .'" Height: '. (float)$PanelInfo['IA_Panels_Height'] .'"<br />';
								$this->PanelList .= 'Sections: Wide: '.$PanelInfo['IA_Panels_Wide'].' High: '.$PanelInfo['IA_Panels_High'].'<br />';
								$this->PanelList .= 'Date Added: '. date('m/d/Y', strtotime($PanelInfo['IA_Panels_Date']));
								$this->PanelList .= '</p>';
								
								$this->PanelList .= $Panel;
								
								$this->PanelList .= "\n".'</div>'."\n";
								$ColumnCount++;
							}
							else 
							{ }
						}
					}
					else 
					{ }
					break;
			}
			
			return $this->PanelList;
		}
		
		public function BuildContractRentReport($UserID, $AccountInfo, $ReportView)
		{
			//$Accounts = new _Accounts();
			$Advertisers = new _Advertisers();
			$Advertisements = new _Advertisements();
			//$Accounts->GetInfo($AccountID);
			
			switch($ReportView)
			{
				case 'Account':
					echo '<div id="PageTitle">';
					echo $AccountInfo[0]['IA_Accounts_BusinessName'].'\'s Rent Report';
					echo '</div>'."\n";
					$StartDate = $AccountInfo[0]['IA_Accounts_StartDate'];
					$EndDate = $AccountInfo[0]['IA_Accounts_EndDate'];
					break;
				case 'Region':
					echo '<div id="PageTitle">';
					echo $AccountInfo[0]['IA_Regions_Name'].'\'s Regional Rent Report';
					echo '</div>'."\n";
					foreach($AccountInfo as $Account)
					{
						if(empty($StartDate) || strtotime($StartDate) > strtotime($Account['IA_Accounts_StartDate'])) 
						{
							$StartDate = $Account['IA_Accounts_StartDate'];
							
						}
						if(empty($EndDate) || strtotime($EndDate) < strtotime($Account['IA_Accounts_EndDate'])) 
						{
							$EndDate = $Account['IA_Accounts_EndDate'];
						}
					}
					/*
					if($this->RegionID != $Accounts->AccountRegionID) 
					{
						echo '<div id="PageTitle">';
						echo $AccountInfo[0]['IA_Regions_Name'].'\'s Regional Rent Report';
						echo '</div>'."\n";
						$this->RegionID = $Accounts->AccountRegionID;
						
						$StartDate = mysql_fetch_assoc(mysql_query("SELECT IA_Accounts_StartDate FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$Accounts->AccountRegionID." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID, IA_Accounts_RegionID ORDER BY IA_Accounts_StartDate ASC LIMIT 1", CONN));
						$EndDate = mysql_fetch_assoc(mysql_query("SELECT IA_Accounts_EndDate FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$Accounts->AccountRegionID." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID, IA_Accounts_RegionID ORDER BY IA_Accounts_EndDate DESC LIMIT 1", CONN));
						$StartDate = $StartDate['IA_Accounts_StartDate'];
						$EndDate = $EndDate['IA_Accounts_EndDate'];
					}
					*/
					break;
				default:
					break;
			}
			
			echo "\n".'<div id="RentReportSearchOptions" style="background-color:#ffffff; border-bottom:1px solid #aaaaaa; width:50%; height:30px; text-align:center; vertical-align:middle;">';
			echo "\n".'<select id="YearStartDropdown" name="YearStartDropdown">'."\n";
			echo Year_Dropdown(date("Y", strtotime($StartDate)));
			echo '</select>'."\n";
			echo '<select id="MonthStartDropdown" name="MonthStartDropdown">'."\n";
			echo Month_Dropdown((int) date("m", strtotime($StartDate)));
			echo '</select>'."\n";
			echo '<select id="DayStartDropdown" name="DayStartDropdown">'."\n";
			echo Day_Dropdown((int) date("d", strtotime($StartDate)));
			echo '</select>'."\n";
			echo "\n".'through: <select id="YearEndDropdown" name="YearEndDropdown">'."\n";
			echo Year_Dropdown(date("Y", strtotime($EndDate)));
			echo '</select>'."\n";
			echo '<select id="MonthEndDropdown" name="MonthEndDropdown">'."\n";
			echo Month_Dropdown((int) date("m", strtotime($EndDate)));
			echo '</select>'."\n";
			echo '<select id="DayEndDropdown" name="DayEndDropdown">'."\n";
			echo Day_Dropdown((int) date("d", strtotime($EndDate)));
			echo '</select>'."\n";
			echo ' <input type="button" onclick="GetContractRentReport(\''.$UserID.'\', \''.$ReportView.'\', '.$AccountInfo[0]['IA_Accounts_RegionID'].', '.$AccountInfo[0]['IA_Accounts_ID'].', document.getElementById(\'YearStartDropdown\').value+\'-\'+document.getElementById(\'MonthStartDropdown\').value+\'-\'+document.getElementById(\'DayStartDropdown\').value, document.getElementById(\'YearEndDropdown\').value+\'-\'+document.getElementById(\'MonthEndDropdown\').value+\'-\'+document.getElementById(\'DayEndDropdown\').value)" name="SearchButton" value="Get Report" /> ';
			echo '</div>'."\n";
			
			/*
			echo "\n".'<div id="RentReportSearchOptions" style="background-color:#ffffff; border-bottom:1px solid #aaaaaa; width:50%; height:30px; text-align:center; vertical-align:middle;">';
			echo "\n".'<select id="YearStartDropdown" name="YearStartDropdown">'."\n";
			echo Year_Dropdown(date("Y", strtotime($Accounts->AccountStartDate)));
			echo '</select>'."\n";
			echo '<select id="MonthStartDropdown" name="MonthStartDropdown">'."\n";
			echo Month_Dropdown((int) date("m", strtotime($Accounts->AccountStartDate)));
			echo '</select>'."\n";
			echo '<select id="DayStartDropdown" name="DayStartDropdown">'."\n";
			echo Day_Dropdown((int) date("d", strtotime($Accounts->AccountStartDate)));
			echo '</select>'."\n";
			echo "\n".'through: <select id="YearEndDropdown" name="YearEndDropdown">'."\n";
			echo Year_Dropdown(date("Y", strtotime($Accounts->AccountEndDate)));
			echo '</select>'."\n";
			echo '<select id="MonthEndDropdown" name="MonthEndDropdown">'."\n";
			echo Month_Dropdown((int) date("m", strtotime($Accounts->AccountEndDate)));
			echo '</select>'."\n";
			echo '<select id="DayEndDropdown" name="DayEndDropdown">'."\n";
			echo Day_Dropdown((int) date("d", strtotime($Accounts->AccountEndDate)));
			echo '</select>'."\n";
			echo ' <input type="button" onclick="GetContractRentReport('.$AccountID.', document.getElementById(\'YearStartDropdown\').value+\'-\'+document.getElementById(\'MonthStartDropdown\').value+\'-\'+document.getElementById(\'DayStartDropdown\').value, document.getElementById(\'YearEndDropdown\').value+\'-\'+document.getElementById(\'MonthEndDropdown\').value+\'-\'+document.getElementById(\'DayEndDropdown\').value)" name="SearchButton" value="Get Report" /> ';
			echo '</div>'."\n";
			*/
			echo '<div id="loading" style="width:50%; margin:0 auto; display:none"><img src="images/loading.gif" /></div>';
			echo "\n".'<div id="ContractRentReport" style="display:block; background-color:#ffffff; width:50%; text-align:left; vertical-align:top;">';
			//echo 'Select a Date Range';
			switch($ReportView) 
			{
				case 'Account':
					$Reports = mysql_query("SELECT * FROM IA_Reports WHERE IA_Reports_ReportType='".$ReportView."RentReport' AND IA_Reports_AccountID=".$AccountInfo[0]['IA_Accounts_ID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
					break;
				case 'Region':
					$Reports = mysql_query("SELECT * FROM IA_Reports WHERE IA_Reports_ReportType='".$ReportView."RentReport' AND IA_Reports_AccountID=".$AccountInfo[0]['IA_Accounts_RegionID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
					break;
				default:
					break;
			}
			echo '<h2>Saved Reports</h2>';
			$ReportCount = mysql_num_rows($Reports);
			if ($ReportCount > 0)
			{
				echo '<ul>';
				while ($Report = mysql_fetch_assoc($Reports))
				{
					$File = $Report['IA_Reports_ID'].'_'.$Report['IA_Reports_AccountID'].'_'.$ReportView.'RentReport_'. date("Y-m-d_H-i", strtotime($Report['IA_Reports_TimeStamp'])).'.xls';
					echo '<li style="line-height:14px">';
					echo '<a href="configuration/download.php?UserID='.$UserID.'&File='.$File.'">';
					echo date("F j, Y - g:i a", strtotime($Report['IA_Reports_TimeStamp']));
					echo '</a> <input type="button" onclick="DeleteSavedRentReport('.$UserID.', '.$Report['IA_Reports_AccountID'].', '.$Report['IA_Reports_ID'].', \''.$File.'\')" value="Delete Report" />';
					echo '</li>';
				}
				echo '</ul>';
			}
			else 
			{
				echo '<p style="font-style:italic">You have no saved reports.</p>';
			}
			echo '</div>'."\n";
		}
		
		public function ContractRentReport($UserID, $ReportView, $AccountID, $StartDate, $EndDate)
		{
			$AdvertiserLoopCount = 0;
			$AdLoopCount = 0;
			$AdPriceLoopCount = 0;
			
			$Accounts = new _Accounts();
			$Advertisers = new _Advertisers();
			$Advertisements = new _Advertisements();
			$this->RentReport = '';
			
			$this->RentReportRowsArray = array();
			//$AdvertiserAdsRowsArray = array();
			
			$this->AdvertiserAdsArray = array();
			$AdvertiserAdsRowsArray = array();
			
			$Data = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
			$Account = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$AccountID.'"]');
			$Account = json_decode(json_encode($Account[0]),true);
//print("Accounts<pre>". print_r($Account,true) ."</pre>");
			
			if($this->AccountID != $Account['IA_Accounts_ID']) 
			{
				switch($ReportView)
				{
					case 'Account':
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['AccountID'] = $Account['IA_Accounts_ID'];
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['AccountBusinessName'] = $Account['IA_Accounts_BusinessName'];
						break;
					case 'Region':
						$this->RentReport .= '<div id="PageTitle">';
						$this->RentReport .= $Account['IA_Accounts_BusinessName'].'\'s Rent Report';
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['AccountID'] = $Account['IA_Accounts_ID'];
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['AccountBusinessName'] = $Account['IA_Accounts_BusinessName'];
						$this->RentReport .= '</div>'."\n";
						break;
					default:
						break;
				}

				$this->RentReport .= "\n".'<div style="display:block; background-color:#ffffff; border-bottom:2px solid #aaaaaa; width:600px; text-align:left; vertical-align:top; font-size:12px; font-weight:normal">';
				$ContractTerms = 'Contract Terms: ';
				if($Account['RentTerms']['IA_TermRates_Rate'] == 'Percentage') 
				{
					$ContractTerms .= ($Account['RentTerms']['IA_AccountTerms_Value'] * 100).'%';
					
					
				}
				else 
				{
					$ContractTerms .= '$'.$Account['RentTerms']['IA_AccountTerms_Value'];
				}
	
				$ContractTerms .= ' ('.$Account['RentTerms']['IA_PaymentIncrements_Increment'].') through ';


				$ContractTerms .= date('F j, Y', strtotime($Account['IA_Accounts_StartDate'])) .' and '. date('F j, Y', strtotime($Account['IA_Accounts_EndDate']));
				$this->RentReport .= $ContractTerms;
				$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['ContractTerms'] = $ContractTerms;
				$this->RentReport .= '</div>'."\n";
				$this->AccountID = $Account['IA_Accounts_ID'];
			}
			
			
			
			
			
			/*
			$Accounts->GetInfo($AccountID);
			
			if($this->AccountID != $Accounts->AccountID) 
			{
				switch($ReportView)
				{
					case 'Account':
						$this->RentReportRowsArray[$Accounts->AccountID]['AccountID'] = $Accounts->AccountID;
						$this->RentReportRowsArray[$Accounts->AccountID]['AccountBusinessName'] = $Accounts->AccountBusinessName;
						break;
					case 'Region':
						$this->RentReport .= '<div id="PageTitle">';
						$this->RentReport .= $Accounts->AccountBusinessName.'\'s Rent Report';
						$this->RentReportRowsArray[$Accounts->AccountID]['AccountID'] = $Accounts->AccountID;
						$this->RentReportRowsArray[$Accounts->AccountID]['AccountBusinessName'] = $Accounts->AccountBusinessName;
						$this->RentReport .= '</div>'."\n";
						break;
					default:
						break;
				}
				
				$this->RentReport .= "\n".'<div style="display:block; background-color:#ffffff; border-bottom:2px solid #aaaaaa; width:600px; text-align:left; vertical-align:top; font-size:12px; font-weight:normal">';
				$ContractTerms = 'Contract Terms: ';
				if($Accounts->AccountTermsRate == 'Percentage') 
				{
					$ContractTerms .= ($Accounts->AccountTermsValue * 100).'%';
				}
				else 
				{
					$ContractTerms .= '$'.$Accounts->AccountTermsValue;
				}
	
				$ContractTerms .= ' ('.$Accounts->AccountTermsIncrement.') through ';
				
				$ContractTerms .= date('F j, Y', strtotime($Accounts->AccountStartDate)) .' and '. date('F j, Y', strtotime($Accounts->AccountEndDate));
				$this->RentReport .= $ContractTerms;
				$this->RentReportRowsArray[$Accounts->AccountID]['ContractTerms'] = $ContractTerms;
				$this->RentReport .= '</div>'."\n";
				$this->AccountID = $Accounts->AccountID;
			}
			*/
			$Total = 0;
			
			$this->RentReport .= '<div style="width:600px; display:block;">';
			$this->RentReport .= '<h2>';
			$this->RentReport .= date("F j, Y", strtotime($StartDate)).' through '.date("F j, Y", strtotime($EndDate));
			$this->RentReport .= '</h2>';
			$this->RentReport .= '<div style="width:330px; height:20px; border-bottom:1px solid #000000; font-weight:bold; text-align:left; vertical-align:middle; display:inline-block; white-space:nowrap">';
			$this->RentReport .= 'Advertiser Name</div>';
			$this->RentReport .= '<div style="width:50px; height:20px; border-bottom:1px solid #000000; font-weight:bold; text-align:left; font-weight:bold; vertical-align:middle; display:inline-block; white-space:nowrap">';
			$this->RentReport .= 'Number of Ads</div>';
			$this->RentReport .= '<div style="width:210px; height:20px; border-bottom:1px solid #000000; font-weight:bold; text-align:right; font-weight:bold; vertical-align:middle; display:inline-block; white-space:nowrap;">';
			$this->RentReport .= 'Total Amount per Ad</div>';
			$this->RentReport .= '<div style="clear:both"></div>';

/* DELETE
			$AdvertisersArray = array();
			$ArrayID = 0;
			
			$Advertisers = mysql_query("SELECT IA_Advertisers.* FROM IA_Advertisers, IA_Ads WHERE IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AdvertiserID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
			while ($Advertiser = mysql_fetch_assoc($Advertisers))
			{
				$AdvertisersArray['Advertisers'][$ArrayID] = $Advertiser;
				
				$Pricings = mysql_query("SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC", CONN);
				while ($Pricing = mysql_fetch_assoc($Pricings))
				{
					$AdvertisersArray['Advertisers'][$ArrayID]['Pricing'][] = $Pricing;
				}
				
				$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 ORDER BY IA_Ads_AccountID ASC", CONN);
				while ($Ad = mysql_fetch_assoc($Ads))
				{
					$AdvertisersArray['Advertisers'][$ArrayID]['Ads'][] = $Ad;
				}
				$ArrayID++;
			}
*/
if(file_exists(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml')) 
{ }
else 
{ 
	$Advertisers = new _Advertisers();
	$Advertisers->GetAdvertisers($UserID, null);
}
$XML = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml');
$AdvertisersInfo = json_decode(json_encode($XML),true);

$Advertisers = mysql_query("SELECT IA_Advertisers.* FROM IA_Advertisers, IA_Ads WHERE IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AdvertiserID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
$ArrayID = 0;
while ($Advertiser = mysql_fetch_assoc($Advertisers))
{
	if(isset($AdvertisersInfo['Advertiser'][0])) 
	{
		for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
		{
			if($Advertiser['IA_Advertisers_ID'] == $AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID']) 
			{
				$AdvertisersArray['Advertisers'][$ArrayID] = array_filter($AdvertisersInfo['Advertiser'][$a]);
				$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 ORDER BY IA_Ads_AccountID ASC", CONN);
				while ($Ad = mysql_fetch_assoc($Ads))
				{
					$AdvertisersArray['Advertisers'][$ArrayID]['Ads'][] = $Ad;
				}
				break;
			}
		}
	}
	else 
	{
		if($Advertiser['IA_Advertisers_ID'] == $AdvertisersInfo['Advertiser']['IA_Advertisers_ID']) 
		{
			if(isset($AdvertisersInfo['Advertiser']) && !empty($AdvertisersInfo['Advertiser'])) 
			{
				$AdvertisersArray['Advertisers'][$ArrayID] = array_filter($AdvertisersInfo['Advertiser']);
				$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertisersInfo['Advertiser']['IA_Advertisers_ID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 ORDER BY IA_Ads_AccountID ASC", CONN);
				while ($Ad = mysql_fetch_assoc($Ads))
				{
					$AdvertisersArray['Advertisers'][$ArrayID]['Ads'][] = $Ad;
				}
			}
			else 
			{ $AdvertisersArray = null; }
		}
	}
	$ArrayID++;
}
//echo count($AdvertisersArray['Advertisers']);
//print("User:<pre>".print_r($AdvertisersArray['Advertisers'][5],true)."</pre>");
			for($a=0; $a<=count($AdvertisersArray['Advertisers']); $a++) 
			{
				if(!empty($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_ID'])) 
				{
					if(isset($AdvertisersArray['Advertisers'][$a]['Pricings']['Pricing'][0])) 
					{ $AdvertiserPricing = $AdvertisersArray['Advertisers'][$a]['Pricings']['Pricing']; }
					else 
					{
						if(isset($AdvertisersArray['Advertisers'][$a]['Pricings']) && !empty($AdvertisersArray['Advertisers'][$a]['Pricings'])) 
						{ $AdvertiserPricing[] = $AdvertisersArray['Advertisers'][$a]['Pricings']['Pricing']; }
						else 
						{ $AdvertiserPricing = null; }
					}
//print("<pre>".print_r($AdvertiserPricing,true)."</pre>");
					$SubTotalContractPricing = 0;
					
					if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_DateDependent'] == 1) 
					{
						$AdvertiserStartDate = $AdvertisersArray['Advertisers'][$a]['IA_Advertisers_StartDate'];
						$AdvertiserEndDate = $AdvertisersArray['Advertisers'][$a]['IA_Advertisers_ExpirationDate'];
					}
					else 
					{
						$AdvertiserStartDate = $StartDate;
						$AdvertiserEndDate = $EndDate;
					}
				
					if(ValidateDateRange($StartDate, $EndDate, $AdvertiserStartDate, $AdvertiserEndDate))
					{
						$AdvertiserLoopCount++;
						$UnpricedAdsCount = 0;
						$PricedAdsCount = 0;
						$AdvertiserContractPricing = 0;
						
						$LocationAdsCount = 0;
						//echo count($AdvertisersArray['Advertisers'][5]['Ads']).'-';
						for($b=0; $b<=count($AdvertisersArray['Advertisers'][$a]['Ads']); $b++) 
						{
							if($AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_AccountID'] == $AccountID && $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_ApplyRent'] == 1) 
							{ $LocationAdsCount++; }
						}
						
						$AllAdsCount = 0;
						for($b=0; $b<=count($AdvertisersArray['Advertisers'][$a]['Ads']); $b++) 
						{
							if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_ApplyToRent'] == 1 && $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_ApplyRent'] == 1) 
							{ $AllAdsCount++; }
						}
						
						for($b=0; $b<=count($AdvertisersArray['Advertisers'][$a]['Ads']); $b++) 
						{
							if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_DateDependent'] == 1) 
							{
								$AdStartDate = '';
								$AdEndDate = '';
							}
							else 
							{
								$AdStartDate = $StartDate;
								$AdEndDate = $EndDate;
							}
							
							if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_ApplyToRent'] == 1 && $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_ApplyRent'] == 1) 
							{
								for($c=0; $c<count($AdvertiserPricing); $c++)
								{
									if(!empty($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdvertiserID'])) 
									{
										if($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdNumber'] > 0) 
										{
											$AdLoopCount++;
											if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_DateDependent'] == 1) 
											{
												$AdvertiserPricingStartDate = $AdvertiserPricing[$c]['IA_AdvertiserPricing_StartDate'];
												$AdvertiserPricingEndDate = $AdvertiserPricing[$c]['IA_AdvertiserPricing_EndDate'];
											}
											else 
											{
												$AdvertiserPricingStartDate = $StartDate;
												$AdvertiserPricingEndDate = $EndDate;
											}
									
											if(ValidateDateRange($AdvertiserStartDate, $AdvertiserEndDate, $AdvertiserPricingStartDate, $AdvertiserPricingEndDate))
											{
												$AdPriceLoopCount++;
								
												if($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdTypeID'] == 0 || $AdvertiserPricing[$c]['IA_AdvertiserPricing_AdTypeID'] == $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_TypeID']) 
												{ $CountAdType = true; }
												else 
												{ $CountAdType = false; }
												if($AdvertiserPricing[$c]['IA_AdvertiserPricing_LocationID'] == 0 || $AdvertiserPricing[$c]['IA_AdvertiserPricing_LocationID'] == $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_Ads_LocationID']) 
												{ $CountAdLocation = true; }
												else 
												{ $CountAdLocation = false; }
												if($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdSize'] == 0 || $AdvertiserPricing[$c]['IA_AdvertiserPricing_AdSize'] == $AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_AdLibrary_Width'].'x'.$AdvertisersArray['Advertisers'][$a]['Ads'][$b]['IA_AdLibrary_Height']) 
												{ $CountAdSize = true; }
												else 
												{ $CountAdSize = false; }
												
												if($CountAdType && $CountAdLocation && $CountAdSize) 
												{
													
													if($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdNumber'] > 0) 
													{
														
														// Increments are based on date range set under the advertiser pricing info
														$AdvertiserContractPricing = $AdvertiserContractPricing + (($AdvertiserPricing[$c]['IA_AdvertiserPricing_Pricing'] / $AdvertiserPricing[$c]['IA_AdvertiserPricing_AdNumber']) * CalculateNumberOfIncrements($AdvertiserPricing[$c]['IA_AdvertiserPricing_IncrementID'], $AdvertiserPricingStartDate, $AdvertiserPricingEndDate));
														// Counts priced ad at every location
														$PricedAdsCount++;
													}
													else 
													{ 
													//$UnpricedAdsCount++; 
													}
												}
												else 
												{ 
													//$UnpricedAdsCount++; 
												}
											}
										}
										else 
										{ $UnpricedAdsCount++; }
									}
								}
							}
						}
						
						$SubTotalContractPricing = $SubTotalContractPricing + $AdvertiserContractPricing;
						// Uncontracted Ads
						$AdvertiserUncontractPricing = 0;
						if($AdvertisersArray['Advertisers'][$a]['IA_Advertisers_ApplyToRent'] == 1) 
						{
							for($c=0; $c<=count($AdvertiserPricing); $c++)
							{
								if(!empty($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdvertiserID'])) 
								{
									if($AdvertiserPricing[$c]['IA_AdvertiserPricing_AdNumber'] == 0) 
									{
										// Increments are based on date range selected 
										$AdvertiserUncontractPricing = $AdvertiserUncontractPricing + (($AdvertiserPricing[$c]['IA_AdvertiserPricing_Pricing'] / $UnpricedAdsCount) * CalculateNumberOfIncrements($AdvertiserPricing[$c]['IA_AdvertiserPricing_IncrementID'], $StartDate, $EndDate));
									}
									else 
									{ }	
								}
							}
						}
						
						$ContractSubTotal = number_format($Account['RentTerms']['IA_AccountTerms_Value'] * number_format($SubTotalContractPricing, 2, '.', ''), 2, '.', '');
						$UncontractSubTotal = number_format(($Account['RentTerms']['IA_AccountTerms_Value'] * number_format($AdvertiserUncontractPricing, 2, '.', '')) * $LocationAdsCount, 2, '.', '');
						$SubTotal = $UncontractSubTotal + $ContractSubTotal;	
						$this->RentReport .= '<div style="width:330px; height:20px; padding-right:10px; text-align:left; vertical-align:middle; display:inline-block; white-space:nowrap">';
						$this->RentReport .= $AdvertisersArray['Advertisers'][$a]['IA_Advertisers_BusinessName'];
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Advertisers'][$a]['AdvertiserBusinessName'] = $AdvertisersArray['Advertisers'][$a]['IA_Advertisers_BusinessName'];
						$this->RentReport .= '</div>';
						$this->RentReport .= '<div style="width:50px; height:20px; text-align:left; vertical-align:middle; display:inline-block; white-space:nowrap">';
						$this->RentReport .= $LocationAdsCount;
						$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Advertisers'][$a]['AdCount'] = $LocationAdsCount;
						$this->RentReport .= '</div>';
						$this->RentReport .= '<div style="width:210px; height:20px; text-align:right; vertical-align:middle; display:inline-block; white-space:nowrap;">';
						if($Account['RentTerms']['IA_TermRates_Rate'] == 'Percentage') 
						{
							$this->RentReport .= '$'. number_format($SubTotal, 2, '.', ',');
							$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Advertisers'][$a]['SubTotal'] = $SubTotal;
							$Total = $Total + $SubTotal;
						}
						else 
						{
							$this->RentReport .= '$0.00';
							$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Advertisers'][$a]['SubTotal'] = '$0.00';
						}
					
						$this->RentReport .= '</div>';
						$this->RentReport .= '<div style="clear:both"></div>';
						// Export Array
						//$this->AdvertiserAdsArray[] = $AdvertiserAdsRowsArray;
						
						//break; // Remove Only Shows 1 Advertiser
					}
				}
			}
			// Bottom Row
			$this->RentReport .= '<div style="width:600px; border-top:2px solid #000000; padding:5px; display:block; text-align:right; vertical-align:middle; font-size:14px; font-weight:bold">';
			if($Account['RentTerms']['IA_TermRates_Rate'] == 'Percentage') 
			{
				$this->RentReport .= $Account['RentTerms']['IA_PaymentIncrements_Increment'].' Total $'. number_format($Total, 2, '.', ',');
				$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Total'] = $Account['RentTerms']['IA_PaymentIncrements_Increment'].' Total:'."\t". number_format($Total, 2, '.', ',');
			}
			else 
			{
				$this->RentReport .= $Account['RentTerms']['IA_PaymentIncrements_Increment'].' Total $'. number_format(($Account['RentTerms']['IA_AccountTerms_Value'] * CalculateNumberOfIncrements($Account['RentTerms']['IA_AccountTerms_IncrementID'], $StartDate, $EndDate)), 2, '.', ',');
				$this->RentReportRowsArray[$Account['IA_Accounts_ID']]['Total'] = $Account['RentTerms']['IA_PaymentIncrements_Increment'].' Total:'."\t". number_format(($Account['RentTerms']['IA_AccountTerms_Value'] * CalculateNumberOfIncrements($Account['RentTerms']['IA_AccountTerms_IncrementID'], $StartDate, $EndDate)), 2, '.', ',');
			}
			
			//$this->AdvertiserAdsArray[] = $AdvertiserAdsRowsArray;
			
			
		
			switch($ReportView)
			{
				case 'Account':
					//$this->RentReport .= ' <input type="button" onclick="window.location=\'configuration/export.php?ReportType=RentReport&ReportView='.$ReportView.'&AccountID='.$AccountID.'&StartDate='.$StartDate.'&EndDate='.$EndDate.'\'" id="ExportReport" name="ExportReport" value="Export Report">';
					$this->RentReport .= '</div>';
					break;
				case 'Region':
					$this->RentReport .= '</div>';
					break;
				default:
					break;
			}
			$this->RentReport .= '</div>';
		}
		
		public function DeleteSavedRentReport($UserID, $AccountID, $ReportID, $FileName) 
		{
			$Delete = "DELETE FROM IA_Reports WHERE IA_Reports_ID=".$ReportID." AND IA_Reports_AccountID=".$AccountID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{ $Confirmation = unlink('../users/'.$UserID.'/reports/'.$FileName); }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}
		
		public function DeleteSavedPOPReport($UserID, $AdvertiserID, $ReportID, $FileName) 
		{
			$Delete = "DELETE FROM IA_Reports WHERE IA_Reports_ID=".$ReportID." AND IA_Reports_AdvertiserID=".$AdvertiserID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{ $Confirmation = unlink('../users/'.$UserID.'/reports/'.$FileName); }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}
		
		public function BuildAdSummary($UserInfo, $AccountID, $ReportView)
		{
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ 
				$Advertisers = new _Advertisers();
				$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
			}
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
			$Advertiser = json_decode(json_encode($XML),true);
			
			if(isset($Advertiser['Advertiser'][0])) 
			{
				for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
				{ $AdvertiserInfo[] = $Advertiser['Advertiser'][$a]; }
			}
			else 
			{
				if(isset($Advertiser['Advertiser']) && !empty($Advertiser['Advertiser'])) 
				{ $AdvertiserInfo[] = $Advertiser['Advertiser']; }
				else 
				{ $AdvertiserInfo = null; }
			}
			
			for($a=0; $a<count($AdvertiserInfo); $a++) 
			{
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisements = new _Advertisements();
					$this->GetAds($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml'));
				$Ads = json_decode(json_encode($XML),true);
				
				if(isset($Ads['Ad'][0])) 
				{
					for($ad=0; $ad<count($Ads['Ad']); $ad++) 
					{ 
						if($Ads['Ad'][$ad]['IA_Ads_AccountID'] == $AccountID) 
						{ $Ad['Ad'][] = $Ads['Ad'][$ad]; }
					}
				}
				else 
				{
					if(isset($Ads['Ad']) && !empty($Ads['Ad'])) 
					{ 
						if($Ads['Ad']['IA_Ads_AccountID'] == $AccountID) 
						{ $Ad['Ad'][] = $Ads['Ad']; }
					}
					else 
					{ 
						//$Ad['Ad'] = null; 
					}
				}
			}
//print("Ad<pre>". print_r($AdvertiserInfo,true) ."</pre>");
//print("Ad<pre>". print_r($Ad,true) ."</pre>");
			$AdvertiserID = null;
			for($a=0; $a<count($AdvertiserInfo); $a++) 
			{
				$AdCount = 0;
				for($ad=0; $ad<count($Ad['Ad']); $ad++) 
				{
					if($Ad['Ad'][$ad]['IA_Advertisers_ID'] == $AdvertiserInfo[$a]['IA_Advertisers_ID']) 
					{
						$AdCount++;
					}
				}
				if($AdCount > 0) 
				{
					echo "\r\n".'<div id="AdSummary'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'">';
					echo '<h2>'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].'</h2>';
					echo '<p>Ad Count: <b style="font-size:14px;">'.$AdCount.'</b> Ad(s)</p>';
					echo '</div>'."\n";
					echo '<div style="clear:both;" />'."\n";
				}
			}
			
		
			/*
			switch($ReportView)
			{
				case 'Account':
					$AccountAds = mysql_query("SELECT * FROM IA_Accounts, IA_Advertisers, IA_Ads WHERE IA_Accounts_UserID=".$UserID." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AdLibraryID", CONN);
					break;
				case 'Region':
					$AccountAds = mysql_query("SELECT * FROM IA_Accounts, IA_Advertisers, IA_Ads WHERE IA_Accounts_UserID=".$UserID." AND IA_Accounts_RegionID=".$AccountID." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AdLibraryID", CONN);
					break;
				default:
					break;
			}
			
			$Advertisements = new _Advertisements();
			
			while ($AccountAd = mysql_fetch_assoc($AccountAds))
			{
				
				switch($ReportView)
				{
					case 'Account':
						$Ads = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountAd['IA_Ads_AccountID']." AND IA_Ads_AdLibraryID=".$AccountAd['IA_Ads_AdLibraryID']." AND IA_Ads_Archived=0", CONN);
						break;
					case 'Region':
						$Ads = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$AccountAd['IA_Accounts_RegionID']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_AdLibraryID=".$AccountAd['IA_Ads_AdLibraryID']." AND IA_Ads_Archived=0", CONN);
						break;
					default:
						break;
				}
				
				$AdCount = 0;
				while ($Ad = mysql_fetch_assoc($Ads))
				{
					$AdCount++;
				}
				
				$Advertisements->GetLibraryInfo($AccountAd['IA_Ads_AdLibraryID']);
				
				echo "\r\n".'<div id="AdSummary">';
				echo '<a href="users/'.$UserID.'/images/highres/ad'.$Advertisements->AdLibraryID.'.jpg" target="_blank" title="'.$Advertisements->AdvertiserBusinessName.'">';
				echo '<img src="users/'.$UserID.'/images/lowres/ad'.$Advertisements->AdLibraryID.'.jpg" style="width:'.(($Advertisements->AdWidth * 72) * .1).'px; height:'.(($Advertisements->AdHeight * 72) * .1).'px" border="0" alt="'.$Advertisements->AdvertiserBusinessName.'" />';
				echo '</a>'."\n";
				echo '<div  id="AdSummaryDescription">';
				echo '<h2>'.$Advertisements->AdvertiserBusinessName.'</h2>';
				echo '<p>Ad Count: <span style="font-size:14px; font-weight:bold">'.$AdCount.'</span> '.$Advertisements->AdWidth.'" x '.$Advertisements->AdHeight.'" Ad(s)</p>';
				echo '</div>'."\n";
				echo '</div>'."\n";
				echo '<div style="clear:both; border-top:1px solid #000000" />'."\n";
			}
			*/
		}
		
		
	}
?>
