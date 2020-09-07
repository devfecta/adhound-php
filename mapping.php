<?php
session_start();
include "configuration/config.php";
include "configuration/classes.php";
?>
<DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
	html { height: 100% }
	body { height: 100%; margin: 0; padding: 0 }
	#map_canvas { height: 100% }
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAWiB6PqOyqsJJZLmoZ5CFb2IP6sqqhtI8&sensor=false">
</script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
 //<![CDATA[

var map;
var markers = [];
//var infoWindow;
var locationSelect;
    
function clearLocations() {
	//infoWindow.close();
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers.length = 0;
}

function load() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(40, -100),
		zoom: 10,
		mapTypeId: 'roadmap',
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	});
	//infoWindow = new google.maps.InfoWindow();
	
	clearLocations();
	
<?php
$AccountInfo = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_UserID='8' LIMIT 0,11", CONN);

echo 'var Locations=new Array();'."\r";
$i=0;

	while ($Account = mysql_fetch_assoc($AccountInfo))
	{
		$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$Account[IA_Accounts_StateID], CONN);
		while ($State = mysql_fetch_assoc($States))
		{
			$StateAbbreviation = $State[IA_States_Abbreviation];
		}
		echo 'Locations['.$i.']="'.$Account[IA_Accounts_BusinessName].'|'.$Account[IA_Accounts_Address].', '.$Account[IA_Accounts_City].', '.$StateAbbreviation.', '.$Account[IA_Accounts_Zipcode].'";'."\r";
		$i++;
	}
?>
	
	var bounds = new google.maps.LatLngBounds();
	var geocoder = new google.maps.Geocoder();
	
	/*
	var image = new google.maps.MarkerImage('images/beachflag.png',
     new google.maps.Size(20, 32),
     new google.maps.Point(0,0),
     new google.maps.Point(0, 32));
     var shadow = new google.maps.MarkerImage('images/beachflag_shadow.png',
          new google.maps.Size(37, 32),
          new google.maps.Point(0,0),
          new google.maps.Point(0, 32));
     */
     var shape = {
          coord: [1, 1, 1, 20, 18, 20, 18 , 1],
          type: 'poly'
     };
	
	
	
	for (var i = 0; i < Locations.length; i++) {
		//var name = Locations[i].getAttribute("name");
		var Location = Locations[i].split("|");
		//var name = Location[0];
		//var address = Location[1];

		geocoder.geocode( { 'address': Location[1]}, function(results, status) {	
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
		  			map: map,
		  			shape: shape,
		  			title: Location[0], 
					position: results[0].geometry.location
				});
				
			} 
			else 
			{
				alert("Geocode was not successful for the following reason: " + status);
			}
		});
	}
}

   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
   }
 
	function createMarker(latlng, address) {
      //var html = "<b>" + name + "</b> <br/>" + address;
     	var html = address;
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }

    //]]>
</script>
</head>
<body onload="load()">

<div id="map" style="width: 100%; height: 80%"></div>

</body>
</html>