<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("Location: teams.php");
	exit;
}
?>	
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<?php
	// Setup mail sending library
	require_once('PHPMailer/PHPMailer.php');
	require_once('PHPMailer/SMTP.php');
	require_once('PHPMailer/Exception.php');
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = '465'; //465
	$mail->isHTML();
	$mail->Username = 'nukees';
	$mail->Password = '<insert password>';
//	$mail->SMTPSecure = "tls";
	$mail->SetFrom('scientistsvseveryone@agnostica.com','Scientists Vs. Everyone');

	function generateRandomString($length = 32)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
	
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
	
		return $randomString;
	}
	
	// Define variables and initialize with empty values
	$teamname = $email = $password = $confirm_password = $player_names = $ham = "";
	$teamname_err = $email_err = $password_err = $confirm_password_err = $player_names_err = "";
	 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
	 
	    // Validate teamname
	    if(empty(trim($_POST["teamname"])))
	    {
	        $teamname_err = "Please enter a team name.";
	    } 
	    else
	    {
	        // Prepare a select statement
	        $sql = "SELECT teamid FROM teams WHERE teamname = ?";
	        
	        if($stmt = mysqli_prepare($link, $sql))
	        {
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($stmt, "s", $param_teamname);
	            
	            // Set parameters
	            $param_teamname = trim($_POST["teamname"]);

		    $ham = 0;
		    if(isset($_POST['ham'])) $ham = 1;
	            
	            // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($stmt))
	            {
	                /* store result */
	                mysqli_stmt_store_result($stmt);
	                
	                if(mysqli_stmt_num_rows($stmt) == 1)
	                {
	                    $teamname_err = "This teamname is already taken.";
	                } 
	                else
	                {
	                    $teamname = trim($_POST["teamname"]);
	                }
	            } 
	            else
	            {
	                echo "Oops! Something went wrong. Please try again later.";
	            }

	            // Close statement
	            mysqli_stmt_close($stmt);
	        }
	    }

	    // Validate password
	    if(empty(trim($_POST["email"])))
	    {
	        $email_err = "Please enter an email address.";     
	    } 
	    elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL))
	    {
	        $email_err = "Please enter a valid email address.";
	    } 
	    else
	    {
	        $email = trim($_POST["email"]);
	    }

	    
	    // Validate password
	    if(empty(trim($_POST["password"])))
	    {
	        $password_err = "Please enter a password.";     
	    } 
	    elseif(strlen(trim($_POST["password"])) < 6)
	    {
	        $password_err = "Password must have at least 6 characters.";
	    } 
	    else
	    {
	        $password = trim($_POST["password"]);
	    }
	    
	    // Validate confirm password
	    if(empty(trim($_POST["confirm_password"])))
	    {
	        $confirm_password_err = "Please confirm password.";     
	    } 
	    else
	    {
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_err) && ($password != $confirm_password))
	        {
	            $confirm_password_err = "Password did not match.";
	        }
	    }

	    $player_names = trim($_POST["player_names"]);

	    // Check input errors before inserting in database
	    if(empty($email_err) && empty($teamname_err) && empty($password_err) && empty($confirm_password_err))
	    {
	        // First, find out the teamid for this newly created team
	        // Prepare an insert statement
	        $sql = "INSERT INTO teams (email, teamname, password, player_names, activation_key, ham) VALUES (?, ?, ?, ?, ?, ?)";
	         
	        if($stmt = mysqli_prepare($link, $sql))
	        {
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($stmt, "ssssss", $param_email, $param_teamname, $param_password, $param_player_names, $param_activation_key, $param_ham);
	            
	            // Set parameters
	            $param_email = $email;
	            $param_teamname = $teamname;
	            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
		    $param_player_names = $player_names;
		    $param_activation_key = generateRandomString(32);
	            $param_ham = $ham; 

	            // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($stmt))
	            {
	            	// First get the team id from the server
					$result = mysqli_query($link, "SELECT teamid FROM teams WHERE teamname = '". $teamname. "'");
					if (!$result) 
					{
						exit;
					}
					$row = mysqli_fetch_row($result);
					$teamid = $row[0];

					//upload tag to server
					$upload_dir = "images/";
					$img = $_POST['hidden_data'];
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data = base64_decode($img);
					$file = $upload_dir . "tag" . $teamid . ".png";
					$success = file_put_contents($file, $data);

					// Send activation email
					$mail->Subject = "Welcome to Scientists vs Everyone!";
					$mail->Body = "Hi there!<br><br>Thank you for playing Scientists vs. Everyone! Just one more step before you're ready to go, to activate your account, please follow this link: <a href='http://sve.nukees.com/sandbox/activate.php?key=" . $param_activation_key . "'>Activate My Account</a><br><br>Good luck saving the multiverse!";
					$mail->AddAddress($email);
					$mail->Send();

	                // Redirect to login page
	                header("location: login.php?new");
	            } 
	            else
	            {
	                echo "Something went wrong. Please try again later.";
	                echo "<br>$param_email <br> $param_teamname <br> $param_password <br> $param_player_names <br> $param_activation_key";
					echo "<br>$sql<br>Hi";
					echo "<br>$stmt<br>Hi";
	            }

	            // Close statement
	            mysqli_stmt_close($stmt);
	        }
	    }
	    
	    // Close connection
	    mysqli_close($link);
	}
?>

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

<!-- Team Info Block -->
<div class="row">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="teaminfo" style="background-color:#EEEEEE">
		<div class="rowheader">
			Please fill in this form to create an account. 
		</div>
                <div class="column" style="line-height: 1.5">

                    <div class="form-group <?php echo (!empty($teamname_err)) ? 'has-error' : ''; ?>">
                        <label>Team Name</label>
                        <input type="text" name="teamname" class="form-control" value="<?php echo $teamname; ?>">
                        <span class="help-block"><?php echo $teamname_err; ?></span>
                    </div>
	            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
	                <label>Email Address</label>
	                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
	                <span class="help-block"><?php echo $email_err; ?></span>
		    <p style="font-size:8pt">Note: We're not going to spam you or sell your s%*@, we're Burners.
		    The game is dynamic and we'll email you updates and announcements during the week of Aug 30-Sep 5.
		    After that, we'd like to let you know if we ever offer a new free game to play like this, both on and
		    off the playa.  But if you never want us to email you again after this game ends, uncheck this box:</p>
	            </div>
                    <div class="form-group">
                        <p style="font-size:10pt"><input type="checkbox" name="ham" value="ham" checked>  Yes!  Send me delicious ham!</p>
                    </div>
	            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
	                <label>Password</label>
	                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
	                <span class="help-block"><?php echo $password_err; ?></span>
	            </div>
	            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
	                <label>Confirm Password</label>
	                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
	                <span class="help-block"><?php echo $confirm_password_err; ?></span>
	            </div>
	            <div class="form-group <?php echo (!empty($player_names_err)) ? 'has-error' : ''; ?>">
	                <label>Player names</label>
					<textarea name="player_names" rows="5" cols="40" class="form-control"><?php echo $player_names; ?></textarea>
	                <span class="help-block"><?php echo $player_names_err; ?></span>
	            </div>

		</div>	
		<div class="column" style="line-height: 1.5">
		    <label>Draw your unique tag</label><br>
		    <p style="font-size:10pt">Your tag is how you show up on the game board</p>
			<!-- Flag editor HTML code here -->
			<canvas id="editor" width="320" height="320" style="border:1px solid #d3d3d3;" onmousemove="drawpixel(event)" onmousedown="drawpixel(event)">
				Your browser does not support the HTML5 canvas tag.
			</canvas>
			<br>
			<canvas id="palette" width="320" height="160" style="border:1px solid #d3d3d3;" onmousedown="selectcolor(event)">
				Your browser does not support the HTML5 canvas tag.
			</canvas>
			<br>
			<div style="font-size:10pt">Here's a preview of what it will look like:</div>
			<canvas id="preview" width="192" height="64" style="border:1px solid #d3d3d3;">
				Your browser does not support the HTML5 canvas tag.
			</canvas>
			<input type="hidden" id="hidden_data" name="hidden_data" value="">
		</div>
		<div class="column" style="line-height: 1.5">
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Submit" onclick="uploadcanvas();">
				<input type="reset" class="btn btn-default" value="Reset">
			</div>
			<p style="font-size:10pt">Already have an account? <a href="login.php">Login here</a>.</p>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<img height=100 width=100 src="images/sharpies.png">
		</div>
	</div>
	</form>
</div>


<script>
	// Init canvasses
	var editor = document.getElementById("editor");
	var editorctx = editor.getContext("2d");
	var palette = document.getElementById("palette");
	var palettectx = palette.getContext("2d");
	var preview = document.getElementById("preview");
	var previewctx = preview.getContext("2d");
//	var colors = ["#444444", "#0000FF", "#00FF00", "#FF0000", "#00FFFF", "#FFFF00", "#FF00FF", "#FFFFFF", "#000000", "#000088", "#008800", "#880000", "#008888", "#888800", "#880088", "#888888"];
        var colors = ["#d6a090", "#fe3b1e", "#a12c32", "#fa2f7a", "#fb9fda", "#e61cf7", "#992f7c", "#47011f", "#051155", "#4f02ec", "#2d69cb", "#00a6ee", "#6febff", "#08a29a", "#2a666a", "#063619", "#000000", "#4a4957", "#8e7ba4", "#b7c0ff", "#ffffff", "#acbe9c", "#827c70", "#5a3b1c", "#ae6507", "#f7aa30", "#f4ea5c", "#9b9500", "#566204", "#11963b", "#51e113", "#08fdcc"];


	// Draw palette
	function drawpalette()
	{
		palettectx.clearRect(0, 0, palette.width, palette.height);

		x = 0;
		y = 0;
		for (color = 0; color < 32; color++)
		{
			if (color == currentcolor)
			{
				palettectx.fillStyle = "#000000";
				palettectx.fillRect(x, y, 40, 40);
				palettectx.fillStyle = colors[color];
				palettectx.fillRect(x + 2, y + 2, 36, 36);
			}
			else
			{
				palettectx.fillStyle = colors[color];
				palettectx.fillRect(x, y, 40, 40);
			}

			x += 40;
			if (x >= 320)
			{
				x  = 0;
				y += 40;
			}
		}
	}

	var currentcolor = 0;
	editorctx.fillStyle = colors[currentcolor];
	palettectx.fillStyle = "#000000";
	drawpalette();

	// keep track of mousebutton state
	var mouseDown = 0;
	document.body.onmousedown = function()
	{ 
		++mouseDown;
	}
	document.body.onmouseup = function()
	{
		--mouseDown;
	}

	function drawpixel(e)
	{
		var rect = editor.getBoundingClientRect();

		var locx = e.clientX - rect.left;
		var locy = e.clientY - rect.top;

		// Pixelsize
		var x = Math.floor(locx / 10);
		var y = Math.floor(locy / 10);

		// Draw pixels
		if (mouseDown)
		{
			// Update editor
			editorctx.fillRect(x * 10, y * 10, 10, 10);

			// update preview
			previewctx.fillStyle = colors[currentcolor];
			previewctx.fillRect(x      , y, 1, 1);
			previewctx.fillRect(x +  32, y, 1, 1);
			previewctx.fillRect(x +  64, y, 1, 1);
			previewctx.fillRect(x +  96, y, 1, 1);
			previewctx.fillRect(x + 128, y, 1, 1);
			previewctx.fillRect(x + 160, y, 1, 1);
			previewctx.fillRect(x      , y + 32, 1, 1);
			previewctx.fillRect(x +  32, y + 32, 1, 1);
			previewctx.fillRect(x +  64, y + 32, 1, 1);
			previewctx.fillRect(x +  96, y + 32, 1, 1);
			previewctx.fillRect(x + 128, y + 32, 1, 1);
			previewctx.fillRect(x + 160, y + 32, 1, 1);
		}	
	}

	function selectcolor(e)
	{
		var rect = palette.getBoundingClientRect();

		var locx = e.clientX - rect.left;
		var locy = e.clientY - rect.top;

		// Palette grid size
		var x = Math.floor(locx / 40);
		var y = Math.floor(locy / 40);

		currentcolor = x + (y * 8);
		editorctx.fillStyle = colors[currentcolor];

		console.log("currentcolor = " + currentcolor + "(" + x + ", " + y + ")");

		drawpalette();
	}

	function uploadcanvas()
	{
		var dataURL = preview.toDataURL("image/png");
		document.getElementById('hidden_data').value = dataURL;
	}

</script>


<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

