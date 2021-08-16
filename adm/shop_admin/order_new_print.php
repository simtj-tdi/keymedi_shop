<?php
$sub_menu = '500220';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '정산관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if(!$fr_date) $fr_date = date("Y-m-d"); 
if(!$to_date) $to_date = date("Y-m-d");

//$fr_date = date_conv($fr_date);
//$to_date = date_conv($to_date);


$sql = "select *  from ( select * , ( select count(*) as cnt from g5_shop_order_js where g5_shop_order_js.od_id = g5_shop_order.od_id ) as od_comision2  FROM {$g5['g5_shop_order_table']} where 1 = 1 and od_status != '주문' ";
if($m_class){
	$whereis .= " and com_id = '$m_class' ";
}

if(!$is_admin){
	 $whereis .= " and  com_id = '$member[mb_id]' ";
}

if($c_class){
	$whereis .= " and left(od_id ,2) = '$c_class' ";
}

if($c_class=="98"){
	$site_fix = "shop_skin";
}else{
	$site_fix = "shop";
}

if($a_class == "od_time"){
	$whereis .= " and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}else{
	$whereis .= " and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if($od_status1_1 || $od_status1_2 || $od_status1_3 || $od_status1_4 || $od_status1_5){
	$whereis .= " and ( 1 = 2 ";
	$whereis_ad .= " and ( 1 = 2 ";
	if($od_status1_1){
		$whereis .= " or od_status = '$od_status1_1' ";
		$whereis_ad .= " or ct_status = '$od_status1_1' ";
	}
	if($od_status1_2){
		$whereis .= " or od_status = '$od_status1_2' ";
		$whereis_ad .= " or ct_status = '$od_status1_2' ";

	}
	if($od_status1_3){
		$whereis .= " or od_status = '$od_status1_3' ";
		$whereis_ad .= " or ct_status = '$od_status1_3' ";
	}
	if($od_status1_4){
		$whereis .= " or od_status = '$od_status1_4' ";
		$whereis_ad .= " or ct_status = '$od_status1_4' ";
	}
	if($od_status1_5){
		$whereis .= " or od_status = '$od_status1_5' ";
		$whereis_ad .= " or ct_status = '$od_status1_5' ";
	}
	$whereis .= " ) ";
	$whereis_ad .= " ) ";
}

$whereis .= " order by com_id , od_time asc ) m ";

if($od_comision2 == "ok"){
	$whereis .= " where m.od_comision2 > 0 ";
}
if($od_comision2 == "okk"){
	$whereis .= " where m.od_comision2 = 0 ";
}

//echo $sql;

// 테이블의 전체 레코드수만 얻음

$sql2 = "select count(*) as cnt from ( select * , ( select count(*) as cnt from g5_shop_order_js where g5_shop_order_js.od_id = g5_shop_order.od_id ) as od_comision2 FROM {$g5['g5_shop_order_table']} where 1 = 1 and od_status != '주문' ".$whereis;

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
<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    전체 주문내역 <?php echo number_format($total_count); ?>개
</div>

<form name="flist" class="local_sch01 local_sch">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
<div>
	<strong>정산상태</strong>
	<input type="radio" name="od_comision2" value="" id="od_comision21"    <?php echo get_checked($od_comision2, '');     ?>>
    <label for="od_comision21">전체</label> 
    <input type="radio" name="od_comision2" value="ok" id="od_comision22" <?php echo get_checked($od_comision2, 'ok'); ?>>
    <label for="od_comision22">정산완료</label>
	<input type="radio" name="od_comision2" value="okk" id="od_comision23" <?php echo get_checked($od_comision2, 'okk'); ?>>
    <label for="od_comision23">미정산</label>
</div>
<br>
<div>
    <strong>상품상태<!-- 주문상태 --></strong> 
    <input type="checkbox" name="od_status1_1" value="입금" id="od_status_income" <?php echo get_checked($od_status1_1, '입금'); ?>>
    <label for="od_status_income">입금</label>
    <input type="checkbox" name="od_status1_2" value="준비" id="od_status_rdy" <?php echo get_checked($od_status1_2, '준비'); ?>>
    <label for="od_status_rdy">준비</label>
    <input type="checkbox" name="od_status1_3" value="배송" id="od_status_dvr" <?php echo get_checked($od_status1_3, '배송'); ?>>
    <label for="od_status_dvr">배송</label>
    <input type="checkbox" name="od_status1_4" value="완료" id="od_status_done" <?php echo get_checked($od_status1_4, '완료'); ?>>
    <label for="od_status_done">완료</label>
    <input type="checkbox" name="od_status1_5" value="전체취소" id="od_status_cancel" <?php echo get_checked($od_status1_5, '전체취소'); ?>>
    <label for="od_status_cancel">취소</label> 
</div>
<!-- <br>
<div>
    <strong>상품상태</strong> 
    <input type="checkbox" name="od_status2_1" value="입금" id="od_status_income" <?php echo get_checked($od_status2_1, '입금'); ?>>
    <label for="od_status_income">입금</label>
    <input type="checkbox" name="od_status2_2" value="준비" id="od_status_rdy" <?php echo get_checked($od_status2_2, '준비'); ?>>
    <label for="od_status_rdy">준비</label>
    <input type="checkbox" name="od_status2_3" value="배송" id="od_status_dvr" <?php echo get_checked($od_status2_3, '배송'); ?>>
    <label for="od_status_dvr">배송</label>
    <input type="checkbox" name="od_status2_4" value="완료" id="od_status_done" <?php echo get_checked($od_status2_4, '완료'); ?>>
    <label for="od_status_done">완료</label>
    <input type="checkbox" name="od_status2_5" value="전체취소" id="od_status_cancel" <?php echo get_checked($od_status2_5, '전체취소'); ?>>
    <label for="od_status_cancel">취소</label> 
</div> -->

<br>

<? if($is_admin){ ?>
<select name="c_class" id="c_class">
<option value="" <?php echo get_selected($c_class, ''); ?>>전체</option>
<?
$sql_site = "select * from site_code where stauts = 'ok' ";
$res_site = sql_query($sql_site);
while($row_site = sql_fetch_array($res_site)){
    switch($row_site[code_name]) {
        case "산부인과 협동조합" : $code_name = "산부인과몰"; break;
        case "메디포털" : $code_name = "키메디몰"; break;
        default : $code_name = $row_site[code_name]; break;
    }
?>
<option value="<?=$row_site[pay_code]?>" <?php echo get_selected($c_class, $row_site[pay_code]); ?> ><?=$code_name?></option>
<? } ?> 
</select>
<? } ?>
<select name="m_class" id="m_class">
<? if($is_admin){ ?>
<option value="" <?php echo get_selected($m_class, ''); ?>>전체</option>
<option value="admin" <?php echo get_selected($m_class, 'admin'); ?>>ADMIN</option>
<? } ?>
<?
if($is_admin){
$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4'";
}else{
$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' and mb_id = '$member[mb_id]' ";
}
$mres = sql_query($msql);
while($mrow = sql_fetch_array($mres)){

?>
<option value="<?=$mrow[mb_id]?>" <?php echo get_selected($m_class, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
<? } ?> 
</select>
<select name="a_class" id="a_class">
<option value="od_time" <?php echo get_selected($a_class, 'od_time'); ?>>주문시간</option>
<option value="od_receipt_time" <?php echo get_selected($a_class, 'od_receipt_time'); ?>>결제일시</option>
</select> 

<label for="fr_date" class="sound_only">기간 시작일</label>
<input type="text" name="fr_date" value="<?php echo $fr_date; ?>" id="fr_date2" required class="required frm_input" size="14" maxlength="10">
~
<label for="to_date" class="sound_only">기간 종료일</label>
<input type="text" name="to_date" value="<?php echo $to_date; ?>" id="to_date2" required class="required frm_input" size="14" maxlength="10">
<input type="submit" value="검색" class="btn_submit">


</form>

<?php if ($is_admin == 'super') {?>
<div class="btn_add01 btn_add">
	<? if($is_admin) { ?>
		<a href="./order_new_print_excel.php?c_class=<?=$c_class?>&amp;m_class=<?=$m_class?>&amp;a_class=<?=$a_class?>&amp;fr_date=<?=$fr_date?>&amp;to_date=<?=$to_date?>&amp;od_comision2=<?=$od_comision2?>&od_status1_1=<?=$od_status1_1?>&od_status1_2=<?=$od_status1_2?>&od_status1_3=<?=$od_status1_3?>&od_status1_4=<?=$od_status1_4?>&od_status1_5=<?=$od_status1_5?>">엑셀다운로드</a>
	<? } ?>
</div>

<?php } ?>

<form name="fcategorylist" method="post" action="./order_new_print_update.php" onsubmit="return fboardlist_submit(this);" autocomplete="off">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>"> 

<input type="hidden" name="mode" value="U">

<div style="position:relative;width:100%;overflow-y:scroll;">

<div id="sct" class="tbl_head02 tbl_wrap dataTables_scroll" style="">
 

 <table id="example" class="display nowrap" style="width:100%" >
 	<thead>
	<tr>
		<? if($is_admin){?>
		<th><input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)"></th>
		<? } ?>
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
		<? if($is_admin){?>
		<th>정산상태</th>
		<? } ?>
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
					select (select sum(cp_price) from {$site_fix}.g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon from g5_shop_order 
					m where mb_id = '$row[mb_id]' and od_id = '$row[od_id]'
				) mm ");


	$sql_cnt = sql_fetch("select count(*) as cnt from {$site_fix}.{$g5['g5_shop_cart_table']} where od_id = '$row[od_id]' {$whereis_ad} ");
	$sqld = "select * from {$site_fix}.{$g5['g5_shop_cart_table']} where od_id = '$row[od_id]' {$whereis_ad} ";
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
		
		$tmp_commission = $rowd[ct_price] * $rowd[ct_qty];
		
		$tmp_commission1 = ( $tmp_commission * $commission) / 100; 
		$tmp_commission2 = $tmp_commission - $tmp_commission1;

	if($i == 1){

        $stname = get_sitename(substr($row['pod_id'],0,2));

	
?>
	<tr>	
		<? if($is_admin){?>
		<td rowspan="<?=$sql_cnt[cnt]?>" style="text-align:center;">
			<input type="checkbox" name="chk[]" value="<?php echo $ii ?>" id="chk_<?php echo $ii ?>">
            <input type="hidden" name="od_id[<?php echo $ii; ?>]" value="<?php echo $row['od_id']; ?>">
        </td>
		<? } ?>
		<td rowspan="<?=$sql_cnt[cnt]?>"><?=$stname;?></td>
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

		<td>
			<input type="text" name="commission[<?php echo $ii; ?>][]" class="frm_input" style="width:50px;" value="<?=$commission?>">%
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
		<? if($is_admin){?>
		<td rowspan="<?=$sql_cnt[cnt]?>">
			<?=($row[od_comision2] > 0 )?"완료":"미완료"?> 
		</td>	
		<? } ?>
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

		<td>
			<input type="text" name="commission[<?php echo $ii; ?>][]" class="frm_input" style="width:50px;" value="<?=$commission?>">%
			<input type="hidden" name="it_id[<?php echo $ii; ?>][]" value="<?=$rowd[it_id]?>">
		</td>
		<td><?=number_format($tmp_commission1)?></td>
		<td><?=number_format($tmp_commission2)?></td>

	</tr>
	<? } ?>
<? } ?>
<? } ?>
	</tbody>		
</table> 
</div>
 </div>
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

<div class="btn_list01 btn_list">
정산기간 : 
<input type="text" name="fr_date_tmp" value="<?php echo $fr_date; ?>" id="fr_date22" required class="required frm_input" size="14" maxlength="10" style="padding:5px 10px;">
~
<input type="text" name="to_date_tmp" value="<?php echo $to_date; ?>" id="to_date22" required class="required frm_input" size="14" maxlength="10" style="padding:5px 10px;">

    <input type="submit" value="정산하기" onclick="document.pressed=this.value" >
</div>

</form>
<!-- //shop_juny -->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;site_code=".$site_code."&amp;c_class=".$c_class."&amp;m_class=".$m_class."&amp;a_class=".$a_class."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;page="); ?>
 
<script>
$(function(){
    $("#fr_date2, #to_date2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
$(function(){
    $("#fr_date22, #to_date22").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
	if(document.pressed == "정산하기") {
		f.mode.value = "U";
        if(!confirm("선택한 자료를 정말 정산하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "scrollX": true,
		"paging":   false,
        "ordering": false,
        "info":     false 
    } );
} );
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
