<?php
  include "./config.php";
  include "./functions.php";

  if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /short_link/profile.php');
    die;
  }
  $_SESSION['success'] = 'АВАШАТА';
  delete_link($_GET['id']);
  $_SESSION['success'] = 'Ссылка успешно удалена';
  header("Location: /short_link/profile.php");
  die;
?>
