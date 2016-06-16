<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$vid_id=$_POST['id'];
global $db;
$tag = $_POST['tag'];
$query = "DELETE FROM tag WHERE id = $tag";
$result = mysqli_query($db, $query);
if (!$result){
   die ("Could not delete from the tag table in the database: <br>". mysqli_error($db));
}
?>
<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $vid_id;?>">  	
