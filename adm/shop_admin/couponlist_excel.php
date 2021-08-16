<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_COUPON.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

$sql_common = " from {$g5['g5_shop_coupon_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "cp_no";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$result = sql_query($sql);

?>

<table width="100%" border="1">
	<tr>
		<th>No</th>
		<th>쿠폰종류</th>
		<th>쿠폰코드</th>
		<th>쿠폰이름</th>
		<th>적용대상</th>
		<th>회원아이디</th>
		<th>사용기한</th>
		<th>사용회수</th>
		<th>발행일시</th> 
		<th>사용일시</th> 
		<th>회원 사이트</th> 
	</tr>

<?  
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		 switch($row['cp_method']) {
            case '0':
                $sql3 = " select it_name from {$g5['g5_shop_item_table']} where it_id = '{$row['cp_target']}' ";
                $row3 = sql_fetch($sql3);
                $cp_method = '개별상품할인';
                $cp_target = get_text($row3['it_name']);
                break;
            case '1':
                $sql3 = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '{$row['cp_target']}' ";
                $row3 = sql_fetch($sql3);
                $cp_method = '카테고리할인';
                $cp_target = get_text($row3['ca_name']);
                break;
            case '2':
                $cp_method = '주문금액할인';
                $cp_target = '주문금액';
                break;
            case '3':
                $cp_method = '배송비할인';
                $cp_target = '배송비';
                break;
        }

		 // 쿠폰사용회수
        $sql = " select count(*) as cnt from {$g5['g5_shop_coupon_log_table']} where cp_id = '{$row['cp_id']}' ";
        $tmp = sql_fetch($sql);
        $used_count = $tmp['cnt'];

		 // 쿠폰사용회수시간
        $sql = " select cl_datetime from {$g5['g5_shop_coupon_log_table']} where cp_id = '{$row['cp_id']}' limit 1";
        $tmp = sql_fetch($sql);
        $used_time = $tmp['cl_datetime'];

		$mem = get_member($row['mb_id']);

?>
	<tr>
		<td><?=$i+1?></td>
		<td><?php echo $cp_method; ?></td>
        <td><?php echo $row['cp_id']; ?></td>
        <td><?php echo $row['cp_subject']; ?></td>
        <td><?php echo $cp_target; ?></td>
		<td><?php echo $row['mb_id']; ?></td>
		<td><?php echo substr($row['cp_start'], 2, 8); ?> ~ <?php echo substr($row['cp_end'], 2, 8); ?></td>
		<td><?php echo number_format($used_count); ?></td>
		<td><?php echo $row['cp_datetime']; ?></td> 
		<td><?php echo $used_time; ?></td> 
		<td><?php echo $mem['mb_where']; ?></td> 
	</tr>
<? } ?>


</table> 