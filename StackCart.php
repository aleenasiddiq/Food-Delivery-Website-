<?php
// classes/StackCart.php

class StackCart {
    private $pdo;
    private $userId;

    public function __construct($pdo, $userId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
    }

    // Push: Add item to the top of the stack (Last In)
    public function push($itemId) {
        $stmt = $this->pdo->prepare("INSERT INTO cart_items (user_id, item_id, added_at) VALUES (?, ?, NOW())");
        $stmt->execute([$this->userId, $itemId]);
    }

    // Pop: Remove the most recently added item (First Out)
    public function pop() {
        // Find the latest item for this user
        $stmt = $this->pdo->prepare("SELECT id FROM cart_items WHERE user_id = ? ORDER BY added_at DESC LIMIT 1");
        $stmt->execute([$this->userId]);
        $lastItem = $stmt->fetch();

        if ($lastItem) {
            $deleteStmt = $this->pdo->prepare("DELETE FROM cart_items WHERE id = ?");
            $deleteStmt->execute([$lastItem['id']]);
            return true;
        }
        return false;
    }

    // Get all items in the stack (for display)
    // We display them in the order they were added (or reverse to show stack top)
    public function getCartItems() {
        $stmt = $this->pdo->prepare("
            SELECT ci.id as cart_id, m.*, ci.added_at 
            FROM cart_items ci 
            JOIN menu_items m ON ci.item_id = m.id 
            WHERE ci.user_id = ? 
            ORDER BY ci.added_at DESC
        ");
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll();
    }

    public function clear() {
         $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
         $stmt->execute([$this->userId]);
    }
}
?>
