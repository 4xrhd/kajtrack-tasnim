<?php
require_once __DIR__ . '/bootstrap.php';

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include __DIR__ . '/../views/login.php';
