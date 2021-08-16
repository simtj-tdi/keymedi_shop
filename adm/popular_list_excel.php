<?php
$sub_menu = "300300";
include_once('./_common.php');
 

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_POPULAR_LIST.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

// 체크된 자료 삭제
if (isset($_POST['chk']) && is_array($_POST['chk'])) {
    for ($i=0; $i<count($_POST['chk']); $i++) {
        $pp_id = $_POST['chk'][$i];

        sql_query(" delete from {$g5['popular_table']} where pp_id = '$pp_id' ", true);
    }
}

$sql_common = " from {$g5['popular_table']} a ";
$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "pp_word" :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
        case "pp_date" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
if($fr_date){
	$sql_search .= " and pp_date >= '$fr_date' ";
}

if($to_date){
	$sql_search .= " and pp_date <= '$to_date' ";
}

if (!$sst) {
    $sst  = "pp_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";
 

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
           ";
$result = sql_query($sql);
 
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
        <th scope="col">검색어</th>
        <th scope="col">등록일</th>
        <th scope="col">등록IP</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $word = get_text($row['pp_word']);
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td><?php echo $word ?></td>
        <td><?php echo $row['pp_date'] ?></td>
        <td><?php echo $row['pp_ip'] ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
 
 
