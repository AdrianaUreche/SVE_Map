<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php

$type = $_POST['type'];
$answer = $_POST['answer'];
$id = $_POST['id'];

function advancepq($link, $type, $id, $teamid) {
	$typelet = substr($type,0,1);
	$sql = "SELECT ".$typelet."id FROM ".$type."s WHERE ".$typelet."id > ".$id." LIMIT 1";
	if($result = mysqli_query($link, $sql))
		if(mysqli_num_rows($result) == 1)
			$next = mysqli_fetch_array($result)[$typelet."id"];
	mysqli_free_result($result);
	$sql = "UPDATE ".$type."solved set status=2 WHERE teamid = ".$teamid." AND ".$typelet."id = ".$id.";";
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link), "teams.php");
	}
	$sql = "INSERT INTO ".$type."solved (".$typelet."id, teamid, status) VALUES (".$next.", ".$teamid.", 1);";
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link), "teams.php");
	}
}

if(isset($_FILES["answerfile"])) {
	$target_dir = "pq_answerfiles/";
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo(basename($_FILES["answerfile"]["name"]),PATHINFO_EXTENSION));
	$target_file = $target_dir."answerfile".$teamid."-".$type.$id.".".$imageFileType;
	echo "Target file: ".$target_file."<br>";

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["answerfile"]["tmp_name"]);
		if($check !== false) {
//			echo("File is an image - " . $check["mime"] . ".");
			$uploadOk = 1;
		} else {
			error("File is not an image.  Nice try.", "teams.php");
		}
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		error("Sorry, file already exists.  Weird, right?  Do me a favor and report this to an admin?", "teams.php");
	}

	// Check file size
	if ($_FILES["answerfile"]["size"] > 5000000) {
		error("Sorry, your file is too large (5MB maximum, please).", "teams.php");
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		error("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", "teams.php");
	}

	// Check if $uploadOk is set to 0 by an error
	if (move_uploaded_file($_FILES["answerfile"]["tmp_name"], $target_file)) {
//		echo "The file ". basename( $_FILES["answerfile"]["name"]). " has been uploaded.";
		advancepq($link, $type, $id, $teamid);
	   
		$_SESSION['achievements'] = "You have successfully uploaded your proof of completing this ".$type.". You will receive an action point once a Senior Scientist has reviewed your proof and judged its scientific merit.  Until then, you can move on to the next ".$type."\\n";
		if ($id==0) {
			$_SESSION['achievements'] .= "As this was THE FIRST QUEST, after review, you'll be able to occupy your first block!\\n";
			advancepq($link, "puzzle", 0, $teamid);
		}

	} else {
		error("Sorry, there was an error uploading your file.", "teams.php");
	}

}

if(isset($_POST['answer'])) {
	$typelet = substr($type,0,1);
	$sql = "SELECT solution FROM ".$type."s WHERE ".$typelet."id = ".$id;
	if($result = mysqli_query($link, $sql))
		if(mysqli_num_rows($result) == 1)
			$solution = mysqli_fetch_array($result)['solution'];
	mysqli_free_result($result);
	if($solution == strtolower($answer)) {
		advancepq($link, $type, $id, $teamid);
		$sql = "UPDATE teams set action_points = action_points + 1  WHERE teamid = ".$teamid;
                if (!mysqli_query($link, $sql)) {
                        error("Error updating record: " . mysqli_error($link));
                }
		$_SESSION['achievements'] = "Hooray!  You got the correct answer (".$solution.")!  Good job!  You've earned one action point!  Spend it on a new ability, or use it to activate one of your existing abilities.\\n";

	} else {
		error("Sorry, that was not the right solution.  Don't be disheartened!  Try again!", "teams.php");
	}
}

header("Location: teams.php");
