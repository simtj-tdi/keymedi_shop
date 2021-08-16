<?
$sub_menu = "940600";
include_once('./_common.php');

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_COUPON_LIST.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

auth_check($auth[$sub_menu], 'r');

$sql_common = " from g5_coupon_list ";
$sql_search = " where 1 = 1  and wr_paren_id = '{$wr_id}' ";

if($ss_bt_id){ 
	$sql_search .=  " and ( ca_name = '{$ss_bt_id}' ) ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "wr_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);
?>

 <table border="1">
    <thead>
    <tr>
 
        <th scope="col">번호</th>
		<th scope="col">쿠폰명</th>
		<th scope="col">쿠폰코드</th>
		<th scope="col">쿠폰종류</th>
		<th scope="col">발급대상</th>
		<th scope="col">시작일시</th>
		<th scope="col">종료일시</th>
		<th scope="col">등록일시</th>
		<th scope="col">쿠폰금액</th>
		<th scope="col">주문최소금</th>
		<th scope="col">등록한 (아이디/이름)</th>
		<th scope="col">등록여부</th>
    </tr>
    </thead>
    <tbody>
	<?php
		 for ($i=0; $row=sql_fetch_array($result); $i++) {
			  $bg = 'bg'.($i%2);
			  $num = $total_count - (($page * $rows) - $rows ) - $i;

			  $totcnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}'");
			  $usecnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}' and wr_state = '1' ");

			  $tomem = get_member($row[mb_id]);
	?>
	<tr class="<?php echo $bg; ?>"> 
		<td class="td_mngsmall"><?=$num?></td>
		<td><?=$row[wr_subject]?></td>
		<td><?=$row[wr_code]?></td>
		<td><?=($row[wr_method]==0)?"개별상품할인":"주문금액할인"?></td>
			<td><?=$row[wr_target]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_sdate]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_edate]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_datetime]?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_price])?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_min])?></td>
		<td style="width:140px;text-align:center;">(<?=$row[mb_id]?> / <?=$tomem[mb_name]?>)</td>
		<td style="width:140px;text-align:center;"><?=($row[wr_state]=="0")?"미등록":"등록"?></td>		
	</tr>
	<?php
		}
	?>
	</tbody>
	</table>