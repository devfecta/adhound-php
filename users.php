<?php
	include "configuration/header.php";

	$ErrorMessage = null;
	$RequiredField = null;
	
	$Users = new _Users();

// User Account
	if (isset($_POST['RegisterButton'])) 
	{
		//$Users = new _Users();
		$UsersInfo = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_Username='".$_POST["UsernameTextBoxRequired"]."'", CONN);
		$UserCount = mysql_num_rows($UsersInfo);
		
		if($UserCount > 0) 
		{
			$_SESSION['ErrorMessage'] = "Username already exsists. Please choose a different username.";
		}
		elseif(strlen($_POST["UsernameTextBoxRequired"]) < 5) 
		{
			$_SESSION['ErrorMessage'] = "Username is too short.";
		}
		else 
		{
			if ($Users->Validate($_POST))
			{
				$Users->AddUser(null, $_POST, $UserInfo);
				if (isset($UserInfo['IA_Users_ID'])) 
				{
					// Dealer registers a user
					header ('Location: users.php');
				}
				else 
				{ }
			}
			else 
			{}
		}
	}
	else 
	{ 
		if (isset($UserInfo['IA_Users_ID'])) 
		{
			$Username = '';
			$FirstName = '';
			$LastName = '';
			$Phone = $UserInfo['IA_Users_Phone'];
			$Fax = $UserInfo['IA_Users_Fax'];
			$Email = '';
		}
		else 
		{ }
	}
// User Account - Update
	if (isset($_POST['UpdateButton'])) 
	{
		header ("Location: index.php");
		if ($Users->UpdateChildUser($_POST, $UserInfo))
		{
			header ("Location: users.php");
		}
		else
		{
			header ("Location: users.php?ModeType=EditUser&UserID=".$_POST['UserID']);
		}
	}
	else 
	{ }
/*
// User Account - Delete
	if (isset($_POST['DeleteButton'])) 
	{
		if ($Users->DeleteUser($_POST['UserID'], $_POST['UserTypeID']))
		{
			header ("Location: users.php");
		}
		else
		{ }
	}
	else 
	{ }
*/
// Standard Cancel
	if (isset($_POST['CancelButton'])) 
	{
		unset($_SESSION['ModeType']);
	}

	switch ($UserInfo['IA_Users_Type']) 
	{
		case 4:
			break;
		default:
			$PageTitle = '<h1 style="margin:0px 0px 10px 0px">'.$UserInfo['IA_Users_BusinessName'].'</h1>';
			break;
	}
?>

<form id="UserForm" name="UserForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div style="width:90%; text-align:left; vertical-align:top; display:block; padding-top:10px">
	<?php echo $PageTitle; ?>

<?php 
//print("UserInfo<pre>". print_r($UserInfo,true) ."</pre>");
// List Users
switch($_REQUEST['ModeType']) 
{
	case 'AddUser':
	case 'EditUser':
		if(isset($_REQUEST['UserID']) && !empty($_REQUEST['UserID'])) 
		{
			for($u=0; $u<count($UserInfo['Users']); $u++) 
			{
				if($UserInfo['Users'][$u]['IA_Users_ID'] == $_REQUEST['UserID']) 
				{
					$User = $UserInfo['Users'][$u];
					$UserID = $UserInfo['Users'][$u]['IA_Users_ID'];
					break;
				}
			}
			
			$Username = $User['IA_Users_Username'];
			$FirstName = $User['IA_Users_FirstName'];
			$LastName = $User['IA_Users_LastName'];
			$Phone = $User['IA_Users_Phone'];
			$Fax = $User['IA_Users_Fax'];
			$Email = $User['IA_Users_Email'];
			$UserType = $User['IA_Users_Type'];
			//print("UserInfo<pre>". print_r($User['Preferences'],true) ."</pre>");
			$ReadOnly= ' readonly="readonly" disabled="disabled"';
		}
		else 
		{ $User = array(null); $ReadOnly= null; $UserID = null; }
		echo '<div style="width:450px; display:inline-block">';
			echo '<div id="PageTitle" style="display:block; font-size:18px;">User Information</div>';
			echo '<div style="width:100px; margin:5px 5px 5px 0px; text-align:right; display:inline-block; float:left">Username:</div>';
			echo '<div style="width:320px; margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" name="UsernameTextBoxRequired" onkeyup="CheckUsername(this.value)" size="20" maxlength="30" '.$_SESSION['RequiredFields'].' style=""'.$ReadOnly.' value="'.$Username.'" /> * ';
			if($_REQUEST['ModeType'] == 'EditUser' && (isset($_REQUEST['UserID']) && !empty($_REQUEST['UserID']))) 
			{
				echo '<input type="button" id="ResetPasswordButton" name="ResetPasswordButton" onclick="window.location=\'reset.php?Username='.$Username.'\'" value="Reset Password">';
			}
			else 
			{ }
			echo '<div id="SearchResults" style="width:150px; display:inline-block; float:right"></div>';
			echo '</div>';
			echo '<div style="width:auto; clear:both;"></div>';
		
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">First Name:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" id="FirstNameTextBoxRequired" name="FirstNameTextBoxRequired" size="20" maxlength="30"'.$_SESSION['RequiredFields'].' style=""'.$ReadOnly.' value="'.$FirstName.'" /> *';
			echo '</div>';
			echo '<div style="width:auto; clear:both"></div>';
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">Last Name:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" id="LastNameTextBoxRequired" name="LastNameTextBoxRequired" size="20" maxlength="30"'.$_SESSION['RequiredFields'].' style=""'.$ReadOnly.' value="'.$LastName.'" /> *';
			echo '</div>';
			echo '<div style="width:auto; clear:both"></div>';
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">Phone:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" name="PhoneTextBoxRequired" size="15" maxlength="20"'.$_SESSION['RequiredFields'].' style=""'.$ReadOnly.' value="'.$Phone.'" /> *';
			echo '</div>';
			echo '<div style="width:auto; clear:both"></div>';
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">Fax:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" name="FaxTextBox" size="15" maxlength="20" style=""'.$ReadOnly.' value="'.$Fax.'" />';
			echo '</div>';
			echo '<div style="width:auto; clear:both"></div>';
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">E-mail:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<input type="text" name="EmailTextBoxRequired" size="40" maxlength="100"'.$_SESSION['RequiredFields'].' style=""'.$ReadOnly.' value="'.$Email.'" /> *';
			echo '</div>';
			/*
			echo '<div style="width:auto; clear:both"></div>';
			echo '<div style="margin:5px 5px 5px 0px; width:100px; text-align:right; display:inline-block; float:left">User Type:</div>';
			echo '<div style="margin:5px 5px 5px 0px; display:inline-block; float:left">';
			echo '<select id="UserTypeDropdown" name="UserTypeDropdown" title="Sets User Type">';
			echo '<option value="3">Dealer Assistant/Runner</option>';
			//echo '<option value="5">Runner</option>';
			echo '</select> *';
			echo '</div>';
			*/
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="width:300px; display:inline-block">';
			echo '<div style="display:block; font-size:14px;">User Preferences</div>';
			echo '<div style="width:200px; margin:9px 5px 5px 0px; text-align:left; display:block; float:left">';
			$PreferenceTypes = mysql_query("SELECT * FROM IA_Preferences GROUP BY IA_Preferences_Type ORDER BY IA_Preferences_TypeName DESC", CONN);
			while($PreferenceType = mysql_fetch_assoc($PreferenceTypes))
			{
				echo '<ul style="list-style-type:none">'.$PreferenceType['IA_Preferences_TypeName'];
				if(isset($User['Preferences']) && !empty($User['Preferences'])) 
				{
					$Preferences = mysql_query("SELECT * FROM IA_Preferences WHERE IA_Preferences_Type=".$PreferenceType['IA_Preferences_Type']." ORDER BY IA_Preferences_Name ASC", CONN);
					while($Preference = mysql_fetch_assoc($Preferences))
					{
						echo '<li><label title="'.$Preference['IA_Preferences_Description'].'"><input type="checkbox" id="Preference'.$Preference['IA_Preferences_ID'].'" name="Preference'.$Preference['IA_Preferences_ID'].'" value="'.$Preference['IA_Preferences_Type'].'-'.$Preference['IA_Preferences_ID'].'" />'.$Preference['IA_Preferences_Name'].'</label></li>';
						if(isset($User['Preferences'][$PreferenceType['IA_Preferences_Type']])) 
						{
							foreach($User['Preferences'][$PreferenceType['IA_Preferences_Type']] as $PreferenceKey => $PreferenceValue)
							{
								if($PreferenceValue == $Preference['IA_Preferences_ID']) 
								{
									//$PreferenceList = '<li><label title="'.$Preference['IA_Preferences_Description'].'"><input type="checkbox" id="Preference'.$Preference['IA_Preferences_ID'].'" name="Preference'.$Preference['IA_Preferences_ID'].'" value="'.$Preference['IA_Preferences_ID'].'" checked />'.$Preference['IA_Preferences_Name'].'</label></li>';
									$SelectedPreferences .= 'document.getElementById("Preference'.$Preference['IA_Preferences_ID'].'").checked=true;'."\n";
								}
								else 
								{ }
							}
						}
					}
				}
				else 
				{
					$Preferences = mysql_query("SELECT * FROM IA_Preferences WHERE IA_Preferences_Type=".$PreferenceType['IA_Preferences_Type']." ORDER BY IA_Preferences_Name ASC", CONN);
					while($Preference = mysql_fetch_assoc($Preferences))
					{
						echo '<li><label title="'.$Preference['IA_Preferences_Description'].'"><input type="checkbox" id="Preference'.$Preference['IA_Preferences_ID'].'" name="Preference'.$Preference['IA_Preferences_ID'].'" value="'.$Preference['IA_Preferences_Type'].'-'.$Preference['IA_Preferences_ID'].'" checked />'.$Preference['IA_Preferences_Name'].'</label></li>';
					}
				}
				echo '</ul>';
			}
			echo '<script type="text/javascript">'.$SelectedPreferences.'</script>';
			echo '</div>';
		echo '</div>';
		echo '<div style="display:inline-block;">';
			echo '<div style="display:block; font-size:14px;">User Regions</div>';
			echo '<div style="width:250px; height:300px; margin:9px 5px 5px 0px; text-align:left; display:block; float:left; overflow-x:hidden; overflow-y:scroll;">';
			echo '<ul style="list-style-type:none">Locations';
			echo '<li><label title="All Regions"><input type="checkbox" id="PreferenceRegion" name="PreferenceRegion" value="6-0" />All Regions</label></li>';
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
			{ }
			else 
			{ 
				$Accounts = new _Accounts();
				$Accounts->GetRegions($UserInfo['UserParentID'], $RegionID);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
			$RegionsInfo = json_decode(json_encode($XML),true);
			if(isset($RegionsInfo['Region'][0])) 
			{
				for($r=0; $r<count($RegionsInfo['Region']); $r++) 
				{ $RegionInfo[] = array_filter($RegionsInfo['Region'][$r]); }
			}
			else 
			{
				if(isset($RegionsInfo['Region']) && !empty($RegionsInfo['Region'])) 
				{ $RegionInfo[] = array_filter($RegionsInfo['Region']); }
				else 
				{ $RegionInfo = null; }
			}
//print("UserInfo<pre>". print_r($User['Preferences'][6],true) ."</pre>");
			//reset($User['Preferences']);
			for($r=0; $r<count($RegionInfo); $r++) 
			{	
				if(isset($User['Preferences'][6]) && !empty($User['Preferences'][6])) 
				{
					echo '<li><label title="'.$RegionInfo[$r]['IA_Regions_Name'].'"><input type="checkbox" id="PreferenceRegion'.$RegionInfo[$r]['IA_Regions_ID'].'" name="PreferenceRegion'.$RegionInfo[$r]['IA_Regions_ID'].'" value="6-'.$RegionInfo[$r]['IA_Regions_ID'].'" />'.$RegionInfo[$r]['IA_Regions_Name'].'</label></li>';
					foreach($User['Preferences'][6] as $PreferenceKey => $PreferenceValue)
					{
						if($PreferenceValue == 0 || $PreferenceValue == $RegionInfo[$r]['IA_Regions_ID']) 
						{
							if($PreferenceValue == 0) 
							{
								$SelectedRegions .= 'document.getElementById("PreferenceRegion").checked=true;'."\n";
							}
							else 
							{
								$SelectedRegions .= 'document.getElementById("PreferenceRegion'.$RegionInfo[$r]['IA_Regions_ID'].'").checked=true;'."\n";
							}
						}
						else 
						{
							
						}
						
					}
				}
				else 
				{
					echo '<li><label title="'.$RegionInfo[$r]['IA_Regions_Name'].'"><input type="checkbox" id="PreferenceRegion'.$RegionInfo[$r]['IA_Regions_ID'].'" name="PreferenceRegion'.$RegionInfo[$r]['IA_Regions_ID'].'" value="6-'.$RegionInfo[$r]['IA_Regions_ID'].'" />'.$RegionInfo[$r]['IA_Regions_Name'].'</label></li>';
				}
			}
			echo '</ul>';
			echo '<script type="text/javascript">'.$SelectedRegions.'</script>';
			echo '</div>';
		echo '</div>';
		echo '<div style="clear:both"></div>';

		echo '<div style="width:530px; text-align:right; margin:10px 10px; display:block;">';
		echo '<input type="hidden" id="UserType" name="UserType" value="3" /> ';
		echo '<input type="hidden" id="UserID" name="UserID" value="'.$UserID.'" /> ';
		
		if(isset($User['Preferences']) && !empty($User['Preferences'])) 
		{
			echo '<input type="submit" id="UpdateButton" name="UpdateButton" style="width:80px; height:30px;" value="Update User" /> ';
		}
		else 
		{
			echo '<input type="submit" id="RegisterButton" name="RegisterButton" style="width:80px; height:30px;" value="Add User" /> ';
		}
		echo '<input type="button" onclick="window.location=\'users.php\'" name="CancelButton" style="width:80px; height:30px;" value="Cancel" />';
		echo '</div>';
		break;
	default:
		switch ($UserInfo['IA_Users_Type']) 
		{
			case 1:
				echo '<div style="display:block; text-align:left; height:30px">';
				echo '<input type="button" name="AddUserButton" onclick="window.location=\'users.php?ModeType=AddUser\'" style="margin-top:5px; width:80px; height:30px;" value="Add User">';
				//echo '<input type="button" name="AddUserButton" onclick="window.location=\'register.php\'" style="margin-top:5px; width:80px; height:30px;" value="Add User">';
				echo '</div>';
				echo "\n".'<div id="UsersTable" name="UsersTable" style="margin:20px 10px 10px 10px; background-color:#ffffff; width:50%; text-align:left; vertical-align:middle">';
				echo $Users->GetUsers($UserInfo);
				echo '</div>';
			default:
				break;
		}
		break;
}

?>
</div>
</form>
<script type="text/javascript">
/*
$('#CancelButton').click(function() {
  alert($('form').serialize());
  return false;
});
*/
</script>
<?php
	include "configuration/footer.php";
?>