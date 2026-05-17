<?php 
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    if (!isset($_GET['id'])) {
    	 header("Location: ../public/user.php");
    	 exit();
    }
    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);

    if ($user == 0) {
    	 header("Location: ../public/user.php");
    	 exit();
    }

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit User | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "views/layout/header.php" ?>
	<div class="body">
		<?php include "views/layout/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Edit Users <a href="../public/user.php">Users</a></h4>
			<form class="form-1"
			      method="POST"
			      action="handlers/update-user.php">
			      <?php if (isset($_GET['error'])) {?>
      	  	<div class="danger" role="alert">
			  <?php echo stripcslashes($_GET['error']); ?>
			</div>
      	  <?php } ?>

      	  <?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
      	  <?php } ?>
				<div class="input-holder">
					<lable>Full Name</lable>
					<input type="text" name="full_name" class="input-1" placeholder="Full Name" value="<?=$user['full_name']?>"><br>
				</div>
				<div class="input-holder">
					<lable>Username</lable>
					<input type="text" name="user_name" value="<?=$user['username']?>" class="input-1" placeholder="Username"><br>
				</div>
				<div class="input-holder">
					<lable>Password</lable>
					<input type="text" value="**********" name="password" class="input-1" placeholder="Password"><br>
				</div>
				<input type="text" name="id" value="<?=$user['id']?>" hidden>

				<button class="edit-btn">Update</button>
			</form>
			
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
   header("Location: ../public/login.php?error=$em");
   exit();
}
 ?>