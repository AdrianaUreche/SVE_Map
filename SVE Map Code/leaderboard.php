<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php 
list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);
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
      border: 2px solid white;
      color: white;
      text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
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


if(isset($blockownid)) {
    $lead = array_count_values($blockownid);
    arsort($lead);
    // $test = implode(",", $lead);
    // echo "<p> ", $test, "</p>";
    
    echo "<div class=\"board\">";
    $iter = 1;
    foreach($lead as $tid => $tscore) {
        if($tid!=0){
            if($iter==1){
                echo "<div id=\"star-five\" class=\"star1\"></div>";
            }
            echo "<p style= \"background: url(flags/flag",$tid,".png)\" > ",$iter, ": ", $teamids[$tid]," - ", $tscore, "</p>";
            if($iter==1){
                echo "<div id=\"star-five\" class=\"star2\"></div>";
                echo "<br>";
            }
            $iter++;
        }
    }
    if($iter==1) {
	    echo "<p style= \"background: url(flags/flag0.png)\" >No players yet!</p>\n";
    }
    echo "</div>";
 }
?>

<script type="text/javascript">

</script>




<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
