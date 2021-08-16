<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");



if ($w == 'u')
    check_demo();

//auth_check($auth[$sub_menu], 'w');

//check_admin_token();

$mb_id = trim($_POST['mb_id']);

// 휴대폰번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);
/*
if($mb_hp) {
    $result = exist_mb_hp($mb_hp, $mb_id);
    if ($result)
        alert($result);
}
*/
// 인증정보처리
if($_POST['mb_certify_case'] && $_POST['mb_certify']) {
    $mb_certify = $_POST['mb_certify_case'];
    $mb_adult = $_POST['mb_adult'];
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3);

if($mb_where =="메디포털"){
	$g5['member_table'] = "portal.g5_member";
}else{
	$g5['member_table'] = "shop.g5_member";
}
$mb = get_member($mb_id);

if($_POST['mb_level'] == $mb['mb_level']){
	$sql_common = "  mb_name = '{$_POST['mb_name']}',
					 mb_nick = '{$_POST['mb_nick']}',
					 mb_email = '{$_POST['mb_email']}',
					 mb_homepage = '{$_POST['mb_homepage']}',
					 mb_tel = '{$_POST['mb_tel']}',
					 mb_hp = '{$mb_hp}',
					 mb_certify = '{$mb_certify}',
					 mb_adult = '{$mb_adult}',
					 mb_zip1 = '$mb_zip1',
					 mb_zip2 = '$mb_zip2',
					 mb_addr1 = '{$_POST['mb_addr1']}',
					 mb_addr2 = '{$_POST['mb_addr2']}',
					 mb_addr3 = '{$_POST['mb_addr3']}',
					 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
					 mb_signature = '{$_POST['mb_signature']}',
					 mb_leave_date = '{$_POST['mb_leave_date']}',
					 mb_intercept_date='{$_POST['mb_intercept_date']}',
					 mb_memo = '{$_POST['mb_memo']}',
					 mb_mailling = '{$_POST['mb_mailling']}',
					 mb_sms = '{$_POST['mb_sms']}',
					 mb_open = '{$_POST['mb_open']}',
					 mb_profile = '{$_POST['mb_profile']}',
					 mb_level = '{$_POST['mb_level']}',
					 mb_1 = '{$_POST['mb_1']}',
					 mb_2 = '{$_POST['mb_2']}',
					 mb_3 = '{$_POST['mb_3']}',
					 mb_4 = '{$_POST['mb_4']}',
					 mb_5 = '{$_POST['mb_5']}',
					 mb_6 = '{$_POST['mb_6']}',
					 mb_7 = '{$_POST['mb_7']}',
					 mb_8 = '{$_POST['mb_8']}',
					 mb_9 = '{$_POST['mb_9']}',
					 mb_10 = '{$_POST['mb_10']}',
					 mb_11 = '{$_POST['mb_11']}',
					 mb_12 = '{$_POST['mb_12']}',
					 mb_13 = '{$_POST['mb_13']}',
					 mb_14 = '{$_POST['mb_14']}',
					 mb_15 = '{$_POST['mb_15']}',
					 mb_16 = '{$_POST['mb_16']}',
					 mb_17 = '{$_POST['mb_17']}',
					 mb_18 = '{$_POST['mb_18']}',
					 mb_19 = '{$_POST['mb_19']}',
					 mb_20 = '{$_POST['mb_20']}',
					 mb_21 = '{$_POST['mb_21']}',
					 mb_22 = '{$_POST['mb_22']}',
					 mb_23 = '{$_POST['mb_23']}',
					 mb_24 = '{$_POST['mb_24']}',
					 mb_25 = '{$_POST['mb_25']}',
					 mb_26 = '{$_POST['mb_26']}',
					 mb_27 = '{$_POST['mb_27']}',
					 mb_28 = '{$_POST['mb_28']}',
					 mb_29 = '{$_POST['mb_29']}',
					 mb_shop = '{$_POST['mb_shop']}',
					 mb_where = '{$_POST['mb_where']}',
					 mb_v = '{$_POST['mb_v']}'
					 ";
}else {
	$sql_common = "  mb_name = '{$_POST['mb_name']}',
					 mb_nick = '{$_POST['mb_nick']}',
					 mb_email = '{$_POST['mb_email']}',
					 mb_homepage = '{$_POST['mb_homepage']}',
					 mb_tel = '{$_POST['mb_tel']}',
					 mb_hp = '{$mb_hp}',
					 mb_certify = '{$mb_certify}',
					 mb_adult = '{$mb_adult}',
					 mb_zip1 = '$mb_zip1',
					 mb_zip2 = '$mb_zip2',
					 mb_addr1 = '{$_POST['mb_addr1']}',
					 mb_addr2 = '{$_POST['mb_addr2']}',
					 mb_addr3 = '{$_POST['mb_addr3']}',
					 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
					 mb_signature = '{$_POST['mb_signature']}',
					 mb_leave_date = '{$_POST['mb_leave_date']}',
					 mb_intercept_date='{$_POST['mb_intercept_date']}',
					 mb_memo = '{$_POST['mb_memo']}',
					 mb_mailling = '{$_POST['mb_mailling']}',
					 mb_sms = '{$_POST['mb_sms']}',
					 mb_open = '{$_POST['mb_open']}',
					 mb_profile = '{$_POST['mb_profile']}',
					 mb_level = '{$_POST['mb_level']}',
					 mb_1 = '{$_POST['mb_1']}',
					 mb_2 = '{$_POST['mb_2']}',
					 mb_3 = '{$_POST['mb_3']}',
					 mb_4 = '{$_POST['mb_4']}',
					 mb_5 = '{$_POST['mb_5']}',
					 mb_6 = '{$_POST['mb_6']}',
					 mb_7 = '{$_POST['mb_7']}',
					 mb_8 = '{$_POST['mb_8']}',
					 mb_9 = '{$_POST['mb_9']}',
					 mb_10 = '{$_POST['mb_10']}',
					 mb_11 = '{$_POST['mb_11']}',
					 mb_12 = '{$_POST['mb_12']}',
					 mb_13 = '{$_POST['mb_13']}',
					 mb_14 = '{$_POST['mb_14']}',
					 mb_15 = '{$_POST['mb_15']}',
					 mb_16 = '{$_POST['mb_16']}',
					 mb_17 = '{$_POST['mb_17']}',
					 mb_18 = '{$_POST['mb_18']}',
					 mb_19 = '{$_POST['mb_19']}',
					 mb_20 = '{$_POST['mb_20']}',
					 mb_21 = '{$_POST['mb_21']}',
					 mb_22 = '{$_POST['mb_22']}',
					 mb_23 = '{$_POST['mb_23']}',
					 mb_24 = '{$_POST['mb_24']}',
					 mb_25 = '{$_POST['mb_25']}',
					 mb_26 = '{$_POST['mb_26']}',
					 mb_27 = '{$_POST['mb_27']}',
					 mb_28 = '{$_POST['mb_28']}',
					 mb_29 = '{$_POST['mb_29']}',
					 mb_shop = '{$_POST['mb_shop']}',
					 level_datetime = now() ,
					 mb_where = '{$_POST['mb_where']}',
					 mb_v = '{$_POST['mb_v']}'
					 ";
}

if ($w == '')
{
    $mb = get_member($mb_id);
    if ($mb['mb_id'])
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : '.$mb['mb_id'].'\\n이름 : '.$mb['mb_name'].'\\n닉네임 : '.$mb['mb_nick'].'\\n메일 : '.$mb['mb_email']);
	
	

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);
	

	if($_POST['mb_v'] == "4"){
		$cnts = sql_fetch("select count(*) as cnt from portal.g5_member where mb_id = '$mb_id' ");
		if($cnts["cnt"] > 0){
			alert('키메디에 존재하는 공급사아이디입니다.\\nＩＤ : '.$mb['mb_id'].'\\n이름 : '.$mb['mb_name'].'\\n닉네임 : '.$mb['mb_nick'].'\\n메일 : '.$mb['mb_email']);
		}else{
			 sql_query(" insert into portal.g5_member set mb_id = '{$mb_id}', mb_password = '".get_encrypt_string($mb_password)."', mb_datetime = '".G5_TIME_YMDHIS."', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '".G5_TIME_YMDHIS."', {$sql_common} ");
		}
	}

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '".get_encrypt_string($mb_password)."', mb_datetime = '".G5_TIME_YMDHIS."', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '".G5_TIME_YMDHIS."', {$sql_common} ");
	
	
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    if ($_POST['mb_id'] == $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level'])
        alert($mb['mb_id'].' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');
	/*
    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);
	*/

    $mb_dir = substr($mb_id,0,2);

    // 회원 아이콘 삭제
    if ($del_mb_icon) {
        unlink(G5_DATA_PATH . '/member/' . $mb_dir . '/' . $mb_id);

    }


    // 아이콘 업로드
    if (is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
        if (!preg_match("/(\.gif|jpg|png|bmp)$/i", $_FILES['mb_icon']['name'])) {
            alert($_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match("/(\.gif|jpg|png|bmp)$/i", $_FILES['mb_icon']['name'])) {
            @mkdir(G5_DATA_PATH.'/member/'.$mb_dir, G5_DIR_PERMISSION);
            @chmod(G5_DATA_PATH.'/member/'.$mb_dir, G5_DIR_PERMISSION);

            $dest_path = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_id;

            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

			/*
            if (file_exists($dest_path)) {
                $size = getimagesize($dest_path);
                // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    @unlink($dest_path);
                }
            }
			*/
        }
    }

    if ($mb_password)
        $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' ";
    else
        $sql_password = "";

    if ($passive_certify)
        $sql_certify = " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
    else
        $sql_certify = "";

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);

	if($_POST['mb_leave_date'] != ""){
		$sso = sql_fetch("select * from sso2.user_sso where wr_key = 'SHOP' and in_id = '{$mb_id}' limit 1 ");
		$out_id = sql_fetch("select count(*) as cnt from portal.g5_member where mb_id = '{$sso[out_id]}' and mb_1 = '{$_POST[mb_1]}' ");
		if($out_id[cnt] > 0){
			sql_query("update portal.g5_member set mb_agree_ck = '' where mb_id = '{$sso[out_id]}' and mb_1 = '{$_POST[mb_1]}' ");
		}
	}
}
else
{
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

if ($w == ''){
	alert('등록되었습니다.','./'.$return_url.'?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id.'&mb_where='.$mb_where, false);
}else if ($w == 'u'){
	alert('수정되었습니다.','./'.$return_url.'?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id.'&mb_where='.$mb_where, false);
}

?>