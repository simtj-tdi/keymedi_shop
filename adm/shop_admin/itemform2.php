<?php
$sub_menu = '400300';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH.'/iteminfo.lib.php');

auth_check($auth[$sub_menu], "w");

$html_title = "상품 ";

if ($w == "")
{
    $html_title .= "입력";

    // 옵션은 쿠키에 저장된 값을 보여줌. 다음 입력을 위한것임
    //$it[ca_id] = _COOKIE[ck_ca_id];
    $it['ca_id'] = get_cookie("ck_ca_id");
    $it['ca_id2'] = get_cookie("ck_ca_id2");
    $it['ca_id3'] = get_cookie("ck_ca_id3");
    if (!$it['ca_id'])
    {
        $sql = " select ca_id from {$g5['g5_shop_category_table']} order by ca_order, ca_id limit 1 ";
        $row = sql_fetch($sql);
        if (!$row['ca_id'])
            alert("등록된 분류가 없습니다. 우선 분류를 등록하여 주십시오.", './categorylist.php');
        $it['ca_id'] = $row['ca_id'];
    }
    //$it[it_maker]  = stripslashes($_COOKIE[ck_maker]);
    //$it[it_origin] = stripslashes($_COOKIE[ck_origin]);
    $it['it_maker']  = stripslashes(get_cookie("ck_maker"));
    $it['it_origin'] = stripslashes(get_cookie("ck_origin"));
}
else if ($w == "u")
{
    $html_title .= "수정";

    if ($is_admin != 'super')
    {
        $sql = " select it_id from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b
                  where a.it_id = '$it_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        // if (!$row['it_id'])
        //     alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 상품입니다.");
    }

    $sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $it = sql_fetch($sql);

    if(!$it)
        alert('상품정보가 존재하지 않습니다.');

    if (!$ca_id)
        $ca_id = $it['ca_id'];

    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);
}
else
{
    alert();
}

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');

// 분류리스트
$category_select = '';
$script = '';
$sql = " select * from {$g5['g5_shop_category_table']} ";
//if ($is_admin != 'super')
//   $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
//$sql .= " order by ca_order, ca_id ";
$sql .= " order by  ca_id , ca_order ";


$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;

    $nbsp = "";
    for ($i=0; $i<$len; $i++)
        $nbsp .= "&nbsp;&nbsp;&nbsp;";

    $category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']} ({$row['ca_id']})</option>\n";

    $script .= "ca_use['{$row['ca_id']}'] = {$row['ca_use']};\n";
    $script .= "ca_stock_qty['{$row['ca_id']}'] = {$row['ca_stock_qty']};\n";
    //$script .= "ca_explan_html['$row[ca_id]'] = $row[ca_explan_html];\n";
    $script .= "ca_sell_email['{$row['ca_id']}'] = '{$row['ca_sell_email']}';\n";
}

// 재입고알림 설정 필드 추가
if(!sql_query(" select it_stock_sms from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_stock_sms` tinyint(4) NOT NULL DEFAULT '0' AFTER `it_stock_qty` ", true);
}

// 추가옵션 포인트 설정 필드 추가
if(!sql_query(" select it_supply_point from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_supply_point` int(11) NOT NULL DEFAULT '0' AFTER `it_point_type` ", true);
}

// 상품메모 필드 추가
if(!sql_query(" select it_shop_memo from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_shop_memo` text NOT NULL AFTER `it_use_avg` ", true);
}

// 지식쇼핑 PID 필드추가
// 상품메모 필드 추가
if(!sql_query(" select ec_mall_pid from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `ec_mall_pid` varchar(255) NOT NULL AFTER `it_shop_memo` ", true);
}

$pg_anchor ='<ul class="anchor">
<li><a href="#anc_sitfrm_cate">상품분류</a></li>
<!-- <li><a href="#anc_sitfrm_skin">스킨설정</a></li> -->
<li><a href="#anc_sitfrm_ini">기본정보</a></li>
<!-- <li><a href="#anc_sitfrm_compact">요약정보</a></li> -->
<li><a href="#anc_sitfrm_cost">가격 및 재고</a></li>
<li><a href="#anc_sitfrm_sendcost">배송비</a></li>
<li><a href="#anc_sitfrm_img">상품이미지</a></li>
<li><a href="#anc_sitfrm_relation">관련상품</a></li>
<li><a href="#anc_sitfrm_event">관련이벤트</a></li>
<!-- <li><a href="#anc_sitfrm_optional">상세설명설정</a></li>
<li><a href="#anc_sitfrm_extra">여분필드</a></li> -->
</ul>
';

if($it[it_8]){
    $frm_submit = '<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./itemlist2.php?'.$qstr.'">목록</a>';
}else{

    $frm_submit = '<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./itemlist.php?'.$qstr.'">목록</a>';
}

if($it_id)
    $frm_submit .= PHP_EOL.'<a href="'.G5_SHOP_URL.'/item.php?it_id='.$it_id.'" class="btn_frmline">상품보기</a>';
$frm_submit .= '</div>';

// 쿠폰적용안함 설정 필드 추가
if(!sql_query(" select it_nocoupon from {$g5['g5_shop_item_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_nocoupon` tinyint(4) NOT NULL DEFAULT '0' AFTER `it_use` ", true);
}

// 스킨필드 추가
if(!sql_query(" select it_skin from {$g5['g5_shop_item_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_skin` varchar(255) NOT NULL DEFAULT '' AFTER `ca_id3`,
                    ADD `it_mobile_skin` varchar(255) NOT NULL DEFAULT '' AFTER `it_skin` ", true);
}
?>

<form name="fitemform" action="./itemformupdate2.php" method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return fitemformcheck(this)">

    <input type="hidden" name="codedup" value="<?php echo $default['de_code_dup_use']; ?>">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod"  value="<?php echo $sod; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx"  value="<?php echo $stx; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
    <section id="anc_sitfrm_ini">
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>기본정보 입력</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                    <col class="grid_3">
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="it_price">판매가격</label></th>
                    <td>
                        <input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" class="frm_input" size="8"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="it_stock_qty">재고수량</label></th>
                    <td>
                        <?php echo help("<b>주문관리에서 상품별 상태 변경에 따라 자동으로 재고를 가감합니다.</b> 재고는 규격/색상별이 아닌, 상품별로만 관리됩니다.<br>재고수량을 0으로 설정하시면 품절상품으로 표시됩니다."); ?>
                        <input type="text" name="it_stock_qty" value="<?php echo $it['it_stock_qty']; ?>" id="it_stock_qty" class="frm_input" size="8"> 개
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="it_buy_min_qty">최소구매수량</label></th>
                    <td>
                        <?php echo help("상품 구매시 최소 구매 수량을 설정합니다."); ?>
                        <input type="text" name="it_buy_min_qty" value="<?php echo $it['it_buy_min_qty']; ?>" id="it_buy_min_qty" class="frm_input" size="8"> 개
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="it_buy_max_qty">최대구매수량</label></th>
                    <td>
                        <?php echo help("상품 구매시 최대 구매 수량을 설정합니다."); ?>
                        <input type="text" name="it_buy_max_qty" value="<?php echo $it['it_buy_max_qty']; ?>" id="it_buy_max_qty" class="frm_input" size="8"> 개
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="it_shop_memo">상점메모</label></th>
                    <td><textarea name="it_shop_memo" id="it_shop_memo"><?php echo $it['it_shop_memo']; ?></textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="anc_sitfrm_img">

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>이미지 업로드</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                <?php for($i=1; $i<=5; $i++) { ?>
                    <tr>
                        <th scope="row"><label for="it_img<?php echo $i; ?>">셀러이미지 <?php echo $i; ?></label></th>
                        <td>
                            <input type="file" name="it_img<?php echo $i; ?>" id="it_img<?php echo $i; ?>">
                            <?php
                            $it_img = G5_DATA_PATH.'/item/'.$it['it_img'.$i];
                            if(is_file($it_img) && $it['it_img'.$i]) {
                                $size = @getimagesize($it_img);
                                $thumb = get_it_thumbnail($it['it_img'.$i], 25, 25);
                                ?>
                                <label for="it_img<?php echo $i; ?>_del"><span class="sound_only">이미지 <?php echo $i; ?> </span>파일삭제</label>
                                <input type="checkbox" name="it_img<?php echo $i; ?>_del" id="it_img<?php echo $i; ?>_del" value="1">
                                <span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
                                <div id="limg<?php echo $i; ?>" class="banner_or_img">
                                    <img src="<?php echo G5_DATA_URL; ?>/item/<?php echo $it['it_img'.$i]; ?>" alt="" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>">
                                    <button type="button" class="sit_wimg_close">닫기</button>
                                </div>
                                <script>
                                    $('<button type="button" id="it_limg<?php echo $i; ?>_view" class="btn_frmline sit_wimg_view">이미지<?php echo $i; ?> 확인</button>').appendTo('.sit_wimg_limg<?php echo $i; ?>');
                                </script>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="anc_sitfrm_extra">


        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="grid_4">
                    <col>
                    <col class="grid_3">
                </colgroup>
                <tbody>

                <?php if ($w == "u") { ?>
                    <tr>
                        <th scope="row">입력일시</th>
                        <td colspan="2">
                            <?php echo help("상품을 처음 입력(등록)한 시간입니다."); ?>
                            <?php echo $it['it_time']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">수정일시</th>
                        <td colspan="2">
                            <?php echo help("상품을 최종 수정한 시간입니다."); ?>
                            <?php echo $it['it_update_time']; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php echo $frm_submit; ?>
</form>


<script>
    var f = document.fitemform;

    <?php if ($w == 'u') { ?>
    $(".banner_or_img").addClass("sit_wimg");
    $(function() {
        $(".sit_wimg_view").bind("click", function() {
            var sit_wimg_id = $(this).attr("id").split("_");
            var $img_display = $("#"+sit_wimg_id[1]);

            $img_display.toggle();

            if($img_display.is(":visible")) {
                $(this).text($(this).text().replace("확인", "닫기"));
            } else {
                $(this).text($(this).text().replace("닫기", "확인"));
            }

            var $img = $("#"+sit_wimg_id[1]).children("img");
            var width = $img.width();
            var height = $img.height();
            if(width > 700) {
                var img_width = 700;
                var img_height = Math.round((img_width * height) / width);

                $img.width(img_width).height(img_height);
            }
        });
        $(".sit_wimg_close").bind("click", function() {
            var $img_display = $(this).parents(".banner_or_img");
            var id = $img_display.attr("id");
            $img_display.toggle();
            var $button = $("#it_"+id+"_view");
            $button.text($button.text().replace("닫기", "확인"));
        });
    });
    <?php } ?>

    function codedupcheck(id)
    {
        if (!id) {
            alert('상품코드를 입력하십시오.');
            f.it_id.focus();
            return;
        }

        var it_id = id.replace(/[A-Za-z0-9\-_]/g, "");
        if(it_id.length > 0) {
            alert("상품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
            return false;
        }

        $.post(
            "./codedupcheck.php",
            { it_id: id },
            function(data) {
                if(data.name) {
                    alert("코드 '"+data.code+"' 는 '".data.name+"' (으)로 이미 등록되어 있으므로\n\n사용하실 수 없습니다.");
                    return false;
                } else {
                    alert("'"+data.code+"' 은(는) 등록된 코드가 없으므로 사용하실 수 있습니다.");
                    document.fitemform.codedup.value = '';
                }
            }, "json"
        );
    }

    function fitemformcheck(f)
    {
        if (!f.ca_id.value) {
            alert("기본분류를 선택하십시오.");
            f.ca_id.focus();
            return false;
        }

        if (f.w.value == "") {
            var error = "";
            $.ajax({
                url: "./ajax.it_id.php",
                type: "POST",
                data: {
                    "it_id": f.it_id.value
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(data, textStatus) {
                    error = data.error;
                }
            });

            if (error) {
                alert(error);
                return false;
            }
        }

        if(f.it_point_type.value == "1") {
            var point = parseInt(f.it_point.value);
            if(point > 99) {
                alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");
                return false;
            }
        }

        if(parseInt(f.it_sc_type.value) > 1) {
            if(!f.it_sc_price.value || f.it_sc_price.value == "0") {
                alert("기본배송비를 입력해 주십시오.");
                return false;
            }

            if(f.it_sc_type.value == "2" && (!f.it_sc_minimum.value || f.it_sc_minimum.value == "0")) {
                alert("배송비 상세조건의 주문금액을 입력해 주십시오.");
                return false;
            }

            if(f.it_sc_type.value == "4" && (!f.it_sc_qty.value || f.it_sc_qty.value == "0")) {
                alert("배송비 상세조건의 주문수량을 입력해 주십시오.");
                return false;
            }
        }

        // 관련상품처리
        var item = new Array();
        var re_item = it_id = "";

        $("#reg_relation input[name='re_it_id[]']").each(function() {
            it_id = $(this).val();
            if(it_id == "")
                return true;

            item.push(it_id);
        });

        if(item.length > 0)
            re_item = item.join();

        $("input[name=it_list]").val(re_item);

        // 이벤트처리
        var evnt = new Array();
        var ev = ev_id = "";

        $("#reg_event_list input[name='ev_id[]']").each(function() {
            ev_id = $(this).val();
            if(ev_id == "")
                return true;

            evnt.push(ev_id);
        });

        if(evnt.length > 0)
            ev = evnt.join();

        $("input[name=ev_list]").val(ev);

        <?php echo get_editor_js('it_explan'); ?>
        <?php echo get_editor_js('it_mobile_explan'); ?>
        <?php echo get_editor_js('it_head_html'); ?>
        <?php echo get_editor_js('it_tail_html'); ?>
        <?php echo get_editor_js('it_mobile_head_html'); ?>
        <?php echo get_editor_js('it_mobile_tail_html'); ?>

        return true;
    }

    function categorychange(f)
    {
        var idx = f.ca_id.value;

        if (f.w.value == "" && idx)
        {
            f.it_use.checked = ca_use[idx] ? true : false;
            f.it_stock_qty.value = ca_stock_qty[idx];
            f.it_sell_email.value = ca_sell_email[idx];
        }
    }

    categorychange(document.fitemform);
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
