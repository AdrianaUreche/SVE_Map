<html>

<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php 
list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);
?>

<head>
  <style>
  p {
      border: 2px solid white;
      color: white;
      text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
      text-align: center;
      margin-bottom: 0;
      width: 50%;
      margin-left: auto;
      margin-right: auto;
      border-radius: 25px;
      font-size: 25px;
  }
    
  .board {
      background-color: hsla(0, 100%, 0%, 0.6);
      width: 75%;
      margin-left: auto;
      margin-right: auto;
      margin-top: 25px;
  }
  </style>
</head>

<body>

<?php 


if(isset($blocknextid)) {
    $lead = array_count_values($blocknextid);
    arsort($lead);
    // $test = implode(",", $lead);
    // echo "<p> ", $test, "</p>";
    
    echo "<div class=\"board\">";
    $iter = 1;
    foreach($lead as $tid => $tscore) {
        echo "<p style= \"background: url(/flags/flag",$tid,".png)\" > ",$iter, ": ", $teamids[$tid]," - ", $tscore, "</p>";
    
        $iter++;
    }
    echo "</div>";
 }
?>

<script type="text/javascript">

</script>




</body>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
