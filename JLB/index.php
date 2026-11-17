<?php
require 'config.php';
if(logged_in()){ if(is_admin()){header('Location: admin.php');}else{header('Location: shop.php');} exit; }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>JLB Collection's | Welcome</title><link rel="stylesheet" href="style.css">
<style>
body{background:#080a08;color:#fff;min-height:100vh}
.welcome{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px 20px;background:linear-gradient(90deg,rgba(4,12,9,.88),rgba(4,12,9,.45)),url('https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=2000&q=90') center/cover;position:relative}
.welcome:after{content:'';position:absolute;inset:14px;border:1px solid rgba(255,255,255,.25);pointer-events:none}
.inner{position:relative;z-index:1;text-align:center;width:min(760px,100%);animation:rise .9s ease both}
.mark{font:42px Georgia,serif;letter-spacing:.22em}.sub{font-size:10px;letter-spacing:.4em;margin:12px 0 52px;color:#e5e9e1}
h1{font-size:clamp(48px,9vw,100px);line-height:.88;letter-spacing:-.055em;text-transform:uppercase;margin:15px 0 22px}h1 span{color:#d3b68e}
.inner p{max-width:450px;margin:0 auto 32px;color:#e2e5df;line-height:1.8;font-size:14px}
.actions{display:flex;justify-content:center;gap:12px;flex-wrap:wrap}.actions a{min-width:170px;padding:16px 25px;border:1px solid #fff;letter-spacing:.2em;font-size:11px;text-transform:uppercase;font-weight:bold;transition:.25s}
.actions .primary{background:#6D9773;border-color:#6D9773;color:#071b13}.actions a:hover{background:#FFBA00;border-color:#FFBA00;color:#10251c;transform:translateY(-3px)}
.foot{margin-top:70px;font-size:9px;letter-spacing:.25em;color:#d8ded6}
@keyframes rise{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:600px){.sub{margin-bottom:38px}h1{font-size:54px}.foot{margin-top:50px}}
</style></head><body><main class="welcome"><div class="inner">
<div class="mark">JLB</div><div class="sub">COLLECTION'S · JEREMY · BLESS · LESTER</div>
<div class="eyebrow">Streetwear / Culture / You</div><h1>Wear your<br><span>story.</span></h1>
<p>More than clothing. Discover everyday essentials, confident streetwear, and pieces made to express your own style. Welcome to JLB Collection's.</p>
<div class="actions"><a href="login.php">Login</a><a class="primary" href="register.php">Create Account</a></div>
<div class="foot">THREE NAMES. ONE COLLECTION. · EST. JLB</div></div></main></body></html>