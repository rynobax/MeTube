<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$v_id =$_GET['id'];
global $db;

$query = "SELECT comments FROM media WHERE id = $v_id";
$request = mysqli_query(  $db, $query )
			or die ("Could not query the media table: <br />". mysqli_error( $db));
$request_rows = mysqli_fetch_row($request);
$comment = $request_rows[0];
if($comment)
	$comment = FALSE;
else
	$comment = TRUE;
$query = "UPDATE media 
			SET comments = '$comment'
			WHERE id = $v_id";
$insert = mysqli_query(  $db, $query )
			or die ("Could not update the media table: <br />". mysqli_error( $db));	
?>

<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $v_id;?>">
