<?php
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['role']) || !isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=First login");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: tasks.php");
    exit();
}
$id = $_GET['id'];
$task = get_task_by_id($conn, $id);

if ($task == 0) {
    header("Location: tasks.php");
    exit();
}

$data = array($id);
delete_task($conn, $data);
$sm = "Deleted Successfully";
header("Location: tasks.php?success=$sm");
exit();