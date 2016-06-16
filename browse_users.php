<?php
	include 'header.php';
	include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User browse</title>
</head>

<body>
<h1>Here are our users!</h1>
<?php
	global $db;
	$query = "SELECT id, username, join_datetime from user"; 
	$result = mysqli_query($db, $query );
	if (!$result){
	   die ("Could not query the media table in the database: <br />". mysqli_error($db));
	}
?>
	<table class="table" width="50%" cellpadding="0" cellspacing="0">
		<?php
			while ($result_row = mysqli_fetch_row($result)) //filename, username, type, mediaid, path
			{ 
				$id = $result_row[0];
				$username = $result_row[1];
				$join_datetime = $result_row[2];
		?>
        	 <tr valign="top">			
                        <td>
            	            <a href="user.php?id=<?php echo $id;?>"><?php echo $username;?></a> 
                        </td>
                        <td>
            	            <?php echo $join_datetime;?>
                        </td>
		</tr>
        	<?php
			}
		?>
	</table>
   </div>
</body>
</html>
