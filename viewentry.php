<?php

require("config.php");

if (isset($_GET['id'])) {
  if (!is_numeric($_GET['id'])) {
     $error = 1;
  }

  if (isset($error) && $error == 1) {
    header("Location: " . $config_basedir);
  } else {
    $validentry = $_GET['id'];
  }
} else {
  $validentry = 0;
}

require("header.php");

if ($validentry == 0) {
  $sql = "SELECT Entries.*, Categories.CategoryName FROM Entries, Categories " .
         "WHERE Entries.CategoryID = Categories.CategoryID " .
         "ORDER BY DatePosted DESC " .
         "LIMIT 1;";
} else {
$sql = "SELECT Entries.*, Categories.CategoryName FROM Entries, Categories " .
       "WHERE Entries.CategoryID = Categories.CategoryID AND Entries.EntryID = " . $validentry .
       " ORDER BY DatePosted DESC " .
       "LIMIT 1;";
}

$entries_result = $db_handler->query($sql);
?>

<div class="container">

  <div class="starter-template">
  <?php
    while ($entry_row = $entries_result->fetch(PDO::FETCH_OBJ)) {
      echo "<h2>" . $entry_row->Subject . "</h2><br />";
      echo "<p class='lead'><i>In <a href='viewcat.php?id=" . $entry_row->CategoryID . "'>" . $entry_row->CategoryName . "</a> - Posted on " .
      date("F jS Y", strtotime($entry_row->DatePosted)) . "</i></p>";
      echo "<p class='lead'>" . nl2br($entry_row->Body) . "</p>";
    }
  ?>
  </div>

<?php
require("footer.php");
?>
