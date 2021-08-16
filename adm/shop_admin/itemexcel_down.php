<?php
/**
 * 	210122 - jinam23 - ca_id 분류추가.
 */
include_once('./_common.php');

$secret_key = "123456789";
$secret_iv = "#@$%^&*()_+=-";
$decrypted = Decrypt($_GET[sql], $secret_key, $secret_iv);

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_MASTER_ITEM.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

?>
<table border="1">
	<tr>
		<td>마스터코드</td>
		<td>분류코드</td>
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

		<td>포인트 유형</td> 
		<td>포인트</td> 
	</tr>
<? 
//	$sql = "select * from g5_shop_item where it_8 = '' order by it_id asc";
    $sql = $decrypted;
    $res = sql_query($sql);

    if ($img_chk == "Y") {
        while ($img_row = sql_fetch_array($res)) {
            if ( get_it_imageChk($img_row['it_id'], 50, 50) == false ) {
                $imgRow[] = $img_row;
            }
        }
    } else {
        while ($img_row = sql_fetch_array($res)) {
            $imgRow[] = $img_row;
        }
    }

	//while($row = sql_fetch_array($res)){
    foreach ( $imgRow as $key => $row ) {
		$min = sql_fetch("select min(it_price) as price from {$g5['g5_shop_item_table']} where it_8 = '{$row['it_id']}' ");
?>
	<tr>
		<td><?=$row[it_id]?></td>
		<td><?=$row[ca_id]?></td>
		<td><?=cate_name($row[ca_id],"1")?></td>
		<td><?=cate_name($row[ca_id],"2")?></td>
		<td><?=cate_name($row[ca_id],"3")?></td>
		<td><?=$row[it_name]?></td>
		<td><?=strip_tags($row[it_basic])?></td>
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

		<td>
			<? if($row[it_point_type]=="0") echo "설정금액"; ?>
			<? if($row[it_point_type]=="1") echo "판매가기준 설정비율"; ?>
			<? if($row[it_point_type]=="2") echo "구매가기준 설정비율"; ?>
		</td>
		<td><?=$row[it_point]?></td>
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
