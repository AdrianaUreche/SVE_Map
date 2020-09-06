<?php
	// REMOVE_ME - Try to get some data out of the db, since I'm not really logging in here...
	$_SESSION["loggedin"]=true;
	$_SESSION["teamname"]="Red";
?>

<?php include("db.php");?>  <!-- Login Session and database functions -->


<?php
	// REMOVE_ME - Initialize some more variables that should end up in being initialized in db.php
	$flagfile = "https://i.redd.it/2cwvcnz95zl41.jpg"; // placeholder, assuming the db field is a url and not a blob
?>

<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php
	// Additional queries for puzzles and quests
	function getpuzzlesforteam($link, $teamid)
	{
		printf("<!-- getpuzzlesforteam(link, %s); -->", $teamid);
		// SELECT name, difficulty, status
		// FROM `puzzlesolved`, puzzles
		// WHERE (puzzles.pid = puzzlesolved.pid) AND (puzzlesolved.teamid = 5)

		$sql = "SELECT puzzles.pid, name, difficulty, status, 'http://www.google.com' as url FROM puzzlesolved, puzzles WHERE (puzzles.pid = puzzlesolved.pid) AND (puzzlesolved.teamid = '$teamid')";

		$num_results = 0;
		$returnpuzzles = null;
		if($result = mysqli_query($link, $sql))
		{
			if(mysqli_num_rows($result) > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$returnpuzzles[$num_results]['id'] = $row['pid']; 
					$returnpuzzles[$num_results]['name'] = $row['name']; 
					$returnpuzzles[$num_results]['difficulty'] = $row['difficulty']; 
					$returnpuzzles[$num_results]['status'] = $row['status']; 
					$returnpuzzles[$num_results]['url'] = $row['url']; 
					$num_results++;
				}
			}
			// Free result set
			mysqli_free_result($result);
		}
		return $returnpuzzles;
	}

	function getquestsforteam($link, $teamid)
	{
		printf("<!-- getquestsforteam(link, %s); -->", $teamid);
		// SELECT name, difficulty, status
		// FROM `questsolved`, quests
		// WHERE (quests.qid = questsolved.qid) AND (questsolved.teamid = 5)

		$sql = "SELECT quests.qid, name, difficulty, status, 'http://www.google.com' as url FROM questsolved, quests WHERE (quests.qid = questsolved.qid) AND (questsolved.teamid = '$teamid')";

		$num_results = 0;
		$returnquests = null;
		if($result = mysqli_query($link, $sql))
		{
			if(mysqli_num_rows($result) > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$returnquests[$num_results]['id'] = $row['qid']; 
					$returnquests[$num_results]['name'] = $row['name']; 
					$returnquests[$num_results]['difficulty'] = $row['difficulty']; 
					$returnquests[$num_results]['status'] = $row['status']; 
					$returnquests[$num_results]['url'] = $row['url']; 
					$num_results++;
				}
			}
			// Free result set
			mysqli_free_result($result);
		}
		return $returnquests;
	}

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
		printf("<div class='puzzlename'><div style='text-align: left; width:70%%; display:inline-block'>%s</div>", $name);
		switch ($difficulty) {
			case 1:
				$dif_icon = "images/easy.png";
				break;
			
			case 2:
				$dif_icon = "images/medium.png";
				break;
			default:
				$dif_icon = "images/hard.png";
				break;
		}

		switch ($status) {
			case 0:
				$stat_icon = "images/started.png";
				break;
			
			case 1:
				$stat_icon = "images/done.png";
				break;
			default:
				$stat_icon = "images/unknown.png";
				break;
		}

		// reverse the order in which we print these, to keep alignment correct
		if ($type == 'puzzle')
		{
			if ($askconfirmation)
			{
				$onclickstring="onclick='return confirm(\"You are currently working on another puzzle. Starting this one will forfeit the currently active one and you will not be able to reactivate it.\\n\\nAre you sure you want to do this?\");'";
			}
			else
			{
				$onclickstring="";
			}
			printf("<a href='toggle_puzzle_status.php?id=%s&from=%s' %s><img src='%s' height=40 width=40 align=right></a>", $id, $status, $onclickstring, $stat_icon);
		}
		else
		{
			printf("<a href='toggle_quest_status.php?id=%s&from=%s'><img src='%s' height=40 width=40 align=right></a>", $id, $status, $stat_icon);
		}	
		printf("<img src='%s' height=40 width=40 align=right>", $dif_icon);
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
  width: 95%;
  padding: 0px;
  margin: 2%;
  font-size: 18pt;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 29%;
  padding: 0px;
  margin: 2%;
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
}

.puzzlename {
  padding: 2%;
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
			<?php printf("<b>Players:</b> %s<br>", $player_names); ?>
			<?php printf("<b>email:</b> %s<br>", $email); ?>
		</div>	
		<div class="column" style="line-height: 2">
			<?php printf("<b>Impact Factor:</b> %s<br>", $impact);  ?>
			<?php printf("<b>Action Points:</b> %s<br>", $action_points); ?>
			<?php printf("<b>Points used:</b> %s<br>", $action_points_used); ?>
		</div>	
		<div class="column">
			<?php printf("<b>Flag</b>: <img src=\"%s\" height=150px>", $flagfile); ?>
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
		$active_puzzles = getpuzzlesforteam($link, $teamid);
		if ($active_puzzles)
		{
			$askconfirmation = false;
			foreach ($active_puzzles as $active_puzzle) 
			{
				// Guard active puzzles 
				if ($active_puzzle['status'] = '1')
					$askconfirmation = true;
			
			 	prettyprintline($active_puzzle['id'],  $active_puzzle['name'],  $active_puzzle['difficulty'],  $active_puzzle['status'], $active_puzzle['url'], 'puzzle', $askconfirmation);
			} 
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
		$active_quests = getquestsforteam($link, $teamid);
		if ($active_quests)
		{
			$num_ongoing_quests = 0;
			foreach ($active_quests as $active_quest) 
			{
				if ($active_quests['status'] = '1')
					$num_ongoing_quests++;
			 	prettyprintline($active_quest['id'], $active_quest['name'], $active_quest['difficulty'],  $active_quest['status'], $active_quest['url'], 'quest', false);
			} 
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
	?>
	&nbsp
  </div>
</div>


<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

