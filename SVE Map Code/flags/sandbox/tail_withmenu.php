<a href="puzzle_template_manual.php" class="button1"> Puzzle Template MANUAL</a>
<a href="puzzle_template_png.php" class="button1"> Puzzle Template PNG</a>
<a href="puzzle_template_pdf.php" class="button1"> Puzzle Template PDF</a>
<a href="puzzle_flowers.php" class="button1"> Puzzle LIZ</a>



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
