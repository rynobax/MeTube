<?php
	include "header.php";
	include_once "function.php";
	
	
	$show_read = 0;
	
	$uid = $_SESSION['id'];
	
	if(isset($_GET['d'])) {
		$td = $_GET['d'];
		$query = "DELETE FROM message WHERE id = $td";
		mysqli_query($db, $query);
	}
	if(isset($_GET['mr'])) {
		$mr = $_GET['mr'];
		$query = "UPDATE `message` SET  `read` =  '1' WHERE  `id` = $mr";
		if(!mysqli_query($db, $query)) {
			die ("Could not query the media table in the database: <br>". mysqli_error($db));
		}
	}
	if(isset($_GET['sr'])) {
		$td = $_GET['sr'];
		$show_read = 1;
	}
	if(isset($_GET['a'])) {
			$query = "UPDATE  `message` SET  `read` = 1";
			$result = mysqli_query($db, $query);
			if(!$result) {
				die ("Could not query the media table in the database: <br>". mysqli_error($db));
			}
	}
	
	
	if(!$show_read) {
		$query = "SELECT *  FROM `message` WHERE `user_receive_id` = $uid AND `read` = 0 ORDER BY send_datetime DESC";
	}
	else {
		$query = "SELECT * FROM message WHERE user_receive_id = $uid ORDER BY send_datetime DESC";
	}	
	
	$result = mysqli_query($db, $query);
	
	if(!user_is_logged_in()) {
		echo "You are not currently logged in. Please click <a href=\"login.php\">here</a> to log in.";
		exit(-1);
	}
	
	$uname = $_SESSION['username'];
	
?>
</head>
<body>
	<h1 align="center"><?php echo "$uname's Inbox"; ?></h1>
	<div align="center" id="inbox">
		<div width="80%">
			<a href="messaging.php?sr=1"><button>Show Read Messages</button></a>&nbsp;<a href="messaging.php?a=1"><button>Mark All Read</button></a>
			<button onclick="window.open('new_message.php', 'newwindow', 'width=800, height=500'); return false;">Compose New Message</button>
		</div>
		<br /><br />
	<table width="80%" border="1">

<?php
	
	$num_msg = mysqli_num_rows($result);
	if($num_msg != 0) {
		while($row = mysqli_fetch_array($result)) {
			$sender = $row['user_send_id'];
			$user_query = "SELECT username FROM user WHERE id = $sender";
			$user_results = mysqli_query($db, $user_query);
			$send_id = mysqli_fetch_array($user_results);
			$mid = $row['id'];
?>
	<tr border="0" width="100%">
		<td width="100%">
			<b>From: </b><?php echo $send_id['username'] ?><br />
			<b>To: </b><?php echo $uname ?><br />
			<b>Subject: </b><?php echo $row['title'] ?><br /><br />
			<p>
			<?php echo $row['content'] ?>
			</p>
			<br />
			<button onclick="window.open('new_message.php?m=<?php echo $mid; ?>', 'newwindow', 'width=800, height=500'); return false;">Reply</button>&nbsp;
			<button onclick="window.open('new_message.php?m=<?php echo $mid; ?>&f=1', 'newwindow', 'width=800, height=500'); return false;">Forward</button>&nbsp;
			<a href="messaging.php?mr=<?php echo $mid; ?>"><button>Mark As Read</button></a>&nbsp;
			<a href="messaging.php?d=<?php echo $mid; ?>"><button>Delete</button></a>
		</td>
	</tr>
<?php }}
	else {
		echo "You have no new messages.";
	}
?>
</table>
</div>
</body>
</html>


