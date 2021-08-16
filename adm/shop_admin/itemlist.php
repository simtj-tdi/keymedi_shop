<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '마스터상품관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
//if ($is_admin != 'super')
//    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
//$sql .= " order by ca_order, ca_id ";
$sql .= " order by ca_id , ca_order";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].' ('.$row['ca_id'].')</option>'.PHP_EOL;
}

$where = " and ";
$sql_search = "";
if ($stx != "" || $save_stx!= "") {

    $search_wd = $stx != "" ? $stx : $save_stx ;

    if($sfl == "it_id") {
        $sql_search .= " $where $sfl = '$search_wd' ";
        $where = " and ";
    }else if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$search_wd%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($stx2 != "") {
    if($sfl2 == "it_id") {
        $sql_search .= " $where $sfl2 = '$stx2' ";
        $where = " and ";
    }else if ($sfl2 != "") {
        $sql_search .= " $where $sfl2 like '%$stx2%' ";
        $where = " and ";
    }
    if ($save_stx != $stx2)
        $page = 1;

    $re_display = "block";
}else{
    $re_display = "none";
}



if ($sca != "") {
    $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%' or a.ca_id3 like '$sca%') ";
}


if($mb_s_date){
    $sql_search .= " and  '{$mb_s_date}' <= left(it_time,10)   ";
}
if($mb_e_date){
    $sql_search .= " and  left(it_time,10) <= '{$mb_e_date}' ";
}


if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$g5['g5_shop_item_table']} a ,
                     {$g5['g5_shop_category_table']} b
               where (a.ca_id = b.ca_id";
/*
if($s_mas =="my"){
	if ($is_admin != 'super')
		$sql_common .= " and a.it_10 = '{$member['mb_id']}'";
}else{
	$sql_common .= " and a.it_8 =''";
}
*/
$sql_common .= " and a.it_8 =''";

if($m_class){
    $sql_common .= " and a.it_10 = '{$m_class}'";
}

$sql_common .= ") ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    //  $sst  = "it_id";
    $sst  = "it_time";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select *
           $sql_common
           $sql_order
           limit $from_record, $rows ";
$result = sql_query($sql);

//############ 이미지 존재여부 체크 total_page ############

$img_total = 0;
$imgRow = array();
ini_set('memory_limit', '1024M');

$sql  = " select * ".$sql_common."$sql_order";
$result = sql_query($sql);

if ($img_chk == "Y") {
    while ($img_row = sql_fetch_array($result)) {
        if ( get_it_imageChk($img_row['it_id'], 50, 50) == false ) {
            $img_total++;
            $imgRow[] = $img_row;
        }
    }
} else {
    while ($img_row = sql_fetch_array($result)) {
        $img_total++;
        $imgRow[] = $img_row;
    }
}

$total_count = $img_total;
$total_page  = ceil($img_total / $rows);  // 전체 페이지 계산
$from = ($page-1) * $rows;
$to = ($page * $rows) - 1;
$arrRow = array_slice($imgRow, $from, $rows, true);

//############ 이미지 존재여부 체크 total_page ############

$_send_sql = " select *
           $sql_common
           $sql_order";

$secret_key = "123456789";
$secret_iv = "#@$%^&*()_+=-";
$encrypted = Encrypt($_send_sql, $secret_key, $secret_iv);

//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;
//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;m_class='.$m_class.'&amp;page='.$page.'&amp;save_stx='.$stx;
//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;sca1='.$sca1.'&amp;sca2='.$sca2.'&amp;sca3='.$sca3.'&amp;m_class='.$m_class.'&amp;page='.$page.'&amp;save_stx='.$search_wd.'&amp;sfl='.$sfl.'&amp;sfl2='.$sfl2.'&amp;stx2='.$stx2;
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;sca1='.$sca1.'&amp;sca2='.$sca2.'&amp;sca3='.$sca3.'&amp;m_class='.$m_class.'&amp;page='.$page.'&amp;save_stx='.$search_wd.'&amp;sfl='.$sfl.'&amp;sfl2='.$sfl2.'&amp;stx2='.$stx2;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    등록된 상품 <?php echo $total_count; ?>건
</div>

<form name="flist" class="local_sch02 local_sch">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
    <p>
        <label for="sca">분류선택 : </label>
        <input type="hidden" name="sca" id="sca" value="<?=$sca?>">

        <select name="sca1" id="sca1" onchange="ajax_cate(this.value,2,'');">
            <option value="">1차분류</option>
            <?php
            $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where LENGTH(ca_id) = '2'  order by ca_id , ca_order ";
            $result1 = sql_query($sql1);
            for ($i=0; $row1=sql_fetch_array($result1); $i++) {
                echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca1, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
            }
            ?>
        </select>
        <select name="sca2" id="sca2" onchange="ajax_cate(this.value,3,'');">

        </select>
        <select name="sca3" id="sca3" onchange="ajax_cate(this.value,4,'');">

        </select>
    </p>
    <script>
        function ajax_cate(val,dep,dep2){
            document.getElementById("sca").value = val;
            var g5_url       = "<?php echo G5_URL ?>";
            var save_result = "";
            if(dep < 4){
                $.ajax({
                    type: "POST",
                    data: {
                        "site_fix":"<?=$site_fix?>",
                        "val":val,
                        "dep":dep,
                        "ck":dep2
                    },
                    url: g5_url+"/adm/shop_admin/ajax.cate.php",
                    cache: false,
                    async: false,
                    success: function(data) {
                        save_result = data;
                    }
                });

                if(save_result) {
                    $("#sca"+dep).html(save_result);
                }
            }
        }

    </script>
    <p>
        <!-- mb_datetime  -->등록기간 : <input type="text" name="mb_s_date" id="mb_s_date" value="<?=$mb_s_date?>" class="frm_input"/> ~ <input type="text" name="mb_e_date" id="mb_e_date" value="<?=$mb_e_date?>" class="frm_input"/>

        <button type="button" onclick="javascript:set_date('오늘');">오늘</button>
        <button type="button" onclick="javascript:set_date('어제');">어제</button>
        <button type="button" onclick="javascript:set_date('이번주');">이번주</button>
        <button type="button" onclick="javascript:set_date('이번달');">이번달</button>
        <button type="button" onclick="javascript:set_date('지난주');">지난주</button>
        <button type="button" onclick="javascript:set_date('지난달');">지난달</button>
        <button type="button" onclick="javascript:set_date('전체');">전체</button>

    </p>
    <p>
        <!-- 상품유형 :
<select name="s_mas" id="s_mas">
    <option value="master" <?php echo get_selected($s_mas, 'master'); ?>>마스터상품</option>
    <option value="my" <?php echo get_selected($s_mas, 'my'); ?>>내상품</option>
</select> -->
        업체명 :
        <select name="m_class" id="m_class">
            <option value="" <?php echo get_selected($m_class, ''); ?>>전체</option>
            <option value="admin" <?php echo get_selected($m_class, 'admin'); ?>>ADMIN</option>
            <?
            $msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' order by mb_nick asc";
            $mres = sql_query($msql);
            while($mrow = sql_fetch_array($mres)){

                ?>
                <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($m_class, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
            <? } ?>
        </select>
    </p>

    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
        <option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>마스터코드</option>
        <option value="it_maker" <?php echo get_selected($sfl, 'it_maker'); ?>>제조사</option>
        <option value="it_origin" <?php echo get_selected($sfl, 'it_origin'); ?>>원산지</option>
        <option value="it_sell_email" <?php echo get_selected($sfl, 'it_sell_email'); ?>>판매자 e-mail</option>
    </select>


    <label for="stx" class="sound_only">검색어</label>
    <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">
    <input type="checkbox" name="re_search" id="re_search" onclick="chk_research(this);" <?if($stx2){echo "checked";}?>>&nbsp;결과내 검색

</form>



<!--결과내검색-->
<form id="frm_research"  style="padding-left: 20px; display: <?=$re_display?>;" class="local_sch">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="save_stx" value="<?php echo $save_stx; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="sca1" value="<?php echo $sca1; ?>">
    <input type="hidden" name="sca2" value="<?php echo $sca2; ?>">
    <input type="hidden" name="sca3" value="<?php echo $sca3; ?>">
    <input type="hidden" name="mb_s_date" value="<?php echo $mb_s_date; ?>">
    <input type="hidden" name="mb_e_date" value="<?php echo $mb_e_date; ?>">
    <input type="hidden" name="m_class" value="<?php echo $m_class; ?>">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl2" id="sfl2">
        <option value="it_name" <?php echo get_selected($sfl2, 'it_name'); ?>>상품명</option>
        <option value="it_id" <?php echo get_selected($sfl2, 'it_id'); ?>>마스터코드</option>
        <option value="it_maker" <?php echo get_selected($sfl2, 'it_maker'); ?>>제조사</option>
        <option value="it_origin" <?php echo get_selected($sfl2, 'it_origin'); ?>>원산지</option>
        <option value="it_sell_email" <?php echo get_selected($sfl2, 'it_sell_email'); ?>>판매자 e-mail</option>
    </select>


    <label for="stx" class="sound_only">검색어</label>
    <input type="text" name="stx2" value="<?php echo $stx2; ?>" id="stx2" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">


</form>
<!--결과내검색-->


<div class="btn_add01 btn_add">

    <? if($is_admin) { ?>
        <a href="./itemexcel_down.php?sql=<?=$encrypted?>&img_chk=<?=$img_chk;?>">엑셀다운로드</a>
    <? } ?>
    <a href="./itemform.php">마스터상품등록</a>
    <a href="./itemexcel.php" onclick="return excelform(this.href);" target="_blank">상품일괄등록</a>
</div>

<form name="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod" value="<?php echo $sod; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="m_class" value="<?php echo $m_class; ?>">



    <div class="tbl_head02 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <? if($is_admin) { ?>
                    <th scope="col" rowspan="3" width="2%">
                        <label for="chkall" class="sound_only">상품 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                <? } ?>
                <th scope="col" rowspan="3" width="8%">업체명</th>
                <th scope="col" rowspan="3" width="5%"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>마스터 코드</a></th>
                <? if($is_admin) { ?>
                    <th scope="col" rowspan="3" width="4%">등록업체수</th>
                <? }else{ ?>
                    <th scope="col" rowspan="3" width="4%">등록여부</th>
                <? } ?>
                <th scope="col" rowspan="3" width="2%"><?php echo subject_sort_link('it_use', 'sca='.$sca, 1); ?>승인</a><br><input type="checkbox" name="chkall2" value="1" id="chkall2" onclick="check_all2(this.form)"></th>
                <th scope="col" <? if($is_admin){?>colspan="5"<? }else{?>colspan="2"<? } ?>>분류</th>
                <th scope="col" rowspan="3" width="3%">규격</th>
                <th scope="col" rowspan="3" width="3%">단위</th>
                <th scope="col" rowspan="3" width="8%"><?php echo subject_sort_link('it_maker', 'sca='.$sca); ?>제조사</a></th>

                <th scope="col" rowspan="3" width="3%">최소금액</th>

                <th scope="col" rowspan="3" width="3%">표준코드</th>

                <? if($is_admin){?>
                    <th scope="col" rowspan="3" width="2%"><?php echo subject_sort_link('it_order', 'sca='.$sca); ?>순서</a></th>
                    <th scope="col" rowspan="3" width="2%"><?php echo subject_sort_link('it_soldout', 'sca='.$sca, 1); ?>품절</a></th>
                <? } ?>
                <th scope="col" rowspan="3" width="2%"><?php echo subject_sort_link('it_hit', 'sca='.$sca, 1); ?>조회</a></th>
                <th scope="col" rowspan="3" width="8%">관리</th>
            </tr>
            <tr>
                <th scope="col" rowspan="2" id="th_img">이미지없음 <input type="checkbox" id="img_chk" <?php if ($img_chk == "Y") echo "checked"; ?>></th>
                <th scope="col" rowspan="2" id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품명</a></th>
                <? if($is_admin){?>
                    <th scope="col" id="th_amt"><?php echo subject_sort_link('it_price', 'sca='.$sca); ?>판매가격</a></th>
                    <th scope="col" id="th_camt"><?php echo subject_sort_link('it_cust_price', 'sca='.$sca); ?>시중가격</a></th>
                    <th scope="col" id="th_skin">PC스킨</th>
                <? } ?>
            </tr>
            <? if($is_admin){?>
                <tr>
                    <th scope="col" id="th_pt"><?php echo subject_sort_link('it_point', 'sca='.$sca); ?>포인트</a></th>
                    <th scope="col" id="th_qty"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>재고</a></th>
                    <th scope="col" id="th_mskin">모바일스킨</th>
                </tr>
            <? } ?>
            </thead>
            <tbody>
            <?php
            //for ($i=0; $row=sql_fetch_array($result); $i++)
            $i=0;
            foreach ( $arrRow as $key => $row )
            {
                $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
                $bg = 'bg'.($i%2);

                $it_point = $row['it_point'];
                if($row['it_point_type'])
                    $it_point .= '%';
                ?>
                <tr class="<?php echo $bg; ?>">
                    <? if($is_admin) { ?>
                        <td rowspan="3" class="td_chk">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>

                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">

                        </td>
                    <? } ?>
                    <td rowspan="3" class="td_num">
                        <?

                        $msql = "select mb_nick from {$g5['member_table']} where mb_id = '{$row[it_10]}'";
                        $mem = sql_fetch($msql);
                        echo $mem[mb_nick];

                        ?>
                    </td>
                    <td rowspan="3" style="width:100px;text-align:center;">
                        <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">

                        <span style='color:red;'>
				 <?php echo $row['it_id']; ?>
				</span>

                    </td>
                    <td rowspan="3" class="td_num">
                        <?
                        if($is_admin){

                            $msql = "select count(DISTINCT(it_10)) as cnt from {$g5['g5_shop_item_table']} where it_8 = '$row[it_id]' ";
                            $mem = sql_fetch($msql);
                            echo $mem[cnt];

                        }else{
                            $dis_cnt = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where it_8 = '$row[it_id]' and it_10 = '$member[mb_id]' ");
                            if($dis_cnt[cnt] > 0 ){
                                echo "Y";
                            }else{
                                echo "N";
                            }
                        }
                        ?>
                    </td>
                    <td rowspan="3" class="td_chk">
                        <label for="use_<?php echo $i; ?>" class="sound_only">승인상태</label>
                        <input type="checkbox" class="it_use" name="it_use[<?php echo $i; ?>]" <?php echo ($row['it_use'] ? 'checked' : ''); ?> value="1" id="use_<?php echo $i; ?>">
                    </td>

                    <td <? if($is_admin){?>colspan="5"<? }else{?>colspan="2"<? } ?>>
                        <label for="ca_id_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 기본분류</label>
                        <select name="ca_id[<?php echo $i; ?>]" id="ca_id_<?php echo $i; ?>" <? if(!$is_admin) {?>disabled<? }?> style="width:260px;">
                            <?php echo conv_selected_option($ca_list, $row['ca_id']); ?>
                        </select>
                        <label for="ca_id2_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 2차분류</label>
                        <select name="ca_id2[<?php echo $i; ?>]" id="ca_id2_<?php echo $i; ?>" <? if(!$is_admin) {?>disabled<? }?> style="width:260px;">
                            <?php echo conv_selected_option($ca_list, $row['ca_id2']); ?>
                        </select>
                        <label for="ca_id3_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 3차분류</label>
                        <select name="ca_id3[<?php echo $i; ?>]" id="ca_id3_<?php echo $i; ?>" <? if(!$is_admin) {?>disabled<? }?> style="width:260px;">
                            <?php echo conv_selected_option($ca_list, $row['ca_id3']); ?>
                        </select>
                    </td>

                    <td rowspan="3" class="td_mngsmall"><?php echo $row['it_5']; ?></td>
                    <td rowspan="3" class="td_mngsmall"><?php echo $row['it_6']; ?></td>
                    <td rowspan="3" class="td_mngsmall"><?php echo $row['it_maker']; ?></td>
                    <?
                    $min = sql_fetch("select min(it_price) as price from {$g5['g5_shop_item_table']} where it_8 = '{$row['it_id']}' and it_use = '1' and it_soldout = '0' ");
                    ?>
                    <td rowspan="3" class="td_mngsmall"><?=number_format($min[price]) ?></td>
                    <td rowspan="3" class="td_mngsmall"><?php echo $row['it_2']; ?></td>


                    <? if($is_admin){?>
                        <td rowspan="3" class="td_mngsmall">
                            <label for="order_<?php echo $i; ?>" class="sound_only">순서</label>
                            <input type="text" name="it_order[<?php echo $i; ?>]" value="<?php echo $row['it_order']; ?>" id="order_<?php echo $i; ?>" class="frm_input" size="3">
                        </td>

                        <td rowspan="3" class="td_chk">
                            <label for="soldout_<?php echo $i; ?>" class="sound_only">품절</label>
                            <input type="checkbox" name="it_soldout[<?php echo $i; ?>]" <?php echo ($row['it_soldout'] ? 'checked' : ''); ?> value="1" id="soldout_<?php echo $i; ?>">
                        </td>
                    <? } ?>
                    <td rowspan="3" class="td_num"><?php echo $row['it_hit']; ?></td>
                    <td rowspan="3" class="td_mng">
                        <? if($row['it_8']==""){ ?>
                            <a href="./itemcopy.php?it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>" class="itemcopy btn btn-info btn-xs" target="_blank"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>상품등록</a>
                        <? } ?>
                        <? if($is_admin) { ?>
                            <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>&amp;m_class=<?=$m_class?>" class="btn btn-primary btn-xs"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>수정</a>
                        <? } ?>
                        <a href="<?php echo $href; ?>" target="_blank" class="btn btn-dark btn-xs"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>보기</a>
                    </td>
                </tr>
                <tr class="<?php echo $bg; ?>">
                    <td rowspan="2" class="td_img"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?></a></td>
                    <td headers="th_pc_title" rowspan="2" class="td_input">
                        <label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
                        <input type="text" name="it_name[<?php echo $i; ?>]" value="<?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?>" id="name_<?php echo $i; ?>" required class="frm_input required" size="30">
                    </td>
                    <? if($is_admin) {?>
                        <td headers="th_amt" class="td_numbig td_input">
                            <label for="price_<?php echo $i; ?>" class="sound_only">판매가격</label>
                            <input type="text" name="it_price[<?php echo $i; ?>]" value="<?php echo $row['it_price']; ?>" id="price_<?php echo $i; ?>" class="frm_input sit_amt" size="7">
                        </td>
                        <td headers="th_camt" class="td_numbig td_input">
                            <label for="cust_price_<?php echo $i; ?>" class="sound_only">시중가격</label>
                            <input type="text" name="it_cust_price[<?php echo $i; ?>]" value="<?php echo $row['it_cust_price']; ?>" id="cust_price_<?php echo $i; ?>" class="frm_input sit_camt" size="7">
                        </td>
                        <td headers="th_skin" class="td_numbig td_input">
                            <label for="it_skin_<?php echo $i; ?>" class="sound_only">PC 스킨</label>
                            <?php echo get_skin_select('shop', 'it_skin_'.$i, 'it_skin['.$i.']', $row['it_skin']); ?>
                        </td>
                    <? } ?>
                </tr>
                <tr class="<?php echo $bg; ?>">
                    <? if($is_admin) {?>
                        <td headers="th_pt" class="td_numbig td_input"><?php echo $it_point; ?></td>
                        <td headers="th_qty" class="td_numbig td_input">
                            <label for="stock_qty_<?php echo $i; ?>" class="sound_only">재고</label>
                            <input type="text" name="it_stock_qty[<?php echo $i; ?>]" value="<?php echo $row['it_stock_qty']; ?>" id="stock_qty_<?php echo $i; ?>" class="frm_input sit_qty" size="7">
                        </td>
                        <td headers="th_mskin" class="td_numbig td_input">
                            <label for="it_mobile_skin_<?php echo $i; ?>" class="sound_only">모바일 스킨</label>
                            <?php echo get_mobile_skin_select('shop', 'it_mobile_skin_'.$i, 'it_mobile_skin['.$i.']', $row['it_mobile_skin']); ?>
                        </td>
                    <? } ?>
                </tr>
                <?php
                $i++;
            }
            if ($i == 0)
                echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>

    <? if($is_admin){?>

        <div class="btn_list01 btn_list">
            <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
            <?php if ($is_admin == 'super') { ?>
                <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
            <?php } ?>
        </div>

    <? } ?>

    <!-- <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="일괄수정" class="btn_submit" accesskey="s">
    </div> -->
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;img_chk=$img_chk&amp;page="); ?>

<script>
    function chk_research(){
        if($("input:checkbox[name=re_search]").is(":checked") == true) {
            $('#frm_research').css("display", "block");
        }else{
            $('#frm_research').css("display", "none");
        }
    }
    function fitemlist_submit(f)
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

    $(function() {
        $(".itemcopy").click(function() {
            var href = $(this).attr("href");
            window.open(href, "copywin", "left=100, top=100, width=300, height=300, scrollbars=0");
            return false;
        });

        $('#img_chk').click(function() {
            var img_url = "";
            if( $(this).is(":checked") == true ) {
                img_url = "&img_chk=Y";
            }
            location.href = encodeURI('<?=$_SERVER['SCRIPT_NAME'];?>?<?=str_replace('&amp;', '&', $qstr."&page=".$page) ;?>'+img_url);
        });
    });

    function excelform(url)
    {
        var opt = "width=600,height=450,left=10,top=10";
        window.open(url, "win_excel", opt);
        return false;
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
    <? if($sca1){ ?>
    ajax_cate(<?=$sca1?>,"2",<?=$sca2?>);
    <? } ?>
    <? if($sca2){ ?>
    ajax_cate(<?=$sca2?>,"3",<?=$sca3?>);
    <? } ?>
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
