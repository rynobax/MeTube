<!-- 
function.php

Useful functions that can be used throughout the website.  Included in global_includes

TODO:
-->
<?php
global $db;
include_once "config.php";

function user_exist_check ($username, $password){
	global $db;
	$query = "select * from user where username='$username'";
	$result = mysqli_query( $db, $query );
	if (!$result){
		die ("user_exist_check() failed. Could not query the database: <br />". mysqli_error( $db));
	}	
	else {
		$row = mysqli_fetch_assoc($result);
		if($row == 0){
			$query = "insert into user (username, password, join_datetime) values ('$username','$password', NOW())";
			$insert = mysqli_query(  $db, $query );
			if(!$insert)
				die ("Could not insert into the database: <br />". mysqli_error( $db));		
			$query = "SELECT id FROM user WHERE username = '$username'";
			$result = mysqli_query($db,$query);
			if(!$result)
				die ("Could not query into the user database: <br />". mysqli_error( $db));		
			$result_row = mysqli_fetch_row($result);
			$u_id = $result_row[0];
			$query = "INSERT INTO playlist(title, user_id, creation_datetime) VALUES('Favorites', 
			'$u_id', NOW())";
			$insert = mysqli_query($db,$query);
			if(!$insert)
				die ("Could not insert into the database: <br />". mysqli_error( $db));		
			return 1;
		}
		else{
			return 2;
		}
	}
}


function user_pass_check($username, $password)
{
	global $db;
	$query = "select password from user where username='$username'";
	$result = mysqli_query($db,$query );
		
	if (!$result)
	{
	   die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error($db));
	}
	else{
		$row = mysqli_fetch_row($result);
		if(strcmp($row[0],$password))
			return 2; //wrong password
		else 
			return 0; //Checked.
	}	
}

function user_get_id($username)
{
	global $db;
	$query = "select id from user where username='$username'";
	echo  $query;
	$result = mysqli_query( $db,$query );
		
	if (!$result)
	{
	   die ("user_get_id() failed. Could not query the database: <br />". mysqli_error($db));
	}
	else{
		$row = mysqli_fetch_row($result);
		return $row[0];
	}	
}

function updateMediaTime($mediaid)
{
	global $db;
	$query = "	update  media set last_viewed_datetime=NOW()
   						WHERE id = '$mediaid'
					";
					 // Run the query created above on the database through the connection
    $result = mysqli_query( $db,$query );
	if (!$result)
	{
	   die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error($db));
	}
}

function upload_error($result)
{
	//view error description in http://us2.php.net/manual/en/features.file-upload.errors.php
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	}
}

function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

function user_is_logged_in(){
	if(isset($_SESSION['id'])){
		return TRUE;
	}
	return FALSE;
}

function subscribe($sub_id)
{
	global $db;
	if(user_is_logged_in() && $_SESSION['id'] != $sub_id)
	{
		$query = "SELECT subscriber_id FROM subscriber WHERE subscribeie_id = '$sub_id' AND "
			."subscriber_id = '".$_SESSION['id']."'";
		$result = mysqli_query($db,$query);
		if (!$result){
	   die ("Could not query the subscriber table in the database: <br />". mysqli_error($db));
		}
		$result_row = mysqli_fetch_row($result); 
		$is_subbed = $result_row[0];
		echo "<form action=\"toggle_subscribe.php?id=$sub_id\" method=\"post\">";
		echo "<input ";
		if($is_subbed == $_SESSION['id']){ echo "class=\"btn btn-danger\"";}
		else{ echo "class=\"btn btn-success\"";}
		echo " name=\"submit\" type=\"submit\" value=\"";
		if($is_subbed == $_SESSION['id'])
			echo "Unsubscribe\">";
		else
			echo "Subscribe\">";
		echo "</form>";
	}
}

function do_tags($tags, $vid_id)
{
	global $db;
	$ready = str_replace(" ", ",", $tags);
	$all_tags = explode(",", $ready);
	foreach ($all_tags as &$tag)
	{
		$query = "SELECT tag FROM tag WHERE tag = '$tag' AND video_id = $vid_id";
		$result = mysqli_query($db,$query);
		if(!$result){
			die ("$query Could not query the tag table in the database: <br />". mysqli_error($db));
		}
		$result_row = mysqli_fetch_row($result);
		$tag_res = $result_row[0];
		if($tag != $tag_res)
		{
			$insert = "INSERT INTO tag(video_id, tag) VALUES( '$vid_id', '$tag')";
			$result = mysqli_query($db,$insert);
			if(!$result){
				die ("Could not insert into the tag table in the database: <br />". mysqli_error($db));
			}
		}
	}
}

function favorite($vid_id)
{
	global $db;
	if(user_is_logged_in())
	{
		$u_id = $_SESSION['id'];
		$query = "SELECT id FROM playlist WHERE user_id = '$u_id' AND title = 'Favorites'";
		$result = mysqli_query($db,$query);
		if (!$result){
	   die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
		}
		$result_row = mysqli_fetch_row($result); 
		$playlist_id = $result_row[0];
		$query = "SELECT video_id FROM playlist_entry WHERE playlist_id = '$playlist_id' AND video_id = '$vid_id'";
		$result = mysqli_query($db,$query);
		if (!$result){
	   die ("Could not query the playlist_entry table in the database: <br />". mysqli_error($db));
		}
		$result_row = mysqli_fetch_row($result); 
		$is_fav = $result_row[0];?>
		<form action="toggle_favorite.php?id=<?php echo $vid_id ?>" id="toggle_favorite" method="post">
		<input type="hidden" name="tog" value ="<?php echo $is_fav;?>">
		<input type="hidden" name="pid" value ="<?php echo $playlist_id;?>">
		</form>
		<button name="submit" type="submit" form="toggle_favorite" <?php
		if($is_fav == $vid_id)
			echo "class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\" ></span> Unfavorite";
		else
			echo "class=\"btn btn-success\"><span class=\"glyphicon glyphicon-star\" ></span> Favorite";
		echo "</button>";
	}
}

function playlists($vid_id)
{
	global $db;
	if(user_is_logged_in())
	{
		$u_id = $_SESSION['id'];
		$query = "SELECT playlist.id
							FROM playlist
							LEFT JOIN playlist_entry
							ON playlist.id = playlist_entry.playlist_id
							WHERE playlist.user_id = '$u_id' AND playlist.title != 'Favorites'";
		$result = mysqli_query($db, $query);
		if(!$result)
			die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
		$num_playlist = mysqli_num_rows($result);
		$query = "SELECT playlist.id, playlist.title
							FROM playlist 
							LEFT JOIN playlist_entry
							ON playlist.id = playlist_entry.playlist_id 
							WHERE playlist.user_id = '$u_id' AND playlist.title != 'Favorites'
							AND playlist_entry.video_id = '$vid_id'";
		$result = mysqli_query($db, $query);
		if(!$result)
			die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
		if (mysqli_num_rows($result)!=0)
		{?>
			<form action = "playlist_process.php" method="post">
			<input type="hidden" name="vid_id" value="<?php echo $vid_id; ?>"/>
			<select name="remove" id="remove">
			<?php
			while($result_row = mysqli_fetch_row($result))
			{
				$play_id = $result_row[0];
				$play_title = $result_row[1];
			
				 echo "<option value=\"$play_id\">$play_title</option>";
			}
			?>
			</select>
			<button value ="Remove" name="Remove" type="submit" class="btn btn-danger">
					<span class="glyphicon glyphicon-minus"></span> Remove
			</button> 
			</form>
		<?php
		}
		if(($num_playlist - mysqli_num_rows($result)) != 0)
		{
			$query = "SELECT playlist.id, playlist.title
								FROM playlist 
								LEFT JOIN playlist_entry
								ON playlist.id = playlist_entry.playlist_id
								WHERE playlist.user_id = '$u_id' AND title != 'Favorites'";
			$result = mysqli_query($db, $query);
			if(!$result)
				die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
			if (mysqli_num_rows($result)!=0)
			{?>
				<form id = "add_form" action = "playlist_process.php" method="post">
				<input type="hidden" name="vid_id" value="<?php echo $vid_id; ?>"/>
				<select name="add" id="add">
				<?php
				while($result_row = mysqli_fetch_row($result))
				{
					$play_id = $result_row[0];
					$play_title = $result_row[1];
					$new_query = "SELECT video_id FROM playlist_entry WHERE video_id = '$vid_id' 
												AND playlist_id = '$play_id'";
					$new_result = mysqli_query($db, $new_query);
					if(!$new_result)
						die ("Could not query the playlist table in the database: <br />". mysqli_error($db));
					$new_result_row = mysqli_fetch_row($new_result);
					if($new_result_row[0] != $vid_id)
				 		echo "<option value=\"$play_id\">$play_title</option>";
				}
				?>
				</select>
				<button value ="Add" name="Add" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-plus"></span> Add
				</button> 
				</form>
				<?php
			}
		}
	}
}
?>
