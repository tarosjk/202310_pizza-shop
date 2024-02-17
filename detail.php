<?php
session_start();

// データベース接続
require 'config/dbconnect.php';

$stmt = $db->prepare('SELECT * FROM pizzas WHERE id = ?');
$stmt->bindValue(1, $_GET['id']);
$result = $stmt->execute();

if ($result) {
  $pizza = $stmt->fetch();
  var_dump($pizza);
}

include 'template/header.php';
?>

<div class="container">
  <h2 class="text-center display-4 my-5">Our Special Pizzas</h2>


</div>

<?php
include 'template/footer.php';
?>