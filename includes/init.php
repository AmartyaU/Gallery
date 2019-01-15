<?php

$pages1 = array(
  "gallery" => "Gallery",
  "upload" => "Upload Image",
  "logout" => "Log out"
);

$pages2 = array(
  "index" => "Log in",
  "gallery" => "Gallery",
);


$messages = array();

function record_message($message) {
  global $messages;
  array_push($messages, $message);
}

function print_messages() {
  global $messages;
  foreach ($messages as $message) {
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
  }
}

function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' .
  $exception->getMessage()) . '</strong></p>';
}

function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        handle_db_error($exception);
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

$db = open_or_init_sqlite_db("website.sqlite", "init/init.sql");

function check_login() {
  global $db;

  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $sql = "SELECT * FROM accounts WHERE session = :session";
    $params = array(
      ':session' => $session
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $account = $records[0];
      return $account['username'];
    }
  }
  return NULL;
}

function log_in($username, $password) {
  global $db;

  if ($username && $password) {
    $sql = "SELECT * FROM accounts WHERE username = :username;";
    $params = array(
      ':username' => $username
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $account = $records[0];

      if ( password_verify($password, $account['password']) ) {

        $session = uniqid();
        $sql = "UPDATE accounts SET session = :session WHERE id = :user_id;";
        $params = array(
          ':user_id' => $account['id'],
          ':session' => $session
        );
        $result = exec_sql_query($db, $sql, $params);
        if ($result) {

          setcookie("session", $session, time()+3600);

          record_message("Logged in as $username.
          If you want to sign in as a different user, log out and sign in again.
          Else you can access all the pages in the nav bar above.");
          return $username;
        } else {
          record_message("Log in failed.");
        }
      } else {
        record_message("Invalid username or password.");
      }
    } else {
      record_message("Invalid username or password.");
    }
  } else {
    record_message("No username or password given.");
  }
  return NULL;
}

function log_out() {
  global $current_user;
  global $db;

  if ($current_user) {
    $sql = "UPDATE accounts SET session = :session WHERE username = :username;";
    $params = array(
      ':username' => $current_user,
      ':session' => NULL
    );
    if (!exec_sql_query($db, $sql, $params)) {
      record_message("Log out failed.");
    }
  }

  setcookie("session", "", time()-3600);
  $current_user = NULL;
}

if (isset($_POST['login'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $current_user=log_in($username, $password);
} else{
  $current_user = check_login();
}

function is_uploader($imgid) {
  global $current_user;
  global $db;

  $sql = "SELECT * FROM images WHERE id= :id";
  $params = array(':id' => $imgid);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if (isset($records) and !empty($records)) {

    $sql = "SELECT uploader FROM images WHERE id=:id;";
    $params = array(':id' => $imgid);
    $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    if($records[0]==$current_user) return TRUE;
    else { return FALSE;} }

  }

  ?>
