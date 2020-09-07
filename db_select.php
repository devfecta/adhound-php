<?php
// Searches

function AccountSearch($UserID)
{
	$Search = mysql_query("SELECT * FROM IA_Accounts, IA_States WHERE IA_Accounts_UserID=".$UserID." AND IA_States_ID=IA_Accounts_StateID", CONN);
	$SearchArray = array();
	$SearchResultsArray = array();
	while($SearchArray[] = mysql_fetch_array($Search));
	
	foreach($SearchArray as $i => $Search) 
	{
		if($SearchArray[$i]['IA_States_Abbreviation'] == $LookUp)
		{ }
		if(!empty($SearchArray[$i])) 
		{
			$SearchResultsArray[] = $SearchArray[$i];	
		}
	}
	return $SearchResultsArray;
}

$AccountSearchArray = array();
if(empty($AccountSearchArray)) 
{
	$AccountSearchArray = AccountSearch($_SESSION['UserParentID']);
}
/*
if(!empty($AccountSearchArray)) 
{
	echo 'After:';
	unset($AccountSearchArray);
}
*/
/*
foreach($AccountSearchArray as $i => $AccountSearch) 
{
	echo $AccountSearchArray[$i]['IA_Accounts_City'].', '.$AccountSearchArray[$i]['IA_States_Abbreviation'].'<br />';
}
*/


?>