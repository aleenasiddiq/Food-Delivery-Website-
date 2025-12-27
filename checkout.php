<?php
// checkout.php
require_once 'config/db.php';
require_once 'classes/StackCart.php';
require_once 'classes/QueueOrder.php';

require 'views/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = new StackCart($pdo, $_SESSION['user_id']);
$cartItems = $cart->getCartItems();

if (empty($cartItems)) {
    header("Location: cart.php");
    exit;
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'];
}

// Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queue = new QueueOrder($pdo);
    try {
        // Enqueue Order
        $orderId = $queue->enqueue($_SESSION['user_id'], $total, $cartItems);
        
        // Clear Stack (Cart) after order
        $cart->clear();

        header("Location: order_success.php?order_id=" . $orderId);
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-error'>Order failed: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container">
    <h2>Checkout</h2>
    <div class="card">
        <h3>Order Summary</h3>
        <p>Total Items: <?= count($cartItems) ?></p>
        <p>Total Amount: <strong>$<?= number_format($total, 2) ?></strong></p>
        <hr style="margin: 20px 0;">
        
        <form method="POST">
            <h3>Payment Details</h3>
            <p>(This is a demo, no real payment is processed)</p>
            <div style="margin-bottom: 15px;">
                 <label>Card Number</label>
                 <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" style="width:100%; padding: 8px;">
            </div>
            <button type="submit" class="btn">Place Order (Enqueue)</button>
        </form>
    </div>
</div>

<?php require 'views/footer.php'; ?>
