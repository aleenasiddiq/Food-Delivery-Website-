<?php
// index.php
require_once 'config/db.php';
require 'views/header.php';
?>

<div style="text-align: center; padding: 50px 20px;">
    <h1>Welcome to FoodieDSA</h1>
    <p>The smartest food delivery system powered by Data Structures & Algorithms.</p>
    <br>
    <a href="menu.php" class="btn">Browse Menu</a>
</div>

<div class="container">
    <div class="card">
        <h3>How it Works? (DSA Under the Hood)</h3>
        <ul>
            <li><strong>Menu:</strong> Stored in an <em>Array</em>, Sorted using <em>Bubble Sort</em>.</li>
            <li><strong>Cart:</strong> Managed using a <em>Stack</em> (LIFO) allowing Undo operations.</li>
            <li><strong>Orders:</strong> Processed using a <em>Queue</em> (FIFO) for fair service.</li>
        </ul>
    </div>
</div>

<?php require 'views/footer.php'; ?>
