<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php
$sql = "SELECT qid, name FROM quests";

if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$qid = $row['qid'];
			$qname[$qid] = $row['name'];
		}
	}
	// Free result set
	mysqli_free_result($result);
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
			Review "special quests" submissions below
		</div>
	</div>
</div>

<?php
$files=array_diff(scandir("pq_perm_answerfiles"), array('.','..')); 

if(sizeof($files)>0) {

foreach ($files as $file) {

	$tid = substr($file,10,strpos($file,"-")-10);
	$quest = substr($file,strpos($file,"-")+6,strpos($file,".")-strpos($file,"-")-6);
	list($width, $height, $ftype, $attr) = getimagesize("pq_perm_answerfiles/".$file);

	while($height>500 && $width>500) {
		$height=$height/2;
		$width=$width/2;
	}

	echo "<div class=\"row\">\n<div class=\"abilities\" style=\"background-color:#DDDDDD\">\n<div class=\"columnone\" style=\"line-height: 2\">";
	echo "</div>\n";
	echo "<div class=\"columntwo\" style=\"line-height: 1\">";
	printf("<p>Quest #%d, Team #%d\n",$quest,$tid);
	printf("<p><a href=\"pq_perm_answerfiles/%s\"><img height=\"$height\" width=\"$width\" src=\"pq_perm_answerfiles/%s\"></a>\n",$file,$file);
	echo "</div>\n</div>\n</div>";
}
} else {
	printf("No files to review!<br>");
}

?>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

