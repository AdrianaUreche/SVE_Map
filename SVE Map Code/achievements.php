<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam, $achflag)=getachievements($link); 


echo "<ul>";
foreach($achname as $aid => $aname) {
	echo "<li>",$aname,"</li>\n";
	echo "<ol><li>",$achfluff[$aid],"</li>\n";
	echo "<li>",$achdescription[$aid],"</li>\n";
	echo "<li> Impact = ",$achimpact[$aid],"</li>\n";
	echo "<li> Max number = ",$achmaxnum[$aid],"</li>\n<ol>\n";
	foreach($achteam[$aid] as $atid => $ateam) {
		echo "<li> ",$ateam,"</li>\n";
		echo "<li> Team number = ",$achflag[$aid][$atid],"</li>\n";
	}
	echo "</ol></ol>";
}
echo "</ul>";

?>


<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

