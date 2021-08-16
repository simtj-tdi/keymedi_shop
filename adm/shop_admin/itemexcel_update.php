<?php 
include_once('./_common.php');

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = $member[mb_id]."_itemlist_".date("Y-m-d").".xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" );

$secret_key = "123456789";
$secret_iv = "#@$%^&*()_+=-";
$decrypted = Decrypt($_GET[sql], $secret_key, $secret_iv);
?>

<table border=1>

<tr>
	<td colspan="7">* 상품코드/마스터코드/상품명/제조사/규격/단위 는 수정하시면 안됩니다.</td>
 
	<td>제약사코드<br>(자체 재품코드)</td>
	<td>판매가격<br>(숫자만 입력해주세요)</td>
	<td>재고수량<br>(숫자만 입력해주세요)</td>
	<td>
		0 : 판매안함<br>
		1 : 판매함
	</td>
	<td>
		0 : 품절해제<br>
		1 : 품절
	</td>
</tr>

<tr>
	<td>상품코드</td>
	<td>마스터코드</td>
	<td>공급사</td>
	<td>상품명</td>
	<td>제조사</td>
	<td>규격</td>
	<td>단위</td>
	<td>제약사코드</td>
	<td>판매가격</td>
	<td>재고수량</td>
	<td>판매가능</td>
	<td>품절여부</td>
</tr>
<?
	if($is_admin){
		$where = " and it_8 != '' ";
	}else{
		$where = " and it_10 = '$member[mb_id]' and it_8 != '' ";
	} 

//	$sql = "select * from {$g5['g5_shop_item_table']} where 1 = 1  {$where}";
	$sql = $decrypted; 
    $res = sql_query($sql);
	while($row = sql_fetch_array($res)){
	
		$rowd = sql_fetch("select it_maker , it_5 , it_6 from {$g5['g5_shop_item_table']} where it_id = '$row[it_8]' ");
		$rmem = get_member($row[it_10]);
?>
<tr>
	<td><?=$row[it_id]?>&nbsp;</td>
	<td><?=$row[it_8]?>&nbsp;</td>
	<td><?=$rmem[mb_nick]?>&nbsp;</td>
	<td><?=$row[it_name]?>&nbsp;</td>
	<td><?=$rowd[it_maker]?>&nbsp;</td>
	<td><?=$rowd[it_5]?>&nbsp;</td>
	<td><?=$rowd[it_6]?>&nbsp;</td>
	<td><?=$row[it_9]?>&nbsp;</td>
	<td><?=$row[it_price]?>&nbsp;</td>
	<td><?=$row[it_stock_qty]?>&nbsp;</td>
	<td><?=$row[it_use]?>&nbsp;</td>
	<td><?=$row[it_soldout]?>&nbsp;</td>
</tr>
<? } ?>

</table>
