<?php
// Prevent direct access
if (count(get_included_files()) == 1) {
    http_response_code(403);
    exit("Direct access not allowed");
}

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    session_save_path(__DIR__ . "/../database/sessions");
    session_start();
}

require_once __DIR__ . '/../config/DB_connection.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notification.php';
