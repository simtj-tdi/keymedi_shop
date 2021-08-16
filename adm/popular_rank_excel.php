<?php
$sub_menu = "300400";
include_once('./_common.php');
 
header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_POPULAR.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 
 
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date={$fr_date}&amp;to_date={$to_date}";

$sql_common = " from {$g5['popular_table']} a ";
$sql_search = " where trim(pp_word) <> '' and pp_date between '{$fr_date}' and '{$to_date}' ";
$sql_group = " group by pp_word ";
$sql_order = " order by cnt desc ";
 

$sql = " select pp_word, count(*) as cnt {$sql_common} {$sql_search} {$sql_group} {$sql_order}   ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '인기검색어순위'; 

$colspan = 3;
?>
  
 
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
</head>
  
    <table border="1"> 
    <thead>
    <tr>
        <th scope="col">순위</th>
        <th scope="col">검색어</th>
        <th scope="col">검색회수</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $word = get_text($row['pp_word']);
        $rank = ($i + 1 + ($rows * ($page - 1)));

    ?>

    <tr>
        <td class="td_num"><?php echo $rank ?></td>
        <td><?php echo $word ?></td>
        <td class="td_numbig"><?php echo $row['cnt'] ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table> 
 
