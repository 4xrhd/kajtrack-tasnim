<?php 
session_save_path(__DIR__ . "/../../database/sessions");
    session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../../config/DB_connection.php";
    include "../../models/Notification.php";


    $notifications = get_all_my_notifications($conn, $_SESSION['id']);
    if ($notifications == 0) { ?>
        <li>
        <a href="#">
            You have zero notifications
        </a>
        </li>
       
    <?php }else{
    foreach ($notifications as $notification) {
 ?>
    <li>
    <a href="notification-read.php?notification_id=<?=$notification['id']?>">
        
        <?php if ($notification['is_read'] == 0) {
            echo "<mark>".$notification['type']."</mark>: ";
        }else echo $notification['type'].": " ?>
        <?=$notification['message']?>
        &nbsp;&nbsp;<small><?=$notification['date']?></small>
    </a>
    </li>
 <?php
  }
  }
}else{ 
  echo "";
}
 ?>