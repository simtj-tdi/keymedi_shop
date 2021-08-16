<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$mb_1 = trim($_POST['reg_mb_1']);

set_session('ss_check_mb_1', '');

if ($msg = exist_mb_1($mb_1))     die($msg);


set_session('ss_check_mb_1', $mb_1);
?>