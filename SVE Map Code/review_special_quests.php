<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php
if(isset($_POST['Accept'])) {
	$sql = "UPDATE teams set action_points = action_points + 1  WHERE teamid = ".$_POST['Team'];
	if (!mysqli_query($link, $sql)) {
		echo "Error updating record: " . mysqli_error($link);
	} else {
		echo "Accepted Quest ".$_POST['Accept']." by Team #".$_POST['Team'];
		mysqli_free_result($result);
		$sql = "UPDATE questsolved set status = 6 WHERE teamid = ".$_POST['Team']." AND qid = ".$_POST['Accept'];
		if (!mysqli_query($link, $sql)) {
			echo "Error updating record: " . mysqli_error($link);
		} else {
			echo " and updated quest status";
		}
		mysqli_free_result($result);
	}

	if(!rename("pq_answerfiles/".$_POST['File'],"pq_perm_answerfiles/".$_POST['File']))
		echo " but couldn't delete file ".$_POST['File'];
}

if(isset($_POST["Reject"])) {
	echo "Rejected Quest ".$_POST['Reject']." by Team #".$_POST['Team'];
	$sql = "UPDATE questsolved set status = 7 WHERE teamid = ".$_POST['Team']." AND qid = ".$_POST['Reject'];
	if (!mysqli_query($link, $sql)) {
		echo "Error updating record: " . mysqli_error($link);
	} else {
		echo " and updated quest status";
	}
	mysqli_free_result($result);
	if(!rename("pq_answerfiles/".$_POST['File'],"pq_perm_answerfiles/".$_POST['File']))
		echo " but couldn't delete file ".$_POST['File'];
}
echo "<br>";

?>

<style>
* {
  box-sizing: border-box;
}


/* Create columns that float next to each other */
.abilities {
  float: left;
  width: 95%;
  padding: 0px;
  margin: 2%;
  font-size: 14pt;
}

/* Create columns that float next to each other */
.columnone {
  float: left;
  width: 5%;
  padding: 0px;
  margin: 2%;
}

.columntwo {
  float: left;
  width: 87%;
  padding: 0px;
  margin: 2%;
}


/* Clear floats after the columns */
.rowheader {
  background-color: #FF3333;
  color: #EEEEEE;
  margin: 0;
  padding: 10px;
  font-weight: bold;
  font-size: 14pt;
}

.row {
  padding: 0px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>


<div class="row">
	<div class="abilities" style="background-color:#FFBBBB">
		<div class="rowheader">
			Review "special quests" submissions below
		</div>
	</div>
</div>

<?php
$files=array_diff(scandir("pq_answerfiles"), array('.','..')); 

if(sizeof($files)>0) {

foreach ($files as $file) {

	$tid = substr($file,10,strpos($file,"-")-10);
	$quest = substr($file,strpos($file,"-")+6,strpos($file,".")-strpos($file,"-")-6);
	list($width, $height, $ftype, $attr) = getimagesize("pq_answerfiles/".$file);
	while($height>500 && $width>500) {
		$height=$height/2;
		$width=$width/2;
	}

	echo "<div class=\"row\">\n<div class=\"abilities\" style=\"background-color:#DDDDDD\">\n<div class=\"columnone\" style=\"line-height: 2\">";
		echo "<form method=\"POST\">\n";
                printf("<input type=\"hidden\" id=\"Team\" name=\"Team\" value=\"%d\">\n",$tid);
		printf("<input type=\"hidden\" id=\"Accept\" name=\"Accept\" value=\"%d\">\n",$quest);
		printf("<input type=\"hidden\" id=\"File\" name=\"File\" value=\"%s\">\n",$file);
		echo "<button class=\"btn btn-info\">Accept</button>\n";
		echo "</form><br>\n";
		echo "<form method=\"POST\">\n";
                printf("<input type=\"hidden\" id=\"Team\" name=\"Team\" value=\"%d\">\n",$tid);
                printf("<input type=\"hidden\" id=\"Reject\" name=\"Reject\" value=\"%d\">\n",$quest);
		printf("<input type=\"hidden\" id=\"File\" name=\"File\" value=\"%s\">\n",$file);
		echo "<button class=\"btn btn-danger\">Reject</button>\n";
                echo "</form><br>\n";
	echo "</div>\n";
	echo "<div class=\"columntwo\" style=\"line-height: 1\">";
	printf("<p>Quest #%d, Team #%d\n",$quest,$tid);
	printf("<p><a href=\"pq_answerfiles/%s\"><img height=\"$height\" width=\"$width\" src=\"pq_answerfiles/%s\"></a>\n",$file,$file);
	echo "</div>\n</div>\n</div>";
}
} else {
	printf("No files to review!<br>");
}

?>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

