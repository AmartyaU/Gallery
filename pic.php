<?php include('includes/init.php');
$current_page_id="l";

if (isset($_POST['delete'])) {
  $process="failure";
  $imgid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $ext = filter_input(INPUT_POST, 'ext', FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM images WHERE id= :id";
  $params = array(':id' => $imgid);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if (isset($records) and !empty($records)) {

    $sql = "SELECT * FROM images WHERE id= :id AND image_ext= :ext";
    $params = array(':id' => $imgid, ':ext'=>$ext);
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if (isset($records) and !empty($records)) {
      $sql1 = "DELETE FROM images WHERE images.id= :id";
      $sql2 = "DELETE FROM images_tags WHERE images_tags.images_id= :id";
      $params = array(':id' => $imgid);
      exec_sql_query($db, $sql1, $params);
      exec_sql_query($db, $sql2, $params);
      $full_name= $imgid.".".$ext;
      unlink("uploads/images/".$full_name);
      $process="success";} else {$process="ext_failure";}}
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
      <div class="body">

        <?php include("includes/header.php");
        if (isset($_POST['delete'])) {
          if($process=="success"){
            echo"<div class='homepageContent2'>
            <p> The image you selected has been deleted. Go back to
            <a href='gallery.php'> Gallery</a> to view more images.
            </p>
            </div>";} elseif ($process=="ext_failure") {
              echo"<div class='homepageContent2'>
              <p> The image you selected could not be deleted as you provided
              a wrong extension. Go back to <a href='gallery.php'> Gallery </a>
              to view more images.
              </p>
              </div>";
            }else {
              echo"<div class='homepageContent2'>
              <p> The image you selected could not be deleted, as there's no
              such image in the gallery. Go back to <a href='gallery.php'>Gallery</a>
              to view more images.
              </p>
              </div>";
            }
          }
          else{
            echo"<div class='homepageContent2'>
            <p> You can see more information about the image you selected
            (name and tags) below. Go back to <a href='gallery.php'> Gallery</a>
            to view more images.
            </p>
            </div>";}
            ?>
            <?php

            if (isset($_GET['imgid'])) {
              $imgid = filter_input(INPUT_GET, 'imgid', FILTER_SANITIZE_STRING);

              $sql = "SELECT * FROM images WHERE id= :id";
              $params = array(':id' => $imgid);
              $records = exec_sql_query($db, $sql, $params)->fetchAll();
              if (isset($records) and !empty($records)) {

                $ext = filter_input(INPUT_GET, 'extension', FILTER_SANITIZE_STRING);
                $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING);

                $sql = "SELECT tags.id, tags.tag_title FROM images_tags INNER JOIN
                tags ON images_tags.tags_id = tags.id INNER JOIN images ON
                images_tags.tags_id = images.id WHERE images_tags.images_id=:id;";
                $params = array(':id' => $imgid);
                $records = exec_sql_query($db, $sql, $params)->fetchAll();

                echo "<h1>Image Title: ".htmlspecialchars($title)."</h1>";

                echo "<img alt='Gallery Image' src= uploads/images/".
                htmlspecialchars($imgid).".".htmlspecialchars($ext)."
                class='image1'/>";

                echo "<div class='parent'>";
                echo "<form id='tags' action='tags.php' method='get'>";
                echo "<input type='hidden' name='id' value=".
                htmlspecialchars($imgid).">";
                echo "<button class='delete1'>Edit Tags</button><p></form>";

                if (is_uploader($imgid)) {
                  echo "<form id='delete' action='pic.php' method='post'>";
                  echo "<input type='hidden' name='id' value=".
                  htmlspecialchars($imgid).">";
                  echo "<input type='hidden' name='ext' value=".
                  htmlspecialchars($ext).">";
                  echo "<button name='delete' class='delete2' type='submit'>
                  Delete Image</button></form>";
                }
                echo("<form id='search' action='gallery.php' method='get'>");
                echo"<label class='tag'>TAGS:</label>";
                echo "<button class='delete3'>All Images</button>";
                if (isset($records) and !empty($records)) {
                  foreach($records as $record) {
                    $id=$record["id"];
                    $name=$record["tag_title"];
                    echo "<button name='tag_id' class='delete3' value=".
                    htmlspecialchars($id).">".htmlspecialchars($name)."</button>";
                  }
                } echo "</form>";
              } else { echo "<div class='homepageContent1'> Image doesn't exist.
                You can go back to <a href='gallery.php'>Gallery</a> to select
                another image.</div>";}
            }
            ?>

            <div class="line"></div>
          </div>
          <footer class="footer"><?php include("includes/footer.php");?></footer>
        </div>
      </body>
      </html>
