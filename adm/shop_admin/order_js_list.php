<?php
$sub_menu = '500230';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '정산내역관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
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
<option value="od_time" <?php echo get_selected($a_class, 'od_time'); ?>>정산일자</option> 
</select> 

<label for="fr_date" class="sound_only">기간 시작일</label>
<input type="text" name="fr_date" value="<?php echo $fr_date; ?>" id="fr_date2" required class="required frm_input" size="14" maxlength="12">
~
<label for="to_date" class="sound_only">기간 종료일</label>
<input type="text" name="to_date" value="<?php echo $to_date; ?>" id="to_date2" required class="required frm_input" size="14" maxlength="12">
<input type="submit" value="검색" class="btn_submit">


</form>

<?php if ($is_admin == 'super') {?>
<div class="btn_add01 btn_add">
	<? if($is_admin) { ?>
		<a href="./order_js_list_excel.php?c_class=<?=$c_class?>&amp;m_class=<?=$m_class?>&amp;a_class=<?=$a_class?>&amp;fr_date=<?=$fr_date?>&amp;to_date=<?=$to_date?>">엑셀다운로드</a>
	<? } ?>
</div>

<?php } ?>

<form name="fcategorylist" method="post" action="./order_js_update.php" onsubmit="return fboardlist_submit(this);" autocomplete="off">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="fr_date" value="<?php echo $fr_date; ?>">
<input type="hidden" name="to_date" value="<?php echo $to_date; ?>">

<input type="hidden" name="mode" value="U">

<div style="position:relative;width:100%;overflow-y:scroll;">

<div id="sct" class="tbl_head02 tbl_wrap dataTables_scroll" style="">
 

 <table id="example" class="display nowrap" style="width:100%" >
 	<thead>
	<tr>
		<? if($is_admin){?>
		<th width="2%"><input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)"></th>
		<? } ?>
		<th width="8%">정산일자</th>
		<th width="8%">정산기간</th>
		<th>문서번호</th>
		<th width="8%">공급사명</th>
		<th width="5%">주문계</th>
		<th width="5%">수수료계</th>
		<th width="5%">수수료율</th>
		<th width="5%">정산금액</th>
		<th width="5%">배송비</th>
		<th width="5%">최종입금금액</th>
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
		<? if($is_admin){?>
		<td rowspan="<?=$sql_cnt[cnt]?>" style="text-align:center;">
			<input type="checkbox" name="chk[]" value="<?php echo $ii ?>" id="chk_<?php echo $ii ?>">
            <input type="hidden" name="wr_id[<?php echo $ii; ?>]" value="<?php echo $row['wr_id']; ?>">
        </td>
		<? } ?> 
		<td style="text-align:center;"><a href="#" onclick="show_js(<?=$row[wr_id]?>);return false;"><?=$row[wr_datetime]?></a></td> 
		<td style="text-align:center;"><?=$row[wr_sdate]?>~<?=$row[wr_edate]?></td> 
		<td style="text-align:center;"><a href="#" onclick="show_js(<?=$row[wr_id]?>);return false;"><?=$row[wr_subject]?></a></td> 
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
		<td style="text-align:center;" <? if($is_admin){?>colspan="5"<?}else{?>colspan="4"<?}?>>기간 전체합계</td>
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

    $code_name = $sql_site['code_name'];

    switch($code_name) {
        case "산부인과 협동조합" : $code_name = "산부인과몰"; break;
        case "메디포탈" : $code_name = "키메디몰"; break;
    }

    return $code_name;

	return $sql_site['code_name'];
}
?>
<? if($is_admin){?>
<div class="btn_list01 btn_list">
    <input type="submit" value="삭제" onclick="document.pressed=this.value" >
</div>
<? } ?>
</form>
<!-- //shop_juny -->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;site_code=".$site_code."&amp;c_class=".$c_class."&amp;m_class=".$m_class."&amp;a_class=".$a_class."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;page="); ?>
 
<script>
$(function(){
    $("#fr_date2, #to_date2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
	if(document.pressed == "삭제") {
		f.mode.value = "D";
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
function show_js(num){
	window.open("./order_js_list_pop.php?wr_id="+num,"js_pop","width=1200,height=500,scrollbars=yes,resizable=yes");
}
</script>

 
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
