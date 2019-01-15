<?php
include('includes/init.php');
$current_page_id="index";
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

  <?php include("includes/header.php");

  if(!$current_user){
    echo("<div class='homepageContent2'>
    <p>This is the login page for Berserk gallery. You need to be Logged
    In to be able to upload / delete images. You can Log In below.
    </p>
    </div>"); }
    ?>

    <div class= homepageContent2><?php
    print_messages();
    ?></div>

    <?php if(!$current_user){
      echo("<div class='homepageContent2'>
      <form id='form' action='index.php' method='post'>
      <fieldset>
      <legend>LOG IN FORM</legend>
      <p><label>Username:</label>
      <input type='text' name='username' required/></p>
      <p><label>Password:</label>
      <input type='password' name='password' required/></p>
      <button name='login' type='submit'>Log In</button>
      </fieldset>
      </form>
      </div>");
    }
    ?>


  </body>
  </html>
