function getLocations(userId)
{
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getLocations", userID: ""+userId+""}, function(data) {
            $('#locationCount').html(data['count']);
			$('main').html(data['locations']);
		}, "json");
	}
	else
	{ }
}

function getAddLocationForm(userId) {
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getAddLocationForm", userID: ""+userId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}

function getEditLocationForm(locationId) {
    if(typeof locationId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getEditLocationForm", locationID: ""+locationId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}

function deleteLocation(locationId) {
    if(typeof locationId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "deleteLocation", locationID: ""+locationId+""}, function(data) {
			$('#locationCount').html(data['count']);
			$('main').html(data['locations']);
		}, "json");
	}
	else
	{ }
}

function viewLocation(locationId) {
    if(typeof locationId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "viewLocation", locationID: ""+locationId+""}, function(data) {
			$('main').html(data['location']);
		}, "json");
	}
	else
	{ }
}



function viewWallPanels(locationId, levelId, roomId, wallId)
{
    if(typeof wallId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "viewWallPanels", locationID: ""+locationId+"", levelID: ""+levelId+"", roomID: ""+roomId+"", wallID: ""+wallId+""}, function(data) {
			$('main').html(data['panels']);
		}, "json");
	}
	else
	{ }
}

function searchString(searchText) {

    var regexLocation = new RegExp("location:","ig");

    if(searchText.length > 3) {

        if (regexLocation.test(searchText)) {

            searchText = searchText.toLowerCase();
            searchText = searchText.replace('location:', '').trim();

            $.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "searchString", search: ""+searchText+""}, function(data) {
    			$('main').html(data['locations']);
    		}, "json");

        }

    }
}

/*
function getAdvertisers(userId)
{
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getAdvertisers", userID: ""+userId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}

function getAdvertisements(userId)
{
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getAdvertisements", userID: ""+userId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}
*/
function getUsers(userId)
{
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getUsers", userID: ""+userId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}

function getReports(userId)
{
    if(typeof userId !== 'undefined')
	{
		$.post(window.location.protocol+"//"+window.location.host+"/jquery/jquery.php", {FunctionType: "getReports", userID: ""+userId+""}, function(data) {
			$('main').html(data);
		});
	}
	else
	{ }
}