<?php
require 'config.php';
require_login();
if (is_admin()) { header('Location: admin.php'); exit; }

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header('Location: cart.php'); exit; }
$ids = array_keys($cart);
$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in) AND is_active=1");
$stmt->execute($ids);
$items = $stmt->fetchAll();
$total = 0;
foreach ($items as $product) $total += $product['price'] * ($cart[$product['id']] ?? 0);

$addressStmt = $pdo->prepare("SELECT * FROM customer_addresses WHERE user_id=? ORDER BY is_default DESC, updated_at DESC LIMIT 1");
$addressStmt->execute([$_SESSION['user_id']]);
$saved = $addressStmt->fetch() ?: [];
$fields = ['recipient_name','phone','region','province','city','barangay','street','postal_code','landmark'];
$values = [];
foreach ($fields as $field) $values[$field] = $_POST[$field] ?? ($saved[$field] ?? '');
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['recipient_name','phone','region','province','city','barangay','street','postal_code'] as $required) {
        if (trim($values[$required]) === '') { $err = 'Please complete all required delivery address fields.'; break; }
    }
    $payment = $_POST['payment'] ?? 'Cash on Delivery';
    if (!in_array($payment, ['GCash','Maya','Cash on Delivery'], true)) $payment = 'Cash on Delivery';

    if ($err === '') {
        $addressText = $values['recipient_name']." | ".$values['phone']." | ".$values['street'].", ".$values['barangay'].", ".$values['city'].", ".$values['province'].", ".$values['region']." ".$values['postal_code'];
        if (trim($values['landmark']) !== '') $addressText .= " | Landmark: ".$values['landmark'];

        try {
            $pdo->beginTransaction();
            foreach ($items as $product) {
                if ($product['stock'] < ($cart[$product['id']] ?? 0)) throw new Exception('Not enough stock for '.$product['name']);
            }

            $pdo->prepare("UPDATE customer_addresses SET is_default=0 WHERE user_id=?")->execute([$_SESSION['user_id']]);
            $addressCheck = $pdo->prepare("SELECT id FROM customer_addresses WHERE user_id=? ORDER BY id DESC LIMIT 1");
            $addressCheck->execute([$_SESSION['user_id']]);
            $existingAddress = $addressCheck->fetchColumn();

            if ($existingAddress) {
                $sql = "UPDATE customer_addresses SET recipient_name=?,phone=?,region=?,province=?,city=?,barangay=?,street=?,postal_code=?,landmark=?,is_default=1 WHERE id=? AND user_id=?";
                $pdo->prepare($sql)->execute([$values['recipient_name'],$values['phone'],$values['region'],$values['province'],$values['city'],$values['barangay'],$values['street'],$values['postal_code'],$values['landmark'],$existingAddress,$_SESSION['user_id']]);
            } else {
                $sql = "INSERT INTO customer_addresses(user_id,recipient_name,phone,region,province,city,barangay,street,postal_code,landmark,is_default) VALUES(?,?,?,?,?,?,?,?,?,?,1)";
                $pdo->prepare($sql)->execute([$_SESSION['user_id'],$values['recipient_name'],$values['phone'],$values['region'],$values['province'],$values['city'],$values['barangay'],$values['street'],$values['postal_code'],$values['landmark']]);
            }

            $pdo->prepare("INSERT INTO orders(user_id,total,payment_method,address) VALUES(?,?,?,?)")->execute([$_SESSION['user_id'],$total,$payment,$addressText]);
            $orderId = $pdo->lastInsertId();

            foreach ($items as $product) {
                $quantity = (int)$cart[$product['id']];
                $choice = $_SESSION['choices'][$product['id']] ?? [];
                $pdo->prepare("INSERT INTO order_items(order_id,product_id,product_name,quantity,unit_price,chosen_size,chosen_color,custom_design) VALUES(?,?,?,?,?,?,?,?)")
                    ->execute([$orderId,$product['id'],$product['name'],$quantity,$product['price'],$choice['size']??'',$choice['color']??'',$choice['design']??'']);
                $pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?")->execute([$quantity,$product['id']]);
            }
            $pdo->commit();
            unset($_SESSION['cart'], $_SESSION['choices']);
            header('Location: orders.php?placed=1');
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $err = $e->getMessage();
        }
    }
}
$page_title = 'Checkout';
require 'header.php';
?>
<section class="wrap">
 <div class="formbox" style="max-width:760px">
  <div class="eyebrow">Delivery details</div><h1>Checkout</h1>
  <p class="muted">Your address will be saved for your next JLB order. You can edit it before placing an order.</p>
  <h2>Order total: <?=money($total)?></h2>
  <?php if ($err): ?><div class="notice"><?=e($err)?></div><?php endif; ?>
  <form method="post">
   <label>Recipient full name *</label><input name="recipient_name" required maxlength="120" value="<?=e($values['recipient_name'])?>">
   <label>Mobile number *</label><input name="phone" type="tel" required maxlength="30" placeholder="09XX XXX XXXX" value="<?=e($values['phone'])?>">
   <div class="grid2">
    <div><label>Region *</label><input name="region" required value="<?=e($values['region'])?>" placeholder="e.g. Central Visayas"></div>
    <div><label>Province *</label><input name="province" required value="<?=e($values['province'])?>" placeholder="e.g. Cebu"></div>
    <div><label>City / Municipality *</label><input name="city" required value="<?=e($values['city'])?>" placeholder="e.g. Cebu City"></div>
    <div><label>Barangay *</label><input name="barangay" required value="<?=e($values['barangay'])?>"></div>
   </div>
   <label>House number, street, building *</label><input name="street" required value="<?=e($values['street'])?>">
   <div class="grid2">
    <div><label>Postal code *</label><input name="postal_code" required maxlength="12" value="<?=e($values['postal_code'])?>"></div>
    <div><label>Landmark (optional)</label><input name="landmark" value="<?=e($values['landmark'])?>" placeholder="Near..."></div>
   </div>
   <label>Payment method</label>
   <select name="payment"><option value="GCash">GCash</option><option value="Maya">Maya</option><option value="Cash on Delivery" selected>Cash on Delivery</option></select>
   <button class="btn" type="submit">Save address & place order</button>
  </form>
  <p class="muted">GCash and Maya are payment selections only in this version. Payment confirmation will be coordinated with the shop admin; no payment credentials are collected.</p>
 </div>
</section>
<?php require 'footer.php'; ?>
