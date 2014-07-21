<?php
session_start();

require 'config.php';

try {
  $db_handler = new PDO("sqlite:blogtastic.db");
} catch (PDOException $e) {
  echo $e->getMessage();
}

if (!isset($_SESSION['Username'])) {
  header("Location: " . $config_basedir);
}

if (isset($_POST['submit'])) {
  $sql = "INSERT INTO Entries (CategoryID, DatePosted, Subject, Body) VALUES (?, CURRENT_DATE, ?, ?);";
  $st_handler = $db_handler->prepare($sql);
  $st_handler->bindParam(1, $_POST['cat']);
  $st_handler->bindParam(2, $_POST['subject']);
  $st_handler->bindParam(3, $_POST['body']);
  $st_handler->execute() or die(print_r($st_handler->errorInfo(), true));

  header("Location: " . $config_basedir);

} else {
  require("addentry_header.php");
?>

<div class="container">

  <div class="starter-template">

    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
      <h2>Add new entry</h2>
      <table>
        <tr>
          <td>Category</td>
          <td>
            <select name="cat">
            <?php
              $cat_sql = "SELECT * FROM Categories;";
              $cat_result = $db_handler->query($cat_sql);
                while ($cat_row = $cat_result->fetch(PDO::FETCH_OBJ)) {
                  echo "<option value='" . $cat_row->CategoryID . "'>" . $cat_row->CategoryName . "</option>";
                }
              ?>
              </select>
          </td>
        </tr>

        <tr>
          <td>Subject</td>
          <td><input name="subject" type="text"></td>
        </tr>

        <tr>
          <td>Body</td>
          <td><textarea name="body" rows="10" cols="50"></textarea></td>
        </tr>

        <tr>
          <td></td>
          <td><input type="submit" name="submit" value="Add Entry!"></td>
        </tr>
      </table>

    </form>
  </div>

<?php
}
require 'footer.php';
?>
