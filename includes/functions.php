<?php
require_once __DIR__.'/db.php';
function e($v):string{return htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');}
function url(string $p=''):string{return BASE_URL.($p?'/'.ltrim($p,'/'):'');}
function is_logged_in():bool{return isset($_SESSION['user']);}
function is_admin():bool{return is_logged_in()&&($_SESSION['user']['role']??'')==='admin';}
function set_flash(string $m,string $t='success'):void{$_SESSION['flash']=['message'=>$m,'type'=>$t];}
function flash_html():string{$f=$_SESSION['flash']??null;unset($_SESSION['flash']);return $f?'<div class="alert alert-'.e($f['type']).'">'.e($f['message']).'</div>':'';}
function require_login():void{if(!is_logged_in()){set_flash('Please log in first.','error');header('Location: '.url('login.php'));exit;}}
function require_admin():void{if(!is_admin()){set_flash('Admin access is required.','error');header('Location: '.url('login.php'));exit;}}
function cart_count():int{return array_sum($_SESSION['cart']??[]);}
function product_image(?string $p):string{return $p&&is_file(__DIR__.'/../'.$p)?url($p):'';}
