<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | KajTrack</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
	<!-- Dynamic Background Blobs -->
	<div class="blur-circle blur-circle-1"></div>
	<div class="blur-circle blur-circle-2"></div>

	<!-- Live Corner Theme Switcher -->
	<button id="themeToggleBtn" class="login-theme-toggle" aria-label="Toggle Theme">
		<i class="fa fa-moon-o"></i>
	</button>
      
	<form method="POST" action="handlers/login.php" style="z-index: 1;">
		<!-- Brand Logo and Header -->
		<div class="login-header">
			<div class="login-logo">
				<i class="fa fa-rocket"></i>
			</div>
			<h2>Welcome Back</h2>
			<p>Sign in to your KajTrack workspace to manage your tasks</p>
		</div>

		<?php if (isset($_GET['error'])) {?>
			<div class="danger" role="alert">
				<i class="fa fa-exclamation-circle"></i>
				<span><?php echo stripcslashes($_GET['error']); ?></span>
			</div>
		<?php } ?>

		<?php if (isset($_GET['success'])) {?>
			<div class="success" role="alert">
				<i class="fa fa-check-circle"></i>
				<span><?php echo stripcslashes($_GET['success']); ?></span>
			</div>
		<?php } ?>
			
		<div class="input-holder">
			<label><i class="fa fa-user-circle"></i> Username</label>
			<input type="text" class="input-1" name="user_name" placeholder="Enter your username" required autocomplete="username">
		</div>

		<div class="input-holder">
			<label><i class="fa fa-lock"></i> Password</label>
			<div style="position: relative; width: 100%;">
				<input type="password" class="input-1" name="password" id="loginPassword" placeholder="Enter your password" required autocomplete="current-password" style="padding-right: 50px;">
				<button type="button" id="togglePasswordBtn" class="password-toggle-eye" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility">
					<i class="fa fa-eye" id="passwordEyeIcon"></i>
				</button>
			</div>
		</div>

		<button type="submit" class="btn"><i class="fa fa-sign-in"></i> Sign In</button>
	</form>

	<script>
		// Toggle Password Visibility
		function togglePasswordVisibility() {
			const passwordInput = document.getElementById('loginPassword');
			const eyeIcon = document.getElementById('passwordEyeIcon');
			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				eyeIcon.classList.remove('fa-eye');
				eyeIcon.classList.add('fa-eye-slash');
			} else {
				passwordInput.type = 'password';
				eyeIcon.classList.remove('fa-eye-slash');
				eyeIcon.classList.add('fa-eye');
			}
		}

		// Theme toggle logic for login screen
		const themeToggleBtn = document.getElementById('themeToggleBtn');
		const icon = themeToggleBtn.querySelector('i');
		
		function setTheme(theme) {
			document.body.setAttribute('data-theme', theme);
			localStorage.setItem('theme', theme);
			if (theme === 'light') {
				icon.className = 'fa fa-sun-o';
			} else {
				icon.className = 'fa fa-moon-o';
			}
		}

		themeToggleBtn.addEventListener('click', () => {
			const currentTheme = document.body.getAttribute('data-theme');
			if (currentTheme === 'light') {
				setTheme('dark');
			} else {
				setTheme('light');
			}
		});

		// Initial load theme
		const savedTheme = localStorage.getItem('theme') || 'dark';
		setTheme(savedTheme);
	</script>
</body>
</html>