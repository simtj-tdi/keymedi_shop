
<?php
$sub_menu = "300900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['event_table']} a ";
$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    $sql_search .= " ($sfl like '%$stx%') ";
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.idx ";
    $sod = "asc";
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

$g5['title'] = '이벤트상품관리';
include_once('./admin.head.php');

$colspan = 15;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    등록된 이벤트상품수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="a.e_title"<?php echo get_selected($_GET['sfl'], "a.e_title"); ?>>제목</option>
        <option value="a.e_name"<?php echo get_selected($_GET['sfl'], "a.e_name"); ?>>이벤트명</option>
        <option value="a.e_idx"<?php echo get_selected($_GET['sfl'], "a.e_idx"); ?>>이벤트번호</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" value="검색" class="btn_submit">

</form>

<?php if ($is_admin == 'super') { ?>
    <div class="btn_add01 btn_add">
        <a href="./event_form.php?type=write" id="bo_add">이벤트상품 추가</a>
    </div>
<?php } ?>

<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return del_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <th scope="col" width="2%">
                    <label for="chkall" class="sound_only">전체선택</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                </th>
                <th scope="col" width="5%">No</th>
                <th scope="col" width="5%">이벤트번호</th>
                <th scope="col" width="25%">제목</th>
                <th scope="col" width="25%">이벤트명</th>
                <th scope="col">상단배너이미지</th>
                <th scope="col" width="3%">단수</th>
                <th scope="col" width="5%">노출유무</th>
                <th scope="col" width="8%">등록일</th>
                <th scope="col" width="5%">관리</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $img = "";

            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $_update = '<a href="./event_form.php?type=modify&idx='.$row[idx].'&amp;'.$qstr.'" class="btn btn-primary btn-xs">수정</a>';
                $_del = '<a href="./event_process.php?type=del&idx='.$row[idx].'&amp;'.$qstr.'" class="btn btn-danger btn-xs" target="win_board_copy">삭제</a>';
                $bg = 'bg'.($i%2);

                $num = $total_count - (($page * $rows) - $rows ) - $i;
                $step = count(explode(",",$row['e_cat_title']));
                $view = $row['e_view'] == 1 ? "<font color='blue'><b>노출</b></font>" : "<font color='red'><b>비노출</b></font>" ;

                $img = resize_image("/data/was/shop/data/event_banner/".$row['e_file'], 100, 100); // 파일경로, 폭, 높이를 입력하세요

                ob_start();
                switch($img[1]){
                    case "png":
                        imagepng($img[0]);
                        break;
                    case "jpeg":
                    case "jpg":
                        imagejpeg($img[0]);
                        break;
                    case "gif":
                        imagegif($img[0]);
                        break;
                    default:
                        imagejpeg($img[0]);
                        break;
                }
                $output = base64_encode(ob_get_contents());
                ob_end_clean();
                ?>

                <tr class="<?php echo $bg; ?>">
                    <td class="td_chk">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['idx']) ?></label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                    </td>
                    <td><?=$num?></td>
                    <td><?=$row['e_idx']?></td>
                    <td><?=$row['e_title']?></td>
                    <td><?=$row['e_name']?></td>
                    <td><img src="data:image/<?=$img[1]?>;base64,<?=$output?>"/></td>
                    <td><?=$step-1?></td>
                    <td><?=$view?></td>
                    <td><?=$row['e_date']?></td>
                    <td class="td_mngsmall">
                        <?php echo $_update ?>
                        <?php echo $_del ?>
                    </td>
                </tr>
                <?php
            }
            if ($i == 0)
                echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>

    <div class="btn_list01 btn_list">
        <?php if ($is_admin == 'super') { ?>
            <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
        <?php } ?>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
    function del_submit(f)
    {
        if (!is_checked("chk[]")) {
            alert("삭제 하실 항목을 하나 이상 선택하세요.");
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
include_once('./admin.tail.php');
?>
