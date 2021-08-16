<?php
include_once("./_common.php");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_bannerlist.xls";

header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=$filename" );
header( "Content-Description: PHP4 Generated Data" );

$g5['g5_shop_banner_table'] = "g5_shop_banner";

$sql_common = " from {$g5['g5_shop_banner_table']} ";

$sql_search = " where 1 = 1 ";

if($bn_area!=''){
    $sql_search .= " and bn_position = '{$bn_area}' ";
}

if($stx!=''){
    $sql_search .= " and bn_alt like '%$stx%' ";
}


$date_name = "";

if($date_kind){

    switch($date_kind){
        case "bn_time":
            $date_name .= " left(bn_time,10) ";
            break;
        case "bn_begin_time":
            $date_name .= " left(bn_begin_time,10) ";
            break;
        case "bn_end_time":
            $date_name .= " left(bn_end_time,10) ";
            break;
    }

    if($_search_start_date && $_search_end_date){
        $_search_date =  "{$date_name} BETWEEN '{$_search_start_date}' AND '{$_search_end_date}'";
    }elseif($_search_start_date && !$_search_end_date){
        $_search_date =  "{$date_name} < '{$_search_start_date}'";
    }elseif(!$_search_start_date && $_search_end_date) {
        $_search_date = "{$date_name} > '{$_search_end_date}'";
    }

    $sql_search.= " and {$_search_date} ";
}

if (!$sst) {
    $sst  = "bn_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";


$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order}";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '배너리스트';

$colspan = 15;
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
        <th style="width:50px;text-align:center;">번호</th>
        <th style="width:50px;text-align:center;">ID</th>
        <th style="text-align:center;">이미지 설명</th>
        <th style="width:150px;text-align:center;">출력 위치</th>
        <th style="width:150px;text-align:center;">등록일</th>
        <th style="width:150px;text-align:center;">시작일</th>
        <th style="width:150px;text-align:center;">종료일</th>
        <th style="width:100px;text-align:center;">출력 순서</th>
        <th style="width:100px;text-align:center;">조회</th>
        <th style="text-align:center;">링크</th>
    </tr>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
        $num = $total_count - (($page * $rows) - $rows ) - $i;

        $bn_begin_time = substr($row['bn_begin_time'], 2, 14);
        $bn_end_time   = substr($row['bn_end_time'], 2, 14);
        $bn_time   = substr($row['bn_time'], 2, 14);
        ?>
        <tr class="<?php echo $bg; ?>">
            <td style="text-align:center;"><?=$num?></td>
            <td style="text-align:center;"><?=$row[bn_id]?></td>
            <td style=""><?=$row[bn_alt]?></td>
            <td style="text-align:center;"><?=$row[bn_position]?></td>
            <td style="text-align:center;"><?=$bn_time?></td>
            <td style="text-align:center;"><?=$bn_begin_time?></td>
            <td style="text-align:center;"><?=$bn_end_time?></td>
            <td style="text-align:center;"><?=$row[bn_order]?></td>
            <td style="text-align:center;">
                <?php
                $sqla = " select count(*) as cnt from g5_shop_banner_list where bn_id = $row[bn_id] ";
                $rowa = sql_fetch($sqla);
                echo $rowa[cnt];
                ?>
            </td>
            <td style=""><?=$row[bn_url]?></td>
        </tr>
        <?php
    }
    ?>
</table>
</body>
</html>