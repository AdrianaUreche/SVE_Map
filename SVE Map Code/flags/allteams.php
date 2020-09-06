<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php 
list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);
$sql = "SELECT teamid, teamname, player_names, email, ham FROM teams";

if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
				$teamid = $row['teamid'];
				list($teamid, $teamnames[$teamid], $players[$teamid], $teamemail[$teamid], $ham[$teamid]) = $row;
			}
		}
}
// Free result set
mysqli_free_result($result);
?>

  <style>
    .star1{
        float right;
    }
    .star2{
        float left
    }
    #star-five {
      margin: auto;
      /*float: right;*/
      position: relative;
      display: block;
      color: yellow;
      width: 0px;
      height: 0px;
      border-right: 20px solid transparent;
      border-bottom: 14px solid yellow;
      border-left: 20px solid transparent;
      transform: rotate(35deg);
    }
    #star-five:before {
      border-bottom: 16px solid yellow;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      position: absolute;
      height: 0;
      width: 0;
      top: -9px;
      left: -13px;
      display: block;
      content: '';
      transform: rotate(-35deg);
    }
    #star-five:after {
      position: absolute;
      display: block;
      color: yellow;
      top: 3px;
      left: -21px;
      width: 0px;
      height: 0px;
      border-right: 20px solid transparent;
      border-bottom: 14px solid yellow;
      border-left: 20px solid transparent;
      transform: rotate(-70deg);
      content: '';
    }
    p {
      border: 4px solid black;
      color: black;
      text-shadow: -2px 0 white, 0 2px white, 2px 0 white, 0 -2px white;
      font-weight: bolder;
      text-align: center;
      margin-bottom: 0;
      border-radius: 25px;
      font-size: 25px;
      font-family:courier,arial,helvetica;
      /*display:inline-block;*/
      /*text-align: center;*/
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }
    
    .board {
      background-color: hsla(0, 100%, 0%, 0.8);
      width: 50%;
      margin-left: auto;
      margin-right: auto;
      margin-top: 25px;
    }
  </style>

<?php 


    // $test = implode(",", $lead);
    // echo "<p> ", $test, "</p>";
    
    echo "<div class=\"board\">";
    $iter = 1;
    foreach($teamnames as $tid => $tname) {
        if($tid>0){
            echo "<p style= \"background: url(flags/flag",$tid,".png)\" > ",$tid, ": ", $tname,"</p>";
            $iter++;
        }
    }
    echo "</div>";
?>

<script type="text/javascript">

</script>



<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
