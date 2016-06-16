<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$sub_id =$_GET['id'];
global $db;

$query = "SELECT subscriber_id FROM subscriber WHERE subscribeie_id = '$sub_id' AND "
			."subscriber_id = '".$_SESSION['id']."'";
$request = mysqli_query(  $db, $query )
			or die ("Could not query the subscriber table: <br />". mysqli_error( $db));
$request_rows = mysqli_fetch_row($request);
$is_sub = $request_rows[0];
if($is_sub == $_SESSION['id'])
{
	$query = "DELETE FROM subscriber WHERE subscribeie_id = '$sub_id' AND "
			."subscriber_id = '".$_SESSION['id']."'";
}
else
{
	$query = "INSERT INTO subscriber(subscribeie_id, subscriber_id) VALUES(".
						"'$sub_id', '".$_SESSION['id']."')";
}
$insert = mysqli_query(  $db, $query )
			or die ("Could not update the media table: <br />". mysqli_error( $db));	
?>

<meta http-equiv="refresh" content="0;url=user.php?id=<?php echo $sub_id;?>">
