<?php

function GetTeamName($TeamNumber)
	{
	# lots of optimization can be done here to avoid having to read in file for 
	# each call to this function.  May not be a noticeable performance hit, but 
	# certainly not best practices.

if (file_exists("teamlist.csv"))
		{
		$fullteamlist = file("teamlist.csv");
		foreach ($fullteamlist as $teamline)
			{
			$temparray = explode(",",$teamline);
			#print_r ($temparray);
			if ($temparray[1] == $TeamNumber)
				{
				$League      = trim($temparray[0]); # FTC
				$TeamNumber  = trim($temparray[1]); # Team Number
				$NickName    = trim($temparray[2]); # Nickname
				$FullName    = trim($temparray[3]); # Full name
				$YearStarted = trim($temparray[4]); # year started
				$Website     = trim($temparray[5]); # website
				$Location    = trim($temparray[6]); # location
				return($temparray);
				}
			}
		}
#temporary until full list of teams is captured
	$dummydata = array();
	$dummydata[] = "FTC";
	$dummydata[] = $TeamNumber;
	$dummydata[] = "($TeamNumber)";
	$dummydata[] = "Full Name";
	$dummydata[] = "Year Started";
	$dummydata[] = "Website";
	$dummydata[] = "Location";

	
	return ($dummydata);
	}
	
function standard_deviation($sample){
	if(is_array($sample)){
		$mean = array_sum($sample) / count($sample);
		foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
		return sqrt(array_sum($devs) / (count($devs) - 1));
	}
}
?>
