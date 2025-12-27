<?php
// login.php
require_once 'config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
require 'views/header.php';
?>

<div class="container" style="max-width: 400px; margin-top: 50px;">
    <div class="card">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px;">
            </div>
            <button type="submit" class="btn">Login</button>
            <p style="margin-top: 10px;">No account? <a href="signup.php">Sign up</a></p>
        </form>
    </div>
</div>

<?php require 'views/footer.php'; ?>
