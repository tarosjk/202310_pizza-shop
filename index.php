<?php
session_start();

// データベース接続
require 'config/dbconnect.php';

$sql = 'SELECT id, pizzaname, topping FROM pizzas  ORDER BY id DESC';
$stmt = $db->query($sql);
$pizzas = $stmt->fetchAll();
// var_dump($stmt->fetchAll());


include 'template/header.php';
?>

<div class="container">
  <h2 class="text-center display-4 my-5">Our Special Pizzas</h2>

  <?php if (isset($_SESSION['success-msg'])) : ?>
    <p class="alert alert-success">
      <?= $_SESSION['success-msg']; ?>
      <?php unset($_SESSION['success-msg']); ?>
    </p>
  <?php endif; ?>

  <div class="row g-4">
    <?php foreach ($pizzas as $pizza) : ?>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="h4"><?= htmlspecialchars($pizza['pizzaname']); ?></h3>
            <p><?= htmlspecialchars($pizza['topping']); ?></p>
          </div>
          <div class="card-footer">
            <a href="detail.php?id=<?= htmlspecialchars($pizza['id']); ?>" class="btn btn-primary">詳細</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>

</div>

<?php
include 'template/footer.php';
?>