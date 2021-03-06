<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_")) exit; // 개별 페이지 접근 불가

// 테마에 orderinquiry.sub.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiry_file = G5_THEME_SHOP_PATH.'/orderinquiry.sub.php';
    if(is_file($theme_inquiry_file)) {
        include_once($theme_inquiry_file);
        return;
        unset($theme_inquiry_file);
    }
}

if($sdate){
	 $sql_where .= " and left(od_time,10) >= '$sdate' ";
}
if($edate){
	 $sql_where .= " and left(od_time,10) <= '$edate' ";
}
if($od_status){
	$sql_where .= " and od_status = '$od_status' ";
}
if($com_id){
	$sql_where .= " and com_id = '$com_id' ";
}

?>

<!-- 주문 내역 목록 시작 { -->
<?php if (!$limit) { ?>총 <?php echo $cnt; ?> 건<?php } ?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
		<th scope="col">번호</th>
        <th scope="col">주문서번호</th>
		<th scope="col">공급사명</th>
        <th scope="col">주문일시</th>
        <th scope="col">상품수</th>
        <th scope="col">주문금액</th>
		<th scope="col">쿠폰</th>
		<th scope="col"><?=$point_txt?></th>
        <th scope="col">주문금액</th>
        <th scope="col">미입금액</th>
        <th scope="col">상태</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = " select * , (select sum(cp_price) from g5_shop_cart c where c.od_id = m.od_id ) as od_cart_coupon2 
               from {$g5['g5_shop_order_table']} m 
              where mb_id = '{$member['mb_id']}' {$sql_where}  
              order by od_id desc
              $limit ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

        switch($row['od_status']) {
            case '주문':
                $od_status = '입금확인중';
                break;
            case '입금':
                $od_status = '입금완료';
                break;
            case '준비':
                $od_status = '상품준비중';
                break;
            case '배송':
                $od_status = '상품배송';
                break;
            case '완료':
                $od_status = '배송완료';
                break;
            default:
                $od_status = '주문취소';
                break;
        }

		$mem = sql_fetch("select * from shop.g5_member where mb_id = '{$row['com_id']}' ");
		$total_pay = $total_pay + $row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2'];
 
    ?>

    <tr>
		<td class="td_num"><?=$i+1?></td>
        <td style="text-align:center;">
            <input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
            <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a>
        </td>
		<td class="td_numbig"><?=$mem['mb_nick']?></td>
        <td style="text-align:center;"><?php echo substr($row['od_time'],2,14); ?> </td>
        <td class="td_num"><?php echo $row['od_cart_count']; ?></td>
        <td class="td_numbig"><?php echo display_price($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></td>
        <td class="td_num"><?php echo display_price($row['od_coupon']+$row['od_cart_coupon2']); ?></td>
		<td class="td_num"><?php echo display_price($row['od_receipt_point']); ?></td>
		<td class="td_numbig"><?php echo display_price($row['od_receipt_price']-$row['od_coupon']-$row['od_cart_coupon2']-$row['od_receipt_point']); ?></td>
        <td class="td_num"><?php echo display_price($row['od_misu']); ?></td>
        <td class="td_numbig"><?php echo $od_status; ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="11" class="empty_table">주문 내역이 없습니다.</td></tr>';
    ?>
	<tr>
		<td colspan="6" style="background:#f0f1f3;font-size:16px;">총 <?=$i?>건</td>
		<td colspan="5" style="background:#f0f1f3;font-size:16px;" align="right">주문 금액 합계 : <?=display_price($total_pay)?></td>
	</tr>
    </tbody>
    </table>
</div>
<!-- } 주문 내역 목록 끝 -->