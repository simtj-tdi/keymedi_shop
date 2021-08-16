<?php
$sub_menu = '500500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '배너관리(키메디)';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql_common = " from shop.shop_banner ";

$sql_search = " where 1 = 1  ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt 
        {$sql_common}
        {$sql_search}
        {$sql_order} ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
?>

<div class="local_ov01 local_ov">
    등록된 배너 <?php echo $total_count; ?>개
</div>

<? if($_SERVER['HTTPS'] != "on"){  ?>
    <link type="text/css" href="/css/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<? }else{?>
    <link type="text/css" href="/css/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<? } ?>

<style type="text/css">

    .ui-datepicker { font:12px dotum; }
    .ui-datepicker select.ui-datepicker-month,
    .ui-datepicker select.ui-datepicker-year { width: 70px;}
    .ui-datepicker-trigger { margin:0 0 -5px 2px; cursor:pointer;}

</style>
<script>
    jQuery(function($){
        $.datepicker.regional['ko'] = {
            closeText: '닫기',
            prevText: '이전달',
            nextText: '다음달',
            currentText: '오늘',
            monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
                '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
            monthNamesShort: ['1월','2월','3월','4월','5월','6월',
                '7월','8월','9월','10월','11월','12월'],
            dayNames: ['일','월','화','수','목','금','토'],
            dayNamesShort: ['일','월','화','수','목','금','토'],
            dayNamesMin: ['일','월','화','수','목','금','토'],
            weekHeader: 'Wk',
            dateFormat: 'yy-mm-dd',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['ko']);

        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: 'c-99:c+99',
//        minDate: '+2d',
            onSelect: function(dateText, inst) { }

        });

        $('#sdate').datepicker();
        $('#sdate').datepicker("option", "maxDate", $("#edate").val());
        $('#sdate').datepicker("option", "onClose", function ( selectedDate ) {
            $("#edate").datepicker( "option", "minDate", selectedDate );
        });

        $('#edate').datepicker();
        $('#edate').datepicker("option", "minDate", $("#sdate").val());
        $('#edate').datepicker("option", "onClose", function ( selectedDate ) {
            $("#sdate").datepicker( "option", "maxDate", selectedDate );
        });
    });

    function check_sort(no){
        var form = document.fsearch;
        var sort="";
        if(no==1){
            sort = "asc";
        }else{
            sort = "desc";
        }
        document.getElementById("sort_ord").value = sort;
        form.submit();
    }
</script>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get" onsubmit="return search_chk(this);">
    <input type="hidden" name="sort_ord" id="sort_ord" />

    <select name="bn_area" id="bn_area">
        <option value="">위치</option>
        <option value="왼쪽" <?php echo get_selected($bn['bn_position'], '왼쪽'); ?>>왼쪽</option>
        <option value="메인" <?php echo get_selected($bn['bn_position'], '메인'); ?>>산부인과몰 메인</option>
        <option value="키메디왼쪽" <?php echo get_selected($bn['bn_position'], '키메디왼쪽'); ?>>키메디몰 왼쪽</option>
        <option value="키메디메인" <?php echo get_selected($bn['bn_position'], '키메디메인'); ?>>키메디몰 메인</option>

        <option value="공통메인_이벤트" <?php echo get_selected($bn['bn_position'], '공통메인_이벤트'); ?>>공통메인_이벤트</option>
        <option value="산협몰 로그인페이지" <?php echo get_selected($bn['bn_position'], '산협몰 로그인페이지'); ?>>산부인과몰 로그인페이지</option>

        <option value="키메디몰_상단" <?php echo get_selected($bn['bn_position'], '키메디몰_상단'); ?>>키메디몰_상단</option>
        <option value="산협몰_상단" <?php echo get_selected($bn['bn_position'], '산협몰_상단'); ?>>산부인과몰_상단</option>
        <option value="베스트상품_상단부" <?php echo get_selected($bn['bn_position'], '베스트상품_상단부'); ?>>베스트상품_상단부</option>
    </select>

    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">

    <input type="submit" class="btn_submit" value="검색">
</form>

<div class="btn_add01 btn_add">
    <a href="#" onclick="down_excel('./bannerlist_excel.php');" id="member_add">엑셀다운</a>
    <a href="./bannerform.php">배너추가</a>
</div>

<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"id="th_id" width="3%">ID</th>
        <th scope="col" id="th_dvc">접속기기</th>
        <th scope="col" id="th_loc">위치</th>
        <th scope="col" id="th_st" width="8%">등록일</th>
        <th scope="col" id="th_st" width="8%">시작일시</th>
        <th scope="col" id="th_end" width="8%">종료일시</th>
        <th scope="col" id="th_odr" width="4%">
        <?
        echo "출력순서";
        if($sort_ord=='' || $sort_ord=='asc'){
            echo "<a onclick='check_sort(2);'><font color='red'>&#9660;</font></a>";
        }else{
            echo "<a onclick='check_sort(1);'><font color='blue'>&#9650;</font></a>";
        }
        ?>
        </th>
        <th scope="col" id="th_hit" width="3%">조회</th>
        <th scope="col" id="th_mng" width="5%">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = " select * 
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 테두리 있는지
        $bn_border  = $row['bn_border'];
        // 새창 띄우기인지
        $bn_new_win = ($row['bn_new_win']) ? 'target="_blank"' : '';

        $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
        if(file_exists($bimg)) {
            $size = @getimagesize($bimg);
            if($size[0] && $size[0] > 800)
                $width = 800;
            else
                $width = $size[0];

            $bn_img = "";
            if ($row['bn_url'] && $row['bn_url'] != "http://")
                $bn_img .= '<a href="'.$row['bn_url'].'" '.$bn_new_win.'>';
            $bn_img .= '<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" width="'.$width.'" alt="'.$row['bn_alt'].'"></a>';
        }

        switch($row['bn_device']) {
            case 'pc':
                $bn_device = 'PC';
                break;
            case 'mobile':
                $bn_device = '모바일';
                break;
            default:
                $bn_device = 'PC와 모바일';
                break;
        }

        $bn_begin_time = substr($row['bn_begin_time'], 2, 14);
        $bn_end_time   = substr($row['bn_end_time'], 2, 14);
        $bn_time   = substr($row['bn_time'], 2, 14);

        $bg = 'bg'.($i%2);

        switch($row['bn_position']) {
            case "메인" : $bn_position = "산부인과몰 메인"; break;
            case "키메디왼쪽" : $bn_position = "키메디몰 왼쪽"; break;
            case "키메디메인" : $bn_position = "키메디몰 메인"; break;
            case "신협몰_상단" : $bn_position = "상부인과몰 상단"; break;
            default : $bn_position = $row['bn_position']; break;
        }
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="th_id" rowspan="2" class="td_num"><?php echo $row['bn_id']; ?></td>
        <td headers="th_dvc"><?php echo $row['bn_alt']; ?> (<?php echo $bn_device; ?>)</td>
        <td headers="th_loc"><?php echo $bn_position; ?></td>
        <td headers="th_loc"><?php echo $bn_time; ?></td>
        <td headers="th_st" class="td_datetime"><?php echo $bn_begin_time; ?></td>
        <td headers="th_end" class="td_datetime"><?php echo $bn_end_time; ?></td>
        <td headers="th_odr" class="td_num"><?php echo $row['bn_order']; ?></td>
        <td headers="th_hit" class="td_num"><?php echo $row['bn_hit']; ?></td>
        <td headers="th_mng" class="td_mngsmall">
            <a href="./bannerform.php?w=u&amp;bn_id=<?php echo $row['bn_id']; ?>" class="btn btn-primary btn-xs">수정</a></li>
            <a href="./bannerformupdate.php?w=d&amp;bn_id=<?php echo $row['bn_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger btn-xs">삭제</a>
        </td>
    </tr>
    <tr class="<?php echo $bg; ?>">
        <td headers="th_img" colspan="8" class="td_img_view sbn_img">
	        <div class="sbn_image"><?php echo $bn_img; ?></div>
            <button type="button" class="sbn_img_view btn_frmline">이미지확인</button>
        </td>
    </tr>

    <?php
    }
    if ($i == 0) {
    echo '<tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>

</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?&amp;sdate=".$sdate."&amp;edate=".$edate."&amp;date_kind=".$date_kind."&amp;bn_area=".$bn_area."&amp;page="); ?>

<script>
$(function() {
    $(".sbn_img_view").on("click", function() {
        $(this).closest(".td_img_view").find(".sbn_image").slideToggle();
    });
});

function down_excel(src){
    document.fsearch.action = src;
    document.fsearch.submit();
    document.fsearch.action = "./bannerlist.php";
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
