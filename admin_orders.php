<?php
// admin_orders.php (Admin Dashboard)
require_once 'config/db.php';
require_once 'classes/QueueOrder.php';

require 'views/header.php';

// Check Admin Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container'><div class='alert alert-error'>Access Denied. Admins only.</div></div>";
    require 'views/footer.php';
    exit;
}

$queue = new QueueOrder($pdo);
$message = '';

// Handle Dequeue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_next'])) {
    $processedOrder = $queue->dequeue();
    if ($processedOrder) {
        $message = "Order #" . $processedOrder['id'] . " processed (Dequeued)!";
    } else {
        $message = "No pending orders in the queue.";
    }
}

$pendingOrders = $queue->getPendingOrders();
?>

<div class="container">
    <h2>Admin Dashboard - Order Queue (FIFO)</h2>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="controls">
        <form method="POST">
            <button type="submit" name="process_next" class="btn">Process Next Order (Dequeue)</button>
        </form>
    </div>

    <h3>Pending Orders</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Time Placed</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingOrders as $order): ?>
            <tr>
                <td>#<?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['username']) ?></td>
                <td>$<?= htmlspecialchars($order['total_amount']) ?></td>
                <td><?= htmlspecialchars($order['created_at']) ?></td>
                <td><span style="color: orange;"><?= strtoupper($order['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($pendingOrders)): ?>
                <tr><td colspan="5" style="text-align:center;">Queue is empty.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'views/footer.php'; ?>
