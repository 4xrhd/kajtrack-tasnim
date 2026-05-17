<?php
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['role']) || !isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=First login");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit();
}
$id = $_GET['id'];
$user = get_user_by_id($conn, $id);

if ($user == 0) {
    header("Location: user.php");
    exit();
}

$data = array($id, "employee");
delete_user($conn, $data);
$sm = "Deleted Successfully";
header("Location: user.php?success=$sm");
exit();