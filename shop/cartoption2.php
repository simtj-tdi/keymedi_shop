<?php
include_once('./_common.php');

$pattern = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]#';
$it_id  = preg_replace($pattern, '', $_POST['it_id']);

$sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' and it_use = '1' ";
$it = sql_fetch($sql);
$it_point = get_item_point($it);

$row_v = sql_fetch("select * from shop.{$g5['g5_shop_item_table']} where it_id = '$it[it_8]' ");

if(!$it['it_id'])
    die('no-item');


$sql_list = "select * from {$g5['g5_shop_item_table']} where it_8 = '{$it['it_8']}' and it_use = '1' and it_stock_qty > 0 order by it_price asc";
$res_list = sql_query($sql_list);


$cart_id = get_session('ss_cart_id');
$it2 = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by io_type asc, ct_id asc ");
//echo " select * from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by io_type asc, ct_id asc ";
/*
// 장바구니 자료
$cart_id = get_session('ss_cart_id');
$sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by io_type asc, ct_id asc ";
$result = sql_query($sql);

// 판매가격
$sql2 = " select ct_price, it_name, ct_send_cost from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by ct_id asc limit 1 ";
$row2 = sql_fetch($sql2);

if(!sql_num_rows($result))
    die('no-cart');
*/

?>
<style>


#sit_opt_added {margin:0;padding:0;border:1px solid #e9e9e9;border-bottom:0;background:#fff;list-style:none;font-size:14px;}
#sit_opt_added li {padding:10px 20px;border-bottom:1px solid #e9e9e9}
#sit_opt_added li div {margin:5px 0 0;text-align:right}
#sit_opt_added button {margin:0 0 0 1px}

#sod_bsk_list {position:relative}
#sod_bsk_list #mod_option_frm2 {z-index:10000;position:absolute;top:0;left:99px;padding:20px;width:500px;height:auto !important;height:500px;max-height:500px;border:1px solid #000;background:#f2f5f9;overflow-y:scroll;overflow-x:none}

#sod_bsk_tot {margin:0 0 20px;padding:10px;border:1px solid #e9e9e9;background:#f2f5f9;zoom:1}
#sod_bsk_tot:after {display:block;visibility:hidden;clear:both;content:""}
#sod_bsk_tot dt, #sod_bsk_tot dd {float:left;padding:12px 0;border-bottom:1px solid #e9e9e9}
#sod_bsk_tot dt {padding-left:2%;width:48%;font-weight:bold}
#sod_bsk_tot dd {margin:0;padding-right:2%;width:47%;text-align:right}
.sod_bsk_cnt {background:#ff3061;color:#fff}
.sod_bsk_point {border-bottom:0 !important}

#sod_bsk_act {text-align:center}
#sod_bsk_act p {margin:0 0 10px}
.tbl_head01 thead th {font-size:1.1em;}
</style>
<!-- 장바구니 옵션 시작 { -->
<form name="foption" id="foption" method="post" action="<?php echo G5_SHOP_URL; ?>/cartupdate.php" onsubmit="return formcheck(this);">
<input type="hidden" name="act" value="seldelete2">

<input type="hidden" name="ct_id" value="<?php echo $it2['ct_id']; ?>">

<input type="hidden" name="it_id[]" value="<?php echo $it['it_id']; ?>" id="it_id">
<input type="hidden" id="it_price" value="<?php echo $row2['ct_price']; ?>">


<div id="ct_qty_1">
	<input type="hidden" name="ct_qty[<?php echo $it['it_id']; ?>][]" value="<?php echo $it2['ct_qty']; ?>" >
	<input type="hidden" name="io_type[<?php echo $it['it_id']; ?>][]" value="0">
	<input type="hidden" name="io_id[<?php echo $it['it_id']; ?>][]" value="0">
	<input type="hidden" name="io_value[<?php echo $it['it_id']; ?>][]" value="<?php echo $it2['ct_option']; ?>">
</div>

<input type="hidden" name="ct_send_cost" value="<?php echo $row2['ct_send_cost']; ?>">
<input type="hidden" name="sw_direct">
  
<div id="sit_sel_option">
	<h4>공급사를 통일하면 배송비가 절약됩니다.</h4><br>
    <ul id="sit_opt_added">
		<li class="sit_<?php echo $cls; ?>_list">

		<h4><?php echo $row_v['it_name']; ?></h4><br>

	<table class="write_tb">
		<thead>
			<tr>
				 
				<th style="text-align:left;text-indent:15px;" colspan="2">공급사</th>
				<th width="80" style="color:#989898;">공급가</th>
				<th width="80" style="color:#989898;">재고</th>
			</tr>
		</thead>
		<body>
		<? while($row = sql_fetch_array($res_list)){ 
			$mem = sql_fetch("select mb_nick from g5_member where mb_id = '{$row[it_10]}' ");
		$cp_count = 0;
		$sqld = " select cp_id
					from {$g5['g5_shop_coupon_table']}
					where mb_id IN ( '{$member['mb_id']}', '{$member['mb_where']}','전체회원' )
					  and cp_start <= '".G5_TIME_YMD."'
					  and cp_end >= '".G5_TIME_YMD."' 
					  and (
							( cp_method = '0' and cp_target = '{$row['it_id']}' )
							OR
							( cp_method = '2' and (cp_target = '{$row['it_10']}' ) ) 
						  ) ";
		$resd = sql_query($sqld);

		for($k=0; $cp=sql_fetch_array($resd); $k++) {
			 
			if(is_used_coupon($member['mb_id'], $cp['cp_id']))
				continue;

			$cp_count++;
		}
		 
		
		
		
		
		?>
				<tr>
					<td width="50" style="color:#989898;"><input type="radio" name="ck[]" value="<?=$row[it_id]?>" onclick="viewitem_tmp('<?=$row[it_id]?>','<?=$it2[ct_qty]?>');" <?=($it_id == $row[it_id])?"checked":""?>  style="color:#989898;"/></td>
					<td ><?if($cp_count>0 && substr($it['ca_id'],0,2) != "20" ){?><img src="/shop/img/icon_cp.gif"><?}?><a href="#" onclick="viewitem('<?=$row[it_id]?>');" style="color:#989898;"><?=$mem[mb_nick]?></a></td>
					<td style="color:#989898;"><?=number_format($row[it_price])?></td>
					<td style="color:#989898;"><?=number_format($row[it_stock_qty])?></td>
				</tr>
		<? } ?>
			</body>
		</table>
		</li>
	</ul>
</div>
<br>
<div class="btn_confirm">
    <input type="submit" value="공급사변경" class="btn_submit">
    <button type="button" id="mod_option_close2" class="btn_cancel">닫기</button>
</div>
</form>

<script>
function viewitem_tmp(it_id,ct_qty){
	var tmp ="<input type='hidden' name='ct_qty["+it_id+"][]' value='"+ct_qty+"'>";
	tmp +="<input type='hidden' name='io_type["+it_id+"][]' value='0'>";
	tmp +="<input type='hidden' name='io_id["+it_id+"][]' value='0'>";
	tmp +="<input type='hidden' name='io_value["+it_id+"][]' value='<?php echo $it2['ct_option']; ?>'>";


	$("#it_id").val(it_id); 
	$("#ct_qty_1").html(tmp);
}
function formcheck(f)
{

    return true;
}
</script>
<!-- } 장바구니 옵션 끝 -->