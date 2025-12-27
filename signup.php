<?php
// signup.php
require_once 'config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed. Email or Username might already exist.";
    }
}
require 'views/header.php';
?>

<div class="container" style="max-width: 400px; margin-top: 50px;">
    <div class="card">
        <h2>Sign Up</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div style="margin-bottom: 15px;">
                <label>Username</label>
                <input type="text" name="username" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px;">
            </div>
            <button type="submit" class="btn">Register</button>
            <p style="margin-top: 10px;">Have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>

<?php require 'views/footer.php'; ?>
