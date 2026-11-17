<?php
if (!isset($page_title)) $page_title = "JLB Collection's";
$isCustomer = logged_in() && !is_admin();
$isAdmin = logged_in() && is_admin();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($page_title)?> | JLB Collection's</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php if ($isCustomer): ?>
<header>
  <div class="topline">JLB COLLECTION'S · EVERYDAY STREETWEAR · CEBU, PH</div>
  <nav class="nav">
    <a class="brand" href="shop.php">JLB<small>COLLECTION'S</small></a>
    <div class="navlinks">
      <a href="shop.php">Home</a>
      <a href="shop.php">Shop</a>
      <a href="collections.php">Collections</a>
      <a href="messages.php">Message</a>
      <a href="orders.php">Orders</a>
      <a href="cart.php">Cart (<?=array_sum($_SESSION['cart']??[])?>)</a>
    </div>
    <a class="btn" href="logout.php">Logout</a>
  </nav>
</header>
<?php elseif ($isAdmin): ?>
<header>
  <div class="topline">JLB COLLECTION'S · ADMINISTRATION</div>
  <nav class="nav">
    <a class="brand" href="admin.php">JLB<small>ADMIN PANEL</small></a>
    <a class="btn" href="logout.php">Logout</a>
  </nav>
</header>
<?php endif; ?>
