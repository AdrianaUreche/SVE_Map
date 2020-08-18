<a href="library_pq.php" class="button1"> Library of Puzzles and Quests for dev</a>\

	<ul class="customStyle">
		<li class="customStyle"><a href="contact.php">Contact Us</a></li>
		<li class="customStyle"><a href="register.php">Registration</a></li>
		<li class="customStyle"><a href="logout.php">Logout</a></li>
	</ul>

<?php
if (isset($achtext)) echo "<script>alert(\"",$achtext,"\");</script>";
if (isset($alert)) echo "<script>alert(\"",$alert,"\");</script>";

mysqli_close($link);
?>

</body>
</html>
