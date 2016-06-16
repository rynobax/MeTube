<?php
include 'header.php';

if(isset($_GET['id'])){
	$playlist_id = $_GET['id'];
}else{
	header('Location: index.php');
} 
if(isset($_POST['delete_playlist_media_id'])){
	$video_id = $_POST['delete_playlist_media_id'];
	$query = "DELETE FROM playlist_entry WHERE video_id = '$video_id'";
	$result = mysqli_query($db, $query);
	if(!$result)
		die ("Could not query playist: <br />". mysqli_error($db));
}

$query = "SELECT title, user_id FROM playlist WHERE id = '$playlist_id'";
$result = mysqli_query($db, $query);
if(!$result)
	die ("Could not query playist: <br />". mysqli_error($db));
$row = mysqli_fetch_row($result);
$playlist_title = ucwords ($row[0]);
$id = $row[1];
if(user_is_logged_in() and $_SESSION['id'] == $id)
	$owner = true;
else
	$owner = false;

function editing(){
	if(isset($_POST['edit'])) return true;
	return false;
}

echo "<title>$playlist_title</title>";
?>
<div class="container">
	<div class="jumbotron">
		<?php if($owner AND !editing()){?>
		<div class="pull-right">
        	<button class="btn btn-lg btn-danger btn-block" form="edit_form" type="submit">Edit</button>
        	<form action="playlist.php?id=<?php echo $playlist_id?>" method="post" id="edit_form">
        		<input type = "hidden" name = "edit" value="1"/>
        	</form>
		</div>
		<?php } ?>
		<h1><?php echo $playlist_title; ?></h1>
		<div class="list-group">
<?php
$query = "SELECT playlist_entry.video_id, media.title, media.type
					FROM playlist_entry
					LEFT JOIN media
					ON playlist_entry.video_id = media.id
					AND playlist_entry.playlist_id = '$playlist_id'";
$res_entry = mysqli_query($db, $query);
if(!$res_entry)
	die ("Could not query the playlist_entry and media tables in the database: <br />". mysqli_error($db));
while($res_entry_row = mysqli_fetch_row($res_entry))
{
	$media_id = $res_entry_row[0];
	$media_title = $res_entry_row[1];
	$media_type = $res_entry_row[2];
	if($media_title)
	{
	?>
	<table width="100%" class="list-inline">
    	<td width="100%">
			<a href="media.php?id=<?php echo $media_id;?>" class="list-group-item">
				<h2 class="list-group-item-heading"><?php echo $media_title;?></h2>
				<i><div class="list-group-item-text"><?php echo $media_type;?></div></i>
			</a>
		</td>
		<?php if(editing()){?>
		<td width="0%">
			<button class="btn btn-lg btn-danger" form="delete" name="delete" type="submit">
				<span class="glyphicon glyphicon-remove" ></span>
			</button>
        	<form action="playlist.php?id=<?php echo $playlist_id?>" method="post" id="delete">
        		<input type = "hidden" name = "delete_playlist_media_id" value="<?php echo $media_id ?>"/>
        		<input type = "hidden" name = "edit" value="1"/>
        	</form>
		</td>
		<?php } ?>
	</table>
	<?php
	}
}
?>
		</div>
	</div>
</div>