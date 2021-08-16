<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiry.php');
    return;
}

define("_ORDERINQUIRY_", true);

$od_pwd = get_encrypt_string($od_pwd);

// 회원인 경우
if ($is_member)
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
}
else if ($od_id && $od_pwd) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    $sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
}
else // 그렇지 않다면 로그인으로 가기
{
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/orderinquiry.php'));
}

if($sdate){
	 $sql_common .= " and left(od_time,10) >= '$sdate' ";
}
if($edate){
	 $sql_common .= " and left(od_time,10) <= '$edate' ";
}
if($od_status){
	$sql_common .= " and od_status = '$od_status' ";
}
if($com_id){
	$sql_common .= " and com_id = '$com_id' ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
/*
if ($total_count == 0)
{
    if ($is_member) // 회원일 경우는 메인으로 이동
        alert('주문이 존재하지 않습니다.', G5_SHOP_URL);
    else // 비회원일 경우는 이전 페이지로 이동
        alert('주문이 존재하지 않습니다.');
}
*/
$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
if (!$is_member)
{
    $sql = " select od_id, od_time, od_ip from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
    $row = sql_fetch($sql);
    if ($row['od_id']) {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
        set_session('ss_orderview_uid', $uid);
        goto_url(G5_SHOP_URL.'/orderinquiryview.php?od_id='.$row['od_id'].'&amp;uid='.$uid);
    }
}

$g5['title'] = '주문내역조회';
include_once('./_head.php');
?>

<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
<!--
.ui-datepicker { font:12px dotum; }
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 70px;}
.ui-datepicker-trigger { margin:0 0 -5px 2px; cursor:pointer;}
-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script>
jQuery(function($){
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
		'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ko']);

    $('.datepicker').datepicker({
        changeMonth: true,
		changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
//        minDate: '+2d',
		onSelect: function(dateText, inst) { } 
	  
    }); 


});
</script>

<div id="sub_top_new_menu"> 
	<span><a href="/shop/mypage.php">마이페이지</a></span>
	<span class="ov"><a href="/shop/orderinquiry.php">주문내역</a></span>
	<span><a href="/shop/takeback.php">반품신청</a></span>
	<span><a href="/shop/wishlist.php">위시리스트</a></span>
	<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
	<span><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
	<span><a href="/shop/mylist.php">문의내역</a></span>
	<? if($member[mb_v] == "4"){?>
		<span><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
</div>
<div id="sub_top_new_menu_title">
	<h2>주문내역</h2>
</div>

<? 
//if($_SERVER['REMOTE_ADDR']=="116.124.131.177"){
 
?>

<div id="new_search_frm">
	<form name="s_frm" action="" method="get">
		<p>조회기간&nbsp;&nbsp;&nbsp;<input type="text" name="sdate" class="new_input datepicker" value="<?=$sdate?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="edate" class="new_input datepicker" value="<?=$edate?>"></p>
		<p>			
			주문상태&nbsp;&nbsp;&nbsp;<select name="od_status" class="new_input2">
				<option value="">전체</option>
				<option value="주문" <?=($od_status=="주문")?"selected":""?>>입금확인중</option>
				<option value="입금" <?=($od_status=="입금")?"selected":""?>>입금완료</option>
				<option value="준비" <?=($od_status=="준비")?"selected":""?>>상품준비중</option>
				<option value="배송" <?=($od_status=="배송")?"selected":""?>>상품배송</option>
				<option value="완료" <?=($od_status=="완료")?"selected":""?>>배송완료</option>
				<option value="취소" <?=($od_status=="취소")?"selected":""?>>주문취소</option>
			</select>
			&nbsp;&nbsp;&nbsp;공급사&nbsp;&nbsp;&nbsp;
			<select name="com_id" class="new_input2">
				<option value="" <?php echo get_selected($com_id, ''); ?>>전체</option> 
			<?
				$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' order by mb_nick asc ";
				$mres = sql_query($msql);
				while($mrow = sql_fetch_array($mres)){
			?>
				 <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($com_id, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
			<? } ?> 
			</select>
			<input type="image" src="/img/board/serch_btn.png" alt=""  class="new_input3" >
		</p>
	</form>
</div> 
<? //} ?>
<!-- 주문 내역 시작 { -->
<div id="sod_v">
    <p id="sod_v_info">주문서번호 링크를 누르시면 주문상세내역을 조회하실 수 있습니다.</p>

    <?php
    $limit = " limit $from_record, $rows ";
    include "./orderinquiry.sub.php";
    ?>

    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>
<!-- } 주문 내역 끝 -->

<?php
include_once('./_tail.php');
?>
