<?php

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

  // エラーの内容確認
  echo '<pre>';
  var_dump($errors);
  echo '</pre>';
} // 送信チェック if


?>
<?php
include 'template/header.php';
?>

<div class="container">
  <h1 class="text-center my-5">ピザの追加</h1>

  <div class="row justify-content-center">
    <div class="col-md-8 bg-white p-4 rounded">
      <form action="add.php" method="post">
        <div class="mb-3">
          <label for="pizza-name" class="form-label fw-bold">ピザの名前</label>
          <input type="text" name="pizza-name" id="pizza-name" placeholder="マルゲリータ" class="form-control">
          <p class="form-text">100文字以内で入力</p>
          <?= error_msg('pizza-name'); ?>
        </div>
        <div class="mb-3">
          <label for="chef-name" class="form-label fw-bold">シェフの名前</label>
          <input type="text" name="chef-name" id="chef-name" placeholder="ピザシェフ" class="form-control">
          <p class="form-text">ピザの作者の名前を入力して下さい。ニックネームもOK。</p>
          <?= error_msg('chef-name'); ?>
        </div>
        <div class="mb-3">
          <label for="topping" class="form-label fw-bold">トッピング</label>
          <input type="text" name="topping" id="topping" placeholder="チーズ,バジル" class="form-control">
          <?= error_msg('topping'); ?>
        </div>
        <div class="text-center">
          <button class="btn btn-primary" value="submit" name="submit">追加する</button>
        </div>
      </form>
      <?php var_dump($_POST) ?>
    </div>
  </div>
</div>

<?php
include 'template/footer.php';
?>