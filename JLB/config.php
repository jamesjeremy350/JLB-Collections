<?php
session_start();
$host='localhost'; $db='jlb_collections'; $user='root'; $pass='';
try { $pdo=new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); }
catch(PDOException $e){ die('Database connection failed. Import database.sql and check config.php.'); }
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function logged_in(){ return !empty($_SESSION['user_id']); }
function is_admin(){ return !empty($_SESSION['role']) && $_SESSION['role']==='admin'; }
function require_login(){ if(!logged_in()){ header('Location: login.php'); exit; } }
function require_admin(){ if(!is_admin()){ header('Location: admin_login.php'); exit; } }
function money($n){ return '₱'.number_format((float)$n,2); }