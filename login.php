<?php
	include "./includes/header.php";

	if ((isset($_SESSION['user']) || !empty($_SESSION['user']))) {
		header('Location: '.get_url('profile.php'));
	}

	$alerts = [];

	$alerts['error'] = '';
	if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
		$alerts['error'] = $_SESSION['error'];
		$_SESSION['error'] = '';
	}
	$alerts['success'] = '';
	if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
		$alerts['success'] = $_SESSION['success'];
		$_SESSION['success'] = '';
	}

	if (isset($_POST['login']) && !empty($_POST['login']) || isset($_POST['pass']) && !empty($_POST['pass'])) {
		login_user($_POST);
	}

?>
	<main class="container">
	<?php echo show_alert($alerts)?>
		<div class="row mt-5">
			<div class="col">
				<h2 class="text-center">Вход в личный кабинет</h2>
				<p class="text-center">Если вы еще не зарегистрированы, то самое время <a href="<?php echo get_url('register.php')?>">зарегистрироваться</a></p>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-4 offset-4">
				<form action="" method="post">
					<div class="mb-3">
						<label for="login-input" class="form-label">Логин</label>
						<input type="text" class="form-control" id="login-input" name="login" required>
					</div>
					<div class="mb-3">
						<label for="password-input" class="form-label">Пароль</label>
						<input type="password" class="form-control" id="password-input" name="pass" required>
					</div>
					<button type="submit" class="btn btn-primary">Войти</button>
				</form>
			</div>
		</div>
	</main>
<?php include "./includes/footer.php"?>
