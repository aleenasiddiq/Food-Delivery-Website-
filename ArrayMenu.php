<?php
// classes/ArrayMenu.php

class ArrayMenu {
    private $items = [];
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loadFromDatabase();
    }

    // Load items from DB into the array
    private function loadFromDatabase() {
        $stmt = $this->pdo->query("SELECT * FROM menu_items");
        $this->items = $stmt->fetchAll();
    }

    // Return the array of items
    public function getItems() {
        return $this->items;
    }

    // Set items (useful after sorting)
    public function setItems($items) {
        $this->items = $items;
    }
}
?>
