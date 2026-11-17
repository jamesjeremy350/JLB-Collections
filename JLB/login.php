<?php
require 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // One shared login page for both administrators and customers.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'] ?? '';
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: shop.php');
        }
        exit;
    }

    $err = 'Incorrect Gmail or password. Please check your details and try again.';
}

$page_title = 'Login';
require 'header.php';
?>
<div class="formbox">
    <div class="eyebrow">Welcome back to JLB</div>
    <h1>Log in</h1>
    <p class="muted">Customers and administrators can use this same login page. You will be taken to the correct page based on your account.</p>

    <?php if (isset($_GET['registered'])): ?>
        <div class="notice">Your customer account has been created. You can now log in.</div>
    <?php endif; ?>
    <?php if ($err): ?>
        <div class="notice"><?=e($err)?></div>
    <?php endif; ?>

    <form method="post">
        <label>Gmail account</label>
        <input type="email" name="email" required autocomplete="username" placeholder="you@gmail.com">

        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">

        <button class="btn" type="submit">Log in</button>
    </form>
    <p class="muted">New customer? <a href="register.php">Create a new account</a>.</p>
</div>
<?php require 'footer.php'; ?>
