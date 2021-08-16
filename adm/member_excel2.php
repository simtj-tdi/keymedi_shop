<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_MEMBER.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 


$sql_common = " from {$g5['member_table']} ";

$sql_search = " where mb_v = '4' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
if($mb_s_date){
	 $sql_search .= " and  '{$mb_s_date}' <= left(mb_datetime,10)   ";
}
if($mb_e_date){
	 $sql_search .= " and  left(mb_datetime,10) <= '{$mb_e_date}' ";
}

if($mb_l == "mb_leave_msg"){
	 $sql_search .= " and  mb_leave_msg !=''   ";
}
if($mb_l == "mb_intercept_date"){
	 $sql_search .= " and  mb_intercept_date !=''   ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리'; 

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);


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
		<th>아이디</th>
		<th>업체명</th>
		<th>이메일</th>
		<th>휴대전화</th>
		<th>전화번호</th>
		<th>팩스</th>
		<th>우편번호</th>
		<th>주소</th>
		<th>가입일</th>
		<th>탈퇴일</th>
		<th>폐업일</th>
	</tr>

<?  for ($i=0; $row=sql_fetch_array($result); $i++) { ?>
	<tr>
		<td><?=$row[mb_id]?></td>
		<td><?=$row[mb_nick]?></td>
		<td><?=$row[mb_email]?></td>
		<td><?=$row[mb_hp]?></td>
		<td><?=$row[mb_tel]?></td>
		<td><?=$row[mb_2]?></td>
		<td><?=$row[mb_zip1]?><?=$row[mb_zip2]?></td>
		<td><?=$row[mb_addr1]?> <?=$row[mb_addr2]?> <?=$row[mb_addr3]?></td>
		<td><?=$row[mb_datetime]?></td>
		<td><?=$row[mb_leave_date]?></td>
		<td><?=$row[mb_intercept_date]?></td>
	</tr>

<? } ?>

</body>
</html>