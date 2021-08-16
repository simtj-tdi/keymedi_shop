<?php
include_once('./_common.php');
//auth_check($auth[$sub_menu], "r");
 $tmp_od_id = explode(",",$_GET[od_id]);
?>
<!doctype html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>거래명세표</title>
<style>
	td {font-size:9pt;font-family:gulim;line-height:1.2em;}
	.acount_boxs { position:relative;width:700px;height:1094px;}
</style>
</head>
<body>

	<button onclick="go_print('1');">공급자 보관용 인쇄</button> <button onclick="go_print('2');">공급받는자 보관용 인쇄</button>


<?
for ($t=0; $t<count($tmp_od_id); $t++)
{ 
    $od_id = $tmp_od_id[$t];
	
	$sql = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'";
	$od = sql_fetch($sql);

	$to_men = get_member($od[com_id]);

	//  mb_6 // mb_7

	IF (!$od['od_id']){alert("해당 주문번호로 주문서가 존재하지 않습니다.");}
	
	if(substr($od['od_id'],0,2) == "20"){
		$form_men = get_member($od['mb_id']);
	}else{
		$form_men = sql_fetch("select * from portal.g5_member where mb_id = '{$od['mb_id']}' " );
	}

	//print_r($form_men);

	$sql = "SELECT * FROM {$g5['g5_shop_cart_table']} ";
	$sql .= "WHERE od_id = '$od_id' and ct_status != '취소' GROUP BY it_id ORDER BY ct_id";
	$result = sql_query($sql);
	$loop_n = 0;
	$price_tot = 0;
	$price_sum = 0;

?>
<div style="margin:30px auto;" class="acount_boxs">

	<table border="0" cellpadding="0" cellspacing="0" width="700">
		<colgroup>
			<col style="width:25px;">
			<col style="width:40px;">
			<col style="width:10px;">
			<col style="width:70px;">
			<col style="width:20px;">
			<col style="width:40px;">
			<col style="width:20px;">
			<col style="width:10px;">
			<col style="width:50px;">
			<col style="width:20px;">
			<col style="width:20px;">
			<col style="width:25px;">
			<col style="width:30px;">
			<col style="width:20px;">
			<col style="width:50px;">
			<col style="width:10px;">
			<col style="width:10px;">
			<col style="width:20px;">
			<col style="width:40px;">
			<col style="width:20px;">
			<col style="width:20px;">
			<col style="width:30px;">
			<col style="width:80px;">
		</colgroup>
		
		<tr height="6">
			<td colspan="7"></td>
			<td rowspan="3"></td>
			<td style="padding:6px;font-size:14pt;font-weight:bold;text-align:center;border-radius:6px;border:1px solid blue;background:#f4fbff; color:#1211ff;font-size:10pt;font-weight:bold;text-align:center;" colspan="8" rowspan="2">거 래 명 세 표</td>
			<td rowspan="3"></td>
			<td colspan="6"></td>
		</tr>
		<tr height="22">
			<td style="border-top:2px solid blue;border-right:1px solid blue;border-left:2px solid blue;background:#dbf4ff; color:#1211ff;font-size:10pt;text-align:center;" colspan="2" rowspan="2">일자</td>
			<td style="border-top:2px solid blue;border-right:2px solid blue;background:#f4fbff; color:#222;font-size:10pt;text-align:left;padding-left:6px;" colspan="5" rowspan="2"><?php echo date('Y년 m월 d일', strtotime($od['od_time'])); ?></td>
			<td style="color:#1211ff;font-size:9pt;text-align:right;"  colspan="6" rowspan="2"><!-- (공급자 보관용) --></td>
		</tr>
		<tr height="4">
			<td colspan="8"></td>
		</tr>
		<tr>
			<td style="border-top:2px solid blue;border-right:1px solid blue;border-bottom:1px solid blue;border-left:2px solid blue;background:#dbf4ff; color:#1211ff;font-size:10pt;text-align:center;" rowspan="4">공<br>급<br>자</td>
			<td style="height:32px;border-top:2px solid blue;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">등록<br>번호</td>
			<td style="height:32px;border-top:2px solid blue;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:11pt;text-align:left;padding-left:6px;" colspan="9"><strong><?php echo $to_men['mb_1']; ?></strong></td>
			<td style="border-top:2px solid blue;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;" rowspan="4">공<br>급<br>받<br>는<br>자</td>
			<td style="height:32px;border-top:2px solid blue;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">등록<br>번호</td>
			<td style="height:32px;border-top:2px solid blue;border-right:2px solid blue;border-bottom:1px solid blue; color:#222;font-size:11pt;text-align:left;padding-left:6px;" colspan="10"><strong><?php echo $form_men['mb_15']; ?></strong></td>
		</tr>
		<tr>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">상호</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:10pt;text-align:left;padding-left:6px;" colspan="4"><?php echo $to_men['mb_nick']; ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">성<br>명</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:10pt;text-align:left;padding-left:6px;" colspan="4"><?php echo $to_men['mb_4']; ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">상호</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="6"><?php echo get_text($form_men['mb_11']); ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">성<br>명</td>
			<td style="height:32px;border-right:2px solid blue;border-bottom:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="3"><?php echo get_text($form_men['mb_name']); ?></td>
		</tr>
		<tr>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">주소</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="9"><?php echo $to_men['mb_addr1']; ?> <?php echo $to_men['mb_addr2']; ?> </td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">주소</td>
			<td style="height:32px;border-right:2px solid blue;border-bottom:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="10"><?php echo get_text($form_men['mb_6']); ?> <?php echo get_text($form_men['mb_7']); ?></td>
		</tr>
		<tr>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">업태</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:10pt;text-align:left;padding-left:6px;" colspan="2"><?php echo get_text($to_men['mb_6']); ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#dbf4ff; color:#1211ff;font-size:9pt;text-align:center;">TEL</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue;background:#f4fbff; color:#222;font-size:10pt;text-align:left;padding-left:6px;" colspan="6"><?php echo get_text($to_men['mb_tel']); ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">업태</td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="4"><?php echo get_text($mem['mb_5']); ?></td>
			<td style="height:32px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:10pt;text-align:center;">TEL</td>
			<td style="height:32px;border-right:2px solid blue;border-bottom:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="5"><?php echo get_text($form_men['mb_8']); ?></td>
		</tr>
		<tr>
			<td style="height:22px;border-bottom:1px solid blue;border-left:2px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="2">제조사</td>
			<td style="height:22px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="9">품 &nbsp; 목 &nbsp; / &nbsp; 규 &nbsp; 격</td>
			<td style="height:22px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="2">단위</td>
			<td style="height:22px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="2">수량</td>
			<td style="height:22px;border-right:1px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="4">단가</td>
			<td style="height:22px;border-right:2px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="4">금액</td>
			<!-- <td style="height:22px;border-right:2px solid blue;border-bottom:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;">세액</td> -->
		</tr>
		<?php FOR ($i=0; $row=sql_fetch_array($result); $i++){
			$loop_grp = floor($loop_n/5);
			$bg_class = floor($loop_grp%2) == 1 ? ' background:#ebf8ff;' : '';
			$loop_n++;
			$select_date = date('m/d', strtotime($row['ct_select_time']));
			
			$it_name = get_text(stripslashes($row['it_name']));
			
			
			$sql = "SELECT it_name , it_maker , it_5 , it_6 from {$g5['g5_shop_item_table']} where it_id = (SELECT it_8 FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$row['it_id']}')  ";
			//$sql .= "WHERE it_id = '{$row['it_id']}'";

			$res = sql_fetch($sql);


			IF ( $row['io_type'] ){
				$opt_price = $row['io_price'];
			}ELSE{
				$opt_price = $row['ct_price'] + $row['io_price'];
			}
			$sell_price = $opt_price * $row['ct_qty'];
			$tex_n = floor($sell_price/11);
			$price_tot += ($sell_price-$tex_n);
			$price_sum += $sell_price;
			?>
		<tr>
			<td style="padding:2px;height:24px;border-left:2px solid blue; color:#222;font-size:9pt;text-align:center;padding-left:6px;<?php echo $bg_class; ?>" colspan="2"><?//php echo $select_date; ?><? echo $res[it_maker];?></td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;<?php echo $bg_class; ?>" colspan="9"><?php echo get_text(stripslashes($res['it_name'])); ?> / <?=$res['it_5']?></td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;<?php echo $bg_class; ?>" colspan="2"><?php echo $res['it_6']; ?></td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="2"><?php echo number_format($row['ct_qty']); ?></td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="4"><?php echo number_format($row['ct_price']); ?></td>
			<td style="padding:2px;height:24px;border-right:2px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="4"><?php echo number_format($sell_price); ?></td>
			<!-- <td style="padding:2px;height:24px;border-right:2px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>"><?php echo number_format($tex_n); ?></td> -->
		</tr>
		<?php }?>
		<? 				
			$sql_m = "select mb_11 , mb_12 from {$g5['member_table']} where mb_id = '$od[com_id]' ";
			$res_m = sql_fetch($sql_m); 

			if($price_sum >= $res_m[mb_11] ){
				$total_send_cost = 0;
			}else{
				$total_send_cost = $res_m[mb_12];
			} 
		?>
		<?// if($total_send_cost > 0){ ?>
		<? if($od['od_send_cost'] > 0){ ?>

		<tr>
			<td style="padding:2px;height:24px;border-left:2px solid blue; color:#222;font-size:9pt;text-align:center;padding-left:6px;<?php echo $bg_class; ?>" colspan="2">-</td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;<?php echo $bg_class; ?>" colspan="9">배송비</td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;<?php echo $bg_class; ?>" colspan="2">-</td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="2">-</td>
			<td style="padding:2px;height:24px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="4">-</td>
            <td style="padding:2px;height:24px;border-right:2px solid blue; color:#222;font-size:9pt;text-align:right;padding-left:6px;<?php echo $bg_class; ?>" colspan="4">
                <?php
                // 20210615 simtj 배송비:배송정보에 있는 값으로 수정
                // echo number_format($total_send_cost);
                echo number_format($od['od_send_cost']);
                ?>
            </td>
		</tr>

		<? } ?>
		<?
		
			//$price_sum = $price_sum + $total_send_cost;
			$price_sum = $price_sum + $od['od_send_cost'];
			$price_sum_tex = floor($price_sum/11);
			$price_tot = ($price_sum-$price_sum_tex);
		?>
		<tr>
			<td style="padding:2px;border-top:1px solid blue;border-right:1px solid blue;padding:4px;border-left:2px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="2">공급<br>가액</td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="2"><?php echo number_format($price_tot); ?></td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;">세<br>액</td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="4"><?php echo number_format(floor($price_sum_tex)); ?></td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;">합<br>계</td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="4"><?php echo number_format($price_sum); ?></td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;">미수금</td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="4"><?php echo number_format($od['od_misu']); ?></td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:1px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="2">인수자</td>
			<td style="padding:2px;border-top:1px solid blue;padding:4px;border-right:2px solid blue; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="2"></td>
			</tr>
            <tr><td style="padding:2px;height:24px;border-top:1px solid blue;border-bottom:2px solid blue;border-left:2px solid blue; color:#1211ff;font-size:9pt;text-align:center;" colspan="3">참고사항</td>
			    <td style="padding:2px;height:24px;border-top:1px solid blue;border-right:2px solid blue;border-bottom:2px solid blue;padding:4px; color:#222;font-size:9pt;text-align:left;padding-left:6px;" colspan="20">
		            <?php echo get_text($od['od_memo']); ?>
			    </td>
		    </tr>
	    </table>
	<p style="color:red;font-size:12px;">* 파손/유효기간짧음/수량누락/오배송 등 발생 시 (070-4006-3891)으로 문의바랍니다.</p>
    <?php
    if($od[od_b_addr1] != $form_men[mb_6]){
        ?>
        <p></p>
        <p></p>
        <p></p>
        <p><strong>※ 배송지 변경 정보</strong></p>
        <p style="color:red;font-size:12px;">주문자명: <?=$od['od_b_name']?>   전화번호: <?=$od['od_b_tel']?>  휴대폰: <?=$od['od_b_hp']?></p>
        <p style="color:red;font-size:12px;">배송지 주소 : <?=$od['od_b_addr1']."&nbsp;".$od['od_b_addr2']?></p>
        <?
    }
    ?>
</div>
<? } ?>

<script>
function go_print(tmp){
	if(tmp == "1"){
		document.location.href = "./item_account_p1.php?od_id=<?=$_GET[od_id]?>";
	}else if(tmp == "2"){
		document.location.href = "./item_account_p2.php?od_id=<?=$_GET[od_id]?>";
	}else{
		alert("잘못된요청입니다.");
	}
}
 
</script>
</body>
</html>