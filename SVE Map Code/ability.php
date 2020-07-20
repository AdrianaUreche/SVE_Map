<?php
$ability = $_POST['ability'];
$blocks = json_decode($_POST['blocksclicked'], true);
echo "Ability #",$ability," clicked.<p>Blocks clicked:\n";
echo "<p><ul>";
foreach($blocks as $blockid => $value) {
	echo "<li>",$value;
};
echo "</ul>\n";
?>
