<!-- 
login.php

Login page

TODO:	
-->
<?php 
include 'header.php';

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
			if($check == 1) {
				$login_error = "User ".$_POST['username']." not found.";
			}
			elseif($check==2) {
				$login_error = "Incorrect password.";
			}
			else if($check==0){
				$_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
				$_SESSION['id'] = user_get_id($_POST['username']);
				header('Location: index.php');
			}		
		}
}


 
?>
<style>
/* Override default */
@media (min-width: 1200px) {
  .container {
    width: 400px;
  }
}
</style>
	<div class="container">
      <form class="form-signin" method="post" action="login.php">
        <h2 class="form-signin-heading">Sign In</h2>
        <label for="username" class="sr-only">Email address</label>
        <input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="Login">Sign in</button>
      </form>
    </div>

<?php
  if(isset($login_error))
   {  echo "<div id='passwd_result'>".$login_error."</div>";}
?>
