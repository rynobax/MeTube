<?php
include_once "function.php";

/******************************************************
*
* upload document from user
*
*******************************************************/

$user_id=$_POST['user_id'];
$name =$_POST['name'];
global $db;

$query = "INSERT INTO playlist(title,user_id,creation_datetime) values(".
			"'$name', '$user_id', NOW())";
$insert = mysqli_query(  $db, $query )
			or die ("Could not insert into the comment table: <br />". mysqli_error( $db));	
?>

<meta http-equiv="refresh" content="0;url=user.php?id=<?php echo $user_id;?>&playlists=1"> 
