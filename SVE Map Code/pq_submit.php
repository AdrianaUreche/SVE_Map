<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php
// Setup mail sending library
require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/SMTP.php');
require_once('PHPMailer/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$type = $_POST['type'];
$answer = $_POST['answer'];
$id = $_POST['id'];

function advancepq($link, $type, $id, $teamid, $status=2) {
	$typelet = substr($type,0,1);
	$sql = "SELECT ".$typelet."id FROM ".$type."s WHERE ".$typelet."id > ".$id." ORDER BY ".$typelet."id LIMIT 1";
	if($result = mysqli_query($link, $sql))
		if(mysqli_num_rows($result) == 1) {
			$next = mysqli_fetch_array($result)[$typelet."id"];
			$nsql = "INSERT INTO ".$type."solved (".$typelet."id, teamid, status) VALUES (".$next.", ".$teamid.", 1);";
			if (!mysqli_query($link, $nsql)) {
				error("Error7 updating record: " . mysqli_error($link) . "\\n(".$sql.")", "teams.php");
			}
		}
	mysqli_free_result($result);
	$sql = "UPDATE ".$type."solved set status=".$status." WHERE teamid = ".$teamid." AND ".$typelet."id = ".$id.";";
	if (!mysqli_query($link, $sql)) {
		error("Error6 updating record: " . mysqli_error($link), "teams.php");
	}
}

if(isset($_FILES["answerfile"])) {
	$target_dir = "pq_answerfiles/";
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo(basename($_FILES["answerfile"]["name"]),PATHINFO_EXTENSION));
	$target_file = $target_dir."answerfile".$teamid."-".$type.$id.".".$imageFileType;
//	echo "Target file: ".$target_file."<br>";

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
		advancepq($link, $type, $id, $teamid, 5);
	   
		$_SESSION['achievements'] = "You have successfully uploaded your proof of completing this ".$type.". You will receive an action point once a Senior Scientist has reviewed your proof and judged its scientific merit.  Until then, you can move on to the next ".$type."\\n";
		if ($id==0) {
			$_SESSION['achievements'] .= "As this was THE FIRST QUEST, after review, you'll be able to occupy your first block!\\n";
			advancepq($link, "puzzle", 0, $teamid);

			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = '465'; //465
			$mail->isHTML();
			$mail->Username = 'nukees';
			include("/home/nukees/sve_private/email.php");

			//      $mail->SMTPSecure = "tls";
			$mail->SetFrom('scientistsvseveryone@agnostica.com','Scientists Vs. Everyone');

			$mail->Subject = "SvE-bot: New quest to review";
			$mail->Body = "Beep.  This is Sciencebot.  There is a new image submitted for review for Quest#".$id." by team ".$teamname.".<br><br><a href=\"http://sve.nukees.com/".$rootdir."review_special_quests.php\">Click here to review it</a><br><br>Bloop.<br><br>--<br>Sciencebot<br>";
			$mail->AddAddress("darren@nukees.com");
			$mail->AddAddress("mahogony314@hotmail.com");
			$mail->AddAddress("ericsweet2@gmail.com");


			$mail->Send();

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
	if(str_replace(" ","",strtolower($solution)) == str_replace(" ","",strtolower($answer))) {
		advancepq($link, $type, $id, $teamid);
		$sql = "UPDATE teams set action_points = action_points + 1  WHERE teamid = ".$teamid;
                if (!mysqli_query($link, $sql)) {
                        error("Error5 updating record: " . mysqli_error($link));
                }
		$_SESSION['achievements'] = "Hooray!  You got the correct answer (".$solution.")!  Good job!  You've earned one action point!  Spend it on a new ability, or use it to activate one of your existing abilities.\\n";

	} else {
		error("Sorry, that was not the right solution.  Don't be disheartened!  Try again!", "teams.php");
	}
}

header("Location: achievementcheck.php?l=teams.php");

//header("Location: teams.php");
