<?php
include_once('./_common.php');

$secret_key = "123456789";
$secret_iv = "#@$%^&*()_+=-";
$decrypted = Decrypt($_GET[sql], $secret_key, $secret_iv);



header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_SELLER_ITEM.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" );

?>
<table border="1">
	<tr>
		<td>업체명</td>
		<td>마스터코드</td>
        <td>셀러코드</td>
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
		<td>판매가격</td>
		<td>시중가격</td>
		<td>판매가능</td> 

		<td>포인트 유형</td> 
		<td>포인트</td> 

		<td>특가수수료</td>
		<td>수수료</td>
	</tr>
<? 
	/*$add_search = "";
	if($m_class){
		$add_search = " and it_10 = '$m_class' ";
	}else{
		if(!$is_admin){
			$add_search = " and it_10 = '$member[mb_id]' ";
		}
	}*/
	$sql = $decrypted;
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){

		$mem = get_member($row[it_10]);	
		
		$rows = sql_fetch("select * from g5_shop_item where it_id = '$row[it_8]' ");

?>
	<tr>
		<td><?=$mem[mb_nick]?>&nbsp;</td>
		<td><?=$row[it_8]?>&nbsp;</td>
        <td><?=$row[it_id]?>&nbsp;</td>
		<td><?=cate_name($rows[ca_id],"1")?></td>
		<td><?=cate_name($rows[ca_id],"2")?></td>
		<td><?=cate_name($rows[ca_id],"3")?></td>
		<td><?=$rows[it_name]?>&nbsp;</td>
		<td><?=$rows[it_basic]?>&nbsp;</td>
		<td><?=$rows[it_maker]?>&nbsp;</td>
		<td><?=$rows[it_origin]?>&nbsp;</td>
		<td><?=$rows[it_brand]?>&nbsp;</td>
		<td><?=$rows[it_model]?>&nbsp;</td>
		<td><?=$rows[it_1]?>&nbsp;</td>
		<td><?=$rows[it_2]?>&nbsp;</td>
		<td><?=$rows[it_3]?>&nbsp;</td>
		<td><?=$rows[it_4]?>&nbsp;</td>
		<td><?=$rows[it_5]?>&nbsp;</td>
		<td><?=$rows[it_6]?>&nbsp;</td>
		<td><?=$row[it_9]?>&nbsp;</td>
		<td><?=$row[it_price]?>&nbsp;</td>
		<td><?=$row[it_cust_price]?>&nbsp;</td>
		<td><?=($row[it_use]=="1")?"예":"아니오"?></td>

		<td>
			<? if($row[it_point_type]=="0") echo "설정금액"; ?>
			<? if($row[it_point_type]=="1") echo "판매가기준 설정비율"; ?>
			<? if($row[it_point_type]=="2") echo "구매가기준 설정비율"; ?>
		</td>
		<td><?=$row[it_point]?></td>
		<td><?=$row[it_s_commission]?></td>
		<td><?=$row[it_commission]?></td>
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
