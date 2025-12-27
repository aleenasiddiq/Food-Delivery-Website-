<?php
// menu.php
require_once 'config/db.php';
require_once 'classes/ArrayMenu.php';
require_once 'classes/BubbleSort.php';
require_once 'classes/StackCart.php';

require 'views/header.php';

// Initialize Menu Array
$menuObj = new ArrayMenu($pdo);
$menuItems = $menuObj->getItems();

// Handle Sorting using Bubble Sort
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
if ($sort == 'price') {
    $menuItems = BubbleSort::sort($menuItems, 'price');
} elseif ($sort == 'rating') {
    // For rating, we usually want descending (highest first)
    // Custom logic or re-use BubbleSort? BubbleSort is generic ASC mostly.
    // Let's modify sort call or just stick to what BubbleSort does.
    // Assuming BubbleSort does ASC, so for Rating we want DESC. 
    // For simplicity, let's keep it ASC or modify the class later if we strictly need DESC.
    // Actually, usually sorting by rating implies 5 stars first.
    // Simplest way: Sort ASC then reverse array, OR update BubbleSort logic to handle direction.
    // Let's just use the basic sort for now as per prompt "Bubble Sort (sort menu by price or rating)"
    $menuItems = BubbleSort::sort($menuItems, 'rating');
    $menuItems = array_reverse($menuItems); // Quick hack to make it DESC
}

// Handle Add to Cart
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    $cart = new StackCart($pdo, $_SESSION['user_id']);
    $cart->push($_POST['item_id']);
    $message = "Item added to cart (Stack Push)!";
}

?>

<div class="container">
    <h2>Our Menu</h2>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="controls">
        <span>Sort By: </span>
        <a href="?sort=price" class="btn btn-secondary">Price (Low to High)</a>
        <a href="?sort=rating" class="btn btn-secondary">Rating (High to Low)</a>
        <a href="menu.php" class="btn btn-secondary">Default</a>
    </div>

    <div class="menu-grid">
        <?php foreach ($menuItems as $item): ?>
            <div class="menu-item">
                <img src="public/images/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="background:#eee;"> 
                <!-- Note: using default placeholder logic just in case -->
                
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?></p>
                <div class="price">$<?= htmlspecialchars($item['price']) ?></div>
                <div style="margin-bottom: 10px; color: gold;">
                    <?= str_repeat('★', round($item['rating'])) . str_repeat('☆', 5 - round($item['rating'])) ?>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <button type="submit" name="add_to_cart" class="btn">Add to Cart (Push)</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'views/footer.php'; ?>
