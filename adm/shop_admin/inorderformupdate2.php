<?php
include_once('./_common.php');
 
$res = sql_fetch("select * from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' ");

$pod_id = $od_id;

$data = unserialize(base64_decode($res['dt_data']));

$com_od_id_tmp = array_unique($data[com_od_id]);

$mb_id			  = $res[mb_id];
$od_email         = get_email_address($data[od_email]);
$od_name          = clean_xss_tags($data[od_name]);
$od_tel           = clean_xss_tags($data[od_tel]);
$od_hp            = clean_xss_tags($data[od_hp]);
$od_zip           = preg_replace('/[^0-9]/', '', $data[od_zip]);
$od_zip1          = substr($data[od_zip], 0, 3);
$od_zip2          = substr($data[od_zip], 3);
$od_addr1         = clean_xss_tags($data[od_addr1]);
$od_addr2         = clean_xss_tags($data[od_addr2]);
$od_addr3         = clean_xss_tags($data[od_addr3]);
$od_addr_jibeon   = preg_match("/^(N|R)$/", $data[od_addr_jibeon]) ? $data[od_addr_jibeon] : '';
$od_b_name        = clean_xss_tags($data[od_b_name]);
$od_b_tel         = clean_xss_tags($data[od_b_tel]);
$od_b_hp          = clean_xss_tags($data[od_b_hp]);
$od_b_addr1       = clean_xss_tags($data[od_b_addr1]);
$od_b_addr2       = clean_xss_tags($data[od_b_addr2]);
$od_b_addr3       = clean_xss_tags($data[od_b_addr3]);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $data[od_b_addr_jibeon]) ? $data[od_b_addr_jibeon] : '';
$od_memo          = clean_xss_tags($data[od_memo]);
$od_deposit_name  = clean_xss_tags($data[od_deposit_name]);
$od_tax_flag      = $default['de_tax_flag_use'];
$od_settle_case	  = $data[od_settle_case];
$od_time		  = $res[dt_time];

if($od_settle_case == "신용카드"){
	$od_status = "입금";
	$od_receipt_time  = $res[dt_time];
}else{
	$od_status = "주문";
}

foreach($com_od_id_tmp  as $key => $value){
    $com_od_id[] = $value;
}
  

for($ii = 0 ; $ii < count($com_od_id);$ii++){
	$sql_i = "select count(*) as cnt , od_id , com_od_id , com_id , sum(ct_price * ct_qty) as ct_price from {$g5['g5_shop_cart_table']} where com_od_id = '$com_od_id[$ii]' and ct_status = '쇼핑' and ct_select = '1' group by com_od_id";
	$res_i = sql_query($sql_i);
	while($row_i = sql_fetch_array($res_i)){
		
		
		$od_id = $row_i[com_od_id];
		
		$od_misu = $row_i[ct_price];
		
		$od_send_cost = get_sendcost($row_i[com_od_id]);
		

		// 장바구니 상태변경
		// 신용카드로 주문하면서 신용카드 포인트 사용하지 않는다면 포인트 부여하지 않음
		$cart_status = $od_status;
		$sql_card_point = "";
		if ($od_receipt_price > 0 && !$default['de_card_point']) {
			$sql_card_point = " , ct_point = '0' ";
		}
		$sql_c = "update {$g5['g5_shop_cart_table']}
				   set od_id = '$row_i[com_od_id]',
					   ct_status = '$cart_status'
					   $sql_card_point
				 where com_od_id = '$row_i[com_od_id]'
				   and ct_select = '1' ";
		sql_query($sql_c);
		
		if($od_settle_case == "가상계좌" || $od_settle_case == "신용카드"){
			$od_receipt_price = $row_i[ct_price] + $od_send_cost;
			$od_misu = 0;
		}
		

$sql = " insert {$g5['g5_shop_order_table']}
            set od_id             = '$od_id',
                mb_id             = '$mb_id',
				pod_id			  = '$pod_id',
				com_id			  = '$row_i[com_id]',
                od_pwd            = '$od_pwd',
                od_name           = '$od_name',
                od_email          = '$od_email',
                od_tel            = '$od_tel',
                od_hp             = '$od_hp',
                od_zip1           = '$od_zip1',
                od_zip2           = '$od_zip2',
                od_addr1          = '$od_addr1',
                od_addr2          = '$od_addr2',
                od_addr3          = '$od_addr3',
                od_addr_jibeon    = '$od_addr_jibeon',
                od_b_name         = '$od_b_name',
                od_b_tel          = '$od_b_tel',
                od_b_hp           = '$od_b_hp',
                od_b_zip1         = '$od_b_zip1',
                od_b_zip2         = '$od_b_zip2',
                od_b_addr1        = '$od_b_addr1',
                od_b_addr2        = '$od_b_addr2',
                od_b_addr3        = '$od_b_addr3',
                od_b_addr_jibeon  = '$od_b_addr_jibeon',
                od_deposit_name   = '$od_deposit_name',
                od_memo           = '$od_memo',
                od_cart_count     = '$row_i[cnt]',
                od_cart_price     = '$row_i[ct_price]',
                od_cart_coupon    = '$tot_it_cp_price',
                od_send_cost      = '$od_send_cost',
                od_send_coupon    = '$tot_sc_cp_price',
                od_send_cost2     = '$od_send_cost2',
                od_coupon         = '$tot_od_cp_price',
                od_receipt_price  = '$od_receipt_price',
                od_receipt_point  = '$od_receipt_point',
                od_bank_account   = '$od_bank_account',
                od_receipt_time   = '$od_receipt_time',
                od_misu           = '$od_misu',
                od_pg             = '$od_pg',
                od_tno            = '$od_tno',
                od_app_no         = '$od_app_no',
                od_escrow         = '$od_escrow',
                od_tax_flag       = '$od_tax_flag',
                od_tax_mny        = '$od_tax_mny',
                od_vat_mny        = '$od_vat_mny',
                od_free_mny       = '$od_free_mny',
                od_status         = '$od_status',
                od_shop_memo      = '',
                od_hope_date      = '$od_hope_date',
                od_time           = '$od_time',
                od_ip             = '$data[REMOTE_ADDR]',
                od_settle_case    = '$od_settle_case',
                od_test           = '{$default['de_card_test']}'
                ";
	$result = sql_query($sql, false);

	 

	}

	

} 

$sql = " delete from {$g5['g5_shop_order_data_table']} where od_id = '$pod_id' ";
sql_query($sql);

echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">'.PHP_EOL;
echo '<script>'.PHP_EOL;
echo 'if(confirm("주문내역 페이지로 이동하시겠습니까?"))'.PHP_EOL;
echo 'document.location.href = "./orderlist.php";'.PHP_EOL;
echo 'else'.PHP_EOL;
echo 'document.location.href = "./inorderlist.php?'.str_replace('&amp;', '&', $qstr).'";'.PHP_EOL;
echo '</script>'.PHP_EOL;

?>