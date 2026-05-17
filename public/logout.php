<?php
session_save_path(__DIR__ . "/../database/sessions");
    session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
