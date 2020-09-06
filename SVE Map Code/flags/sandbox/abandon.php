<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php

$type = $_GET['type'];
if($type!="quest"&&$type!="puzzle") error("Incorrect type. Stop playing with the URLs.");
$id = $_GET['id'];

function advancepq($link, $type, $id, $teamid, $status=2) {
	$typelet = substr($type,0,1);
	$sql = "SELECT ".$typelet."id FROM ".$type."s WHERE ".$typelet."id > ".$id." ORDER BY ".$typelet."id LIMIT 1";
	if($result = mysqli_query($link, $sql))
		if(mysqli_num_rows($result) == 1)
			$next = mysqli_fetch_array($result)[$typelet."id"];
	mysqli_free_result($result);
	$sql = "UPDATE ".$type."solved set status=".$status." WHERE teamid = ".$teamid." AND ".$typelet."id = ".$id;
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link), "teams.php");
	}
	$sql = "INSERT INTO ".$type."solved (".$typelet."id, teamid, status) VALUES (".$next.", ".$teamid.", 1)";
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link), "teams.php");
	}
}

$typelet = substr($type,0,1);

$status = 0;
$sql = "SELECT status FROM ".$type."solved WHERE ".$typelet."id = ".$id." AND teamid = ".$teamid;
if($result = mysqli_query($link, $sql))
	if(mysqli_num_rows($result) == 1)
		$status = mysqli_fetch_array($result)["status"];
if($status!=1) error("You are not currently undertaking the ".$type." you are trying to abandon (STATUS = ".$status.").  Stop playing with the URLs.");

$sql = "SELECT ".$typelet."id FROM ".$type."s WHERE ".$typelet."id > ".$id." ORDER BY ".$typelet."id LIMIT 1";

if($result = mysqli_query($link, $sql))
	if(mysqli_num_rows($result) == 1) {
		$next = mysqli_fetch_array($result)[$typelet."id"];
	} else {
		error("Actually, there are no more ".$type."s so you wouldn't gain anything by abandoning this one now.  New puzzles/quests will be added Monday, Wednesday, and Friday (at noon Pacific Time), though, so try to abandon this later!");
	}
advancepq($link, $type, $id, $teamid, 3);

header("Location: achievementcheck.php?l=teams.php");

//header("Location: teams.php");
