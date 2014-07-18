<?php
require("header.php");

$sql = "select Entries.*, Categories.CategoryName from Entries, Categories where Entries.CategoryID = Categories.CategoryID order by DatePosted desc limit 1";

$db_query = $db_handler->query($sql);

$db_query2 = $db_handler->query($sql);

$latest_row = $db_query->fetch(PDO::FETCH_OBJ);
$second_latest_row = $db_query->fetch(PDO::FETCH_OBJ);

?>

    <div class="container">

      <div class="starter-template">
        <h1><?php echo "<a href='viewentry.php?id=" . $latest_row->EntryID . "'>" . $latest_row->Subject . "</a>"; ?></h1>
        <p><?php echo "<i>In <a href='viewentry.php?id=" . $latest_row->CategoryID . "'>"
        . $latest_row->CategoryName . "</a> - Posted on " . date("F jS Y", strtotime($latest_row->DatePosted)) . "</i>";

        if (isset($_SESSION['Username'])) {
          echo " [<a href='updateentry.php?id=" . $latest_row->EntryID . "'>edit</a>]";
        }
        ?>
        </p>

        <p class="lead"><?php echo nl2br($latest_row->Body); ?></p>
        <p class="lead"><?php

        $comments_sql = "select Name from Comments where BlogID = " . $latest_row->EntryID . " order by DatePosted;";
        $comments_result = $db_handler->query($comments_sql);
        $comments_rows_count = count($comments_result->fetchAll());
        if ($comments_rows_count == 0) {
          echo "<p>No comments.</p>";
        } else {
          $comments_result2 = $db_handler->query($comments_sql);
          echo "<p>(<strong>" . $comments_rows_count . "</strong>) comments : ";
          $i = 1;
          while ($comment_row = $comments_result2->fetch(PDO::FETCH_ASSOC)) {
            echo "<a href='viewentry.php?id=" . $latest_row->EntryID . "#comment" . $i . "'>" . $comment_row['Name'] . "</a> ";
            $i++;
          }
          echo "</p>";
        }

        $prevsql = "select Entries.*, Categories.CategoryName from Entries, Categories where Entries.CategoryID = Categories.CategoryID order by DatePosted desc limit 1, 5;";
        $prev_numrows = count($db_handler->query($prevsql)->fetchAll());
        if ($prev_numrows == 0) {
          echo "<p class='lead'>No previous entries.</p>";
        } else {
          echo "<ul class='lead'>";
          $prev_result = $db_handler->query($prevsql);
          while ($prev_row = $prev_result->fetch(PDO::FETCH_OBJ)) {
            echo "<li><a href='viewentry.php?id=" . $prev_row->EntryID . "'>" . $prev_row->Subject . "</a></li>";
          }
          echo "</ul>";
        }

        ?></p>
      </div>


<?php
require("footer.php");
?>
