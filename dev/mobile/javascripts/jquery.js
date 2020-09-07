
var xmlhttp;

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
/*
function stripeResponseHandler(status, response) {
if (response.error) {
// re-enable the submit button
$('.submit-button').removeAttr("disabled");
// show the errors on the form
$(".payment-errors").html(response.error.message);
} else {
var form$ = $("#SubscriptionForm");
// token contains id, last4, and card type
var token = response['id'];
// insert the token into the form so it gets submitted to the server
form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
// and submit
form$.get(0).submit();
}
}

$(document).ready(function() {
$("#SubscriptionForm").submit(function(event) {
// disable the submit button to prevent repeated clicks
$('.submit-button').attr("disabled", "disabled");
// createToken returns immediately - the supplied callback submits the form if there are no errors
	if ($('.card-number'))
	{
		Stripe.createToken({
		number: $('.card-number').val(),
		cvc: $('.card-cvc').val(),
		exp_month: $('.card-expiry-month').val(),
		exp_year: $('.card-expiry-year').val()
		}, stripeResponseHandler);
	}
return false; // submit from callback
});
});
*/
/*
function AddDamage(AccountID, DateAdded, DescriptionText)
{
	if(AccountID.toString().length > 0) 
	{
		document.getElementById('LoadingField').style.display = 'block';
		$.post("configuration/jquery.php", {FunctionType: "AddDamage", Account: ""+AccountID+"", DateAdd: ""+DateAdded+"", Description: ""+DescriptionText+""}, function(data) {
			document.getElementById('LoadingField').style.display = 'none';
			$('#DamageLog').html(data);
			document.getElementById('AccountNotesTextBox').value = '';
		});
	} 
	else 
	{ }
}


function UpdateDamageLog(AccountID, LogID, FixedID)
{
	if(LogID.toString().length > 0) 
	{
		document.getElementById('LoadingField').style.display = 'block';
		$.post("configuration/jquery.php", {FunctionType: "UpdateDamageLog", Account: ""+AccountID+"", Log: ""+LogID+"", Fixed: ""+FixedID+""}, function(data) {
			document.getElementById('LoadingField').style.display = 'none';
			$('#DamageLog').html(data);
		});
	} 
	else 
	{ }
}

function AddAdType(ParentUserID, TypeName, TypeDescription)
{
	if(TypeName.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "AddAdType", User: ""+ParentUserID+"", Name: ""+TypeName+"", Description: ""+TypeDescription+""}, function(data) {
			$('#AdTypes').html(data);
		});
	} 
	else 
	{ }
}
*/
function LoadData(User) 
{
	if(User.toString().length > 0) 
	{
		document.getElementById('loading').style.display = 'block';
		document.getElementById('Content_Main').style.display = 'none';
		$.post("../configuration/xml.php", {UserInfo: ""+User+""}, function(data) {
			document.getElementById('loading').style.display = 'none';
			document.getElementById('Content_Main').style.display = 'block';
			$('#DataLoaded').html(data);
		});
	} 
	else 
	{ }
	
}
/*
function AdvertiserSearch(SearchString) 
{
	if(SearchString.length <= 3) 
	{
		//$('#suggestions').fadeOut(); // Hide the suggestions box
	} 
	else 
	{
		document.getElementById('LoadingSearch').style.display = 'block';
		$.post("configuration/jquery.php", {FunctionType: "AdvertiserSearch", QueryString: ""+SearchString+""}, function(data) { 
			document.getElementById('LoadingSearch').style.display = 'none';
			$('#SearchResults').fadeIn();
			$('#SearchResults').html(data);
			
		});
	}
}
*/
function LocationSearch(SearchString, User, UserType, ModeType) 
{
	if(SearchString.length <= 3) 
	{
		//$('#suggestions').fadeOut(); // Hide the suggestions box
	} 
	else 
	{
		// var SearchOption = $("input[@name=SearchOption]:checked").val();
		//$("#progressbar").show();
		document.getElementById('LoadingSearch').style.display = 'block';
		
		$.post("../configuration/jquery.php", {FunctionType: "LocationSearch", UserID: ""+User+"", UserTypeID: ""+UserType+"", QueryString: ""+SearchString+"", Mode: ""+ModeType+""}, function(data) { // Do an AJAX call
			//$('#progressbar').hide();
			document.getElementById('LoadingSearch').style.display = 'none';
			$('#SearchResults').fadeIn();
			$('#SearchResults').html(data);
			
		});
	}
}
/*
function CheckUsername(Username) 
{
	if(Username.length < 5) 
	{
		$('#SearchResults').fadeIn('slow');
		$('#SearchResults').html('Username is too short.');
	} 
	else 
	{
		$.post("configuration/jquery.users.php", {FunctionType: "CheckUsername", User: ""+Username+""}, function(data) {
			//document.getElementById('loading').style.display = 'none';
			$('#SearchResults').fadeIn('slow');
			$('#SearchResults').html(data);
		});
	}
}

function ActivateUser(UserInfo)
{
	if(UserInfo.toString().length > 0) 
	{
		$.post("configuration/jquery.users.php", {FunctionType: "ActivateUser", UserID: ""+UserInfo.split('-')[0]+"", Activate: ""+UserInfo.split('-')[1]+""}, function(data) {
			//$('#ActivateUser').html(data);
			window.location.reload();
		});
	} 
	else 
	{ }
}

function ValidateCard(UserInfo)
{
	if(UserInfo.toString().length > 0) 
	{
		$.post("configuration/jquery.users.php", {FunctionType: "ValidateCard", UserID: ""+UserInfo.split('-')[0]+"", Validate: ""+UserInfo.split('-')[1]+""}, function(data) {
			//$('#ActivateUser').html(data);
			window.location.reload();
		});
	} 
	else 
	{ }
}

function DeleteUser(ParentUserID, UserID) 
{
	var answer = confirm("The following user information will be deleted.\n-User Information\n");
	if (answer)
	{
		if(UserID.toString().length > 0) 
		{
			document.getElementById('loading').style.display = 'block';
			$.post("configuration/jquery.users.php", {FunctionType: "DeleteUser", ParentUser: ""+ParentUserID+"", User: ""+UserID+""}, function(data) {
				document.getElementById('loading').style.display = 'none';
				$('#UsersTable').html(data);
			});
		} 
		else 
		{ }
	}
	
}

function DeleteAccount(UserID) 
{
	var answer = confirm("You are about to delete the following:.\n-User Information\n-Associated Users Under This User\n-All Locations, Panels, and Ads");
	if (answer)
	{
		if(UserID.toString().length > 0) 
		{ } 
		else 
		{ }
	}
	
}

// reports.php

function GetProofOfPerformanceReport(UserID, AdvertiserID, StartDate, EndDate)
{
	$.post("configuration/jquery.php", {FunctionType: "GetProofOfPerformanceReport", User: ""+UserID+"", Advertiser: ""+AdvertiserID+"", Start: ""+StartDate+"", End: ""+EndDate+""}, function(data) {
		//document.getElementById('LoadingDiv').display = 'block';
		$('#ProofOfPerformanceReport').html(data);
		//document.getElementById('LoadingDiv').display = 'none';
	});
}

function GetContractRentReport(UserID, ReportView, RegionID, AccountID, StartDate, EndDate)
{
	document.getElementById('loading').style.display = 'block';
	$.post("configuration/jquery.php", {FunctionType: "GetContractRentReport", User: ""+UserID+"", View: ""+ReportView+"", Region: ""+RegionID+"", Account: ""+AccountID+"", Start: ""+StartDate+"", End: ""+EndDate+""}, function(data) {
		document.getElementById('loading').style.display = 'none';
		$('#ContractRentReport').html(data);
	});
}

function DeleteSavedRentReport(UserID, AccountID, ReportID, FileName)
{
	$.post("configuration/jquery.php", {FunctionType: "DeleteSavedRentReport", User: ""+UserID+"", Account: ""+AccountID+"", Report: ""+ReportID+"", File: ""+FileName+""}, function(data) {
		//$('#ContractRentReport').html(data);
		window.location.reload();
	});
}

function DeleteSavedPOPReport(UserID, AdvertiserID, ReportID, FileName)
{
	$.post("configuration/jquery.php", {FunctionType: "DeleteSavedPOPReport", User: ""+UserID+"", Advertiser: ""+AdvertiserID+"", Report: ""+ReportID+"", File: ""+FileName+""}, function(data) {
		//$('#ContractRentReport').html(data);
		window.location.reload();
	});
}

// ads.php

function GetNewAdFiles(UserID, AdvertiserID, OldAdID, AdWidth, AdHeight) 
{
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById('NewAdsDIV').innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("POST","configuration/ajax.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("FunctionType=GetNewAdFiles"+
			"&UserID="+UserID+
			"&OldAdID="+OldAdID+
			"&AdWidth="+AdWidth+
			"&AdHeight="+AdHeight+
			"&AdvertiserID="+AdvertiserID);
}

function ReplaceAd(UserID, AccountID, RoomID, LocationID, AdTypeID, PlacedOptionID, OldAdvertiserID, OldAdID, NewAdvertiserID, NewAdID) 
{
	var answer = confirm("The following ad information will be replaced.\n-Ad Information\n-Associated Panel Ads");
	if (answer)
	{
		if(NewAdID.toString().length > 0) 
		{
			document.getElementById('LoadingField'+OldAdID).style.display = 'block';
			$.post("configuration/jquery.php", {FunctionType: "ReplaceAd", User: ""+UserID+"", Account: ""+AccountID+"", Room: ""+RoomID+"", Location: ""+LocationID+"", AdType: ""+AdTypeID+"", PlacedOption: ""+PlacedOptionID+"", OldAdvertiser: ""+OldAdvertiserID+"", OldAd: ""+OldAdID+"", NewAdvertiser: ""+NewAdvertiserID+"", NewAd: ""+NewAdID+""}, function(data) {
				//document.write(data);
				document.getElementById('LoadingField'+OldAdID).style.display = 'none';
				if(AccountID == '') 
				{
					window.location = 'locations.php';
				}
				else 
				{
					window.location = 'reports.php?ReportType=RunReport+'+AccountID+'&AreaID='+RoomID.split('-')[0]+'&RoomID='+RoomID.split('-')[1]+'&AdLocationID='+LocationID;
				}
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

// advertisers.php

function AddAdvertiserPricing(UserID, UserTypeID, AdvertiserID, AdLocationID, AdTypeID, AdSizes, Count, PricingAmount, IncrementID, StartDate, EndDate) 
{
	if(AdvertiserID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "AddAdvertiserPricing", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+"", AdLocation: ""+AdLocationID+"", AdType: ""+AdTypeID+"", AdSize: ""+AdSizes+"", AdCount: ""+Count+"", Pricing: ""+PricingAmount+"", Increment: ""+IncrementID+"", Start: ""+StartDate+"", End: ""+EndDate+""}, function(data) {
			$('#AdvertiserPricingTable'+AdvertiserID).html(data);
		});
	} 
	else 
	{ }
}

function EditAdvertiserPricing(UserID, UserTypeID, AdvertiserID, AdvertiserPricingID)
{
	if(AdvertiserPricingID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "EditAdvertiserPricing", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+"", AdvertiserPricing: ""+AdvertiserPricingID+""}, function(data) {
			$('#AdvertiserPricingTable'+AdvertiserID).html(data);
		});
	} 
	else 
	{ }
}

function UpdateAdvertiserPricing(UserID, UserTypeID, AdvertiserID, AdvertiserPricingID, AdLocationID, AdTypeID, AdSizes, Count, PricingAmount, IncrementID, StartDate, EndDate) 
{
	if(AdvertiserPricingID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "UpdateAdvertiserPricing", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+"", AdvertiserPricing: ""+AdvertiserPricingID+"", AdLocation: ""+AdLocationID+"", AdType: ""+AdTypeID+"", AdSize: ""+AdSizes+"", AdCount: ""+Count+"", Pricing: ""+PricingAmount+"", Increment: ""+IncrementID+"", Start: ""+StartDate+"", End: ""+EndDate+""}, function(data) {
			$('#AdvertiserPricingTable'+AdvertiserID).html(data);
		});
	} 
	else 
	{ }
}

function DeleteAdvertiserPricing(UserID, UserTypeID, AdvertiserID, AdvertiserPricingID) 
{
	if(AdvertiserPricingID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "DeleteAdvertiserPricing", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+"", AdvertiserPricing: ""+AdvertiserPricingID+""}, function(data) {
			$('#AdvertiserPricingTable'+AdvertiserID).html(data);
		});
	} 
	else 
	{ }
}

function CancelAdvertiserPricing(UserID, UserTypeID, AdvertiserID) 
{
	if(AdvertiserID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "CancelAdvertiserPricing", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+""}, function(data) {
			$('#AdvertiserPricingTable'+AdvertiserID).html(data);
		});
	} 
	else 
	{ }
}

function DeleteAdvertiser(UserID, AdvertiserID, PageNumber) 
{
	var answer = confirm("The following advertiser information will be deleted.\n-Advertiser Information\n-Associated Advertiser Panel Ads\n-Associated Advertiser Ad Library Ads");
	if (answer)
	{
		if(AdvertiserID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteAdvertiser", User: ""+UserID+"", Advertiser: ""+AdvertiserID+"", Page: ""+PageNumber+""}, function(data) {
				//$('#AdvertiserTable').html(data);
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else{
		//alert("No action taken")
	}
}

function ArchiveAdvertiser(UserID, AdvertiserID, PageNumber) 
{
	var answer = confirm("The following advertiser information will be disabled.\n-Advertiser Information\n-Associated Advertiser Panel Ads\n-Associated Advertiser Ad Library Ads");
	if (answer)
	{
		if(AdvertiserID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "ArchiveAdvertiser", User: ""+UserID+"", Advertiser: ""+AdvertiserID+"", Page: ""+PageNumber+""}, function(data) {
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else{
		//alert("No action taken")
	}
}

function UnarchiveAdvertiser(UserID, AdvertiserID, PageNumber) 
{
	var answer = confirm("The following advertiser information will be enabled.\n-Advertiser Information\n-Associated Advertiser Ad Library Ads");
	if (answer)
	{
		if(AdvertiserID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "UnarchiveAdvertiser", User: ""+UserID+"", Advertiser: ""+AdvertiserID+"", Page: ""+PageNumber+""}, function(data) {
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else{
		//alert("No action taken")
	}
}

function EditAdvertiser(UserID, AdvertiserID, ModeType)
{
	//document.write(xmlhttp);
	
	xmlhttp.onreadystatechange=function()
	{
		//alert(xmlhttp);
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("AdvertiserTable").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("POST","configuration/ajax.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("FunctionType=EditAdvertiser"+
			"&UserID="+UserID+
			"&AdvertiserID="+AdvertiserID+
			"&ModeType="+ModeType);
}

// reports.php

function PlaceAllLocationAds(UserID, ModeType, AccountID)
{
	var answer = confirm("Are you sure you want to place ads?");
	if (answer)
	{
		if(AccountID.toString().length > 0) 
		{
			document.getElementById('loading').style.display = 'block';
			$.post("configuration/jquery.php", {FunctionType: "PlaceAllLocationAds", User: ""+UserID+"", Mode: ""+ModeType+"", Account: ""+AccountID+""}, function(data) {
					//alert('Worked');
					window.location.reload();
					document.getElementById('loading').style.display = 'none';
				});
		} 
		else 
		{ }
	}
	else
	{ }
}

function ArchiveLibraryAd(AdvertiserID, AdLibraryID) 
{
	var answer = confirm("Archiving this ad will do the following:\n-Remove the ad from any panels it\'s currently in\n-Make it unselectable for placement in a panels\n-Leave available in the ad library for future placement and reporting");
	if (answer)
	{
		if(AdLibraryID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "ArchiveLibraryAd", Advertiser: ""+AdvertiserID+"", Ad: ""+AdLibraryID+""}, function(data) {
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function UnarchiveLibraryAd(AdvertiserID, AdLibraryID) 
{
	var answer = confirm("Unarchiving this ad will do the following:-Make it selectable for placement in a panels\n-Leave available in the ad library for future placement and reporting");
	if (answer)
	{
		if(AdLibraryID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "UnarchiveLibraryAd", Advertiser: ""+AdvertiserID+"", Ad: ""+AdLibraryID+""}, function(data) {
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function DeleteLibraryAd(AdvertiserID, AdLibraryID) 
{
	var answer = confirm("The following ad information will be deleted.\n-Ad Library Information\n-Associated Panel Placement of the Ad");
	if (answer)
	{
		if(AdLibraryID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteLibraryAd", Advertiser: ""+AdvertiserID+"", Ad: ""+AdLibraryID+""}, function(data) {
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function DeleteAdListing(AdID, AccountID, AdvertiserID, PanelID, LocationID) 
{
	var answer = confirm("Are you sure you want to delete this?");
	if (answer)
	{
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				window.location = xmlhttp.responseText;
			}
		}
		
		xmlhttp.open("POST","configuration/ajax.php",true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlhttp.send("FunctionType=DeleteAdListing"+
				"&AdID="+AdID+
				"&AccountID="+AccountID+
				"&AdvertiserID="+AdvertiserID+
				"&PanelID="+PanelID+
				"&LocationID="+LocationID);
	}
	else{
		//alert("No action taken")
	}
}

function DeleteRunReportAd(UserTypeID, AdvertiserID, AdID, AccountID, PanelsID, PanelSectionID, Mode, PanelScale) 
{
	var answer = confirm("Are you sure you want to delete this?");
	if (answer)
	{
		if(AdID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteRunReportAd", Scale: ""+PanelScale+"", ViewMode: ""+Mode+"", UserType: ""+UserTypeID+"", Account: ""+AccountID+"", Panels: ""+PanelsID+"", PanelSection: ""+PanelSectionID+"", Advertiser: ""+AdvertiserID+"", Ad: ""+AdID+""}, function(data) {
				//$('#PanelCell'+PanelID).html(data);
				//$('#Account'+AccountID+'Location'+LocationID+'Panel'+PanelID).html(data);
				$('#Panel'+PanelsID).html(data);
			});
		} 
		else 
		{ }
	}
	else
	{
		//alert("No action taken")
	}
}

function DeleteThumbnailAd(UserTypeID, AdLibraryID, AdID, AccountID, PanelID, RoomID, LocationID, Mode, PanelScale) 
{
	var answer = confirm("Are you sure you want to delete this?");
	if (answer)
	{
		if(AdID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteRunReportAd", Scale: ""+PanelScale+"", ViewMode: ""+Mode+"", UserType: ""+UserTypeID+"", Account: ""+AccountID+"", Room: ""+RoomID+"", Location: ""+LocationID+"", Panel: ""+PanelID+"", Ad: ""+AdID+""}, function(data) {
				//$('#LocationDropdownRequired').html(data);
				//window.location.reload();
				//$('#PanelCell').html(data);
				$('#Panel'+AccountID+'-'+LocationID+'-'+PanelID).html(data);
				ShowPanelSections(UserTypeID, AccountID, RoomID, LocationID, PanelID, AdLibraryID);
			});
		} 
		else 
		{ }
	}
	else{
		//alert("No action taken")
	}
}

function DuplicateRunReportPanel(UserID, PanelID, AdPanelID) 
{
	var answer = confirm("The following panel information will be deleted.\n-Panel Information\n-Associated Panel Ads");
	if (answer)
	{
		if(AdPanelID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DuplicateRunReportPanel", User: ""+UserID+"", Panel: ""+PanelID+"", AdPanel: ""+AdPanelID+""}, function(data) {
				////window.location = 'reports.php?ReportType=RunReport+'+AccountID+'&AdLocationID='+LocationID;
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else
	{ }
}


function DeleteRunReportPanel(UserID, AccountID, PanelID) 
{
	var answer = confirm("The following panel information will be deleted.\n-Panel Information\n-Associated Panel Ads");
	if (answer)
	{
		if(PanelID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteRunReportPanel", User: ""+UserID+"", Account: ""+AccountID+"", Panel: ""+PanelID+""}, function(data) {
				////window.location = 'reports.php?ReportType=RunReport+'+AccountID+'&AdLocationID='+LocationID;
				window.location.reload();
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

// account.php
function ArchiveLocation(RecordID, PageNumber) 
{
	var answer = confirm("The following location information will be disabled.\n-Location Information\n-Associated Location Panels\n-Associated Location Ads");
	if (answer)
	{
		if(RecordID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "ArchiveLocation", Record: ""+RecordID+""}, function(data) {
				window.location = 'locations.php';
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function UnarchiveLocation(RecordID, PageNumber) 
{
	var answer = confirm("The following location information will be enabled.\n-Location Information\n-Associated Location Panels");
	if (answer)
	{
		if(RecordID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "UnarchiveLocation", Record: ""+RecordID+""}, function(data) {
				window.location = 'locations.php';
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function DeleteLocation(RecordID, PageNumber) 
{
	var answer = confirm("The following location information will be deleted.\n-Location Information\n-Associated Location Panels\n-Associated Location Ads");
	if (answer)
	{
		if(RecordID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteLocation", Record: ""+RecordID+""}, function(data) {
				window.location = 'locations.php';
			});
		} 
		else 
		{ }
	}
	else
	{ }
}
*/
/*
function ShowPassword(UserID, Username, UserType)
{
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("PasswordText").innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("POST","configuration/ajax.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("FunctionType=UpdatePassword"+
			"&UserID="+UserID+
			"&Username="+Username+
			"&UserType="+UserType+
			"&PasswordTextBox="+document.getElementById('PasswordTextBox').value);

}
*/
/*
function AddCategory(CategoryName) 
{
	if(CategoryName.toString().length > 0) 
	{ 
		$.post("configuration/jquery.php", {FunctionType: "AddCategory", Category: ""+CategoryName+""}, function(data) {
			$('#AccountCategoryDropdown').html(data);
			document.getElementById('AccountCategoryDropdown').style.display='inline-block';
			document.getElementById('CategoriesButton').style.display='inline-block';
			document.getElementById('CategoryTextBox').style.display='none';
			document.getElementById('AddCategoryButton').style.display='none';
			document.getElementById('CancelCategoryButton').style.display='none';
		});
	}
}

function AddRegion(UserID, StateID, RegionName) 
{
	if(RegionName.toString().length > 0) 
	{ 
		$.post("configuration/jquery.php", {FunctionType: "AddRegion", User: ""+UserID+"", State: ""+StateID+"", Region: ""+RegionName+""}, function(data) {
			$('#AccountRegionDropdownRequired').html(data);
			document.getElementById('AccountRegionDropdownRequired').style.display='inline-block';
			document.getElementById('RegionsButton').style.display='inline-block';
			document.getElementById('RegionTextBox').style.display='none';
			document.getElementById('AddRegionButton').style.display='none';
			document.getElementById('CancelRegionButton').style.display='none';
		});
	}
}

function DeleteRegion(UserID, RegionID) 
{
	var answer = confirm("The following region information will be deleted.\n-Region Information\n-Associated Location(s) Information\n-Associated Location(s) Panels\n-Associated Location(s) Ads");
	if (answer)
	{
		if(RegionID.toString().length > 0) 
		{
			$.post("configuration/jquery.php", {FunctionType: "DeleteRegion", User: ""+UserID+"", Region: ""+RegionID+""}, function(data) {
				window.location = 'regions.php';
			});
		} 
		else 
		{ }
	}
	else
	{ }
}

function GetRegions(UserID, StateID)
{
	if(StateID.toString().length < 0) 
	{ } 
	else 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetRegions", User: ""+UserID+"", State: ""+StateID+""}, function(data) {
			$('#RegionsIDDropdownDIV').html(data);
			
		});
		
		$.post("configuration/jquery.php", {FunctionType: "GetCounties", State: ""+StateID+""}, function(data) {
			$('#CountiesIDDropdownDIV').html(data);
		});
	}
}

function AddCounty(StateID, CountyName) 
{
	if(CountyName.toString().length > 0) 
	{ 
		$.post("configuration/jquery.php", {FunctionType: "AddCounty", State: ""+StateID+"", County: ""+CountyName+""}, function(data) {
			$('#AccountCountyDropdownRequired').html(data);
			document.getElementById('AccountCountyDropdownRequired').style.display='inline-block';
			document.getElementById('CountiesButton').style.display='inline-block';
			document.getElementById('CountyTextBox').style.display='none';
			document.getElementById('AddCountyButton').style.display='none';
			document.getElementById('CancelCountyButton').style.display='none';
		});
	}
}

// ads.php

function GetLocations(ModeType, OldAdID, NewAdID, AccountID)
{
	if(AccountID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetLocations", Mode: ""+ModeType+"", OldAd: ""+OldAdID+"", NewAd: ""+NewAdID+"", Account: ""+AccountID+""}, function(data) {
			if(NewAdID == null) 
			{
				//$('#RoomDropdown').html(data);
				$('#PanelLocationDropdown').html(data);
				
			}
			else 
			{
				//$('#RoomDropdown'+OldAdID).html(data);
				$('#PanelLocationDropdown'+OldAdID).html(data);
			}
			
		});
		
		if (ModeType != 'ReplaceAd') 
		{
			GetAdTypes(ModeType, OldAdID, NewAdID, AccountID, null);
		}
	} 
	else 
	{ }
}

function GetAdTypes(ModeType, OldAdID, NewAdID, AccountID, LocationID)
{
	if(AccountID.toString().length > 0) 
	{	
		$.post("configuration/jquery.php", {FunctionType: "GetAdTypes", Mode: ""+ModeType+"", OldAd: ""+OldAdID+"", NewAd: ""+NewAdID+"", Account: ""+AccountID+"", Location: ""+LocationID+""}, function(data) {
			if(NewAdID == null) 
			{
				$('#AdTypeDropdown').html(data);
			}
			else 
			{
				$('#AdTypeDropdown'+OldAdID).html(data);
			}
			
		});

		//GetAdPlacements(ModeType, OldAdID, NewAdID, AccountID, LocationID);
	} 
	else 
	{ }
}

function GetAdPlacements(ModeType, OldAdID, NewAdID, AccountID, LocationID)
{
	if(AccountID.toString().length > 0) 
	{	
		$.post("configuration/jquery.php", {FunctionType: "GetAdPlacements", Mode: ""+ModeType+"", OldAd: ""+OldAdID+"", Account: ""+AccountID+"", Location: ""+LocationID+""}, function(data) {
			if(NewAdID == null) 
			{
				$('#AdPlacementDropdown').html(data);
			}
			else 
			{
				$('#AdPlacementDropdown'+OldAdID).html(data);
			}
			
		});
	} 
	else 
	{ }
}

function GetWalls(ModeType, OldAdID, NewAdID, AccountID, RoomID)
{
	if(RoomID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetWalls", Mode: ""+ModeType+"", OldAd: ""+OldAdID+"", NewAd: ""+NewAdID+"", Account: ""+AccountID+"", Room: ""+RoomID+""}, function(data) {
			if(NewAdID == null) 
			{
				$('#LocationDropdown').html(data);
			}
			else 
			{
				$('#LocationDropdown'+OldAdID).html(data);
			}
		});
	} 
	else 
	{ }
}

function GetPanels(AccountID, PanelIDs)
{
	if(PanelIDs.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetPanels", Account: ""+AccountID+"", Panels: ""+PanelIDs+""}, function(data) {
			$('#PanelIDDropdownRequired').html(data);
		});
	} 
	else 
	{ }
}

function GetAvailableRooms(UserID, AreaID)
{
	if(AreaID.toString().length > 0) 
	{
		document.getElementById('LoadingRoomField').style.display = 'inline-block';
		$.post("configuration/jquery.php", {FunctionType: "GetAvailableRooms", User: ""+UserID+"", Area: ""+AreaID+""}, function(data) {
			document.getElementById('LoadingRoomField').style.display = 'none';
			$('#RoomDropdownRequired').html(data);
		});
	} 
	else 
	{ }
	
}

function GetAvailableAdLocations(UserID, RoomID)
{
	if(RoomID.toString().length > 0) 
	{
		document.getElementById('LoadingWallField').style.display = 'inline-block';
		$.post("configuration/jquery.php", {FunctionType: "GetAvailableAdLocations", User: ""+UserID+"", Room: ""+RoomID+""}, function(data) {
			document.getElementById('LoadingWallField').style.display = 'none';
			$('#LocationDropdownRequired').html(data);
		});
	} 
	else 
	{ }
	
}


function GetAvailablePanels(AccountID, AreaID, RoomID, LocationID)
{
	if(LocationID.toString().length > 0) 
	{
		document.getElementById('LoadingPanelIDField').style.display = 'inline-block';
		$.post("configuration/jquery.php", {FunctionType: "GetAvailablePanels", Account: ""+AccountID+"", Area: ""+AreaID+"", Room: ""+RoomID+"", Location: ""+LocationID+""}, function(data) {
			document.getElementById('LoadingPanelIDField').style.display = 'none';
			$('#PanelIDDropdownRequired').html(data);
		});
	} 
	else 
	{ }
	
}

function ShowPanelSections(UserTypeID, AccountID, PanelsID, PanelID)
{	
	if(PanelID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetPanelSections", Account: ""+AccountID+"", Panels: ""+PanelsID+"", Panel: ""+PanelID+""}, function(data) {
			$('#PanelSectionDropdownRequired').html(data);
		});
	} 
	else 
	{ }
				
	ShowPanelThumbnail(UserTypeID, AccountID, PanelsID, PanelID);
}

function ShowPanelThumbnail(UserTypeID, AccountID, PanelsID, PanelID)
{
	if(PanelsID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetPanelThumbnail", UserType: ""+UserTypeID+"", Account: ""+AccountID+"", Panels: ""+PanelsID+"", Panel: ""+PanelID+""}, function(data) {
			//$('#PanelThumbnailDIV').html(data);
			//$('#PanelCell'+PanelID).html(data);
			//$('#PanelCell').html(data);
			if($('#Panel'+PanelsID.split('-')[0]).length > 0) 
			{ $('#Panel'+PanelsID.split('-')[0]).html(data); }
			else 
			{ $('#Panel').html(data); }
		});
	} 
	else 
	{ }
}

function UpdatePanelThumbnail(UserID, UserTypeID, AccountID, PanelsID, PanelID, PanelSectionID, AdvertiserID, AdID, AdLibraryID)
{
	if(AdLibraryID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "UpdatePanelThumbnail", User: ""+UserID+"", Account: ""+AccountID+"", Panels: ""+PanelsID+"", Panel: ""+PanelID+"", PanelSection: ""+PanelSectionID+"", Advertiser: ""+AdvertiserID+"", Ad: ""+AdID+"", AdLibrary: ""+AdLibraryID+""}, function(data) {
			//$('#PanelThumbnailDIV').html(data);
			//$('#PanelCell'+PanelID).html(data);
			//$('#PanelCell').html(data);
			//$('#AdID').val(data);
			ShowPanelThumbnail(UserTypeID, AccountID, PanelsID, PanelID);
		});
	} 
	else 
	{ }
}

function GetAdvertiserAds(AdvertiserID)
{
	// OLD
	if(AdvertiserID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetAdvertiserAds", Advertiser: ""+AdvertiserID+""}, function(data) {
			$('#AdFilesDIV').html(data);
		});
	} 
	else 
	{ }
}

function GetAdFiles(UserID, UserTypeID, AdvertiserID, AdID, AdLibraryID, PanelSectionWidth, PanelWidth, PanelSectionHeight, PanelHeight)
{
	if(AdvertiserID.toString().length > 0) 
	{
		$.post("configuration/jquery.php", {FunctionType: "GetAdFiles", User: ""+UserID+"", UserType: ""+UserTypeID+"", Advertiser: ""+AdvertiserID+"", Ad: ""+AdID+"", AdLibrary: ""+AdLibraryID+"", SectionWidth: ""+PanelSectionWidth+"", PWidth: ""+PanelWidth+"", SectionHeight: ""+PanelSectionHeight+"", PHeight: ""+PanelHeight+""}, function(data) {
			$('#AdFilesDIV').html(data);
		});
	} 
	else 
	{ }
}
*/
// panels.php
/*
function UpdateRoom(AccountID, RoomID)
{
	xmlhttp.open("POST","configuration/ajax.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("FunctionType=UpdateRoom"+
				"&AccountID="+AccountID+
				"&RoomID="+RoomID+
				"&RoomTextBox="+document.getElementById('RoomTextBox'+RoomID).value);

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById('RoomIDRow'+RoomID).innerHTML=xmlhttp.responseText;
		}
	}
}
*/


/*
function DeletePanelLocation(AccountID, PanelLocationID)
{
	var answer = confirm("Are you sure you want to delete this?");
	if (answer)
	{
		xmlhttp.open("POST","configuration/ajax.php",true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlhttp.send("FunctionType=DeletePanelLocation"+
					"&AccountID="+AccountID+
					"&PanelLocationID="+PanelLocationID);
	
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById('PanelLocationsTableCell').innerHTML=xmlhttp.responseText;
			}
		}
	}
	else
	{ }
}
*/