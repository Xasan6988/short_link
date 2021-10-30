<?php
  include "./includes/config.php";

  function get_url($page = '') {
    return HOST."/$page";
  }

  function db() {
    try {
      return new PDO("mysql:host=".DB_HOST."; dbname=".DB_NAME."; charset=utf8", DB_USER, DB_PASS, [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  function db_query($sql = '', $exec = false) {
    if (empty($sql)) return false;

    if ($exec) {
      return db()->exec($sql);
    }
      return db()->query($sql);
  }

  function get_user_counts() {
    return db_query("SELECT COUNT(id) FROM `users`;")->fetchColumn();
  }

  function get_user_info($login) {
    if (empty($login)) return [];

    return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
  }

  function get_link_counts() {
    return db_query("SELECT COUNT(id) FROM `links`;")->fetchColumn();
  }
  function get_view_counts() {
    return db_query("SELECT SUM(`views`) FROM `links`;")->fetchColumn();
  }
  function get_link_info($url) {
    if (empty($url)) return [];
    return db_query("SELECT * FROM `links` WHERE `short_link` = '$url';")->fetch();
  }
  function update_views($url) {
    if (empty($url)) return false;
    return db_query("UPDATE `links` SET `views` = `views` + 1 WHERE `short_link` = '$url'", true);
  }

  function add_user($login, $pass) {
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    return db_query("INSERT INTO `users` (`id`, `login`, `pass`) VALUES (NULL, NULL, NULL), (NULL, '$login', '$hashed_pass');", true);
  }

  function register_user($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || !isset($auth_data['pass2'])) return false;

    $user = get_user_info($auth_data['login']);

    if (!empty($user)) {
      $_SESSION['error'] = 'Пользователь '.$auth_data['login'].' уже существует';
      header('Location: register.php');
      die;
    }

    if (!strlen($auth_data['pass']) < 6 || !strlen($auth_data['pass2']) < 6) {
			$_SESSION['error'] = 'Минимальная длина пароля - 6 символов';
      header('Location: register.php');
      die;
		}


    if ($auth_data['pass'] !== $auth_data['pass2']) {
      $_SESSION['error'] = 'Введенные пароли не совпадают!';
      header('Location: register.php');
      die;
    }

    if (add_user($auth_data['login'], $auth_data['pass'])) {
      $_SESSION['success'] = 'Регистрация прошла успешно';
      header('Location: login.php');
      die;
    }
    return true;
  }

  function login_user($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || empty($auth_data['pass'])) {
      $_SESSION['error'] = 'Логин или пароль не может быть пустым';
      header('Location: login.php');
      die;
    }

    $user = get_user_info($auth_data['login']);

    if (empty($user)) {
      $_SESSION['error'] = 'Логин или пароль неверен';
      header('Location: login.php');
      die;
    }
    if (password_verify($auth_data['pass'], $user['pass'])) {
      $_SESSION['user'] = ['id' => $user['id'], 'login' => $user['login']];
      header('Location: profile.php');
      die;
    } else {
      $_SESSION['error'] = 'Логин или пароль неверен';
      header('Location: login.php');
      die;
    }
  }

  function show_alert($alerts) {
    if (!empty($alerts['success'])) {
      return '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
			          '.$alerts['success'].'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else if (!empty($alerts['error'])) {
      return '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
			          '.$alerts['error'].'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
  }

  function get_user_links($id) {
    if (empty($id)) return [];
    return db_query("SELECT * FROM `links` WHERE `user_id` = $id;")->fetchAll();
  }
  function delete_link($id) {
    if (empty($id)) return false;

    return db_query("DELETE FROM `links` WHERE `id` = $id;", true);
  }
  function generate_string($size = 6) {
    $new_string = str_shuffle(URL_CHARS);
    return substr($new_string, 0, $size);
  }
  function create_link($link, $user_id) {
    if (empty($link) || empty($user_id)) return false;
    $short_link = generate_string();

    return db_query("INSERT INTO `links` (`id`, `user_id`, `long_link`, `short_link`, `views`) VALUES (NULL, '$user_id', '$link', '$short_link', '0');", true);
  }
?>
