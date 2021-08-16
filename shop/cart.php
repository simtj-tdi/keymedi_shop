<?php
include_once('./_common.php');
include_once(G5_SHOP_PATH.'/settle_naverpay.inc.php');

// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);

$s_cart_id = get_session('ss_cart_id');
// 선택필드 초기화
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
sql_query($sql);

$cart_action_url = G5_SHOP_URL.'/cartupdate.php';

$cart_action_url = str_replace("http://","https://",$cart_action_url);
if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/cart.php');
    return;
}

// 테마에 cart.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_cart_file = G5_THEME_SHOP_PATH.'/cart.php';
    if(is_file($theme_cart_file)) {
        include_once($theme_cart_file);
        return;
        unset($theme_cart_file);
    }
}

$g5['title'] = '장바구니';
include_once('./_head.php');
?>
<style>
.sod_option_btn2 { position:relative;}
.sod_option_btn2 .mod_options2 {margin:0;padding:3px 10px;/*width:100%;*/border:0;background:#79c2e2;color:#fff;cursor:pointer}
</style>
<!-- 장바구니 시작 { -->
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<div id="st_title">장바구니</div>
<div id="carttop_bg">
	<div class="step1 ov">Step1<br>장바구니</div>
	<div class="step2">Step2<br>결제진행</div>
	<div class="step3">Step3<br>주문완료</div>
</div>
<div id="sod_bsk">
	<div style="margin-bottom:10px;height:auto;overflow:hidden;">
		<div style="position:relative;width:80%;float:left;">
		   <button type="button" class="btn_submit allchecks">전체선택</button>
		   <button type="button" onclick="return form_check('seldelete');" class="btn01">선택삭제</button>
            <span id="view_text"></span>
	   </div>
        <div style="position:relative;width:20%;float:left;text-align:right;font-size:14px;">
			<?=$point_txt?>	<span style="color:#01A3E4;font-size:20px;"><?=number_format(get_point_sum( $member['mb_id']))?></span> 점
			
	   </div>
	</div>

    <form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">
 <?
	$full_cnt = sql_fetch("select count(*) as cnt from ( select count(*) as cnt from {$g5['g5_shop_cart_table']} a WHERE a.od_id = '$s_cart_id' group by a.com_id  order by a.com_id ) m "); 

	$sql_ar = "select com_id from {$g5['g5_shop_cart_table']} a WHERE a.od_id = '$s_cart_id' group by a.com_id  order by a.com_id ";
	$res_ar = sql_query($sql_ar);
	$i = 0;

	$full_tot_sell_price = 0;
	$full_send_cost = 0;

	while($ar = sql_fetch_array($res_ar)) {
?>
	<div class="tbl_head01 tbl_wrap" style="margin:0;">


        <table>
        <thead>
        <tr>
			 <th scope="col">
			<? if($i == 0){?>
                <label for="ct_all" class="sound_only">상품 전체</label>선택
                <!-- <input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked"> -->
			<? } ?>
            </th>
			<th scope="col" width="140">공급사</th>
            <th scope="col" width="180">상품이미지</th>
            <th scope="col">상품명</th>
			<!-- <th scope="col" >규격</th> 
			<th scope="col" >단위</th> -->

            <th scope="col" width="100">총수량</th>
            <th scope="col">판매가</th>
            <th scope="col">소계</th>
            <!-- <th scope="col">포인트</th> -->
            <th scope="col" width="100">배송비</th>
           
        </tr>
        </thead>
        <tbody>
        <?php
        $tot_point = 0;
        $tot_sell_price = 0;


        // $s_cart_id 로 현재 장바구니 자료 쿼리
        $sql = " select 
						(select mb_nick from g5_member where mb_id = b.it_10 ) as mb_nick ,
						a.ct_id,
                        a.it_id,
						b.it_8,
						b.it_5,
						b.it_6,
						b.it_tax,
                        a.it_name,
                        a.ct_price,
                        a.ct_point,
                        a.ct_qty,
                        a.ct_status,
                        a.ct_send_cost,
                        a.it_sc_type,
						a.com_od_id,
                        b.ca_id,
                        b.ca_id2,
                        b.ca_id3,
                        a.ct_option,
                        a.io_id,
                        a.io_price,
                        b.it_price
                   from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
                  where a.od_id = '$s_cart_id' and a.com_id = '$ar[com_id]' ";
        $sql .= " group by a.it_id ";
        $sql .= " order by a.ct_id ";
		//echo $sql;
        $result = sql_query($sql);
		
        $it_send_cost = 0;

        $_alert_chk = "";
        $_alert_txt = "";

        for ($i=$i; $row=sql_fetch_array($result); $i++)
        {

            if (isset($_new_normal_category[$row['ca_id']])) $_res_n_goods = $_res_n_goods + $_n_goods_count += 1;
            if (isset($_new_medical_category[$row['ca_id']])) $_res_m_goods = $_res_m_goods + $_m_goods_count += 1;

            if($row['ct_price']!=$row['it_price']){

                $_price_sql = "update {$g5['g5_shop_cart_table']} set ct_price='{$row['it_price']}' WHERE ct_id = '{$row['ct_id']}'";
                $_price_res = sql_query($_price_sql);

                if($_price_res){
                    $_alert_chk = "Y";
                    $_alert_res_chk = $_alert_chk;
                    $_alert_txt .= $row['it_name']."의 가격이 ".number_format($row['ct_price'])."에서 ".number_format($row['it_price'])."로 변동";
                    $_alert_res_txt = $_alert_res_txt.'\n'.$_alert_txt;
                }
            }

            // 합계금액 계산
            $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                            SUM(ct_point * ct_qty) as point,
                            SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where it_id = '{$row['it_id']}'
                          and od_id = '$s_cart_id' ";
            $sum = sql_fetch($sql);

            if ($i==0) { // 계속쇼핑
                $continue_ca_id = $row['ca_id'];
            }
			if($row['it_tax'])  { $it_tax = "<img src='/shop/img/icon_taxs.jpg'> "; } else { $it_tax = "";}

            //$a1 = '<a href="./item.php?it_id='.$row['it_id'].'"><b>';
			$a1 = '<a href="/shop/search.php?s_select=all&q='.$row['it_8'].'"><b>';
            $a2 = '</b></a>';
            //$image = get_it_image($row['it_id'], 70, 70); 
			$image = get_it_image($row['it_8'], 70, 70); 
			//echo "select * from shop.g5_shop_item where it_id = '$row[it_8]'";

			$row2 = sql_fetch("select * from g5_shop_item where it_id = '$row[it_id]' ");
			$row3 = sql_fetch("select * from shop.g5_shop_item where it_id = '$row[it_8]' ");

            $it_name =  $a1 . stripslashes($row3['it_name']) . $a2 . $it_tax ;
            $it_options = print_item_options($row['it_id'], $s_cart_id);
            if($it_options) {
                $mod_options = '<div class="sod_option_btn"><button type="button" class="mod_options">수량수정</button></div>';
				$mod_options2 = '<div class="sod_option_btn2"><button type="button" class="mod_options2">공급사변경</button></div>';

                //$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
				if($row3['it_5'] || $row3['it_6']){
					$it_name .= '<div class="sod_opt">'.$row3['it_5'].'/'.$row3['it_6'].'</div>';
				}
            }

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
                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

                if($sendcost == 0)
                    $ct_send_cost = '무료';
            }

            $point      = $sum['point'];
            $sell_price = $sum['price'];


			$s_cart_id2 = $row[com_od_id];

			$tot_point      += $point;
            $tot_sell_price += $sell_price;

			$full_tot_sell_price +=$sell_price;
			
			 // 배송비 계산
            if($row['it_sc_type'] != 1) {
                $send_cost = get_sendcost($s_cart_id2, 0);
            }
			//$full_send_cost += $send_cost;
			
			
        ?>

        <tr>
			<td class="td_chk">
                <label for="ct_chk_<?php echo $i; ?>" class="sound_only">상품</label>
                <input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked" onclick="ck_prince('ct_chk_<?php echo $i; ?>','<?=$sell_price?>','<?=$send_cost?>','send_<?=$ar[com_id]?>');" class="send_<?=$ar[com_id]?>" >
				
            </td>
			<td class="sod_img"><?php echo $row['mb_nick']; ?></td>
            <td class="sod_img"><?php echo $image; ?></td>
            <td style="text-align:center;">
                <input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
                <input type="hidden" name="it_name[<?php echo $i; ?>]"  value="<?php echo get_text($row['it_name']); ?>">
                <?php 
                echo $it_name.$mod_options2;
                echo "<br>";
                
                $opt_sql = "SELECT ct_option,io_id,io_price FROM g5_shop_cart WHERE com_od_id='{$s_cart_id2}'";
                $opt_res = sql_query($opt_sql);

                for ($o=0; $opt_row=sql_fetch_array($opt_res); $o++){
                    if ($opt_row['ct_option'] && $opt_row['io_id']) {
                        echo "옵션 : ".get_text($opt_row['ct_option']);
                        echo "&nbsp;&nbsp;&nbsp;";
                        echo "가격 : ".number_format($opt_row['io_price']);
                        echo "<br>";
                    }
                }
                ?>

            </td>
			<!-- <td  class="td_numbig"><?=$row3['it_5']?></td>
			<td  class="td_numbig"><?=$row3['it_6']?></td>
 -->
            <td class="td_num">
				<?php echo number_format($sum['qty']); ?>
				<?php echo $mod_options;?>
			</td>
            <td class="td_numbig"><?php echo number_format($row['ct_price']); ?></td>
            <td class="td_numbig"><span id="sell_price_<?php echo $i; ?>"><?php echo number_format($sell_price); ?></span></td>
            <!-- <td class="td_numbig"><?php echo number_format($point); ?></td> -->
            <td class="td_num"><?php echo $ct_send_cost; ?></td>
            
        </tr>

        <?php
           
        } // for 끝

        if ($i == 0) { echo '<tr><td colspan="8" class="empty_table">장바구니에 담긴 상품이 없습니다.</td></tr>'; }

        if ($_res_n_goods >= 1 && $_res_m_goods >= 1) {
            $medical_check = "Y";
        } elseif ($_res_n_goods >= 1 && $_res_m_goods < 1) {
            $medical_check = "";
        } elseif ($_res_n_goods < 1 && $_res_m_goods >= 1) {
            $medical_check = "Y";
        }
        ?>
        </tbody>
        </table>



    </div>

    <?
    if($medical_check == 'Y'){
    ?>
        <script language="JavaScript">
            $('#view_text').html('<span style="font-size:10pt; background-color:#0B0B3B; color: white;padding: 12px 10px 10px 10px;">배송지 변경안내</span>&nbsp;<span style="font-size:10pt; background-color:#0B0B3B; color: white;padding: 12px 10px 10px 10px;">“의약품은 사업자 기본주소 외 배송이 불가하며 의약품 포함 시 배송지 변경이 안됩니다.“</span>');
        </script>
    <?
    }

    $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비
    if ($tot_price > 0 || $send_cost > 0) {
		$full_send_cost += $send_cost;
    ?>
	<div id="sod_bsk_tot_new">
		<input type="hidden" name="send_<?=$ar[com_id]?>" id="send_<?=$ar[com_id]?>" value="0">
		상품금액 <?=number_format($tot_sell_price)?>원  <?php if ($send_cost > 0) { ?>+ 배송비 <?php echo number_format($send_cost); ?> 원<? } ?> = <span>결제예상금액 <?=number_format($tot_price)?>원</span>
	</div>
    <!-- <dl id="sod_bsk_tot">
        <?php if ($send_cost > 0) { // 배송비가 0 보다 크다면 (있다면) ?>
        <dt class="sod_bsk_dvr">배송비</dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($send_cost); ?> 원</strong></dd>
        <?php } ?>

        <?php
        if ($tot_price > 0) {
        ?>

        <dt class="sod_bsk_cnt">총계 가격/포인트</dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> 원 / <?php echo number_format($tot_point); ?> 점</strong></dd>
        <?php } ?>

    </dl> -->
    <?php } ?>


<? }
 if($_alert_res_chk == "Y"){
     ?>
     <script>
         var test="<?=$_alert_res_txt?>";
         alert("상품 가격변동 안내\n\n"+test);
         location.reload();
     </script>

 <?}?>
	<div style="position:relative;width:1180px;height:96px;background:url('/img/etc/payyy_01.png') center top no-repeat;">
		<span id="item_all">총 상품 <?//=$full_cnt['cnt']?><?=$i?>개</span>
		<span id="item_p1"><b><?=number_format($full_tot_sell_price)?></b>원</span>
		<span id="item_p2"><b><?=number_format($full_send_cost)?></b>원</span>
		<span id="item_p3"><b><?=number_format($full_tot_sell_price + $full_send_cost)?></b>원</span> 
		<input type="hidden" name="item_all_txt" id="item_all_txt" value="<?=$i?>">
		<input type="hidden" name="item_p1_txt" id="item_p1_txt" value="<?=$full_tot_sell_price?>">
		<input type="hidden" name="item_p2_txt" id="item_p2_txt" value="<?=$full_send_cost?>">
		<input type="hidden" name="item_p3_txt" id="item_p3_txt" value="<?=$full_tot_sell_price + $full_send_cost?>">

		<input type="hidden" name="item_all_txt_on" id="item_all_txt_on" value="<?=$i?>">
		<input type="hidden" name="item_p1_txt_on" id="item_p1_txt_on" value="<?=$full_tot_sell_price?>">
		<input type="hidden" name="item_p2_txt_on" id="item_p2_txt_on" value="<?=$full_send_cost?>">
		<input type="hidden" name="item_p3_txt_on" id="item_p3_txt_on" value="<?=$full_tot_sell_price + $full_send_cost?>">

	</div>
	<style>
	#item_all { position:absolute;font-size:24px;font-weight:bold;left:0;top:33px;color:#3A3A3A;}
	#item_p1 { position:absolute;font-size:20px;left:370px;top:40px;}	
	#item_p2 { position:absolute;font-size:20px;left:690px;top:40px;}
	#item_p3 { position:absolute;font-size:20px;left:972px;top:40px;}

	#item_p1 b {color:#01A3E4;}
	#item_p2 b {color:#01A3E4;}
	#item_p3 b {color:#01A3E4;}
	</style>
	
	<script>
	function ck_prince(ids , f_price , f_send , f_id){
		var id_tmp = document.getElementById(ids);

		//alert($("input:checkbox[class='"+f_id+"']").is(":checked"));

		if(id_tmp.checked == true){
			 
			 console.log("1111");


			document.getElementById("item_all_txt").value = parseInt(document.getElementById("item_all_txt").value) + 1;
			document.getElementById("item_p1_txt").value = parseInt(document.getElementById("item_p1_txt").value) + parseInt(f_price);
			if($("input:checkbox[class='"+f_id+"']").is(":checked")){
				document.getElementById("item_p2_txt").value = parseInt(document.getElementById("item_p2_txt").value) + parseInt(document.getElementById(f_id).value);
				document.getElementById(f_id).value = 0;

			} 
			document.getElementById("item_p3_txt").value = parseInt(document.getElementById("item_p1_txt").value) + parseInt(document.getElementById("item_p2_txt").value);	
			inner_txt();

		}else{


			document.getElementById("item_all_txt").value = parseInt(document.getElementById("item_all_txt").value) - 1;
			document.getElementById("item_p1_txt").value = parseInt(document.getElementById("item_p1_txt").value) - parseInt(f_price);

			if(!$("input:checkbox[class='"+f_id+"']").is(":checked")){
			    if(document.getElementById("item_p2_txt").value > 0) {

                    document.getElementById("item_p2_txt").value = parseInt(document.getElementById("item_p2_txt").value) - parseInt(f_send);
                    document.getElementById(f_id).value = f_send;
                }
			}



			document.getElementById("item_p3_txt").value = parseInt(document.getElementById("item_p1_txt").value) + parseInt(document.getElementById("item_p2_txt").value);
			inner_txt();
		}
	}
	function inner_txt(){ 
		$("#item_all").html("총 상품 "+document.getElementById("item_all_txt").value+"개");
		$("#item_p1 b").html(number_format(document.getElementById("item_p1_txt").value));
		$("#item_p2 b").html(number_format(document.getElementById("item_p2_txt").value));
		$("#item_p3 b").html(number_format(document.getElementById("item_p3_txt").value));
	}
	</script>

    <div id="sod_bsk_act" style="margin-top:15px;">
        <?php if ($i == 0) { ?>
        <a href="<?php echo G5_SHOP_URL; ?>/" class="btn01" style="width:120px;height:43px;font-size:16px;padding:0;line-height:45px;">쇼핑 계속하기</a>
        <?php } else { ?>
        <input type="hidden" name="url" value="./orderform.php">
        <input type="hidden" name="records" value="<?php echo $i; ?>">
        <input type="hidden" name="act" value="">
        <a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn01" style="width:120px;height:43px;font-size:16px;padding:0;line-height:45px;">쇼핑 계속하기</a>
		
        <button type="button" onclick="return form_check('buy');" class="btn_submit"  style="width:120px;height:45px;font-size:16px;">주문하기</button>
	
        <!-- <button type="button" onclick="return form_check('seldelete');" class="btn01">선택삭제</button> -->
        <!-- <button type="button" onclick="return form_check('alldelete');" class="btn01">비우기</button> -->
        <?php if ($naverpay_button_js) { ?>
        <div class="cart-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
        <?php } ?>
        <?php } ?>
    </div>

    </form>

</div>

<script>
$(function() {
    var close_btn_idx;
	var close_btn_idx2;

    // 선택사항수정
    $(".mod_options").click(function() {
        var it_id = $(this).closest("tr").find("input[name^=it_id]").val();
        var $this = $(this);
        close_btn_idx = $(".mod_options").index($(this));

        $.post(
            "./cartoption.php",
            { it_id: it_id },
            function(data) {
                $("#mod_option_frm").remove();
                $this.after("<div id=\"mod_option_frm\" style='left:-180px;' ></div>");
                $("#mod_option_frm").html(data);
                price_calculate();
            }
        );
    });
	// 공급사수정
    $(".mod_options2").click(function() {
        var it_id = $(this).closest("tr").find("input[name^=it_id]").val();
        var $this = $(this);
        close_btn_idx2 = $(".mod_options2").index($(this));

        $.post(
            "./cartoption2.php",
            { it_id: it_id },
            function(data) {
                $("#mod_option_frm2").remove();
                $this.after("<div id=\"mod_option_frm2\" style='left:-80px;' ></div>");
                $("#mod_option_frm2").html(data);
                price_calculate();
            }
        );
    });
    $(document).on("click", "#mod_option_close2", function() {
        $("#mod_option_frm2").remove();
        $(".mod_options2").eq(close_btn_idx).focus();
    });

    // 모두선택
   // $("input[name=ct_all]").click(function() {
	// if($(this).is(":checked"))
	var cks = "1";
	  $(".allchecks").click(function() { 
       if(cks == "1"){
            $("input[name^=ct_chk]").attr("checked", true);
			$("#item_all_txt").val($("#item_all_txt_on").val());
			$("#item_p1_txt").val($("#item_p1_txt_on").val());
			$("#item_p2_txt").val($("#item_p2_txt_on").val());
			$("#item_p3_txt").val($("#item_p3_txt_on").val());

			$("#item_all").html("총 상품 "+document.getElementById("item_all_txt").value+"개");
			$("#item_p1 b").html(number_format(document.getElementById("item_p1_txt").value));
			$("#item_p2 b").html(number_format(document.getElementById("item_p2_txt").value));
			$("#item_p3 b").html(number_format(document.getElementById("item_p3_txt").value));

			cks = 2;
	   }else{
            $("input[name^=ct_chk]").attr("checked", false);
			cks = 1;
			$("#item_all_txt").val("0");
			$("#item_p1_txt").val("0");
			$("#item_p2_txt").val("0");
			$("#item_p3_txt").val("0");

			$("#item_all").html("총 상품 0개");
			$("#item_p1 b").html("0");
			$("#item_p2 b").html("0");
			$("#item_p3 b").html("0");
	   }
    });

    // 옵션수정 닫기
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });
    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });

});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("구매하실 상품을 하나이상 선택해 주십시오.");
        return false;
    }

    return true;
}

function form_check(act) {
    var f = document.frmcartlist;
    var cnt = f.records.value;

    if (act == "buy")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("주문하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }
    else if (act == "alldelete")
    {
        f.act.value = act;
        f.submit();
    }
    else if (act == "seldelete")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("삭제하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }

    return true;
}
</script>
<!-- } 장바구니 끝 -->

<?php
include_once('./_tail.php');
?>