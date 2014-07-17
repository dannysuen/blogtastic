<?php

require("viewcat_header.php");


if (isset($_GET['id'])) {
  if (!is_numeric($_GET['id'])) {
    $error = 1;
  }

  if (isset($error) && $error == 1) {
    header("Location: " . $config_basedir . "/viewcat.php");
  } else {
    $validcat = $_GET['id'];
  }
} else {
  $validcat = 0;
}

$sql = "SELECT * FROM Categories;";
$result = $db_handler->query($sql);

?>

<div class="container">

  <div class="starter-template">
  <?php
      while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        if ($validcat == $row->CategoryID) {
          echo "<strong>" . $row->CategoryName . "</strong>";

          $entries_sql = "SELECT * FROM Entries WHERE CategoryID = " . $validcat . " ORDER BY DatePosted DESC;";
          $entries_result = $db_handler->query($entries_sql);
          $numrows_entries = count($entries_result->fetchAll());

          echo "<ul>";
          if ($numrows_entries == 0) {
            echo "<li>No entries!</li>";
          } else {
            $entries_result = $db_handler->query($entries_sql);
            while ($entry_row = $entries_result->fetch(PDO::FETCH_OBJ)) {
              echo "<li>" . date("F jS Y", strtotime($entry_row->DatePosted)) . " - <a href='viewentry.php?id=" .
              $entry_row->EntryID . "'>" . $entry_row->Subject . "</a></li>";
            }
          }
          echo "</ul>";
        } else {
          echo "<a href='viewcat.php?id=" . $row->CategoryID . "'>" . $row->CategoryName . "</a><br />";
        }
      }
  ?>
  </div>

<?php
require("footer.php");
?>
