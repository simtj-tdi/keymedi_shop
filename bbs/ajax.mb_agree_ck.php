<?php
include_once('./_common.php');

/*$agree = trim($_POST['agree']);

if ($agree == '1') {
    $data = sql_fetch("select * from agree_ck where mb_id='".$member['mb_id']."'");
    
    if ($data) {
        sql_query("update agree_ck set stat='".$agree."' where mb_id='".$member['mb_id']."'");
        sql_query("update g5_member set mb_datetime=now() where mb_id='".$member['mb_id']."'");
    }
}*/

$_old_agree = trim($_POST['old_stat']);
$_new_agree = trim($_POST['new_stat']);

if ($_old_agree) {
    sql_query("insert into new_agree_ck set mb_id='".$member['mb_id']."',old_stat='".$_old_agree."', old_datetime=now() ");
} else {
    sql_query("insert into new_agree_ck set mb_id='".$member['mb_id']."',new_stat='".$_new_agree."', new_datetime=now() ");
}
?>