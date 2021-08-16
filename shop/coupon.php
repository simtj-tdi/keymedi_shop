<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/coupon.php');
    return;
}

// 테마에 coupon.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_coupon_file = G5_THEME_SHOP_PATH.'/coupon.php';
    if(is_file($theme_coupon_file)) {
        include_once($theme_coupon_file);
        return;
        unset($theme_coupon_file);
    }
}

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = $member['mb_nick'].' 님의 쿠폰 내역';
include_once(G5_PATH.'/head.sub.php');

$sql = " select cp_id, cp_subject, cp_method, cp_target, cp_start, cp_end, cp_type, cp_price
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '{$member['mb_where']}', '전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."'
            order by cp_no ";
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
	<span class="ov"><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
	<span><a href="/shop/mylist.php">문의내역</a></span>
	<? if($member[mb_v] == "4"){?>
		<span><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
</div>
<div id="sub_top_new_menu_title">
	<h2>쿠폰/<?=$point_txt?></h2>
</div>

<div id="item_ex_box2">
	<div id="item_s_12" class="ovtop"><a href="/shop/coupon.php">쿠폰</a></div>
	<div id="item_s_22" class=""><a href="/bbs/point.php"><?=$point_txt?></a></div>
	<div id="item_s_32" ></div>

	<div style="position:relative;top:-1px;padding:30px;clear:both;border-left:1px solid #d7d7d7;border-right:1px solid #d7d7d7;border-bottom:1px solid #d7d7d7;">


<div id="coupon" class="new_win">
    <!-- <h1 id="win_title"><?php echo $g5['title'] ?></h1> -->
 
	<div id="cupon_boxs">
		<form name="fcouponform" id="fcouponform" action="./coupon_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" >
			<input type="hidden" name="wr_code" value="" />
			쿠폰등록하기
			<input type="text" maxlength="4" size="5" class="cu_input " required="" id="wr_code1"  name="wr_code1"> 
			<input type="text" maxlength="4" size="5" class="cu_input " required="" id="wr_code2"  name="wr_code2">
			<input type="text" maxlength="4" size="5" class="cu_input " required="" id="wr_code3"  name="wr_code3">
			<input type="text" maxlength="4" size="5" class="cu_input " required="" id="wr_code4"  name="wr_code4">
			<input type="submit" class="cu_input2" accesskey="s" id="" value="등록하기"> 		 
		</form>
	</div> 
	<ul class="cupon_li">
		<li>쿠폰번호를 대소문자 구분하여 정확하게 입력해주시기 바랍니다.</li>
		<li>쿠폰번호는 1회용으로 등록이 완료된 번호는 재사용이 불가능합니다.</li>
		<li>구폰사용과 관련해서 더 궁금하신 점이 있으시다면 <a href="/bbs/write.php?bo_table=0402">고객센터</a>로 문의주시기 바랍니다.</li>
	</ul>
	<!-- <form name="fcouponform" id="fcouponform" action="./coupon_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" >
		<input type="hidden" name="wr_code" value="" />
		<div class="tbl_wrap tbl_head01">
		<table>
		<thead>
			<tr>
				<th scope="col" colspan="2">쿠폰등록하기</th>
			</tr>
		</thead>
		<tbody> 
			<tr>
				<td align="center">
					<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_code1"  name="wr_code1"> - 
					<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_code2"  name="wr_code2"> - 
					<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_code3"  name="wr_code3"> -
					<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_code4"  name="wr_code4">
				</td> 
				<td align="center"><input type="submit" class="btn_submit" accesskey="s" id="btn_submit" value="등록하기"></td>
			</tr>
		</tbody>
		</table>
		</div>
	</form> -->
	<br>
    <div class="tbl_wrap tbl_head01">
        <table>
        <thead>
        <tr>
            <th scope="col">쿠폰명</th>
			<th scope="col">업체명</th>
            <th scope="col">적용대상</th>
            <th scope="col">할인금액</th>
            <th scope="col">사용기한</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $cp_count = 0;
        for($i=0; $row=sql_fetch_array($result); $i++) {
            if(is_used_coupon($member['mb_id'], $row['cp_id']))
                continue;

            if($row['cp_method'] == 1) {
                $sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '{$row['cp_target']}' ";
                $ca = sql_fetch($sql);
                $cp_target = $ca['ca_name'].'의 상품할인';
            } else if($row['cp_method'] == 2) {
                $cp_target = '결제금액 할인';
            } else if($row['cp_method'] == 3) {
                $cp_target = '배송비 할인';
            } else {
                $sql = " select it_name from {$g5['g5_shop_item_table']} where it_id = '{$row['cp_target']}' ";
                $it = sql_fetch($sql);
                $cp_target = $it['it_name'].' 상품할인';
            }

            if($row['cp_type'])
                $cp_price = $row['cp_price'].'%';
            else
                $cp_price = number_format($row['cp_price']).'원';

            $cp_count++;

			if($row['cp_method'] == 0){
				$seller_id = sql_fetch("select it_10 from g5_shop_item where it_id = '$row[cp_target]' ");
				$seller_name = get_member($seller_id[it_10]);
			}else{
				if($row[cp_target] == "all"){
					$seller_name['mb_nick'] = "전체공급사";
				}else{
					$seller_name = get_member($row[cp_target]);
				}
			}
        ?>
        <tr>
			<td align="center"><?php echo $row['cp_subject']; ?></td>
			<td align="center" width="100"><?php echo $seller_name['mb_nick']; ?></td>
            <td align="center"><?php echo $cp_target; ?></td>
            <td align="center" width="60"><?php echo $cp_price; ?></td>
            <td align="center" width="120"><?php echo substr($row['cp_start'], 2, 8); ?> ~ <?php echo substr($row['cp_end'], 2, 8); ?></td>
        </tr>
        <?php
        }

        if(!$cp_count)
            echo '<tr><td colspan="4" class="empty_table">사용할 수 있는 쿠폰이 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>

    <!-- <div class="win_btn"><button type="button" onclick="window.close();">창닫기</button></div> -->
</div>

</div>
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