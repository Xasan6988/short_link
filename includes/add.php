<?php
  include_once './config.php';
  include_once './functions.php';

  if (isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    if (create_link($_POST['link'], $_POST['user_id'])) {
      $_SESSION['success'] = 'Ссылка успешно создана';
    } else {
      echo 'xyu sosi';
      $_SESSION['error'] = 'Что то пошло не так';
    }
    header('Location: /short_link/profile.php');
    die;
  }
?>
