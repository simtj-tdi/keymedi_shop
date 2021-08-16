<?php
$sub_menu = "940700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


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


$rows = '15';
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$sql = "SELECT * FROM ".$tbl." WHERE 1=1 {$sql_search} {$sql_order} LIMIT {$from_record}, {$rows} ";

$result = sql_query($sql);


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '동영상팝업배너 로그';
include_once('../admin.head.php');

$colspan = 15;
?>
<link type="text/css" href="/css/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
.ui-datepicker { font:12px dotum; }
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 70px;}
.ui-datepicker-trigger { margin:0 0 -5px 2px; cursor:pointer;}
</style>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
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




<div class="local_ov01 local_ov">
	
	<select name="privacy_s" class="frm_input" onchange="privacy_s(this.value)"> 
	<option value="rck_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/rck_list.php'); ?>>추천왕이벤트</option>
	<option value="roulette/roulette_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/roulette/roulette_list.php'); ?>>룰렛이벤트</option>
	<option value="roulette/moon9_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/roulette/moon9_list.php'); ?>>송편이벤트</option>	
	<option value="roulette/goldpig_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/roulette/goldpig_list.php'); ?>>황금돼지</option>	
	<option value="roulette2/roulette_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/roulette2/roulette_list.php'); ?>>친구추천룰렛</option>	
	<option value="roulette/movpop_log_list.php" <?php echo get_selected($_SERVER['PHP_SELF'], '/adm/roulette/movpop_log_list.php'); ?>>영상쇼핑배너</option>
</select><br><br>
<script>
function privacy_s(ddd){
	document.location.href = "<?php echo G5_ADMIN_URL ?>/"+ddd;
}
</script>

    <?php echo $listall ?>
    검색수 : <?php echo number_format($total_count) ?>


</div>



<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>

<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
</select> 
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class=" frm_input">
기간 : 
<input type="text" name="sdate" class="frm_input datepicker" size="12" value="<?=$sdate?>"> ~
<input type="text" name="edate" class="frm_input datepicker" size="12" value="<?=$edate?>">

<input type="submit" class="btn_submit" value="검색">
</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
   <a href="#" onclick="down_excel('./movpop_log_excel.php');" id="member_add">엑셀다운</a>
</div>
<?php } ?>

<form name="fboardlist" id="fboardlist" action="./movpop_log_list.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="hidden" name="mode" value="D">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
		<th scope="col">번호</th>
		<th scope="col">이름</th>
		<th scope="col">분류</th>
		<th scope="col">날짜</th>
    </tr>
    </thead>
    <tbody>
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
		if ($i == 0)
			echo "<tr><td colspan='4' class='empty_table'>내역이 없습니다.</td></tr>";
	?>
	</tbody>
	</table>
</div>
<div class="btn_list01 btn_list">
   <?php if ($is_admin == 'super') { ?>
   <!-- <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value"> -->
	
    <?php } ?>
</div>
</form >
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>

function down_excel(src){
	document.fsearch.action = src;
	document.fsearch.submit();
	document.fsearch.action = "./movpop_log_list.php";
}
</script>
<?php
include_once('./admin.tail.php');
?>
