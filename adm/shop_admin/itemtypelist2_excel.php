<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_ITEMTYPE_SHOP.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 
 

$where = " where ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sca != "") {
    $sql_search .= " $where (ca_id like '$sca%' or ca_id2 like '$sca%' or ca_id3 like '$sca%') ";
	$sql_search .= " and it_8 = '' ";
}else{
	$sql_search .= " where it_8 = '' ";
}


if ($sfl == "")  $sfl = "it_name";

if (!$sst)  {
    $sst  = "it_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";

$sql_common = "  from {$g5['g5_shop_item_table']} ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select it_id,
                 it_name,
                 it_type11,
                 it_type22,
                 it_type33,
                 it_type44,
                 it_type55
          $sql_common
          $sql_order ";
$result = sql_query($sql);
?>


<table width="100%" border="1">
	<tr>
		<th>No</th>
		<th>마스터코드</th>
		<th>상품명</th>
		<th>히트상품</th>
		<th>추천상품</th>
		<th>신규상품</th>
		<th>인기상품</th>
		<th>할인상품</th> 
	</tr>
	<?  
	for ($i=0; $row=sql_fetch_array($result); $i++) {
	?>
	<tr>
		<td><?=$i+1?></td>
		<td><?php echo $row['it_id']; ?></td>
		<td><?php echo cut_str(stripslashes($row['it_name']), 60, "&#133"); ?></td>
		<td><?php echo ($row['it_type11'] ? '히트상품' : ''); ?></td>
		<td><?php echo ($row['it_type22'] ? '추천상품' : ''); ?></td>
		<td><?php echo ($row['it_type33'] ? '신규상품' : ''); ?></td>
		<td><?php echo ($row['it_type44'] ? '인기상품' : ''); ?></td>
		<td><?php echo ($row['it_type55'] ? '할인상품' : ''); ?></td>
	</td>
<? } ?>
</table> 