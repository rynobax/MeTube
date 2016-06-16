<!-- 
header.php

Should be included in the top of almost every page.
Starts the session
Gives options for logging in and out.

TODO: Login/Logout button does not update until page is refreshed (caching issue)
-->

<?php
include 'global_includes.php';
session_start();

?>
<script type='text/javascript'>
  function refreshHeader(){
    $('#header').load('index.php #header');
  }
</script>

<html>
<body>
<nav class="navbar navbar-default navbar-static-top" id="header">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">MeTube</a>
    </div>
       <form action="browse_media.php" class="navbar-form navbar-left" role="search">
         <div class="form-group">
		 <select name="category" id="categories">
			 <option value="All">All</option>
			 <option value="Music">Music</option>
			 <option value="Sports">Sports</option>
			 <option value="Gaming">Gaming</option>
			 <option value="Movies">Movies</option>
			 <option value="TV Shows">TV Shows</option>
			 <option value="News">News</option>
			 <option value="Education">Education</option>
			 <option value="Comedy">Comedy</option>
		   </select> 
           <input type="text" name="search" class="form-control" placeholder="Search">
         </div>
	     <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
       </form>
    
    <ul class="nav navbar-nav navbar-right">
<?php
	if(!user_is_logged_in()){?>
  <div class="btn-group">
      <a href="register.php" role="button" class="btn btn-lg btn-primary" style="color:white">
        <span class="glyphicon glyphicon-user" ></span> Sign Up
      </a>
			<a href="login.php" role= "button" class="btn btn-lg btn-success">
				<span class="glyphicon glyphicon-log-in" ></span> Log In
			</a>
    </div>
			<?php
  		}else{?>
    <div class="btn-group">
    <?php
		echo "<a href=\"user.php?id=".$_SESSION['id']."\" role=\"button\" class=\"btn btn-lg btn-primary\">
        <span class=\"glyphicon glyphicon-home\" ></span> ".ucwords($_SESSION['username'])."</a>";?>
		<a href='media_upload.php' role="button"  class="btn btn-lg btn-warning">
      <span class="glyphicon glyphicon-upload" ></span> Upload File
    </a>
		<a href="messaging.php" role="button" class="btn btn-lg btn-success">
      <span class="glyphicon glyphicon-inbox" ></span> Inbox
    </a>
    </div>
    <button type="submit" form="logout_form" class="btn btn-lg btn-danger" name="logout">
      <span class="glyphicon glyphicon-log-out" ></span> Log Out
      </button>
    <form action="index.php" method="post" name="logout" id="logout_form"></form>
  	
		<?php
  	}?>
      </ul>
    </div>
</nav>
