<?php
$sub_menu = '500100';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '상품판매순위';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (!$to_date) $to_date = date("Ymd", time());

if ($sort1 == "") $sort1 = "ct_status_sum";
if ($sort2 == "" || $sort2 != "asc") $sort2 = "desc";

$doc = strip_tags($doc);
$sort1 = strip_tags($sort1);

$sql  = " select a.it_id,
                 b.*,
                 SUM(IF(ct_status = '쇼핑',ct_qty, 0)) as ct_status_1,
                 SUM(IF(ct_status = '주문',ct_qty, 0)) as ct_status_2,
                 SUM(IF(ct_status = '입금',ct_qty, 0)) as ct_status_3,
                 SUM(IF(ct_status = '준비',ct_qty, 0)) as ct_status_4,
                 SUM(IF(ct_status = '배송',ct_qty, 0)) as ct_status_5,
                 SUM(IF(ct_status = '완료',ct_qty, 0)) as ct_status_6,
                 SUM(IF(ct_status = '취소',ct_qty, 0)) as ct_status_7,
                 SUM(IF(ct_status = '반품',ct_qty, 0)) as ct_status_8,
                 SUM(IF(ct_status = '품절',ct_qty, 0)) as ct_status_9,
                 SUM(ct_qty) as ct_status_sum
            from {$g5['g5_shop_cart_table']} a, {$g5['g5_shop_item_table']} b ";
$sql .= " where a.it_id = b.it_id ";
if ($fr_date && $to_date)
{
    $fr = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
    $to = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);
    $sql .= " and ct_time between '$fr 00:00:00' and '$to 23:59:59' ";
}
if ($sel_ca_id1 != "") $_search_no = 1;
if ($sel_ca_id2 != "") $_search_no = 2;
if ($sel_ca_id3 != "") $_search_no = 3;

switch($_search_no) {
    case 1 :
        $sql_search .= " and (ca_id like '$sel_ca_id1%') ";
        break;

    case 2 :
        $sql_search .= " and (ca_id like '$sel_ca_id2%') ";
        break;

    case 3 :
        $sql_search .= " and (ca_id like '$sel_ca_id3%') ";
        break;
}
$sql .= " group by a.it_id
          order by $sort1 $sort2 ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$rank = ($page - 1) * $rows;

$sql = $sql . " limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr = 'page='.$page.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2;
$qstr1 = $qstr.'&amp;fr_date='.$fr_date.'&amp;to_date='.$to_date.'&amp;sel_ca_id='.$sel_ca_id;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<script>
    function ajax_cate(val, dep, sort, ck) {
        document.getElementById("sca").value = val;
        document.getElementById("sca_ck").value = ck;

        var g5_url = "<?php echo G5_URL ?>";
        var save_result = "";
        $.ajax({
            type: "POST",
            data: {
                "site_fix": "<?=$site_fix?>",
                "val": val,
                "dep": dep,
                "sort": sort,
                "sca_ck": ck
            },

            url: g5_url + "/adm/shop_admin/new.ajax.cate.php",
            cache: false,
            async: false,
            success: function (data) {
                save_result = data;
            }
        });

        if (save_result) {
            $("#" + dep).html(save_result);
        }

    }
</script>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    등록상품 <?php echo $total_count; ?>건
</div>

<form name="flist" class="local_sch01 local_sch">
<input type="hidden" name="doc" value="<?php echo $doc; ?>">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="sca" id="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sca_ck" id="sca_ck" value="<?php echo $sca_ck; ?>">

<label for="sel_ca_id" class="sound_only">검색대상</label>
    <select name="sel_ca_id1" id="sel_ca_id1" onchange="ajax_cate(this.value,'sel_ca_id2','2','sel_ca_id2');">
        <option value="">1차분류</option>
        <?php
        $sql1 = " select ca_id, ca_name from {$site_fix}.{$g5['g5_shop_category_table']} where LENGTH(ca_id) = '2'  order by ca_id , ca_order ";
        $result1 = sql_query($sql1);
        for ($i = 0; $row1 = sql_fetch_array($result1); $i++) {
            echo '<option value="' . $row1['ca_id'] . '" ' . get_selected($sel_ca_id1, $row1['ca_id']) . '>' . $nbsp . $row1['ca_name'] . '</option>' . PHP_EOL;
        }
        ?>
    </select>

    <select name="sel_ca_id2" id="sel_ca_id2" onchange="ajax_cate(this.value,'sel_ca_id3','3','sel_ca_id3');">

    </select>
    <select name="sel_ca_id3" id="sel_ca_id3" onchange="ajax_cate(this.value,,'3');">

    </select>

기간설정
<label for="fr_date" class="sound_only">시작일</label>
<input type="text" name="fr_date" value="<?php echo $fr_date; ?>" id="fr_date" required class="required frm_input" size="8" maxlength="8"> 에서
<label for="to_date" class="sound_only">종료일</label>
<input type="text" name="to_date" value="<?php echo $to_date; ?>" id="to_date" required class="required frm_input" size="8" maxlength="8"> 까지
<input type="submit" value="검색" class="btn_submit">

</form>

<div class="local_desc01 local_desc">
    <p>판매량을 합산하여 상품판매순위를 집계합니다.</p>
</div>

<div class="btn_add01 btn_add">
    <a href="./itemlist.php" class="btn_add01 btn_add_optional">상품등록</a>
    <a href="./itemstocklist.php" class="btn_add01 btn_add_optional">상품재고관리</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" width="3%">순위</th>
        <th scope="col">상품명</th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_1",1)."&amp;$qstr1"; ?>">쇼핑</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_2",1)."&amp;$qstr1"; ?>">주문</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_3",1)."&amp;$qstr1"; ?>">입금</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_4",1)."&amp;$qstr1"; ?>">준비</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_5",1)."&amp;$qstr1"; ?>">배송</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_6",1)."&amp;$qstr1"; ?>">완료</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_7",1)."&amp;$qstr1"; ?>">취소</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_8",1)."&amp;$qstr1"; ?>">반품</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_9",1)."&amp;$qstr1"; ?>">품절</a></th>
        <th scope="col" width="3%"><a href="<?php echo title_sort("ct_status_sum",1)."&amp;$qstr1"; ?>">합계</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $href = G5_SHOP_URL."/item.php?it_id={$row['it_id']}";

        $num = $rank + $i + 1;

        $bg = 'bg'.($i%2);
        ?>
        <tr class="<?php echo $bg; ?>">
            <td class="td_num"><?php echo $num; ?></td>
            <td><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo cut_str($row['it_name'],30); ?></a></td>
            <td class="td_num"><?php echo $row['ct_status_1']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_2']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_3']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_4']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_5']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_6']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_7']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_8']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_9']; ?></td>
            <td class="td_num"><?php echo $row['ct_status_sum']; ?></td>
        </tr>
        <?php
    }

    if ($i == 0) {
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr1&amp;page="); ?>

<script>
$(function() {
    $("#fr_date, #to_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yymmdd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        maxDate: "+0d"
    });
});

<? if($sel_ca_id1){ ?>
setTimeout(function() {
    ajax_cate("<?=$sel_ca_id1?>", "sel_ca_id2", "2", "<?=$sel_ca_id2?>");
}, 1000);
<? } ?>
<? if($sel_ca_id2){ ?>
setTimeout(function() {
    ajax_cate("<?=$sel_ca_id2?>", "sel_ca_id3", "3", "<?=$sel_ca_id3?>");
}, 2000);
<? } ?>
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
