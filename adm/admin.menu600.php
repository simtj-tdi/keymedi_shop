<?php
if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;

$menu['menu600'] = array (
    array('600000', '확장', G5_ADMIN_URL.'/exp_shop/member_list.php', 'shop_stats'), 
	array('600400', '회원관리(확장)', G5_ADMIN_URL.'/exp_shop/member_list.php', 'shop_stats') , 
	array('600100', '분류관리(확장)', G5_ADMIN_URL.'/exp_shop/categorylist.php', 'shop_stats'),
	array('600200', '마스터상품관리(확장)', G5_ADMIN_URL.'/exp_shop/itemlist.php', 'shop_stats') ,
	array('600300', '셀러상품관리(확장)', G5_ADMIN_URL.'/exp_shop/itemlist2.php', 'shop_stats') ,
	
	array('600500', '재고수량 및 판매금관리(확장)', G5_ADMIN_URL.'/exp_shop/cnt_list.php', 'shop_stats'),
	array('600600', '상품유형관리(확장)', G5_ADMIN_URL.'/exp_shop/itemtypelist.php', 'shop_stats')  ,
	
);
?>