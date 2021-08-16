<?php
$sub_menu = "400810";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from g5_coupon ";
$sql_search = " where 1 = 1  ";

if($ss_bt_id){ 
	$sql_search .=  " and ( ca_name = '{$ss_bt_id}' ) ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "wr_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

 $sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '쿠폰관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 15;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    등록수 : <?php echo number_format($total_count) ?>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject", true); ?>>쿠폰명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>

<div class="btn_add01 btn_add">
    <a id="bo_add" href="./coupon_view.php">등록하기</a>
</div>

<form name="fboardlist" id="fboardlist" action="./coupon_update.php" onsubmit="return fboardlist_submit(this);" method="post">
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
        <th scope="col" width="2%">
            <label for="chkall" class="sound_only">게시판 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" width="3%">번호</th>
		<th scope="col">쿠폰명</th>
		<th scope="col" width="5%">쿠폰종류</th>
		<th scope="col" width="3%">발급대상</th>
		<th scope="col" width="5%">시작일시</th>
		<th scope="col" width="5%">종료일시</th>
		<th scope="col" width="8%">등록일시</th>
		<th scope="col" width="5%">쿠폰금액</th>
		<th scope="col" width="5%">주문최소금</th>
		<th scope="col" width="5%">(사용 / 발급)</th>
		<th scope="col" width="5%">쿠폰목록보기</th>
    </tr>
    </thead>
    <tbody>
	<?php
		 for ($i=0; $row=sql_fetch_array($result); $i++) {
			  $bg = 'bg'.($i%2);
			  $num = $total_count - (($page * $rows) - $rows ) - $i;

			  $totcnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}'");
			  $usecnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}' and wr_state = '1' ");
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="td_mngsmall"><input type="checkbox" name="chk[]" value="<?=$row[wr_id]?>" id="chk_<?php echo $i ?>"></td>
		<td class="td_mngsmall"><?=$num?></td>
		<td><a href="./coupon_view.php?wr_id=<?=$row[wr_id]?>"><?=$row[wr_subject]?></a></td>
		<td style="width:150px;text-align:center;"><a href="./coupon_view.php?wr_id=<?=$row[wr_id]?>"><?=($row[wr_method]==0)?"개별상품할인":"주문금액할인"?></a></td>
		<td style="width:150px;text-align:center;"><a href="./coupon_view.php?wr_id=<?=$row[wr_id]?>"><?=$row[wr_target]?></a></td>
		<td style="width:150px;text-align:center;"><a href="./coupon_view.php?wr_id=<?=$row[wr_id]?>"><?=$row[wr_sdate]?></a></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_edate]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_datetime]?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_price])?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_min])?></td>
		<td style="width:140px;text-align:center;"><?=$usecnt[cnt]?> / <?=$totcnt[cnt]?></td>
		<td style="width:140px;text-align:center;"><a href="./coupon_list2.php?wr_id=<?=$row[wr_id]?>" class="btn btn-dark btn-xs">보러가기</a></td>
	</tr>
	<?php
		}
	?>
	</tbody>
	</table>
</div>
<div class="btn_list01 btn_list">
   <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	
    <?php } ?>
</div>
</form >
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
<script>
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}


</script>
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
