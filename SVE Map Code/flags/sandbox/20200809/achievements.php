<html>

<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php 
list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam)=getachievements($link);
?>

<head>
  <style>
  .collapsible {
      background-color: hsla(0, 0%, 100%, 0.5);
      text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
      color: yellow;
      cursor: pointer;
      padding: 10px;
      width: 100%;
      border: none;
      text-align: center;
      outline: none;
      font-size: 15px;
    }

    .active, .collapsible:hover {
      background-color: #0000FF;
    }
    
    .content {
      padding: 0 18px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
      /*background: rgba(255, 255, 255, 0.3) */
      background-color: hsla(0, 0%, 100%, 0.3);
      text-align: center;
      text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
    }
      
  /*<!-- define general style elements -->*/
    
    /*<!-- define style elemtns for Sub table -->*/
    table.Sub{
        margin-left:auto; 
        margin-right:auto;
        border: 5px solid black;
        background-image: radial-gradient(black , white, 0.5);
        color: black;
        text-align: center;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
    }
    th.Sub{
        /*background-image: linear-gradient(to right, red , yellow);*/
        padding: 10px;
        text-align: center;
    }
    td.Sub{
        padding: 10px;
        text-align: center;
    }

  </style>
</head>

<body>

<?php 


if(isset($achname)) {
    
foreach($achname as $aid => $aname) {
    echo "<button class=\"collapsible\">",$aname,"</button>";
    echo "<div class=\"content\">";
        echo "<p> ",$achfluff[$aid], " </p>";
        echo "<p> ",$achdescription[$aid]," </p>";
        echo "<p> Impact = ",$achimpact[$aid]," </p>";
        echo "<p> Max number = ",$achmaxnum[$aid]," </p>";

    echo "</div>";

	if(isset($achteam[$aid])) {
	    echo "<table class=\"Sub\">";
	    echo "<tr class=\"Sub\">";
	    foreach($achteam[$aid] as $atid => $ateam) {
		  //  echo   "<th class=\"Sub\"> ",$ateam,"</th>";
		    echo   "<th class=\"Sub\" background=\"/flags/flag",$atid,".png\"> ",$ateam,"</th>";
		  //  echo " <image xlink:href=\"flags/flag",$tid,".png\" width=\"".$fw."\" height=\"".$fh."\"/>\n";
		  //  echo   "<td class=\"Sub\"> Team number = ",$achflag[$aid][$atid],"</td>";
	    }
	    echo "</tr>";
	    echo "</table>";
	}
}

}
?>

<script type="text/javascript">
// pass PHP variable declared above to JavaScript variable
// var achname = <?php echo json_encode($achname) ?>;

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}

</script>




</body>
<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
