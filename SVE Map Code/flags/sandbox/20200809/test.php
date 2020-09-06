<?php
// Initialize the session
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

// Define variables and initialize with empty values
$teamname = htmlspecialchars($_SESSION["teamname"]);
}

// Include config file
require_once "config.php";

$sql = "SELECT teamid, player_names FROM teams WHERE teamname = '$teamname'";

if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_array($result);
		$teamid = $row['teamid'];
		$player_names = $row['player_names'];
	}
	// Free result set
	mysqli_free_result($result);
}

?>

<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php 
function getblocks($link) {
	
	$sql = "SELECT blockid, time, letter FROM blocks";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
                                $blockname[$row['blockid']] = $row['time']."&".$row['letter'];
                        }
                }
                // Free result set
                mysqli_free_result($result);
        }
        return $blockname;
}

$bname=getblocks($link);
echo "Block: ".$bname[1];
?>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

