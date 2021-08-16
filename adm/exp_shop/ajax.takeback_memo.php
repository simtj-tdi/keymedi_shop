<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$od_id = $_POST['od_id'];

$sql = " select * from shop.g5_takeback where od_id = '$od_id' ";
$od = sql_fetch($sql);

if(!$od['od_id'])
    die('<div>주문정보가 존재하지 않습니다.</div>');

 
?>

<section id="cart_list">
    <h2 class="h2_frm">반품요청사유</h2>

    <div class="tbl_head01 tbl_wrap">
        
		<table style="margin-top:20px;">
			<tr>
				<th>전달메세지</th>
			</tr>
			<tr>
				<td><?=$od[memo]?></td>
			</tr>
		</table>

    </div>
</section>