<!-- 
register.php

Register a new user

TODO:	
-->
<?php
include 'header.php';

include_once "function.php";

if(isset($_POST['submit'])) {
	$username = $_POST['username'];
	if( $_POST['password1'] != $_POST['password2']) {
		$register_error = "Passwords don't match. Try again?";
	}
	else {
		$check = user_exist_check($username, $_POST['password1']);	
		if($check == 1){
			//echo "Register succeeds";
			$_SESSION['username']=$username;
			$id = user_get_id($username);
			$_SESSION['id']=$id;
			header("Location: user.php?id='$id'");
		}
		else if($check == 2){
			$register_error = "Username already exists. Please user a different username.";
		}
	}
}

?>
<style>
/* Override default */
.container {
    width: 400px;
}
</style>
	<div class="container">
      <form class="form-signin" method="post" action="register.php">
        <h2 class="form-signin-heading">Register</h2>
        <label for="username" class="sr-only">Username</label>
        <input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="password1" class="sr-only">Create Password</label>
        <input type="password" id="password1" name="password1" class="form-control" placeholder="Password" required>
        <label for="password2" class="sr-only">Repeat Password</label>
        <input type="password" id="password2" name="password2" class="form-control" placeholder="Repeat Password" required>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="Submit">Register</button>
      </form>
    </div>

<?php
  if(isset($register_error))
   {  echo "<div id='passwd_result'> register_error:".$register_error."</div>";}
?>

</body>
</html>
