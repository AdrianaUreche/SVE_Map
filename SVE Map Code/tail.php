</div>

<div class="footer">
	<ul class="customStyle">
		<li class="customStyle"><a href="contact.php">Contact Us</a></li>
<?php 
if(!isset($teamid)) {
	echo "<li class=\"customStyle\"><a href=\"register.php\">Registration</a></li>"; 
} else {
	echo "<li class=\"customStyle\"><a href=\"logout.php\">Logout</a></li>";
}
?>
			</ul>
</div> 

<?php
if (isset($achtext)) echo "<script>alert(\"",$achtext,"\");</script>";
if (isset($alert)) echo "<script>alert(\"",$alert,"\");</script>";

mysqli_close($link);
?>

</body>
</html>
