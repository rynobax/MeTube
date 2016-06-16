<!-- 
global_includes.php

Should be the first part of every page.  Included in header.php

TODO:
-->

<!DOCTYPE html>

<!-- Global CSS Includes -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

<!-- Global js Includes -->
<script type="text/javascript" src="js/jquery-2.2.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php include_once "function.php"; ?>

<style>
.jumbotron {
  text-align: center;
  border-bottom: 1px solid #e5e5e5;
}
@media (min-width: 768px) {
  .container {
    max-width: 730px;
  }
}
.container-narrow > hr {
  margin: 30px 0;
}
</style>