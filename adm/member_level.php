<?php
$sub_menu = "200910";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
if(!$site){ $site = "obgys";}
if(!$year){ $year = date("Y");}
if(!$month){ $month = "1";}
if(!$grade){ $grade = "ROYAL";}

$month_tmp1 = "";
$month_tmp2 = "";
switch($month){
	case "1" : $month_tmp1 = "-01-01"; $month_tmp2 = "-03-31";break;
	case "2" : $month_tmp1 = "-04-01"; $month_tmp2 = "-06-31";break;
	case "3" : $month_tmp1 = "-07-01"; $month_tmp2 = "-09-31";break;
	case "4" : $month_tmp1 = "-10-01"; $month_tmp2 = "-12-31";break;
}

switch($grade){
	case "ROYAL" : $grade_tmp = "where mm.price >= '3000000' "; break;
	case "GOLD" : $grade_tmp = "where mm.price < '3000000' and mm.price >= '1500000' "; break;
	case "SILVER" : $grade_tmp = "where mm.price < '1500000' and mm.price >= '600000' "; break;
    case "BRONZE" : $grade_tmp = "where mm.price < '600000' and mm.price >= '300000' "; break;
	case "FAMILY" : $grade_tmp = "where mm.price < '300000' "; break;
}

if($year){
	$sql_search = " and left(od_receipt_time,10) between '{$year}{$month_tmp1}' and '{$year}{$month_tmp2}'   ";
}

if(!$site){
	$site_tmp = " ( left(od_id,2) = '20' or left(od_id,2) = '99' ) ";
}else if($site=="obgys"){
	$mem_g = "g5_member";
	$mem_g2 = "산부인과 협동조합";
	$site_tmp = "  left(od_id,2) = '20' ";
}else if($site=="keymedi"){
	$mem_g = "portal.g5_member";
	$mem_g2 = "메디포털";
	$site_tmp = "  left(od_id,2) = '99' ";
}
if($grade != "FAMILY"){

$sql_common = "select * from ( select m.mb_id , sum(m.price) as price from (

select 
	mb_id , od_cart_price , 
	
	( select sum(ct_price * ct_qty) from g5_shop_cart where 1 = 1 and (ct_status = '배송' or ct_status = '완료') and g5_shop_cart.od_id = g5_shop_order.od_id ) as price 
from g5_shop_order   where 1 = 1  and (od_status = '배송' or od_status = '완료') and $site_tmp $sql_search

) m  group by m.mb_id ) mm {$grade_tmp} order by mm.price desc ";
  

$sql = " select count(*) as cnt from (select * from ( select m.mb_id , sum(m.price) as price from (

select 
	mb_id , od_cart_price , 
	
	( select sum(ct_price * ct_qty) from g5_shop_cart where 1 = 1 and (ct_status = '배송' or ct_status = '완료') and g5_shop_cart.od_id = g5_shop_order.od_id ) as price 
from g5_shop_order   where 1 = 1  and (od_status = '배송' or od_status = '완료') and $site_tmp $sql_search

) m  group by m.mb_id ) mm {$grade_tmp} ) aaa  ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " {$sql_common }  ";
$result = sql_query($sql);
}else{
	
	$sql_common = "select * from ( select * from ( select m.mb_id , sum(m.price) as price from (

	select 
		mb_id , od_cart_price , 
		
		( select sum(ct_price * ct_qty) from g5_shop_cart where 1 = 1 and (ct_status = '배송' or ct_status = '완료') and g5_shop_cart.od_id = g5_shop_order.od_id ) as price 
	from g5_shop_order   where 1 = 1  and (od_status = '배송' or od_status = '완료') and $site_tmp $sql_search

	) m  group by m.mb_id ) mm {$grade_tmp} 
	
	union all

	select mb_id , price from (

	select * , ( select ifnull(sum(od_receipt_price),0) as price from g5_shop_order where g5_shop_order.mb_id = m.mb_id {$sql_search} ) as price from {$mem_g} m 

	where mb_shop = '2' and mb_v = '1' and (mb_level = '5' or mb_level = '6') and mb_where = '{$mem_g2}'

	) mm where mm.price = '0'

	
	) mmm order by mmm.price desc ";  




	$sql = " select count(*) as cnt from ($sql_common ) aaa  ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = " {$sql_common }  ";
	$result = sql_query($sql);

}


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원 등급제 관리';
include_once('./admin.head.php');

$colspan = 7;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<div class="sch_last">
	<select name="site" >		 
		<option value="" <?=($site=="")?"selected":""?>>전체</option>
		<option value="obgys" <?=($site=="obgys")?"selected":""?>>산부인과 협동조합</option>
		<option value="keymedi" <?=($site=="keymedi")?"selected":""?>>키메디몰</option>
		 
	</select>

	<select name="year" >
		<? for($i = 2017 ; $i <= date("Y"); $i++){?>
		<option value="<?=$i?>" <?=($i==$year)?"selected":""?>><?=$i?>년도</option>
		<? }?>
	</select>
    <select name="month" >
		<? for($i = 1 ; $i <= 4; $i++){?>
		<option value="<?=$i?>" <?=($i==$month)?"selected":""?>><?=$i?>분기</option>
		<? }?>
	</select>
	<select name="grade" >
		<option value="ROYAL" <?=($grade=="ROYAL")?"selected":""?>>ROYAL</option>
		<option value="GOLD" <?=($grade=="GOLD")?"selected":""?>>GOLD</option>
		<option value="SILVER" <?=($grade=="SILVER")?"selected":""?>>SILVER</option>
        <option value="BRONZE" <?=($grade=="BRONZE")?"selected":""?>>BRONZE</option>
		<option value="FAMILY" <?=($grade=="FAMILY")?"selected":""?>>FAMILY</option>
	</select>
    <input type="submit" class="btn_submit" value="검색">
</div>
</form>

<div class="btn_add01 btn_add">
    <!-- <a href="./poll_form.php" id="poll_add">투표 추가</a> -->
</div>

<form name="member_level" method="post" id="member_level" action="./member_level_update.php" autocomplete="off">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<input type="hidden" name="years" value="<?php echo $year ?>">
<input type="hidden" name="months" value="<?php echo $month ?>">
<input type="hidden" name="grades" value="<?php echo $grade ?>">

<input type="hidden" name="site" value="<?php echo $site ?>">

<input type="hidden" name="mode" value="coupon">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" width="2%">
            <label for="chkall" class="sound_only">현재 페이지 투표 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" width="3%">순위</th>
        <th scope="col" width="8%">아이디</th>
        <th scope="col" width="8%">성명</th>
        <th scope="col">병원명</th>
        <th scope="col" width="8%">구매금액</th>
        <th scope="col" width="5%">등급</th>
		<th scope="col" width="5%">현재 등급</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		$num = $from_record  + $i +1;

		if($site=="obgys"){
			$mem = get_member($row[mb_id]);
		}else{
			$mem = sql_fetch("select * from portal.g5_member where mb_id = '{$row[mb_id]}' ");
		}
		
		$gregre = sql_fetch(" select wr_class from g5_grade where mb_id = '{$row[mb_id]}'  order by  wr_date desc limit 1");

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk"> 
            <input type="checkbox" name="chk[]" value="<?php echo $row['mb_id'] ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_num"><?php echo $num ?></td>
        <td class="td_mngsmall"><?php echo $row[mb_id] ?></td>
        <td class="td_mngsmall"><?php echo $mem['mb_name'] ?></td>
        <td class="td_mngsmall"><?php echo $mem['mb_11'] ?></td>
        <td class="td_mngsmall"><?php echo number_format($row[price]) ?></td>
        <td class="td_mngsmall"><?php echo $grade ?></td>
		<td class="td_mngsmall"><?php echo $gregre['wr_class'] ?></td>
    </tr>

    <?php
    }

    if ($i==0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

  




<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="grid_4">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="mb_id">쿠폰종류<strong class="sound_only">필수</strong></label></th>
		<td>
			<select name="coupon_id">
				<? 
					$sql = " select * from g5_coupon  where 1 = 1 order by wr_id desc";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
				?>
					<option value="<?=$row[wr_id]?>"><?=$row[wr_subject]?> (금액 : <?=number_format($row[wr_price])?> 기간 : <?=$row[wr_sdate]?> ~ <?=$row[wr_edate]?>)</option>
				<? } ?>
			</select>
		</td>
	</tr>
	 
	<tr>
		<th scope="row"><label for="coupon_num">1인당 발급수</label></th>
		<td>
			<select name="coupon_num">
				<option value="1">1</option>
				<option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
			</select>장
		</td>
	</tr> 

	</tbody>
	</table>
</div>

<div class="btn_confirm01 btn_confirm">
	<input type="submit" value="확인" class="btn_submit">
	<a href="#" class="btn_submit" onclick="go_greade();return false;">등급적용</a>
</div>

</form>

<?//php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function() {
    $('#fpolllist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});
function go_greade(){
	var f = document.member_level;
	f.action = "./member_level_update2.php";
	f.submit();
	f.action = "./member_level_update.php";

}
</script>

<?php
include_once ('./admin.tail.php');
?>