<?php
session_start();

// データベース接続
require 'config/dbconnect.php';

// 削除
if (
  isset($_POST['delete']) &&
  $_POST['delete'] === 'delete'
) {

  $stmt = $db->prepare('DELETE FROM pizzas WHERE id = ?');
  $stmt->bindValue(1, $_POST['delete-id']);

  if ($stmt->execute() && $stmt->rowCount()) {
    $_SESSION['success-msg'] = 'ピザを削除しました';
    header('location:index.php');
    exit;
  }
}


// URLパラメーターがない場合（TOPページにリダイレクト）
if (!isset($_GET['id'])) {
  header('location:index.php');
  exit;
}

$stmt = $db->prepare('SELECT * FROM pizzas WHERE id = ?');
$stmt->bindValue(1, $_GET['id']);
$result = $stmt->execute();

if ($result) {
  $pizza = $stmt->fetch();

  if (!$pizza) {
    header('location:index.php');
    exit;
  }
}

include 'template/header.php';
?>

<div class="container">
  <h2 class="text-center display-4 my-5">Pizza Detail</h2>

  <h3 class="text-center mb-5"><?= htmlspecialchars($pizza['pizzaname']) ?></h3>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <p class="fw-bold">トッピング:</p>
          <p><?= htmlspecialchars($pizza['topping']); ?></p>
          <p>シェフ: <?= htmlspecialchars($pizza['chefname']); ?></p>
          <?php
          $created_at = date('Y年n月j日 G時i分s秒', strtotime($pizza['created_at']));
          ?>
          <p class="text-secondary">登録日: <?= htmlspecialchars($created_at); ?></p>
        </div>
        <div class="card-footer text-end">
          <a href="update.php" class="btn btn-primary">編集する</a>
          <form action="detail.php" method="post" id="delete-form" class="d-inline">
            <input type="hidden" name="delete-id" value="<?= htmlspecialchars($pizza['id']); ?>">
            <input type="hidden" name="delete" value="delete">
            <button class="btn btn-danger">この商品を削除する</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<?php
include 'template/footer.php';
?>