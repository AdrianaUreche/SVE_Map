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
  width: 1300px;
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
  top: 25%;
  left: 45%;
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
    <h1>How to Play the Game</h1>
    <hr>
    	<ol>
  			<li>Anything “removable” on the game board is a “block” that may be occupied by a team, except for Center Camp (which is the reward for winning the game).</li>
  			<li>To “occupy” a block, a team draws their pattern on it, a.k.a “flag,” “tartan...” </li>
  			<li>The winner of the game is the team with the highest score, calculated as the sum of the points for each occupied block according to the following point schedule:</li>
  				<ul>
  					<li>Each single-sized block: 1 point</li>
  					<li>Each double-sized block: 2 points</li>
  					<li>The Man: 10 points</li>
				</ul>  
			<li>Completing a puzzle or quest allows a team to <b>either</b> gain an ability (see “Abilities”) or activate an existing ability once.</li>
			<li>The Man and The Temple are adjacent to each other, but <b>not</b> adjacent to any other block on the map.  The Man has the equivalent of <u>five</u> blocks between it and any Esplanade block.</li>
			<li>First team to occupy The Man or The Temple gets to keep the original board piece as a prize.  Replace with unmarked replacement blocks.</li>

		</ol> 
    </div>
  </div>
  




</body>
</html>



<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

