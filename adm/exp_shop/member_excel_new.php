<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_MEMBER.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

//shop_juny
if(!$site_code){
	$site_fix = "shop_skin";
}else{
	$site_fix = $site_code;
}

$sql_w = sql_fetch("select code_name from site_code where code = '$site_fix' ");

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";
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

 $sql_search .= " and ( mb_level = '4' or  mb_level = '6'  or  mb_level = '5')";

if($sdate){
	 $sql_search .= " and left(mb_datetime,10) >= '{$sdate}' ";	
}
if($edate){
	 $sql_search .= " and left(mb_datetime,10) <= '{$edate}' ";	
}
 
	 $sql_search .= " and  mb_where = '{$sql_w[code_name]}' ";
 


if($mb_level){
	 $sql_search .= " and mb_level = '{$mb_level}' ";
}
if($mb_9){
	 $sql_search .= " and mb_9 = '{$mb_9}' ";
}
if($mb_21){
	 $sql_search .= " and mb_21 = '{$mb_21}' ";
}
$sql_search .= " and mb_shop = '2' "; 

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

// 의사회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_level >= '4' and mb_level <= '6' {$sql_order} ";
$row = sql_fetch($sql);
$doc_count = $row['cnt'];

// 대기회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_level = '1' {$sql_order} ";
$row = sql_fetch($sql);
$stay_count = $row['cnt'];

// 관리회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_level = '10' {$sql_order} ";
$row = sql_fetch($sql);
$adm_count = $row['cnt'];

// 지인회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_22 = '지인추천' {$sql_order} ";
$row = sql_fetch($sql);
$jiin_count = $row['cnt'];

// 인터넷 검색회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_22 = '인터넷 검색' {$sql_order} ";
$row = sql_fetch($sql);
$inter_count = $row['cnt'];

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
<!-- 
<table width="100%" border="1">
	<tr>
		<th>총회원</th>
		<th>의사회원</th>
		<th>준회원</th>
		<th>관리자</th>
		<th>지인추천</th>
		<th>인터넷 검색</th>
	</tr>
	<tr>
		<td><?=$total_count?></td>
		<td><?=$doc_count?></td>
		<td><?=$stay_count?></td>
		<td><?=$adm_count?></td>
		<td><?=$jiin_count?></td>
		<td><?=$inter_count?></td>
	</tr>
</table> -->


<table width="100%" border="1">
	<tr>
		<th>No</th>
		<th>아이디</th>
		<th>회원구분</th>
		<th>이름</th>
		<th>닉네임</th>
		<th>생년월일</th>
		<th>휴대폰</th>
		<th>전화번호</th>
		<th>성별</th>
		<th>이메일</th>
		<th>면허번호</th>
		<th>사업자등록번호</th>
		<th>인증파일</th>
		<th>학회</th>
		<th>근무처</th>
		<th>주소</th>
		<th>지역</th>
		<th>근무형태</th>
		<th>대표진료과</th>
		<th>가입경로</th>
		<th>메디몰이용</th>
		<th>상태/권한</th>
		<th>포인트</th>
		<th>sms수신	</th>
		<th>메일수신</th>
		<th>최종접속</th>
		<th>가입일</th>
		<th>등급수정일</th>
		<th>폐업일</th>	
		<th>탈퇴일</th>	
		<th>보유포인트</th>	
	</tr>




<?  for ($i=0; $row=sql_fetch_array($result); $i++) { ?>
	<tr>
		<td><?=$i+1?></td>
		<td><?=$row[mb_id]?></td>
		<td><?=($row[mb_where]=="메디포털")?"키메디":$row[mb_where] ?></td>
		<td><?=$row[mb_name]?></td>
		<td><?=$row[mb_nick]?></td>
		<td><?=$row[mb_birth]?></td>
		<td><?=$row[mb_hp]?></td>
		<td><?=($row[mb_8])?"tel :".$row[mb_8]:""?></td>
		<td><?=$row[mb_sex]?></td>
		<td><?=$row[mb_email]?></td>
		<td><?=$row[mb_1]?></td>
		<td><?=$row[mb_15]?></td>
		<td>
		<?
			$mb_dir = substr($row['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$row['mb_id'];
            if (file_exists($icon_file)) { echo "O";	}else { echo "X"; }
		?>
		</td>
		<td><?=$row[mb_21]?></td>
		<td><?=$row[mb_11]?></td>
		<td>[<?=$row[mb_5]?>] <?=$row[mb_6]?> <?=$row[mb_7]?></td>
		<td><?=cut_str($row[mb_addr1],2,"")?></td>
		<td><?=$row[mb_4]?></td>
		<td><?=$row[mb_9]?></td>
		<td><?=$row[mb_22]?> <?=$row[mb_23]?></td>
		<td><?=($row[mb_shop]=="2")?"예":"아니오"?></td>
		<td><?=$row[mb_level]?></td>
		<td><?=number_format($row[mb_point])?></td>
		<td><?=($row[mb_sms]=="1")?"예":"아니오"?></td>
		<td><?=($row[mb_mailling]=="1")?"예":"아니오"?></td>
		<td><?=$row[mb_today_login]?></td>
		<td><?=$row[mb_datetime]?></td>
		<td><?=$row[level_datetime]?></td>
		<td><?=$row[mb_intercept_date]?></td>	
		<td><?=$row[mb_leave_date]?></td>	
		<td><?=get_point_sum($row[mb_id])?></td>	
	</tr>

<? } ?>

</body>
</html>