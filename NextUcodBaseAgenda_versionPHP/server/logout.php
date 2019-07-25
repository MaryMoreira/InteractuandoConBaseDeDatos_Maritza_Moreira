<?php

/**********************
 * Remueve la sesion
 **********************/

session_start();

$url = '../client/';
$permanent = false;

if (isset($_SESSION['iduser'])) {
  session_destroy();
  header('Location: ' . $url, true, $permanent ? 301 : 302);
  exit();
}

?>
