<?php
include_once('./_common.php');

if (!$member['mb_id']){
	alert('회원만 접근하실 수 있습니다.',"/");
}

$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_email       = trim($_POST['mb_email']);
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_3           = isset($_POST['mb_3'])             ? trim($_POST['mb_3'])           : "";


if($mb_password != $mb_password_re){
	alert('비밀번호가 일치하지 않습니다.',"/");
}

if ($mb_password){
        $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' ";
}

 $sql = " update {$g5['member_table']}
                set mb_hp = '{$mb_hp}',
                    mb_email = '{$mb_email}',
                    mb_3 = '{$mb_3}'
                    {$sql_password}
              where mb_id = '$member[mb_id]' ";
//echo $sql;

    sql_query($sql); 

   alert('공급사정보가 수정되었습니다.',"/");
?>