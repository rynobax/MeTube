<?php
	include 'header.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id,
	},
	function(message) 
    { }
 	);
} 
</script>
<title>Media browse</title>
</head>

<body>
<?php
	global $db;
	//searching
	$search_query = $_GET["search"];
	$category_query = $_GET["category"];
	if($search_query == ""){
		$query = "SELECT id, title, path from media";
		if($category_query != "All")
			$query = $query." WHERE category = '".$category_query."'";
	}
	else{
		$all_search_query = explode(" ", $search_query);
		$query = "SELECT DISTINCT media.id, media.title, media.path 
			FROM media
			LEFT JOIN user
			ON media.user_id = user.id
			LEFT JOIN tag
			ON tag.video_id = media.id
			WHERE (";
		$sqcount = 0;
			foreach ($all_search_query as &$sq)
			{
				if($sqcount == 0)
				{
					$query = $query."(media.title LIKE '%$sq%'
      		OR tag.tag LIKE '%$sq%'
      		OR user.username LIKE '%$sq%')";
				}
				else
				{
					$query = $query."AND (media.title LIKE '%$sq%'
          OR tag.tag LIKE '%$sq%'
          OR user.username LIKE '%$sq%')";
				}
				$sqcount++;
			}
		$query = $query.")";
		if($category_query != "All")
			$query = $query." AND media.category = '".$category_query."'";
	}
	$result = mysqli_query( $db,$query );
	if (!$result){
	   die ($query."Could not query the media table in the database: <br />". mysqli_error($db));
	}
?>

<style>
.jumbotron
{
	padding-top: 0px;
}
</style>
<div class="container">
  <div class="jumbotron">
    <h2 style="text:center"><b>
    	<?php 
    	echo "All media";
    	if($category_query != "" AND $category_query != "All"){
    		echo " in the category $category_query";
    	}
    	if($search_query != ""){
    		echo " containing \"$search_query\"";
    	}
    	?>
    </b></h2>
		<?php
			while ($result_row = mysqli_fetch_row($result)) //filename, username, type, mediaid, path
			{ 
				$media_id = $result_row[0];
				$media_title = $result_row[1];
				$path = $result_row[2];
		?>
        	 <table width="100%" class="list-inline">
	    	<td width="85%">
				<a href="media.php?id=<?php echo $media_id;?>" class="list-group-item">
					<h3 class="list-group-item-heading"><?php echo $media_title;?></h3>
				</a>
			</td>
			<td width="15%">
				<img src="<?php echo $path; ?>" class="img-responsive img-thumbnail" alt="<?php echo $media_title; ?>">
			</td>
			</table>
        	<?php
			}
		?>


		</div>
   </div>
</body>
</html>
