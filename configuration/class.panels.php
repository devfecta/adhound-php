<?php
// Panels
	class _Panels extends _Validation
	{
		public function GetPanels($UserID, $RegionID, $AccountID, $LocationID)
		{
			if(isset($AccountID) && !empty($AccountID)) 
			{
				$Accounts = mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_ID=".$AccountID, CONN);
			}
			else
			{
				$Accounts = mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_RegionID=".$RegionID." ORDER BY IA_Accounts_BusinessName", CONN);
			}
			
			while($Account = mysql_fetch_assoc($Accounts))
			{
				/*
				if((isset($AccountID) && !empty($AccountID)) && (isset($LocationID) && !empty($LocationID))) 
				{
					if($LocationID == 'All') 
					{
						$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations, IA_Accounts, IA_States WHERE IA_Panels_AccountID=".$AccountID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=IA_AdLocations_ID ORDER BY IA_Panels_AccountID, IA_Panels_LocationID, IA_Panels_PanelID", CONN);
					}
					else 
					{
						$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations, IA_Accounts, IA_States WHERE IA_Panels_AccountID=".$AccountID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=".$LocationID." AND IA_AdLocations_ID=IA_Panels_LocationID ORDER BY IA_Panels_AccountID, IA_Panels_LocationID, IA_Panels_PanelID ASC", CONN);
					}
				}
				elseif(isset($AccountID) && !empty($AccountID)) 
				{
					$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations, IA_Accounts, IA_States WHERE IA_Panels_AccountID=".$AccountID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=IA_AdLocations_ID ORDER BY IA_Panels_AccountID, IA_Panels_LocationID, IA_Panels_PanelID", CONN);
				}
				else 
				{
					$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_AdLocations, IA_Accounts, IA_States WHERE IA_Panels_UserID=".$UserID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID AND IA_Panels_PanelID=IA_AdPanels_ID AND IA_Panels_LocationID=IA_AdLocations_ID ORDER BY IA_Panels_AccountID, IA_Panels_LocationID, IA_Panels_PanelID", CONN);
				}
				*/
				if(isset($AccountID) && !empty($AccountID)) 
				{
					$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_Accounts, IA_States, IA_Regions WHERE IA_Panels_AccountID=".$AccountID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_Panels_PanelID=IA_AdPanels_ID ORDER BY IA_Accounts_BusinessName, IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location, IA_AdPanels_ID", CONN);
				}
				else 
				{
					$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_Accounts, IA_States, IA_Regions WHERE IA_Panels_UserID=".$UserID." AND IA_Accounts_ID=IA_Panels_AccountID AND IA_Accounts_RegionID=".$RegionID." AND IA_Regions_ID=IA_Accounts_RegionID AND IA_States_ID=IA_Accounts_StateID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_Panels_PanelID=IA_AdPanels_ID ORDER BY IA_Accounts_BusinessName, IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location, IA_AdPanels_ID", CONN);
				}
				//// START Save MySQL Data to XML File
				$FileName = $UserID.'_'.$Account['IA_Accounts_ID'].'_PanelsInfo';
				$XML = new DOMDocument('1.0', 'UTF-8');
				$XML->formatOutput = true;
				
				$Root = $XML->createElement('Panels');
				$Root = $XML->appendChild($Root);
				
				//$NodeName = $XML->createElement('ModifiedDate');
				//$NodeName = $Root->appendChild($NodeName);
				//$NodeValue = $XML->createTextNode(date('Y-m-d'));
				//$NodeValue = $NodeName->appendChild($NodeValue);
				
				$PanelID = null;
				
				while($Panel = mysql_fetch_assoc($Panels))
				{
					if($Panel['IA_Accounts_ID'] == $Account['IA_Accounts_ID']) 
					{
						$Parent = $XML->createElement('Panel');
						$Parent = $Root->appendChild($Parent);
						foreach($Panel as $Name => $Value)
						{
							$NodeName = $XML->createElement($Name);
							$NodeName = $Parent->appendChild($NodeName);
							$NodeValue = $XML->createTextNode($Value);
							$NodeValue = $NodeName->appendChild($NodeValue);
						}
						
						if($Panel['IA_Panels_ID'] != $PanelID) 
						{
							$ParentAds = $XML->createElement('Ads');
							$ParentAds = $Parent->appendChild($ParentAds);
							//$PanelAds = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_Advertisers WHERE IA_Ads_AccountID=".$Panel['IA_Accounts_ID']." AND IA_Ads_PanelsID=".$Panel['IA_Panels_ID']." AND IA_Ads_LocationID=".$Panel['IA_AdLocations_ID']." AND IA_Ads_PanelID=".$Panel['IA_Panels_PanelID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Advertisers_ID=IA_Ads_AdvertiserID ORDER BY IA_Ads_PanelSectionID ASC", CONN);
							$PanelAds = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_Advertisers WHERE IA_Ads_AccountID=".$Panel['IA_Accounts_ID']." AND IA_Ads_PanelsID=".$Panel['IA_Panels_ID']." AND IA_Ads_Archived=0 AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Advertisers_ID=IA_Ads_AdvertiserID ORDER BY IA_Ads_PanelSectionID ASC", CONN);
							while($Ad = mysql_fetch_assoc($PanelAds))
							{
								$ParentAd = $XML->createElement('Ad');
								$ParentAd = $ParentAds->appendChild($ParentAd);
								foreach($Ad as $Name => $Value)
								{
									$NodeName = $XML->createElement($Name);
									$NodeName = $ParentAd->appendChild($NodeName);
									$NodeValue = $XML->createTextNode($Value);
									$NodeValue = $NodeName->appendChild($NodeValue);
								}
							}
							$PanelID = $Panel['IA_Panels_ID'];
						}
					}
				}
				$_SESSION['PanelInfo'] = $XML->save(ROOT."/users/".$UserID."/data/".$FileName.".xml");
				//// END Save MySQL Data to XML File
			}
			
			
			
			/*
			while($Panel = mysql_fetch_array($PanelsOLD, MYSQL_ASSOC))
			{
				$this->PanelInfoArray[] = $Panel;
			}
			//$_SESSION['PanelInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->PanelInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
			$_SESSION['PanelInfo'] = $this->PanelInfoArray;
			unset($this->PanelInfoArray);
			*/
			return true;
		}
	
		public function AddPanel($UserInfo, $PanelInfo)
		{
			
			$Confirmation = false;
			$InsertPanel = "INSERT INTO IA_Panels (";
			$InsertPanel .= "IA_Panels_UserID, ";
			$InsertPanel .= "IA_Panels_AccountID, ";
			$InsertPanel .= "IA_Panels_AreaID, ";
			$InsertPanel .= "IA_Panels_RoomID, ";
			$InsertPanel .= "IA_Panels_LocationID, ";
			$InsertPanel .= "IA_Panels_PanelID, ";
			$InsertPanel .= "IA_Panels_High, ";
			$InsertPanel .= "IA_Panels_Wide, ";
			$InsertPanel .= "IA_Panels_Height, ";
			$InsertPanel .= "IA_Panels_Width, ";
			$InsertPanel .= "IA_Panels_Description) VALUES ";
			$InsertPanel .= "(";
			$InsertPanel .= "'".$UserInfo['UserParentID']."', ";
			$InsertPanel .= "'".$PanelInfo['AccountID']."', ";
			$InsertPanel .= "'".trim($PanelInfo['AreaDropdownRequired'])."', ";
		  	$InsertPanel .= "'".trim($PanelInfo['RoomDropdownRequired'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['LocationDropdownRequired'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['PanelIDDropdownRequired'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['SectionsHighTextBox'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['SectionsWideTextBox'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['PanelHeightTextBox'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['PanelWidthTextBox'])."', ";
			$InsertPanel .= "'".trim($PanelInfo['PanelDescriptionTextBox'])."'";
			$InsertPanel .= ")";
		    
			if (mysql_query($InsertPanel, CONN) or die(mysql_error())) 
			{
				$this->GetPanels($UserInfo['UserParentID'], null, $PanelInfo['AccountID'], null);
				$Confirmation = true;
			}
			return $Confirmation;
		}
		
		public function DeletePanel($UserID, $AccountID, $PanelID) 
		{
			$Confirmation = false;	
			$DeletePanel = 'DELETE FROM IA_Panels WHERE IA_Panels_AccountID='.$AccountID.' AND IA_Panels_ID='.$PanelID;
			if (mysql_query($DeletePanel, CONN) or die(mysql_error())) 
			{
				/*
				$Update = "UPDATE IA_Ads SET ";
				$Update .= "IA_Ads_Archived=1";
				$Update .= " WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_PanelID=".$PanelID." AND IA_Ads_LocationID=".$LocationID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{ }
				*/
				$Ads = mysql_query('SELECT IA_Ads_AdvertiserID FROM IA_Ads WHERE IA_Ads_AccountID='.$AccountID.' AND IA_Ads_PanelsID='.$PanelID, CONN);
				
				$DeleteAdPanel = 'DELETE FROM IA_Ads WHERE IA_Ads_AccountID='.$AccountID.' AND IA_Ads_PanelsID='.$PanelID;
				if (mysql_query($DeleteAdPanel, CONN) or die(mysql_error())) 
				{ }
				
				$Confirmation = true;
				
				$Advertisements = new _Advertisements();
				while($Ad = mysql_fetch_assoc($Ads))
				{
					$Advertisements->GetAds($UserID, $Ad['IA_Ads_AdvertiserID']);
				}
				$this->GetPanels($UserID, null, $AccountID, null);
			}
			
			return $Confirmation;
		}
		
		public function DeletePanelAd($UserInfo, $AccountID, $AdvertiserID, $PanelID, $PanelSectionID, $AdID) 
		{
			$Confirmation = false;
			/*
			$Update = "UPDATE IA_Ads SET ";
			$Update .= "IA_Ads_Archived=1";
			$Update .= " WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_PanelID=".$PanelID." AND IA_Ads_ID=".$AdID." AND IA_Ads_LocationID=".$LocationID;
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			*/
			$DeleteAd = 'DELETE FROM IA_Ads WHERE IA_Ads_AccountID='.$AccountID.' AND IA_Ads_PanelsID='.$PanelID.' AND IA_Ads_PanelSectionID='.$PanelSectionID.' AND IA_Ads_ID='.$AdID;
			if (mysql_query($DeleteAd, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
//$Confirmation = true;
			$Advertisements = new _Advertisements();
			$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserID);
			$this->GetPanels($UserInfo['UserParentID'], null, $AccountID, null);
			
			return $Confirmation;
		}
		
		public function BuildPanelForm($PanelInfo, $AccountInfo)
		{
			/*
			if(!empty($PanelInfo)) 
			{
				// Edit Panel Location
				$this->PanelForm .= "\r".'<div style="width:100px; text-align:right; display:inline-block">';
				$this->PanelForm .= 'Business Name:';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<h2>'.$AccountInfo['IA_Accounts_BusinessName'].' ('.$AccountInfo['IA_Accounts_City'].' ,'.$AccountInfo['IA_States_Abbreviation'].')</h2>';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				
				$this->PanelForm .= "\r".'<div style="width:100px; text-align:right; display:inline-block">';
				$this->PanelForm .= 'Location\'s Room:';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<input type="text" id="RoomTextBox" name="RoomTextBox" size="30" maxlength="64" value="'.$PanelInfo['IA_AdLocations_Location'].'" />';
				$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$PanelInfo['IA_Panels_AccountID'].'">';
				$this->PanelForm .= '<input type="hidden" id="LocationID" name="LocationID" value="'.$PanelInfo['IA_AdLocations_ID'].'">';
				$this->PanelForm .= '<input type="submit" style="font-size:11px" id="UpdatePanelLocationButton" name="UpdatePanelLocationButton" value="Update Panel Location"> ';
				$this->PanelForm .= '<input type="submit" style="font-size:11px" id="DeletePanelLocationButton" name="DeletePanelLocationButton" onclick="return confirm(\'The following panel location information will be deleted.\n-Panel Location Information\n-Associated Panel Location Panels\n-Associated Panel Location Ads\')" value="Delete Panel Location"> ';
				$this->PanelForm .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r\n";
			}
			else 
			{
			*/
				$this->PanelForm .= "\r".'<div style="text-align:right; display:inline-block">';
				$this->PanelForm .= 'Business Name:';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<h2>'.$AccountInfo['IA_Accounts_BusinessName'].' ('.$AccountInfo['IA_Accounts_City'].' ,'.$AccountInfo['IA_States_Abbreviation'].')</h2>';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				// Area Start
				$this->PanelForm .= "\r".'<div style="text-align:right; display:inline-block">';
				$this->PanelForm .= 'Location\'s Area:';
				$this->PanelForm .= '</div><div id="Area" name="Area" style="display:inline-block"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<select id="AreaDropdownRequired" name="AreaDropdownRequired" onchange="GetAvailableRooms('.$AccountInfo['IA_Accounts_UserID'].', this.value)" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].')">'."\r\n";
				$this->PanelForm .= '<option value="">Select A Location Area</option>'."\r\n";
				
				$LocationAreas = mysql_query("SELECT * FROM IA_LocationAreas WHERE IA_LocationAreas_UserID=".$AccountInfo['IA_Accounts_UserID']." AND IA_LocationAreas_AccountID=".$AccountInfo['IA_Accounts_ID']." ORDER BY IA_LocationAreas_Area", CONN) or die(mysql_error());
				while ($LocationArea = mysql_fetch_assoc($LocationAreas))
				{
					if($PanelInfo['IA_Panels_AreaID'] == $LocationArea['IA_LocationAreas_ID']) 
					{ $this->PanelForm .= '<option value="'.$LocationArea['IA_LocationAreas_ID'].'" selected>'.$LocationArea['IA_LocationAreas_Area'].'</option>'."\r\n"; }
					else 
					{ $this->PanelForm .= '<option value="'.$LocationArea['IA_LocationAreas_ID'].'">'.$LocationArea['IA_LocationAreas_Area'].'</option>'."\r\n"; }
				}
				$this->PanelForm .= '</select> ';
				$this->PanelForm .= '<input type="button" onclick="ShowAddArea()" id="ShowAreaButton" name="ShowAreaButton" value="Add Location Area">';
				$this->PanelForm .= '</div></div>'."\r";
				
				$this->PanelForm .= "\r".'<div id="NewArea" name="NewArea" style="display:none"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<input type="text" id="AreaTextBox" name="AreaTextBox" size="30" maxlength="48" style="color:#aaaaaa" onfocus="this.value=\'\'; this.style.color=\'#000000\'" value="Second Floor" />';
				//$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$AccountInfo['IA_Accounts_ID'].'">';
				$this->PanelForm .= ' <input type="submit" style="font-size:11px" id="AddAreaButton" name="AddAreaButton" value="Add Location Area"> ';
				$this->PanelForm .= '<input type="button" onclick="ShowArea()" id="CancelButton" name="CancelButton" value="Cancel">';
				$this->PanelForm .= '</div></div><div style="margin-top:10px; clear:both" />'."\r";
				// Area End
				
				// Room Start
				$this->PanelForm .= "\r".'<div style="text-align:right; display:inline-block">';
				$this->PanelForm .= 'Location\'s Room:';
				$this->PanelForm .= '</div><div id="Room" name="Room" style="display:inline-block"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<select id="RoomDropdownRequired" name="RoomDropdownRequired" onchange="GetAvailableAdLocations('.$AccountInfo['IA_Accounts_UserID'].', this.value)" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].')">'."\r\n";
				if(!empty($PanelInfo)) 
				{
					//$LocationRooms = mysql_query("SELECT * FROM IA_LocationRooms, IA_Panels WHERE IA_LocationRooms_UserID=".$AccountInfo['IA_Accounts_UserID']." AND IA_Panels_RoomID=IA_LocationRooms_ID AND IA_Panels_AccountID=".$AccountInfo['IA_Accounts_ID']." GROUP BY IA_Panels_RoomID ORDER BY IA_LocationRooms_Room", CONN) or die(mysql_error());
					$LocationRooms = mysql_query("SELECT * FROM IA_LocationRooms WHERE IA_LocationRooms_UserID=".$AccountInfo['IA_Accounts_UserID']." ORDER BY IA_LocationRooms_Room", CONN) or die(mysql_error());
					while ($LocationRoom = mysql_fetch_assoc($LocationRooms))
					{
						if($PanelInfo['IA_Panels_RoomID'] == $LocationRoom['IA_LocationRooms_ID']) 
						{ $this->PanelForm .= '<option value="'.$LocationRoom['IA_LocationRooms_ID'].'" selected>'.$LocationRoom['IA_LocationRooms_Room'].'</option>'."\r\n"; }
						else 
						{ $this->PanelForm .= '<option value="'.$LocationRoom['IA_LocationRooms_ID'].'">'.$LocationRoom['IA_LocationRooms_Room'].'</option>'."\r\n"; }
					}
				}
				else 
				{ $this->PanelForm .= '<option disabled selected>Select A Location Room</option>'."\r\n"; }
				$this->PanelForm .= '</select> ';
				$this->PanelForm .= '<input type="button" onclick="ShowAddRoom()" id="ShowRoomButton" name="ShowRoomButton" value="Add Panel Location">';
				$this->PanelForm .= '<img id="LoadingRoomField" name="LoadingRoomField" src="images/loading.gif" align="center" style="text-align:center; margin:0px 3px 0px 3px; width:20px; height:20px; display:none" />';
				$this->PanelForm .= '</div></div>'."\r";
				
				$this->PanelForm .= "\r".'<div id="NewRoom" name="NewRoom" style="display:none"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<input type="text" id="RoomTextBox" name="RoomTextBox" size="30" maxlength="64" style="color:#aaaaaa" onfocus="this.value=\'\'; this.style.color=\'#000000\'" value="Men\'s Bathroom" />';
				$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$AccountInfo['IA_Accounts_ID'].'">';
				$this->PanelForm .= ' <input type="submit" style="font-size:11px" id="AddRoomButton" name="AddRoomButton" value="Add Panel Location"> ';
				$this->PanelForm .= '<input type="button" onclick="ShowRoom()" id="CancelButton" name="CancelButton" value="Cancel">';
				$this->PanelForm .= '</div></div><div style="margin-top:10px; clear:both" />'."\r";
				// Room End
				
				// Wall Start
				$this->PanelForm .= "\r".'<div style="text-align:right; display:inline-block">';
				$this->PanelForm .= 'Wall Location:';
				$this->PanelForm .= '</div><div id="WallLocation" name="WallLocation" style="display:inline-block"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<select id="LocationDropdownRequired" name="LocationDropdownRequired" onchange="GetAvailablePanels('.$AccountInfo['IA_Accounts_ID'].', document.getElementById(\'AreaDropdownRequired\').value, document.getElementById(\'RoomDropdownRequired\').value, this.value)" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].')">'."\r\n";
				$this->PanelForm .= '<option disabled selected>Select A Location</option>'."\r\n";
				if(!empty($PanelInfo)) 
				{
					//$AdLocations = mysql_query("SELECT * FROM IA_AdLocations, IA_Panels WHERE IA_AdLocations_UserID=".$AccountInfo['IA_Accounts_UserID']." AND IA_Panels_LocationID=IA_AdLocations_ID AND IA_Panels_AccountID=".$AccountInfo['IA_Accounts_ID']." AND IA_Panels_RoomID=".$PanelInfo['IA_Panels_RoomID']." GROUP BY IA_Panels_LocationID ORDER BY IA_AdLocations_Location", CONN) or die(mysql_error());
					$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_UserID=".$AccountInfo['IA_Accounts_UserID']." ORDER BY IA_AdLocations_Location", CONN) or die(mysql_error());
					while ($AdLocation = mysql_fetch_assoc($AdLocations))
					{
						if(!empty($PanelInfo) && $PanelInfo['IA_Panels_LocationID'] == $AdLocation['IA_AdLocations_ID']) 
						{ $this->PanelForm .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'" selected>'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n"; }
						else 
						{ $this->PanelForm .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'">'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n"; }
					}
				}
				else 
				{ $this->PanelForm .= '<option disabled selected>Select A Location</option>'."\r\n"; }
				$this->PanelForm .= '</select> ';
				$this->PanelForm .= '<input type="button" onclick="ShowAddWallLocation()" id="ShowWallLocationButton" name="ShowWallLocationButton" value="Add Wall Location">';
				$this->PanelForm .= '<img id="LoadingWallField" name="LoadingWallField" src="images/loading.gif" align="center" style="text-align:center; margin:0px 3px 0px 3px; width:20px; height:20px; display:none" />';
				$this->PanelForm .= '</div></div>'."\r";
				
				$this->PanelForm .= "\r".'<div id="NewWallLocation" name="NewWallLocation" style="display:none"><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<input type="text" id="WallLocationTextBox" name="WallLocationTextBox" size="30" maxlength="64" style="color:#aaaaaa" onfocus="this.value=\'\'; this.style.color=\'#000000\'" value="Stall" />';
				$this->PanelForm .= '<input type="hidden" id="PanelID" name="PanelID" value="'.$PanelInfo['IA_Panel_ID'].'">';
				$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$AccountInfo['IA_Accounts_ID'].'">';
				$this->PanelForm .= ' <input type="submit" style="font-size:11px" id="AddWallLocationButton" name="AddWallLocationButton" value="Add Wall Location"> ';
				$this->PanelForm .= '<input type="button" onclick="ShowWalllLocation()" id="CancelButton" name="CancelButton" value="Cancel">';
				$this->PanelForm .= '</div></div><div style="margin-top:10px; clear:both" />'."\r";
				// Wall End
				$this->PanelForm .= "\r".'<div style="text-align:right; display:inline-block">';
				$this->PanelForm .= 'Panel ID:';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= '<select id="PanelIDDropdownRequired" name="PanelIDDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].'>'."\r\n";
				
				if(!empty($PanelInfo)) 
				{
					$this->PanelForm .= '<option value="'.$PanelInfo['IA_AdPanels_ID'].'" selected>'.$PanelInfo['IA_AdPanels_Name'].'</option>'."\r\n";
					$PanelIDs = mysql_query("SELECT * FROM IA_AdPanels WHERE IA_AdPanels_ID>0 AND IA_AdPanels_ID NOT IN (SELECT IA_Panels_PanelID FROM IA_Panels WHERE IA_Panels_AccountID=".$AccountInfo['IA_Accounts_ID']." AND IA_Panels_AreaID=".$PanelInfo['IA_Panels_AreaID']." AND IA_Panels_RoomID=".$PanelInfo['IA_Panels_RoomID']." AND IA_Panels_LocationID=".$PanelInfo['IA_Panels_LocationID'].") ORDER BY IA_AdPanels_Name", CONN) or die(mysql_error());
					while ($PanelID = mysql_fetch_assoc($PanelIDs))
					{
						$this->PanelForm .= '<option value="'.$PanelID['IA_AdPanels_ID'].'">'.$PanelID['IA_AdPanels_Name'].'</option>'."\r\n";
					}
				}
				else 
				{ $this->PanelForm .= '<option disabled selected>Select A Location Room</option>'."\r\n"; }
				
				$this->PanelForm .= '</select>';
				$this->PanelForm .= '<img id="LoadingPanelIDField" name="LoadingPanelIDField" src="images/loading.gif" align="center" style="text-align:center; margin:0px 3px 0px 3px; width:20px; height:20px; display:none" />';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";

				$this->PanelForm .= "\r".'<div style="text-align:right; white-space:nowrap; display:inline-block">';
				$this->PanelForm .= 'Number of Sections High:';
				$this->PanelForm .= '</div><div style="display:inline-block">';
				$this->PanelForm .= '<input type="text" id="SectionsHighTextBox" name="SectionsHighTextBox" onkeyup="document.getElementById(\'PanelHeightTextBox\').value=(this.value*11)" size="5" maxlength="4" value="'. (!empty($PanelInfo['IA_Panels_High']) ? $PanelInfo['IA_Panels_High'] : '1') .'" />';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= 'Overall Panel Height:';
				$this->PanelForm .= '</div><div style="display:inline-block">';
				$this->PanelForm .= '<input type="text" id="PanelHeightTextBox" name="PanelHeightTextBox" size="5" maxlength="7" value="'. (float)(!empty($PanelInfo['IA_Panels_Height']) ? $PanelInfo['IA_Panels_Height'] : '11') .'" /> (Inches)';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				
				$this->PanelForm .= "\r".'<div style="text-align:right; white-space:nowrap; display:inline-block">';
				$this->PanelForm .= 'Number of Sections Wide:';
				$this->PanelForm .= '</div><div style="display:inline-block">';
				$this->PanelForm .= '<input type="text" id="SectionsWideTextBox" name="SectionsWideTextBox" onkeyup="document.getElementById(\'PanelWidthTextBox\').value=(this.value*8.5)" size="5" maxlength="4" value="'. (!empty($PanelInfo['IA_Panels_High']) ? $PanelInfo['IA_Panels_Wide'] : '1') .'" />';
				$this->PanelForm .= '</div><div style="white-space:nowrap; display:inline-block">';
				$this->PanelForm .= 'Overall Panel Width:';
				$this->PanelForm .= '</div><div style="display:inline-block">';
				$this->PanelForm .= '<input type="text" id="PanelWidthTextBox" name="PanelWidthTextBox" size="5" maxlength="7" value="'. (float)(!empty($PanelInfo['IA_Panels_Width']) ? $PanelInfo['IA_Panels_Width'] : '8.5') .'" /> (Inches)';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				
				$this->PanelForm .= "\r".'<div style="vertical-align:top; text-align:right; white-space:nowrap; display:inline-block">';
				$this->PanelForm .= 'Description:';
				$this->PanelForm .= '</div><div style="display:inline-block">';
				$this->PanelForm .= '<textarea id="PanelDescriptionTextBox" name="PanelDescriptionTextBox" style="width:400px; height:80px" maxlength="512"></textarea>';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				
				$this->PanelForm .= "\r".'<div id="PanelButtons" name="PanelButtons" style="width:550px; text-align:right; display:block">';
				$this->PanelForm .= '<input type="hidden" name="AccountID" value="'.$AccountInfo['IA_Accounts_ID'].'" />';
				$this->PanelForm .= '<input type="hidden" name="PanelID" value="'.$PanelInfo['IA_Panels_ID'].'" />';
				if(!empty($PanelInfo)) 
				{
					$this->PanelForm .= '<input type="submit" id="UpdatePanelLocationButton" name="UpdatePanelLocationButton" value="Update Panel Location"> ';
				}
				else 
				{
					$this->PanelForm .= '<input type="submit" id="AddPanelButton" name="AddPanelButton" value="Add Panel"> ';
				}
				$this->PanelForm .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
				$this->PanelForm .= '</div><div style="margin-top:10px; clear:both" />'."\r";
				
				$this->PanelForm .= '<script type="text/javascript">'."\r";
				$this->PanelForm .= 'function ShowAddArea()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewArea\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'Area\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'none\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= 'function ShowArea()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewArea\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'Area\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'block\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= 'function ShowAddRoom()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewRoom\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'Room\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'none\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= 'function Show()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewRoom\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'Room\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'block\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= 'function ShowAddWallLocation()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewWallLocation\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'WallLocation\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'none\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= 'function ShowWalllLocation()'."\r";
				$this->PanelForm .= '{'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'NewWallLocation\').style.display=\'none\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'WallLocation\').style.display=\'inline-block\';'."\r";
				$this->PanelForm .= "\t".'document.getElementById(\'PanelButtons\').style.display=\'block\';'."\r";
				$this->PanelForm .= '}'."\r";
				$this->PanelForm .= '</script>'."\r";
			//}
			
			
			
/*		
			$this->PanelForm = '<table border="0" align="center" style="width:60%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
			
		// Locaction Name
			$this->PanelForm .= '<tr style="vertical-align:middle; white-space: nowrap">';
			$this->PanelForm .= '<td style="text-align:right">Business Name:</td><td colspan="3">';
			if(isset($AccountID) && !empty($AccountID)) 
			{
				$Accounts = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_ID=".$AccountID, CONN);
				while ($Account = mysql_fetch_assoc($Accounts))
				{
					$UserID = $Account['IA_Accounts_UserID'];
					$this->PanelForm .= '<h2>'.$Account[IA_Accounts_BusinessName].' ('.$Account[IA_Accounts_City].', ';
					$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$Account[IA_Accounts_StateID], CONN);
					while ($State = mysql_fetch_assoc($States))
					{
						$this->PanelForm .= $State[IA_States_Abbreviation];
					}
					$this->PanelForm .= ')</h2>';
				}
			}
			else 
			{
				
			}
			
			$this->PanelForm .= '</td></tr>';

		// Ad Locations
			$this->PanelForm .= '<tr style="vertical-align:middle; white-space: nowrap">';
			$this->PanelForm .= '<td style="text-align:right">Panel Location:</td><td colspan="3">';
			
			if(!empty($PanelLocationID)) 
			{
				$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_ID=".$PanelLocationID." GROUP BY IA_AdLocations_Location ORDER BY IA_AdLocations_Location", CONN);
				while ($AdLocation = mysql_fetch_assoc($AdLocations))
				{
					$this->PanelForm .= ' <input type="text" id="PanelLocationTextBox" name="PanelLocationTextBox" size="30" maxlength="64" value="'.$AdLocation[IA_AdLocations_Location].'" />';
					$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$AccountID.'">';
					$this->PanelForm .= '<input type="hidden" id="PanelLocationID" name="PanelLocationID" value="'.$AdLocation[IA_AdLocations_ID].'">';
					$this->PanelForm .= '<input type="submit" style="font-size:11px" id="UpdatePanelLocationButton" name="UpdatePanelLocationButton" value="Update Panel Location"> ';
					$this->PanelForm .= '<input type="submit" style="font-size:11px" id="DeletePanelLocationButton" name="DeletePanelLocationButton" onclick="return confirm(\'The following panel location information will be deleted.\n-Panel Location Information\n-Associated Panel Location Panels\n-Associated Panel Location Ads\')" value="Delete Panel Location"> ';
					$this->PanelForm .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
				}
				$this->PanelForm .= '</td></tr>';
			}
			else 
			{
				$this->PanelForm .= '<select id="LocationDropdownRequired" name="LocationDropdownRequired" onchange="GetAvailablePanels('.$AccountID.', this.value)" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].')">'."\r\n";
				$this->PanelForm .= '<option value="">Select An Panel Location</option>'."\r\n";
				$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_UserID=".$UserID." GROUP BY IA_AdLocations_Location ORDER BY IA_AdLocations_Location", CONN);
				while ($AdLocation = mysql_fetch_assoc($AdLocations))
				{
					$this->PanelForm .= '<option value="'.$AdLocation[IA_AdLocations_ID].'">'.$AdLocation[IA_AdLocations_Location].'</option>'."\r\n";
				}
				$this->PanelForm .= '</select> <input type="button" onclick="document.getElementById(\'PanelLocationTextBox\').style.display=\'block\'; document.getElementById(\'AddPanelLocationButton\').style.display=\'block\'; this.style.display=\'none\'" id="ShowPanelLocationButton" name="ShowPanelLocationButton" value="Add Panel Location">';
				$this->PanelForm .= ' <input type="text" id="PanelLocationTextBox" name="PanelLocationTextBox" size="30" maxlength="64" style="display:none; color:#aaaaaa" onfocus="this.value=\'\'; this.style.color=\'#000000\'" value="Men\'s Bathroom" />';
				$this->PanelForm .= '<input type="hidden" id="AccountID" name="AccountID" value="'.$AccountID.'">';
				$this->PanelForm .= '<input type="submit" style="display:none; font-size:11px" id="AddPanelLocationButton" name="AddPanelLocationButton" value="Add Panel Location"> ';
				$this->PanelForm .= '</td></tr>';
				
			// Panels
				$this->PanelForm .= '<tr style="vertical-align:middle">';
				$this->PanelForm .= '<td style="text-align:right">Panel ID:</td><td colspan="3"><div id="PanelIDDropdownDIV">';
				$this->PanelForm .= '<select id="PanelIDDropdownRequired" name="PanelIDDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>'."\r\n";
				$this->PanelForm .= '</select></div></td></tr>';
				
				
				
			// Panel Height & Width
				$this->PanelForm .= '<tr style="vertical-align:middle">';
				$this->PanelForm .= '<td style="width:30%; text-align:right">Number of Sections High:</td><td style="width:10%">';
				$this->PanelForm .= ' <input type="text" id="SectionsHighTextBox" name="SectionsHighTextBox" onkeyup="document.getElementById(\'PanelHeightTextBox\').value=(this.value*11)" size="5" maxlength="4" value="1" />';
				$this->PanelForm .= '</td><td style="width:25%; text-align:right">Overall Panel Height:</td><td style="width:35%">';
				$this->PanelForm .= ' <input type="text" id="PanelHeightTextBox" name="PanelHeightTextBox" size="5" maxlength="7" value="11" /> (Inches)';
				$this->PanelForm .= '</td></tr>'."\r\n";
				$this->PanelForm .= '<tr style="vertical-align:middle">';
				$this->PanelForm .= '<td style="text-align:right">Number of Sections Wide:</td><td>';
				$this->PanelForm .= ' <input type="text" id="SectionsWideTextBox" name="SectionsWideTextBox" onkeyup="document.getElementById(\'PanelWidthTextBox\').value=(this.value*8.5)" size="5" maxlength="4" value="1" />';
				$this->PanelForm .= '</td><td style="text-align:right">Overall Panel Width:</td><td>';
				$this->PanelForm .= ' <input type="text" id="PanelWidthTextBox" name="PanelWidthTextBox" size="5" maxlength="7" value="8.5" /> (Inches)';
				$this->PanelForm .= '</td></tr>'."\r\n";
				$this->PanelForm .= '<tr><td style="text-align:right; vertical-align:middle" colspan="4">';
				//$this->AdForm .= '<input type="hidden" name="AdID" value="'.$AdID.'" />';
				$this->PanelForm .= '<input type="hidden" name="AccountID" value="'.$AccountID.'" />';
				$this->PanelForm .= '<input type="submit" name="AddPanelButton" value="Add Panel"> ';
				$this->PanelForm .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
				$this->PanelForm .= '</td></tr>';
			}
			
			$this->PanelForm .= '</table>';
*/	
			return $this->PanelForm;
		}

/*	
		public function BuildPanelLocationList($UserInfo, $AccountID)
		{
			$XML = new DOMDocument();
			$PanelLocationID = null;
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml')) 
			{ }
			else 
			{ 
				$this->GetPanels($UserInfo['UserParentID'], null, $AccountID, null);
			}
			//$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml'));
			//$PanelInfo = json_decode(json_encode($XML),true);
			$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml');
			$PanelsInfo = $XML->getElementsByTagName("Panel");
			$a = 0;
			foreach ($PanelsInfo as $Array) 
			{
				foreach($Array->childNodes as $n) 
				{
					if($n->nodeName != '#text') 
					{  $PanelInfo[$a][$n->nodeName] .= $n->nodeValue; }
				}
				$a++;
			}
			
			for($p=0; $p<count($PanelInfo); $p++) 
			{
				if($PanelInfo[$p]['IA_Panels_AccountID'] == $AccountID && $PanelInfo[$p]['IA_AdLocations_ID'] != $PanelLocationID) 
				{
					$PanelList .= "\r".'<div style="width:200px; display:inline-block">';
					$PanelList .= $PanelInfo[$p]['IA_AdLocations_Location'];
					$PanelList .= '</div><div style="white-space:nowrap; display:inline-block">';
					$PanelList .= '<input type="button" style="font-size:11px" onclick="window.location=\'panels.php?AccountID='.$PanelInfo[$p]['IA_Panels_AccountID'].'&PanelLocationID='.$PanelInfo[$p]['IA_AdLocations_ID'].'\'" id="EditPanelLocationButton'.$PanelInfo[$p]['IA_AdLocations_ID'].'" name="EditPanelLocationButton'.$PanelInfo[$p]['IA_AdLocations_ID'].'" value="Edit"> ';
					//$PanelList .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
					$PanelList .= '</div><div style="margin-top:10px; clear:both" />'."\r";
					$PanelLocationID = $PanelInfo[$p]['IA_AdLocations_ID'];
				}
			}
			
			//$AdLocations = mysql_query("SELECT * FROM IA_Panels, IA_AdLocations WHERE IA_Panels_AccountID=".$AccountID." AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_AdLocations_Location ORDER BY IA_AdLocations_Location", CONN);
			//while ($AdLocation = mysql_fetch_assoc($AdLocations))
			//{
			//	$PanelList .= "\r".'<div style="width:200px; display:inline-block">';
			//	$PanelList .= $AdLocation['IA_AdLocations_Location'];
			//	$PanelList .= '</div><div style="white-space:nowrap; display:inline-block">';
			//	$PanelList .= '<input type="button" style="font-size:11px" onclick="window.location=\'panels.php?AccountID='.$AccountID.'&PanelLocationID='.$AdLocation['IA_AdLocations_ID'].'\'" id="EditPanelLocationButton'.$AdLocation['IA_AdLocations_ID'].'" name="EditPanelLocationButton'.$AdLocation['IA_AdLocations_ID'].'" value="Edit"> ';
			//	$PanelList .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
			//	$PanelList .= '</div><div style="margin-top:10px; clear:both" />'."\r";
			//}

			return $PanelList;
		}
*/	
		public function BuildPanelList($UserInfo, $AccountID, $LocationID)
		{
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
			{ }
			else 
			{ 
				$Accounts = new _Accounts();
				$Accounts->GetAdvertisers($UserInfo['UserParentID'], null);
			}
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml'));
			$Account = json_decode(json_encode($XML),true);
			
			if(isset($Account['Account'][0])) 
			{
				for($a=0; $a<count($Account['Account']); $a++) 
				{
					if(isset($AccountID) && !empty($AccountID)) 
					{
						if($Account['Account'][$a]['IA_Accounts_ID'] == $AccountID) 
						{
							$AccountsInfo[] = $Account['Account'][$a];
							break;
						}
						else 
						{ }
					}
					else 
					{ $AccountsInfo[] = $Account['Account'][$a]; }
				}
			}
			else 
			{ $AccountsInfo[] = $Account['Account']; }
	//print("AccountInfo<pre>". print_r($AccountsInfo,true) ."</pre>");
			for($a=0; $a<count($AccountsInfo); $a++) 
			{
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
				{ }
				else 
				{ 
					$Panels = new _Panels();
					$Panels->GetPanels($UserInfo['UserParentID'], null, $AccountsInfo[$a]['IA_Accounts_ID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml'));
				$PanelInfo = array();
				$Panel = json_decode(json_encode($XML),true);
				if(isset($Panel['Panel'][0])) 
				{ $PanelInfo = $Panel; }
				else 
				{ $PanelInfo['Panel'][] = $Panel['Panel']; }
		//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");
				$PanelList .= '<div style="width:500px">';
				$PanelList .= '<h2>'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].'</h2>';
				for($p=0; $p<count($PanelInfo['Panel']); $p++) 
				{
					if($PanelInfo['Panel'][$p]['IA_Panels_AccountID'] == $AccountsInfo[$a]['IA_Accounts_ID']) 
					{
						if($PanelInfo['Panel'][$p]['IA_Panels_RoomID'] != $RoomID) 
						{
							$Location = null;
							$RoomID = $PanelInfo['Panel'][$p]['IA_Panels_RoomID'];
							$PanelList .= "\n".'<ul>'.$PanelInfo['Panel'][$p]['IA_LocationRooms_Room'];
						}
					
						if($PanelInfo['Panel'][$p]['IA_Panels_RoomID'] == $RoomID && $PanelInfo['Panel'][$p]['IA_Panels_LocationID'] != $Location) 
						{
							$Location = $PanelInfo['Panel'][$p]['IA_Panels_LocationID'];
							$PanelList .= "\n".'<ul>'.$PanelInfo['Panel'][$p]['IA_AdLocations_Location'];
						}

						$PanelID = $PanelInfo['Panel'][$p]['IA_Panels_PanelID'];
						$PanelList .= "\t\n".'<li style="line-height:24px">';
						$PanelList .= 'Panel: '.$PanelInfo['Panel'][$p]['IA_AdPanels_Name'].' ';
						$PanelList .= '('.$PanelInfo['Panel'][$p]['IA_Panels_Wide'].'W x '.$PanelInfo['Panel'][$p]['IA_Panels_High'].'H Ads) ';
						// WIP
						$PanelList .= '<input type="button" style="font-size:11px" onclick="window.location=\'panels.php?AccountID='.$PanelInfo['Panel'][$p]['IA_Panels_AccountID'].'&PanelID='.$PanelInfo['Panel'][$p]['IA_Panels_ID'].'\'" id="EditPanelButton'.$PanelInfo['Panel'][$p]['IA_Panels_ID'].'" name="EditPanelButton'.$PanelInfo['Panel'][$p]['IA_Panels_ID'].'" value="Edit Panel"> ';
						//
						$PanelList .= '<input type="button" style="font-size:11px" onclick="DeleteRunReportPanel('.$UserInfo['UserParentID'].', '.$AccountsInfo[$a]['IA_Accounts_ID'].', '.$PanelInfo['Panel'][$p]['IA_Panels_ID'].')" id="DeletePanelButton'.$PanelInfo['Panel'][$p]['IA_Panels_ID'].'" name="DeletePanelButton'.$PanelInfo['Panel'][$p]['IA_Panels_ID'].'" value="Delete Panel" /> ';
						if(!empty($PanelInfo['Panel'][$p]['IA_Panels_Description'])) 
						{
							$PanelList .= '<p style="margin-top:0px; line-spacing:10px">Description: '.$PanelInfo['Panel'][$p]['IA_Panels_Description'].'</p>';
						}
						$PanelList .= '</li>'."\n";
					
					
					
						if($PanelInfo['Panel'][($p+1)]['IA_Panels_LocationID'] != $Location || $PanelInfo['Panel'][($p+1)]['IA_Panels_RoomID'] != $RoomID) 
						{ $PanelList .= '</ul>'."\n"; }
						
						if($PanelInfo['Panel'][($p+1)]['IA_Panels_RoomID'] != $PanelInfo['Panel'][($p)]['IA_Panels_RoomID']) 
						{ $PanelList .= '</ul>'."\n"; }
						
					}
				}
				$PanelList .= '</div>';
			}
			return $PanelList;
		}
		
		public function AddArea($UserID, $AreaInfo) 
		{
			$Insert = "INSERT INTO IA_LocationAreas (";
			$Insert .= "IA_LocationAreas_UserID, ";
			$Insert .= "IA_LocationAreas_AccountID, ";
			$Insert .= "IA_LocationAreas_Area) VALUES ";
			$Insert .= "(";
			$Insert .= "'".$UserID."', ";
			$Insert .= "'".$AreaInfo['AccountID']."', ";
		 	$Insert .= "'".$AreaInfo['AreaTextBox']."'";
			$Insert .= ")";
		    
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}
		
		public function AddRoom($UserID, $RoomInfo) 
		{
			$InsertRoom = "INSERT INTO IA_LocationRooms (";
			$InsertRoom .= "IA_LocationRooms_UserID, ";
			$InsertRoom .= "IA_LocationRooms_Room) VALUES ";
			$InsertRoom .= "(";
			$InsertRoom .= "'".$UserID."', ";
			$InsertRoom .= "'".$RoomInfo['RoomTextBox']."'";
			$InsertRoom .= ")";
		    
			if (mysql_query($InsertRoom, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}
	
		public function AddWallLocation($UserID, $WallLocationInfo) 
		{
			$InsertWallLocation = "INSERT INTO IA_AdLocations (";
			$InsertWallLocation .= "IA_AdLocations_UserID, ";
			$InsertWallLocation .= "IA_AdLocations_Location) VALUES ";
			$InsertWallLocation .= "(";
			$InsertWallLocation .= "'".$UserID."', ";
			$InsertWallLocation .= "'".$WallLocationInfo['WallLocationTextBox']."'";
			$InsertWallLocation .= ")";
		    
			if (mysql_query($InsertWallLocation, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}

		public function UpdatePanelLocation($UserInfo, $PanelInfo) 
		{
			$Confirmation = false;
			$Update = "UPDATE IA_Panels SET";
			$Update .= " IA_Panels_AreaID='".trim($PanelInfo['AreaDropdownRequired']);
			$Update .= "', IA_Panels_RoomID='".trim($PanelInfo['RoomDropdownRequired']);
			$Update .= "', IA_Panels_LocationID='".trim($PanelInfo['LocationDropdownRequired']);
			$Update .= "', IA_Panels_PanelID='".trim($PanelInfo['PanelIDDropdownRequired']);
			$Update .= "', IA_Panels_High='".trim($PanelInfo['SectionsHighTextBox']);
			$Update .= "', IA_Panels_Wide='".trim($PanelInfo['SectionsWideTextBox']);
			$Update .= "', IA_Panels_Height='".trim($PanelInfo['PanelHeightTextBox']);
			$Update .= "', IA_Panels_Width='".trim($PanelInfo['PanelWidthTextBox']);
			$Update .= "', IA_Panels_Description='".trim($PanelInfo['PanelDescriptionTextBox']);
			$Update .= "' WHERE IA_Panels_ID=".$PanelInfo['PanelID'];
		    
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Advertisements = new _Advertisements();
				$Advertisements->GetAds($UserInfo['UserParentID'], null);
				$this->GetPanels($UserInfo['UserParentID'], null, $PanelInfo['AccountID'], null);
				$Confirmation = true;
			}
			/*
			$Confirmation = true;
			$Update = "UPDATE IA_AdLocations SET ";
			$Update .= "IA_AdLocations_Location='".trim($PanelLocationInfo['PanelLocationTextBox'])."' ";
			$Update .= "WHERE IA_AdLocations_ID='".$PanelLocationInfo['PanelLocationID']."'";
			
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				//$Advertisements = new _Advertisements();
				//$Advertisements->GetAds($UserInfo['UserParentID'], null);
				array_map('unlink', glob(ROOT."/users/".$UserInfo['UserParentID']."/data/".$UserInfo['UserParentID']."_*_PanelsInfo.xml"));
			}
			else
			{ $Confirmation = false; }
			*/
			return $Confirmation;
		}
//WIP
		public function DeletePanelLocation($UserInfo, $AccountID, $PanelLocationID) 
		{
			$Confirmation = false;
			$DeletePanel = 'DELETE FROM IA_Panels WHERE IA_Panels_AccountID='.$AccountID.' AND IA_Panels_LocationID='.$PanelLocationID;
			if (mysql_query($DeletePanel, CONN) or die(mysql_error())) 
			{
				/*
				$Update = "UPDATE IA_Ads SET ";
				$Update .= "IA_Ads_Archived=1";
				$Update .= " WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_LocationID=".$PanelLocationID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{ }
				*/
				$DeleteAdPanel = 'DELETE FROM IA_Ads WHERE IA_Ads_AccountID='.$AccountID.' AND IA_Ads_LocationID='.$PanelLocationID;
				if (mysql_query($DeleteAdPanel, CONN) or die(mysql_error())) 
				{ }
				$DeleteAdPanel = 'DELETE FROM IA_AdLocations WHERE IA_AdLocations_ID='.$PanelLocationID;
				if (mysql_query($DeleteAdPanel, CONN) or die(mysql_error())) 
				{ }
				$Confirmation = true;
				
				$Advertisements = new _Advertisements();
				$Advertisements->GetAds($UserInfo['UserParentID'], null);
				$this->GetPanels($UserInfo['UserParentID'], null, $AccountID, null);
			}
			return $Confirmation;
		}
		
		public function BuildPanel($UserInfo, $AccountID, $PanelID, $AdID, $Mode, $Scale)
		{
			//$XML = new DOMDocument();
			$this->OpenSectionsCount = 0;

			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml')) 
			{ }
			else 
			{ 
				//$Panels = new _Panels();
				$this->GetPanels($UserInfo['UserParentID'], null, $AccountID, null);
			}
			//ini_set('memory_limit', '512M');
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml', null, LIBXML_COMPACT);

//print("<pre>XML:". print_r($XML,true) ."</pre>");
//print("<pre>Errors:". print_r(libxml_get_last_error(),true) ."</pre>");
			//$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountID.'_PanelsInfo.xml'));
			$PanelInfo = json_decode(json_encode($XML),true);


			if(isset($PanelInfo['Panel'][0])) 
			{
				for($a=0; $a<count($PanelInfo['Panel']); $a++) 
				{
					if($PanelInfo['Panel'][$a]['IA_Panels_ID'] == $PanelID) 
					{
						$Panel = $PanelInfo['Panel'][$a];
						break;
					}
				}
			}
			else 
			{ $Panel = $PanelInfo['Panel']; }
//print("<pre>". print_r($Panel,true) ."</pre>");
			if(!empty($Panel)) 
			{
				$PanelHigh = $Panel['IA_Panels_High'];
				$PanelWide = $Panel['IA_Panels_Wide'];
				$PanelSectionCount = $PanelHigh * $PanelWide;
				$PanelHeight = number_format((($Panel['IA_Panels_Height'] * 72) * $Scale), 0, '.', '');
				$PanelWidth = number_format((($Panel['IA_Panels_Width'] * 72) * $Scale), 0, '.', '');
				$DefaultSectionHeight = number_format(($PanelHeight / $PanelHigh), 0, '.', '');
				$DefaultSectionWidth = number_format(($PanelWidth / $PanelWide), 0, '.', '');
				
				$PanelLayout = "\n".'<table border="0" cellspacing="0" cellpadding="0" class="panel" style="height:'.$PanelHeight.'px; width:'.$PanelWidth.'px;">';
				$PanelLayout .= '<tr><td style="height:30px; text-align:center; vertical-align:middle">'."\n";
				
				$PanelLayout .= '<h2 style="font-size:10px; margin-bottom:0px">'.$Panel['IA_LocationAreas_Area'].' '.$Panel['IA_LocationRooms_Room'].'<br />('.$Panel['IA_AdLocations_Location'].') '.$Panel['IA_AdPanels_Name'].'</h2>'."\n";
				//$PanelLayout .= '<p style="font-size:9px; margin:0px">Panel WxH: '. floatval($Panel['IA_Panels_Width']) .'" x '. floatval($Panel['IA_Panels_Height']) .'"</p>'."\n";
				$PanelLayout .= '<pre style="display:none">'."\n";
				$PanelLayout .= 'Account ID: '.$Panel['IA_Panels_AccountID']."\n";
				$PanelLayout .= 'Panel ID: '.$Panel['IA_Panels_ID']."\n";
				$PanelLayout .= 'Panel WxH: '.$PanelWidth.' x '.$PanelHeight."\n";
				$PanelLayout .= 'Section Wide: '.$Panel['IA_Panels_Wide'].' High: '.$Panel['IA_Panels_High']."\n";
				$PanelLayout .= '</pre>'."\n";
				
				$PanelLayout .= '</td></tr>'."\n";
				$PanelLayout .= '<tr><td style="vertical-align:top; text-align:center">'."\n";
				//$AdList = array();
				if(isset($Panel['Ads']['Ad'][0])) 
				{
					
					for($a=0; $a<count($Panel['Ads']['Ad']); $a++) 
					{ 
						$AdList[] = $Panel['Ads']['Ad'][$a]; 
					}
				}
				else 
				{ $AdList[] = $Panel['Ads']['Ad']; }
//print("<pre>". print_r($AdList,true) ."</pre>");
			
				$a = 0;
				$SectionNumber = 1;
				for ($Row=1; $Row<=$Panel['IA_Panels_High']; $Row++)
				{
					$SkipCell = false;
					$RowWidth = $PanelWidth;
					// Section Panels Table Row Start
					$SectionHeight = number_format(((($Panel['IA_Panels_Height'] / $Panel['IA_Panels_High']) * 72) * $Scale), 0, '.', '');
					$SectionWidth = number_format(((($Panel['IA_Panels_Width'] / $Panel['IA_Panels_Wide']) * 72) * $Scale), 0, '.', '');
					$SectionLayout = '<table border="0" cellspacing="0" cellpadding="0" style="border:1px solid #cccccc; vertical-align:top; margin:0px">'."\n";
					$SectionLayout .= '<tr>'."\n";
					
					for ($Cell=1; $Cell<=$Panel['IA_Panels_Wide']; $Cell++)
					{
						switch ($Mode)
						{
							case 'ImageOnly':
								$SectionInfo = '';
								break;
							default:
								//$SectionInfo = "\n".'<br /><pre style="display:none">Row: '.$Row.' Cell: '.$Cell."\n".'<br />Wide: '.$SectionWidth.' Height: '.$SectionHeight."\n".'<br /> Row W: '.$RowWidth."\n".'<br />Section: '.$SectionNumber.'</pre>'."\n";
								break;
						}

						if((isset($AdList[$a]['IA_Ads_AdLibraryID']) && !empty($AdList[$a]['IA_Ads_AdLibraryID'])) && $SectionNumber == $AdList[$a]['IA_Ads_PanelSectionID']) 
						{
							$SectionHeight = number_format((($AdList[$a]["IA_AdLibrary_Height"] * 72) * $Scale), 0, '.', '');
							$SectionWidth = number_format((($AdList[$a]["IA_AdLibrary_Width"] * 72) * $Scale), 0, '.', '');
							$AdHeight = $SectionHeight;
							$AdWidth = $SectionWidth;
							
							if($AdID != $AdList[$a]['IA_Ads_ID'] && $Mode == 'ImageOnly' && $UserInfo['IA_Users_Type'] == 4) 
							{
								$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" src="images/AdHound_Icon.png" style="border:1px solid #eeeeee; height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
							}
							else 
							{
								
							}
							
							if($AdList[$a]['IA_AdTypes_ID'] == 9) 
							{
								$AdHeight = $AdHeight - 20;
							}
							
							if ($AdList[$a]['IA_Ads_Placement'] == 1)
							{
								if (file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'BW.jpg')) 
								{ }
								else 
								{
									if (file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'.jpg')) 
									{
										$Image = imagecreatefromjpeg(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'.jpg');
										if($Image && imagefilter($Image, IMG_FILTER_GRAYSCALE))
										{ imagejpeg($Image, ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'BW.jpg'); }
										else
										{ }
										imagedestroy($Image);
									}
									else 
									{ }
								}
								if (file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'BW.jpg')) 
								{
									$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" onclick="document.getElementById(\'PanelAdOptions'.$AdList[$a]['IA_Ads_ID'].'\').style.display=\'block\'" src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'BW.jpg" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
								}
								else 
								{
									$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" src="images/AdHound_Icon.png" style="border:1px solid #eeeeee; height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
								}
							}
							else
							{
								if (file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'.jpg')) 
								{
									$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" onclick="document.getElementById(\'PanelAdOptions'.$AdList[$a]['IA_Ads_ID'].'\').style.display=\'block\'" src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'.jpg" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
								}
								else 
								{
									$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" src="images/AdHound_Icon.png" style="border:1px solid #eeeeee; height:'.$AdHeight.'px; width:'.$AdWidth.'px; cursor:pointer" border="0" />'."\n";
								}
							}
							
							if ($AdList[$a]['IA_Ads_Placement'] == 1)
							{ $AdHeight = $AdHeight + 4; }
							else
							{
								$PlaceAd .= '<div style="display:block; background-color:#eeeeee; height:20px; width:100%; color:#000000; font-weight:bold">Change Ad</div>'."\n";
							}
							
							$PlaceAd .= '<div style="display:block; background-color:#eeeeee; min-height:20px; width:100%; color:#000000; font-weight:normal; font-size:8px">'.$AdList[$a]['IA_Advertisers_BusinessName'].'</div>'."\n";
							
							if($AdList[$a]['IA_AdTypes_ID'] == 9) 
							{
								$PlaceAd .= '<div style="display:block; background-color:#ee2722; height:20px; width:100%; color:#ffffff; font-weight:bold">'.$AdList[$a]['IA_AdTypes_Name'].'</div>'."\n";
							}
							else 
							{ }
							$PlaceAd .= '<div id="PanelAdOptions'.$AdList[$a]['IA_Ads_ID'].'" name="PanelAdOptions'.$AdList[$a]['IA_Ads_ID'].'" style="background: url(images/table_background.png); background-repeat:repeat-x; background-color:#ffffff; display:none; text-align:center; vertical-align:middle">';
							switch ($Mode)
							{
								case 'ImageOnly':
									switch($UserInfo['IA_Users_Type']) 
									{
										case 1:
										case 3:
											//ShowPanelSections('.$_SESSION['UserType'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdownRequired\').value, this.value)
											//$PlaceAd .= '<a onclick="DeleteThumbnailAd('.$UserType.', '.$AdList[$a]['IA_Ads_AdLibraryID'].', '.$AdList[$a]['IA_Ads_ID'].', '.$AdList[$a]['IA_Ads_AccountID'].', '.$AdList[$a]['IA_Ads_PanelID'].', '.$AdList[$a]['IA_Ads_LocationID'].', \''.$Mode.'\', '.$Scale.')">[Delete]</a><br />';
											break;
										default:
											break;
									}
									break;
								default:
									if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['EditAds']))	
									{
										$PlaceAd .= '<a onclick="window.location=\'ads.php?AccountID='.$Panel['IA_Panels_AccountID'].'&PanelID='.$Panel['IA_Panels_ID'].'&AdvertiserID='.$AdList[$a]['IA_Ads_AdvertiserID'].'&AdID='.$AdList[$a]['IA_Ads_ID'].'&ModeType=EditAdvertisement\'">[Edit]</a> | ';
										$PlaceAd .= '<a onclick="DeleteRunReportAd('.$UserInfo['IA_Users_Type'].', '.$AdList[$a]['IA_Ads_AdvertiserID'].', '.$AdList[$a]['IA_Ads_ID'].', '.$AdList[$a]['IA_Ads_AccountID'].', '.$Panel['IA_Panels_ID'].', '.$AdList[$a]['IA_Ads_PanelSectionID'].', \''.$Mode.'\', '.$Scale.')">[Delete]</a><br />';
										// WIP
											//$PlaceAd .= '<a onclick="PlaceAd('.$AdList[$a]['IA_Ads_ID'].')">[Place Ad]</a><br />';
									}
									break;
							}
							
							$PlaceAd .= '<a onclick="window.location=\'users/'.$UserInfo['UserParentID'].'/images/ads/ad'.$AdList[$a]['IA_Ads_AdLibraryID'].'.jpg\'">[Download]</a><br />';
							$PlaceAd .= '<a onclick="document.getElementById(\'PanelAdOptions'.$AdList[$a]['IA_Ads_ID'].'\').style.display=\'none\'">[Hide]</a>';
							$PlaceAd .= '</div>';
						
							$a++;
						}
						else 
						{
//echo $AdID.'!='.$AdList[$a]['IA_Ads_ID'].'&&'.$Mode.'=='.'ImageOnly'.'|| ('.$AdID.'=='.$AdList[$a]['IA_Ads_ID'].'&&'.$Mode.'=='.'ImageOnly'.'&&'.$SectionNumber.'!='.$AdList[$a]['IA_Ads_PanelSectionID'].')';
							
							if($AdID != $AdList[$a]['IA_Ads_ID'] && $Mode == 'ImageOnly' || ($AdID == $AdList[$a]['IA_Ads_ID'] && $Mode == 'ImageOnly' && $SectionNumber != $AdList[$a]['IA_Ads_PanelSectionID'])) 
							{
								$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" src="images/AdHound_Icon.png" style="border:1px solid #eeeeee; height:'.$SectionHeight.'px; width:'.$SectionWidth.'px; cursor:pointer" border="0" />'."\n";
							}
							else 
							{
								if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['EditAds']))	
								{
									//$PlaceAd = '<input type="button" onclick="window.location=\'ads.php?AccountID='.$Panel['IA_Panels_AccountID'].'&RoomID='.$Panel['IA_Panels_RoomID'].'&LocationID='.$Panel['IA_Panels_LocationID'].'&PanelID='.$Panel['IA_Panels_PanelID'].'&PanelWidth='. number_format((($PanelWidth / 72) / $Scale), 1, '.', ',') .'&PanelHeight='. number_format((($PanelHeight / 72) / $Scale), 0, '.', ',') .'&PanelSectionID='.$SectionNumber.'&PanelSectionWidth='. number_format((($SectionWidth / 72) / $Scale), 1, '.', ',') .'&PanelSectionHeight='. number_format((($SectionHeight / 72) / $Scale), 0, '.', ',') .'&ModeType=PlaceAdvertisement\'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; cursor:pointer; font-size:10px" value="Section: '.$SectionNumber."\n".'Open'."\n".'Add Advertisement" />';
									$PlaceAd = '<input type="button" onclick="window.location=\'ads.php?AccountID='.$Panel['IA_Panels_AccountID'].'&PanelID='.$Panel['IA_Panels_ID'].'&PanelWidth='.$Panel['IA_Panels_Width'].'&PanelHeight='.$Panel['IA_Panels_Height'].'&PanelSectionID='.$SectionNumber.'&PanelSectionWidth='. number_format((($SectionWidth / 72) / $Scale), 1, '.', ',') .'&PanelSectionHeight='. number_format((($SectionHeight / 72) / $Scale), 0, '.', ',') .'&ModeType=PlaceAdvertisement\'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; cursor:pointer; font-size:10px" value="Section: '.$SectionNumber."\n".'Open'."\n".'Add Advertisement" />';
								}
								else 
								{
									$PlaceAd = "\t".'<img id="Ad'.$AdList[$a]['IA_Ads_ID'].'" name="Ad'.$AdList[$a]['IA_Ads_ID'].'" src="images/AdHound_Icon.png" style="border:1px solid #eeeeee; height:'.$SectionHeight.'px; width:'.$SectionWidth.'px; cursor:pointer" border="0" />'."\n";
								}
							}
							$this->OpenSectionsCount++;
						}

						if(($SectionWidth < $RowWidth || $SectionWidth <> $PanelWidth) && ($AdWidth <= $PanelWidth)) 
						{
							//echo '<br />'.$SectionWidth.'<'.$RowWidth.'||'.$SectionWidth.'<>'.$PanelWidth;
							$SectionLayout .= '<td id="1Panel'.$PanelID.'Section'.$SectionNumber.'" name="Panel'.$PanelID.'Section'.$SectionNumber.'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; text-align:center; vertical-align:middle; cursor:pointer">'."\n";
							$SectionLayout .= $PlaceAd;
							$SectionLayout .= $SectionInfo;
							$SectionLayout .= '</td>'."\n";
						}
						else 
						{
							if($SectionWidth == $PanelWidth && $Cell > 1) 
							{
								$SectionLayout .= '<td id="2Panel'.$PanelID.'Section'.$SectionNumber.'" name="Panel'.$PanelID.'Section'.$SectionNumber.'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; text-align:center; vertical-align:middle; cursor:pointer">'."\n";
								$SectionLayout .= 'Section: '.$SectionNumber.'<br />Open';
								$this->OpenSectionsCount++;
								//$SectionLayout .= '<input type="button" onclick="window.location=\'ads.php?AccountID='.$Panel['IA_Panels_AccountID'].'&LocationID='.$Panel['IA_Panels_LocationID'].'&PanelID='.$Panel['IA_Panels_PanelID'].'&PanelSectionID='.$SectionNumber.'&PanelSectionWidth='. number_format((($SectionWidth / 72) / $Scale), 1, '.', ',') .'&PanelSectionHeight='. number_format((($SectionHeight / 72) / $Scale), 0, '.', ',') .'&ModeType=PlaceAdvertisement\'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; cursor:pointer" value="Section: '.$SectionNumber."\n".' Open'."\n".'Add Advertisement" />';
								$SectionLayout .= $SectionInfo;
								$SectionLayout .= '</td>'."\n";
								$a = $a - 1;
								$Cell = 1;
								//$SectionNumber++;
								//echo '1: '. ceil($SectionWidth / $DefaultSectionWidth);
								//$SectionNumber = $SectionNumber + ceil($SectionWidth / $DefaultSectionWidth);
							}
							else 
							{
								// Takes up entire row
								$SectionLayout .= '<td id="3Panel'.$PanelID.'Section'.$SectionNumber.'" name="Panel'.$PanelID.'Section'.$SectionNumber.'" style="width:'.$SectionWidth.'px; height:'.$SectionHeight.'px; text-align:center; vertical-align:middle; cursor:pointer">'."\n";
								$SectionLayout .= $PlaceAd;
								$SectionLayout .= $SectionInfo;
								$SectionLayout .= '</td>'."\n";
								
								$SectionNumber = $SectionNumber + $PanelWide;
							}
							break;
						}
						
						$RowWidth = $SectionWidth - $RowWidth;
						$SectionNumber++;
						
						/*
						if($SectionNumber <= $PanelSectionCount) 
						{
							$SectionNumber++;
						}
						*/
					}
					$SectionLayout .= '</tr>'."\n";
					$SectionLayout .= '</table>'."\n\r";
					
					// Section Panels Table Row End
					$PanelLayout .= $SectionLayout;
					
					if($SectionHeight > $DefaultSectionHeight) 
					{
						$Row = ceil($SectionHeight / $DefaultSectionHeight);
						//echo '2: '. ceil($SectionWidth / $DefaultSectionWidth);
						$SectionNumber = $SectionNumber + ceil($SectionWidth / $DefaultSectionWidth);
						if($SectionNumber > $PanelSectionCount) 
						{
							break;
						}
						//$Row++;
						//echo '<br />Test 1<br />';
						//if($SectionHeight >= $PanelHeight) 
						//{
							//$Row =  ceil($SectionHeight / $DefaultSectionHeight);
							//$Row = $Panel['IA_Panels_High'];
							//echo '<br />Test 2<br />';
						//}
						//else 
						//{ }
					}
					elseif($SectionHeight > $PanelHeight) 
					{
						$Row = $Panel['IA_Panels_High'];
					}
				}
				
				$PanelLayout .= '</td></tr>';
				$PanelLayout .= '</table>';
			}
			
			return $PanelLayout;
		}

	}
?>
