<?php
require_once dirname(__DIR__).'/app/init.php';require_once dirname(__DIR__).'/app/layout.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)?:0;$stmt=db()->prepare("SELECT * FROM products WHERE id=? AND is_active=1");$stmt->execute([$id]);$p=$stmt->fetch();
if(!$p){http_response_code(404);page_header(t('products'));echo '<div class="empty">404</div>';page_footer();exit;}
page_header(product_name($p));?>
<div class="detail"><div class="detail-photo"><img src="<?=h($p['image_url']?:'')?>" alt="<?=h(product_name($p))?>"></div><div><span class="kicker">NOVA STORE</span><h1><?=h(product_name($p))?></h1><div class="detail-price"><?=h(cash($p['price']))?></div><p><?=nl2br(h(($_SESSION['lang']??'ar')==='fr'?$p['description_fr']:$p['description_ar']))?></p><p class="muted"><?=h(t('stock'))?>: <?=(int)$p['stock']?></p>
<?php if((int)$p['stock']>0):?><form method="post" action="/cart.php" class="stack"><?=csrf_field()?><input type="hidden" name="action" value="add"><input type="hidden" name="id" value="<?=(int)$p['id']?>"><label><?=h(t('quantity'))?><input class="field" type="number" name="qty" min="1" max="<?=(int)$p['stock']?>" value="1" required></label><button class="btn" type="submit"><?=h(t('add'))?> +</button></form><?php endif;?></div></div>
<?php page_footer();?>
