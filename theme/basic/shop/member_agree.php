<?php
include_once('./_common.php');

if($agree != "1"){
	alert("약관에 동의해주세요." ,"/");
}
if($agree2 != "1"){
	alert("약관에 동의해주세요." ,"/");
}
if($member[mb_agree_ck]=="1"){
	alert("키메디에 가입되어있습니다. " ,"/");	
}else{
	sql_query("update {$g5['member_table']} set mb_agree_ck = '1' , mb_agree_date = now() , mb_agree_who = '본인' where mb_id = '$member[mb_id]' ");
	/*

	$sql = "insert into kakao.BM_REQUEST 
			set MSG_ID			= concat(now(),-$concat) ,
			SVC_ID				= '$SVC_ID' ,
			YELLOW_ID			= '$YELLOW_ID' ,
			SEND_MSG_KIND		= '$talk' ,
			PHONE_NO			= '$tel' ,
			TEMP_ID				= '$temid' ,
			MESSGE				= '$msg' ,
			ADVERTISEMENT_YN	= 'N' ,
			RESERVE_DT			= '$date' ,
			IMAGE_URL			= 'http://www.keymedi.com/img/main/top/top_logo.png' ,
			IMAGE_LINK			= '' ,
			SWITCH_SEND_DIV		= '' ,
			SEND_PHONE_NO		= '' ,
			RECV_PHONE_NO		= '' ,
			SMS_MESSAGE			= '' ,
			LMS_TITLE			= '' ,
			BUTTON_TYPE			= 'WL' ,
			BUTTON_ORDER		= '1' ,
			BUTTON_NAME			= '$BUTTON_NAME' ,
			BUTTON_OPT1			= 'http://www.keymedi.com' ,
			BUTTON_OPT2			= ''
	";

	sql_query($sql);

	*/
	
	//alert("가입되었습니다.","http://www.keymedi.com/bbs/board.php?bo_table=0101&type=4&mbw=1");

	if($pointzone == 0){
		alert("가입되었습니다.","http://www.keymedi.com/bbs/content.php?co_id=0205&vod_select=대한산부인과의사회");
	}else{
		alert("가입되었습니다.","http://www.keymedi.com/bbs/content.php?co_id=0300");
	}
}



?>
