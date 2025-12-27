<?php
// order_success.php
require 'views/header.php';
?>

<div class="container" style="text-align: center; padding: 50px;">
    <h1 style="color: green;">Order Placed Successfully!</h1>
    <p>Your Order ID is #<?= htmlspecialchars($_GET['order_id'] ?? 'Unknown') ?></p>
    <p>It has been added to the <strong>Order Queue</strong>.</p>
    <br>
    <a href="index.php" class="btn">Back to Home</a>
</div>

<?php require 'views/footer.php'; ?>
