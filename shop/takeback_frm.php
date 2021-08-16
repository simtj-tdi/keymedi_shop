<?php
include_once('./_common.php');

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!$is_member) {
    if (get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 주문서 조회가 불가합니다.\\n\\n주문조회 화면을 통하여 조회하시기 바랍니다.", G5_SHOP_URL);
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

// 결제방법
$settle_case = $od['od_settle_case'];

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiryview.php');
    return;
}

// 테마에 orderinquiryview.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiryview_file = G5_THEME_SHOP_PATH.'/orderinquiryview.php';
    if(is_file($theme_inquiryview_file)) {
        include_once($theme_inquiryview_file);
        return;
        unset($theme_inquiryview_file);
    }
}

$g5['title'] = '반품관리';
include_once('./_head.php');

// LG 현금영수증 JS
if($od['od_pg'] == 'lg') {
    if($default['de_card_test']) {
    echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    }
}

$sql_cc = sql_fetch("select sum(od_cart_coupon) as od_cart_coupon_sum from (
					select (select sum(cp_price) from g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon from g5_shop_order 
					m where mb_id = '$od[mb_id]' and od_id = '$od[od_id]'
				) mm ");

?>

<!-- 주문상세내역 시작 { -->
<div id="sod_fin">
 
	<div id="sub_top_new_menu"> 
		<span><a href="/shop/mypage.php">마이페이지</a></span>
		<span><a href="/shop/orderinquiry.php">주문내역</a></span>
		<span class="ov"><a href="/shop/takeback.php">반품신청</a></span>
		<span><a href="/shop/wishlist.php">위시리스트</a></span>
		<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
		<span><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
		<span><a href="/shop/mylist.php">문의내역</a></span>
		<span><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
	</div>
	<!-- 상세검색 항목 시작 { -->
    <div id="seller_frm">
     <?
		$sell = sql_fetch("select * from {$g5['member_table']} where mb_v = '4' and mb_leave_date = '' and mb_id = '$od[com_id]' "); 
		switch($sell[mb_13]){
			case "1":$mb_13 = "제약";break;
			case "2":$mb_13 = "도매";break;
			case "3":$mb_13 = "의료기기";break;
			case "4":$mb_13 = "소모품";break;
		}
	 ?> 
		<h2>반품관리</h2>
		<p style="font-size:16px;">주문일 <strong><?php echo substr($od[od_time],0,10); ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;주문번호 <strong><?php echo $od_id; ?></strong></p>
		<div class="sell_box" style="margin-top:10px;">
			<div class="sell_box_left" style="padding-top:50px;height:80px;">
				공급사 정보				
			</div>
			<div class="sell_box_right">
				<p>
					<span><?=$sell[mb_nick]?></span><a href="/bbs/board.php?bo_table=0402"><img src="/img/board/qna_btn.png"></a>
				</p>
				<p>
					<? if($sell[mb_tel]){?><span>TEL</span><?=$sell[mb_tel]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
					<? if($sell[mb_2]){?><span>FAX</span><?=$sell[mb_2]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
					<? if($sell[mb_3]){?><span>담당자</span><?=$sell[mb_3]?><?}?>
				</p>
				<p>
					<? if($sell[mb_profile]){?><span>교환/반품</span><?=$sell[mb_profile]?><?}?>
				</p>
				<p>
					<? if($sell[mb_11]){?><span>배송비정책금액</span><?=($sell[mb_11]/10000)?>만원 이상 구매시 무료배송&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
					<? if($sell[mb_12]){?><span>배송비</span><?=number_format($sell[mb_12])?>원<?}?>
				</p>
			</div>			
		</div>
	 
    </div>
	<br><br> 
    <section id="sod_fin_list">
        <!-- <h2>주문하신 상품</h2> -->

        <?php
        $st_count1 = $st_count2 = 0;
        $custom_cancel = false;

        $sql = " select ct_id , it_id, it_name, ct_send_cost, it_sc_type
                    from {$g5['g5_shop_cart_table']}
                    where od_id = '$od_id'
                    group by it_id
                    order by ct_id ";
        $result = sql_query($sql);
        ?>

		<form name="fboardlist" id="fboardlist" action="./takeback_update.php" onsubmit="return fboardlist_submit(this);" method="post">
		 
			<input type="hidden" name="mode" value="B">
			<input type="hidden" name="od_id" value="<?=$od_id?>">
			<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
			<input type="hidden" name="wr_name" value="<?=$member['wr_name']?>">
			<input type="hidden" name="com_id" value="<?=$od[com_id]?>">

			

        <div class="tbl_head01 tbl_wrap">
            <table>
            <thead> 
            <tr>
				<th scope="col" ><input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)"></th>
				<th scope="col" >이미지</th> 
                <th scope="col" id="th_itopt">상품명</th>
				<th scope="col" >규격</th> 
				<th scope="col" >단위</th> 
                <th scope="col" id="th_itqty">수량</th>
                <th scope="col" id="th_itprice">판매가</th>
                <th scope="col" id="th_itsum">소계</th>
                <!-- <th scope="col" id="th_itpt">포인트</th> -->
                <th scope="col" id="th_itpt">배송비</th>
                <th scope="col" id="th_itst">상태</th>
				<th scope="col" id="th_itst">반품상태</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $image = get_it_image($row['it_id'], 70, 70);

                $sql = " select ct_id, it_name, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$od_id'
                              and it_id = '{$row['it_id']}'
                            order by io_type asc, ct_id asc ";
                $res = sql_query($sql);
                $rowspan = sql_num_rows($res) + 1;

                // 합계금액 계산
                $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                                SUM(ct_qty) as qty
                            from {$g5['g5_shop_cart_table']}
                            where it_id = '{$row['it_id']}'
                              and od_id = '$od_id' ";
                $sum = sql_fetch($sql); 

				$row2 = sql_fetch("select * from g5_shop_item where it_id = '$row[it_id]' ");
				$row3 = sql_fetch("select * from shop.g5_shop_item where it_id = '$row2[it_8]' ");

                // 배송비
                switch($row['ct_send_cost'])
                {
                    case 1:
                        $ct_send_cost = '착불';
                        break;
                    case 2:
                        $ct_send_cost = '무료';
                        break;
                    default:
                        $ct_send_cost = '선불';
                        break;
                }

                // 조건부무료
                if($row['it_sc_type'] == 2) {
                    $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $od_id);

                    if($sendcost == 0)
                        $ct_send_cost = '무료';
                }

                for($k=0; $opt=sql_fetch_array($res); $k++) {
                    if($opt['io_type'])
                        $opt_price = $opt['io_price'];
                    else
                        $opt_price = $opt['ct_price'] + $opt['io_price'];

                    $sell_price = $opt_price * $opt['ct_qty'];
                    $point = $opt['ct_point'] * $opt['ct_qty'];

                    if($k == 0) {


            ?>
          
            <?php } ?>
            <tr>
				<td class="td_mngsmall">
					<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					<input type="hidden" name="ct_id[<?php echo $i ?>]" value="<?php echo $row['ct_id'] ?>" id="ct_id_<?php echo $i ?>">
				</td>
				<td  class="td_imgsmall"><?php echo $image; ?></td>
                <td headers="th_itopt" style="text-align:center;"><a href="./item.php?it_id=<?php echo $row['it_id']; ?>"><?php echo $row['it_name']; ?></a></td>
				<td  class="td_numbig"><?=$row3['it_5']?></td>
				<td  class="td_numbig"><?=$row3['it_6']?></td>
                <td headers="th_itqty" class="td_mngsmall">
					<?//php echo number_format($opt['ct_qty']); ?>
					<select name="ct_qty[<?php echo $i ?>]">
						<? for($jj = 0 ; $jj <= $opt['ct_qty'] ; $jj++){?>
						<option value="<?=$jj?>"><?=$jj?></option>
						<? } ?>
					</select>
				</td>
                <td headers="th_itprice" class="td_numbig"><?php echo number_format($opt_price); ?></td>
                <td headers="th_itsum" class="td_numbig"><?php echo number_format($sell_price); ?></td>
                <!-- <td headers="th_itpt" class="td_num"><?php echo number_format($point); ?></td> -->
                <td headers="th_itpt" class="td_dvr"><?php echo $ct_send_cost; ?></td>
                <td headers="th_itst" class="td_mngsmall"><?php echo $opt['ct_status']; ?></td>
				<td headers="th_itst" class="td_mngsmall"> 
				<?
					$aacnt = sql_fetch("select status from shop.g5_takeback where ct_id = '{$row['ct_id']}' limit 1 ");
					echo $aacnt[status];
				?>
				</td>
            </tr>
            <?php
                    $tot_point       += $point;

                    $st_count1++;
                    if($opt['ct_status'] == '주문')
                        $st_count2++;
                }
            }

            // 주문 상품의 상태가 모두 주문이면 고객 취소 가능
            if($st_count1 > 0 && $st_count1 == $st_count2)
                $custom_cancel = true;
            ?>
            </tbody>
            </table>
        </div>
		
		<p class="b_title">반품신청 사유 | <span>반품사유를 입력해 주세요.</span></p>
		<input type="text" name="memo" value="" class="b_input" placeholder="상세 사유를 입력해주세요." required >

		<p class="b_title">※ 반품신청 전 꼭 확인해주세요!</p>
		<ul class="b_ullist">
			<li>· 단순변심으로 인한 반품은 <span style="color:#5574a0;">상단 공급사정보의 배송비</span>를참고하시어 <span style="color:red;">왕복택배비</span>를 상품과 함께 박스에 동봉하여 준비해주십시오. </li>
			<li>· 반품상태가 확인 이후가 되면 택배기사님이 방문하여 반품 제품을 회수 합니다.</li>
			<li>· 교환신청은 <a href="/bbs/board.php?bo_table=0402" style="text-decoration:underline;color:#5574a0;">고객센터</a> 로 문의해주세요.</li> 
		</ul>
		<div style="text-align:center;margin-top:30px;">
			<button type="buttom" value="취소" class="cencel" onclick="location.href='takeback.php';return false;">취소</button>
			<button type="submit" value="신청하기" class="b_bubmit" onclick="document.pressed=this.value">신청하기</button>
		</div>
      <style>
	  	.b_title { font-size:16px;font-weight:bold;margin-top:30px;}
		.b_title span {font-size:14px;font-weight:normal;}
		.b_input { width: 1065px;height: 38px;border: 1px solid #d7d7d7;margin-top:15px; }
		.b_ullist { position:relative;left:20px;line-height:2em;font-size:14px;}
		.cencel { display: inline-block;width: 140px;height: 47px;line-height: 50px;background: #f3f7f8;border: 1px solid #d7d7d7;text-align:center;font-size:14px;}
		.b_bubmit  { display: inline-block;width: 140px;height: 50px;line-height: 50px;background: #5574a0;border: 1px solid #d7d7d7;color:#fff;font-size:14px;}
	  </style>
	</form>
</div>
<!-- } 주문상세내역 끝 -->

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}
function is_checked(elements_name)
{
    var checked = false;
    var chk = document.getElementsByName(elements_name);
    for (var i=0; i<chk.length; i++) {
        if (chk[i].checked) {
            checked = true;
        }
    }
    return checked;
} 
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    } 
	if(document.pressed == "신청하기") {
		f.mode.value = "B";
        if(!confirm("반품신청 하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<script>
$(function() {
    $("#sod_sts_explan_open").on("click", function() {
        var $explan = $("#sod_sts_explan");
        if($explan.is(":animated"))
            return false;

        if($explan.is(":visible")) {
            $explan.slideUp(200);
            $("#sod_sts_explan_open").text("상태설명보기");
        } else {
            $explan.slideDown(200);
            $("#sod_sts_explan_open").text("상태설명닫기");
        }
    });

    $("#sod_sts_explan_close").on("click", function() {
        var $explan = $("#sod_sts_explan");
        if($explan.is(":animated"))
            return false;

        $explan.slideUp(200);
        $("#sod_sts_explan_open").text("상태설명보기");
    });
});

function fcancel_check(f)
{
    if(!confirm("주문을 정말 취소하시겠습니까?"))
        return false;

    var memo = f.cancel_memo.value;
    if(memo == "") {
        alert("취소사유를 입력해 주십시오.");
        return false;
    }

    return true;
}
</script>

<?php
include_once('./_tail.php');
?>