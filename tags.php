<?php include('includes/init.php');
$current_page_id="tags";

$go="yes";
if (isset($_POST['delete']) || isset($_POST['add']) || isset($_POST['add1'])) {
  $imgid = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM images WHERE id= :id";
  $params = array(':id' => $imgid);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if (isset($records) and !empty($records)) {

    $progress="failure";
    if (isset($_POST['delete'])) {
      $id1 = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);

      $sql = "SELECT * FROM images_tags WHERE images_id= :id2 AND tags_id= :id1";
      $params = array(':id1' => $id1, ':id2' => $imgid);
      $records = exec_sql_query($db, $sql, $params)->fetchAll();
      if (isset($records) and !empty($records)) {
        $sql1 = "DELETE FROM images_tags WHERE images_tags.tags_id= :id";
        $params = array(':id' => $id1);
        exec_sql_query($db, $sql1, $params);
        $progress="success";}
      }

      if (isset($_POST['add'])) {
        $id2 = filter_input(INPUT_POST, 'tag1', FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM tags WHERE id= :id";
        $params = array(':id' => $id2);
        $records = exec_sql_query($db, $sql, $params)->fetchAll();
        if (isset($records) and !empty($records)) {

          $sql = "SELECT * FROM images_tags WHERE images_id= :id2
          AND tags_id= :id1";
          $params = array(':id1' => $id2, ':id2' => $imgid);
          $records = exec_sql_query($db, $sql, $params)->fetchAll();
          if (isset($records) and !empty($records)) {$progress="duplicate";}
          else {
            $sql1 = "INSERT INTO images_tags(tags_id, images_id)
            VALUES(:tagid, :imgid)";
            $params = array(':tagid' => $id2, ':imgid' => $imgid);
            exec_sql_query($db, $sql1, $params);
            $progress="success";
          }}}

          if (isset($_POST['add1'])) {
            $name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
            $name=strtolower(trim($name));

            $sql = "SELECT * FROM tags WHERE tag_title=:id";
            $params = array(':id' => $name);
            $records = exec_sql_query($db, $sql, $params)->fetchAll();
            if (isset($records) and !empty($records)) {
            } else {
              $sql1 = "INSERT INTO tags(tag_title) VALUES(:name)";
              $params1 = array(':name' => $name);
              exec_sql_query($db, $sql1, $params1);

              $tagid = $db->lastInsertId("id");
              $sql2 = "INSERT INTO images_tags(tags_id, images_id)
              VALUES(:tagid, :imgid)";
              $params2 = array(':tagid' => $tagid, ':imgid' => $imgid);
              exec_sql_query($db, $sql2, $params2);
              $progress="success";
            }}
          } else {
            $go="no";}}
            ?>
            <!DOCTYPE html>
            <html>

            <head>
              <meta charset="UTF-8" />
              <meta name="viewport" content="width=device-width, initial-scale=1"/>
              <link rel="stylesheet" type="text/css" href="styles/all.css"
              media="all" />

              <title>Home</title>
            </head>

            <body>

              <?php include("includes/header.php");

              if (isset($_GET['id'])) {
                $imgid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

                $sql = "SELECT * FROM images WHERE id= :id";
                $params = array(':id' => $imgid);
                $records = exec_sql_query($db, $sql, $params)->fetchAll();
                if (isset($records) and !empty($records)) {

                  $sql = "SELECT tags.id, tags.tag_title FROM images_tags
                  INNER JOIN tags ON images_tags.tags_id = tags.id INNER JOIN
                  images ON images_tags.tags_id = images.id WHERE
                  images_tags.images_id=:id;";
                  $params = array(':id' => $imgid);
                  $records = exec_sql_query($db, $sql, $params)->fetchAll();

                  if (is_uploader($imgid)) {

                    echo(" <div class='homepageContent2'>
                    <form id='tag_delete' action='tags.php' method='post'>
                    <p><label>Delete Tag From This Image:</label>
                    <input type='hidden' name='image' value=".
                    htmlspecialchars($imgid).">
                    <p> <select name='tag'>");

                    if (isset($records) and !empty($records)) {
                      foreach($records as $record) {
                        $name=$record["tag_title"];
                        $id= $record["id"];
                        echo "<option value=".htmlspecialchars($id).">".
                        htmlspecialchars($name)."</option>";
                      }
                      echo("</select> </p>
                      <button name='delete' type='submit'>Delete Tag</button>
                      </form>"); echo "</div>";
                    } else {
                      echo "<option value='None'>No tags to select from</option>";
                      echo "</select></form></div>";
                    }

                  }

                  echo(" <div class='homepageContent2'>
                  <form id='tag_add' action='tags.php' method='post'>
                  <p><label>Add Existing Tag To This Image:</label>");
                  echo "<input type='hidden' name='image' value=".
                  htmlspecialchars($imgid).">";
                  echo("<p> <select name='tag1'>");


                  $sql2 = "SELECT images_tags.tags_id FROM images_tags WHERE
                  images_tags.images_id= :id";
                  $params2 = array(':id' => $imgid);
                  $list="(";
                  $records2 = exec_sql_query($db, $sql2, $params2)->fetchAll();
                  if (isset($records2) and !empty($records2)) {
                    foreach($records2 as $record2) {
                      $list= $list.$record2["tags_id"]." ,";
                    }
                    $list= substr($list, 0, strlen($list)-2);
                    $list= $list.")";

                    $sql1 = "SELECT tags.id, tags.tag_title FROM tags WHERE
                    tags.id NOT IN".$list;
                    $params1 = array();
                    $records1 = exec_sql_query($db, $sql1, $params1)->fetchAll();
                  }
                  else {
                    $sql1 = "SELECT * FROM tags";
                    $params1 = array();
                    $records1 = exec_sql_query($db, $sql1, $params1)->fetchAll();
                  }

                  if (isset($records1) and !empty($records1)) {
                    foreach($records1 as $record1) {
                      $name1=$record1["tag_title"];
                      $id1= $record1["id"];
                      echo "<option value=".htmlspecialchars($id1).">".
                      htmlspecialchars($name1)."</option>";
                    }
                  } else {
                    echo "<option value='None'>No tags to select from</option>";}

                    echo("</select> </p>
                    <button name='add' type='submit'>Add Old Tag</button>
                    </form></div>");

                    echo(" <div class='homepageContent1'> Can't find a tag
                    you are looking for? Use form below to add a new tag. </div>");

                    echo(" <div class='homepageContent2'>
                    <form id='add_new' action='tags.php' method='post'>
                    <p><label>Add New Tag To This Image:</label>
                    <p><input type='text' name='tag' required></p>
                    <input type='hidden' name='image' value=".
                    htmlspecialchars($imgid).">
                    <button name='add1' type='submit'>Add New Tag</button>
                    </form></div>");

                  } else { echo "<div class='homepageContent1'> Image doesn't exist.
                    You can go back to <a href='gallery.php'>Gallery</a> to select
                    another image.</div>";}}
                  else{

                    if($go=="yes"){
                      if (isset($_POST['delete'])) {
                        if($progress=="success"){
                          echo(" <div class='homepageContent1'>");
                          echo("The specified tag has been deleted. You can go
                          back to <a href='gallery.php'>Gallery</a> now or can
                          edit more <a href='tags.php?id=".htmlspecialchars($imgid).
                          "'>Tags</a>.</div>"); }
                          else {
                            echo(" <div class='homepageContent1'>");
                            echo("The specified tag has not been deleted, as it
                            doesn't exist in the image selected. Go back to <a
                            href='gallery.php'>Gallery</a> now or can edit more
                            <a href='tags.php?id=".htmlspecialchars($imgid)."'>
                            Tags</a>.</div>"); }
                          }
                          if (isset($_POST['add'])) {
                            if($progress=="success"){
                              echo(" <div class='homepageContent1'>");
                              echo("The specified tag has been added. You can go
                              back to <a href='gallery.php'>Gallery</a> now or can
                              edit more <a href='tags.php?id=".
                              htmlspecialchars($imgid)."'>Tags</a>.</div>");}
                              elseif ($progress=="duplicate") {
                                echo(" <div class='homepageContent1'>");
                                echo("The specified tag has not been added, as
                                it already exists for given image. You can go back
                                to <a href='gallery.php'>Gallery</a> now or can
                                edit more <a href='tags.php?id=".
                                htmlspecialchars($imgid)."'>Tags</a>.</div>");}
                                else {
                                  echo(" <div class='homepageContent1'>");
                                  echo("The specified tag has not been added, as
                                  it doesn't exist in table. You can go back to
                                  <a href='gallery.php'>Gallery</a> now or can
                                  edit more <a href='tags.php?id=".
                                  htmlspecialchars($imgid)."'>Tags</a>.</div>");}
                                }
                                if (isset($_POST['add1'])) {
                                  if($progress=="success"){echo("
                                  <div class='homepageContent1'>");
                                    echo("The tag (".htmlspecialchars($name).")
                                    has been added. You can go back to
                                    <a href='gallery.php'>Gallery</a> now or can
                                    edit more <a href='tags.php?id=".
                                    htmlspecialchars($imgid)."'>Tags</a>.</div>");}
                                    else {
                                      echo(" <div class='homepageContent1'>");
                                      echo("The specified tag has not been added,
                                      as it already exists in table. You can use
                                      the Add Existing Tag To This Image Form in
                                      <a href='tags.php?id=".htmlspecialchars($imgid).
                                      "'>Tags</a> to add existing tags or can go
                                      back to <a href='gallery.php'>Gallery</a>
                                      now.</div>");
                                    }
                                  }
                                } else { echo "<div class='homepageContent1'>
                                  Image doesn't exist. You can go back to
                                  <a href='gallery.php'>Gallery</a> to select
                                  another image.</div>";}
                              }
                              ?>

                            </body>
                            </html>
