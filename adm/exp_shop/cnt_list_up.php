<?php
$sub_menu = '600200';
include_once('./_common.php');

if($it_id){
	sql_query("update shop.g5_shop_item set it_stock_qty = '$shop_cnt' , it_price = '$shop_price' where it_id = '{$it_id}' ");
}
if($it_id2){
	sql_query("update shop_skin.g5_shop_item set it_stock_qty = '$shop_skin_cnt' , it_price = '$shop_skin_price' where it_id = '{$it_id2}' ");
}
//echo "update shop_skin.{$g5['g5_shop_item_table']} set it_stock_qty = '$shop_skin_cnt' , it_price = '$shop_skin_price' where it_id = '{$it_id2}' ";
echo "수정했습니다.";

/*
다라메디텍	1285	1539593144	dara004578	종이반창고(마이크로포어/1533-0)	
996

996

13300
*/
?>