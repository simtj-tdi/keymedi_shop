<?php
include_once('./_common.php');

?>
<table border="1">
	<tr>
		<td>마스터코드</td>
		<td>1차분류</td>
		<td>2차분류</td>
		<td>3차분류</td>
	</tr>
<? 
	$sql = "select * from g5_shop_item where it_8 = '' order by it_id asc"; 
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){
?>
	<tr>
		<td><?=$row[it_id]?></td>
		<td><?=cate_name($row[ca_id],"1")?></td>
		<td><?=cate_name($row[ca_id],"2")?></td>
		<td><?=cate_name($row[ca_id],"3")?></td>
	</tr>
<? } ?>
</table>
<?
function cate_name($ca_id,$dep){
	if($dep == "1"){
		$ca_id = substr($ca_id,0,2);
	}else if($dep == "2"){
		$ca_id = substr($ca_id,0,4);
	}else{
		$ca_id = substr($ca_id,0,6);
	}
	$sql = "select ca_name from g5_shop_category where ca_id = '{$ca_id}' ";
	$res = sql_fetch($sql);
	return $res[ca_name];
}
?>
