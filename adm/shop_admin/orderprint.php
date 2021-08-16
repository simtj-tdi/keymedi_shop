<?php
$sub_menu = '500120';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '주문내역출력';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="local_sch02 local_sch">
<? if($is_admin) { ?>
    <div>
        <form name="forderprint" action="./orderprintresult.php" onsubmit="return forderprintcheck(this);" autocomplete="off">
        <input type="hidden" name="case" value="1">

        <strong class="sch_long">기간별 출력</strong>
        <input type="radio" name="csv" value="xls" id="xls1">
        <label for="xls1">MS엑셀 XLS 데이터</label>
        <input type="radio" name="csv" value="csv" id="csv1">
        <label for="csv1">MS엑셀 CSV 데이터</label>
        <label for="ct_status_p" class="sound_only">출력대상</label>
        <select name="ct_status" id="ct_status_p">
            <option value="주문">주문</option>
            <option value="입금">입금</option>
            <option value="준비">준비</option>
            <option value="배송">배송</option>
            <option value="완료">완료</option>
            <option value="취소">취소</option>
            <option value="반품">반품</option>
            <option value="품절">품절</option>
            <option value="">전체</option>
        </select>
        <label for="fr_date" class="sound_only">기간 시작일</label>
        <input type="text" name="fr_date" value="<?php echo date("Ymd"); ?>" id="fr_date" required class="required frm_input" size="10" maxlength="8">
        ~
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php echo date("Ymd"); ?>" id="to_date" required class="required frm_input" size="10" maxlength="8">
        <input type="submit" value="출력 (새창)" class="btn_submit">

        </form>
    </div>

    <div class="sch_last">

        <form name="forderprint" action="./orderprintresult.php" onsubmit="return forderprintcheck(this);" autocomplete="off" >
        <input type="hidden" name="case" value="2">
        <strong class="sch_long">주문번호구간별 출력</strong>

        <input type="radio" name="csv" value="xls" id="xls2">
        <label for="xls2">MS엑셀 XLS 데이터</label>
        <input type="radio" name="csv" value="csv" id="csv2">
        <label for="csv2">MS엑셀 CSV 데이터</label>
        <label for="ct_status_n" class="sound_only">출력대상</label>
        <select name="ct_status" id="ct_status_n">
            <option value="주문">주문</option>
            <option value="입금">입금</option>
            <option value="준비">준비</option>
            <option value="배송">배송</option>
            <option value="완료">완료</option>
            <option value="취소">취소</option>
            <option value="반품">반품</option>
            <option value="품절">품절</option>
            <option value="">전체</option>
        </select>
        <label for="fr_od_id" class="sound_only">주문번호 구간 시작</label>
        <input type="text" name="fr_od_id" id="fr_od_id" required class="required frm_input" size="10" maxlength="20">
        ~
        <label for="fr_od_id" class="sound_only">주문번호 구간 종료</label>
        <input type="text" name="to_od_id" id="to_od_id" required class="required frm_input" size="10" maxlength="20">
        <input type="submit" value="출력 (새창)" class="btn_submit">

        </form>
    </div>
<? } ?>	
	 <div class="sch_last">

        <form name="forderprint" action="./orderprintresult2.php" onsubmit="return forderprintcheck2(this);" autocomplete="off" >
        <input type="hidden" name="case" value="2">
        <strong class="sch_long">공급사별 엑셀출력</strong>
 
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
                        default : $code_name = $row_site[code_name];
                    }
			?>
				<option value="<?=$row_site[pay_code]?>" <?php echo get_selected($c_class, $row_site[pay_code]); ?> ><?=$code_name;?></option>
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
				$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' order by mb_nick asc";
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
        <input type="text" name="fr_date" value="<?php echo date("Ymd"); ?>" id="fr_date2" required class="required frm_input" size="10" maxlength="8">
        ~
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php echo date("Ymd"); ?>" id="to_date2" required class="required frm_input" size="10" maxlength="8">
        <input type="submit" value="엑셀출력" class="btn_submit">
 

        </form>
    </div>
<? if($is_admin){ ?>
<div class="sch_last">
	<form name="forderprint" action="./orderprintresult3.php" onsubmit="return forderprintcheck2(this);" autocomplete="off" >
       <input type="hidden" name="case" value="2">
		<strong class="sch_long">공급사별 취소주문 엑셀출력</strong>
 
        <? if($is_admin){ ?>
		<select name="c_class" id="c_class">
			<option value="" <?php echo get_selected($c_class, ''); ?>>산부인과몰과 키메디몰</option>
			<?
				$sql_site = "select * from site_code where stauts = 'ok' ";
				$res_site = sql_query($sql_site);
				while($row_site = sql_fetch_array($res_site)){

                    switch($row_site[code_name]) {
                        case "산부인과 협동조합" : $code_name = "산부인과몰"; break;
                        case "메디포털" : $code_name = "키메디몰"; break;
                        default : $code_name = $row_site[code_name];
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
				$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' order by mb_nick asc";
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
        <input type="text" name="fr_date" value="<?php echo date("Ymd"); ?>" id="fr_date3" required class="required frm_input" size="10" maxlength="8">
        ~
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php echo date("Ymd"); ?>" id="to_date3" required class="required frm_input" size="10" maxlength="8">
        <input type="submit" value="엑셀출력" class="btn_submit">
	</form>
</div>

<div class="sch_last">
	<form name="forderprint" action="./orderprintresult4.php" onsubmit="return forderprintcheck2(this);" autocomplete="off" >
	<strong class="sch_long">상품별 엑셀출력</strong>

	 <? if($is_admin){ ?>
	<select name="c_class" id="c_class">
		<option value="" <?php echo get_selected($c_class, ''); ?>>산부인과몰과 키메디몰</option>
		<?
			$sql_site = "select * from site_code where stauts = 'ok' ";
			$res_site = sql_query($sql_site);
			while($row_site = sql_fetch_array($res_site)){

                switch($row_site[code_name]) {
                    case "산부인과 협동조합" : $code_name = "산부인과몰"; break;
                    case "메디포털" : $code_name = "키메디몰"; break;
                    default : $code_name = $row_site[code_name];
                }
		?>
			<option value="<?=$row_site[pay_code]?>" <?php echo get_selected($c_class, $row_site[pay_code]); ?> ><?=$code_name?></option>
		<? } ?> 
	</select>
	<? } ?>
	<select name="m_cart" id="m_cart">
		<option value="all" <?php echo get_selected($m_cart, 'all'); ?>>전체</option>
		<option value="it_name" <?php echo get_selected($m_cart, 'it_name'); ?>>상품명</option>
		<option value="it_8" <?php echo get_selected($m_cart, 'it_8'); ?>>마스터코드</option>
		<option value="it_id" <?php echo get_selected($m_cart, 'it_id'); ?>>셀러코드</option>
	</select>
	<input type="text" id="m_cart_txt"  name="m_cart_txt" value="<?php echo $m_cart_txt; ?>" class="frm_input" size="30" >

	<select name="a_class" id="a_class">
		<option value="od_time" <?php echo get_selected($a_class, 'od_time'); ?>>주문시간</option>
		<option value="od_receipt_time" <?php echo get_selected($a_class, 'od_receipt_time'); ?>>결제일시</option>
	</select> 

	<label for="fr_date" class="sound_only">기간 시작일</label>
	<input type="text" name="fr_date" value="<?php echo date("Ymd"); ?>" id="fr_date3" required class="required frm_input" size="10" maxlength="8">
	~
	<label for="to_date" class="sound_only">기간 종료일</label>
	<input type="text" name="to_date" value="<?php echo date("Ymd"); ?>" id="to_date3" required class="required frm_input" size="10" maxlength="8">
	<input type="submit" value="엑셀출력" class="btn_submit">
</div>
<? } ?>


</div>

<div class="btn_add01 btn_add">
    <a href="./orderlist.php" class="btn_add01 btn_add_optional">주문내역</a>
</div>

<div class="local_desc01 local_desc">
    <p>기간별 혹은 주문번호구간별 주문내역을 새창으로 출력할 수 있습니다.</p>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
$(function(){
    $("#fr_date2, #to_date2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
$(function(){
    $("#fr_date3, #to_date3").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
function forderprintcheck(f)
{
    if (f.csv[0].checked || f.csv[1].checked)
    {
        f.target = "_top";
    }
    else
    {
        var win = window.open("", "winprint", "left=10,top=10,width=670,height=800,menubar=yes,toolbar=yes,scrollbars=yes");
        f.target = "winprint";
    }

    f.submit();
}
function forderprintcheck2(f)
{
	f.target = "_top";
	f.submit();
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
