<?php
$sub_menu = "400810";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
auth_check($auth[$sub_menu], 'r');

$sql = "select * from g5_coupon where wr_id = '$wr_id'";

$row = sql_fetch($sql);


$g5['title'] = '쿠폰관리 상세';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>
<style>
.th_color {background: #f5f8f9 none repeat scroll 0 0;}
</style>
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
<script type="text/javascript">
/* Korean initialisation for the jQuery calendar extension. */
/* Written by DaeKwon Kang (ncrash.dk@gmail.com). */
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
        yearRange: 'c-99:c+99'
       
    });
});
</script>
<div class="tbl_frm01 tbl_wrap">
		<form name="fcouponform" id="fcouponform" action="./coupon_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
		<input type="hidden" name="wr_id" value="<?php echo $row['wr_id'] ?>">
		<input type="hidden" name="mode" value="<?=($row['wr_id'])?"U":"" ?>">
        <table>
        <tbody>
		   
        <tr>
            <th scope="row"  class="th_color" width="150"><label for="wr_subject">쿠폰명<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" maxlength="250" size="50" class="frm_input required" required="" id="wr_subject" value="<?=$row[wr_subject]?>" name="wr_subject"></td>
        </tr>
		<tr>
			<th scope="row"><label for="cp_method">쿠폰종류</label></th>
			<td>
			   <?php echo help("쿠폰 종류를 변경하시면 입력 서식도 일부 변경됩니다."); ?>
			   <select name="wr_method" id="cp_method">
					<option value="0"<?php echo get_selected('0', $row['wr_method']); ?>>개별상품할인</option>
					<!-- <option value="1"<?php echo get_selected('1', $cp['cp_method']); ?>>카테고리할인</option> -->
					<option value="2"<?php echo get_selected('2', $row['wr_method']); ?>>주문금액할인</option>
					<!-- <option value="3"<?php echo get_selected('3', $cp['cp_method']); ?>>배송비할인</option> -->
			   </select>
			</td>
		</tr>
		 <tr id="tr_cp_target">
			<th scope="row"><label for="cp_target">적용상품</label></th>
			<td>
			   <input type="text" name="wr_target" value="<?php echo stripslashes($row['wr_target']); ?>" id="cp_target" required class="required frm_input">
			   <button type="button" id="sch_target" class="btn_frmline">상품검색</button>
			</td>
		</tr>
		 <tr>
            <th scope="row"  class="th_color" width="150"><label for="wr_sdate">쿠폰 시작일<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" maxlength="50" size="10" class="frm_input required datepicker"  required="" id="wr_sdate" value="<?=$row[wr_sdate]?>" name="wr_sdate"></td>
        </tr>
        <tr>
            <th scope="row"  class="th_color"><label for="wr_edate">쿠폰 종요일</label></th>
            <td><input type="text" maxlength="50" size="10" class="frm_input required datepicker"  id="wr_edate" value="<?=$row[wr_edate]?>" name="wr_edate"></td>
        </tr>
        <tr>
            <th scope="row"  class="th_color"><label for="wr_price">할인금액</label></th>
            <td><input type="text" maxlength="15" size="15" class="frm_input required" id="wr_price" value="<?=$row[wr_price]?>" name="wr_price"></td>
        </tr>
		<tr>
            <th scope="row"  class="th_color"><label for="wr_min">최소주문금액</label></th>
            <td><input type="text" maxlength="15" size="15" class="frm_input required" id="wr_min" value="<?=$row[wr_min]?>" name="wr_min"></td>
        </tr>
		<tr>
            <th scope="row"  class="th_color"><label for="wr_num">쿠폰 발급 갯수</label></th>
            <td><input type="text" size="10" class="frm_input required" id="wr_num" value="<?=$row[wr_num]?>" name="wr_num"></td>
        </tr> 
        </tbody>
        </table>
		<div class="btn_confirm" style="margin-top:20px;text-align:center;">
			<input type="submit" class="btn_submit" accesskey="s" id="btn_submit" value="작성완료">
			<a class="btn_cancel" href="./coupon_list.php">취소</a>
			<a class="btn_cancel" href="./coupon_list.php">목록가기</a>
		</div>
    </div>


<script>
function fwrite_submit(f)
{	
   return true;
}
$(function() {
    <?php if(/*$cp['cp_method'] == 2 || */$cp['cp_method'] == 3) { ?>
    $("#tr_cp_target").hide();
    $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
    <?php } ?>
    <?php if($cp['cp_type'] != 1) { ?>
    $("#tr_cp_maximum").hide();
    $("#tr_cp_trunc").hide();
    <?php } ?>
    $("#cp_method").change(function() {
        var cp_method = $(this).val();
        change_method(cp_method);
    });

    $("#cp_type").change(function() {
        var cp_type = $(this).val();
        change_type(cp_type);
    });

   $("#sch_target").click(function() {
        var cp_method = $("#cp_method").val();
        var opt = "left=50,top=50,width=520,height=600,scrollbars=1";
        var url = "./coupontarget.php?sch_target=";
		var url2 = "./coupontarget2.php";

        if(cp_method == "0") {
            window.open(url+"0", "win_target", opt);
        } else if(cp_method == "1") {
            window.open(url+"1", "win_target", opt);
        } else if(cp_method == "2") {
            window.open(url2, "win_target", opt);
        }  else {
            return false;
        }
    });
 
}); 

function change_method(cp_method)
{
    if(cp_method == "0") {
        $("#sch_target").text("상품검색");
        $("#tr_cp_target").find("label").text("적용상품");
        $("#tr_cp_target").find("input").attr("required", true).addClass("required");
        $("#tr_cp_target").show();
    } else if(cp_method == "1") {
        $("#sch_target").text("분류검색");
        $("#tr_cp_target").find("label").text("적용분류");
        $("#tr_cp_target").find("input").attr("required", true).addClass("required");
        $("#tr_cp_target").show();
    }else if(cp_method == "2") {
        $("#sch_target").text("공급사검색");
        $("#tr_cp_target").find("label").text("적용공급사");
        $("#tr_cp_target").find("input").attr("required", true).addClass("required");
        $("#tr_cp_target").show();
    } else {
        $("#tr_cp_target").hide();
        $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
    }
}
</script>
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
