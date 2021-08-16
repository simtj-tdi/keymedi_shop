<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
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
	<span><a href="/shop/orderinquiry.php">주문내역</a></span>
	<span><a href="/shop/takeback.php">반품신청</a></span>
	<span><a href="/shop/wishlist.php">위시리스트</a></span>
	<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
	<span class="ov"><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
	<span><a href="/shop/mylist.php">문의내역</a></span>
	<? if($member[mb_v] == "4"){?>
		<span><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
</div>
<div id="sub_top_new_menu_title">
	<h2>쿠폰/<?=$point_txt?></h2>
</div>

<div id="item_ex_box2">
	<div id="item_s_12" class=""><a href="/shop/coupon.php">쿠폰</a></div>
	<div id="item_s_22" class="ovtop"><a href="/bbs/point.php"><?=$point_txt?></a></div>
	<div id="item_s_32" ></div>

	<div style="position:relative;top:-1px;padding:30px;clear:both;border-left:1px solid #d7d7d7;border-right:1px solid #d7d7d7;border-bottom:1px solid #d7d7d7;">




<style>
#point table td.plusnum {
    color:#02a2e3;
}
</style>
<div id="new_search_frm" style="width:93%;">
	<form name="s_frm" action="" method="get">
		<p>조회기간&nbsp;&nbsp;&nbsp;<input type="text" name="sdate" class="new_input datepicker" value="<?=$sdate?>">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;<input type="text" name="edate" class="new_input datepicker" value="<?=$edate?>"></p>
		<p>			
			유형<select name="wr_class" class="new_input2" style="margin-left:43px;">
				<option value="">전체</option>
				<option value="add" <?=($wr_class=="add")?"selected":""?>>적립</option>
				<option value="dell" <?=($wr_class=="dell")?"selected":""?>>사용</option> 

			</select> 
			<input type="image" src="/img/board/serch_btn.png" alt=""  class="new_input3" >
		</p>
	</form>
</div>  

<div id="point" class="new_win">
    <!-- <h1 id="win_title"><?php echo $g5['title'] ?></h1> -->
	<h2 style="font-size:18px;text-indent:20px;margin-bottom:10px;">보유<?=$point_txt?> <span style="color:#02a2e3;"><?php echo number_format($member['mb_point']); ?></span>점</h2>
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?=$point_txt?> 사용내역 목록</caption>
        <thead>
        <tr>
			<th>날짜</th> 
			<th>유형</th>
			<th>내용</th>
			<th>금액</th>
			<th>유효기간</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sum_point1 = $sum_point2 = $sum_point3 = 0;

        $sql = " select *
                    {$sql_common}
                    {$sql_order}
                    limit {$from_record}, {$rows} ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
			
			if($row[po_point]>0) { $is_use = "적립"; } else { $is_use = "사용"; }		

            $point1 = $point2 = 0;
            if ($row['po_point'] > 0) {
                $point1 = '+' .number_format($row['po_point']);
                $sum_point1 += $row['po_point'];
            } else {
                $point2 = number_format($row['po_point']);
                $sum_point2 += $row['po_point'];
            }

            $po_content = $row['po_content'];

            $expr = '';
            if($row['po_expired'] == 1)
                $expr = ' txt_expired';
        ?>
        <tr>
            <td style="width:150px;text-align:center;font-size:14px;"><?=substr($row[po_datetime],0,19)?></td> 
			<td style="width:100px;text-align:center;font-size:14px;" class="<?=($row[po_point]>0)?"plusnum":""?>"><?=$is_use?></td>
			<td style="font-size:14px;text-align:center;"><?=$row[po_content]?></td>
			<td style="width:150px;text-align:center;font-size:14px;" class="<?=($row[po_point]>0)?"plusnum":""?>"><?=number_format($row[po_point])?>p</td>
			<td style="width:150px;text-align:center;font-size:14px;" class="<?=($row[po_expire_date] <= date('Y-m-d'))?"underL":""?>"><?=$row[po_expire_date]?></td>
        </tr>
        <?php
        }

        if ($i == 0)
            echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
        else {
            if ($sum_point1 > 0)
                $sum_point1 = "+" . number_format($sum_point1);
            $sum_point2 = number_format($sum_point2);
        }
        ?>
        </tbody>
        <tfoot>
        <!-- <tr>
            <th scope="row" colspan="3">소계</th>
            <td><?php echo $sum_point1; ?></td>
            <td><?php echo $sum_point2; ?></td>
        </tr>
        <tr>
            <th scope="row" colspan="3">보유포인트</th>
            <td colspan="2"><?php echo number_format($member['mb_point']); ?></td>
        </tr> -->
        </tfoot>
        </table>
    </div>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

    <!-- <div class="win_btn"><button type="button" onclick="javascript:window.close();">창닫기</button></div> -->
</div>

</div>
</div>