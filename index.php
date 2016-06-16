<!-- 
index.php

Main Page

TODO:
-->
<?php include 'header.php';?>
<title>Metube</title>
<div class="container">
  <div class="jumbotron">
  	<h1>
<?php
	if(isset($_POST['logout'])){
		$_SESSION = array();
		session_destroy();
	}
	if(user_is_logged_in()){
		$username = ucwords($_SESSION['username']);
		$id = $_SESSION['id'];
		echo "Welcome $username!</h>";
	}
	else{
		echo "Welcome to Metube!</b></h>";?>
		<script>refreshHeader()</script>
		<?php
	}
?>
<p class="lead">
<?php if( user_is_logged_in() ){ ?>
<h2><a href="user.php?id=<?php echo $id;?>"> <font color="black">View Your Profile</font></a></h2>
<?php } ?>
<h2><a href="browse_users.php"> <font color="black">Browse All Users</font></a></h2>
<h2><a href="browse_media.php?category=All&search="> <font color="black">Browse All Media</font></a></h2>
</p>

</div></div>

</body>
</html>
