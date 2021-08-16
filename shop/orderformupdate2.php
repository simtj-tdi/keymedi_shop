<?
include_once('./_common.php');


$od_id = $_POST[oid];
$pod_id = $od_id;

$res = sql_fetch("select * from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' ");
$data = unserialize(base64_decode($res['dt_data']));

$com_od_id_tmp = array_unique($data[com_od_id]);
$it_id_tmp = array_unique($data[it_id]);

$mb_id      = $res[mb_id];
$od_email   = get_email_address($data[od_email]);
$od_name    = clean_xss_tags($data[od_name]);
$od_tel     = clean_xss_tags($data[od_tel]);
$od_hp      = clean_xss_tags($data[od_hp]);
$od_zip     = preg_replace('/[^0-9]/', '', $data[od_zip]);
$od_zip1    = substr($data[od_zip], 0, 3);
$od_zip2    = substr($data[od_zip], 3);
$od_b_zip1  = substr($data[od_b_zip], 0, 3);
$od_b_zip2  = substr($data[od_b_zip], 3);
$od_addr1   = clean_xss_tags($data[od_addr1]);
$od_addr2   = clean_xss_tags($data[od_addr2]);
$od_addr3   = clean_xss_tags($data[od_addr3]);
$od_addr_jibeon = preg_match("/^(N|R)$/", $data[od_addr_jibeon]) ? $data[od_addr_jibeon] : '';
$od_b_name  = clean_xss_tags($data[od_b_name]);
$od_b_tel   = clean_xss_tags($data[od_b_tel]);
$od_b_hp    = clean_xss_tags($data[od_b_hp]);
$od_b_addr1 = clean_xss_tags($data[od_b_addr1]);
$od_b_addr2 = clean_xss_tags($data[od_b_addr2]);
$od_b_addr3 = clean_xss_tags($data[od_b_addr3]);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $data[od_b_addr_jibeon]) ? $data[od_b_addr_jibeon] : '';
$od_memo    = clean_xss_tags($data[od_memo]);
$od_deposit_name = clean_xss_tags($data[od_deposit_name]);
$od_tax_flag= $default['de_tax_flag_use'];
$od_settle_case = $data[od_settle_case];
$od_time    = $res[dt_time];
$f_cp_id    = clean_xss_tags($data[f_cp_id]);
$f_cp_price = clean_xss_tags($data[f_cp_price]);
$f_point    = clean_xss_tags($data[f_point]);
$bankname   = $BANK_CODE[$resultMap['VACT_BankCode']];
$account    = $resultMap['VACT_Num'] . ' ' . $resultMap['VACT_Name'];
$depositor  = $resultMap['VACT_InputName'];
$od_tno     = $tid;
$od_pg      = "inicis";
$od_app_no  = $resultMap['applNum'];

$od_bank_account = $bankname . ' ' . $account;
$od_deposit_name = $depositor;


//if($od_settle_case == "신용카드"){
$od_status = "입금";
$od_receipt_time = $res[dt_time];
//	$od_app_no     = $resultMap['applNum'];
//}else{
//	$od_status = "주문";
//	$od_app_no    = $resultMap['VACT_Num'];
//}

foreach ($com_od_id_tmp as $key => $value) {
    $com_od_id[] = $value;
}

$it_id_arr = "";
foreach ($it_id_tmp as $key => $value) {
    if ($it_id_arr == "") {
        $it_id_arr .= $value;
    } else {
        $it_id_arr .= "," . $value;
    }
}
// 쿠폰사용내역기록
if ($is_member) {
    $it_cp_cnt = count($data['cp_id']);
    for ($i = 0; $i < $it_cp_cnt; $i++) {
        $cid = $data['cp_id'][$i];
        $cp_it_id = $data['it_id'][$i];
        $cp_prc = (int)$data['cp_price'][$i];
        $od_id = $data['com_od_id'][$i];

        if (trim($cid)) {
            $sql = " insert into {$g5['g5_shop_coupon_log_table']}
					set cp_id       = '$cid',
					mb_id       = '$mb_id',
					od_id       = '$od_id',
					cp_price    = '$cp_prc',
					cl_datetime = '" . G5_TIME_YMDHIS . "' ";
            sql_query($sql);


            // 쿠폰사용금액 cart에 기록
            //$cp_prc = (int)$data['cp_price'][$i];
            $sql = " update {$g5['g5_shop_cart_table']}
			        set cp_price = '$cp_prc'
					where com_od_id = '$od_id'
					and it_id = '$cp_it_id'
					order by ct_id asc
					limit 1 ";
            sql_query($sql);


        }
    }
}


for ($ii = 0; $ii < count($com_od_id); $ii++) {
    $sql_i = "select count(*) as cnt , od_id , com_od_id , com_id , sum(ct_price * ct_qty) as ct_price from {$g5['g5_shop_cart_table']} where com_od_id = '$com_od_id[$ii]' and ct_status = '쇼핑' and it_id in (" . $it_id_arr . ") group by com_od_id";
    $res_i = sql_query($sql_i);
    while ($row_i = sql_fetch_array($res_i)) {

        $od_id      = $row_i[com_od_id];
        $od_misu    = $row_i[ct_price];
        $od_send_cost = get_sendcost($row_i[com_od_id]);

        //제고 감소
        $sqla = "select it_id , ct_qty from {$g5['g5_shop_cart_table']} where com_od_id = '$row_i[com_od_id]' and it_id in (" . $it_id_arr . ") ";
        $resa = sql_query($sqla);
        while ($rowa = sql_fetch_array($resa)) {
            $sqlaa = " update {$g5['g5_shop_item_table']}
						set it_stock_qty = it_stock_qty - '{$rowa['ct_qty']}'
						where it_id = '{$rowa['it_id']}' ";
            sql_query($sqlaa);
        }


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
				and it_id in (" . $it_id_arr . ") ";
        sql_query($sql_c);

        if ($od_settle_case == "가상계좌" || $od_settle_case == "신용카드") {
            $od_receipt_price = $row_i[ct_price] + $od_send_cost;
            $od_misu = 0;
        }
        $tot_od_cp_price = 0;
        // 주문쿠폰사용내역기록
        if (trim($f_cp_id[$ii])) {

            $tot_od_cp_price = $f_cp_price[$ii];

            $sql = " insert into {$g5['g5_shop_coupon_log_table']}
					set cp_id       = '{$f_cp_id[$ii]}',
					mb_id       = '$mb_id',
					od_id       = '$od_id',
					cp_price    = '$tot_od_cp_price',
					cl_datetime = '" . G5_TIME_YMDHIS . "' ";
            sql_query($sql);
        }
        $tmp_point = $f_point[$ii];

        insert_point($mb_id, $tmp_point * -1, "상품구매", "@shop", $mb_id, microtime());

        $sql = " insert {$g5['g5_shop_order_table']}
				set od_id         = '$od_id',
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
				od_receipt_point  = '$f_point[$ii]',
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

?>
    <script>
        alert("구매완료되었습니다.");
        document.location.href = "/shop/mypage.php";
    </script>
<?
exit;
?>