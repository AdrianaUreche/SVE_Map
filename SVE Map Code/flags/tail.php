</div>

<div class="footer">
	<ul class="customStyle">
		<li class="customStyle"><a href="contact.php">Contact Us</a></li>
                <li class="customStyle"><a href="MeetTheBurners.html">Meet The Burners!</a></li>
<?php 

if(!isset($teamid)) {
	echo "<li class=\"customStyle\"><a href=\"register.php\">Registration</a></li>"; 
} else {
	echo "<li class=\"customStyle\"><a href=\"logout.php\">Logout</a></li>";
}
?>
<style>
.button {
  border: none;
  color: white;
  Background-color: tomato;
  Height: 65px;
  Width: 31%;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  cursor: pointer;
  margin-right:2px;
  margin-left:2px;
  border-radius: 5px;
}
</style>
<li style="float: right;">
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v7.0" nonce="xpBUNARO"></script>
<div class="fb-like" style="margin-top: 10px; margin-left:4px;" data-href="http://sve.nukees.com/" data-width="200" data-layout="button" data-action="like" data-size="large" data-share="true"></div>
			</ul>
</div>

<?php
if (isset($achtext)) echo "<script>alert(",json_encode($achtext),");</script>";
if (isset($alert)) echo "<script>alert(",json_encode($alert),");</script>";

mysqli_close($link);
?>

</body>
</html>
