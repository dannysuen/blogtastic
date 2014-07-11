<?php
require("header.php");

$sql = "select Entries.*, Categories.CategoryName from Entries, Categories where Entries.CategoryID = Categories.CategoryID order by DatePosted desc limit 2";


$db_query = $db_handler->query($sql);

$db_query2 = $db_handler->query($sql);
$db_query2->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $db_query2->fetch())

$latest_row = $db_query->fetch(PDO::FETCH_OBJ);
$second_latest_row = $db_query->fetch(PDO::FETCH_OBJ);

?>


    <div class="container">

      <div class="starter-template">
        <h1><?php echo "<a href='viewentry.php?id=" . $latest_row->EntryID . "'>" . $latest_row->Subject . "</a>"; ?></h1>
        <p class="lead"><?php echo "<i>In <a href='viewentry.php?id=" . $latest_row->CategoryID . "'>"
        . $latest_row->CategoryName . "</a> - Posted on " . date("F jS Y", strtotime($latest_row->DatePosted)) . "</i>"; ?></p>
        <p class="lead"><?php echo nl2br($latest_row->Body); ?></p>
        <p class="lead"><?php

        $comments_sql = "select Name from Comments where BlogID = " . $latest_row->EntryID . " order by DatePosted;";
        $comments_result = $db_handler->query($comments_sql);
        $comments_rows_count = $comments_result->fetchColumn();
        if ($comments_rows_count == 0) {
          echo "<p class='lead'>No comments</p>";
        } else {
          echo "<p class='lead'>(" . $comments_rows_count . ") comments.</p>";
        }

        ?></p>
      </div>

      <div class="starter-template">
        <h1><?php echo "<a href='viewentry.php?id=" . $second_latest_row->EntryID . "'>" . $second_latest_row->Subject . "</a>"; ?></h1>
        <p class="lead"><?php echo "<i>In <a href='viewentry.php?id=" . $second_latest_row->CategoryID . "'>"
        . $second_latest_row->CategoryName . "</a> - Posted on " . date("F jS Y", strtotime($second_latest_row->DatePosted)) . "</i>"; ?></p>
        <p class="lead"><?php echo nl2br($second_latest_row->Body); ?></p>
      </div>


<?php
require("footer.php");
?>
