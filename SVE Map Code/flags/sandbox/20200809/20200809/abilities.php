<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php

if(!isset($teamid)) $teamid = 0;

if(isset($teamid)){
	list($abilname,$abilfluff,$abildesc,$teamabil)=getabilities($link, $teamid);
	foreach($teamabil as $on){
		if($on)$numteamabil++;
	}
}

$abilitychecked = $_POST['ability'];
if(isset($abilitychecked) && $teamid>0 && $abilitychecked>0 && $abilitychecked<=8 && !$teamabil[$abilitychecked]){
        if ($action_points>0) {
		$sql = "INSERT INTO abilityactive (teamid, abilityid) VALUES (".$teamid.",".$abilitychecked.")";
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
		$sql = "UPDATE teams SET action_points = ".(--$action_points)." WHERE teamid = ".$teamid;
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
		mysqli_close($link);

		header("Location: BRCMap_index.php");
	} else {
                error("You need action points to gain an ability.  Go solve a puzzle or complete a quest!  They're fun.");
        }
}

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
<?php if($teamid==0) echo "<a href=\"login.php\">Login</a> to "; ?>
			Choose an Ability Below
		</div>
	</div>
</div>

<?php

foreach ($abilname as $aid => $aname) {

	echo "<div classs=\"row\">\n<div class=\"abilities\" style=\"background-color:#FFDDDD\">\n<div class=\"columnone\" style=\"line-height: 2\">";
	if($teamid>0) if($teamabil[$aid]) {
		echo "<img width=\"64\" height=\"63\" src=\"images/checkmark.png\">";
	} else {
		echo "<form method=\"POST\">\n";
		printf("<input type=\"hidden\" id=\"%d\" name=\"ability\" value=\"%d\">\n",$aid,$aid);
		echo "<button class=\"btn btn-info\">Select</button>\n";
		echo "</form>\n";
	}
	echo "</div>\n";
	echo "<div class=\"columntwo\" style=\"line-height: 1\">";
	printf("<p><b>Ability: </b> %s\n",$aname);
	printf("<p>%s\n",$abildesc[$aid]);
	echo "</div>\n</div>\n</div>";
}

?>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

