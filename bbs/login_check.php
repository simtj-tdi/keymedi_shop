<?php
include_once('./_common.php');

$g5['title'] = "로그인 검사";

$mb_id       = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_id || !$mb_password)
    alert('회원아이디나 비밀번호가 공백이면 안됩니다.');

if ($mb_id != $config['cf_admin'] && $mb_id!='ijump01') {
    // 관리자 회원이 아닐 경우

    $ku = new keymedi_curl();
    $ku->keymedi_login($mb_id, $mb_password);
    $access_token = $ku->get_access_token();

    if (!$access_token) {
        alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
    }

    $portal_mb = $ku->get_member_info();

    if (is_null($portal_mb)) {
        alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
    }

    $mb = get_member($portal_mb['info']['uid']);

    if (!$mb) {
        // portal 회원이 없을 경우
        $mb_sex = $portal_mb['info']['gender'] === "male" ? 'M' : 'F';
        $mb_birth = str_replace('-', '', $portal_mb['info']['birthdate']);

        if (strlen($portal_mb['info']['mobile']) == 10) {
            $mb_hp = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $portal_mb['info']['mobile']);
        } else {
            $mb_hp = preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $portal_mb['info']['mobile']);
        }

        $mb_1 = ""; // 의사면허번호
        $mb_5 = ""; // 우편번호
        $mb_6 = ""; // 주소 1
        $mb_7 = ""; // 주소 2
        $mb_8 = $mb_hp;
        $mb_9 = ""; // 대표진료과
        $mb_9_sub = ""; // 상세진료과
        $mb_11 = ""; // 근무처
        $mb_15 = ""; // 사업자등록번호

        // insert
        $sql = " insert into {$g5['member_table']}
            set mb_id = '{$portal_mb['info']['uid']}',
            mb_type = '3',
            mb_level = '6',
            mb_shop = '2',
            mb_v = '1',
            mb_where = '메디포털',
            mb_name = '{$portal_mb['info']['name']}',
            mb_nick = '{$portal_mb['info']['name']}',
            mb_email = '{$portal_mb['shop_info']['email']}',
            mb_sex = '{$mb_sex}',
            mb_birth = '{$mb_birth}',
            mb_hp = '{$mb_hp}',
            mb_1 = '',
            mb_5 = '',
            mb_6 = '',
            mb_7 = '',
            mb_8 = '',
            mb_9 = '',
            mb_9_sub = '',
            mb_11 = '',
            mb_15 = '',
            mb_datetime = now()
        ";

        sql_query($sql);

        $mb = get_member($portal_mb['uid']);
    }
// 회원이 있을 경우 업데이트


    // 회원아이디 세션 생성
    set_session('ss_mb_id', $mb['mb_id']);
    // FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
    set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

    // 회원토큰 세션 생성
    set_session('ss_mb_access_token', $access_token);

    // 포인트 체크
    if($config['cf_use_point']) {

        $user_balance = $ku->get_member_point()['user_balance'];
        if ($user_balance) {
            $sum_point =  (int)$user_balance['credit_balance'] + (int)$user_balance['point_balance'];
        } else {
            $sum_point = 0;
        }

        $sql= " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";

        sql_query($sql);

    }

    Header("Location:".G5_REACT_URL."tokenlogin?token={$access_token}");
    exit;
} else {
    // 관리자 회원 일 때만

    $mb = get_member($mb_id);

    // 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
    // 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
    // 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
    if (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password'])) {
        alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
    }

    // 회원아이디 세션 생성
    set_session('ss_mb_id', $mb['mb_id']);
    // FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
    set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

    // 포인트 체크
    if($config['cf_use_point']) {
        $sum_point = get_point_sum($mb['mb_id']);

        $sql= " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";
        sql_query($sql);
    }
}

if ($url) {
    // url 체크
    check_url_host($url);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match("/\?/", $link))
        $split= "&amp;";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    foreach($_POST as $key=>$value) {
        if ($key != 'mb_id' && $key != 'mb_password' && $key != 'x' && $key != 'y' && $key != 'url') {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else  {
    $link = G5_URL;
}

goto_url($link);
?>
