<?php
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: login.php?error=First login");
    exit();
}

include __DIR__ . '/../views/notifications.php';
