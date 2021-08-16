<?php
$sub_menu = '500230';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

 
header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = $member[mb_id]."_orderlist_".date("Y-m-d").".xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 
 
$g5['title'] = '정산내역관리';
//include_once (G5_ADMIN_PATH.'/admin.head.php');
//include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if(!$fr_date) $fr_date = date("Y-m-d"); 
if(!$to_date) $to_date = date("Y-m-d");

//$fr_date = date_conv($fr_date);
//$to_date = date_conv($to_date);


$sql = "select * FROM  g5_shop_js where 1 = 1   ";
if($m_class){
	$whereis .= " and com_id = '$m_class' ";
}

if(!$is_admin){
	 $whereis .= " and  com_id = '$member[mb_id]' ";
}

if($c_class){
	$whereis .= " and left(od_id ,2) = '$c_class' ";
}
 

if($a_class == "od_time"){
	$whereis .= " and wr_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
} 

if($od_status){
	$whereis .= " and od_status = '$od_status' ";
}

$whereis .= " order by wr_datetime asc ";

//echo $sql;

// 테이블의 전체 레코드수만 얻음

$sql2 = "select count(*) as cnt  FROM g5_shop_js where 1 = 1 ".$whereis;

$config['cf_page_rows'] = 1500;
 
$row = sql_fetch($sql2);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = $sql.$whereis." limit {$from_record}, {$rows}";


$result = sql_query($sql);



$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

 

 <table border="1">
 	<thead>
	<tr> 
		<th>정산일자</th>
		<th>정산기간</th>
		<th>문서번호</th>
		<th>공급사명</th>
		<th>주문계</th>
		<th>수수료계</th>
		<th>수수료율</th>
		<th>정산금액</th>
		<th>배송비</th>
		<th>최종입금금액</th> 
	</tr>
	</thead>
	<tbody>
<? for($ii = 1 ; $row = sql_fetch_array($result);$ii++){
  
	$cmem = get_member($row[com_id]);
		$array = explode("||",$row[od_id]);
		$od_id_tmp = "";
		for($x = 0 ; $x < count($array); $x++){
			if($od_id_tmp == ""){
				$od_id_tmp = "'".$array[$x]."'";
			}else{
				$od_id_tmp = $od_id_tmp.",'".$array[$x]."'";
			}
		}
	$sqld = "select * from g5_shop_cart_js where od_id in ({$od_id_tmp}) ";
	$resd = sql_query($sqld);
	$ct_price = "";
	$comision = "";
	$comision_per = "";
	$comision_js = "";
	$comision_bs = "";
	$comision_last = "";
	

	$od_send_cost = sql_fetch("select sum(od_send_cost) as od_send_cost from g5_shop_order_js where od_id in ({$od_id_tmp}) ");
	$comision_per = sql_fetch("select avg(ct_js) as ct_js from g5_shop_cart_js where od_id in ({$od_id_tmp}) ");
	//g5_shop_order_js

	while($rowd = sql_fetch_array($resd)){
		$ct_price = $ct_price+($rowd[ct_price]* $rowd[ct_qty] );
		$comision = $comision + ( ($rowd[ct_price] * $rowd[ct_qty] ) * $rowd[ct_js]) / 100; 
		$comision_bs = $comision_bs + $rowd[ct_price];

	}
	$comision_js = $ct_price-$comision;
	$comision_bs = $od_send_cost['od_send_cost'];
	$comision_last = $ct_price-$comision+$od_send_cost['od_send_cost'];
?>
	<tr>	 
		<td style="text-align:center;"><?=$row[wr_datetime]?></td> 
		<td style="text-align:center;"><?=$row[wr_sdate]?>~<?=$row[wr_edate]?></td> 
		<td style="text-align:center;"><?=$row[wr_subject]?></td> 
		<td style="text-align:center;"><?=$cmem[mb_nick]?></td> 
		<td style="text-align:center;"><?=number_format($ct_price)?></td> 
		<td style="text-align:center;"><?=number_format($comision)?></td> 
		<td style="text-align:center;"><?=round($comision_per['ct_js'],2)?>%</td>  
		<td style="text-align:center;"><?=number_format($comision_js)?></td> 
		<td style="text-align:center;"><?=number_format($comision_bs)?></td> 
		<td style="text-align:center;"><?=number_format($comision_last)?></td> 
	</tr> 
<?
	$ct_price_all = $ct_price_all + $ct_price;
	$comision_all = $comision_all + $comision;
	$comision_js_all = $comision_js_all + $comision_js;
	$comision_bs_all = $comision_bs_all + $comision_bs;
	$comision_last_all = $comision_last_all + $comision_last;
?>
<? } ?>
	<tr>
		<td style="text-align:center;" colspan="4">기간 전체합계</td>
		<td style="text-align:center;" ><?=number_format($ct_price_all)?></td>
		<td style="text-align:center;" ><?=number_format($comision_all)?></td>
		<td style="text-align:center;" >-</td>
		<td style="text-align:center;" ><?=number_format($comision_js_all)?></td>
		<td style="text-align:center;" ><?=number_format($comision_bs_all)?></td>
		<td style="text-align:center;" ><?=number_format($comision_last_all)?></td>
	</tr>
	</tbody>		
</table> 
</div>
 </div>
<?
function get_sitename($code){
	$sql_site = sql_fetch("select code_name from site_code where pay_code = '$code' ");
	return $sql_site['code_name'];
}
?>
 

 