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

if (isset($_POST['submit'])) {
  try {
    $db_handler = new PDO("sqlite:blogtastic.db");
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

#  $sql = "INSERT INTO Comments (BlogID, DatePosted, Name, Comment) VALUES (" .
#          $validentry . ", CURRENT_DATE, '" . $_POST['name'] . "', '" . $_POST['comment'] . "');";

  $sql = "INSERT INTO Comments (BlogID, DatePosted, Name, Comment) VALUES (" . $validentry . ", CURRENT_DATE, ?, ?);";
  $st_handler = $db_handler->prepare($sql);
  $st_handler->bindParam(1, $_POST['name']);
  $st_handler->bindParam(2, $_POST['comment']);
  if (!$st_handler) {
    echo "prepare not successful!";
  } else {
    $st_handler->execute()
    or die(print_r($st_handler->errorInfo(), true));
    header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?id=" . $validentry);
  }
} else {

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
        echo "<p><i>In <a href='viewcat.php?id=" . $entry_row->CategoryID . "'>" . $entry_row->CategoryName . "</a> - Posted on " .
        date("F jS Y", strtotime($entry_row->DatePosted)) . "</i> ";

        if (isset($_SESSION['Username'])) {
          echo " [<a href='updateentry.php?id=" . $entry_row->EntryID . "'>edit</a>]";
        }

        echo "</p>";
        echo "<p class='lead'>" . nl2br($entry_row->Body) . "</p>";

        $comments_sql = "SELECT * FROM Comments WHERE BlogID = " . $validentry . " ORDER BY DatePosted DESC;";
        $comments_result = $db_handler->query($comments_sql);
        $comments_row_num = count($comments_result->fetchAll());
        if ($comments_row_num == 0) {
          echo "<p>No comments.</p>";
        } else {
          $i = 1;
          $comments_result = $db_handler->query($comments_sql);
          while ($comments_row = $comments_result->fetch(PDO::FETCH_OBJ)) {
            echo "<a name='comment" . $i . "'>";
            echo "<p>Comment by " . $comments_row->Name . " on " . date("F jS Y", strtotime($comments_row->DatePosted)) . "</p></a>";
            echo "<p>" . $comments_row->Comment . "</p>";
            $i++;
          }
        }
      }
    ?>

    <h3>Leave a comment</h3>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] . "?id=" . $validentry; ?>" method="POST">
      <table>
        <tr>
          <td>Your name: </td>
          <td><input type="text" name="name"></td>
        </tr>
        <tr>
          <td>Comments: </td>
          <td><textarea name="comment" rows="10" cols="50"></textarea></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" name="submit" value="Add comment"></td>
        </tr>
      </table>
    </form>
    </div>

  <?php
}
require("footer.php");
?>
