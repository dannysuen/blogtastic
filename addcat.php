<?php
require("config.php");
session_start();

try {
  $db_handler = new PDO("sqlite:blogtastic.db");
} catch (PDOException $e) {
  echo $e->getMessage();
}

if (!isset($_SESSION['Username'])) {
  header("Location: " . $config_basedir);
}

if (isset($_POST['submit'])) {
  $sql = "INSERT INTO Categories(CategoryName) VALUES ( ? )";
  echo $sql;

  $st_handler = $db_handler->prepare($sql);
  $st_handler->bindParam(1, $_POST['cat']);
  $st_handler->execute() or die(print_r($st_handler->errorInfo(), true));

  header("Location: " . $config_basedir . "viewcat.php");
} else {
  require 'addcat_header.php';
?>

<div class="container">

  <div class="starter-template">

  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">

  <table>
    <tr>
      <td>Category</td>
      <td><input type="text" name="cat"></td>
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
