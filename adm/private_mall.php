<?php
$sub_menu = "300001";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if($stx)
    $qstr = "sfl={$sfl}&amp;stx={$stx}";

$sql_common = " from shop_private_mall ";
if($stx)
    $sql_search = " where mall_name='{$stx}' ";
$sql_order = " order by idx desc ";

$sql = " select idx {$sql_common} {$sql_search}  ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select idx,mall_name,mall_word,mall_ca_id,mall_url,reg_dt {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '전용관페이지 관리';
include_once('./admin.head.php');

$colspan = 8;
?>


<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    건수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
    <div class="sch_last">
        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl">
            <option value="">제약사명</option>
        </select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="frm_input">
        <input type="submit" value="검색" class="btn_submit">
    </div>
</form>

<div class="btn_add01 btn_add">
    <? if($is_admin) { ?>
        <a href="./private_mall_write.php">등록하기</a>
        <a href="./private_mall_excel.php?fr_date=<?=$fr_date?>&to_date=<?=$to_date?>">엑셀다운로드</a>
    <? } ?>
</div>

<form name="delfrm" id="delfrm" method="POST" action="./private_mall_process.php">
    <input type="hidden" name="mode" id="mode" value="del">
    <input type="hidden" name="idx" id="idx" value="">
</form>

<form name="fboardlist" id="fboardlist" action="./private_mall_process.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <input type="hidden" name="mode" value="multidel">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <th scope="col" width="2%">
                    <label for="chkall" class="sound_only">게시판 전체</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                </th>
                <th scope="col" style="width: 3%;">번호</th>
                <th scope="col" style="width: 10%;">제약사명</th>
                <th scope="col">제약사 슬로건</th>
                <th scope="col" style="width: 15%;">분류값</th>
                <th scope="col" style="width: 20%;">페이지 URL</th>
                <th scope="col" style="width: 8%;">등록일</th>
                <th scope="col" style="width: 5%;">관리</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {

                $num = $total_count - (($page * $rows) - $rows ) - $i;

                $ca_sql = "select ca_name from {$g5['g5_shop_category_table']} where ca_id='{$row['mall_ca_id']}'";
                $cate = sql_fetch($ca_sql);

                $mall_url_view = $row['mall_url'] == "" ? "/shop/pharmacist.php?idx=".$row['idx'] : $row['mall_url'] ;
                ?>

                <tr>
                    <td style='width:30px;text-align:center;' class='td_mngsmall'><input type='checkbox' name='chk[]' value='<?=$row['idx']?>' id='chk_<?=$i?>'></td>
                    <td class="td_num"><?php echo $num ?></td>
                    <td><?php echo $row['mall_name'] ?></td>
                    <td><?php echo $row['mall_word'] ?></td>
                    <td><?php echo $cate['ca_name']; ?></td>
                    <td><?php echo $mall_url_view ?></td>
                    <td><?php echo $row['reg_dt'] ?></td>
                    <td>
                        <a href="./private_mall_write.php?idx=<?php echo $row['idx']; ?>&amp;<?php echo $qstr; ?>" class="btn btn-primary btn-xs">수정</a><br>
                        <a onclick="removeCheck('<?=$row['idx']?>');" class="btn btn-danger btn-xs">삭제</a>
                    </td>
                </tr>

                <?php
            }

            if ($total_count == 0)
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

<?php
echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");
?>
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

    function removeCheck(idx) {
        if (confirm("정말 삭제하시겠습니까??") == true){    //확인
            $('#idx').val(idx);
            $('#delfrm').submit();
        }else{   //취소
            return false;

        }

    }
</script>
<?php
include_once('./admin.tail.php');
?>
