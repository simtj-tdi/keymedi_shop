<?php
include_once('./_common.php');

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_MASTER_ITEM.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

//shop_juny
if(!$site_code){
	alert("잘못된요청입니다.");
}else{
	$site_fix = $site_code;
}
?>
<table border="1">
	<tr>
		<td>마스터코드</td>
		<td>1차분류</td>
		<td>2차분류</td>
		<td>3차분류</td>
		<td>상품명</td>
		<td>기본설명</td>
		<td>제조사</td>
		<td>원산지</td>
		<td>브랜드</td>
		<td>모델</td>
		<td>보험코드</td>
		<td>표준코드</td>
		<td>효능/효과</td>
		<td>주요성분</td>
		<td>규격</td>
		<td>단위</td>
		<td>제약사코드</td>
		<td>최소금액</td>
		<td>판매가능</td> 
	</tr>
<? 
	$sql = "select * from {$site_fix}.g5_shop_item where it_8 = '' order by it_id asc"; 
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){
		$min = sql_fetch("select min(it_price) as price from {$site_fix}.{$g5['g5_shop_item_table']} where it_8 = '{$row['it_id']}' ");
?>
	<tr>
		<td><?=$row[it_id]?></td>
		<td><?=cate_name($row[ca_id],"1")?></td>
		<td><?=cate_name($row[ca_id],"2")?></td>
		<td><?=cate_name($row[ca_id],"3")?></td>
		<td><?=$row[it_name]?></td>
		<td><?=$row[it_basic]?></td>
		<td><?=$row[it_maker]?></td>
		<td><?=$row[it_origin]?></td>
		<td><?=$row[it_brand]?></td>
		<td><?=$row[it_model]?></td>
		<td><?=$row[it_1]?></td>
		<td><?=$row[it_2]?></td>
		<td><?=$row[it_3]?></td>
		<td><?=$row[it_4]?></td>
		<td><?=$row[it_5]?></td>
		<td><?=$row[it_6]?></td>
		<td><?=$row[it_9]?></td>
		<td><?=number_format($min[price]) ?></td>
		<td><?=($row[it_use]=="1")?"예":"아니오"?></td>
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
	$sql = "select ca_name from {$site_fix}.g5_shop_category where ca_id = '{$ca_id}' ";
	$res = sql_fetch($sql);
	return $res[ca_name];
}
?>
