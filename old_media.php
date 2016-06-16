\<!-- 
media.php

Displays a media item

TODO:
-->
<?php
	include "header.php";
	include_once "function.php";
	if(isset($_POST['id']) && isset($_POST['description'])){
		$postid = $_POST['id'];
		$postdescription = $_POST['description'];
		$query = "UPDATE media 
					SET description = '$postdescription '
					WHERE id = $postid";
		$result = mysqli_query($db, $query );
		if (!$result){
		   die ("Could not query the media table in the database: <br>". mysqli_error($db));
		}
		echo "success";
		return;
	}
?>
<title>Media Viewer</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script language="Javascript">
function saveEdits(id) {
	//get the editable element
	var editElem = document.getElementById("edit");
	//get the edited element content
	var newBody = editElem.innerHTML;
	$.post("media.php",
	{
		id: id,
		description: newBody
	},
	function(data, status){
        window.location.href = "media.php?id=" + id;
    });
}
</script>
</head>

<body>
<?php
if(isset($_GET['id'])) {
	global $db;
	$query = "SELECT title, path, type, description, user_id, comments FROM media WHERE id='".$_GET['id']."'";
	$result = mysqli_query($db, $query );
	$result_row = mysqli_fetch_row($result);
	
	updateMediaTime($_GET['id']);
	
	$filename=$result_row[0];
	$filepath=$result_row[1]; 
	$type=$result_row[2];
	$description = $result_row[3];
	$submitter = $result_row[4];
	$bool_com = $result_row[5];
	
	$query = "SELECT username FROM user WHERE id = $submitter";
	$result = mysqli_query( $db,$query );
	if (!$result){
	   die ($query."Could not query the comment table in the database: <br />". mysqli_error($db));
	}
	$result_row = mysqli_fetch_row($result);
	$sname = $result_row[0];
	
	if(substr($type,0,5)=="image") //view image
	{
		echo "<p>Viewing Picture: ";
		echo $filename;
		echo "</br>Submitted by: <a href=\"user.php?id=$submitter\">$sname</a>";
		echo "</p><img src='".$filepath."'/>";
	}
	else //view movie
	{	
?>
	<p>Viewing Video:<?php echo $filename;?></p>
	<?php echo "Submitted by: <a href=\"user.php?id=$submitter\">$sname</a>";  ?>   
    <object id="MediaPlayer" width=320 height=286 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

<param name="filename" value="<?php echo $filename?>">
		

<param name="Showcontrols" value="True">
<param name="autoStart" value="True">

<embed type="application/x-mplayer2" src="<?php echo $filepath;  ?>" name="MediaPlayer" width=320 height=240></embed>

</object>

          
          
          
       
              
<?php
	}
	$vid_id = $_GET['id'];
	subscribe($submitter);
	favorite($vid_id);
	playlists($vid_id);
	$query = "SELECT user_id FROM media WHERE id='".$_GET['id']."'"; 
	$result = mysqli_query($db,$query);
	if (!$result){
		// query failed, should never happen outside of early testing
	   die ("Could not query the media table in the database: <br />". mysqli_error($db));
	}
	$result_row = mysqli_fetch_row($result);
	if(user_is_logged_in() && $_SESSION['id'] == $result_row[0])
		$owner = true;
	else
		$owner = false;
	echo "<p>Description:</br></p><p>";
	if($owner)
	{
		if(isset($_GET['edit'])) 
			echo "<div name= \"edit\" id=\"edit\" contenteditable=\"true\">";
		if(!empty($description))
			echo $description;
		else
			echo "You have not set a description.";
		if(isset($_GET['edit'])) 
			echo "</div>";
		echo "</p>";
		if(!isset($_GET['edit']))
			echo "<p><a href=\"?id=".$_GET['id']."&edit=1\">Click to edit the description!</a></p>";
		else
			echo "<input type=\"button\" value=\"Save\" onclick=\"saveEdits(".$_GET['id'].")\"/>";
		//tag stuff
		echo "</br>Tags:</br>";
		$query = "SELECT id, tag FROM tag WHERE video_id = $vid_id";
		$result = mysqli_query($db,$query);
		if (!$result){
			 die ("Could not query the tag table in the database: <br />". mysqli_error($db));
		}
		$tag_count = 0;
		echo "<table><tr>";
		while($result_row = mysqli_fetch_row($result))
		{
			$tag_id = $result_row[0];
			$tag = $result_row[1];
			echo "<td><form method=\"post\" action=\"remove_tag.php\">";
			echo "<input type=\"hidden\" name=\"id\" value=\"$vid_id\">";
			echo "<input type=\"hidden\" name=\"tag\" value=\"$tag_id\">";
			echo "$tag";
			echo "<input type=\"Submit\" value=\"X\" name=\"X\"/>";
			echo "</form></td>";
			if($tag_count == 9)
				echo "</tr>";
			$tag_count++;
		}
		echo"</tr></table>";
		if(isset($_GET['tags']))
		{
				echo "<form method =\"post\" action=\"add_tags.php?id=$vid_id\">";
				?>
				<textarea rows="4" cols="50" name="tags" id="tags"></textarea>
				<input value="Submit" name="submit" type="submit" />
				</form>
				<?php
		}
		else
		{
			echo "<form method=\"post\" action=\"media.php?id=$vid_id&tags=0\"><input type=\"submit\" name=\"submit\" value=\"+\"/></form>";
		}
		echo "</br>";	
	}

	else	
	{
		if(!empty($description))
			echo $description;
		else
			echo "There is no description.";
		echo "</p>";
	}
	echo "<p>Comments: </br></p>";
	if(user_is_logged_in() && $_SESSION['id'] == $result_row[0])
	{
		echo "<form method=\"post\" action=\"toggle_comments.php?id=".$_GET['id']."\" >"
		?>
		<p>Toggle Comments:</p>
		<input type="submit" name="submit" value="<?php if($bool_com)
			echo "Disable";
		else	
			echo "Enable";?>">
		</form>
		<?php
	}
	if($bool_com)
	{
		if(user_is_logged_in())
		{
			echo "<p>
			<form method=\"post\" action=\"comment_process.php?v_id=".$_GET['id']."\" >";
			?>
			<textarea rows="4" cols="50" name="comment" id="comment"></textarea></br>
			<input value="Submit" name="submit" type="submit" />
			</form>
			</p>
			<?php
		}
		$query = "SELECT user_id, content, creation_datetime FROM comment WHERE video_id =".$_GET['id']." ORDER BY creation_datetime ASC";
		$result = mysqli_query( $db,$query );
		if (!$result){
		   die ($query."Could not query the comment table in the database: <br />". mysqli_error($db));
		}
		while($result_row = mysqli_fetch_row($result)){
			$u_id = $result_row[0];
			$content = $result_row[1];
			$c_time = $result_row[2];
			$query = "SELECT username FROM user WHERE id = $u_id";
			$result2 = mysqli_query( $db,$query );
			if (!$result){
			   die ($query."Could not query the comment table in the database: <br />". mysqli_error($db));
			}
			$result_row2 = mysqli_fetch_row($result2);
			$uname = $result_row2[0];
			?>
			<tr valign="top">			
				<td>
					<?php 
						echo $uname;
					?>
				</td>
				<td>
					<?php 
						echo $c_time;
					?>
					</br>
				</td>
				<td>
					<?php 
						echo $content;
					?>
					</br></br>
				</td>
			</tr>
			<?php
		}
	}
	else{
		echo "<p>Comments are disabled.</p>";
	}
}
else
{
if(isset($_GET['result']))
{
	$res = $_GET['result'];
	if($res > 0 && $res <= 4)
		echo "File error. File was not uploaded";
	else if($res == 5)
		echo "A file that you uploaded has the same name, try changing the filename. File was not 	
			uploaded.";
	else if($res == 6)
		echo "Failed to move file from temporary directory. File was not uploaded.";
	else
		echo "File was successfully uploaded.";
}
else
{
?>
<meta http-equiv="refresh" content="0;url=browse_media.php?category=All&search=">
<?php
}
}
?>
</body>
</html>
