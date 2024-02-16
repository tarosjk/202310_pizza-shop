<?php
session_start();

try {
  $dsn = 'mysql:host=localhost;dbname=pizza;charset=utf8';
  $dbuser = 'pizzauser';
  $dbpass = '5XbCD_9zfUn!smj1';
  $option = [
    
  ];

} catch() {

}

include 'template/header.php';
?>

<div class="container">
  <?php if (isset($_SESSION['success-msg'])) : ?>
    <p class="alert alert-success">
      <?= $_SESSION['success-msg']; ?>
      <?php unset($_SESSION['success-msg']); ?>
    </p>
  <?php endif; ?>
</div>

<?php
include 'template/footer.php';
?>