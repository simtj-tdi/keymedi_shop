<?php
include_once('./_common.php');

// 로그인중인 경우 회원가입 할 수 없습니다.
if ($is_member) {
    goto_url(G5_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");

$g5['title'] = '회원가입약관';
if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head2.php');
}

$register_action_url = G5_BBS_URL.'/register_form.php';
include_once($member_skin_path.'/register.skin.php');

if(defined('G5_THEME_PATH')) {
  //  require_once(G5_THEME_PATH.'/tail2.php');
}
?>
