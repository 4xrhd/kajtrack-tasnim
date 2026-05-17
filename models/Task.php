<?php 

function insert_task($conn, $data){
	$sql = "INSERT INTO tasks (title, description, assigned_to, due_date) VALUES(?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_all_tasks($conn){
	$sql = "SELECT * FROM tasks ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	$tasks = $stmt->fetchAll();
	return count($tasks) > 0 ? $tasks : 0;
}

function get_all_tasks_due_today($conn){
	$sql = "SELECT * FROM tasks WHERE due_date = ? AND status != 'completed' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([date('Y-m-d')]);
	$tasks = $stmt->fetchAll();
	return count($tasks) > 0 ? $tasks : 0;
}

function count_tasks_due_today($conn){
	$sql = "SELECT id FROM tasks WHERE due_date = ? AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([date('Y-m-d')]);
	return count($stmt->fetchAll());
}

function get_all_tasks_overdue($conn){
	$sql = "SELECT * FROM tasks WHERE due_date < ? AND status != 'completed' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([date('Y-m-d')]);
	$tasks = $stmt->fetchAll();
	return count($tasks) > 0 ? $tasks : 0;
}

function count_tasks_overdue($conn){
	$sql = "SELECT id FROM tasks WHERE due_date < ? AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([date('Y-m-d')]);
	return count($stmt->fetchAll());
}

function get_all_tasks_NoDeadline($conn){
	$sql = "SELECT * FROM tasks WHERE status != 'completed' AND (due_date IS NULL OR due_date = '0000-00-00' OR due_date = '') ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	$tasks = $stmt->fetchAll();
	return count($tasks) > 0 ? $tasks : 0;
}

function count_tasks_NoDeadline($conn){
	$sql = "SELECT id FROM tasks WHERE status != 'completed' AND (due_date IS NULL OR due_date = '0000-00-00' OR due_date = '')";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	return count($stmt->fetchAll());
}

function delete_task($conn, $data){
	$sql = "DELETE FROM tasks WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_task_by_id($conn, $id){
	$sql = "SELECT * FROM tasks WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	$task = $stmt->fetch();
	return $task ? $task : 0;
}

function count_tasks($conn){
	$sql = "SELECT id FROM tasks";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	return count($stmt->fetchAll());
}

function update_task($conn, $data){
	$sql = "UPDATE tasks SET title=?, description=?, assigned_to=?, due_date=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function update_task_status($conn, $data){
	$sql = "UPDATE tasks SET status=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_all_tasks_by_id($conn, $id){
	$sql = "SELECT * FROM tasks WHERE assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	$tasks = $stmt->fetchAll();
	return count($tasks) > 0 ? $tasks : 0;
}

function count_pending_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'pending'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	return count($stmt->fetchAll());
}

function count_in_progress_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	return count($stmt->fetchAll());
}

function count_completed_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	return count($stmt->fetchAll());
}

function count_my_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	return count($stmt->fetchAll());
}

function count_my_tasks_overdue($conn, $id){
	$sql = "SELECT id FROM tasks WHERE due_date < ? AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00' AND due_date != ''";
	$stmt = $conn->prepare($sql);
	$stmt->execute([date('Y-m-d'), $id]);
	return count($stmt->fetchAll());
}

function count_my_tasks_NoDeadline($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=? AND status != 'completed' AND (due_date IS NULL OR due_date = '0000-00-00' OR due_date = '')";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	return count($stmt->fetchAll());
}

function count_my_pending_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'pending' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	return count($stmt->fetchAll());
}

function count_my_in_progress_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	return count($stmt->fetchAll());
}

function count_my_completed_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'completed' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
	return count($stmt->fetchAll());
}