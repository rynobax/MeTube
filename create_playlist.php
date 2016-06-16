<?php
include "header.php";
include_once "function.php";
?>
<title>New Playlist</title>
</head>
<form action="create_playlist_process.php" method="post">
Playlist Name:<input type = "text" name="name"></br>
<input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>"/>
<input name="submit" type="submit" value="Submit">
</form>
</body>
</html>
