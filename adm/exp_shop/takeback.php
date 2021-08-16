<?php
$sub_menu = "500240";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

 

//$sql_w = sql_fetch("select code from site_code where code = '$site_fix' ");

$sql_common = " from shop.g5_takeback ";

$sql_search = " where 1 = 1 ";
//$sql_search = " where (mb_shop = '2' and mb_level >= '4') and ( mb_where = '{$sql_w[code_name]}' ) and ( mb_level >= '4' or mb_level <='6' ) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
if($mb_s_date){
	 $sql_search .= " and  '{$mb_s_date}' <= left(wr_datetime,10)   ";
}
if($mb_e_date){
	 $sql_search .= " and  left(wr_datetime,10) <= '{$mb_e_date}' ";
}
if($m_class){
	$sql_search .= " and  com_id = '{$m_class}' ";	
}
 if($site_code){
	 $sql_search .= " and  left(od_id,2) = '{$site_code}' ";	
 }
 if($status){
	 $sql_search .= " and  status = '{$status}' ";	
 }
if(!$is_admin){
	 $sql_search .= " and  com_id = '$member[mb_id]' ";
}


if (!$sst) {
    $sst = "wr_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함



$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '반품관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총 <?php echo number_format($total_count) ?>건
</div>

<form id="fsearch" name="fsearch" class="local_sch02 local_sch" method="get">
<p>
<!-- mb_datetime  -->신청일 : <input type="text" name="mb_s_date" id="mb_s_date" value="<?=$mb_s_date?>" class="frm_input"/> ~ <input type="text" name="mb_e_date" id="mb_e_date" value="<?=$mb_e_date?>" class="frm_input"/>

<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
<button type="button" onclick="javascript:set_date('어제');">어제</button>
<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
<button type="button" onclick="javascript:set_date('전체');">전체</button>

</p>
<p>
	반품상태 : 
	<select name="status">
		<option value="" <?=($status=="")?"selected":""?>>선택하세요.</option>
		<option value="반품요청" <?=($status=="반품요청")?"selected":""?>>반품요청</option>
		<option value="반품확인" <?=($status=="반품확인")?"selected":""?>>반품확인</option>
		<option value="반품진행중" <?=($status=="반품진행중")?"selected":""?>>반품진행중</option>
		<option value="반품수령" <?=($status=="반품수령")?"selected":""?>>반품수령</option>
		<option value="반품완료" <?=($status=="반품완료")?"selected":""?>>반품완료</option>
	</select> 
</p>
<p>
<label for="sfl" class="sound_only">검색대상</label>
 <p>
<select name="site_code" id="site_code">
	<option value="">::사이트구분::</option>
	<option value="20" <?=($site_code=="20")?"selected":""?> >산부인과몰</option>
	<option value="99" <?=($site_code=="99")?"selected":""?> >메디몰</option>
	<option value="98" <?=($site_code=="98")?"selected":""?> >피부비만</option>
</select>
<? if($is_admin) { ?>

업체명 : 
<select name="m_class" id="m_class">
	<option value="" <?php echo get_selected($m_class, ''); ?>>전체</option>
	<option value="admin" <?php echo get_selected($m_class, 'admin'); ?>>ADMIN</option>
<?
	$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4'";
	$mres = sql_query($msql);
	while($mrow = sql_fetch_array($mres)){

?>
	 <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($m_class, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
<? } ?> 
</select>
</p>
<? } ?>

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="wr_name" <?php echo get_selected($sfl, 'wr_name'); ?>>이름</option>
	<option value="mb_id" <?php echo get_selected($sfl, 'mb_id'); ?>>아아디</option>
    <option value="status" <?php echo get_selected($sfl, 'status'); ?>>상태</option> 
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">



<input type="submit" class="btn_submit" value="검색">
</p>
</form>

<div class="local_desc01 local_desc">
    <p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>
</div>



<form name="fmemberlist" id="fmemberlist" action="./takeback_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">

<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="mode" value="I1">


 
<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="반품요청" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="반품확인" onclick="document.pressed=this.value">
	<input type="submit" name="act_button" value="반품진행중" onclick="document.pressed=this.value">
	<input type="submit" name="act_button" value="반품수령" onclick="document.pressed=this.value">
	<input type="submit" name="act_button" value="반품완료" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="반품철회" onclick="document.pressed=this.value">
</div>
 

<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr style="height:45px;">
	
		<th scope="col" rowspan="2" id="mb_list_chk">
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
	
		 <th scope="col">사이트구분</th>
		 <th scope="col">PG번호</th>
		 <th scope="col">공급업체명</th>
		 <th scope="col">주문번호</th>
		 <th scope="col">주문상태</th>
		 <th scope="col">결제수단</th>
		 <th scope="col">상품명</th>
		 <th scope="col">규격</th>
		 <th scope="col">단위</th>
		 <th scope="col">수량</th>
		 <th scope="col">단가</th>
		 <th scope="col">금액</th>
		 <th scope="col">반품신청수량</th>
		 <th scope="col">신청금액</th>
		 <th scope="col">상태</th>
		 <th scope="col">병원명</th>
		 <th scope="col">주문자명</th>
		 <th scope="col">사업자번호</th>
		 <th scope="col">등록일</th>
		 <th scope="col">상태변경일</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		$site_class = substr($row['od_id'],0,2);   
    ?>

    <tr class="<?php echo $bg; ?>" style="height:45px;">
		
        <td headers="mb_list_chk" class="td_chk">
            <input type="hidden" name="wr_id[<?php echo $i ?>]" value="<?php echo $row['wr_id'] ?>" id="wr_id_<?php echo $i ?>"> 
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
         
		<td style="text-align:center;">
		<?
			if($site_class == "20"){ echo "산부인과몰";$site_fix = "shop";}else if($site_class == "99"){ echo "메디몰";$site_fix = "shop"; }else{ echo "피부비만";$site_fix = "shop_skin";}
			$row2 = sql_fetch("select * from shop.g5_shop_order where od_id = '{$row['od_id']}' ");
			$row3 = sql_fetch("select * from {$site_fix}.g5_shop_cart where ct_id = '{$row['ct_id']}' ");
			$row4 = sql_fetch("select * from {$site_fix}.g5_shop_item where it_id = '$row3[it_id]' ");
			$row5 = sql_fetch("select * from shop.g5_shop_item where it_id = '$row4[it_8]' ");
			$mem2 = sql_fetch("select * from {$site_fix}.g5_member where mb_id = '$row[mb_id]' ");
            /** 200713 - edited by jinam23
             *  shop 디비에 없을 경우 portal 디비에서 정보 가져오도록
             */
            if( !$mem2['mb_11'] ) {
                $mem2 = sql_fetch("select * from portal.g5_member where mb_id = '$row[mb_id]' ");
            }

			$mem = get_member($row[com_id]);
			
		?>
		</td>
		<td style="text-align:center;"><?=$row2['pod_id']?></td>
		<td style="text-align:center;"><?=$mem['mb_nick']?></td>
		<td style="text-align:center;"><a href="#" class="orderitem"><?=$row['od_id']?></a></td>
		<td style="text-align:center;"><?=$row3['ct_status']?></td>
		<td style="text-align:center;"><?=$row2['od_settle_case']?></td>
		<td style="text-align:center;"><?=$row3['it_name']?></td>
		<td style="text-align:center;"><?=$row5['it_5']?></td>
		<td style="text-align:center;"><?=$row5['it_6']?></td>
		<td style="text-align:center;"><?=$row3['ct_qty']?></td>
		<td style="text-align:center;"><?=number_format($row3['ct_price'])?></td>
		<td style="text-align:center;"><?=number_format($row3['ct_price']*$row3['ct_qty'])?></td>
		<td style="text-align:center;"><?=$row['ct_qty']?></td>
		<td style="text-align:center;"><?=number_format($row3['ct_price']*$row['ct_qty'])?></td>
		<td style="text-align:center;"><?=$row['status']?></td>
		<td style="text-align:center;"><?=$mem2['mb_11']?></td>
		<td style="text-align:center;"><?=$mem2['mb_name']?></td>
		<td style="text-align:center;"><?=$mem2['mb_15']?></td>
		<td style="text-align:center;"><?=$row['wr_datetime']?></td>
		<td style="text-align:center;"><?=$row['wr_update_datetime']?></td>
		
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>



</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;mb_where='.$mb_where.'&amp;mb_level='.$mb_level.'&amp;mb_9='.$mb_9.'&amp;mb_21='.$mb_21.'&amp;mb_s_date='.$mb_s_date.'&amp;mb_e_date='.$mb_e_date.'&amp;status='.$status.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "반품요청") {
		f.mode.value = "I1";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {
			
            return false;
        }
    }
	 if(document.pressed == "반품확인") {
		 f.mode.value = "I2";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {
			
            return false;
        }
    }
	 if(document.pressed == "반품진행중") {
		 f.mode.value = "I3";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {
			
            return false;
        }
    }
	 if(document.pressed == "반품수령") {
		 f.mode.value = "I4";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {
			
            return false;
        }
    }
	 if(document.pressed == "반품완료") {
		 f.mode.value = "I5";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {
			
            return false;
        }
    }

    if(document.pressed == "반품철회") {
        f.mode.value = "I6";
        if(!confirm("선택한 자료를 정말 "+document.pressed+"처리 하시겠습니까?")) {

            return false;
        }
    }

    return true;
}
</script>
<script>
$(function() {
    $("#mb_s_date, #mb_e_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        maxDate: "+0d"
    });

	// 주문상품보기
    $(".orderitem").on("click", function() {
        var $this = $(this);
        var od_id = $this.text().replace(/[^0-9]/g, "");

        if($this.next("#orderitemlist").size())
            return false;

        $("#orderitemlist").remove();

        $.post(
            "./ajax.takeback_memo.php",
            { od_id: od_id },
            function(data) {
                $this.after("<div id=\"orderitemlist\"><div class=\"itemlist\"></div></div>");
                $("#orderitemlist .itemlist")
                    .html(data)
                    .append("<div id=\"orderitemlist_close\"><button type=\"button\" id=\"orderitemlist-x\" class=\"btn_frmline\">닫기</button></div>");
            }
        );

        return false;
    });
	
	 // 상품리스트 닫기
    $(".orderitemlist-x").on("click", function() {
        $("#orderitemlist").remove();
    });

    $("body").on("click", function() {
        $("#orderitemlist").remove();
    });

});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("mb_s_date").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("mb_e_date").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("mb_s_date").value = "";
        document.getElementById("mb_e_date").value = "";
    }
}
function down_excel(src){
	document.fsearch.action = src;
	document.fsearch.submit();
	document.fsearch.action = "./member_list.php";
}
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php'); 
?>
