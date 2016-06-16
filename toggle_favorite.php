<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$v_id =$_GET['id'];
$p_id =$_POST['pid'];
$is_fav = $_POST['tog'];
global $db;

if($is_fav == $v_id)
{
	$query = "DELETE FROM playlist_entry WHERE playlist_id = '$p_id' AND video_id = '$v_id'";
}
else
{
	$query = "INSERT INTO playlist_entry(playlist_id, video_id) VALUES('$p_id', '$v_id')";
}
$insert = mysqli_query(  $db, $query )
			or die ("Could not update the playlist_entry table: <br />". mysqli_error( $db));	
?>

<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $v_id;?>">
