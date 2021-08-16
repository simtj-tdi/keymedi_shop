<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$od_id = $_POST['od_id'];
$od_id_tmp = substr($od_id,0,2);
$sqld = sql_fetch("select code from site_code where pay_code = '$od_id_tmp' and exp = 'yes' ");

if($sqld['code']){
	$site_fix = $sqld[code].".";
}else{
	$site_fix ="";
}

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

if(!$od['od_id'])
    die('<div>주문정보가 존재하지 않습니다.</div>');

// 상품목록

if(substr($od['od_id'],0,2) == "20"){
    $form_men = get_member($od['mb_id']);
}else{
    $form_men = sql_fetch("select * from portal.g5_member where mb_id = '{$od['mb_id']}' " );
}

/*$msql = " select * from {$g5['member_table']} where mb_id = '{$od['mb_id']}' ";
$minfo = sql_fetch($msql);*/

$sql = " select it_id,
                it_name,
                cp_price,
                ct_notax,
                ct_send_cost,
                it_sc_type,
                ct_option
           from {$site_fix}{$g5['g5_shop_cart_table']}
          where od_id = '$od_id'
          group by it_id
          order by ct_id ";

$result = sql_query($sql);
?>

<section id="cart_list">
    <h2 class="h2_frm">주문상품 목록</h2>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>주문 상품 목록</caption>
        <thead>
        <tr>
			<th scope="col">공급사</th>
			
            <th scope="col">상품명</th>
            <th scope="col">규격</th>
            <th scope="col">단위</th>
			<th scope="col">제조사</th>
            <!-- <th scope="col">옵션항목</th> -->
            <th scope="col">상태</th>
            <th scope="col">수량</th>
            <th scope="col">판매가</th>
            <th scope="col">소계</th>
            <th scope="col">쿠폰</th>
            <th scope="col">포인트</th>
            <th scope="col">배송비</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
            // 상품이미지
            $image = get_it_image($row['it_id'], 50, 50);

            // 상품의 옵션정보
            $sql = " select ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price, io_id
                        from {$site_fix}{$g5['g5_shop_cart_table']}
                        where od_id = '$od_id'
                          and it_id = '{$row['it_id']}'
                        order by io_type asc, ct_id asc ";
            $res = sql_query($sql);
            $rowspan = sql_num_rows($res);

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
                
                // 합계금액 계산
                $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                           SUM(ct_qty) as qty
                           from {$site_fix}{$g5['g5_shop_cart_table']}
                          where it_id = '{$row['it_id']}'
                         and od_id = '$od_id' ";
                $sum = sql_fetch($sql);

                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $od_id);

                if($sendcost == 0)
                    $ct_send_cost = '무료';

                $save_it_id = $row['it_id'];
            }

            for($k=0; $opt=sql_fetch_array($res); $k++) {
                if($opt['io_type'])
                    $opt_price = $opt['io_price'];
//                    $opt_price = $opt['io_price'];
                else
                    $opt_price = $opt['ct_price'];
//                    $opt_price = $opt['ct_price'] + $opt['io_price'];

                // 소계
                $_option_price = $opt['io_price'];
                $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                $ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
            ?>
			<?

			$sqld = "select it_10 , it_8 from {$site_fix}{$g5['g5_shop_item_table']} where  it_id = '$row[it_id]'";
			$it_10 = sql_fetch($sqld);
			$tom = get_member($it_10[it_10]);
			

			$sqldd = "select it_maker , it_5 , it_6 from {$site_fix}{$g5['g5_shop_item_table']} where  it_id = '$it_10[it_8]'";

			$it_maker  = sql_fetch($sqldd);
			?>
            <tr>
				<td class="td_itopt_tl"><?=$tom[mb_nick]?></td>
				
                <?php if($k == 0) { ?>
                <td class="td_itname" rowspan="<?php echo $rowspan; ?>">
                    <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                    <?php if($od['od_tax_flag'] && $row['ct_notax']) echo '[비과세상품]'; ?>
                </td>
                <?php } ?>
                <!-- <td class="td_itopt_tl">
                    <?php echo $opt['ct_option']; ?>
                </td> -->
				<td class="td_num"><?=$it_maker[it_5]?></td>
				<td class="td_num"><?=$it_maker[it_6]?></td>
				<td class="td_num"><?=$it_maker[it_maker]?></td>

                <td class="td_mngsmall"><?php echo $opt['ct_status']; ?></td>
                <td class="td_cntsmall"><?php echo $opt['ct_qty']; ?></td>
                <td class="td_num"><?php echo number_format($opt_price); ?></td>
                <td class="td_num"><?php echo number_format($ct_price['stotal']); ?></td>
                <td class="td_num"><?php echo number_format($opt['cp_price']); ?></td>
                <td class="td_num"><?php echo number_format($ct_point['stotal']); ?></td>
                <td class="td_sendcost_by"><?php echo $ct_send_cost; ?></td>
            </tr>


                <?
                if($opt['ct_option'] && $opt['io_id']){
                    if($opt['io_type'])
                        $opt_price = $opt['io_price'];
                    else
                        $opt_price = $opt['ct_price'] + $opt['io_price'];
                    ?>
                    <tr>
                        <td colspan="2">옵션정보</td>
                        <td colspan="5">옵션명 : <?php echo get_text($opt['ct_option']); ?></td>
                        <td colspan="5">옵션가 : <?php echo number_format($_option_price); ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        <?php
        }
        ?>
        </tbody>
        </table>
		<?php if($od[od_memo] != ""){ ?>
		<table style="margin-top:20px;">
			<tr>
				<th>전달메세지</th>
			</tr>
			<tr>
				<td><?=$od[od_memo]?></td>
			</tr>
		</table>
		<? } ?>
        <?php if($od[od_b_addr1] != $form_men[mb_6]){ ?>
            <table style="margin-top:20px;">
                <tr>
                    <th>배송지 변경요청 주소</th>
                </tr>
                <tr>
                    <td>
                        전화번호 : <?=$od['od_b_tel']?><br>
                        휴대폰번호 : <?=$od['od_b_hp']?><br>
                        배송지이름 : <?=$od['od_b_name']?><br>
                        우편번호 : <?=$od['od_b_zip1'].$od['od_b_zip2']?><br>
                        주소 : <?=$od['od_b_addr1']."&nbsp;".$od['od_b_addr2']?>
                    </td>
                </tr>
            </table>
        <? } ?>
    </div>
</section>