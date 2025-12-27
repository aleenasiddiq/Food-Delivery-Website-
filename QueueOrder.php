<?php
// classes/QueueOrder.php

class QueueOrder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Enqueue: Add order to the end of the queue (when user places order)
    public function enqueue($userId, $totalAmount, $cartItems) {
        try {
            $this->pdo->beginTransaction();

            // 1. Create Order
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $this->pdo->lastInsertId();

            // 2. Add Order Details
            $detailStmt = $this->pdo->prepare("INSERT INTO order_details (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
            // Simplify for this demo: flatten cart items. 
            // If StackCart has multiple same items, we treat them individually or could aggregate.
            // Let's just dump the cart items as single units.
            foreach ($cartItems as $item) {
                // Assuming $item has 'id' (menu_item_id) and 'price'
                $detailStmt->execute([$orderId, $item['id'], 1, $item['price']]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Dequeue: Process the first pending order (FIFO)
    // Admin calls this to "serve" the next order
    public function dequeue() {
        // Find the oldest pending order
        $stmt = $this->pdo->query("SELECT * FROM orders WHERE status = 'pending' ORDER BY created_at ASC LIMIT 1");
        $order = $stmt->fetch();

        if ($order) {
            // Update status to 'completed' (or processing)
            $updateStmt = $this->pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
            $updateStmt->execute([$order['id']]);
            return $order;
        }
        return null; // Queue is empty
    }

    // Peek/View all pending orders
    public function getPendingOrders() {
        $stmt = $this->pdo->query("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE status = 'pending' ORDER BY created_at ASC");
        return $stmt->fetchAll();
    }
}
?>
