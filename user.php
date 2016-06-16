<!-- 
user.php

User's homepage.  When the user is own his own page, he can edit it.

TODO:	Add cancel when editing
-->
<?php
	include "header.php";
	function noTabSpecified(){
		if(isset($_GET['uploads'])) return false;
		if(isset($_GET['playlists'])) return false;
		if(isset($_GET['favorites'])) return false;
		if(isset($_GET['subscriptions'])) return false;
		if(isset($_GET['settings'])) return false;
		return true;
	}

	function viewingUploads(){
		if(isset($_GET['uploads'])) return true;
		return false;
	}

	function viewingPlaylists(){
		if(isset($_GET['playlists'])) return true;
		return false;
	}

	function viewingFavorites(){
		if(isset($_GET['favorites'])) return true;
		return false;
	}

	function viewingSubscriptions(){
		if(isset($_GET['subscriptions'])) return true;
		return false;
	}

	function viewingSettings(){
		if(isset($_GET['settings'])) return true;
		return false;
	}

	if(isset($_POST['id']) and isset($_POST['body'])){
		$postid = $_POST['id'];
		$postbody = $_POST['body'];
		$query = "UPDATE user 
					SET profile_body = '$postbody '
					WHERE id = $postid";
		$result = mysqli_query($db, $query );
		if (!$result){
		   die ("Could not query the user table in the database: <br>". mysqli_error($db));
		}
		echo "success";
		return;
	}
	else if(isset($_GET['id'])){
		$id = $_GET['id'];

		// Is the current user own his/her own page?
		if(user_is_logged_in() and $_SESSION['id'] == $id)
			$owner = true;
		else
			$owner = false;

		// Getting data to populate page
		$query = "SELECT username, profile_body, join_datetime 
					FROM user 
					WHERE id = $id"; 
		$result = mysqli_query($db,$query);
		if (!$result){
			// query failed, should never happen outside of early testing
		   die ("Could not query the user table in the database: <br />". mysqli_error($db));
		}
		if (mysqli_num_rows($result) == 0){
			// User does not exist
		   die ("User does not exist");
  		}
		$result_row = mysqli_fetch_row($result);
		$username = $result_row[0];
		$profile_body = $result_row[1];
		$join_datetime = $result_row[2];
	}else{
		header('Location: index.php');
	}

	if(isset($_POST['delete_playlist_media_id'])){
		$vid_id = $_POST['delete_playlist_media_id'];
		$play_id = $_POST['playlist_id'];
		$query = "DELETE FROM playlist_entry WHERE playlist_id = '$play_id' AND video_id = '$vid_id'";
		$result = mysqli_query($db,$query);
		if(!$result)
			die("Could not remove from playlist_entry table in the database: <br />". mysqli_error($db));
	}

	if(isset($_POST['delete_subscription_id'])){
		$sub_id = $_POST['delete_subscription_id'];
		$query = "DELETE FROM subscriber WHERE subscriber_id = '$_SESSION[id]' AND subscribeie_id = '$sub_id'";
		$result = mysqli_query($db,$query);
		if(!$result)
			die("Could not remove from playlist_entry table in the database: <br />". mysqli_error($db));
	}
?>	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ucfirst($username)?>'s Page</title>
<script src="js/jquery-latest.pack.js" type="text/javascript"></script>
<script language="Javascript">
function saveEdits(id) {
	//get the editable element
	var editElem = document.getElementById("edit");
	//get the edited element content
	var newBody = strip(editElem.innerHTML).trim().replace(/\s/g,' ');
	$.post("user.php",
	{
		id: id,
		body: newBody
	},
	function(data, status){
        window.location.href = "user.php?id=" + id;
    });
}
/* Removes html tags */
function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}
</script>
</head>
<body>
<style>
.jumbotron
{
	padding-top: 0px;
}
</style>
<div class="container">
  <div class="jumbotron">
<h1><?php echo ucfirst($username) ?>'s page!</h1>
<div style="float:right">
<?php
subscribe($id);
?>
</div>
	<ul class="nav nav-tabs ">
		<li <?php if(noTabSpecified()) echo "class=\"active\""; ?>>
			<form id="profile_tab" method="GET" action="user.php">
				<input type = "hidden" name = "profile" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('profile_tab').submit();return false;">Profile</a>
		</li>
		<li <?php if(viewingUploads()) echo "class=\"active\""; ?>>
			<form id="uploads_tab" method="GET" action="user.php">
				<input type = "hidden" name = "uploads" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('uploads_tab').submit();return false;">Uploads</a>
		</li>
		<li <?php if(viewingPlaylists()) echo "class=\"active\""; ?>>
			<form id="playlists_tab" method="GET" action="user.php">
				<input type = "hidden" name = "playlists" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('playlists_tab').submit();return false;">Playlists</a>
		</li>
		<li <?php if(viewingFavorites()) echo "class=\"active\""; ?>>
			<form id="favorites_tab" method="GET" action="user.php">
				<input type = "hidden" name = "favorites" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('favorites_tab').submit();return false;">Favorites</a>
		</li>
		<li <?php if(viewingSubscriptions()) echo "class=\"active\""; ?>>
			<form id="subscriptions_tab" method="GET" action="user.php">
				<input type = "hidden" name = "subscriptions" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('subscriptions_tab').submit();return false;">Subscriptions</a>
		</li>
		<?php if($owner){ ?>
		<li <?php if(viewingSettings()) echo "class=\"active\""; ?>>
			<form id="settings_tab" method="GET" action="user.php">
				<input type = "hidden" name = "settings" value="1"/>
				<input type = "hidden" name = "id" value="<?php echo $id;?>"/>
			</form>
			<a href="" onclick="document.getElementById('settings_tab').submit();return false;">Settings</a>
		</li>
		<?php } ?>
	</ul>
<!-- Before profile content -->
<div id="profile-content">
<style>
#profile-content {
    font-size: 21px;/* Match jumbotron default */	
	margin-bottom: 15px;
	text-align: left;
}
#edit {
	background-color: 	white;
}
</style>
<?php 
if(noTabSpecified()){?>
	<div style="text-align: center"><p><i>User since <?php echo $join_datetime ?></i></p></div><?php
	if($owner and isset($_POST['edit'])){?>
	<div id="edit" contenteditable="true">
<?php
}
	if($owner){
		if(!empty($profile_body)){
			echo $profile_body;
		}
		else
			echo "Your profile body is blank!  You should create one!";
	}else{
		echo $profile_body;
	}
	if($owner and isset($_POST['edit'])) echo "</div>";?>
	<!-- After profile content -->
	</div>
	<?php
	if($owner){
		if(!isset($_POST['edit'])){ ?>
			<button class="btn btn-lg btn-secondary" form="edit_form" type="submit">
				<span class="glyphicon glyphicon-edit" ></span> Edit
			</button>
			<form action="user.php?id=<?php echo $id ?>" method="post" name="edit" id="edit_form">
	        	<input type = "hidden" name = "edit" value="1"/>
			</form>
		<?php }else{ ?>
			<button class="btn btn-lg btn-success" onclick="saveEdits(<?php echo $id ?>)"/>
				<span class="glyphicon glyphicon-save" ></span> Save
			</button>
<?php
		}
	}
}

/********************
	SUBSCRIPTIONS
********************/
if($owner && viewingSubscriptions())
{
	$query = "SELECT subscribeie_id, username FROM subscriber, user WHERE subscriber_id = '$id' AND subscribeie_id = user.id";
	$result = mysqli_query( $db,$query );
	if (!$result){
	   die ("Could not query the subscriber and user table in the database: <br />". mysqli_error($db));
	}
	if(mysqli_num_rows($result)==0){
		echo "<tr><td>You are not subscribed to anyone.</td></tr>";
	}
	else
	{
		while($result_row = mysqli_fetch_row($result))
		{
			$sub_id = $result_row[0];
			$sub_uname = $result_row[1];
			?>
			<table width="100%" class="list-inline">
	    	<td width="100%">
				<a href="user.php?id=<?php echo $sub_id;?>" class="list-group-item">
					<h2 class="list-group-item-heading"><?php echo ucwords($sub_uname);?></h2>
				</a>
			</td>
			<td width="0%" >
				<button class="btn btn-lg btn-danger" form="delete<?php echo $sub_id ?>" type="submit">
					<span class="glyphicon glyphicon-remove" ></span>
				</button>
	        	<form action="user.php?id=<?php echo $id?>" method="post" id="delete<?php echo $sub_id ?>">
	        		<input type = "hidden" name = "delete_subscription_id" value="<?php echo $sub_id ?>"/>
	        		<input type = "hidden" name = "subscriptions" value="1"/>
	        	</form>
			</td>
			</table>
			<?php
		}
	}
	echo"</table>";
}

/********************
	  SETTINGS
********************/
else if($owner && viewingSettings())
{
	?>
	<br>
	<div style="text-align:center">
		<button class="btn btn-lg btn-secondary" action="submit" form="change_password_form">Change Password</a>
		<form action="change_password.php" id="change_password_form"></form> 
	</div>
	<?php
}

/********************
       UPLOADS
********************/
else if(viewingUploads())
{
?>
<form action="user.php?id=<?php echo $_GET['id']; ?>&uploads=1" class="navbar-form navbar-left" method="post">   <div class="form-group">
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
</br></br></br>
<?php
if(isset($_POST['category']) && isset($_POST['search']))
{
	$cat = $_POST['category'];
	$ser = $_POST['search'];
	$query = "SELECT DISTINCT media.id, media.title
		FROM media
		LEFT JOIN tag
		ON tag.video_id = media.id
		WHERE ((";
	$all_ser = explode(" ", $ser);
	$sqcount = 0;
	foreach($all_ser as &$sq)
	{
		if($sqcount == 0)
		{
			$query = $query."(media.title LIKE '%$sq%'
				OR tag.tag LIKE '%$sq%')";
		}
		else
		{
			$query = $query."AND (media.title LIKE '%$sq%'
        OR tag.tag LIKE '%$sq%')";
		}
		$sqcount++;
	}
	$query = $query.")AND user_id =$id)";
	if($cat!= "All")
		$query = $query." AND media.category = '$cat'";
	$result = mysqli_query( $db,$query );
	if (!$result){
	   die ($query."Could not query the media table in the database: <br />". mysqli_error($db));
	}
}
else
{
	$query = "SELECT id, title, path, type
			FROM media
			WHERE user_id = $id"; 
	$result = mysqli_query( $db,$query );
	if (!$result){
	   die ("Could not query the media table in the database: <br />". mysqli_error($db));
	}
}
?>
    
	<table class="table" width="100%" cellpadding="0" cellspacing="0">
		<?php
		if (mysqli_num_rows($result)==0){
			echo "This user has uploaded no media, or no media matches your search.";
		}else{
			while ($result_row = mysqli_fetch_row($result)) //filename, username, type, mediaid, path
			{ 
				$media_id = $result_row[0];
				$media_title = $result_row[1];
				$path = $result_row[2];
				$media_type = $result_row[3];
		?>
			<table width="100%" class="list-inline">
	    	<td width="
	    	<?php
	    	if(substr($media_type,0,5)=="image") echo "85%";
	    	else echo "100%";
	    	?>
	    	">
				<a href="media.php?id=<?php echo $media_id;?>" class="list-group-item">
					<h3 class="list-group-item-heading"><?php echo $media_title;?></h3>
				</a>
			</td>
			<?php
				if(substr($media_type,0,5)=="image"){
			?>
			<td width="15%">
				<img src="<?php echo $path; ?>" class="img-responsive img-thumbnail" alt="<?php echo $media_title; ?>">
			</td>
			<?php
			}
			?>
			<td width="0%" >
				<button class="btn btn-lg btn-danger" form="delete<?php echo $media_id ?>" name="delete" type="submit">
					<span class="glyphicon glyphicon-remove" ></span>
				</button>
	        	<form action="media_delete_process.php" method="post" id="delete<?php echo $media_id ?>">
	        		<input type = "hidden" name = "media_id" value="<?php echo $media_id ?>"/>
	        		<input type = "hidden" name = "favorites" value="1"/>
	        	</form>
			</td>
			</table>
        	<?php
			}
		}
		?>
	</table>
   </div>

<?php
}

/********************
	  FAVORITES
********************/
else if($owner && viewingFavorites())
{
	$query = "SELECT playlist_entry.video_id, media.title, media.type, playlist_entry.playlist_id, media.path
						FROM playlist_entry
						INNER JOIN playlist
						ON playlist.title = 'Favorites' 
						AND playlist.user_id = '$id' 
						AND playlist.id = playlist_entry.playlist_id
						LEFT JOIN media
						ON media.id = playlist_entry.video_id";
	$result = mysqli_query( $db, $query);
	if(!$result)
		die ("Could not query the playlist tables in the database: <br />". mysqli_error($db));
	if(mysqli_num_rows($result)==0)
		echo "<tr><td>You have no favorites.</td></tr>";
	else
	{
		while($result_row = mysqli_fetch_row($result))
		{
			$media_id = $result_row[0];
			$media_title = $result_row[1];
			$media_type = $result_row[2];
			$playlist_id = $result_row[3];
			$path = $result_row[4];
			?>
			<table width="100%" class="list-inline">
	    	<td width="
	    	<?php
	    	if(substr($media_type,0,5)=="image") echo "85%";
	    	else echo "100%";
	    	?>
	    	">
				<a href="media.php?id=<?php echo $media_id;?>" class="list-group-item">
					<div class="list-group-item-heading"><?php echo $media_title;?></div>
				</a>
			</td>
			<?if(substr($media_type,0,5)=="image"){ ?>
			<td width="15%">
				<img src="<?php echo $path; ?>" class="img-responsive img-thumbnail" alt="<?php echo $media_title; ?>">
			</td>
			<?php } ?>
			<td width="0%" >
				<button class="btn btn-lg btn-danger" form="delete<?php echo $media_id ?>" name="delete" type="submit">
					<span class="glyphicon glyphicon-remove" ></span>
				</button>
	        	<form action="user.php?id=<?php echo $id?>&favorites=1" method="post" id="delete<?php echo $media_id ?>">
	        		<input type = "hidden" name = "delete_playlist_media_id" value="<?php echo $media_id ?>"/>
	        		<input type = "hidden" name = "playlist_id" value="<?php echo $playlist_id ?>"/>
	        	</form>
			</td>
			</table>
    	<?php
		}
	}
}

/********************
	  PLAYLISTS
********************/
else if(viewingPlaylists())
{
if($owner){
	?>
  <br>
	<a style="float:right" href="create_playlist.php" role="button" class="btn btn-lg btn-success">
      <span class="glyphicon glyphicon-plus" ></span> New Playlist
    </a>
  <?php
} ?>
<!-- After new but before playlists -->
<div class="list-group">
<?php
$query = "SELECT id, title
					FROM playlist 
					WHERE user_id = '$id' AND title != 'Favorites'";
$result = mysqli_query($db, $query);
if(!$result)
	die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
while($result_row = mysqli_fetch_row($result))
{
	$play_id = $result_row[0];
	$play_title = $result_row[1];
	?>
	<table width="100%" class="list-inline">
    	<td width="100%">
			<a href="playlist.php?id=<?php echo $play_id;?>" class="list-group-item">
				<h4><?php echo ucwords ($play_title) ?></h4>
			</a>
		</td>
	<?php
	if($owner)
	{
		?>
		<td width="0%">
			<button class="btn btn-lg btn-danger" form="delete_playlist<?php echo $play_id ?>" type="submit">
				<span class="glyphicon glyphicon-remove" ></span>
			<form method="post" action = "playlist_process.php" id="delete_playlist<?php echo $play_id ?>">
				<input type = "hidden" name = "play_id" value="<?php echo $play_id ?>"/>
				<input type = "hidden" name = "delete_playlist" value="Delete"/>
				<input type = "hidden" name = "playlists" value="1"/>
			</form>
		</td>
		<?php
	}
	echo "</div>";
}
echo "</div>";
}
?>
</div></div>
</body>
</html>
