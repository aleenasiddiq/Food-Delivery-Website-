<?php
// cart.php
require_once 'config/db.php';
require_once 'classes/StackCart.php';

require 'views/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = new StackCart($pdo, $_SESSION['user_id']);
$message = '';

// Handle Undo (Pop)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['undo'])) {
    if ($cart->pop()) {
        $message = "Last item removed (Stack Pop)!";
    } else {
        $message = "Cart is empty, nothing to undo.";
    }
}

$cartItems = $cart->getCartItems();
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'];
}
?>

<div class="container">
    <h2>Your Cart</h2>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
        <a href="menu.php" class="btn">Go to Menu</a>
    <?php else: ?>
        <div class="controls">
            <form method="POST">
                <button type="submit" name="undo" class="btn btn-secondary">Undo Last Item (Pop)</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Added At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= htmlspecialchars($item['price']) ?></td>
                    <td><?= htmlspecialchars($item['added_at']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: right;">
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php require 'views/footer.php'; ?>
