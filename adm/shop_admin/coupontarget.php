<?php
$sub_menu = '400800';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$sch_target = substr($_GET['sch_target'], 0, 1);
$sch_word   = clean_xss_tags($_GET['sch_word']);

if($_GET['sch_target'] == 1) {
    $html_title = '분류';
    $t_name = '분류명';
    $t_id = '분류코드';
    $t_desc1 = '분류를';
    $t_desc2 = '분류가';
} else {
    $html_title = '상품';
    $t_name = '상품명';
    $t_id = '상품코드';
    $t_desc1 = '상품을';
    $t_desc2 = '상품이';
}

$g5['title'] = $html_title.'검색';
include_once(G5_PATH.'/head.sub.php');

if($sch_target == 1) {
    $sql_common = " from {$g5['g5_shop_category_table']} ";
    $sql_where = " where ca_use = '1' and ca_nocoupon = '0' ";
    if($sch_word)
        $sql_where .= " and ca_name like '%$sch_word%' ";
    $sql_select = " select ca_id as t_id, ca_name as t_name ";
    $sql_order = " order by ca_order, ca_name ";
} else {
    $sql_common = " from {$g5['g5_shop_item_table']} ";
    $sql_where = " where it_use = '1' and it_nocoupon = '0' and it_8 != '' ";
    if($sch_word){
//        $sql_where .= " and it_name like '%$sch_word%' ";
	      $sql_where .= " and ( it_name like '%$sch_word%' or it_id like '%$sch_word%')";
	}
	if($m_class){
		 $sql_where .= " and it_10 = '$m_class' ";
	}
    $sql_select = " select it_id as t_id, it_name as t_name , it_8 ,it_9 ,it_10 ";
    $sql_order = " order by it_order, it_name ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common . $sql_where;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = $sql_select . $sql_common . $sql_where . $sql_order . " limit $from_record, $rows ";
$result = sql_query($sql);

$qstr1 = 'sch_target='.$sch_target.'&amp;sch_word='.urlencode($sch_word);
?>

<div id="sch_target_frm" class="new_win scp_new_win">
    <h1>쿠폰 적용 <?php echo $html_title; ?>선택</h1>

    <div class="local_desc01 local_desc">
        <p>
            쿠폰을 적용할 <?php echo $t_desc1; ?> 선택하세요.<br>
            <?php echo $t_desc2; ?> 많을 경우에는 검색 기능을 이용하세요.
        </p>
    </div>

    <form name="ftarget" method="get">
    <input type="hidden" name="sch_target" value="<?php echo $_GET['sch_target']; ?>">

    <div id="scp_list_find">
		<label for="m_class">업체명</label>
		<select name="m_class" id="m_class" class="frm_input">
			<option value="" <?php echo get_selected($m_class, ''); ?>>전체</option>
			<option value="admin" <?php echo get_selected($m_class, 'admin'); ?>>ADMIN</option>
		<?
			$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4'";
			$mres = sql_query($msql);
			while($mrow = sql_fetch_array($mres)){

		?>
			 <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($m_class, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
		<? } ?> 
		</select><br><br>

        <label for="sch_word"><?php echo $t_name; ?> 또는 상품코드</label>
        <input type="text" name="sch_word" id="sch_word" value="<?php echo get_text($sch_word); ?>" class="frm_input"  size="20">
        <input type="submit" value="검색" class="btn_frmline">
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>검색결과</caption>
        <thead>
        <tr>
			<th scope="col">마스터코드</th>
			<th scope="col">업체코드</th>
			<th scope="col">업체명</th>
            <th scope="col"><?php echo $t_name; ?></th>
            <th scope="col"><?php echo $t_id; ?></th>
            <th scope="col">선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
			
			$mem = get_member($row['it_10']);

        ?>
        <tr>
			<td><?php echo $row['it_8']; ?></td>
			<td><?php echo $row['it_9']; ?></td>
			<td><?php echo $mem['mb_nick']; ?></td>
            <td><?php echo $row['t_name']; ?></td>
            <td class="scp_target_code"><?php echo $row['t_id']; ?></td>
            <td class="scp_target_select"><button type="button" class="btn_frmline" onclick="sel_target_id('<?php echo $row['t_id']; ?>');">선택</button>
        </tr>
        <?php
        }

        if($i ==0)
            echo '<tr><td colspan="3" class="empty_table">검색된 자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
    </form>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr1.'&amp;page='); ?>

    <div class="btn_confirm01 btn_confirm">
        <button type="button" onclick="window.close();">닫기</button>
    </div>
</div>

<script>
function sel_target_id(id)
{
    var f = window.opener.document.fcouponform;
    f.cp_target.value = id;

    window.close();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>