<?php include('includes/init.php');
$current_page_id="gallery";
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
  <div class="body">

    <?php
    print_messages();
    ?>

    <?php include("includes/header.php");?>
    <div id="parent_container">
      <?php
      echo"<div class='home'>";
      if (!isset($_GET['tag_id'])) {
        $sql = "SELECT * FROM images";
        $params = array();
        $records = exec_sql_query($db, $sql, $params)->fetchAll();

        if (isset($records) and !empty($records)) {
          foreach($records as $record) {
            $id=$record["id"];
            echo "<a class='image' href= 'pic.php?imgid=".htmlspecialchars($id).
            "&title=".htmlspecialchars($record["image_title"])."&extension=".
            htmlspecialchars($record["image_ext"])."'> <img alt='Gallery Image'
            src= uploads/images/".htmlspecialchars($id).".".
            htmlspecialchars($record["image_ext"])."></a>";
          }
        } else {
          echo ("No images in pic.");
        }}
        else {

          $tagid = filter_input(INPUT_GET, 'tag_id', FILTER_SANITIZE_STRING);
          $sql = "SELECT * FROM tags WHERE id= :id";
          $params = array(':id' => $tagid);
          $records = exec_sql_query($db, $sql, $params)->fetchAll();
          if (isset($records) and !empty($records)) {

            $sql = "SELECT images.id, images.image_title, images.image_ext
            FROM images_tags INNER JOIN images ON images_tags.images_id =
            images.id WHERE images_tags.tags_id= :tagid";

            $params = array(':tagid' => $tagid);
            $records = exec_sql_query($db, $sql, $params)->fetchAll();

            if (isset($records) and !empty($records)) {
              foreach($records as $record) {
                $id=$record["id"];
                echo "<a class='image' href= 'pic.php?imgid=".htmlspecialchars($id).
                "&title=".htmlspecialchars($record["image_title"])."&extension=".
                htmlspecialchars($record["image_ext"])."'> <img alt='Gallery Image'
                src= uploads/images/".htmlspecialchars($id).".".
                htmlspecialchars($record["image_ext"]). "></a>";
              }
            } else {
              echo ("<div class='homepageContent3'>No images for this tag in pic.
              Please search some other tag.</div>");}
            } else { echo "<div class='homepageContent3'> Tag doesn't exist.
                     Please search some other tag.</div>";}
          }
          echo"</div>";
          ?>

          <div class="homepageContent">
            <p> You can use tags below to search for a specific category of images.
            </p>

            <?php

            echo("<form id='search' action='gallery.php' method='get'>");
            echo "<button class='edit'>All Images</button>";
            $sql = "SELECT * FROM tags";
            $params = array();
            $records = exec_sql_query($db, $sql, $params)->fetchAll();

            if (isset($records) and !empty($records)) {
              foreach($records as $record) {
                $id=$record["id"];
                $name=$record["tag_title"];
                echo "<button class='edit' name='tag_id' value=".
                htmlspecialchars($id).">".htmlspecialchars($name)."</button>";
              } echo "</form>";
            } else {
              echo ("No tags present.");
            }

            ?>
          </div>
        </div>

        <div class="line"></div>
      </div>
      <footer class="footer"><div class="bottom"></div></footer>
    </body>
    </html>
