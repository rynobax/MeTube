<?php
include_once "function.php";
session_start();

/******************************************************
*
* Change user password
*
*******************************************************/

$user_id=$_SESSION['id'];
global $db;
$current = $_POST['current'];
$new = mysqli_real_escape_string($db, $_POST['new']);
$repeat = mysqli_real_escape_string($db, $_POST['repeat']);

$query = "SELECT password FROM user WHERE id = $user_id";
$result = mysqli_query(  $db, $query )
			or die ("Could not query the user table: <br />". mysqli_error( $db));
$result_row = mysqli_fetch_row($result);
$actual = $result_row[0];
if($current == $actual)
{
	if($new == $repeat)
	{
		$update = "UPDATE user SET password = '$new' WHERE id = $user_id";
		$result = mysqli_query(  $db, $update )
			or die ("Could not query the user table: <br />". mysqli_error( $db));
		$return = 2;
	}
	else
	{
		$return = 1;
	}
}
else
{
	$return = 0;
}
?>

<meta http-equiv="refresh" content="0;url=change_password.php?return=<?php echo $return;?>">  
