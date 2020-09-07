<?php
// Pricing
	class _Pricing
	{
		public function AdvertiserPricing($UserID, $AdvertiserID)
		{
			switch($UserID) 
			{
				case 1:
				case 8:
					/*
					IA_AdvertiserPricing_ID
					IA_AdvertiserPricing_AdvertiserID
					IA_AdvertiserPricing_LocationID
					
					IA_AdvertiserPricing_AdNumber
					IA_AdvertiserPricing_Pricing
					*/
					$AdvertiserPricing = mysql_query("SELECT * FROM IA_AdvertiserPricing WHERE IA_AdvertiserPricing_AdvertiserID=".$AdvertiserID." ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate DESC", CONN);
					while ($Pricing = mysql_fetch_assoc($AdvertiserPricing))
					{
						$Pricing['IA_AdvertiserPricing_AdTypeID']
						$Pricing['IA_AdvertiserPricing_EndDate']
						$AdvertiserInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_TypeID=".$Pricing['IA_AdvertiserPricing_AdTypeID']." AND IA_Ads_StartDate>='".$Pricing['IA_AdvertiserPricing_StartDate']."' AND IA_Ads_ExpirationDate<='".$Pricing['IA_AdvertiserPricing_EndDate']."' AND IA_Ads_Archived=0", CONN);
					}
					
					break;
				default:
					break;
			}
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
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_TypeID=".$AdType." AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
					}
					else 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
					}
				}
				else 
				{
					if($AdType > 0) 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_TypeID=".$AdType." AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
					}
					else 
					{
						$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Regions, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Date[0]."' AND IA_Ads_ExpirationDate<='".$Date[1]."' AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
					}
				}
			}
			else 
			{
				if(isset($AdLibraryID) && !empty($AdLibraryID) && $AdLibraryID != null && $AdLibraryID != 'null') 
				{
					$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdLibraryID=".$AdLibraryID." AND IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_Ads_StartDate DESC", CONN);
				}
				else 
				{
					$AccountsInfo = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND ".$AdPlacement." AND IA_Ads_Archived=0 ORDER BY IA_Accounts_ID, IA_AdLocations_Location, IA_AdLibrary_Width, IA_AdLibrary_Height, IA_Ads_StartDate DESC", CONN);
				}
			}
			
			$AdvertisementCount = mysql_num_rows($AccountsInfo);
			if ($AdvertisementCount > 0)
			{
				$RowCount = 0;
				$CellCount = 1;
				while ($AccountInfo = mysql_fetch_assoc($AccountsInfo))
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
	
						echo "\n".'<img id="Ad'.$AccountInfo['IA_Ads_ID'].'" name="Ad'.$AccountInfo['IA_Ads_ID'].'" onclick="" src="users/'.$UserID.'/images/lowres/ad'.$AccountInfo['IA_AdLibrary_ID'].'.jpg" align="right" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px; display:none; margin:3px" border="0" />'."\n";
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
					/*
					$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_ID=".$AccountInfo['IA_Ads_TypeID'], CONN);
					while ($AdType = mysql_fetch_assoc($AdTypes))
					{
						echo '<li><b>Ad Type:</b> '.$AdType['IA_AdTypes_Name'].'</li>';
					}
					*/
					echo '<li><b>Ad Dimensions:</b> '.$AccountInfo['IA_AdLibrary_Width'].'" x '.$AccountInfo['IA_AdLibrary_Height'].'"</li>';
					// echo '<li><b>Start Date:</b> '.$AccountInfo['IA_Ads_StartDate'].'</li>';
					// echo '<li><b>Expiration Date:</b> '.$AccountInfo['IA_Ads_ExpirationDate'].'</li>';
					
					$AccountPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountInfo['IA_Ads_AccountID']." AND IA_Panels_PanelID=".$AccountInfo[IA_Ads_PanelID]." AND IA_Panels_LocationID=".$AccountInfo[IA_Ads_LocationID]." AND IA_AdPanels_ID=IA_Panels_PanelID AND IA_AdLocations_ID=IA_Panels_LocationID", CONN);
					$Panels = new _Panels();
					while ($AccountPanel = mysql_fetch_assoc($AccountPanels))
					{
						echo '<li><b>'.$AccountPanel['IA_AdLocations_Location'].'\'s Panel: </b>'.$AccountPanel['IA_AdPanels_Name'].'</li>';
						
					}
					
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
					if(isset($AdLibraryID) && !empty($AdLibraryID) && $AdLibraryID != null && $AdLibraryID != 'null') 
					{ }
					else 
					{
						echo '<p><a onclick="document.getElementById(\'Ad'.$AccountInfo['IA_Ads_ID'].'\').style.display=\'block\'">View Ad</a> | ';
						echo '<a onclick="document.getElementById(\'Ad'.$AccountInfo['IA_Ads_ID'].'\').style.display=\'none\'">Hide Ad</a><br />';
						echo '<a href="'.$_SERVER['PHP_SELF'].'?ReportType=ProofOfPerformance+'.$UserID.'&AdvertiserID='.$AccountInfo['IA_Advertisers_ID'].'&AdLibraryID='.$AccountInfo['IA_AdLibrary_ID'].'">Ad\'s Proof of Performance</a></p>';
					}
					
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
			else 
			{
				echo '<tr><td style="height:30px; text-align:center; vertical-align:middle"><i>You Have No Placed Advertisements</i></td></tr>';
			}
		}
		
		public function BuildContractReport($UserID, $AccountID)
		{
			$Accounts = new _Accounts();
			$Accounts->GetInfo($AccountID);
			$StartDate = strtotime($Accounts->AccountStartDate);
			$EndDate = strtotime($Accounts->AccountEndDate);
			$StartYear = date('Y', $StartDate);
			$EndYear = date('Y', $EndDate);
			$StartMonth = date('n', $StartDate);
			$EndMonth = date('n', $EndDate);
			$StartWeek = date('W', $StartDate);
			$EndWeek = date('W', $EndDate);
			$StartDay = date('j', $StartDate);
			$EndDay = date('j', $EndDate);
			/*
			echo '<table align="right" border="0" style="width:20%" cellpadding="0" cellspacing="0">'."\n";
			$Ads = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Accounts->AccountStartDate."' AND IA_Ads_ExpirationDate<='".$Accounts->AccountEndDate."' ORDER BY IA_Ads_StartDate, IA_Ads_ExpirationDate ASC", CONN);
			$Row = 1;
			while ($Ad = mysql_fetch_assoc($Ads))
			{
				echo '<tr style="vertical-align:middle; height:30px; font-size:10px"><td style="text-align:left; border-bottom:2px solid #aaaaaa; border-top:3px solid #aaaaaa">';
				echo $Row.' Ad ID:'.$Ad['IA_Ads_ID'].' '.$Ad['IA_Ads_StartDate'].'=>'.$Ad['IA_Ads_ExpirationDate'].' $'.$Ad['IA_Ads_Cost'];
				echo '</td></tr>'."\n";
				$Row++;
			}
			echo '</table>'."\n";
			*/
			switch($Accounts->AccountTermsIncrementID) 
			{
				case 52: // Weekly
				case 26: // Biweekly
					$PaymentCount = (($EndYear - $StartYear) * $Accounts->AccountTermsIncrementID) + (($EndWeek - $StartWeek) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0));
					break;
				case 12: // Monthly
					$PaymentCount = (($EndYear - $StartYear) * $Accounts->AccountTermsIncrementID) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0));
					break;
				case 6: // Biannualy
				case 3: // Quarterly
					$PaymentCount = ((($EndYear - $StartYear) * 12) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0))) / $Accounts->AccountTermsIncrementID;
					break;
				case 1: // Annually
					$PaymentCount = ceil(((($EndYear - $StartYear) * 12) + (($EndMonth - $StartMonth) + (max(($EndDay - $StartDay), 0) > 0 ? 1 : 0))) / 12);
					break;
				default:
					$PaymentCount = 1;
					break;
			}
			
			$DateIncrement = ($EndDate - $StartDate) / $PaymentCount;

			echo "\n".'<table align="left" border="0" style="background-color:#ffffff; width:50%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">'."\n";
			$Advertisers = new _Advertisers();
			$AdvertisersInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers WHERE IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Advertisers_ID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
			$LocationSubTotal = 0;
			while ($AdvertiserInfo = mysql_fetch_assoc($AdvertisersInfo))
			{
				$Advertisers->GetInfo($AdvertiserInfo['IA_Advertisers_ID']);
				/*
				$Advertisers->AdvertiserID
				$Advertisers->AdvertiserBusinessName
				$Advertisers->AdvertiserFirstName
				$Advertisers->AdvertiserLastName
				$Advertisers->AdvertiserAddress
				$Advertisers->AdvertiserCity
				$Advertisers->AdvertiserStateID
				$Advertisers->AdvertiserZipcode
				$Advertisers->AdvertiserPhone
				$Advertisers->AdvertiserFax
				$Advertisers->AdvertiserEmail
				$Advertisers->AdvertiserTaxID
				*/
				echo '<tr><td style="width:90%; border-bottom:1px solid #dddddd">';
				echo $Advertisers->AdvertiserBusinessName;
				echo '</td><td style="width:10%; text-align:right; border-bottom:1px solid #dddddd">';
				$Advertisements = new _Advertisements();
				//$Accounts->AccountStartDate
				//$Accounts->AccountEndDate
				
				$AdsInfo = mysql_query("SELECT * FROM IA_Ads, IA_Accounts, IA_Advertisers, IA_AdLibrary WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_AdvertiserID=".$Advertisers->AdvertiserID." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_StartDate>='".$Accounts->AccountStartDate."' AND IA_Ads_ExpirationDate<='".$Accounts->AccountEndDate."' AND IA_Ads_Archived=0 ORDER BY IA_Ads_LocationID, IA_Ads_PanelID, IA_Ads_PanelSectionID ASC", CONN);
				$AdvertiserTotal = 0;
				
				while ($AdInfo = mysql_fetch_assoc($AdsInfo))
				{
					$Advertisements->GetInfo($AdInfo['IA_Ads_ID']);
					$AdvertiserTotal = $AdvertiserTotal + $AdInfo['IA_Ads_Cost'];
					
					// View Ads Start HIDE
					$CostDebugList .= '<tr><td style="white-space:nowrap">';
					$CostDebugList .= $Advertisements->AdvertiserBusinessName.' '.$Advertisements->AdStartDate.'=>'.$Advertisements->AdExpirationDate.' $'.$AdInfo['IA_Ads_Cost'].'<br />';
					$CostDebugList .= $this->PanelLocation.' Panel: '.$Advertisements->PanelName;
					$CostDebugList .= '</td></tr>';
					// View Ads End HIDE
				}
				echo '$'. number_format($AdvertiserTotal, 2, '.', ',');
				$LocationSubTotal = $LocationSubTotal + $AdvertiserTotal;
				echo '</td></tr>'."\n";
				
			}

			echo '<tr style="vertical-align:middle; height:25px; font-size:12px"><td style="text-align:right; font-weight:bold; border-top:2px solid #aaaaaa; border-bottom:2px solid #dddddd">Ad Revenue Total:</td><td style="text-align:right; border-top:2px solid #aaaaaa; border-bottom:2px solid #dddddd">';
			echo '$'. number_format($LocationSubTotal, 2, '.', ',');
			echo '</td></tr>'."\n";
			echo '<tr style="vertical-align:middle; height:25px"><td style="text-align:right; font-weight:bold; border-bottom:1px solid #dddddd">';
			/*
			$Advertisements->AccountTermsID;
			$Advertisements->AccountTermsRateID;
			$Advertisements->AccountTermsIncrementID;
			$Advertisements->AccountTermsRate;
			$Advertisements->AccountTermsIncrement;
			*/
			echo $Accounts->AccountTermsRate.' ('.$Accounts->AccountTermsIncrement.') ';
			echo '</td><td style="text-align:right; border-bottom:1px solid #dddddd">';
			if($Accounts->AccountTermsRate == 'Percentage') 
			{
				echo ($Accounts->AccountTermsValue * 100).'%';
			}
			else 
			{
				echo '$'.$Accounts->AccountTermsValue;
			}
			echo '</td></tr>'."\n";

			$LocationTotal = 0; 
			$DateIncrement = ($EndDate - $StartDate) / $PaymentCount;
			$PaymentDate = $StartDate;
			$PastDateIncrements = $StartDate;
			//$PaymentTotal = 0;
			for($PaymentRows=1; $PaymentRows<=$PaymentCount; $PaymentRows++) 
			{
				$PaymentTotal = 0;
				echo '<tr style="vertical-align:middle; height:25px"><td style="text-align:right; font-weight:bold; border-bottom:1px solid #dddddd">';
				
				echo date("M-d-Y", $PaymentDate) .' through '. date("M-d-Y", ($PaymentDate + $DateIncrement)).' ';

				echo $Accounts->AccountTermsIncrement.' Payment '.$PaymentRows.':';
				echo '</td><td style="text-align:right; border-bottom:1px solid #dddddd">';
					
				if($Accounts->AccountTermsRate == 'Percentage') 
				{
					$AccountAds = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_StartDate>='".$Accounts->AccountStartDate."' AND IA_Ads_ExpirationDate<='".$Accounts->AccountEndDate."' AND IA_Ads_ApplyRent=1 AND IA_Ads_Archived=0", CONN);
					while ($AccountAd = mysql_fetch_assoc($AccountAds))
					{
						if(strtotime($AccountAd['IA_Ads_StartDate'])>=($PaymentDate - $StartDate))
						{ 
								$Payment = $Accounts->AccountTermsValue * $AccountAd['IA_Ads_Cost'];
						}
						else 
						{ 
							$Payment = 0;
						}

						if(strtotime($AccountAd['IA_Ads_StartDate'])>=$PaymentDate && strtotime($AccountAd['IA_Ads_StartDate'])<=($PaymentDate + $DateIncrement))
						{ 
							$Payment = $Accounts->AccountTermsValue * $AccountAd['IA_Ads_Cost'];
							if(strtotime($AccountAd['IA_Ads_StartDate'])>($PaymentDate + $DateIncrement))
							{
								$Payment = 0;
							}
						}
						else 
						{ 
							if(strtotime($AccountAd['IA_Ads_StartDate'])>=($PaymentDate - $StartDate) && strtotime($AccountAd['IA_Ads_StartDate'])<$PaymentDate)
							{ 
									$Payment = $Accounts->AccountTermsValue * $AccountAd['IA_Ads_Cost'];
									if(strtotime($AccountAd['IA_Ads_ExpirationDate'])<=$PaymentDate && strtotime($AccountAd['IA_Ads_ExpirationDate'])<=($PaymentDate + $DateIncrement)) 
									{
										$Payment = 0;
									}
							}
							else 
							{ 
								$Payment = 0;
							}
						}
						
						// View Ads Start HIDE
						$Advertisements->GetInfo($AccountAd['IA_Ads_ID']);
						$PaymentDebugList .= '<tr><td style="white-space:nowrap">';
						$PaymentDebugList .= $Accounts->AccountTermsIncrement.' Payment '.$PaymentRows.': ';
						$PaymentDebugList .= $Advertisements->AdvertiserBusinessName.' '.$Advertisements->AdStartDate.'=>'.$Advertisements->AdExpirationDate.' $'.$Payment.'<br />';
						$PaymentDebugList .= $this->PanelLocation.' Panel: '.$Advertisements->PanelName;
						$PaymentDebugList .= '</td></tr>';
						// View Ads End HIDE
						
						$PaymentTotal = $PaymentTotal + $Payment;
					}
				}
				else 
				{
					$PaymentTotal = $Accounts->AccountTermsValue;
				}
				//echo $PastDateIncrements.'='.($PaymentDate - $StartDate);
				$PastDateIncrements = $PastDateIncrements + $PaymentDate;
				
				$PaymentDate = $PaymentDate + $DateIncrement;
				echo '$'. number_format($PaymentTotal, 2, '.', ',');
				echo '</td></tr>'."\n";
				$LocationTotal = $LocationTotal + $PaymentTotal;
			}
			
			echo '<tr style="vertical-align:middle; height:30px; font-size:14px"><td style="text-align:right; font-weight:bold; border-bottom:2px solid #aaaaaa; border-top:3px solid #aaaaaa">Rent Due Total:</td><td style="text-align:right; border-bottom:2px solid #aaaaaa; border-top:3px solid #aaaaaa">';
			echo '$'. number_format($LocationTotal, 2, '.', ',');
			echo '</td></tr>'."\n";
			echo '</table>'."\n";
			
			
			// View Ads Start HIDE
			echo "\n".'<table align="right" border="0" style="background-color:#ffffff; width:40%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">'."\n";
			echo '<tr style="vertical-align:middle; height:30px; font-size:14px">';
			echo '<td style="text-align:left;">';
			echo 'DEBUG Payments';
			echo '</td></tr>'."\n";
			echo '<tr style="vertical-align:middle; height:30px; font-size:14px">';
			echo '<td style="text-align:left; border-bottom:2px solid #aaaaaa; border-top:3px solid #aaaaaa">';
			echo 'Advertisement Revenue';
			echo '</td></tr>'."\n";
			echo $CostDebugList;
			echo '<tr style="vertical-align:middle; height:30px; font-size:14px">';
			echo '<td style="text-align:left; border-bottom:2px solid #aaaaaa; border-top:3px solid #aaaaaa">';
			echo 'Advertisement Payments';
			echo '</td></tr>'."\n";
			echo $PaymentDebugList;
			echo '</table>'."\n";
			// View Ads End HIDE
		}
	}
?>
