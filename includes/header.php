<header>

  <div id="menu">
    <ul>
      <?php

      if ($current_user) {
        $pages=$pages1;
      } else {
        $pages=$pages2;
      }
      foreach($pages as $page_id => $page_name) {
        if ($page_id == $current_page_id) {
          $css_id = "class='active'";
        } else {
          $css_id = "";
        }
        echo "<li><a " . htmlspecialchars($css_id) . " href='" .
        htmlspecialchars($page_id). ".php'>". htmlspecialchars($page_name).
        "</a></li>";
      }
      ?>
    </ul>
    <p id="Logo">
      <?php
      if ($current_user) {
        echo "Logged in as ". htmlspecialchars($current_user);
      } else {
        echo "Berserk Gallery";
      }
      ?>
    </p>
  </div>
</header>
