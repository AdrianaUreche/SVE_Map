<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<style>
	* 
	{
	  box-sizing: border-box;
	}


	/* Create three equal columns that floats next to each other */
	.teaminfo {
	  float: left;
	  width: 95%;
	  padding: 0px;
	  margin: 2%;
	  font-size: 18pt;
	}

	/* Create three equal columns that floats next to each other */
	.column {
	  float: left;
	  width: 29%;
	  padding: 0px;
	  margin: 2%;
	  font-size: 14pt;
	}

	/* Clear floats after the columns */
	.rowheader {
	  background-color: #333333;
	  color: #EEEEEE;
	  margin: 0;
	  padding: 10px;
	  font-weight: bold;
	  font-size: 18pt;
	}

	.row {
	  padding: 0px;
	}

	/* Clear floats after the columns */
	.row:after {
	  content: "";
	  display: table;
	  clear: both;
	}
</style>


<?php
		if(empty(trim($_GET['key'])))
	    {
?>
			<div class="row">
				<div class="column">
				</div>
				<div class="column">
					<div class="teaminfo">
						You need an activation key, please click or copy/paste the link you've received in your activation email.<br>
					</div>
				</div>
				<div class="column">
				</div>
			</div>
<?php
	    } 
	    else
	    {
	    	$key = trim($_GET['key']);
			$sql = "UPDATE teams SET activation_key = null WHERE activation_key = '" . $key ."'";

			mysqli_query($link, $sql);
?>
			<div class="row">
				<div class="column">
				</div>
				<div class="column">
					<div class="teaminfo">
						Thank you for activating your account!<br>
						<span style="font-size: 10pt">Log in <a href="login.php">here</a></span>
					</div>
				</div>
				<div class="column">
				</div>
			</div>
<?php
	    }
?>

<!-- Test account validation key: 8cJd0ezt00ZdFLAgIBytw1oYvhEEsjSz -->
<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
