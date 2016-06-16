<?php
	include "header.php";
	include_once "function.php";	
	
	$forward = false;
	if(isset($_GET['m'])) {
		$mid = $_GET['m'];
		$vals_set = true;
		
		$query = "SELECT * FROM message WHERE id = $mid";
		$result = mysqli_query($db, $query);
		if(!$result) {
			echo "error";
		}
		
		$row = mysqli_fetch_row($result);
		$uid = $row[1];
		$subj = $row[3];
		
		if(isset($_GET['f'])) {
			$forward = true;
			$subj = "FWD: " . $subj;
			$msg = $row[4];
		}
		else
			$subj = "RE: " . $subj;
		$query = "SELECT username FROM user WHERE id = $uid";
		$usr_r = mysqli_query($db, $query);
		if(!$usr_r) {
			die ("Could not query the media table in the database: <br>". mysqli_error($db));
		}
		$usr = mysqli_fetch_row($usr_r);
		$uid = $usr[0];
	}
?>
<title>New Message -- MeTube</title>
</head>
<body>
	<div align="center">
		<form action="send_message.php" method="POST">
			<input type="text" size="40" name="to" <?php if($vals_set && !$forward) echo"value=\"$uid\""; ?> placeholder="Enter a Username"><br />
			<input type="text" size="40" name="subj" <?php if($vals_set) echo"value=\"$subj\""; ?> placeholder="Enter a Subject"><br />
			<textarea name="msg" cols="80" rows="5"  placeholder="Message"><?php if($forward) echo $msg; ?></textarea><br />
			<input type="submit" value="Send">
		</form>
	</div>
</body>
</html>