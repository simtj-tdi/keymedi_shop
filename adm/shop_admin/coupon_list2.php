<?php
$sub_menu = "400810";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from g5_coupon_list ";
$sql_search = " where 1 = 1  and wr_paren_id = '{$wr_id}' ";

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

$g5['title'] = '발급쿠폰관리';
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
	<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id", true); ?>>사용자아이디</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>

 <div class="btn_add01 btn_add">
    <a id="bo_add" href="./coupon_list2_excel.php?wr_id=<?=$wr_id?>">엑셀다운</a>
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
 
        <th scope="col" width="3%">번호</th>
		<th scope="col">쿠폰명</th>
		<th scope="col" width="10%">쿠폰코드</th>
		<th scope="col" width="5%">쿠폰종류</th>
		<th scope="col" width="3%">발급대상</th>
		<th scope="col" width="5%">시작일시</th>
		<th scope="col" width="5%">종료일시</th>
		<th scope="col" width="8%">등록일시</th>
		<th scope="col" width="5%">쿠폰금액</th>
		<th scope="col" width="5%">주문최소금</th>
		<th scope="col" width="7%">등록한 (아이디/이름)</th>
		<th scope="col" width="4%">등록여부</th>
    </tr>
    </thead>
    <tbody>
	<?php
		 for ($i=0; $row=sql_fetch_array($result); $i++) {
			  $bg = 'bg'.($i%2);
			  $num = $total_count - (($page * $rows) - $rows ) - $i;

			  $totcnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}'");
			  $usecnt = sql_fetch("select count(*) as cnt from g5_coupon_list where wr_paren_id = '{$row[wr_id]}' and wr_state = '1' ");

			  $tomem = get_member($row[mb_id]);
	?>
	<tr class="<?php echo $bg; ?>"> 
		<td class="td_mngsmall"><?=$num?></td>
		<td><?=$row[wr_subject]?></td>
		<td><?=$row[wr_code]?></td>
		<td style="width:100px;text-align:center;"><?=($row[wr_method]==0)?"개별상품할인":"주문금액할인"?></td>
		<td style="width:100px;text-align:center;"><?=$row[wr_target]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_sdate]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_edate]?></td>
		<td style="width:150px;text-align:center;"><?=$row[wr_datetime]?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_price])?></td>
		<td style="width:150px;text-align:center;"><?=number_format($row[wr_min])?></td>
		<td style="width:140px;text-align:center;">(<?=$row[mb_id]?> / <?=$tomem[mb_name]?>)</td>
		<td style="width:140px;text-align:center;font-weight: bold;"><?=($row[wr_state]=="0")?"<font color='red'>미등록</font>":"<font color='blue'>등록</font>"?></td>
	</tr>
	<?php
		}
	?>
	</tbody>
	</table>
</div>
 
</form >
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;wr_id='.$wr_id.'&amp;page='); ?>
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
