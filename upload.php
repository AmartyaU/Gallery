<?php include('includes/init.php');
$current_page_id="upload";

const MAX_FILE_SIZE = 10000000;
const BOX_UPLOADS_PATH = "uploads/images/";
$process="failure";
$valid_ext = array(
  "image/gif",
  "image/png",
  "image/jpeg",
  "image/pjpeg",);

  if (isset($_POST["submit_upload"])) {
    $upload_info = $_FILES["box_file"];

    if ($upload_info['error'] == UPLOAD_ERR_OK) {
      $upload_name = trim(basename($upload_info["name"]));
      $upload_ext = trim(strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) ));

      if (in_array($upload_info["type"], $valid_ext)) {

        if(($upload_info['size'] > MAX_FILE_SIZE)) {
          array_push($messages, "Image you uploaded is too large. Max file
          size is 10MB. Reupload a smaller sized file. ");
        } else {

          $sql = "INSERT INTO images(image_title, image_ext, uploader) VALUES
          (:title, :ext, :uploader);";
          $params = array(
            ':title' => $upload_name,
            ':ext' => $upload_ext,
            ':uploader' => $current_user
          );
          $result = exec_sql_query($db, $sql, $params);

          if ($result) {
            $file_id = $db->lastInsertId("id");
            if (move_uploaded_file($upload_info["tmp_name"], BOX_UPLOADS_PATH .
            "$file_id.$upload_ext")){
              array_push($messages, "Your file has been uploaded.");
              $process="success";
            } else { array_push($messages, "Could not upload file properly.");}
          } else {
            array_push($messages, "Failed to upload file.");
          }
        }} else {
          array_push($messages, "File you uploaded doesn't have a valid image
          extension(gif,png,jpeg,pjpeg). Reupload a correct image file.");
        }
      } else {
        array_push($messages, "Failed to upload file.");
      }
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

      <?php include("includes/header.php");

      if(!$current_user){
        echo ("<div class='homepageContent2'>Not authorised to view this page.
        <a href= 'index.php'>Login </a>to acces this page.</div>");
      } else {
        if (!isset($_POST["submit_upload"])){
          echo(" <div class='homepageContent2'>
          <form id='uploadFile' action='upload.php' method='post'
          enctype='multipart/form-data'>
          <p><label>Upload Image:</label>
          <p><input type='file' name='box_file' required></p>
          <button name='submit_upload' type='submit'>Upload</button>
          </form></div>");}
          else{
            echo(" <div class='homepageContent2'>");  print_messages();
            echo "Go <a href= upload.php> back </a> and upload another image. ";
            if ($process=="success"){ echo "You can edit your image
            <a href='pic.php?imgid=".htmlspecialchars($file_id)."&title=".
            htmlspecialchars($upload_name)."&extension=".htmlspecialchars($upload_ext).
            "'>here</a> now(add/delete tags or delete image).";}
            echo(" </div>");
          }


        }
        ?>

      </body>
      </html>
