<?php 
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    
    $text = "All Task";
    if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Due Today") {
    	$text = "Due Today";
      $tasks = get_all_tasks_due_today($conn);
      $num_task = count_tasks_due_today($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Overdue") {
    	$text = "Overdue";
      $tasks = get_all_tasks_overdue($conn);
      $num_task = count_tasks_overdue($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "No Deadline") {
    	$text = "No Deadline";
      $tasks = get_all_tasks_NoDeadline($conn);
      $num_task = count_tasks_NoDeadline($conn);

    }else{
    	 $tasks = get_all_tasks($conn);
       $num_task = count_tasks($conn);
    }
    $users = get_all_users($conn);
    

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>All Tasks | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include __DIR__ . "/layout/header.php" ?>
	<div class="body">
		<?php include __DIR__ . "/layout/nav.php" ?>
		<section class="section-1">
			<?php
			$current_due_date = isset($_GET['due_date']) ? $_GET['due_date'] : '';
			?>
			<div class="filter-group">
				<a href="create_task.php" class="btn"><i class="fa fa-plus"></i> Create Task</a>
				<a href="tasks.php" class="<?php echo ($current_due_date == '') ? 'btn' : 'btn-secondary'; ?>">All Tasks</a>
				<a href="tasks.php?due_date=Due Today" class="<?php echo ($current_due_date == 'Due Today') ? 'btn' : 'btn-secondary'; ?>">Due Today</a>
				<a href="tasks.php?due_date=Overdue" class="<?php echo ($current_due_date == 'Overdue') ? 'btn' : 'btn-secondary'; ?>">Overdue</a>
				<a href="tasks.php?due_date=No Deadline" class="<?php echo ($current_due_date == 'No Deadline') ? 'btn' : 'btn-secondary'; ?>">No Deadline</a>
			</div>
         <h4 class="title-2"><?=$text?> (<?=$num_task?>)</h4>
			<?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
		<?php } ?>
			<?php if ($tasks != 0) { ?>
			<table class="main-table">
				<tr>
					<th>#</th>
					<th>Title</th>
					<th>Description</th>
					<th>Assigned To</th>
					<th>Due Date</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				<?php $i=0; foreach ($tasks as $task) { ?>
				<tr>
					<td><?=++$i?></td>
					<td><?=$task['title']?></td>
					<td><?=$task['description']?></td>
					<td>
						<?php 
						$assigned = false;
						foreach ($users as $user) {
							if($user['id'] == $task['assigned_to']){
								echo '<span class="user-badge"><i class="fa fa-user-circle"></i> ' . htmlspecialchars($user['full_name']) . '</span>';
								$assigned = true;
								break;
							}
						}
						if (!$assigned) {
							echo '<span class="user-badge unassigned"><i class="fa fa-question-circle"></i> Unassigned</span>';
						}
						?>
					</td>
					<td>
						<?php
						$due_date_text = "";
						$due_date_class = "";
						if (empty($task['due_date'])) {
							$due_date_text = "No Deadline";
							$due_date_class = "deadline-none";
						} else {
							$due_date_text = $task['due_date'];
							if ($task['status'] === 'completed') {
								$due_date_class = "deadline-completed";
							} else {
								$today = date('Y-m-d');
								if ($task['due_date'] < $today) {
									$due_date_class = "deadline-overdue";
								} elseif ($task['due_date'] === $today) {
									$due_date_class = "deadline-today";
								} else {
									$due_date_class = "deadline-future";
								}
							}
						}
						?>
						<span class="deadline-badge <?php echo $due_date_class; ?>">
							<i class="fa fa-calendar"></i> <?php echo htmlspecialchars($due_date_text); ?>
						</span>
					</td>
					<td>
						<?php
						$status = $task['status'];
						$status_class = "";
						$status_text = "";
						if ($status === 'completed') {
							$status_class = "status-completed";
							$status_text = "Completed";
						} elseif ($status === 'in_progress') {
							$status_class = "status-progress";
							$status_text = "In Progress";
						} else {
							$status_class = "status-pending";
							$status_text = "Pending";
						}
						?>
						<span class="status-badge <?php echo $status_class; ?>">
							<?php echo $status_text; ?>
						</span>
					</td>
					<td>
						<a href="edit-task.php?id=<?=$task['id']?>" class="edit-btn">Edit</a>
						<a href="delete-task.php?id=<?=$task['id']?>" class="delete-btn">Delete</a>
					</td>
				</tr>
			   <?php	} ?>
			</table>
		<?php }else { ?>
			<h3>Empty</h3>
		<?php  }?>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>