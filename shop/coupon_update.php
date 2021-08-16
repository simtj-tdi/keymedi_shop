<?
	include_once('./_common.php');
	$to_date = date("Y-m-d");
	$return_url = "/shop/coupon.php";
$ck_cnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_code = '{$wr_code}' ");

$s_cnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_code = '{$wr_code}' and wr_sdate <= '{$to_date}' ");

$e_cnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_code = '{$wr_code}' and wr_edate >= '{$to_date}' ");

$use_cnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_code = '{$wr_code}' and wr_state = '0' ");

$last_cnt = sql_fetch("select * from g5_coupon_list where wr_code = '{$wr_code}' and wr_state = '0' and wr_sdate <= '{$to_date}' and wr_edate >= '{$to_date}' ");

if($ck_cnt[cnt] <= 0 ){
	alert("쿠폰번호가 유효하지 않습니다.",$return_url);
}
if($s_cnt[cnt] <= 0 ){
	alert("쿠폰을 사용할수 있는 기간이 아닙니다.",$return_url);
}
if($e_cnt[cnt] <= 0 ){
	alert("쿠폰을 사용할수 있는 기간이 지났습니다.",$return_url);
}
if($use_cnt[cnt] <= 0 ){
	alert("사용된 쿠폰입니다.",$return_url);
}
if($last_cnt[wr_code]){

	$sql = "update g5_coupon_list set ";
	$sql .= "wr_state = '1' , ";
	$sql .= "mb_id = '$member[mb_id]' ";
	$sql .= "where wr_id = '$last_cnt[wr_id]' ";
	
	sql_query($sql);
	
	$sql_in = sql_fetch("select * from g5_coupon_list where wr_state = '1' and mb_id = '$member[mb_id]' and wr_code = '{$wr_code}' limit 1");

	
	$c_sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
					( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime )
				VALUES
					( '$sql_in[wr_code]', '$sql_in[wr_subject]', '$sql_in[wr_method]', '$sql_in[wr_target]', '$member[mb_id]', '$sql_in[wr_sdate]', '$sql_in[wr_edate]', '0', '$sql_in[wr_price]', '1', '$sql_in[wr_min]', '0', '".G5_TIME_YMDHIS."' ) ";

		sql_query($c_sql);
 

	alert("쿠폰지급완료",$return_url);

}else{
	alert("쿠폰번호가 유효하지 않습니다.",$return_url);
}
?>