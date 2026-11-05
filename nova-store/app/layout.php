<?php
function page_header(string $title): void {
 $lang=$_SESSION['lang']??'ar';$isFr=$lang==='fr';
 ?><!doctype html><html lang="<?=h($lang)?>" dir="<?=$isFr?'ltr':'rtl'?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#143e35"><title><?=h($title)?> | NOVA STORE</title><link rel="stylesheet" href="/assets/style.css"><script src="/assets/app.js" defer></script></head><body>
 <div class="topbar"><?=$isFr?'Livraison en Algérie · Paiement à la livraison':'التوصيل داخل الجزائر · الدفع عند الاستلام'?></div>
 <header class="header"><div class="container nav"><a class="brand" href="/index.php"><b>N</b>NOVA STORE</a><button class="menu" data-menu aria-expanded="false">☰</button><nav data-nav><a href="/index.php"><?=h(t('store'))?></a><a href="/index.php#products"><?=h(t('products'))?></a><a href="/cart.php"><?=h(t('cart'))?> (<?=array_sum(array_map('intval',$_SESSION['cart']??[]))?>)</a><?php if((current_user()['role']??'')==='admin'):?><a href="/admin.php"><?=h(t('admin'))?></a><?php endif;?></nav><div class="nav-right"><a class="lang" href="<?=h($_SERVER['PHP_SELF'])?>?lang=<?=$isFr?'ar':'fr'?>"><?=$isFr?'عربي':'FR'?></a><?php if(current_user()):?><form method="post" action="/logout.php"><?=csrf_field()?><button class="lang" type="submit"><?=h(t('logout'))?></button></form><?php else:?><a class="lang" href="/auth.php?mode=login"><?=h(t('login'))?></a><?php endif;?></div></div></header><main class="container">
 <?php if($message=flash()):?><div class="flash"><?=h($message)?></div><?php endif;
}
function page_footer(): void { ?><footer class="footer"><?=h(t('footer'))?> · © <?=date('Y')?> NOVA STORE</footer></main></body></html><?php }
