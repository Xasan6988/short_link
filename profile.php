<?php
	include "./includes/header_profile.php";

	if ((!isset($_SESSION['user']) || empty($_SESSION['user']))) {
		header('Location: '.get_url());
	}

	$links = get_user_links($_SESSION['user']['id']);

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
?>
	<main class="container">
	<?php echo show_alert($alerts)?>
		<div class="row mt-5">
			<?php if (!empty($links)) {?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Ссылка</th>
						<th scope="col">Сокращение</th>
						<th scope="col">Переходы</th>
						<th scope="col">Действия</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($links as $key => $link) {?>
					<tr>
						<th scope="row"><?php echo $key+1?></th>
						<td><a href="https://google.ru" target="_blank"><?php echo $link['long_link']?></a></td>
						<td class="short-link"><?php echo get_url('?url='.$link['short_link'])?></td>
						<td><?php echo $link['views']?></td>
						<td>
							<a href="#" class="btn btn-primary btn-sm copy-btn" title="Скопировать в буфер" data-clipboard-text="<?php echo get_url('?url='.$link['short_link'])?>"><i class="bi bi-files"></i></a>
							<a href="<?php echo get_url('includes/edit.php?id='.$link['id']);?>" class="btn btn-warning btn-sm" title="Редактировать"><i class="bi bi-pencil"></i></a>
							<a href="<?php echo get_url('includes/delete.php?id='.$link['id']);?>" class="btn btn-danger btn-sm" title="Удалить"><i class="bi bi-trash"></i></a>
						</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			<?php } else {?>
				<div class="col">
					<h2 class="text-center">У вас нет ссылок<h2>
				</div>
				<?php }?>
		</div>
	</main>
	<div aria-live="polite" aria-atomic="true" class="position-relative">
		<div class="toast-container position-absolute top-0 start-50 translate-middle-x">
			<div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="d-flex">
					<div class="toast-body">
						Ссылка скопирована в буфер
					</div>
					<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
	</div>
<?php include "./includes/footer_profile.php"?>
