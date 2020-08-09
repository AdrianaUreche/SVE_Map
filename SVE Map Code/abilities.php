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
  top: 30%;
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
    <h1>Descriptions of Abilities</h1>
    <hr>
    	<ol>
  			<li><b>Occupy Adjacent Blocks.</b> Draw your team’s pattern on a number of unoccupied blocks, equal to your team’s impact factor, adjacent to blocks your team already occupies.  If your impact factor is greater than the number of available blocks, occupy only those available blocks.</li>
  			<li><b>Occupy Non-Adjacent Blocks.</b> Occupy a block not adjacent to one of your team’s occupied blocks, in which the number of blocks between the new block and a currently-occupied block is no more than your impact factor.  Occupy a number of blocks adjacent to this new block equal to your impact factor minus the number of blocks jumped.  If not enough such blocks are available to occupy, occupy only those blocks available.</li>
  			<li><b>Flip blocks.</b>  Flip a number of blocks equal to your impact factor divided by two (round up) that do not have a black “X” on them which are, or are adjacent to any of your team’s occupied blocks. If the underside (before flipping) has no pattern drawn on it, draw your own team’s pattern.</li>
        <li><b>Occupy The Temple,</b> but only if you have assembled the Ultimate Weapon and The Temple does not have a black “X” drawn on it.  If the Temple already has a pattern on it, flip it.  If the underside (before flipping) is blank, draw your team’s pattern on it.</li>
        <li><b>Plaza teleport.</b>  Occupy all unoccupied blocks adjacent to a single Plaza that is adjacent to one of your occupied blocks.  Also occupy one unoccupied block adjacent to another plaza.</li>
        <li><b>Permify blocks.</b>  Draw a black “X” on any number of blocks less than your impact factor.</li>
        <li><b>Destroy a block.</b>  Purely for spite.  Serious, orbital, gigawatt-laser bombardment action here.  Remove it from the game.  Take it home.  Burn it.  Up to you.</li>
        <li><b>Increase Impact Factor.</b>  Add two to your impact factor.</li>
		</ol> 
    </div>
  </div>
  




</body>
</html>



<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

