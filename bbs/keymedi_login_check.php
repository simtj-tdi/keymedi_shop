<?php
include_once('./_common.php');

// http://localhost:3002/?mode=storage_login

$mb_id       = trim($_GET['mb_id']);
$access_token = trim($_GET['access_token']);


if (!$mb_id)
    alert('회원아이디가 공백이면 안됩니다.');

$mb = get_member($mb_id);


if (!$mb['mb_id']) {
    alert('가입된 회원아이디가 아닙니다.');
}

// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['mb_id']);
// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// 회원토큰 세션 생성
set_session('ss_mb_access_token', $access_token);

// 회원 정보 업데이트
// 포인트 업데이트



Header("Location:".G5_DOMAIN);
exit;