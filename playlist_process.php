<!-- 
media_upload_process.php

Uploads media

TODO:
-->
<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/
global $db;
if(isset($_POST['remove']))
{
	$vid_id = $_POST['vid_id'];
	$play_id = $_POST['remove'];
	$query = "DELETE FROM playlist_entry WHERE playlist_id = '$play_id' AND video_id = '$vid_id'";
	$result = mysqli_query($db,$query);
	if(!$result){
		echo $query;
		die("<br>Could not remove from playlist_entry table in the database: <br />". mysqli_error($db));
	}
	?>
	<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $vid_id;?>&playlists=1"> 
	<?php
}
else if(isset($_POST['add']))
{
	$vid_id = $_POST['vid_id'];
	$play_id = $_POST['add'];
	$query = "INSERT INTO playlist_entry(playlist_id, video_id) VALUES($play_id, $vid_id)";
	$result = mysqli_query($db,$query);
	if(!$result){
		echo $query;
		die("<br>Could not insert into playlist_entry table in the database: <br />". mysqli_error($db));
	}
	?>
	<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $vid_id;?>&playlists=1"> 
	<?php
}
else if(isset($_POST['delete_playlist']))
{
	$play_id = $_POST['play_id'];
	$user_id = $_SESSION['id'];
	$query = "DELETE FROM playlist_entry WHERE playlist_id = '$play_id'";
	$result = mysqli_query($db,$query);
	if(!$result)
		die("Could not remove from playlist_entry table in the database: <br />". mysqli_error($db));
	$query = "DELETE FROM playlist WHERE id = '$play_id'";
	$result = mysqli_query($db,$query);
	if(!$result)
		die("Could not remove from playlist table in the database: <br />". mysqli_error($db));
	?>
	<meta http-equiv="refresh" content="0;url=user.php?id=<?php echo $user_id;?>&playlists=1">
	<?php
}

?>
