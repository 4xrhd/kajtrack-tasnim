<?php 
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>My Tasks | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include __DIR__ . "/layout/header.php" ?>
	<div class="body">
		<?php include __DIR__ . "/layout/nav.php" ?>
		<section class="section-1">
			<h4 class="title">My Tasks</h4>
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
					<th>Status</th>
					<th>Due Date</th>
					<th>Action</th>
				</tr>
				<?php $i=0; foreach ($tasks as $task) { ?>
				<tr>
					<td><?=++$i?></td>
					<td><?=$task['title']?></td>
					<td><?=$task['description']?></td>
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
						<a href="edit-task-employee.php?id=<?=$task['id']?>" class="edit-btn">Edit</a>
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
	var active = document.querySelector("#navList li:nth-child(2)");
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