<?php 
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    $user = get_user_by_id($conn, $_SESSION['id']);
    
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "views/layout/header.php" ?>
	<div class="body">
		<?php include "views/layout/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Profile <a href="../public/edit_profile.php">Edit Profile</a></h4>
         <table class="main-table" style="max-width: 300px;">
				<tr>
					<td>Full Name</td>
					<td><?=$user['full_name']?></td>
				</tr>
				<tr>
					<td>User name</td>
					<td><?=$user['username']?></td>
				</tr>
				<tr>
					<td>Joined At</td>
					<td><?=$user['created_at']?></td>
				</tr>
			</table>

		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(3)");
	active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: ../public/login.php?error=$em");
   exit();
}
 ?>