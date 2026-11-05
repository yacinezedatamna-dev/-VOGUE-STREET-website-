<?php
require_once dirname(__DIR__).'/app/init.php';require_once dirname(__DIR__).'/app/layout.php';$number=$_SESSION['order_success']??null;if(!$number)go('/index.php');unset($_SESSION['order_success']);page_header(t('success'));?>
<div class="empty"><h1>✓</h1><h2><?=h(t('success'))?></h2><strong><?=h($number)?></strong><p><?=h(t('cod'))?></p><a class="btn" href="/index.php"><?=h(t('store'))?></a></div><?php page_footer();?>
