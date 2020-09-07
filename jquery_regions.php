<?php
//session_start();
include "config.php";
include "classes.php";
echo '<select name="AccountRegionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
echo '<option value="">Select A Region</option>';
$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_StateID=".$_POST['queryString']." ORDER BY IA_Regions_Name", CONN);
while ($Region = mysql_fetch_assoc($Regions))
{
	echo '<option value="'.$Region[IA_Regions_ID].'">'.$Region[IA_Regions_Name].'</option>';
}
echo '</select>';
echo ' <input type="button" name="RegionsButton" onclick="window.location=\'regions.php\'" style="font-size:11px" value="Add/Edit Regions"> ';
?>