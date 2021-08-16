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

$g5['title'] = '정산관리';
//include_once (G5_PATH.'/head.sub.php');
//include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
//if(!$fr_date) $fr_date = date("Y-m-d"); 
//if(!$to_date) $to_date = date("Y-m-d");

//$fr_date = date_conv($fr_date);
//$to_date = date_conv($to_date);

$aaa = sql_fetch("select * from g5_shop_js where wr_id = '$wr_id' ");

$array = explode("||",$aaa[od_id]);
$od_id_tmp = "";
for($x = 0 ; $x < count($array); $x++){
	if($od_id_tmp == ""){
		$od_id_tmp = "'".$array[$x]."'";
	}else{
		$od_id_tmp = $od_id_tmp.",'".$array[$x]."'";
	}
}

$sql = "select * FROM g5_shop_order_js where 1 = 1   ";
 
$whereis .= " and od_id in ({$od_id_tmp}) ";
 
 

$whereis .= " order by od_time asc ";

//echo $sql;

// 테이블의 전체 레코드수만 얻음

$sql2 = "select count(*) as cnt  FROM g5_shop_order_js where 1 = 1 ".$whereis;

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
<!-- 
    https://code.jquery.com/jquery-3.3.1.js
    https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.js"></script> -->
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> 
<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.js"></script> -->
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<style>
/*
div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
	*/
</style> 
<?
	$mem = get_member($aaa[com_id]);
?>
<h4> - 정산정보-</h4>
<h4>공급사 : <?=$mem[mb_nick]?></h4>
<h4>정산일자 : <?=$aaa[wr_datetime]?></h4>
<h4>문서번호 : <?=$aaa[wr_subject]?></h4>
<h4>정산기간 : <?=$aaa[wr_sdate]?>~<?=$aaa[wr_edate]?></h4>
 
 

 <table id="example" class="display nowrap" style="width:100%" border="1" >
 	<thead>
	<tr> 
		<th>사이트구분</th>
		<th>PG번호</th>
		<th>주문번호</th>
		<th>공급사명</th>
		<th>마스터코드</th>

		<th>셀러상품코드</th>

		<th>입점사코드</th>
		<th>1차 카테고리</th>
		<!-- <th>2차 카테고리</th>
		<th>3차 카테고리</th>
		<th>4차 카테고리</th> -->
		<th>상품명</th>		
		<th>규격</th>
		<th>단위</th>
		<th>단가</th>
		<th>주문수량</th>
		<th>개별쿠폰</th>
		<th>합계</th>

		<th>상품상태</th>
		<th>총 주문가격</th>
		<th>배송비</th>

		<th>수수료율</th>
		<th>수수료금액</th>
		<th>정산금액</th>

		<th>취소금액</th>
		<th>(주문)쿠폰사용금액</th>
		<th>포인트사용금액</th>
		<th>입금합계</th>
		<th>회원아이디</th>
		<th>의사명</th>
		<th>병원명</th>
		<th>사업자번호</th>
		<th>주문시간</th>
		<th>결제일시</th>
		<th>발송완료일시</th>
		<th>현재주문상태</th> 
	</tr>
	</thead>
	<tbody>
<? for($ii = 1 ; $row = sql_fetch_array($result);$ii++){

	$cmem = get_member($row[com_id]);

	
	//if($c_class=="98"){ 
	if(substr($row[od_id],0,2)=="98"){
		$rmem = sql_fetch("select * from shop_skin.g5_member where mb_id = '$row[mb_id]' ");
		$site_fix = "shop_skin";
	}else if(substr($row[od_id],0,2)=="99"){
		$rmem = sql_fetch("select * from portal.g5_member where mb_id = '$row[mb_id]' ");
	}else{
		$rmem = get_member($row[mb_id]); 
	}
	$sql_cc = sql_fetch("select sum(od_cart_coupon) as od_cart_coupon_sum from (
					select (select sum(cp_price) from {$site_fix}.g5_shop_cart_js c where c.od_id = m.od_id ) as od_cart_coupon from g5_shop_order 
					m where mb_id = '$row[mb_id]' and od_id = '$row[od_id]'
				) mm ");


	$sql_cnt = sql_fetch("select count(*) as cnt from {$site_fix}.g5_shop_cart_js where od_id = '$row[od_id]' ");
	$sqld = "select * from {$site_fix}.g5_shop_cart_js where od_id = '$row[od_id]' ";
	$res = sql_query($sqld);

	for($i = 1 ; $rowd = sql_fetch_array($res);$i++){
		
		$it_8 = sql_fetch("select it_8 , it_9 , it_s_commission , it_commission from {$site_fix}.{$g5['g5_shop_item_table']} where it_id = '$rowd[it_id]' ");

		$rowdd = sql_fetch("select ca_id , it_5 , it_6 , it_9  from {$g5['g5_shop_item_table']} where it_id = '$it_8[it_8]' ");
		 

		if(strlen($rowdd[ca_id]) > 8){
			$cate1 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,2)."' ");
			$cate2 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,4)."' ");
			$cate3 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,6)."' ");
			$cate4 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,8)."' ");
			$cate5 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '$rowdd[ca_id]' ");
		}else if(strlen($rowdd[ca_id]) > 6){
			$cate1 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,2)."' ");
			$cate2 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,4)."' ");
			$cate3 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,6)."' ");
			$cate4 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '$rowdd[ca_id]' ");
			$cate5 = "";
		}else if(strlen($rowdd[ca_id]) > 4){
			$cate1 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,2)."' ");
			$cate2 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,4)."' ");
			$cate3 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '$rowdd[ca_id]' ");
			$cate4 = "";
			$cate5 = "";
		}else if(strlen($rowdd[ca_id]) > 2){
			$cate1 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '".substr($rowdd[ca_id],0,2)."' ");
			$cate2 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '$rowdd[ca_id]' ");
			$cate3 = "";
			$cate4 = "";
			$cate5 = "";
		}else{
			$cate1 = sql_fetch("select ca_name  from {$site_fix}.g5_shop_category where ca_id = '$rowdd[ca_id]' ");
			$cate2 = "";
			$cate3 = "";
			$cate4 = "";
			$cate5 = "";
		}	 
		$commission = "0";

		if($it_8['it_s_commission'] > 0){
			$commission	= $it_8['it_s_commission'];
		}else if($it_8['it_commission'] > 0){
			$commission	= $it_8['it_commission'];
		}else if($cmem[mb_27]){
			$commission = $cmem[mb_27];	
		}else{
			$commission = "0";
		}
		$commission = $rowd[ct_js];
		$tmp_commission = $rowd[ct_price] * $rowd[ct_qty];
		
		$tmp_commission1 = ( $tmp_commission * $commission) / 100; 
		$tmp_commission2 = $tmp_commission - $tmp_commission1;


		$cp_price_all = $cp_price_all + $rowd[ct_price];
		$cp_price_all2 = $cp_price_all2 + ($rowd[ct_price] * $rowd[ct_qty]);
		

		$tmp_commission1_all = $tmp_commission1_all + $tmp_commission1;
		$tmp_commission2_all = $tmp_commission2_all + $tmp_commission2;
	if($i == 1){
		$od_cart_price_all = $od_cart_price_all + $row[od_cart_price];
		$od_send_cost_all = $od_send_cost_all + $row[od_send_cost];
	
?>
	<tr>	 
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=get_sitename(substr($row['pod_id'],0,2))?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>">&nbsp;<?=$row[pod_id]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>">&nbsp;<?=$row[od_id]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$cmem[mb_nick]?></td>
		<td><?=$it_8[it_8]?></td>

		<td>&nbsp;<?=$rowd[it_id]?></td>

		<td><?=$it_8[it_9]?></td>
		<td><?=$cate1[ca_name]?></td>
		<!-- <td><?=$cate2[ca_name]?></td>
		<td><?=$cate3[ca_name]?></td>
		<td><?=$cate4[ca_name]?></td> -->
		<td><?=$rowd[it_name]?></td>		
		<td><?=$rowdd[it_5]?></td>
		<td><?=$rowdd[it_6]?></td>
		<td><?=number_format($rowd[ct_price])?></td>
		<td><?=number_format($rowd[ct_qty])?></td>
		<td><?=number_format($rowd[cp_price])?></td>
		<td><?=number_format($rowd[ct_price] * $rowd[ct_qty])?></td>
		<td><?=$rowd[ct_status]?></td>

		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_cart_price])?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_send_cost])?></td>

		<td><?=$commission?>% 
			<input type="hidden" name="it_id[<?php echo $ii; ?>][]" value="<?=$rowd[it_id]?>">
			<input type="hidden" name="com_id[<?php echo $ii; ?>]" value="<?=$row[com_id]?>">
		</td>
		<td><?=number_format($tmp_commission1)?></td>
		<td><?=number_format($tmp_commission2)?></td>


		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_cancel_price])?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_coupon])?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_receipt_point])?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=number_format($row[od_cart_price] + $row[od_send_cost] - $row[od_receipt_point] - $row[od_coupon] - $sql_cc[od_cart_coupon_sum])?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$row[mb_id]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$rmem[mb_name]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$rmem[mb_11]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$rmem[mb_15]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$row[od_time]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$row[od_receipt_time]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$row[od_invoice_time]?></td>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$row[od_status]?></td>	
 
	</tr>
 <? }else { 
	 
 ?>	
	<tr>
		<td><?=$it_8[it_8]?></td>
		<td>&nbsp;<?=$rowd[it_id]?></td>
		<td><?=$it_8[it_9]?></td>
		<td><?=$cate1[ca_name]?></td>
		<!-- <td><?=$cate2[ca_name]?></td>
		<td><?=$cate3[ca_name]?></td>
		<td><?=$cate4[ca_name]?></td> -->
		<td><?=$rowd[it_name]?></td>		
		<td><?=$rowdd[it_5]?></td>
		<td><?=$rowdd[it_6]?></td>
		<td><?=number_format($rowd[ct_price])?></td>
		<td><?=number_format($rowd[ct_qty])?></td>
		<td><?=number_format($rowd[cp_price])?></td>
		<td><?=number_format($rowd[ct_price] * $rowd[ct_qty])?></td>
		<td><?=$rowd[ct_status]?></td>

		<td><?=$commission?>%
			<!-- <input type="text" name="commission[<?php echo $ii; ?>][]" class="frm_input" style="width:50px;" value="<?=$commission?>">% -->
			<input type="hidden" name="it_id[<?php echo $ii; ?>][]" value="<?=$rowd[it_id]?>">
		</td>
		<td><?=number_format($tmp_commission1)?></td>
		<td><?=number_format($tmp_commission2)?></td>

	</tr>
	<? } ?>
<? } ?>
<? } ?>
	<tr>
		<td colspan="11" style="text-align:center;">소계</td>
		<td style="text-align:center;"><?=number_format($cp_price_all)?></td>
		<td colspan="2"  style="text-align:center;">-</td>
		<td style="text-align:center;"><?=number_format($cp_price_all2)?></td>
		<td style="text-align:center;">-</td>
		<td style="text-align:center;"><?=number_format($od_cart_price_all)?></td>
		<td style="text-align:center;"><?=number_format($od_send_cost_all)?></td>
		<td style="text-align:center;">-</td>
		<td style="text-align:center;"><?=number_format($tmp_commission1_all)?></td>
		<td style="text-align:center;"><?=number_format($tmp_commission2_all)?></td>
		
		<td  colspan="12" style="text-align:center;">-</td>
	</tr>
	</tbody>		
</table> 
 
<?
function get_sitename($code){
	$sql_site = sql_fetch("select code_name from site_code where pay_code = '$code' ");

    $code_name = $sql_site['code_name'];

    switch($code_name) {
        case "산부인과 협동조합" : $code_name = "산부인과몰"; break;
        case "메디포탈" : $code_name = "키메디몰"; break;
    }

    return $code_name;
}
?>

   