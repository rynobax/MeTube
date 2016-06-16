<?php
	include "header.php";
	include_once "function.php";
	
	$user = mysqli_real_escape_string($db, $_POST['to']);
	$subj = mysqli_real_escape_string($db, $_POST['subj']);
	$msg = mysqli_real_escape_string($db, $_POST['msg']);
	
	$query = "SELECT id FROM `user` WHERE `username` = '$user'";
	$result = mysqli_query($db, $query);
	
	if(!$result) {
		echo "Error sending message! Username does not exist!";
		exit(-1);
	}
	
	$row = mysqli_fetch_row($result);
	
	$uid = $row[0];
	$mid = rand();
	$sid = $_SESSION['id'];

	$query = "INSERT INTO `message` (`id`, `user_send_id`, `user_receive_id`, `title`, `content`, `send_datetime`, `read`) VALUES ('$mid', '$sid', '$uid', '$subj', '$msg', CURRENT_TIMESTAMP, '0');";
	$result = mysqli_query($db, $query);
	
	if(!$result) {
		echo "Error sending message!";
		exit(-1);
	}
	
	echo "<script type=\"text/javascript\">setTimeout(\"window.close();\", 1000);</script>Message sent!";

?>