<html>

<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<head>
  <style>
  <!-- define general style elements -->
    table, th, td {
        float: left;
        text-align: center;
    }
    th, td {
        padding: 10px;
        border: 1px solid black;
    }
    table {
        border: 5px outset blue;
    }
    th {
        border-bottom: 5px inset blue;
        
    }
    td {
        
    }
    
    <!-- define style elemtns for Sub table -->
    .Sub table, th, td {

    }
    .Sub th, td {

    }
    .Sub table {

    }
    .Sub th {
        border-right: 5px inset blue;
    }
    .Sub td {
        
    }
  </style>
</head>

<body>
<?php list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam, $achflag)=getachievements($link); 

if(isset($achname)) {

foreach($achname as $aid => $aname) {
    echo "<table class=\"Main\">";
    echo   "<tr>";
	echo     "<th>",$aname,"</th>";
	echo   "</tr>";
	echo   "<tr>";
	echo     "<td>",$achfluff[$aid],"</td";
	echo   "</tr>";
	echo   "<tr>";
	echo     "<td>",$achdescription[$aid],"</td>";
	echo   "</tr>";
	echo   "<tr>";
	echo     "<td> Impact = ",$achimpact[$aid],"</td>";
	echo   "</tr>";
	echo   "<tr>";
	echo     "<td> Max number = ",$achmaxnum[$aid],"</td>";
	echo   "</tr>";
	echo "</table>";
	if(isset($achteam[$aid])) {
	    echo "<table class=\"Sub\">";
	    foreach($achteam[$aid] as $atid => $ateam) {
	        echo "<tr>";
		    echo   "<th> ",$ateam,"</th>";
		    echo   "<td> Team number = ",$achflag[$aid][$atid],"</td>";
		    echo "</tr>";
	    }
	    echo "</table>";
	}

}

}
?>

</body>
<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

</html>
