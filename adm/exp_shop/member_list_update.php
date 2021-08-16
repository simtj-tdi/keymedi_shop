<?php
$sub_menu = "600400";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}
//shop_juny
if(!$site_code){
	alert("잘못된요청입니다.");
}else{
	$site_fix = $site_code;
}
// 회원 정보를 얻는다.
function get_member2($site_fix , $mb_id, $fields='*')
{
    global $g5;

    return sql_fetch(" select $fields from {$site_fix}.g5_member where mb_id = TRIM('$mb_id') ");
}
// 회원 삭제
function member_delete2($site_fix , $mb_id)
{
    global $config;
    global $g5;

    $sql = " select mb_name, mb_nick, mb_ip, mb_recommend, mb_memo, mb_level from {$site_fix}.g5_member where mb_id= '".$mb_id."' ";
    $mb = sql_fetch($sql);

    // 이미 삭제된 회원은 제외
    if(preg_match('#^[0-9]{8}.*삭제함#', $mb['mb_memo']))
        return;

    
    // 회원자료는 정보만 없앤 후 아이디는 보관하여 다른 사람이 사용하지 못하도록 함 : 061025
    $sql = " update {$site_fix}.g5_member set mb_password = '', mb_level = 1, mb_email = '', mb_homepage = '', mb_tel = '', mb_hp = '', mb_zip1 = '', mb_zip2 = '', mb_addr1 = '', mb_addr2 = '', mb_birth = '', mb_sex = '', mb_signature = '', mb_memo = '".date('Ymd', G5_SERVER_TIME)." 삭제함\n{$mb['mb_memo']}' where mb_id = '{$mb_id}' ";
    sql_query($sql);

    // 포인트 테이블에서 삭제
    sql_query(" delete from {$site_fix}.g5_point where mb_id = '$mb_id' "); 

    // 쪽지 삭제
    sql_query(" delete from  {$site_fix}.g5_memo where me_recv_mb_id = '$mb_id' or me_send_mb_id = '$mb_id' ");

    // 스크랩 삭제
    sql_query(" delete from {$g5['scrap_table']} where mb_id = '$mb_id' "); 

    // 아이콘 삭제
    @unlink('/data/was/'.$site_fix.'/data/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif');
}

auth_check($auth[$sub_menu], 'w');

if ($_POST['act_button'] == "선택수정") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $mb = get_member2($site_fix ,$_POST['mb_id'][$k]);

        if (!$mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 회원자료가 존재하지 않습니다.\\n';
        } else if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
            $msg .= $mb['mb_id'].' : 자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.\\n';
        } else if ($member['mb_id'] == $mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 로그인 중인 관리자는 수정 할 수 없습니다.\\n';
        } else {
            if($_POST['mb_certify'][$k])
                $mb_adult = $_POST['mb_adult'][$k];
            else
                $mb_adult = 0;
			
			if($_POST['mb_level'][$k] == $mb['mb_level']){
				$sql = " update {$site_fix}.g5_member
							set mb_level = '{$_POST['mb_level'][$k]}',
								mb_intercept_date = '{$_POST['mb_intercept_date'][$k]}',
								mb_mailling = '{$_POST['mb_mailling'][$k]}',
								mb_sms = '{$_POST['mb_sms'][$k]}',
								mb_open = '{$_POST['mb_open'][$k]}',
								mb_certify = '{$_POST['mb_certify'][$k]}',
								mb_adult = '{$mb_adult}'
							where mb_id = '{$_POST['mb_id'][$k]}' ";
			}else {
				$sql = " update {$site_fix}.g5_member
							set mb_level = '{$_POST['mb_level'][$k]}',
								mb_intercept_date = '{$_POST['mb_intercept_date'][$k]}',
								mb_mailling = '{$_POST['mb_mailling'][$k]}',
								mb_sms = '{$_POST['mb_sms'][$k]}',
								mb_open = '{$_POST['mb_open'][$k]}',
								mb_certify = '{$_POST['mb_certify'][$k]}',
								level_datetime = now(),
								mb_adult = '{$mb_adult}'
							where mb_id = '{$_POST['mb_id'][$k]}' ";
			}
            sql_query($sql);
        }
    }

} else if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $mb = get_member2($site_fix ,$_POST['mb_id'][$k]);

        if (!$mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 회원자료가 존재하지 않습니다.\\n';
        } else if ($member['mb_id'] == $mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 로그인 중인 관리자는 삭제 할 수 없습니다.\\n';
        } else if (is_admin($mb['mb_id']) == 'super') {
            $msg .= $mb['mb_id'].' : 최고 관리자는 삭제할 수 없습니다.\\n';
        } else if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
            $msg .= $mb['mb_id'].' : 자신보다 권한이 높거나 같은 회원은 삭제할 수 없습니다.\\n';
        } else {
            // 회원자료 삭제
            member_delete2($site_fix , $mb['mb_id']);
        }
    }
}

if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);

goto_url('./member_list.php?'.$qstr);
?>
