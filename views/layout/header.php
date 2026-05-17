<header class="header">
	<h2 class="u-name">KajTrack
		<label for="checkbox">
			<i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
		</label>
	</h2>
	<div class="header-actions" style="display: flex; gap: 16px; align-items: center;">
		<span class="theme-toggle notification" id="themeToggleBtn" title="Toggle Theme">
			<i class="fa fa-sun-o" id="themeIcon" aria-hidden="true"></i>
		</span>
		<span class="notification" id="notificationBtn">
			<i class="fa fa-bell" aria-hidden="true"></i>
			<span id="notificationNum"></span>
		</span>
	</div>
</header>
<div class="notification-bar" id="notificationBar">
	<ul id="notifications">
	
	</ul>
</div>
<script type="text/javascript">
	// Theme Toggle Logic
	const themeToggleBtn = document.getElementById('themeToggleBtn');
	const themeIcon = document.getElementById('themeIcon');
	const currentTheme = localStorage.getItem('theme') || 'dark';

	if (currentTheme === 'light') {
		document.body.setAttribute('data-theme', 'light');
		themeIcon.classList.replace('fa-sun-o', 'fa-moon-o');
	}

	themeToggleBtn.addEventListener('click', () => {
		let theme = document.body.getAttribute('data-theme');
		if (theme === 'light') {
			document.body.removeAttribute('data-theme');
			localStorage.setItem('theme', 'dark');
			themeIcon.classList.replace('fa-moon-o', 'fa-sun-o');
		} else {
			document.body.setAttribute('data-theme', 'light');
			localStorage.setItem('theme', 'light');
			themeIcon.classList.replace('fa-sun-o', 'fa-moon-o');
		}
	});

	// Notification Logic
	var openNotification = false;

	const notification = ()=> {
		let notificationBar = document.querySelector("#notificationBar");
		if (openNotification) {
			notificationBar.classList.remove('open-notification');
			openNotification = false;
		}else {
			notificationBar.classList.add('open-notification');
			openNotification = true;
		}
	}
	let notificationBtn = document.querySelector("#notificationBtn");
	notificationBtn.addEventListener("click", notification);
</script>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function(){

       $("#notificationNum").load("handlers/notification-count.php");
       $("#notifications").load("handlers/notification.php");

   });
</script>