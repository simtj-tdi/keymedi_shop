<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_POINT.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

$sql_common = " from {$g5['point_table']}  left join shop.g5_member on g5_point.mb_id =  g5_member.mb_id  ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " (g5_member.{$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if($sdate){
	 $sql_search .= " and left(po_datetime,10) >= '{$sdate}' ";	
}
if($edate){
	 $sql_search .= " and left(po_datetime,10) <= '{$edate}' ";	
}

if (!$sst) {
    $sst  = "po_id";
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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '포인트관리';

$colspan = 9;

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title><?=$g5['title']?></title>

</head>

<body>

<table width="100%" border="1">
	<tr>
		<th>회원아이디</th>
		<th>이름</th>
		<th>근무처</th> 
		<th>마일리지 내용</th>
		<th>결제수단</th>
		<th>마일리지 지급/사용</th>
		<th>마일리지</th>
		<th>일시</th>
		<th>만료일</th>
		<th>마일리지합</th>
	</tr>
<?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
            $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
            $row2 = sql_fetch($sql2);
        }

        $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

        $link1 = $link2 = '';
        if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
            $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
            $link2 = '</a>';
        }

        $expr = '';
        if($row['po_expired'] == 1)
            $expr = ' txt_expired';

        $bg = 'bg'.($i%2);

		$add = sql_fetch("select wr_subject , wr_1 from g5_write_0101 where wr_id = '{$row[po_rel_id]}'");
    ?>
	<tr>
		<td><?php echo $row['mb_id'] ?></td>
		<td><?php echo get_text($row['mb_name']); ?></td>
        <td><?php echo get_text($row['mb_11']); ?></td>
 
		<td><?php echo $row['po_content'] ?></td>
		<td><?
				switch($row[po_rel_table]){
					case "@card" :  $po_rel_table = "신용카드";break;
					case "@VBank" :  $po_rel_table = "가상계좌";break;
					default :  $po_rel_table = "포인트";break;
				}
				echo $po_rel_table;
		?></td>
		<td><?php echo (number_format($row['po_point']) > 0 )?"지급":"사용" ?></td>
		<td><?php echo number_format($row['po_point']) ?></td>
		<td><?php echo $row['po_datetime'] ?></td>
		<td><?php if ($row['po_expired'] == 1) { ?>
            만료<?php echo substr(str_replace('-', '', $row['po_expire_date']), 2); ?>
            <?php } else echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['po_expire_date']; ?></td>

		<td><?php echo number_format($row['po_mb_point']) ?></td>
	</tr>
	<? } ?>
</body>
</html>