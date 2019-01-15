<?php include('includes/init.php');
$current_page_id="logout";

log_out();
if (!$current_user) {
  record_message("You've been successfully logged out.");
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

  <title>Home</title>
</head>

<body>
  <?php include("includes/header.php");?>

  <div class="homepageContent2">
    <p>You have been succesfullly logged out! You can login <a href="index.php">
    here</a>
    </p>
  </div>


</body>
</html>
