<?php
require_once dirname(__DIR__).'/app/init.php';require_once dirname(__DIR__).'/app/layout.php';
$pdo=db();$q=trim((string)($_GET['q']??''));$sql="SELECT * FROM products WHERE is_active=1";$args=[];
if($q!==''){$sql.=" AND (name_ar LIKE ? OR name_fr LIKE ?)";$like='%'.mb_substr($q,0,80).'%';$args=[$like,$like];}
$sql.=" ORDER BY created_at DESC LIMIT 40";$stmt=$pdo->prepare($sql);$stmt->execute($args);$products=$stmt->fetchAll();
page_header(t('store'));?>
<section class="hero"><div class="hero-inner"><span class="tag">NOVA STORE · ALGERIA</span><h1><?=h(t('hero'))?></h1><p><?=h(t('hero_text'))?></p><a class="btn light" href="#products"><?=h(t('shop'))?> ↗</a></div></section>
<section class="section" id="products"><div class="section-head"><div><span class="kicker">NOVA PICKS</span><h2><?=h(t('featured'))?></h2></div></div>
<form class="toolbar" method="get"><div class="search-wrap"><input class="search" type="search" name="q" value="<?=h($q)?>" placeholder="<?=h(t('search'))?>"><button class="btn" type="submit">⌕</button></div></form>
<?php if($products):?><div class="grid"><?php foreach($products as $p):?><article class="card"><a class="photo" href="/product.php?id=<?=(int)$p['id']?>"><img src="<?=h($p['image_url']?:'')?>" alt="<?=h(product_name($p))?>" loading="lazy"></a><div class="card-body"><a class="card-title" href="/product.php?id=<?=(int)$p['id']?>"><?=h(product_name($p))?></a><div class="price"><?=h(cash($p['price']))?></div><p class="muted"><?=h(t('stock'))?>: <?=(int)$p['stock']?></p><a class="btn sm" href="/product.php?id=<?=(int)$p['id']?>"><?=h(t('details'))?> ↗</a></div></article><?php endforeach;?></div><?php else:?><div class="empty"><?=h(t('empty_products'))?></div><?php endif;?></section>
<?php page_footer();?>
