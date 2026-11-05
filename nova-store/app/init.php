<?php
declare(strict_types=1);
$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
ini_set('session.use_strict_mode','1');
ini_set('session.use_only_cookies','1');
ini_set('session.use_trans_sid','0');
session_name('NOVA_SESSID');
session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$https,'httponly'=>true,'samesite'=>'Lax']);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once dirname(__DIR__).'/config/database.php';

$dictionary = [
'ar'=>[
'store'=>'المتجر','products'=>'المنتجات','cart'=>'السلة','login'=>'دخول','register'=>'إنشاء حساب','logout'=>'خروج',
'hero'=>'كل ما تحتاجه، في مكان واحد','hero_text'=>'تسوّق بسهولة مع منتجات مختارة وأسعار واضحة وتوصيل إلى باب منزلك.','shop'=>'تسوق الآن',
'featured'=>'منتجات مختارة','all'=>'كل المنتجات','search'=>'ابحث عن منتج...','details'=>'التفاصيل','add'=>'أضف إلى السلة',
'price'=>'السعر','quantity'=>'الكمية','total'=>'المجموع','empty'=>'سلتك فارغة حالياً.','continue'=>'مواصلة التسوق','checkout'=>'إتمام الطلب',
'name'=>'الاسم الكامل','email'=>'البريد الإلكتروني','password'=>'كلمة المرور','phone'=>'رقم الهاتف','city'=>'المدينة','address'=>'العنوان بالتفصيل','notes'=>'ملاحظات',
'place_order'=>'تأكيد الطلب','cod'=>'الدفع عند الاستلام','welcome'=>'مرحباً بك','have_account'=>'لديك حساب؟','no_account'=>'ليس لديك حساب؟',
'admin'=>'لوحة الإدارة','dashboard'=>'الرئيسية','manage_products'=>'إدارة المنتجات','manage_orders'=>'إدارة الطلبات','save'=>'حفظ','delete'=>'حذف','status'=>'الحالة',
'pending'=>'قيد الانتظار','confirmed'=>'مؤكد','shipped'=>'تم الشحن','delivered'=>'تم التسليم','cancelled'=>'ملغي','success'=>'تم استلام طلبك بنجاح. رقم الطلب: ',
'stock'=>'المخزون','category'=>'القسم','add_product'=>'إضافة منتج','name_ar'=>'اسم المنتج بالعربية','name_fr'=>'اسم المنتج بالفرنسية',
'desc_ar'=>'الوصف بالعربية','desc_fr'=>'الوصف بالفرنسية','image'=>'رابط الصورة','error'=>'حدث خطأ. تحقق من البيانات وحاول مجدداً.',
'low_stock'=>'الكمية المطلوبة غير متوفرة.','footer'=>'NOVA STORE — تجربة تسوق سهلة وآمنة.','empty_products'=>'لا توجد منتجات حالياً.',
'admin_only'=>'سجّل الدخول بحساب الإدارة.','order'=>'الطلب','customer'=>'العميل','date'=>'التاريخ','currency'=>'دج','added'=>'تمت إضافة المنتج إلى السلة.'
],
'fr'=>[
'store'=>'Boutique','products'=>'Produits','cart'=>'Panier','login'=>'Connexion','register'=>'Inscription','logout'=>'Déconnexion',
'hero'=>'Tout ce qu’il vous faut, au même endroit','hero_text'=>'Achetez facilement avec une sélection soignée et une livraison chez vous.','shop'=>'Acheter',
'featured'=>'À la une','all'=>'Tous les produits','search'=>'Rechercher un produit...','details'=>'Détails','add'=>'Ajouter au panier',
'price'=>'Prix','quantity'=>'Quantité','total'=>'Total','empty'=>'Votre panier est vide.','continue'=>'Continuer vos achats','checkout'=>'Commander',
'name'=>'Nom complet','email'=>'E-mail','password'=>'Mot de passe','phone'=>'Téléphone','city'=>'Ville','address'=>'Adresse complète','notes'=>'Notes',
'place_order'=>'Confirmer la commande','cod'=>'Paiement à la livraison','welcome'=>'Bienvenue','have_account'=>'Déjà un compte ?','no_account'=>'Pas encore de compte ?',
'admin'=>'Administration','dashboard'=>'Tableau de bord','manage_products'=>'Gérer les produits','manage_orders'=>'Gérer les commandes','save'=>'Enregistrer','delete'=>'Supprimer','status'=>'Statut',
'pending'=>'En attente','confirmed'=>'Confirmée','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée','success'=>'Commande reçue. Numéro : ',
'stock'=>'Stock','category'=>'Catégorie','add_product'=>'Ajouter un produit','name_ar'=>'Nom arabe','name_fr'=>'Nom français',
'desc_ar'=>'Description arabe','desc_fr'=>'Description française','image'=>'URL de l’image','error'=>'Une erreur est survenue. Vérifiez les données et réessayez.',
'low_stock'=>'Stock insuffisant.','footer'=>'NOVA STORE — Une expérience d’achat simple et sûre.','empty_products'=>'Aucun produit pour le moment.',
'admin_only'=>'Connectez-vous avec un compte administrateur.','order'=>'Commande','customer'=>'Client','date'=>'Date','currency'=>'DA','added'=>'Produit ajouté au panier.'
]];
if (isset($_GET['lang']) && in_array($_GET['lang'],['ar','fr'],true)) $_SESSION['lang']=$_GET['lang'];
if (!isset($_SESSION['lang'])) $_SESSION['lang']='ar';
function t(string $key): string { global $dictionary; return $dictionary[$_SESSION['lang']??'ar'][$key]??$key; }
function h(mixed $value): string { return htmlspecialchars((string)($value??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function go(string $path): never { header('Location: '.$path,true,303); exit; }
function csrf_field(): string { if(empty($_SESSION['_csrf']))$_SESSION['_csrf']=bin2hex(random_bytes(32)); return '<input type="hidden" name="_csrf" value="'.h($_SESSION['_csrf']).'">'; }
function verify_csrf(): void { $sent=$_POST['_csrf']??''; if(!is_string($sent)||!hash_equals($_SESSION['_csrf']??'',$sent)){http_response_code(419);exit('Invalid form token. Refresh and retry.');} }
function flash(?string $message=null): ?string { if($message!==null){$_SESSION['_flash']=$message;return null;} $value=$_SESSION['_flash']??null;unset($_SESSION['_flash']);return is_string($value)?$value:null; }
function current_user(): ?array { return isset($_SESSION['user'])&&is_array($_SESSION['user'])?$_SESSION['user']:null; }
function require_admin(): void { if((current_user()['role']??'')!=='admin'){flash(t('admin_only'));go('/auth.php?mode=login');} }
function cash(mixed $amount): string { return number_format((float)$amount,0,',',' ').' '.t('currency'); }
function cart_items(PDO $pdo): array {
 $cart=$_SESSION['cart']??[];if(!is_array($cart)||!$cart)return [];$ids=[];
 foreach($cart as $id=>$qty)if(ctype_digit((string)$id)&&(int)$id>0&&(int)$qty>0)$ids[(int)$id]=min(99,(int)$qty);
 if(!$ids)return [];$marks=implode(',',array_fill(0,count($ids),'?'));
 $stmt=$pdo->prepare("SELECT id,name_ar,name_fr,price,stock,image_url FROM products WHERE is_active=1 AND id IN ($marks)");
 $stmt->execute(array_keys($ids));$rows=$stmt->fetchAll();
 foreach($rows as &$row){$row['qty']=$ids[(int)$row['id']]??0;$row['line_total']=(float)$row['price']*$row['qty'];}unset($row);return $rows;
}
function product_name(array $p): string { return ($_SESSION['lang']??'ar')==='fr'?(string)$p['name_fr']:(string)$p['name_ar']; }
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; img-src 'self' https://images.unsplash.com data:; style-src 'self'; script-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; object-src 'none'");
