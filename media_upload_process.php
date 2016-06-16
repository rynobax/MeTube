<!-- 
media_upload_process.php

Uploads media

TODO:
-->
<?php
include_once "function.php";
session_start();

/******************************************************
*
* upload document from user
*
*******************************************************/

$username=$_SESSION['username'];
$user_id=$_SESSION['id'];
global $db;
//Create Directory if doesn't exist
if(!file_exists('uploads/')){
	mkdir('uploads/');
	chmod('uploads', 0755);
}
$dirfile = 'uploads/'.$username.'/';
if(!file_exists($dirfile))
	mkdir($dirfile);
	chmod($dirfile, 0755);
	if($_FILES["file"]["error"] > 0 )
	{ 	$result=$_FILES["file"]["error"];} //error from 1-4
	else
	{
		$upfile = $dirfile.urlencode($_FILES["file"]["name"]);
	  while(file_exists($upfile))
		{
			$rand = rand();
			$upfile = $dirfile.urlencode($rand."".$_FILES["file"]["name"]);
		}
	  if(file_exists($upfile))
	  {
	  	$result="5"; //The file has been uploaded.
	  }
	  else{
			if(is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile))
				{
					$result="6"; //Failed to move file from temporary directory
				}
				else /*Successfully upload file*/
				{
					//insert into media table
					$insert = "insert into media(title, user_id, type, path,category,description,comments)".
							  "values(
							  	'".$_POST["title"]."',
							  	$user_id,
							  	'".$_FILES["file"]["type"]."',
							  	'$upfile',
								'".$_POST["category"]."',
								'".mysqli_real_escape_string($db, $_POST["description"])."',
								'".$_POST["comments"]."'
							  	)";
					$queryresult = mysqli_query($db,$insert)
						  or die("Insert into Media error in media_upload_process.php\n\nSQL was:\n$insert\n\n" .mysqli_error($db));
					$result="0";
					chmod($upfile, 0644);
					$query = "SELECT id FROM media WHERE path = '$upfile'";
					$queryresult = mysqli_query($db,$query);
					if(!$queryresult)
						  die("Query into Media error in media_upload_process.php\n\nSQL was:\n$query\n\n" .mysqli_error($db));
					$result_row = mysqli_fetch_row($queryresult); 
					$media_id = $result_row[0];
					do_tags($_POST["tags"], $media_id);
				}
			}
			else  
			{
					$result="7"; //upload file failed
			}
		}
	}
	
	//You can process the error code of the $result here.
if(isset($media_id))
{
?>
	<meta http-equiv="refresh" content="0;url=media.php?id=<?php echo $media_id;?>">
<?php
}
else
{
?>

<meta http-equiv="refresh" content="0;url=media.php?result=<?php echo $result;?>">

<?php
}
?>
