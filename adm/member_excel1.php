<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_MEMBER.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

if(!$mb_where) $mb_where = "산부인과 협동조합";

if($mb_where =="메디포털"){
	$sql_common = " from portal.g5_member ";
}else{
	$sql_common = " from {$g5['member_table']} ";
}

$sql_search = " where (mb_shop = '2' and mb_level >= '4') and ( mb_where = '산부인과 협동조합' or mb_where = '메디포털' ) ";
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

if($mb_where){
	 $sql_search .= " and mb_where = '{$mb_where}' ";
}
if($mb_level){
	 $sql_search .= " and mb_level = '{$mb_level}' ";
}
if($mb_9){
	 $sql_search .= " and mb_9 = '{$mb_9}' ";
}
if($mb_21){
	 $sql_search .= " and mb_21 = '{$mb_21}' ";
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
		<th colspan="5">기본정보</th>
		<th colspan="15">필수자격정보</th>
		<th colspan="2">배당금지급</th>
		<th colspan="4">선택정보입력</th>
        <th rowspan="2">기존약관 및 개인정보동의</th>
        <th rowspan="2">개정약관 및 개인정보동의</th>
	</tr>

	<tr>
		<th>아이디</th>
		<th>이름</th>
		<th>생년월일</th>
		<th>E-mail</th>
		<th>휴대폰번호</th>
		
		<th>소속 의료기관명</th>
		<th>요양기관번호</th>
		<th>의사면허번호</th>
		<th>전문의면허번호</th>
		<th>사업자등록번호</th>
		<th>세금계산서Email</th>
		<th>근무형태</th>
		<th>근무처</th>
		<th>병의원 전화번호</th>
		<th>FAX</th>
		<th>업태</th>
		<th>전문진료분야</th>
		<th>출신대학</th>
		<th>졸업년도</th>
		<th>기초의학</th>

		<th>은행명</th>
		<th>계좌번호</th>

		<th>자택주소</th>
		<th>전화번호</th>
		<th>홈페이지</th>
		<th>가입일</th>
	</tr>

<?  for ($i=0; $row=sql_fetch_array($result); $i++) {
        $_agree_sql = "select * from new_agree_ck where mb_id='$row[mb_id]'";
        $_agree_result = sql_query($_agree_sql);
        $_agree_row=sql_fetch_array($_agree_result);

        if($_agree_row['old_stat']=='1')
            $_old_agree  = $_agree_row['old_datetime'];

        if($_agree_row['new_stat']=='1')
            $_new_agree  = $_agree_row['new_datetime'];
    ?>
	<tr>
		<td><?=$row[mb_id]?></td>
		<td><?=$row[mb_name]?></td>
		<td><?=$row[mb_birth]?></td>
		<td><?=$row[mb_email]?></td>
		<td><?=$row[mb_hp]?></td>
		
		<td><?=$row[mb_11]?></td>
		<td><?=$row[mb_18]?></td>
		<td><?=$row[mb_1]?></td>
		<td><?=$row[mb_12]?></td>
		<td><?=$row[mb_15]?></td>
		<td><?=$row[mb_17]?></td>
		<td><?=$row[mb_4]?></td>
		<td>[<?=$row[mb_5]?>] <?=$row[mb_6]?> <?=$row[mb_7]?></td>
		<td><?=$row[mb_8]?></td>
		<td><?=$row[mb_14]?></td>
		<td><?=$row[mb_16]?></td>
		<td><?=$row[mb_9]?></td>
		<td><?=$row[mb_2]?></td>
		<td><?=$row[mb_3]?></td>
		<td><?=$row[mb_10]?></td> 

		<td><?=$row[mb_19]?></td> 
		<td><?=$row[mb_20]?></td> 

		<td>[<?=$row[mb_zip]?>] <?=$row[mb_addr1]?> <?=$row[mb_addr2]?></td> 
		<td><?=$row[mb_tel]?></td> 
		<td><?=$row[mb_homepage]?></td> 
		<td><?=$row[mb_datetime]?></td>
        <td><?=$_old_agree?></td>
        <td><?=$_new_agree?></td>
	</tr>

<?
    $_old_agree = "";
    $_new_agree = "";
} ?>

</body>
</html>