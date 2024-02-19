<?php
// セッションの開始
session_start();

// データベース接続
require 'config/dbconnect.php';

// データ受信後の処理
// 1. 送信のチェック
// 2. エラーチェック
// 3-1. (エラーあり) エラーメッセージを表示
// 3-2. (エラーなし) トップページにリダイレクト
// 4. フラッシュメッセージ（1回のみ表示する）

$errors = [
  'pizza-name' => [
    'required' => false,
    'format' => false,
  ],
  'chef-name' => [
    'required' => false,
    'format' => false,
  ],
  'topping' => [
    'required' => false,
    'format' => false,
  ],
];

$error_msg = [
  'required' => '必須入力です',
  'format' => '日本語、英数字のみ有効です。記号等は使用できません'
];

function error_msg($key)
{
  global $errors;
  global $error_msg;
  $error_html = '';

  foreach ($errors[$key] as $key => $flag) {
    if ($flag) {
      $error_html .= "<p class=\"text-danger error-msg\">{$error_msg[$key]}</p>";
    }
  }

  return $error_html;
}

// 再反映用変数
$pizza_name = $chef_name = $topping = '';

// 1. 送信のチェック
if (
  isset($_POST['submit']) &&
  $_POST['submit'] === 'submit'
) {

  // 2. エラーチェック
  if (empty($_POST['pizza-name'])) {
    $errors['pizza-name']['required'] = true;
  } else {
    // 入力があった場合

    // 書式チェック
    if (!preg_match('/^([^\x01-\x7E]|[\da-zA-Z ])+$/', $_POST['pizza-name'])) {
      $errors['pizza-name']['format'] = true;
    }
  }

  if (empty($_POST['chef-name'])) {
    $errors['chef-name']['required'] = true;
  } else {
    // 入力があった場合

    // 書式チェック
    if (!preg_match('/^([^\x01-\x7E]|[\da-zA-Z ])+$/', $_POST['chef-name'])) {
      $errors['chef-name']['format'] = true;
    }
  }

  if (empty($_POST['topping'])) {
    $errors['topping']['required'] = true;
  }

  // 入力値の再反映
  $pizza_name = $_POST['pizza-name'];
  $chef_name = $_POST['chef-name'];
  $topping = $_POST['topping'];

  // 最終エラーチェック
  $error_exists = false;
  foreach ($errors as $error) {
    if (in_array(true, $error)) {
      $error_exists = true;
      break;
    }
  }


  if (!$error_exists) {

    // データベースへのデータの登録
    $stmt = $db->prepare('
      UPDATE pizzas
      SET
        pizzaname=?, chefname=?, topping=?
      WHERE
        id = ?
    ');
    $stmt->bindValue(1, $_POST['pizza-name']);
    $stmt->bindValue(2, $_POST['chef-name']);
    $stmt->bindValue(3, $_POST['topping']);
    $stmt->bindValue(4, $_POST['id']);
    $result = $stmt->execute();

    if ($result) {
      $_SESSION['success-msg'] = 'ピザの更新が完了しました';
      header('location:index.php');
      exit; //die
    } else {
      echo 'データベース登録時にエラーが発生しました';
    }
  }
} // 送信チェック if


// URLパラメーターがない場合（TOPページにリダイレクト）
if (!isset($_GET['id'])) {
  header('location:index.php');
  exit;
}

// 商品の情報を取得
$stmt = $db->prepare('SELECT * FROM pizzas WHERE id = ?');
$stmt->bindValue(1, $_GET['id']);
$result = $stmt->execute();

if ($result) {
  $pizza = $stmt->fetch();

  if (!$pizza) {
    header('location:index.php');
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pizza_name = $pizza['pizzaname'];
    $chef_name = $pizza['chefname'];
    $topping = $pizza['topping'];
  }
}



?>
<?php
include 'template/header.php';
?>

<div class="container">
  <h1 class="text-center my-5">ピザの編集</h1>

  <div class="row justify-content-center">
    <div class="col-md-8 bg-white p-4 rounded">
      <form action="update.php?id=<?= htmlspecialchars($pizza['id']); ?>" method="post">
        <div class="mb-3">
          <label for="pizza-name" class="form-label fw-bold">ピザの名前</label>
          <input type="text" name="pizza-name" id="pizza-name" placeholder="マルゲリータ" class="form-control" value="<?= $pizza_name; ?>">
          <p class="form-text">100文字以内で入力</p>
          <?= error_msg('pizza-name'); ?>
        </div>
        <div class="mb-3">
          <label for="chef-name" class="form-label fw-bold">シェフの名前</label>
          <input type="text" name="chef-name" id="chef-name" placeholder="ピザシェフ" class="form-control" value="<?= $chef_name; ?>">
          <p class="form-text">ピザの作者の名前を入力して下さい。ニックネームもOK。</p>
          <?= error_msg('chef-name'); ?>
        </div>
        <div class="mb-3">
          <label for="topping" class="form-label fw-bold">トッピング</label>
          <input type="text" name="topping" id="topping" placeholder="チーズ,バジル" class="form-control" value="<?= $topping; ?>">
          <?= error_msg('topping'); ?>
        </div>
        <div class="text-center">
          <input type="hidden" name="id" value="<?= htmlspecialchars($pizza['id']); ?>">
          <button class="btn btn-primary" value="submit" name="submit">更新する</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include 'template/footer.php';
?>