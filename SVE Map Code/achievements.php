<?php include("db.php");?>  <!-- Login Session and database functions -->

<?php 
list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam)=getachievements($link);
?>
<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="formatting_code/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="formatting_code/css/BRCMapCSS.css" />
<link rel="stylesheet" type="text/css" href="formatting_code/css/DropdownCSS.css" />
<link rel="stylesheet" type="text/css" href="formatting_code/css/MenuCSS.css" />
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 


  <style>
<<<<<<< HEAD
  .collapsible {
      background-color: hsla(0, 0%, 100%, 0.5);
      text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
      color: yellow;
      cursor: pointer;
      padding: 10px;
      width: 100%;
=======
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
  .collapsible {
      background-color: hsla(0, 100%, 0%, 0.8);
      font-weight: bolder;
      text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
      color: green;
      cursor: pointer;
      padding: 10px;
<<<<<<< HEAD
>>>>>>> origin/devMap
=======
>>>>>>> origin/devMap
      border: none;
      text-align: center;
      outline: none;
      font-size: 15px;
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> origin/devMap
      display: block;
      width: 50%;
      margin: 0 auto;
      border-style: hidden hidden groove hidden;
      font-size: 150%;
      letter-spacing: 3px;
      font-family:courier,arial,helvetica;
      
<<<<<<< HEAD
>>>>>>> origin/devMap
=======
>>>>>>> origin/devMap
    }

    .active, .collapsible:hover {
      background-color: #0000FF;
<<<<<<< HEAD
<<<<<<< HEAD
=======
      
>>>>>>> origin/devMap
=======
      
>>>>>>> origin/devMap
    }
    
    .content_window {
      padding: 0 18px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
      /*background: rgba(255, 255, 255, 0.3) */
<<<<<<< HEAD
<<<<<<< HEAD
      background-color: hsla(0, 0%, 100%, 0.3);
      text-align: center;
      text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
=======
=======
>>>>>>> origin/devMap
      background-color: hsla(0, 100%, 0%);
      font-weight: bolder;
      text-align: center;
      text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
      width: 50%;
      margin-left: auto;
      margin-right: auto;
      color: blue;
      font-size: 150%;
      letter-spacing: 2px;
      font-family:courier,arial,helvetica;
      border-bottom-right-radius: 20px;
      border-bottom-left-radius: 20px;
<<<<<<< HEAD
>>>>>>> origin/devMap
=======
>>>>>>> origin/devMap
    }
      
  /*<!-- define general style elements -->*/
    
    /*<!-- define style elemtns for Sub table -->*/
    table.Sub{
        margin-left:auto; 
        margin-right:auto;
        border: 5px solid black;
        font-weight: bolder;
        background-image: radial-gradient(black , white, 0.5);
        color: black;
        text-align: center;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
<<<<<<< HEAD
<<<<<<< HEAD
=======
        font-family:courier,arial,helvetica;
>>>>>>> origin/devMap
=======
        font-family:courier,arial,helvetica;
>>>>>>> origin/devMap
    }
    th.Sub{
        /*background-image: linear-gradient(to right, red , yellow);*/
        padding: 10px;
        font-weight: bolder;
        text-align: center;
    }
    td.Sub{
        padding: 10px;
        font-weight: bolder;
        text-align: center;
    }

  </style>
</head>

<body>
<div class=content>


 <style> body {background-image: url("images/playaBackground.jpg");} </style>

<?php include("menubar.php");?>  <!-- Common top menu bar -->
<?php 


if(isset($achname)) {
    
foreach($achname as $aid => $aname) {
    echo "<button class=\"collapsible\">",$aname,"</button>";
    echo "<div class=\"content_window\">";
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
<<<<<<< HEAD
<<<<<<< HEAD
		    echo   "<th class=\"Sub\" background=\"/flags/flag",$atid,".png\"> ",$ateam,"</th>";
		  //  echo " <image xlink:href=\"flags/flag",$tid,".png\" width=\"".$fw."\" height=\"".$fh."\"/>\n";
		  //  echo   "<td class=\"Sub\"> Team number = ",$achflag[$aid][$atid],"</td>";
	    }
	    echo "</tr>";
=======
=======
>>>>>>> origin/devMap
		    echo   "<th class=\"Sub\" background=\"flags/flag",$atid,".png\"> ",$ateam,"</th>";
		  //  echo " <image xlink:href=\"flags/flag",$tid,".png\" width=\"".$fw."\" height=\"".$fh."\"/>\n";
		  //  echo   "<td class=\"Sub\"> Team number = ",$achflag[$aid][$atid],"</td>";
	    }
	    echo "<div id=\"star-five\"></div>";
	    echo "</tr>";
	    
<<<<<<< HEAD
>>>>>>> origin/devMap
=======
>>>>>>> origin/devMap
	    echo "</table>";
	}
}

}
?>

<script type="text/javascript">
<<<<<<< HEAD
<<<<<<< HEAD
// pass PHP variable declared above to JavaScript variable
=======
>>>>>>> origin/devMap
=======
>>>>>>> origin/devMap
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

<<<<<<< HEAD
=======
</body>
>>>>>>> origin/devMap
<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
