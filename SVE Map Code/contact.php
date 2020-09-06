<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->


<html>
<style>
body, html {
  height: 100%;
  margin: 0;
}


.transbox {
  width: 600px;
  padding: 50px;
  margin: 20px;
  background-color: #ffffff;
  border: 1px solid black;
  opacity: 0.6;
}

.transbox p {
  margin: 5%;
  font-weight: bold;
  color: #000000;
}



.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  margin: 5%;
  transform: translate(-50%, -50%);
  text-align: justify;
  text-justify: inter-word;
}

hr {
  margin: auto;
  width: 60%;
}
</style>
<body>



  <div class="middle">
    <div class="transbox">
		<h2 id="id1"> Contact us at: </h2>
		<p id='demo'>
			<ul> 
				<li> <i> Primary Help Email</i> : scientistsvseveryone@agnostica.com </li>
<br>
				<li> <i> Darren Bleuel </i> : darren@nukees.com </li>
				<li> <i> Adriana Sweet </i> : adriana@teews.com </li>
				<li> <i> Eric Sweet </i> :    eric@teews.com </li>
				<li> <i> Freya Wolles </i> :  freya.wolles@gmail.com </li>
                                <li> <i> <a style="color:black" href="http://zoom.us/j/92980350437">Office hours via Zoom each evening from 18:00-20:00 PDT</a></i></li>

			</ul>
		</p>
    </div>
  </div>
  




</body>
</html>



<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

