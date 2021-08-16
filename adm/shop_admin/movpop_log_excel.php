<?php
include_once("./_common.php");
 
header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_RECOMMEND.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 
 

$tbl = "g5_mov_popup_banner_log_tbl" ;

if( $stx ) {
    $sql_search = " AND ".$sfl." LIKE '".$stx."%' " ;
}

if($sdate){
    $sql_search .= " AND reg_dt >= ".strtotime($sdate." 00:00")." ";	
}
if($edate){
   $sql_search .= " AND reg_dt <= ".strtotime($edate." 23:59")." ";	
}

$sst  = "idx";
$sod = "DESC";
$sql_order = " ORDER BY $sst $sod ";

$sql = "SELECT count(*) as tCount FROM ".$tbl." WHERE 1=1 ".$sql_search ;

$result = sql_fetch($sql);

$total_count = $result['tCount'] ;
$page = 1 ;
$rows = 15 ;

$sql = "SELECT * FROM ".$tbl." WHERE 1=1 {$sql_search} {$sql_order} ";

$result = sql_query($sql);


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '동영상팝업배너 로그';


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
      <th scope="col">번호</th>
		<th scope="col">이름</th>
		<th scope="col">분류</th>
		<th scope="col">날짜</th>
    </tr>
	<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'bg'.($i%2);
            $num = $total_count - (($page * $rows) - $rows ) - $i;

            switch( $row['type'] ) {
                case 1 :
                    $view_type = '메인 팝업 VOD' ;
                    break; 
                case 2 :
                    $view_type = '메인 팝업 구매하기' ;
                    break; 
                case 3 :
                    $view_type = '상세화면 구매하기' ;
                    break; 
                case 4 :
                    $view_type = '산협 VOD시청하기' ;
                    break; 
                case 5 :
                    $view_type = '산협 구매하기' ;
                    break; 
                case 6 :
                    $view_type = '키메디몰 VOD시청하기' ;
                    break; 
                case 7 :
                    $view_type = '키메디몰 구매하기' ;
                    break;                     
            }
            $td_st = "style='text-align:center;'" ;
            echo "<tr class='".$bg."'>
                <td ".$td_st.">".$num."</td>
                <td ".$td_st.">".$row['mb_id']."</td>
                <td ".$td_st.">".$view_type."</td>
                <td ".$td_st.">".date("Y-m-d H:i", $row['reg_dt'])."</td> 
			    </tr>" ;
        }
    ?>
</table>
</body>
</html>