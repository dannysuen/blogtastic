<?php

session_start();

require("config.php");

try {
  $db_handler = new PDO("sqlite:blogtastic.db");
} catch (PDOException $e) {
  echo $e->getMessage();
}

if (isset($_POST['submit'])) {
  $sql = "SELECT * FROM Logins WHERE Username = '" . $_POST['username'] . "' AND Password = '" .
          $_POST['password'] . "';";

  echo $sql;

  $result = $db_handler->query($sql);
  $numrows = count($result->fetchAll());

  echo "num rows: " . $numrows;

  if ($numrows == 1) {
    $result = $db_handler->query($sql);
    $user_row = $result->fetch(PDO::FETCH_OBJ);

    $_SESSION["Username"] = $user_row->Username;
    $_SESSION["UserID"] = $user_row->ID;

    header("Location: " . $config_basedir);

  } else {
    header("Location: " . $config_basedir . "login.php?error=1");
  }
} else {
  require("login_header.php");

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="login.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <?php
        if (isset($_GET['error'])) {
          echo "<div class='alert alert-danger' role='alert'>
            <strong>Oh snap!</strong> Incorrect login, please try again!</div>";
        }
      ?>

      <form class="form-signin" role="form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
        <h2 class="form-signin-heading">Please login in</h2>
        <input type="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Login!" />
      </form>


<?php
require("footer.php");
?>
