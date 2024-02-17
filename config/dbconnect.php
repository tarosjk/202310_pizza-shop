<?php
try {
  $dsn = 'mysql:host=localhost;dbname=pizza;charset=utf8';
  $dbuser = 'pizzauser';
  $dbpass = '5XbCD_9zfUn!smj1';
  $option = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ];

  $db = new PDO($dsn, $dbuser, $dbpass, $option);
} catch (PDOException $e) {
  echo $e->getMessage();
}