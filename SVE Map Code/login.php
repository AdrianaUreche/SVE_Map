<?php include("db.php");?>  <!-- Login Session and database functions -->

<?php
// // Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
// {
//     header("location: teams.php");
//     exit;
// }
// 
// // Include config file
// require_once "config.php";
 
// Define variables and initialize with empty values
$teamname = $password = "";
$teamname_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
 
    // Check if teamname is empty
    if(empty(trim($_POST["teamname"])))
    {
        $teamname_err = "Please enter teamname.";
    } 
    else
    {
        $teamname = trim($_POST["teamname"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
    } 
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($teamname_err) && empty($password_err))
    {
        // Prepare a select statement
        // Make sure to only select activated accounts though!
        $sql = "SELECT teamid, teamname, password FROM teams WHERE activation_key IS NULL AND teamname = ?";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_teamname);
            
            // Set parameters
            $param_teamname = $teamname;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if teamname exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1)
                {                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $teamname, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["teamname"] = $teamname;                            
                            
                            // Redirect user to welcome page
                            header("location: teams.php");
                        } 
                        else
                        {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                }
                 else
                {
                    // Display an error message if teamname doesn't exist
			$teamname_err = "No account found with that teamname";
			$teamname_err .= " (you may need to activate your account--check your email for an activation link).";
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
    
    // Close connection
    mysqli_close($link);
}
?>
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

<style>
    * 
    {
      box-sizing: border-box;
    }


    /* Create three equal columns that floats next to each other */
    .teaminfo 
    {
      float: left;
      width: 95%;
      padding: 0px;
      margin: 2%;
      font-size: 18pt;
    }

    /* Create three equal columns that floats next to each other */
    .column 
    {
      float: left;
      width: 29%;
      padding: 0px;
      margin: 2%;
    }

    /* Clear floats after the columns */
    .rowheader 
    {
      background-color: #333333;
      color: #EEEEEE;
      margin: 0;
      padding: 10px;
      font-weight: bold;
      font-size: 18pt;
    }

    .row 
    {
      padding: 0px;
    }

    .puzzlename 
    {
      padding: 2%;
      width: 95%;
      font-weight: bold;
      font-size: 14pt;
      line-height: 2;
    }

    .puzzleicon 
    {
        float: left;
        padding: 10px;
    }

    /* Clear floats after the columns */
    .row:after 
    {
      content: "";
      display: table;
      clear: both;
    }
</style>

 
<div class="row">
  <!-- Empty -->
  <div class="column">
  </div>
  <!-- Login -->
  <div class="column" style="background-color:#EEEEEE;">
    <div class="rowheader">
        Login
    </div>
    <div class="wrapper" style="padding:10px">
        <p>Welcome Scientists!  What's your team name and password?</p>
<?php if (isset($_GET['new'])) echo "<font color=red>Check your email to activate your new account before logging in.</font>";?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($teamname_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="teamname" class="form-control" value="<?php echo $teamname; ?>">
                <span class="help-block"><?php echo $teamname_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
  </div>
  <!-- Empty -->
  <div class="column">
  </div>
</div>

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
