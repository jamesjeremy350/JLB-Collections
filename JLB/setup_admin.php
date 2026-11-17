<?php
require 'config.php';

try {
    // Older installations may have created users before the name column was added.
    $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('name', $columns, true)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN name VARCHAR(120) NOT NULL DEFAULT '' AFTER id");
    }

    // Ensure the saved-address table exists even when this is an existing database.
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS customer_addresses (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT NOT NULL,
          recipient_name VARCHAR(120) NOT NULL,
          phone VARCHAR(30) NOT NULL,
          region VARCHAR(120) NOT NULL,
          province VARCHAR(120) NOT NULL,
          city VARCHAR(120) NOT NULL,
          barangay VARCHAR(120) NOT NULL,
          street VARCHAR(180) NOT NULL,
          postal_code VARCHAR(12) NOT NULL,
          landmark VARCHAR(180) DEFAULT '',
          is_default TINYINT(1) NOT NULL DEFAULT 1,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          CONSTRAINT fk_customer_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
          INDEX idx_customer_address_default (user_id, is_default)
        ) ENGINE=InnoDB
    ");

    $email = 'AdminJLB@gmail.com';
    $password = 'Admin123J';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password_hash, role)
        VALUES ('JLB Administrator', ?, ?, 'admin')
        ON DUPLICATE KEY UPDATE
            name = VALUES(name),
            password_hash = VALUES(password_hash),
            role = 'admin'
    ");
    $stmt->execute([$email, $hash]);
    ?>
    <!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin setup</title><link rel="stylesheet" href="style.css"></head>
    <body><main class="formbox">
      <div class="eyebrow">JLB Collection's</div><h1>Admin account ready</h1>
      <div class="notice">The administrator account and saved delivery address feature are ready.</div>
      <p><b>Admin Gmail:</b> <?=e($email)?></p>
      <p><b>Password:</b> <?=e($password)?></p>
      <p class="muted">Now open <a href="admin_login.php">the admin login page</a>. For safety, delete <b>setup_admin.php</b> from your project folder after this page confirms success.</p>
    </main></body></html>
    <?php
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Admin setup failed. Check that database.sql was imported and config.php uses the correct database. Details: ' . e($e->getMessage());
}
