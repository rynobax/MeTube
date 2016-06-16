<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$vid_id=$_GET['id'];
global $db;
$tags = $_POST['tags'];
do_tags($_POST["tags"], $vid_id);
?>

<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $vid_id;?>">  
