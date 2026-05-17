<?php 
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

	if ($_SESSION['role'] == "admin") {
		  $todaydue_task = count_tasks_due_today($conn);
	     $overdue_task = count_tasks_overdue($conn);
	     $nodeadline_task = count_tasks_NoDeadline($conn);
	     $num_task = count_tasks($conn);
	     $num_users = count_users($conn);
	     $pending = count_pending_tasks($conn);
	     $in_progress = count_in_progress_tasks($conn);
	     $completed = count_completed_tasks($conn);
	}else {
        $num_my_task = count_my_tasks($conn, $_SESSION['id']);
        $overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
        $nodeadline_task = count_my_tasks_NoDeadline($conn, $_SESSION['id']);
        $pending = count_my_pending_tasks($conn, $_SESSION['id']);
	     $in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
	     $completed = count_my_completed_tasks($conn, $_SESSION['id']);

	}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include __DIR__ . "/layout/header.php" ?>
	<div class="body">
		<?php include __DIR__ . "/layout/nav.php" ?>
		<section class="section-1">
			<?php if ($_SESSION['role'] == "admin") { ?>
				<div class="dashboard">
					<div class="dashboard-item">
						<i class="fa fa-users"></i>
						<span>
							<span class="dashboard-label">Staff Directory</span>
							<?=$num_users?> Employees
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-tasks"></i>
						<span>
							<span class="dashboard-label">Total Tasks</span>
							<?=$num_task?> All Tasks
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-window-close-o" style="color: #ef4444;"></i>
						<span>
							<span class="dashboard-label">Overdue Tasks</span>
							<?=$overdue_task?> Overdue
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-clock-o"></i>
						<span>
							<span class="dashboard-label">No Deadline</span>
							<?=$nodeadline_task?> Open
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-exclamation-triangle" style="color: #f59e0b;"></i>
						<span>
							<span class="dashboard-label">Due Today</span>
							<?=$todaydue_task?> Due
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-bell" style="color: var(--accent-color);"></i>
						<span>
							<span class="dashboard-label">Recent Alerts</span>
							<?=$overdue_task?> Alerts
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-square-o" style="color: #3b82f6;"></i>
						<span>
							<span class="dashboard-label">Pending Tasks</span>
							<?=$pending?> Pending
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-spinner" style="color: #f59e0b;"></i>
						<span>
							<span class="dashboard-label">Work in Progress</span>
							<?=$in_progress?> Active
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-check-square-o" style="color: #10b981;"></i>
						<span>
							<span class="dashboard-label">Tasks Completed</span>
							<?=$completed?> Closed
						</span>
					</div>
				</div>
			<?php }else{ ?>
				<div class="dashboard">
					<div class="dashboard-item">
						<i class="fa fa-tasks"></i>
						<span>
							<span class="dashboard-label">My Tasks</span>
							<?=$num_my_task?> Assigned
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-window-close-o" style="color: #ef4444;"></i>
						<span>
							<span class="dashboard-label">Overdue Tasks</span>
							<?=$overdue_task?> Overdue
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-clock-o"></i>
						<span>
							<span class="dashboard-label">No Deadline</span>
							<?=$nodeadline_task?> Open
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-square-o" style="color: #3b82f6;"></i>
						<span>
							<span class="dashboard-label">Pending Tasks</span>
							<?=$pending?> Pending
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-spinner" style="color: #f59e0b;"></i>
						<span>
							<span class="dashboard-label">Work in Progress</span>
							<?=$in_progress?> Active
						</span>
					</div>
					<div class="dashboard-item">
						<i class="fa fa-check-square-o" style="color: #10b981;"></i>
						<span>
							<span class="dashboard-label">Tasks Completed</span>
							<?=$completed?> Closed
						</span>
					</div>
				</div>
			<?php } ?>

			<!-- Premium Charts Section -->
			<div class="charts-container">
				<!-- Completeness Doughnut Chart -->
				<div class="chart-card">
					<h3><i class="fa fa-pie-chart" style="color: var(--accent-color);"></i> Task Completeness</h3>
					<div class="chart-wrapper">
						<canvas id="completenessChart"></canvas>
					</div>
				</div>

				<!-- Deadline Bar Chart -->
				<div class="chart-card">
					<h3><i class="fa fa-bar-chart" style="color: #06b6d4;"></i> Deadline & Urgency</h3>
					<div class="chart-wrapper">
						<canvas id="deadlineChart"></canvas>
					</div>
				</div>
			</div>
		</section>
	</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(1)");
	active.classList.add("active");

	// Chart theme variables based on glass theme state
	const isLight = document.body.getAttribute('data-theme') === 'light';
	const textSecondary = isLight ? '#475569' : '#94a3b8';
	const gridColor = isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)';

	// 1. Task Completeness Doughnut Chart
	const compCtx = document.getElementById('completenessChart').getContext('2d');
	new Chart(compCtx, {
		type: 'doughnut',
		data: {
			labels: ['Completed', 'In Progress', 'Pending'],
			datasets: [{
				data: [<?=(int)$completed?>, <?=(int)$in_progress?>, <?=(int)$pending?>],
				backgroundColor: [
					'rgba(16, 185, 129, 0.75)',  // Emerald Green
					'rgba(245, 158, 11, 0.75)',  // Amber
					'rgba(59, 130, 246, 0.75)'   // Sky Blue
				],
				borderColor: isLight ? '#ffffff' : '#1e1b4b',
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'bottom',
					labels: {
						color: textSecondary,
						font: {
							family: 'Inter, system-ui, sans-serif',
							size: 13,
							weight: '500'
						},
						padding: 20
					}
				}
			},
			cutout: '70%'
		}
	});

	// 2. Deadline Status Bar Chart
	const deadlineCtx = document.getElementById('deadlineChart').getContext('2d');
	new Chart(deadlineCtx, {
		type: 'bar',
		data: {
			<?php if ($_SESSION['role'] == "admin") { ?>
				labels: ['Overdue', 'Due Today', 'No Deadline'],
				datasets: [{
					label: 'Tasks Count',
					data: [<?=(int)$overdue_task?>, <?=(int)$todaydue_task?>, <?=(int)$nodeadline_task?>],
					backgroundColor: [
						'rgba(239, 68, 68, 0.7)',
						'rgba(245, 158, 11, 0.7)',
						'rgba(148, 163, 184, 0.7)'
					],
					borderColor: [
						'#ef4444',
						'#f59e0b',
						'#94a3b8'
					],
					borderWidth: 1.5,
					borderRadius: 8
				}]
			<?php } else { ?>
				labels: ['Overdue', 'No Deadline'],
				datasets: [{
					label: 'Tasks Count',
					data: [<?=(int)$overdue_task?>, <?=(int)$nodeadline_task?>],
					backgroundColor: [
						'rgba(239, 68, 68, 0.7)',
						'rgba(148, 163, 184, 0.7)'
					],
					borderColor: [
						'#ef4444',
						'#94a3b8'
					],
					borderWidth: 1.5,
					borderRadius: 8
				}]
			<?php } ?>
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
					grid: {
						display: false
					},
					ticks: {
						color: textSecondary,
						font: {
							family: 'Inter, system-ui, sans-serif',
							size: 12,
							weight: '500'
						}
					}
				},
				y: {
					grid: {
						color: gridColor
					},
					ticks: {
						color: textSecondary,
						stepSize: 1,
						font: {
							family: 'Inter, system-ui, sans-serif',
							size: 12
						}
					}
				}
			}
		}
	});
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>