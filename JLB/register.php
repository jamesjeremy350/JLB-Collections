<?php
require 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || mb_strlen($name) > 120) {
        $err = 'Please enter your name (up to 120 characters).';
    } elseif (!$email || strtolower(substr($email, -10)) !== '@gmail.com') {
        $err = 'Please enter a valid Gmail address.';
    } elseif (strlen($password) < 8 || $password !== $confirm) {
        $err = 'Passwords must match and contain at least 8 characters.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1');
            exit;
        } catch (Throwable $e) {
            $err = 'That Gmail address is already registered.';
        }
    }
}

$page_title = 'Customer sign in';
require 'header.php';
?>
<div class="formbox">
    <div class="eyebrow">Become part of the collection</div>
    <h1>Sign in</h1>
    <p class="muted">Create your JLB customer account to explore the collection and place orders.</p>

    <?php if ($err): ?>
        <div class="notice"><?= e($err) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full name</label>
        <input name="name" required maxlength="120" placeholder="Your name" value="<?= e($_POST['name'] ?? '') ?>">

        <label>Gmail account</label>
        <input name="email" type="email" required placeholder="you@gmail.com" value="<?= e($_POST['email'] ?? '') ?>">

        <label>Password</label>
        <input name="password" type="password" required minlength="8" placeholder="At least 8 characters">

        <label>Confirm password</label>
        <input name="confirm" type="password" required minlength="8" placeholder="Re-enter your password">

        <button class="btn" type="submit">Create account</button>
    </form>
    <p class="muted">Already have a customer account? <a href="login.php">Log in here</a>.</p>
</div>
<?php require 'footer.php'; ?>
