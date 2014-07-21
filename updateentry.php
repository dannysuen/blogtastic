<?php

session_start();
require 'config.php';

if (!isset($_SESSION['Username'])) {
  header("Location: " . $config_basedir);
}

try {
  $db_handler = new PDO("sqlite:blogtastic.db");
} catch (PDOException $e) {
  echo $e->getMessage();
}

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
  $update_sql = "UPDATE Entries SET CategoryID = ?, Subject = ?, Body = ? WHERE EntryID = ?;";

  echo $update_sql;

  $st_handler = $db_handler->prepare($update_sql);
  $st_handler->bindParam(1, $_POST['cat_id']);
  $st_handler->bindParam(2, $_POST['subject']);
  $st_handler->bindParam(3, $_POST['body']);
  $st_handler->bindParam(4, $validentry);
  $st_handler->execute() or die(print_r($st_handler->errorInfo(), true));

  header("Location: " . $config_basedir . "viewentry.php?id=" . $validentry);
} else {
  require 'updateentry_header.php';

  $fill_sql = "SELECT * FROM Entries WHERE EntryID = " . $validentry . ";";
  $fill_result = $db_handler->query($fill_sql);
  $fill_row = $fill_result->fetch(PDO::FETCH_OBJ);


?>
  <div class="container">

    <div class="starter-template">

      <form action="<?php echo $_SERVER['SCRIPT_NAME'] . "?id=" . $validentry; ?>" method="POST">
      <h2>Update entry</h2>
      <table>
        <tr>
          <td>Category</td>
          <td>
            <select name="cat_id">
            <?php
              $cat_sql = "SELECT * FROM Categories;";
              $cat_result = $db_handler->query($cat_sql);
              while ($cat_row = $cat_result->fetch(PDO::FETCH_OBJ)) {
                echo "<option value='" . $cat_row->CategoryID . "'";

                if ($cat_row->CategoryID == $fill_row->CategoryID) {
                  echo " selected";
                }

                echo ">" . $cat_row->CategoryName . "</option>";
              }
            ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>Subject</td>
          <td><input type="text" name="subject" value="<?php echo $fill_row->Subject; ?>"></td>
          <tr>
            <td>Body</td>
            <td><textarea name="body" rows="10" cols="50"><?php echo $fill_row->Body; ?></textarea></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Update Entry!"></td>
          </tr>
        </table>
      </form>
    </div>

<?php
}
require "footer.php";
?>
