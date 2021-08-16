<?php
include_once('./_common.php');

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/mypage.php');
    return;
}

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_SHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = $member['mb_name'].'님 마이페이지';
include_once('./_head.php');

// 쿠폰
$cp_count = 0;
$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '{$member['mb_where']}','전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
    if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
        $cp_count++;
}
?>

<!-- 마이페이지 시작 { -->
<div id="smb_my">
	<div id="sub_top_new_menu"> 
		<span class="ov"><a href="/shop/mypage.php">마이페이지</a></span>
		<span><a href="/shop/orderinquiry.php">주문내역</a></span>
		<span><a href="/shop/takeback.php">반품신청</a></span>
		<span><a href="/shop/wishlist.php">위시리스트</a></span>
		<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
		<span><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
		<span><a href="/shop/mylist.php">문의내역</a></span>
		<? if($member[mb_v] == "4"){?>
		<span><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
	</div>
	
    <!-- 회원정보 개요 시작 { -->
    <section id="smb_my_ov">
        <h2>회원정보 개요</h2>

        <!-- <div id="smb_my_act">
            <ul>
                <?php if ($is_admin == 'super') { ?><li><a href="<?php echo G5_ADMIN_URL; ?>/" class="btn_admin">관리자</a></li><?php } ?>
                <li><a href="<?php echo G5_BBS_URL; ?>/memo.php" target="_blank" class="win_memo btn01">쪽지함</a></li>
                <li><a href="http://www.kacsmall.com/bbs/member_confirm.php?url=register_form.php" class="btn01" target="_blank">회원정보수정</a></li>
                <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" onclick="return member_leave();" class="btn02">회원탈퇴</a></li>
            </ul>
        </div> -->
<?
	$gregre = sql_fetch(" select wr_class from g5_grade where mb_id = '{$member[mb_id]}'  order by  wr_date desc limit 1");
?>
        <dl>
            <dt style="height:25px;"><?=$point_txt?></dt>
            <dd style="height:25px;"><a href="<?php echo G5_BBS_URL; ?>/point.php"><?php echo number_format($member['mb_point']); ?>점</a>&nbsp;<a href="<?php echo G5_BBS_URL; ?>/point.php"class="btn02" style="padding:1px 5px;"><span>상세보기</span></a></dd>
            <dt style="height:25px;">보유쿠폰</dt>
            <dd style="height:25px;"><a href="<?php echo G5_SHOP_URL; ?>/coupon.php" ><?php echo number_format($cp_count); ?></a>&nbsp;<a href="<?php echo G5_SHOP_URL; ?>/coupon.php" class="btn02" style="padding:1px 5px;"><span>상세보기</span></a></dd>
            <dt style="height:25px;">연락처</dt>
            <dd style="height:25px;"><?php echo ($member['mb_8'] ? $member['mb_8'] : '미등록'); ?></dd>
            <dt style="height:25px;">E-Mail</dt>
            <dd style="height:25px;"><?php echo ($member['mb_email'] ? $member['mb_email'] : '미등록'); ?></dd>
            <dt style="height:25px;">최종접속일시</dt>
            <dd style="height:25px;"><?php echo $member['mb_today_login']; ?></dd>
            <dt style="height:25px;">회원가입일시</dt>
            <dd style="height:25px;"><?php echo $member['mb_datetime']; ?></dd>
            <dt id="smb_my_ovaddt"  style="height:25px;">주소</dt>
            <dd id="smb_my_ovaddd"  style="height:25px;"><?php echo sprintf("(%s%s)", $member['mb_5'],"").' '.print_address($member['mb_6'], $member['mb_7'], "", ""); ?></dd>
			<? if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){  ?>
            <dt style="height:25px;">회원등급</dt>
            
            <dd style="height:25px;"><a href="#" onclick="open_layer();return false;" style="color:#01a3e4;"><?=$gregre[wr_class]?> <span style="background: #01a3e4; color: #fff; padding: 3px 7px 4px; font-size: 11px; border-radius: 5px;">회원등급제란?</span></a></dd>

			<? } ?>
        </dl>
    </section>
    <!-- } 회원정보 개요 끝 -->
	

			<div id="medi_live_bn_box" style="position:fixed;width:100%;top:100px;z-index:99999;"> 
				<div id="medi_live_bn" style="position:relative;width:536px;margin:0 auto;;display:none;z-index:99999;border:1px solid #ccc;">
					<img src="<?php echo G5_IMG_URL ?>/shop_grade2.jpg" usemap="#Map_ca2" />	
					<div class="hd_pops_footer">				
						<a href="#" onclick="close_layer();return false;"><button>닫기</button></a>
					</div>
				</div>		
			</div>
			<script>
			function open_layer(){
				document.getElementById("medi_live_bn").style.display = "block";
			}
			function close_layer(){
				document.getElementById("medi_live_bn").style.display = "none";
			}
			</script>


    <!-- 최근 주문내역 시작 { -->
    <section id="smb_my_od">
        <h2>최근 주문내역</h2>
        <?php
        // 최근 주문내역
        define("_ORDERINQUIRY_", true);

        $limit = " limit 0, 5 ";
        include G5_SHOP_PATH.'/orderinquiry.sub.php';
        ?>

        <div class="smb_my_more">
            <a href="./orderinquiry.php" class="btn01">주문내역 더보기</a>
        </div>
    </section>
    <!-- } 최근 주문내역 끝 -->
	<?
		$acnt = sql_fetch("select count(pod_id) as cnt from {$g5['g5_shop_order_table']} where mb_id = '$member[mb_id]' and od_status = '주문' and od_settle_case = '가상계좌' ");
	?>
<? if($acnt[cnt] > 0){?>
	<section id="smb_my_od">
		<h2>입금해야할 가상계좌내역</h2>
		<div class="tbl_head01 tbl_wrap">
            <table>
            <thead>
            <tr>
                <!-- <th scope="col">PG주문번호</th> -->
                <th scope="col">주문번호</th>
                <th scope="col">금액</th>
                <th scope="col">합계금액</th>
				<th scope="col">입금계좌</th>
            </tr>
            </thead>
			<?
			$sql = "select pod_id , od_id , od_receipt_price ,od_coupon,
			(select sum(cp_price) from g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon,
			od_receipt_point , od_bank_account from {$g5['g5_shop_order_table']} m  where mb_id = '$member[mb_id]' and od_status = '주문' and od_settle_case = '가상계좌' order by od_time desc ";
			$res = sql_query($sql);

			?>
			<tbody>
			<? //$row['od_coupon']-$row['od_cart_coupon']-$row['od_receipt_point']
				$j = 0;
				for($i = 0 ; $row = sql_fetch_array($res);$i++){
				$sql_c = sql_fetch("select count(*) as cnt , sum(od_receipt_price-od_coupon-od_cart_coupon-od_receipt_point) as price , (select sum(cp_price) from g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon from {$g5['g5_shop_order_table']} m where mb_id = '$member[mb_id]' and pod_id = '$row[pod_id]'"); 
		 

				$sql_cc = sql_fetch("select sum(od_cart_coupon) as od_cart_coupon_sum from (
					select (select sum(cp_price) from g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon from {$g5['g5_shop_order_table']} 
					m where mb_id = '$member[mb_id]' and pod_id = '$row[pod_id]'
				) mm ");
			?>
				<? if($j==0){?>
					<tr>
						<!-- <td style="text-align:center;" rowspan="<?=$sql_c[cnt]?>"><?=$row[od_id]?></td> -->
						<td style="text-align:center;"><?=$row[od_id]?></td>
						<td style="text-align:center;"><?=number_format($row[od_receipt_price]-$row['od_coupon']-$row['od_cart_coupon']-$row['od_receipt_point'])?></td>
						<td style="text-align:center;" rowspan="<?=$sql_c[cnt]?>"><?=number_format($sql_c[price] - $sql_cc['od_cart_coupon_sum'])?></td>
						<td style="text-align:center;" rowspan="<?=$sql_c[cnt]?>"><?=$row[od_bank_account]?></td>
					</tr>
				<? }else{?>
					<tr>
						<td style="text-align:center;"><?=$row[od_id]?></td>
						<td style="text-align:center;"><?=number_format($row[od_receipt_price]-$row['od_coupon']-$row['od_cart_coupon']-$row['od_receipt_point'])?></td>
					</tr>
				<? } ?>
			<? 
				$j++;
				if($j == $sql_c[cnt]) $j = 0;
			} 			
			?>
			</tbody>
            </table>
        </div>
	</section>
<? } ?>
    <!-- 최근 위시리스트 시작 { -->
    <section id="smb_my_wish">
        <h2>최근 위시리스트</h2>

        <div class="tbl_head01 tbl_wrap">
            <table>
            <thead>
            <tr>
                <th scope="col" width="160">이미지</th>
                <th scope="col">상품명</th>
				<th scope="col" >규격</th> 
				<th scope="col" >단위</th>
                <th scope="col" width="180">보관일시</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = " select *
                       from {$g5['g5_shop_wish_table']} a,
                            {$g5['g5_shop_item_table']} b
                      where a.mb_id = '{$member['mb_id']}'
                        and a.it_id  = b.it_id
                      order by a.wi_id desc
                      limit 0, 3 ";
            $result = sql_query($sql);
            for ($i=0; $row = sql_fetch_array($result); $i++)
            {
                $image = get_it_image($row['it_id'], 70, 70, false);
				$row2 = sql_fetch("select * from g5_shop_item where it_id = '$row[it_id]' ");
				$row3 = sql_fetch("select * from shop.g5_shop_item where it_id = '$row2[it_8]' ");
            ?>

            <tr>
                <td class="smb_my_img"><?php echo $image; ?></td>
                <td style="text-align:center;"><a href="./search.php?q=<?php echo $row['it_id']; ?>&q_where=main"><?php echo stripslashes($row['it_name']); ?></a></td>
				<td  class="td_numbig"><?=$row3['it_5']?></td>
				<td  class="td_numbig"><?=$row3['it_6']?></td>
                <td class="td_datetime"><?php echo substr($row['wi_time'],0,16); ?></td>
            </tr>

            <?php
            }

            if ($i == 0)
                echo '<tr><td colspan="3" class="empty_table">보관 내역이 없습니다.</td></tr>';
            ?>
            </tbody>
            </table>
        </div>

        <div class="smb_my_more">
            <a href="./wishlist.php" class="btn01">위시리스트 더보기</a>
        </div>
    </section>
    <!-- } 최근 위시리스트 끝 -->

</div>

<script>
$(function() {
    $(".win_coupon").click(function() {
        var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
        new_win.focus();
        return false;
    });
});

function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
?>