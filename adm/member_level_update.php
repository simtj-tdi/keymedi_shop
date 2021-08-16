<?
	include_once('./_common.php');

if(count($chk) <= 0){

	alert("오류입니다.","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}
	
if($mode == "coupon"){
	for($i = 0; $i < count($chk); $i++){
		
		for($j = 0; $j < $coupon_num; $j++){
 
			$row = sql_fetch("select * from g5_coupon_list where wr_paren_id = '$coupon_id' and ( mb_id is null or mb_id = '' ) order by wr_id desc limit 1");
			

			$sql = "update g5_coupon_list set ";
			$sql .= "wr_state = '1' , ";
			$sql .= "mb_id = '{$chk[$i]}' ";
			$sql .= "where wr_id = '{$row['wr_id']}' ";
			 
			sql_query($sql);

			$c_sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
					( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime )
				VALUES
					( '$row[wr_code]', '$row[wr_subject]', '$row[wr_method]', '$row[wr_target]', '{$chk[$i]}', '$row[wr_sdate]', '$row[wr_edate]', '0', '$row[wr_price]', '1', '$row[wr_min]', '0', '".G5_TIME_YMDHIS."' ) ";

			sql_query($c_sql);
  

		}
	}
	alert("쿠폰지급완료","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}else{
	alert("오류입니다.","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}
?>