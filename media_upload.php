<!-- 
media_upload.php

Uploads media

TODO:
-->
<?php
include 'header.php';
?>
<title>Media Upload</title>
</head>

<body>

<style>
/* Override default */
.container {
    width: 400px;
}
</style>
<div class="container">
  <form class="form-signin" method="post" action="media_upload_process.php" enctype="multipart/form-data">
    <h2 class="form-signin-heading">Upload A File (Max 20MB)</h2>
    <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
    
    <label for="file">Add a Media:</label> 
    <input name="file" type="file" size="50" />

    <label for="title">Title:</label>
    <input name="title" class="form-control" placeholder="Title" required>

    <div class="form-group">
      <label for="category">Category:</label>
      <select class="form-control" name="category" id="categories">
         <option value="Music">Music</option>
         <option value="Sports">Sports</option>
         <option value="Gaming">Gaming</option>
         <option value="Movies">Movies</option>
         <option value="TV Shows">TV Shows</option>
         <option value="News">News</option>
         <option value="Education">Education</option>
         <option value="Comedy">Comedy</option>
      </select>
    </div>

    <label for="description">Description:</label>
    <div class="form-group">
      <textarea class="form-control" rows="5" id="description" name="description"></textarea>
    </div>

    <div class="form-group">
      <label for="tags">Tags (seperate by comma):</label>
      <textarea class="form-control" rows="5" id="tags" name="tags"></textarea>
    </div>

    <label for="comments">Comments:&nbsp</label>
    <label class="radio-inline"><input type="radio" name="comments" value="1">Enabled</label>
    <label class="radio-inline"><input type="radio" name="comments" value="0">Disabled</label>

    <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="Upload"/>Upload</button>
  </form> 
</div>
</body>
</html>
