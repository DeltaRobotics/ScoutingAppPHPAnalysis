<?php

#create CSVHeader for CSV files
$CSVHeader  = "TeamNumber";
$CSVHeader .= ",TeamName";
$CSVHeader .= ",MatchNumber";
$CSVHeader .= ",AllianceColor";

$CSVHeader .= ",AutonomousSkystoneDeliver";
$CSVHeader .= ",AutonomousStoneDeliver";
$CSVHeader .= ",AutonomousFoundationMoved";
$CSVHeader .= ",AutonomousParked";

$CSVHeader .= ",TeleOpGlyphStonePlaced";
$CSVHeader .= ",TeleOpGlyphStoneDelivered";

$CSVHeader .= ",EndGameCapstone";
$CSVHeader .= ",EndGameCapstoneLevels";
$CSVHeader .= ",EndGameFoundationMoved";
$CSVHeader .= ",EndGameParking";

$CSVHeader .= ",ExtrasRobotScoringEffeciencyTransportingStones";
$CSVHeader .= ",ExtrasRobotScoringEffeciencyPlacingStones";
$CSVHeader .= ",ExtrasRobotSpeed";
$CSVHeader .= ",ExtrasRobotProblemsConnections";
$CSVHeader .= ",ExtrasRobotProblemsMechanical";
$CSVHeader .= ",ExtrasComments";
$CSVHeader .= "\r\n";

#create HTMLHeader for Team page and decoration for HTML files
$HTMLHeaderDecoration = "<b><center><small>";
$HTMLHeaderDecorationRed = " bgcolor=red ";
$HTMLHeaderDecorationBlue = " bgcolor=blue ";
$HTMLHeaderEnd = "</center></b>";
$HTMLHeaderTeamInfo = " valign=top ";
$HTMLHeaderAutonomous = " bgcolor=lightyellow valign=top";
$HTMLHeaderTeleOp = " bgcolor=pink valign=top";
$HTMLHeaderEndGame = " bgcolor=lightblue valign=top";
$HTMLHeaderEstimatedContribution = " bgcolor=lightgreen valign=top";
$HTMLHeaderExtras = " bgcolor=gray valign=top";
$HTMLCellDecoration = "<center><small>";
$HTMLHeader = "<html><body>";
$HTMLRankFirst = "bgcolor=LimeGreen";
$HTMLRankSecond = "bgcolor=Gold";
$HTMLRankThird = "bgcolor=Tomato";



$HTMLHeaderTeam .= "<table border=1><tr>";
$HTMLHeaderTeam .= "<td colspan=3 $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Team </td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td colspan=5 $HTMLHeaderAutonomous> $HTMLHeaderDecoration Autonomous </td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td colspan=4 $HTMLHeaderTeleOp> $HTMLHeaderDecoration TeleOp </td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td colspan=5 $HTMLHeaderEndGame> $HTMLHeaderDecoration End Game </td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td colspan=1 $HTMLHeaderEstimatedContribution> $HTMLHeaderDecoration Summary </td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td colspan=6 $HTMLHeaderExtras> $HTMLHeaderDecoration Performance Information </td>";
$HTMLHeaderTeam .= "</tr><tr>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Number / Name $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Match $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Alliance $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Skystones <br> Delivered$HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Stones <br> Delivered$HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Foundation <br> Moved $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Stones <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Parked $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Stones <br> Delivered $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Stones <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Highest Stone <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Capstone $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Capstone <br> Levels $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Foundation <br> Moved $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Parked $HTMLHeaderEnd</td>";

$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderEstimatedContribution>$HTMLHeaderDecoration Estimated <br> Scoring <br> Contribution $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td></td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Stone Transport <br> Efficiency$HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Stone Scoring <br> Efficiency $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Speed $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Connection <br> Problems $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Mechanical <br> Problems $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Comments $HTMLHeaderEnd</td>";
$HTMLHeaderTeam .= "</tr><tr></tr>";

$HTMLHeaderSummary .= "<table border=1><tr>";
$HTMLHeaderSummary .= "<td colspan=3 $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Team </td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td colspan=5 $HTMLHeaderAutonomous> $HTMLHeaderDecoration Autonomous </td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td colspan=4 $HTMLHeaderTeleOp> $HTMLHeaderDecoration TeleOp </td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td colspan=5 $HTMLHeaderEndGame> $HTMLHeaderDecoration End Game </td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td colspan=3 $HTMLHeaderEstimatedContribution> $HTMLHeaderDecoration Summary </td>";
$HTMLHeaderSummary .= "</tr><tr>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Number / Name $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Matches $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeamInfo> $HTMLHeaderDecoration Rank $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Skystones <br> Delivered $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Foundation <br> Moved $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Stones <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Stones <br> Del $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderAutonomous>$HTMLHeaderDecoration Parked $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Stones <br> Delivered $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Stones <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderTeleOp>$HTMLHeaderDecoration Highest Stone <br> Placed $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Capstone $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Capstone <br> Levels $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Foundation <br> Moved $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEndGame>$HTMLHeaderDecoration Parked $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td></td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEstimatedContribution>$HTMLHeaderDecoration Estimated <br> Contribution $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEstimatedContribution>$HTMLHeaderDecoration Standard <br> Deviation $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "<td $HTMLHeaderEstimatedContribution>$HTMLHeaderDecoration <br> Max / Min $HTMLHeaderEnd</td>";
#$HTMLHeaderSummary .= "<td></td>";
#$HTMLHeaderSummary .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Robot Scoring Effeciency <br> Glyphs $HTMLHeaderEnd</td>";
#$HTMLHeaderSummary .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Robot Scoring Effeciency Relics $HTMLHeaderEnd</td>";
#$HTMLHeaderSummary .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Robot Speed $HTMLHeaderEnd</td>";
#$HTMLHeaderSummary .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Robot Problems <br> Connections $HTMLHeaderEnd</td>";
#$HTMLHeaderSummary .= "<td $HTMLHeaderExtras>$HTMLHeaderDecoration Robot Problems <br> Mechanical $HTMLHeaderEnd</td>";
$HTMLHeaderSummary .= "</tr><tr></tr>";

$Year = date('Y');
$HTMLFooter = "</table><br><br><center><small>&copy; $Year - <a href=http://DeltaRoboticsFTC.org>DeltaRoboticsFTC #9925</a></small></center><br>";

?>