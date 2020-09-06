<?php include("db_test.php");?>  <!-- Login Session and database functions -->

<?php
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
	// REMOVE_ME - Initialize some more variables that should end up in being initialized in db.php
	$flagfile = "flags/flag".$teamid.".png"; // placeholder, assuming the db field is a url and not a blob
?>

<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php

	function getachievementsforteam($link, $teamid)
	{
		// SELECT name, difficulty, status
		// FROM `questsolved`, quests
		// WHERE (quests.qid = questsolved.qid) AND (questsolved.teamid = 5)

		$sql = "SELECT name, impact FROM achieved, achievements WHERE (achievements.achid = achieved.achid) AND (achieved.teamid = '$teamid')";

		$num_results = 0;
		$returnachievements = null;
		if($result = mysqli_query($link, $sql))
		{
			if(mysqli_num_rows($result) > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$returnachievements[$num_results]['name'] = $row['name']; 
					$returnachievements[$num_results]['impact'] = $row['impact']; 
					$num_results++;
				}
			}
			// Free result set
			mysqli_free_result($result);
		}
		return $returnachievements;
	}

	function prettyprintline($id, $name, $difficulty, $status, $url, $type, $askconfirmation)
	{
		$diftypes = array("impossible", "easy", "medium", "hard");
		$statypes = array("unknown", "started", "done", "abandoned", "unknown", "submitted", "accepted", "rejected");
		printf("<div class='puzzlename'><div style='text-align: left; width:70%%; display:inline-block'><a href=\"".$url."\">%s</a></div>", $name);
		$dif_icon = "images/".$diftypes[$difficulty & 3].".png";
		$stat_icon = "images/".$statypes[$status].".png";
//		$statypes[1] .= "\n(click to abandon)";
//		switch ($difficulty) {
//			case 1:
//				$dif_icon = "images/easy.png";
//				break;
//			case 2:
//				$dif_icon = "images/medium.png";
//				break;
//			default:
//				$dif_icon = "images/hard.png";
//				break;
//		}
//
//		switch ($status%12) {
//			case 1:
//				$stat_icon = "images/started.png";
//				break;
//			case 2:
//				$stat_icon = "images/done.png";
//				break;
//			case 3:
//				$stat_icon = "images/abandoned.png";
//				break;
//			default:
//				$stat_icon = "images/unknown.png";
//				break;
//		}

		// reverse the order in which we print these, to keep alignment correct
//		if ($type == 'puzzle')
//		{
		$hw = 40;
		if ($status==1)
		{
			$onclickstring="onclick='return confirm(\"This action will abandon this ".$type." and allow you to work on another ".$type.".  You CANNOT return to this ".$type." once you have abandoned it.\\n\\nAre you sure you want to do this?\");'";
			$enda = "</a>";
			printf("<a href='abandon.php?id=%s&type=%s' %s><img alt='Abandon' title='Abandon' src='images/abandon.png' height=20 width=20 align=right></a>",$id,$type,$onclickstring);
			$hw=20;
		}
		printf("<img alt='%s' title='%s' src='%s' height=%d width=%d align=right>%s", $statypes[$status], $statypes[$status], $stat_icon, $hw, $hw, $enda);
//	}
//		else
//		{
//			printf("<a href='toggle_quest_status.php?id=%s&from=%s'><img src='%s' height=40 width=40 align=right></a>", $id, $status, $stat_icon);
//		}	
		printf("<img src='%s' alt='%s' title='%s' height=40 width=40 align=right>", $dif_icon, $diftypes[$difficulty&3], $diftypes[$difficulty&3]);
		if($status==1) {
			printf("<form enctype=\"multipart/form-data\" action=\"pq_submit.php\" method=\"post\">\n");
			printf("<input type=\"hidden\" name=\"type\" id=\"type\" value=\"%s\">",$type);
			printf("<input type=\"hidden\" name=\"id\" id=\"id\" value=\"%s\">",$id);
			if (4&$difficulty) { 
				printf("<label for=\"answerfile\"><u>Upload proof:</u></label><br>\n<input type=\"file\" name=\"answerfile\" id=\"answerfile\"><button class=\"btn btn-info\">Upload Image</button>\n");
			} else {
				printf("<label for=\"answer\"><u>Your answer:</u></label><br>\n<input maxlength=\"256\" size=\"20\" type=\"text\" id=\"answer\" name=\"answer\"><br>\n");
			}
			printf("</form>\n");
		}

		printf("</div>");
	}

	function prettyprintachievements($name, $impact)
	{
		printf("<div class='puzzlename'><div style='text-align: left; width:94%%; display:inline-block'>%s</div>", $name);
		printf("<b><div style='text-align: right; display:inline'>%s</div></b>", $impact);
		printf("</div>");
	}
?>


<style>
* {
  box-sizing: border-box;
}


/* Create three equal columns that floats next to each other */
.teaminfo {
  float: left;
  width: 96%;
  padding: 0px;
  margin: 2%;
  font-size: 18pt;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  min-width: 300px;
  width: 31.333%;
  padding: 0px;
  margin: 1%;
}

/* Clear floats after the columns */
.rowheader {
  background-color: #333333;
  color: #EEEEEE;
  margin: 0;
  padding: 10px;
  font-weight: bold;
  font-size: 18pt;
}

.row {
  padding: 0px;
  margin: auto;
  width: 96%;
}

.puzzlename {
  padding-left: 2%;
  width: 95%;
  font-weight: bold;
  font-size: 14pt;
  line-height: 2;
}

.puzzleicon {
	float: left;
	padding: 10px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>



<!-- Team Info Block -->
<div class="row">
	<div class="teaminfo" style="background-color:#EEEEEE">
		<div class="rowheader">
			Team Info
		</div>
		<div class="column" style="line-height: 2">
			<?php printf("<b>Name:</b> %s<br>", $teamname);  ?>
			<?php printf("<b>Players:</b> %s<br>", nl2br($player_names)); ?>
			<?php printf("<b>email:</b> %s<br>", $email); ?>
		</div>	
		<div class="column" style="line-height: 2">
			<?php printf("<b>Impact Factor:</b> %s<br>", $impact);  ?>
			<?php printf("<b>Action Points:</b> %s", $action_points); 
			 if($action_points>0) printf("&nbsp;&nbsp;(<font color=\"Red\"><a href=\"BRCMap_index.php\">Use!</a></font>)");
			 printf("<br>");  ?>
			<?php printf("<b>Points used:</b> %s<br>", $action_points_used); ?>
		</div>	
		<div class="column">
			<?php printf("<b>Flag</b>: <img src=\"%s\" width=32px height=32px><img src=\"%s\" width=32px height=32px><img src=\"%s\" width=32px height=32px>", $flagfile,$flagfile,$flagfile); ?>
		</div>
	</div>
</div>

<div class="row">
  <!-- Puzzles -->
  <div class="column" style="background-color:#EEEEEE;">
	<div class="rowheader">
		Puzzles
	</div>
	<?php
                $r = $tot = 0;
		if($result = mysqli_query($link, "SELECT released FROM puzzles"))$tot = mysqli_num_rows($result);
	        if($result = mysqli_query($link, "SELECT released FROM puzzles WHERE released >= 1"))$r = mysqli_num_rows($result);
		$active_puzzles = getpuzzlesforteam($link, $teamid);
		if ($active_puzzles)
		{
			$askconfirmation = false;
			foreach ($active_puzzles as $active_puzzle) 
			{
				// Guard active puzzles 
				if (($active_puzzle['status']) == 1)
					$askconfirmation = true;
				if($active_puzzle['released']) 
					prettyprintline($active_puzzle['id'],  $active_puzzle['name'],  $active_puzzle['difficulty'],  $active_puzzle['status'], $active_puzzle['url'], 'puzzle', $askconfirmation);
				
			} 
			printf("<div class='puzzlename' style=\"font-size:10pt\">(%d out of %d %ss have been released).</div>",$r,$tot,"puzzle");

		} else {
			printf("<div class='puzzlename'>No puzzles yet!</div>");
		}
	
	?>
	&nbsp
  </div>
  <!-- Quests -->
  <div class="column" style="background-color:#EEEEEE;">
	<div class="rowheader">
		Quests
	</div>
	<?php
		$r = $tot = 0;
		if($result = mysqli_query($link, "SELECT released FROM quests"))$tot = mysqli_num_rows($result);
		if($result = mysqli_query($link, "SELECT released FROM quests WHERE released >= 1"))$r = mysqli_num_rows($result);
		$active_quests = getquestsforteam($link, $teamid);
		if ($active_quests)
		{
			$num_ongoing_quests = 0;
			foreach ($active_quests as $active_quest) 
			{
				if ($active_quests['status']&3 == 1)
					$num_ongoing_quests++;
				if($active_quest['released']) 
					prettyprintline($active_quest['id'], $active_quest['name'], $active_quest['difficulty'],  $active_quest['status'], $active_quest['url'], 'quest', false);
			} 
			printf("<div class='puzzlename' style=\"font-size:10pt\">(%d out of %d %ss have been released).</div>",$r,$tot,"quest");

		} else {
			printf("<div class='puzzlename'>No quests yet!</div>");
		}
	?>
	&nbsp
  </div>
  <!-- Achievements -->
  <div class="column" style="background-color:#EEEEEE;">
	<div class="rowheader">
		Achievements
	</div>
	<?php
		$active_achievements = getachievementsforteam($link, $teamid);
		if ($active_achievements)
		{
			foreach ($active_achievements as $active_achievement) 
			{
			 	prettyprintachievements($active_achievement['name'],  $active_achievement['impact']);
			} 
		}
		else
			printf("<div class='puzzlename'>No achievements yet!</div>");
	?>
	&nbsp
  </div>
</div>


<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

