<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

global $db;
$vid_id =$_GET['v_id'];

if(isset($_POST['comment_id']))
{
	$comment_id = $_POST['comment_id'];
	$query = "DELETE FROM comment WHERE id = $comment_id";
	$result = mysqli_query($db, $query);
	if (!$result){
		 die ("Could not delete from the comment table in the database: <br>". mysqli_error($db));
	}
}
else
{

$username=$_SESSION['username'];
$user_id=$_SESSION['id'];
$comment =mysqli_real_escape_string($db, $_POST['comment']);

$query = "insert into comment(video_id,user_id,content,creation_datetime) values(".
			"'$vid_id', '$user_id', '$comment', NOW())";
$insert = mysqli_query(  $db, $query )
			or die ("Could not insert into the comment table: <br />". mysqli_error( $db));	
}
?>

<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $vid_id;?>"> 