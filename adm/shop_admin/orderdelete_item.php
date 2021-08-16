<?php

include_once('./_common.php');
$od_id = '99201210144738710';
echo $od_id;

$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
if (!$od) continue;



// 주문상태가 주문이 아니면 건너뜀
//if($od['od_status'] != '주문') continue;

$data = serialize($od);

$sql = " insert {$g5['g5_shop_order_delete_table']} set de_key = '$od_id', de_data = '".addslashes($data)."', mb_id = 'admin', de_ip = '{$_SERVER['REMOTE_ADDR']}', de_datetime = '".G5_TIME_YMDHIS."' ";
sql_query($sql, true);

// cart 테이블의 상품 상태를 삭제로 변경
$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '삭제' where od_id = '$od_id' and ct_status = '주문' ";
sql_query($sql);

$sql = " delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
sql_query($sql);
exit;
?>