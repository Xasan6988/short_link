<?php
  define ('SITE_NAME', "Cut your URL");
  define ("HOST", "http://".$_SERVER['HTTP_HOST']."/short_link");

  const DB_HOST = '127.0.0.1';
  const DB_NAME = 'short_link';
  const DB_USER = 'root';
  const DB_PASS = 'root';

  session_start();
?>
