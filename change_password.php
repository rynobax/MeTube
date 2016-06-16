<?php
include "header.php";
include_once "function.php";
?>
<title>Change Password</title>
</head>
<?php
if(isset($_GET['return'])){
	$return = $_GET['return'];
	echo '<h2 align="center">';
	if($return == 0)
		echo "Current Password did not match your password.</h2>";
	else if($return == 1)
		echo "New Password and did not match the repeat.</h2>";
	else{
		echo 'Password change was a success.<br></h2><h3 align="center">Redirecting in 5 seconds.</h3>';
		?>
		<script type='text/javascript'>
		window.setTimeout(function(){

        // Move to a new location or you can do something else
        window.location.href = "user.php?id=<?php echo $_SESSION['id'] ?>";

    	}, 5000);</script><?php
		return;
	}
}
?>

<div class="container">
   	<form class="form-signin" method="post" action="change_password_process.php">
    	<h2 class="form-signin-heading">Change Password</h2>
        <label for="currentpassword" class="sr-only">Current Password</label>
        <input type="password" id="currentpassword" name="current" class="form-control" placeholder="Current Password" required autofocus>
        <label for="password1" class="sr-only">New Password</label>
        <input type="password" id="password1" name="new" class="form-control" placeholder="New Password" required>
        <label for="password2" class="sr-only">Repeat New Password</label>
        <input type="password" id="password2" name="repeat" class="form-control" placeholder="Repeat New Password" required>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="Submit">Change Password</button>
    </form>
</div>
</form>
</body>
</html>