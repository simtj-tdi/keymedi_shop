<?php
include_once('./_common.php');


if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = $member['mb_nick'].' 님의 문의 내역';
include_once(G5_PATH.'/head.sub.php');

$sql_common = " from g5_write_0402 ";
$sql_search = " where 1 = 1 and wr_is_comment = 0 and mb_id = '{$member[mb_id]}' ";

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
$config['cf_page_rows'] = "10";

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_search2} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];


if(!$add_page){
	$add_page = 1;
} 

$rows = 10;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_search2} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);


$g5['title'] = "쿠폰 내역";
include_once('./_head.php');

?>

<!-- 쿠폰 내역 시작 { -->

<div id="sub_top_new_menu"> 
	<span><a href="/shop/mypage.php">마이페이지</a></span>
	<span><a href="/shop/orderinquiry.php">주문내역</a></span>
	<span><a href="/shop/takeback.php">반품신청</a></span>
	<span><a href="/shop/wishlist.php">위시리스트</a></span>
	<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
	<span><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
	<span class="ov"><a href="/shop/mylist.php">문의내역</a></span>
	<? if($member[mb_v] == "4"){?>
		<span><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
</div>
<div id="sub_top_new_menu_title">
	<h2>문의내역</h2>
</div>


<div id="coupon" class="new_win">
    <!-- <h1 id="win_title"><?php echo $g5['title'] ?></h1> -->
	
	 
    <div class="tbl_wrap tbl_head01">
        <table>
        <thead>
        <tr>
            <th scope="col">번호</th>
			<th scope="col">제목</th>
            <th scope="col">글쓴이</th>
            <th scope="col">날짜</th>
            <th scope="col">조회</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        for($i=0; $row=sql_fetch_array($result); $i++) {
             
        ?>
        <tr>
			<td align="center" width="50"><?php echo $i+1; ?></td>
			<td align="center" ><a href="/bbs/board.php?bo_table=0402&wr_id=<?php echo $row['wr_id']; ?>"><?php echo $row['wr_subject']; ?></a></td>
            <td align="center" width="100"><?php echo $row['wr_name']; ?></td>
            <td align="center" width="100"><?php echo substr($row['wr_datetime'],0,10); ?></td>
            <td align="center" width="120"><?php echo $row['wr_hit']; ?></td>
        </tr>
        <?php
        }

        if($i==0)
            echo '<tr><td colspan="4" class="empty_table">문의내역이 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>

		<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;search_1='.$search_1.'&amp;search_2='.$search_2.'&amp;wr_class='.$wr_class.'&amp;co_id='.$co_id.'&amp;page='); ?>

    </div>
	
    <!-- <div class="win_btn"><button type="button" onclick="window.close();">창닫기</button></div> -->
</div>
<script>
function fwrite_submit(f)
{	
   f.wr_code.value = f.wr_code1.value+"-"+f.wr_code2.value+"-"+f.wr_code3.value+"-"+f.wr_code4.value;
   return true;
}
</script>
<?php
include_once('./_tail.php');
?>