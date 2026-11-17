<?php
require 'config.php';
require_login();
if (is_admin()) { header('Location: admin.php'); exit; }
$featured = $pdo->query("SELECT * FROM products WHERE is_active=1 ORDER BY price DESC LIMIT 1")->fetch();
$products = $pdo->query("SELECT * FROM products WHERE is_active=1 ORDER BY created_at DESC, id DESC LIMIT 8")->fetchAll();
$page_title = 'Collections';
require 'header.php';
?>
<style>
.collection-hero{min-height:430px;padding:7vw;display:flex;align-items:center;color:white;background:linear-gradient(90deg,rgba(0,0,0,.82),rgba(0,0,0,.18)),url('https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1800&q=90') center/cover}
.collection-hero h1{font-size:clamp(44px,7vw,82px);line-height:.92;text-transform:uppercase;margin:18px 0;max-width:680px}
.collection-hero p{max-width:420px;line-height:1.8;color:#eee}
.featured-luxe{display:grid;grid-template-columns:1.1fr .9fr;background:#0C3B2E;color:white;margin:42px auto;max-width:1200px}
.featured-luxe img{width:100%;height:100%;min-height:390px;object-fit:cover}
.featured-copy{padding:clamp(25px,5vw,65px);display:flex;flex-direction:column;justify-content:center}
.featured-copy h2{font-size:clamp(30px,4vw,52px);text-transform:uppercase;line-height:1;margin:15px 0}
.collection-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.collection-tile{position:relative;min-height:260px;background:#222;overflow:hidden;color:white}
.collection-tile img{width:100%;height:260px;object-fit:cover;transition:transform .5s}
.collection-tile:hover img{transform:scale(1.06)}
.collection-tile span{position:absolute;left:15px;bottom:15px;text-transform:uppercase;letter-spacing:.15em;font-size:12px;background:rgba(0,0,0,.55);padding:10px}
@media(max-width:760px){.featured-luxe{grid-template-columns:1fr}.featured-luxe img{min-height:260px}.collection-grid{grid-template-columns:repeat(2,1fr)}}
</style>
<section class="collection-hero">
 <div><div class="eyebrow">JLB Collection's · Curated streetwear</div><h1>Made to move.<br>Built to stand out.</h1>
 <p>Discover the pieces that define our collection — everyday essentials, signature details, and confident streetwear inspired by Jeremy, Bless, and Lester.</p>
 <a class="btn" href="shop.php">Explore the shop</a></div>
</section>
<?php if ($featured): ?>
<section class="featured-luxe">
 <img src="<?=e($featured['image_url'])?>" alt="<?=e($featured['name'])?>">
 <div class="featured-copy"><div class="eyebrow">The standout piece · Highest priced item</div>
  <h2><?=e($featured['name'])?></h2><p><?=e($featured['description'])?></p>
  <h2><?=money($featured['price'])?></h2><a class="btn" href="product.php?id=<?=(int)$featured['id']?>">View featured product</a>
 </div>
</section>
<?php endif; ?>
<section class="wrap">
 <div class="sectionhead"><div><div class="eyebrow">Explore the edit</div><h2>Collection highlights</h2></div><a href="shop.php">View all products →</a></div>
 <div class="collection-grid">
 <?php foreach ($products as $product): ?>
  <a class="collection-tile" href="product.php?id=<?=(int)$product['id']?>"><img src="<?=e($product['image_url'])?>" alt="<?=e($product['name'])?>"><span><?=e($product['name'])?> · <?=money($product['price'])?></span></a>
 <?php endforeach; ?>
 </div>
</section>
<?php require 'footer.php'; ?>
