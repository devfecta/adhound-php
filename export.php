<?php
require "config.php";
require "classes.php";

switch($_REQUEST['ReportType']) 
{
	case 'RentReport':
		$Reports = new _Reports();
		$RentReportArray = array();
		switch($_REQUEST['ReportView'])
		{
			case 'Account':
				$Reports->ContractRentReport($_REQUEST['ReportView'], $_REQUEST['AccountID'], $_REQUEST['StartDate'], $_REQUEST['EndDate']);
				$RentReportArray[] = $Reports->RentReportRowsArray;
				$FileName = $_REQUEST['AccountID'].'_'.$_REQUEST['ReportView'].'RentReport_'. date("Y-m-d_H-i",time());
				$ExportFileName = str_replace(' ', '_', $Reports->RentReportRowsArray[$_REQUEST['AccountID']]['AccountBusinessName']) .'_RentReport_'. date("Y-m-d_H-i",time());
//print("<pre>". print_r($Reports->RentReportRowsArray,true) ."</pre>");
				break;
			case 'Region':
				//ini_set("max_execution_time","1200");
				$Accounts = mysql_query("SELECT IA_Ads_AccountID, IA_Regions_Name FROM IA_Regions, IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$_REQUEST['RegionID']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AccountID ORDER BY IA_Accounts_BusinessName", CONN);
				while ($Account = mysql_fetch_assoc($Accounts))
				{
					$RegionName = $Account['IA_Regions_Name'];
					$Reports->ContractRentReport($_REQUEST['ReportView'], $Account['IA_Ads_AccountID'], $_REQUEST['StartDate'], $_REQUEST['EndDate']);
					$RentReportArray[] = $Reports->RentReportRowsArray;
				}
				$FileName = $_REQUEST['RegionID'].'_'.$_REQUEST['ReportView'].'RentReport_'. date("Y-m-d_H-i",time());
				$ExportFileName = str_replace(' ', '_', $RegionName).'_RentReport_'. date("Y-m-d_H-i",time());
				break;
			default:
				break;
		}
		// $RentReportArray[Location][Advertiser]
		
		//print("<pre>". print_r($RentReportArray,true) ."</pre>");
		
		$AccountID = null;
		for($r=0; $r<count($RentReportArray); $r++)
		{
			foreach($RentReportArray[$r] as $a=>$a_value)
			{
				if($RentReportArray[$r][$a]['AccountID'] != $AccountID) 
				{
					$CSV .= $RentReportArray[$r][$a]['AccountBusinessName']."\n";
					$CSV .= trim($RentReportArray[$r][$a]['ContractTerms'])."\n";
					
				}
				
				$CSV .= 'Advertiser'."\t".'Ad Count'."\t".'Total Amount per Ad'."\n";
				foreach($RentReportArray[$r][$a]['Advertisers'] as $b=>$b_value)
			   {
				   $CSV .= $RentReportArray[$r][$a]['Advertisers'][$b]['AdvertiserBusinessName']."\t".$RentReportArray[$r][$a]['Advertisers'][$b]['AdCount']."\t".$RentReportArray[$r][$a]['Advertisers'][$b]['SubTotal'];
					$CSV .= "\n";
			   }
			   
			   $CSV .= "\t\t".$RentReportArray[$r][$a]['Total']."\n";
				
				$CSV .= "\n";
				$AccountID = $RentReportArray[$r][$a]['AccountID'];
			}
		}

		//print("<pre>". $CSV ."</pre>");
		
		if($_REQUEST['SaveReport'] == 'true') 
		{
			if (!file_exists(ROOT.'/users/'.$_REQUEST['UserID'])) 
			{
				mkdir(ROOT.'/users/'.$_REQUEST['UserID'], 0777, true);
			}
			else 
			{ }
			
			$Insert = "INSERT INTO IA_Reports (";
			$Insert .= "IA_Reports_ReportType, ";
			$Insert .= "IA_Reports_AccountID, ";
			$Insert .= "IA_Reports_StartDate, ";
			$Insert .= "IA_Reports_EndDate) VALUES ";
			
			$Insert .= "(";
			$Insert .= "'".$_REQUEST['ReportView'].'RentReport'."', ";
			switch($_REQUEST['ReportView'])
			{
				case 'Account':
					$Insert .= "'".$_REQUEST['AccountID']."', ";
					break;
				case 'Region':
					$Insert .= "'".$_REQUEST['RegionID']."', ";
					break;
				default:
					break;
			}
			$Insert .= "'".$_REQUEST['StartDate']."', ";
			$Insert .= "'".$_REQUEST['EndDate']."'";
			$Insert .= ")";
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{
				$file = fopen(ROOT.'/users/'.$_REQUEST['UserID'].'/'. mysql_insert_id() .'_'.$FileName.'.xls',"w");
				fwrite($file, chr(255).chr(254).mb_convert_encoding($CSV, 'UTF-16LE', 'UTF-8'));
				fclose($file);
				
				//chmod('../users/'.$_REQUEST['UserID'].'/'. mysql_insert_id() .'_'.$FileName.'.xls', 0755);
				switch($_REQUEST['ReportView'])
				{
					case 'Account':
						header ("Location: ../reports.php?ReportType=ContractReport+".$_REQUEST['AccountID']."&ReportView=".$_REQUEST['ReportView']);
						break;
					case 'Region':
						header ("Location: ../reports.php?ReportType=ContractReport+".$_REQUEST['RegionID']."&ReportView=".$_REQUEST['ReportView']);
						break;
					default:
						break;
				}
			}
		}
		else 
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-type: application/ms-excel");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");;
			header("Content-Disposition: attachment;filename=".$ExportFileName.".xls ");
			header("Content-Type: text/plain; charset=UTF-8");
			header("Content-Transfer-Encoding: binary ");
			print $CSV;
			exit();
		}
		break;
	case 'ProofOfPerformanceReport':
		$Reports = new _Reports();
		$Reports->ProofOfPerformance($_REQUEST['UserID'], $_REQUEST['AdvertiserID'], $_REQUEST['StartDate'], $_REQUEST['EndDate']);
		
		//$FileName = $_REQUEST['AdvertiserID'].'_'.'AccountPOPReport_'. date("Y-m-d_H-i",time());
//print("<pre>". $Reports->POPReportRowsArray[0] ."</pre>");

		$AdvertiserBusinessName = $Reports->POPReportRowsArray['IA_Advertisers_BusinessName'];
		$CSV .= $Reports->POPReportRowsArray['IA_Advertisers_BusinessName'].'\'s Contract Summary'."\n";
		$CSV .= $Reports->POPReportRowsArray['IA_Advertisers_Address']."\n";
		$CSV .= $Reports->POPReportRowsArray['IA_Advertisers_City'].', ';
		$CSV .= $Reports->POPReportRowsArray['IA_States_Abbreviation'].' ';
		$CSV .= $Reports->POPReportRowsArray['IA_Advertisers_Zipcode']."\n";
		$CSV .= 'Phone: '.$Reports->POPReportRowsArray['IA_Advertisers_Phone']."\n";
		$CSV .= 'Fax: '.$Reports->POPReportRowsArray['IA_Advertisers_Fax']."\n";
		$CSV .= 'e-Mail: '.$Reports->POPReportRowsArray['IA_Advertisers_Email']."\n\n";
		
		$CSV .= 'Report Date Range:'."\n";
		$CSV .= $Reports->POPReportRowsArray['ReportDate']."\n\n";
		$CSV .= 'Overall Ads:'."\n";
		$CSV .= $Reports->POPReportRowsArray['OverallAdsCount']."\n";
		$CSV .= 'Overall Sizes:'."\n";
		foreach($Reports->POPReportRowsArray['OverallSizes'] as $a=>$a_value)
		{
			$CSV .= $Reports->POPReportRowsArray['OverallSizes'][$a]."\n";
		}
		$CSV .= $Reports->POPReportRowsArray['Report'];

//print("<pre>". $CSV ."</pre>");
		
		if($_REQUEST['SaveReport'] == 'true') 
		{
			if (!file_exists(ROOT.'/users/'.$_REQUEST['UserID'])) 
			{
				mkdir(ROOT.'/users/'.$_REQUEST['UserID'], 0777, true);
			}
			else 
			{ }
			
			$Insert = "INSERT INTO IA_Reports (";
			$Insert .= "IA_Reports_ReportType, ";
			$Insert .= "IA_Reports_AdvertiserID, ";
			$Insert .= "IA_Reports_StartDate, ";
			$Insert .= "IA_Reports_EndDate) VALUES ";
			
			$Insert .= "(";
			$Insert .= "'".'POPReport'."', ";
			$Insert .= "'".$_REQUEST['AdvertiserID']."', ";
			$Insert .= "'".$_REQUEST['StartDate']."', ";
			$Insert .= "'".$_REQUEST['EndDate']."'";
			$Insert .= ")";
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{
				$FileName = $_REQUEST['AdvertiserID'].'_'.'POPReport_'. date("Y-m-d_H-i",time());
				$file = fopen(ROOT.'/users/'.$_REQUEST['UserID'].'/'. mysql_insert_id() .'_'.$FileName.'.xls',"w");
				fwrite($file, chr(255).chr(254).mb_convert_encoding($CSV, 'UTF-16LE', 'UTF-8'));
				fclose($file);
				
				switch($_REQUEST['ReportView'])
				{
					case 'Account':
						header ("Location: ../reports.php?ReportType=ProofOfPerformance+".$_REQUEST['UserID']."&AdvertiserID=".$_REQUEST['AdvertiserID']);
						break;
					case 'Region':
						header ("Location: ../reports.php?ReportType=ProofOfPerformance+".$_REQUEST['UserID']."&AdvertiserID=".$_REQUEST['AdvertiserID']);
						break;
					default:
						break;
				}
			}
		}
		else 
		{
			$AdvertiserBusinessName = str_replace(' ', '_', $AdvertiserBusinessName);
			$FileName = $AdvertiserBusinessName.'_'.'POPReport_'. date("Y-m-d_H-i",time());
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-type: application/ms-excel");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");;
			header("Content-Disposition: attachment;filename=".$FileName.".xls ");
			header("Content-Type: text/plain; charset=UTF-8");
			header("Content-Transfer-Encoding: binary ");
			print $CSV;
			exit();
		}
		
		break;
	default:
		break;
}
?>