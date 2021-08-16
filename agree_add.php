<?php
include_once('./_common.php');
exit;
$sql = "select * from portal.g5_member where mb_where = '산부인과 협동조합' and mb_agree_ck = '1' ";
$res = sql_query($sql);

while($row = sql_fetch_array($res)){
 

	$add = sql_query("insert into sso2.user_sso set wr_key = 'KEYMEDI' , in_id = '$row[mb_id]' , out_id ='$row[mb_id]'  ");
	$add = sql_query("insert into sso2.user_sso set wr_key = 'SHOP' , in_id = '$row[mb_id]' , out_id ='$row[mb_id]'  ");
}

?>