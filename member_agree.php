<?php
include_once('./_common.php');

if($agree != "1"){
	alert("약관에 동의해주세요." ,"/");
}
if($agree2 != "1"){
	alert("약관에 동의해주세요." ,"/");
}


$ck = sql_fetch("select count(*) as cnt from portal.g5_member where mb_id = '$member[mb_id]' ");
$ck2 = sql_fetch("select count(*) as cnt from portal.g5_member where mb_1 = '$member[mb_1]' ");


$sso = sql_fetch("select * from sso2.user_sso where wr_key = 'KEYMEDI' and in_id = '$member[mb_id]' ");


if($sso[out_id]){
	alert("키메디에 가입되어있습니다. " ,"/");	
}else{

	if($ck[cnt] > 0 ){
		
		if($ck2[cnt] > 0){
			alert("중복된 면허번호입니다." ,"/");exit;
		}

		$tmp_id = "skin_".$member[mb_id];


		sql_query("update g5_member set mb_id = '{$tmp_id}' where mb_id = '{$member[mb_id]}' ");
		
		sql_query("insert into portal.g5_member (mb_id,mb_password,mb_name,mb_nick,mb_nick_date,mb_email,mb_homepage,mb_level,mb_sex,mb_birth,mb_tel,mb_hp,mb_certify,mb_adult,mb_dupinfo,mb_zip1,mb_zip2,mb_addr1,mb_addr2,mb_addr3,mb_addr_jibeon,mb_signature,mb_recommend,mb_point,mb_today_login,mb_login_ip,mb_datetime,mb_ip,mb_leave_date,mb_intercept_date,mb_email_certify,mb_email_certify2,mb_memo,mb_lost_certify,mb_mailling,mb_sms,mb_open,mb_open_date,mb_profile,mb_memo_call,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_11,mb_12,mb_13,mb_14,mb_15,mb_16,mb_17,mb_18,mb_19,mb_20,mb_21,mb_22,mb_23,mb_24,mb_25,mb_26,mb_27,mb_28,mb_29,mb_30,mb_shop,mb_v,mb_fseminar,mb_fseminar2,mb_fseminar3,mb_fseminar4,mb_where,mb_agree_ck,level_datetime) 
		select mb_id,mb_password,mb_name,mb_nick,mb_nick_date,mb_email,mb_homepage,mb_level,mb_sex,mb_birth,mb_tel,mb_hp,mb_certify,mb_adult,mb_dupinfo,mb_zip1,mb_zip2,mb_addr1,mb_addr2,mb_addr3,mb_addr_jibeon,mb_signature,mb_recommend,mb_point,mb_today_login,mb_login_ip,mb_datetime,mb_ip,mb_leave_date,mb_intercept_date,mb_email_certify,mb_email_certify2,mb_memo,mb_lost_certify,mb_mailling,mb_sms,mb_open,mb_open_date,mb_profile,mb_memo_call,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_11,mb_12,mb_13,mb_14,mb_15,mb_16,mb_17,mb_18,mb_19,mb_20,mb_21,mb_22,mb_23,mb_24,mb_25,mb_26,mb_27,mb_28,mb_29,mb_30,mb_shop,mb_v,mb_fseminar,mb_fseminar2,mb_fseminar3,mb_fseminar4,'산부인과 협동조합',mb_agree_ck,level_datetime from g5_member where mb_id = '$tmp_id'");

	 
		sql_query("update g5_member set mb_id = '{$member[mb_id]}' where mb_id = '{$tmp_id}' ");

		$add_sqld = "insert into sso2.user_sso set wr_key = 'KEYMEDI' , in_id = '$member[mb_id]' , out_id = '$tmp_id' ";
		sql_query($add_sqld);
		$add_sqld = "insert into sso2.user_sso set wr_key = 'SHOP' , in_id = '$tmp_id' , out_id = '$member[mb_id]' ";
		sql_query($add_sqld);

		sql_query("update portal.g5_member set mb_agree_ck = '1' , mb_agree_date = now() , mb_agree_who = '본인' where mb_id = '{$tmp_id}' ");
		
		alert("{$member[mb_name]}님 의 키메디 ID는 {$tmp_id} 입니다.","/");
 
	}else{
		
		if($ck2[cnt] > 0){
			alert("중복된 면허번호입니다." ,"/");exit;
		}

		sql_query("insert into portal.g5_member (mb_id,mb_password,mb_name,mb_nick,mb_nick_date,mb_email,mb_homepage,mb_level,mb_sex,mb_birth,mb_tel,mb_hp,mb_certify,mb_adult,mb_dupinfo,mb_zip1,mb_zip2,mb_addr1,mb_addr2,mb_addr3,mb_addr_jibeon,mb_signature,mb_recommend,mb_point,mb_today_login,mb_login_ip,mb_datetime,mb_ip,mb_leave_date,mb_intercept_date,mb_email_certify,mb_email_certify2,mb_memo,mb_lost_certify,mb_mailling,mb_sms,mb_open,mb_open_date,mb_profile,mb_memo_call,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_11,mb_12,mb_13,mb_14,mb_15,mb_16,mb_17,mb_18,mb_19,mb_20,mb_21,mb_22,mb_23,mb_24,mb_25,mb_26,mb_27,mb_28,mb_29,mb_30,mb_shop,mb_v,mb_fseminar,mb_fseminar2,mb_fseminar3,mb_fseminar4,mb_where,mb_agree_ck,level_datetime) 
		select mb_id,mb_password,mb_name,mb_nick,mb_nick_date,mb_email,mb_homepage,mb_level,mb_sex,mb_birth,mb_tel,mb_hp,mb_certify,mb_adult,mb_dupinfo,mb_zip1,mb_zip2,mb_addr1,mb_addr2,mb_addr3,mb_addr_jibeon,mb_signature,mb_recommend,mb_point,mb_today_login,mb_login_ip,mb_datetime,mb_ip,mb_leave_date,mb_intercept_date,mb_email_certify,mb_email_certify2,mb_memo,mb_lost_certify,mb_mailling,mb_sms,mb_open,mb_open_date,mb_profile,mb_memo_call,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_11,mb_12,mb_13,mb_14,mb_15,mb_16,mb_17,mb_18,mb_19,mb_20,mb_21,mb_22,mb_23,mb_24,mb_25,mb_26,mb_27,mb_28,mb_29,mb_30,mb_shop,mb_v,mb_fseminar,mb_fseminar2,mb_fseminar3,mb_fseminar4,'산부인과 협동조합',mb_agree_ck,level_datetime from g5_member where mb_id = '$member[mb_id]'");
		$add_sqld = "insert into sso2.user_sso set wr_key = 'KEYMEDI' , in_id = '$member[mb_id]' , out_id = '$member[mb_id]' ";
		sql_query($add_sqld);
		$add_sqld = "insert into sso2.user_sso set wr_key = 'SHOP_SKIN' , in_id = '$member[mb_id]' , out_id = '$member[mb_id]' ";
		sql_query($add_sqld);
		
		sql_query("update portal.g5_member set mb_agree_ck = '1' , mb_agree_date = now() , mb_agree_who = '본인' where mb_id = '$member[mb_id]' ");

		alert("가입되었습니다.","/");
	}

	

	//sql_query("update {$g5['member_table']} set mb_agree_ck = '1' where mb_id = '$member[mb_id]' ");
	
	
}



?>
