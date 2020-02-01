<?php
# File Locations
#$SourceFileLocation = "c:/users/user/Google Drive/Delta Robotics Share/Software/Scouting/Data";
$SourceFileLocation = "data";
$HTMLOutputDirectory = "html";
$HTMLOutputDirectoryPurge = "true";
$CSVOutputDirectory = "csv";
$CSVOutputDirectoryPurge = "true";

#Variable Definitions
$MatchResultsList = array();
$TeamNumbersList = array();
$MatchDetailedResults = array();
$TeamNumbers = array();
$TeamScoring = array();
$TeamRankings = array();
$TeamMatchScores = array();
$TeamNumberCurrent = -1; #used to know when done processing a team's results'


#included files
include "headers.php";
include "scoring.php";
include "teams.php";


# get list of matches and team numbers in sorted order
$SourceFileLocationHandle = opendir($SourceFileLocation);
while (false !== ($entry = readdir($SourceFileLocationHandle)))
	{
	if ((strpos($entry,"Match") > 0 ) && (strpos($entry,"json") > 0))
		{
		$MatchResultsList[] = $entry;
		$TeamNumberPos = strpos($entry,"-");
		$TeamNumber = substr($entry,0,$TeamNumberPos);
		$TeamNumbersList[] = $TeamNumber;
		}
	}
natcasesort($MatchResultsList);
natcasesort($TeamNumbersList);
$TeamNumbersList = array_unique($TeamNumbersList);



#set up second header entry for team number links
$HTMLHeader2  = "<center><table border=0><tr>";
$HTMLHeader2 .= "<td><img src=./GearInverted7.png height=100px></td>";
$HTMLHeader2 .= "<td><img src=./FIRST-FTC-Skystone19--Color-840x700.png height=100px></td>";
$HTMLHeader2 .= "<td><img src=./FTCicon.png height=100px></td>";
$HTMLHeader2 .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
$HTMLHeader2 .= "<td><table border=1><tr><td colspan=10 valign=center><center><b>Teams:</td></tr>";
$HTMLHeader2BreakCount = 9;
$HTMLHeader2TeamColumnCount = 0;
foreach ($TeamNumbersList as $NumberLink)
	{
	if ($HTMLHeader2TeamColumnCount > $HTMLHeader2BreakCount)
		{
		$HTMLHeader2 .= "</tr><tr>";
		$HTMLHeader2TeamColumnCount = 0;
		}
	$HTMLHeader2TeamColumnCount++;
	$HTMLHeader2 .= "<td valign=center><center><a href=$NumberLink.html>$NumberLink</a></td>";
	}
$HTMLHeader2 .= "<td valign=center><center><a href=summary.html>Summary</a></td>";
$HTMLHeader2 .= "</tr></table></td>";

$HTMLHeader2 .= "</tr></table><p>\r\n";


#set up HTML and CSV directories
if (file_exists($HTMLOutputDirectory) === false) mkdir($HTMLOutputDirectory);
if (file_exists($CSVOutputDirectory) === false) mkdir($CSVOutputDirectory);


#if purge is set for HTML and CSV directories, purge them now once we have successfully read in json files
if ($HTMLOutputDirectoryPurge == "true") array_map( "unlink", glob( "$HTMLOutputDirectory/*.html" ) );
if ($CSVOutputDirectoryPurge == "true") array_map( "unlink", glob( "$CSVOutputDirectory/*.csv" ) );

foreach($MatchResultsList as $MatchEntry)
	{
	$MatchContents=file_get_contents($SourceFileLocation . "/" . $MatchEntry);
	$MatchJSONDecoded = json_decode($MatchContents,true); #need to set json_decode assoc value to true or else will return stdClass values
	#echo $MatchContents;
	$MatchDetailedResults[]=$MatchJSONDecoded;
	}

#print_r($MatchDetailedResults);

#set up raw csv output
$CSVMasterFile = "$CSVOutputDirectory/AllMatches.csv";

#put header row into csv file if output file doesn't already exist
if (file_exists($CSVMasterFile) === FALSE)
	{
	file_put_contents($CSVMasterFile,$CSVHeader);
	}

#set up summary html output
$HTMLSummaryFile = "$HTMLOutputDirectory/summary.html";

#put header row into html file if output file doesn't already exist
if (file_exists($HTMLSummaryFile) === FALSE)
	{
	file_put_contents($HTMLSummaryFile,"$HTMLHeader \r\n $HTMLHeader2 \r\n");
	$SummaryTitleString = "<h2><center>Overall Scoring Summary</center></h2>";
	file_put_contents($HTMLSummaryFile,"$SummaryTitleString \r\n $HTMLHeaderSummary \r\n",FILE_APPEND);
	}


foreach($MatchDetailedResults as $MatchData)
	{
	$MatchTeamNumber=$MatchData[teamNumber];
	$MatchMatchNumber=$MatchData[matchNumber];
	$MatchAllianceColor=$MatchData[allianceColor];

	#get team name
	$MatchTeamName = GetTeamName($MatchTeamNumber);
	$MatchTeamName = $MatchTeamName[2];

	#set up raw csv output
	$CSVOutputFile = "$CSVOutputDirectory/$MatchTeamNumber.csv";

	#put header row into CSV file if output file doesn't already exist
	if (file_exists($CSVOutputFile) === FALSE)
		{
		file_put_contents($CSVOutputFile,$CSVHeader);

		#since this is a new team being processed, get team name to go with team number
		}

	$MatchAutonomousSkystoneDelivered =  $MatchData[Autonomous][item0][textValue];
		if ($MatchAutonomousSkystoneDelivered == "") $MatchAutonomousSkystoneDelivered = 0;
	$MatchAutonomousStoneDelivered =   $MatchData[Autonomous][item2][value];
		if ($MatchAutonomousStoneDelivered == "") $MatchAutonomousStoneDelivered = 0;
	$MatchAutonomousFoundationMoved =    $MatchData[Autonomous][item1][textValue];
		if ($MatchAutonomousFoundationMoved == "") $MatchAutonomousFoundationMoved = 0;
	$MatchAutonomousStonePlaced = $MatchData[Autonomous][item2][value];
		if ($MatchAutonomousStonePlaced == "") $MatchAutonomousStonePlaced = 0;
	$MatchAutonomousParked =     $MatchData[Autonomous][item4][textValue];
		if ($MatchAutonomousParked == "") $MatchAutonomousParked = 0;

	$MatchTeleOpStonePlaced =  $MatchData[TeleOp][item1][value];
		if ($MatchTeleOpStonePlaced == "") $MatchTeleOpStonePlaced = 0;
	$MatchTeleOpStoneDelivered =    $MatchData[TeleOp][item0][value];
	$MatchTeleOpHighestTower = 		$MatchData[TeleOp][item3][value];

	$MatchEndGameCapstone =            $MatchData[EndGame][item0][value];
		if ($MatchEndGameCapstone == "") $MatchEndGameCapstone = 0;
	$MatchEndGameCapstoneLevels =    $MatchData[EndGame][item1][value];
		if ($MatchEndGameCapstoneLevels == "") $MatchEndGameCapstoneLevels = 0;
	$MatchEndGameFoundationMoved =  $MatchData[EndGame][item2][value];
		if ($MatchEndGameFoundationMoved == "") $MatchEndGameFoundationMoved = 0;
	$MatchEndGameParking =   $MatchData[EndGame][item3][value];
		if ($MatchEndGameParking == "") $MatchEndGameParking = 0;
	
	$MatchExtrasRobotTransportingStones = $MatchData[Extras][item0][value];
		if ($MatchExtrasRobotTransportingStones == "-1") $MatchExtrasRobotTransportingStones = "";
		if ($MatchExtrasRobotTransportingStones == "0") $MatchExtrasRobotTransportingStones = "good";
		if ($MatchExtrasRobotTransportingStones == "1") $MatchExtrasRobotTransportingStones = "so-so";
		if ($MatchExtrasRobotTransportingStones == "2") $MatchExtrasRobotTransportingStones = "bad";
		if ($MatchExtrasRobotTransportingStones == "3") $MatchExtrasRobotTransportingStones = "did not attempt";
	$MatchExtrasRobotPlacingStones = $MatchData[Extras][item1][value];
		if ($MatchExtrasRobotPlacingStones == "-1") $MatchExtrasRobotPlacingStones = "";
		if ($MatchExtrasRobotPlacingStones == "0") $MatchExtrasRobotPlacingStones = "good";
		if ($MatchExtrasRobotPlacingStones == "1") $MatchExtrasRobotPlacingStones = "so-so";
		if ($MatchExtrasRobotPlacingStones == "2") $MatchExtrasRobotPlacingStones = "bad";
		if ($MatchExtrasRobotPlacingStones == "3") $MatchExtrasRobotPlacingStones = "did not attempt";
	$MatchExtrasRobotSpeed =                   $MatchData[Extras][item2][value];
		if ($MatchExtrasRobotSpeed == "-1") $MatchExtrasRobotSpeed = "";
		if ($MatchExtrasRobotSpeed == "0") $MatchExtrasRobotSpeed = "fast";
		if ($MatchExtrasRobotSpeed == "1") $MatchExtrasRobotSpeed = "medium";
		if ($MatchExtrasRobotSpeed == "2") $MatchExtrasRobotSpeed = "slow";
		if ($MatchExtrasRobotSpeed == "3") $MatchExtrasRobotSpeed = "did not move";
	$MatchExtrasRobotProblemsConnections =     $MatchData[Extras][item3][box0Checked];
		if ($MatchExtrasRobotProblemsConnections == "1") $MatchExtrasRobotProblemsConnections = "yes";
	$MatchExtrasRobotProblemsMechanical =      $MatchData[Extras][item3][box1Checked];
		if ($MatchExtrasRobotProblemsMechanical == "1") $MatchExtrasRobotProblemsMechanical = "yes";

		#sae - need to sanitize comments to not goof up html or Excel output
	$MatchExtrasComments =                     $MatchData[Extras][item4][value];
	$MatchExtrasComments = strip_tags($MatchExtrasComments);
	$MatchExtrasComments = str_replace(",","&#44;",$MatchExtrasComments);
	$MatchExtrasComments = str_replace("'","&#39;",$MatchExtrasComments);
	$MatchExtrasComments = str_replace("&","&amp;",$MatchExtrasComments);

	echo "\tProcessing: $MatchTeamNumber - $MatchMatchNumber\r\n";
	#echo "\tMatchNumber: $MatchMatchNumber\r\n";
	#echo "\tAllianceColor: $MatchAllianceColor\r\n";

	#write CSV values out to CSV file
	$CSVOutput  = "$MatchTeamNumber";
	$CSVOutput .= ",$MatchTeamName";
	$CSVOutput .= ",$MatchMatchNumber";
	$CSVOutput .= ",$MatchAllianceColor";
	$CSVOutput .= ",$MatchAutonomousSkystoneDelivered";
	$CSVOutput .= ",$MatchAutonomousStoneDelivered";
	$CSVOutput .= ",$MatchAutonomousFoundationMoved";
	$CSVOutput .= ",$MatchAutonomousStonePlaced";
	$CSVOutput .= ",$MatchAutonomousParked";

	$CSVOutput .= ",$MatchTeleOpStonePlaced";
	$CSVOutput .= ",$MatchTeleOpStoneDelivered";

	$CSVOutput .= ",$MatchEndGameCapstone";
	$CSVOutput .= ",$MatchEndGameCapstoneLevels";
	$CSVOutput .= ",$MatchEndGameFoundationMoved";
	$CSVOutput .= ",$MatchEndGameParking";

	$CSVOutput .= ",$MatchExtrasRobotTransportingStones";
	$CSVOutput .= ",$MatchExtrasRobotPlacingStones";
	$CSVOutput .= ",$MatchExtrasRobotSpeed";
	$CSVOutput .= ",$MatchExtrasRobotProblemsConnections";
	$CSVOutput .= ",$MatchExtrasRobotProblemsMechanical";
	$CSVOutput .= ",$MatchExtrasComments";
	$CSVOutput .= "\r\n";

	file_put_contents($CSVOutputFile,$CSVOutput,FILE_APPEND);
	file_put_contents($CSVMasterFile,$CSVOutput,FILE_APPEND);



#set up aggregates for each team on new team calculations
	if ($TeamNumberCurrent != $MatchTeamNumber)
		{
		$TempValue = GetTeamName($MatchTeamNumber);
		$TempValue = $TempValue[2];
		$TeamNumbers[$MatchTeamNumber] = $TempValue;
		}

	$TeamNumberCurrent = $MatchTeamNumber;

	#update aggregate counts for each team
	$TeamScoring[$MatchTeamNumber][AggregateMatchCount] += 1;


	#set up html output
	$HTMLOutputFile = "$HTMLOutputDirectory/$MatchTeamNumber.html";

	#put header row into HTML file if output file doesn't already exist
	if (file_exists($HTMLOutputFile) === FALSE)
		{
		#file_put_contents($HTMLOutputFile,"$HTMLHeader \r\n $HTMLHeader2 \r\n $HTMLHeaderTeam");


		file_put_contents($HTMLOutputFile,"$HTMLHeader \r\n $HTMLHeader2 \r\n");

		$TempValue = GetTeamName($MatchTeamNumber);
		$TempValue = $TempValue[2];

		#$SummaryTitleString = "<h2><center>Scoring Summary for Team #$MatchTeamNumber - " . $TempValue . "</center></h2>";
		$SummaryTitleString = "";

		$TeamInfoArray = GetTeamName($MatchTeamNumber);
		$TeamLocation = $TeamInfoArray[6];
		$TeamLocation = str_replace("\r","",$TeamLocation);
		$TeamLocation = str_replace("\n","",$TeamLocation);
		$SummaryTitleString .= "<center><table border=0>";
		$SummaryTitleString .= "<td>Team:</td><td><big><b>$TeamInfoArray[2]</td></tr>";
		$SummaryTitleString .= "<td>Number:</td><td><big><b>$TeamInfoArray[1]</td></tr>";
		$SummaryTitleString .= "<td><small>Location:</td><td><small>$TeamLocation</td></tr>";
		$SummaryTitleString .= "<td><small>Website:</td><td><a href=http://$TeamInfoArray[5]><small>$TeamInfoArray[5]</a></td></tr>";
		$SummaryTitleString .= "<td><small>Official Name:</td><td><small>$TeamInfoArray[3]</td></tr>";
		$SummaryTitleString .= "<td><small>Year Started:</td><td><small>$TeamInfoArray[4]</td></tr>";
		$SummaryTitleString .= "</table>";


		file_put_contents($HTMLOutputFile,"$SummaryTitleString \r\n $HTMLHeaderTeam \r\n",FILE_APPEND);

		}

	$MatchContribution = 0;
	$HTMLOutputTeam  = "<tr>";
	$HTMLOutputTeam .= "<td $HTMLHeaderDecoration<center> $MatchTeamNumber <br> $MatchTeamName </td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderDecoration $MatchMatchNumber</td>";
	if ($MatchAllianceColor == "Red")      $HTMLOutputTeam .= "<td $HTMLHeaderDecorationRed> $HTMLHeaderDecoration $MatchAllianceColor</td>";
	elseif ($MatchAllianceColor == "Blue") $HTMLOutputTeam .= "<td $HTMLHeaderDecorationBlue> $HTMLHeaderDecoration $MatchAllianceColor</td>";
	else $HTMLOutputTeam .= "<td> $HTMLHeaderDecoration $MatchAllianceColor</td>";
	$HTMLOutputTeam .= "<td></td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration ($MatchAutonomousSkystoneDelivered)";
	$PointsTotal = 0;
	
	$PointsTotal = $ScoringAutonomousSkystoneDelivered * intval($MatchAutonomousSkystoneDelivered);
	
	$TeamScoring[$MatchTeamNumber][AggregateMatchAutonomousSkystoneDelivered] += $PointsTotal;
	
	
	$MatchContribution += $PointsTotal;
	
	$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	
	$HTMLOutputTeam .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration ($MatchAutonomousStoneDelivered)";
		$PointsTotal = 0;
		$PointsTotal = $MatchAutonomousStoneDelivered * $ScoringAutonomousStoneDelivered;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchAutonomousStoneDelivered] += $PointsTotal;
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration ($MatchAutonomousFoundationMoved)";
	$PointsTotal = 0;
	
	if(strcmp($MatchAutonomousFoundationMoved, "Achieved") == 0)
	{
		$PointsTotal = $ScoringAutonomousFoundationMoved;
		
	}
	else
	{
		$PointsTotal = 0;
	}
	
	$TeamScoring[$MatchTeamNumber][AggregateMatchAutonomousFoundation] += $PointsTotal;
	
	$MatchContribution += $PointsTotal;
	$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration ($MatchAutonomousStonePlaced)";
		$PointsTotal = 0;
		$PointsTotal = $MatchAutonomousStonePlaced * $ScoringAutonomousStonePlaced;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchAutonomousStonePlaced] += $PointsTotal;
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	
	$HTMLOutputTeam .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration ($MatchAutonomousParked)";
		$PointsTotal = 0;
		
		if(strcmp($MatchAutonomousParked, "Parked") == 0)
		{
			$PointsTotal = $ScoringAutonomousParked;
		}
		else{
			$PointsTotal = 0;
		}
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchAutonomousParked] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";

	$HTMLOutputTeam .= "<td></td>";

	
	#Tele-Op
	
	
	$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchTeleOpStoneDelivered)";
		$PointsTotal = 0;
		$PointsTotal = $MatchTeleOpStoneDelivered * $ScoringTeleOpStoneDelivered;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchTeleOpStoneDelivered] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchTeleOpStonePlaced)";
		$PointsTotal = 0;
		$PointsTotal = $MatchTeleOpStonePlaced * $ScoringTeleOpStonePlaced;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchTeleOpStonePlaced] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	
	$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchTeleOpHighestTower)";
		$PointsTotal = 0;
		$PointsTotal = $MatchTeleOpHighestTower * $ScoringTeleOpHighestTower;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchTeleOpHighestTower] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	$HTMLOutputTeam .= "<td></td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration ($MatchEndGameCapstone)";
		$PointsTotal = 0;
		
		if($MatchEndGameCapstone == true)
		{
			$PointsTotal = $ScoringEndGameCapstone;
		}
		else
		{
			$PointsTotal = 0;
		}
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchEndGameCapstone] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	
		$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchEndGameCapstoneLevels)";
		$PointsTotal = 0;
		$PointsTotal = $MatchEndGameCapstoneLevels * $ScoringEndGameCapstoneLevels;
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchEndGameCapstoneLevels] += $PointsTotal;
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	
		$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchEndGameFoundationMoved)";
		$PointsTotal = 0;
		
		if($MatchEndGameFoundationMoved == true)
		{
			$PointsTotal = $ScoringEndGameFoundationMoved;
		}
		else
		{
			$PointsTotal = 0;
		}
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchEndGameFoundationMoved] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";
	
	
		$HTMLOutputTeam .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration ($MatchEndGameParking)";
		$PointsTotal = 0;
		
		if($MatchEndGameParking == true)
		{
			$PointsTotal = $ScoringEndGameParking;
		}
		else
		{
			$PointsTotal = 0;
		}
		
		$TeamScoring[$MatchTeamNumber][AggregateMatchEndGameParking] += $PointsTotal;
		
		$MatchContribution += $PointsTotal;
		$HTMLOutputTeam .= "<br></small><big>$PointsTotal</td>";
	$HTMLOutputTeam .= "</td>";


	$HTMLOutputTeam .= "<td></td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderEstimatedContribution>$HTMLCellDecoration </small><big>$MatchContribution</td>";

	$HTMLOutputTeam .= "<td></td>";

	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasRobotTransportingStones</td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasRobotPlacingStones</td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasRobotSpeed</td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasRobotProblemsConnections</td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasRobotProblemsMechanical</td>";
	$HTMLOutputTeam .= "<td $HTMLHeaderExtras>$HTMLCellDecoration $MatchExtrasComments</td>";
	$HTMLOutputTeam .= "</tr>\r\n";
	if (file_exists($HTMLOutputFile) === TRUE)
		{
		file_put_contents($HTMLOutputFile,"\r\n $HTMLOutputTeam",FILE_APPEND);
		}
	$TeamMatchScores[$MatchTeamNumber][] = $MatchContribution;
	} # end of looping through each match


foreach($TeamNumbers as $TeamNumber => $TeamNameString)
	{
	$HTMLOutputFile = "$HTMLOutputDirectory/$TeamNumber.html";
	$HTMLOutputTeamSummary = "<tr></tr><tr><td colspan=3 align=right>Average Score:&nbsp;</td>";
	$HTMLOutputTeamSummary .= "<td></td>";
	$MatchCount = $TeamScoring[$TeamNumber][AggregateMatchCount];
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDelivered] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchAutonomousStonePlaced] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchAutonomousStoneDelivered] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) . "</td>";
		$HTMLOutputTeamSummary .= "<td></td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonePlaced] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) . "</td>";
		$HTMLOutputTeamSummary .= "<td></td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstone] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td><center>" . round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) . "</td>";
	$HTMLOutputTeamSummary .= "<td></td>";

	$EstimatedScoringContribution  = $TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousStoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousStonePlaced];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousParked];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndCapstone];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameParking];
	$EstimatedScoringContribution = round($EstimatedScoringContribution / $MatchCount,1);
	$HTMLOutputTeamSummary .= "<td><center>" . $EstimatedScoringContribution . "</td>";


	#$HTMLOutputTeamSummary .= "</tr>";
	if (file_exists($HTMLOutputFile) === TRUE)
		{
		file_put_contents($HTMLOutputFile,"\r\n $HTMLOutputTeamSummary",FILE_APPEND);
		file_put_contents($HTMLOutputFile,"\r\n $HTMLFooter",FILE_APPEND);
		}
	}



#get team estimated scoring contribution total and sort that way for display
foreach($TeamNumbers as $TeamNumber => $TeamNameString)
	{
	$MatchCount = $TeamScoring[$TeamNumber][AggregateMatchCount];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDelivered];
		$EstimatedScoringContribution  = $TeamScoring[$TeamNumber][AggregateMatchAutonomousStoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousStonePlaced];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousParked];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndCapstone];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameParking];
	$EstimatedScoringContribution = round($EstimatedScoringContribution / $MatchCount,1);
	$TeamRankings[$TeamNumber]=$EstimatedScoringContribution;

	#determine top scores for display emphasis
	if ((round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoSkystoneDelivered)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoCorrectJewelsScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoCorrectJewelsScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) > (float)$GlobalAutoSkystoneDelivered)
		{
		$GlobalAutoCorrectJewelsScoredThird = $GlobalAutoCorrectJewelsScoredSecond;
		$GlobalAutoCorrectJewelsScoredSecond = $GlobalAutoSkystoneDelivered;
		$GlobalAutoSkystoneDelivered = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) > (float)$GlobalAutoCorrectJewelsScoredSecond)
		{
		$GlobalAutoCorrectJewelsScoredThird = $GlobalAutoCorrectJewelsScoredSecond;
		$GlobalAutoCorrectJewelsScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1) > (float)$GlobalAutoCorrectJewelsScoredThird)
		{
		$GlobalAutoCorrectJewelsScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoInCorrectJewelsScoredFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoInCorrectJewelsScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1) == (float)$GlobalAutoInCorrectJewelsScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1) > (float)$GlobalAutoInCorrectJewelsScoredFirst)
		{
		$GlobalAutoInCorrectJewelsScoredThird = $GlobalAutoInCorrectJewelsScoredSecond;
		$GlobalAutoInCorrectJewelsScoredSecond = $GlobalAutoInCorrectJewelsScoredFirst;
		$GlobalAutoInCorrectJewelsScoredFirst = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount > (float)$GlobalAutoInCorrectJewelsScoredSecond,1))
		{
		$GlobalAutoInCorrectJewelsScoredThird = $GlobalAutoInCorrectJewelsScoredSecond;
		$GlobalAutoInCorrectJewelsScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1) > (float)$GlobalAutoInCorrectJewelsScoredThird)
		{
		$GlobalAutoRedJewelsScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) == (float)$GlobalAutoGlyphsScoredFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) == (float)$GlobalAutoGlyphsScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) == (float)$GlobalAutoGlyphsScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) > (float)$GlobalAutoGlyphsScoredFirst)
		{
		$GlobalAutoGlyphsScoredThird = $GlobalAutoGlyphsScoredSecond;
		$GlobalAutoGlyphsScoredSecond = $GlobalAutoGlyphsScoredFirst;
		$GlobalAutoGlyphsScoredFirst = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount > (float)$GlobalAutoGlyphsScoredSecond,1))
		{
		$GlobalAutoGlyphsScoredThird = $GlobalAutoGlyphsScoredSecond;
		$GlobalAutoGlyphsScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1) > (float)$GlobalAutoGlyphsScoredThird)
		{
		$GlobalAutoGlyphsScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1) == (float)$GlobalAutoFirstGlyphKeyedFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1) == (float)$GlobalAutoFirstGlyphKeyedSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1) == (float)$GlobalAutoFirstGlyphKeyedThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1) > (float)$GlobalAutoFirstGlyphKeyedFirst)
		{
		$GlobalAutoFirstGlyphKeyedThird = $GlobalAutoFirstGlyphKeyedSecond;
		$GlobalAutoFirstGlyphKeyedSecond = $GlobalAutoFirstGlyphKeyedFirst;
		$GlobalAutoFirstGlyphKeyedFirst = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount > (float)$GlobalAutoFirstGlyphKeyedSecond,1))
		{
		$GlobalAutoFirstGlyphKeyedThird = $GlobalAutoFirstGlyphKeyedSecond;
		$GlobalAutoFirstGlyphKeyedSecond = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1) > (float)$GlobalAutoFirstGlyphKeyedThird)
		{
		$GlobalAutoFirstGlyphKeyedThird = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) == (float)$GlobalAutoRobotParkedFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) == (float)$GlobalAutoRobotParkedSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) == (float)$GlobalAutoRobotParkedThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) > (float)$GlobalAutoRobotParkedFirst)
		{
		$GlobalAutoRobotParkedThird = $GlobalAutoRobotParkedSecond;
		$GlobalAutoRobotParkedSecond = $GlobalAutoRobotParkedFirst;
		$GlobalAutoRobotParkedFirst = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount > (float)$GlobalAutoRobotParkedSecond,1))
		{
		$GlobalAutoRobotParkedThird = $GlobalAutoRobotParkedSecond;
		$GlobalAutoRobotParkedSecond = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) > (float)$GlobalAutoRobotParkedThird)
		{
		$GlobalAutoRobotParkedThird = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1) == (float)$GlobalTeleOpGlyphsScoredFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1) == (float)$GlobalTeleOpGlyphsScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1) == (float)$GlobalTeleOpGlyphsScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1) > (float)$GlobalTeleOpGlyphsScoredFirst)
		{
		$GlobalTeleOpGlyphsScoredThird = $GlobalTeleOpGlyphsScoredSecond;
		$GlobalTeleOpGlyphsScoredSecond = $GlobalTeleOpGlyphsScoredFirst;
		$GlobalTeleOpGlyphsScoredFirst = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount > (float)$GlobalTeleOpGlyphsScoredSecond,1))
		{
		$GlobalTeleOpGlyphsScoredThird = $GlobalAutoRobotParkedSecond;
		$GlobalTeleOpGlyphsScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1) > (float)$GlobalTeleOpGlyphsScoredThird)
		{
		$GlobalTeleOpGlyphsScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) == (float)$GlobalTeleOpGlyphRowsFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) == (float)$GlobalTeleOpGlyphRowsSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) == (float)$GlobalTeleOpGlyphRowsThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) > (float)$GlobalTeleOpGlyphRowsFirst)
		{
		$GlobalTeleOpGlyphRowsThird = $GlobalTeleOpGlyphRowsSecond;
		$GlobalTeleOpGlyphRowsSecond = $GlobalTeleOpGlyphRowsFirst;
		$GlobalTeleOpGlyphRowsFirst = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount > (float)$GlobalTeleOpGlyphRowsSecond,1))
		{
		$GlobalTeleOpGlyphRowsThird = $GlobalTeleOpGlyphRowsSecond;
		$GlobalTeleOpGlyphRowsSecond = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1) > (float)$GlobalTeleOpGlyphRowsThird)
		{
		$GlobalTeleOpGlyphRowsThird = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) == (float)$GlobalTeleOpGlyphColumnsFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) == (float)$GlobalTeleOpGlyphColumnsSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) == (float)$GlobalTeleOpGlyphColumnsThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) > (float)$GlobalTeleOpGlyphColumnsFirst)
		{
		$GlobalTeleOpGlyphColumnsThird = $GlobalTeleOpGlyphColumnsSecond;
		$GlobalTeleOpGlyphColumnsSecond = $GlobalTeleOpGlyphColumnsFirst;
		$GlobalTeleOpGlyphColumnsFirst = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount > (float)$GlobalTeleOpGlyphColumnsSecond,1))
		{
		$GlobalTeleOpGlyphColumnsThird = $GlobalTeleOpGlyphColumnsSecond;
		$GlobalTeleOpGlyphColumnsSecond = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1) > (float)$GlobalTeleOpGlyphColumnsThird)
		{
		$GlobalTeleOpGlyphColumnsThird = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1) == (float)$GlobalTeleOpCyphersFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1) == (float)$GlobalTeleOpCyphersSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1) == (float)$GlobalTeleOpCyphersThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1) > (float)$GlobalTeleOpCyphersFirst)
		{
		$GlobalTeleOpCyphersThird = $GlobalTeleOpCyphersSecond;
		$GlobalTeleOpCyphersSecond = $GlobalTeleOpCyphersFirst;
		$GlobalTeleOpCyphersFirst = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount > (float)$GlobalTeleOpCyphersSecond,1))
		{
		$GlobalTeleOpCyphersThird = $GlobalTeleOpCyphersSecond;
		$GlobalTeleOpCyphersSecond = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1) > (float)$GlobalTeleOpCyphersThird)
		{
		$GlobalTeleOpCyphersThird = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1) == (float)$GlobalEndGameBalancedFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1) == (float)$GlobalEndGameBalancedSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1) == (float)$GlobalEndGameBalancedThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1) > (float)$GlobalEndGameBalancedFirst)
		{
		$GlobalEndGameBalancedThird = $GlobalEndGameBalancedSecond;
		$GlobalEndGameBalancedSecond = $GlobalEndGameBalancedFirst;
		$GlobalEndGameBalancedFirst = round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount > (float)$GlobalEndGameBalancedSecond,1))
		{
		$GlobalEndGameBalancedThird = $GlobalEndGameBalancedSecond;
		$GlobalEndGameBalancedSecond = round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1) > (float)$GlobalEndGameBalancedThird)
		{
		$GlobalEndGameBalancedThird = round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicScoredFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) > (float)$GlobalEndGameFirstRelicScoredFirst)
		{
		$GlobalEndGameFirstRelicScoredThird = $GlobalEndGameFirstRelicScoredSecond;
		$GlobalEndGameFirstRelicScoredSecond = $GlobalEndGameFirstRelicScoredFirst;
		$GlobalEndGameFirstRelicScoredFirst = round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount > (float)$GlobalEndGameFirstRelicScoredSecond,1))
		{
		$GlobalEndGameFirstRelicScoredThird = $GlobalEndGameFirstRelicScoredSecond;
		$GlobalEndGameFirstRelicScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1) > (float)$GlobalEndGameFirstRelicScoredThird)
		{
		$GlobalEndGameFirstRelicScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicStandingFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicStandingSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) == (float)$GlobalEndGameFirstRelicStandingThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) > (float)$GlobalEndGameFirstRelicStandingFirst)
		{
		$GlobalEndGameFirstRelicStandingThird = $GlobalEndGameFirstRelicStandingSecond;
		$GlobalEndGameFirstRelicStandingSecond = $GlobalEndGameFirstRelicStandingFirst;
		$GlobalEndGameFirstRelicStandingFirst = round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount > (float)$GlobalEndGameFirstRelicStandingSecond,1))
		{
		$GlobalEndGameFirstRelicStandingThird = $GlobalEndGameFirstRelicStandingSecond;
		$GlobalEndGameFirstRelicStandingSecond = round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1) > (float)$GlobalEndGameFirstRelicStandingThird)
		{
		$GlobalEndGameFirstRelicStandingThird = round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicScoredFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicScoredSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicScoredThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) > (float)$GlobalEndGameSecondRelicScoredFirst)
		{
		$GlobalEndGameSecondRelicScoredThird = $GlobalEndGameSecondRelicScoredSecond;
		$GlobalEndGameSecondRelicScoredSecond = $GlobalEndGameSecondRelicScoredFirst;
		$GlobalEndGameSecondRelicScoredFirst = round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount > (float)$GlobalEndGameSecondRelicScoredSecond,1))
		{
		$GlobalEndGameSecondRelicScoredThird = $GlobalEndGameSecondRelicScoredSecond;
		$GlobalEndGameSecondRelicScoredSecond = round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1) > (float)$GlobalEndGameSecondRelicScoredThird)
		{
		$GlobalEndGameSecondRelicScoredThird = round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1);
		}

	if ((round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicStandingFirst)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicStandingSecond)
		|| (round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1) == (float)$GlobalEndGameSecondRelicStandingThird))
		;
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1) > (float)$GlobalEndGameSecondRelicStandingFirst)
		{
		$GlobalEndGameSecondRelicStandingThird = $GlobalEndGameSecondRelicStandingSecond;
		$GlobalEndGameSecondRelicStandingSecond = $GlobalEndGameSecondRelicStandingFirst;
		$GlobalEndGameSecondRelicStandingFirst = round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount > (float)$GlobalEndGameSecondRelicStandingSecond,1))
		{
		$GlobalEndGameSecondRelicStandingThird = $GlobalEndGameSecondRelicStandingSecond;
		$GlobalEndGameSecondRelicStandingSecond = round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1);
		}
	elseif (round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1) > (float)$GlobalEndGameSecondRelicStandingThird)
		{
		$GlobalEndGameSecondRelicStandingThird = round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1);
		}


#echo round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1) . "\r\n";
#echo "  $GlobalAutoGlyphsScoredFirst \r\n";
#echo "  $GlobalAutoGlyphsScoredSecond \r\n";
#echo "  $GlobalAutoGlyphsScoredThird \r\n\r\n";



	}
arsort($TeamRankings);

#write out data file for each team for summary page
$HTMLOutputSummary = "";

$TeamRank = 1;
$LoopCounter = 1;
$PreviousTeamScore = 9999;


foreach($TeamRankings as $TeamNumber => $Score)
	{
	$MatchCount = $TeamScoring[$TeamNumber][AggregateMatchCount];
	$EstimatedScoringContribution  = $TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchAutonomousParked];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndCapstone];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameParking];
	$EstimatedScoringContribution += $TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding];
	$EstimatedScoringContribution = round($EstimatedScoringContribution / $MatchCount,1);


	if ($EstimatedScoringContribution < $PreviousTeamScore)
		{
		$TeamRank = $LoopCounter;
		$PreviousTeamScore = $EstimatedScoringContribution;
		}
	$LoopCounter++;

	$AggregateMatchContribution = 0;
	$HTMLOutputSummary .= "<tr>";

	$TeamInfoArray = GetTeamName($TeamNumber);
	#print_r($TeamInfoArray);
	$TeamLocation = $TeamInfoArray[6];
	$TeamLocation = str_replace("\r","",$TeamLocation);
	$TeamLocation = str_replace("\n","",$TeamLocation);
	$TeamInfo = "title=\"Team: $TeamInfoArray[2]\r\nNumber: $TeamInfoArray[1]\r\nLocation: $TeamLocation\r\nWebsite: $TeamInfoArray[5]\r\nOfficial Name: $TeamInfoArray[3]\r\nYear Started: $TeamInfoArray[4]\"";
	$TempValue = $TeamInfoArray[2];
	$HTMLOutputSummary .= "<td $TeamInfo>$HTMLHeaderDecoration <center><a href=$TeamNumber.html>$TeamNumber</a><br>";

 	$HTMLOutputSummary .= "<a href=$TeamNumber.html> " . $TempValue . "</a></td>";
	$HTMLOutputSummary .= "<td> $HTMLHeaderDecoration " . $MatchCount . " </td>";
	$HTMLOutputSummary .= "<td> $HTMLHeaderDecoration " . $TeamRank . " </td>";
	$HTMLOutputSummary .= "<td></td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousCorrectJewelMoved] / $MatchCount,1);
	if (($TemporaryValue == $GlobalAutoSkystoneDelivered) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoCorrectJewelsScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoCorrectJewelsScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousInCorrectJewelMoved] / $MatchCount,1);
	if (($TemporaryValue == $GlobalAutoInCorrectJewelsScoredFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoInCorrectJewelsScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoInCorrectJewelsScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousFoundationMoved] / $MatchCount,1);
	if (($TemporaryValue == $GlobalAutoGlyphsScoredFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoGlyphsScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoGlyphsScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousSkystoneDeliver] / $MatchCount,1);
	if (($TemporaryValue == $GlobalAutoFirstGlyphKeyedFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoFirstGlyphKeyedSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoFirstGlyphKeyedThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchAutonomousParked] / $MatchCount,1);
	if (($TemporaryValue == $GlobalAutoRobotParkedFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoRobotParkedSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalAutoRobotParkedThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderAutonomous>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderAutonomous>$HTMLCellDecoration " . $TemporaryValue . " </td>";


	$HTMLOutputSummary .= "<td></td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStonesPlaced] / $MatchCount,1);
	if (($TemporaryValue == $GlobalTeleOpGlyphsScoredFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderTeleOp>$HTMLCellDecoration <b>" . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphsScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderTeleOp>$HTMLCellDecoration <b>" . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphsScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderTeleOp>$HTMLCellDecoration  <b>" . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpStoneDelivered] / $MatchCount,1);
	if (($TemporaryValue == $GlobalTeleOpGlyphRowsFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphRowsSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphRowsThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpHighestTower] / $MatchCount,1);
	if (($TemporaryValue == $GlobalTeleOpGlyphColumnsFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphColumnsSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpGlyphColumnsThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchTeleOpGlyphCyphers] / $MatchCount,1);
	if (($TemporaryValue == $GlobalTeleOpCyphersFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpCyphersSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalTeleOpCyphersThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderTeleOp>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderTeleOp>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$HTMLOutputSummary .= "<td></td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchEndCapstone] / $MatchCount,1);
	if (($TemporaryValue == $GlobalEndGameBalancedFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameBalancedSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameBalancedThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchEndGameCapstoneLevels] / $MatchCount,1);
	if (($TemporaryValue == $GlobalEndGameFirstRelicScoredFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameFirstRelicScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameFirstRelicScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchEndGameFoundationMoved] / $MatchCount,1);
	if (($TemporaryValue == $GlobalEndGameFirstRelicStandingFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameFirstRelicStandingSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameFirstRelicStandingThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchEndGameParking] / $MatchCount,1);
	if (($TemporaryValue == $GlobalEndGameSecondRelicScoredFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameSecondRelicScoredSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameSecondRelicScoredThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration " . $TemporaryValue . " </td>";

	$TemporaryValue = round($TeamScoring[$TeamNumber][AggregateMatchEndGameSecondRelicStanding] / $MatchCount,1);
	if (($TemporaryValue == $GlobalEndGameSecondRelicStandingFirst) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankFirst $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameSecondRelicStandingSecond) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankSecond $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	elseif (($TemporaryValue == $GlobalEndGameSecondRelicStandingThird) && ($TemporaryValue != 0))
		$HTMLOutputSummary .= "<td $HTMLRankThird $HTMLHeaderEndGame>$HTMLCellDecoration <b> " . $TemporaryValue . " </td>";
	else
		$HTMLOutputSummary .= "<td $HTMLHeaderEndGame>$HTMLCellDecoration " . $TemporaryValue . " </td>";


	$HTMLOutputSummary .= "<td></td>";

	$HTMLOutputSummary .= "<td $$HTMLHeaderEstimatedContribution>$HTMLCellDecoration " . $EstimatedScoringContribution . " </td>";

	#get max/min and standard deviation of match scores for display
	$TeamMaxScore = 0;
	$TeamMinScore = 999;

	foreach ($TeamMatchScores[$TeamNumber] as $MatchScore)
		{
		if ($MatchScore > $TeamMaxScore) $TeamMaxScore = $MatchScore;
		if ($MatchScore < $TeamMinScore) $TeamMinScore = $MatchScore;
		}
	if ($MatchCount > 1) $TeamScoringStandardDeviation = round(standard_deviation($TeamMatchScores[$TeamNumber]),1);
	else $TeamScoringStandardDeviation = 0;
	$HTMLOutputSummary .= "<td $$HTMLHeaderEstimatedContribution>$HTMLCellDecoration " . $TeamScoringStandardDeviation . " </td>";
	$HTMLOutputSummary .= "<td $$HTMLHeaderEstimatedContribution>$HTMLCellDecoration " . $TeamMaxScore . " / " . $TeamMinScore . " </td>";


	$HTMLOutputSummary .= "</tr>\r\n";
	#echo $HTMLOutputSummary;
	}

#write team outputs to summary file
if (file_exists($HTMLSummaryFile) === TRUE)
	{
	file_put_contents($HTMLSummaryFile,"\r\n $HTMLOutputSummary",FILE_APPEND);
	}
$SummaryKey =  "<tr><td colspan=24><center><table border=0><tr>";
$SummaryKey .= "<td>Cell colors:</td><td $HTMLRankFirst>Top Score</td>";
$SummaryKey .= "<td $HTMLRankSecond>Second Top Score</td>";
$SummaryKey .= "<td $HTMLRankThird>Third Top Score</td>";
$SummaryKey .= "</tr></table></tr>";

if (file_exists($HTMLSummaryFile) === TRUE)
	{
	file_put_contents($HTMLSummaryFile,"\r\n $SummaryKey",FILE_APPEND);
	}

#put footer row into html summary file to wrap things up
if (file_exists($HTMLSummaryFile) === TRUE)
	{
	file_put_contents($HTMLSummaryFile,"\r\n $HTMLFooter",FILE_APPEND);
	}

#create index file
	$HTMLIndexFile = "$HTMLOutputDirectory/index.html";
	$HTMLContents = "$HTMLHeader \r\n $HTMLHeader2 \r\n ";
	$HTMLContents .=
	file_put_contents($HTMLIndexFile,$HTMLContents);





?>