<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<!-- 장바구니 간략 보기 시작 { -->
<? /* ?>
<aside id="sbsk">

<? if($member[mb_name]){?>
    <h2><?=$member[mb_name]?>님</h2>
<? } ?>
	<h3>장바구니</h3>
    <ul>
    <?php
    $hsql  = " select it_id, it_name from {$g5['g5_shop_cart_table']} ";
    //$hsql .= " where od_id = '".get_session('ss_cart_id')."' group by it_id ";
    $hsql .= " where ct_status = '쇼핑' and mb_id = '$member[mb_id]' and ct_select = '0' ";
    $hresult = sql_query($hsql);
    for ($i=0; $row=sql_fetch_array($hresult); $i++)
    {
        echo '<li>';
        $it_name = get_text($row['it_name']);
        // 이미지로 할 경우
        //echo $it_name = get_it_image($row['it_id'], 110, 65, true);
        echo '<a href="'.G5_SHOP_URL.'/cart.php">'.$it_name.'</a>';
        echo '</li>';
    }

    if ($i==0)
        echo '<li id="sbsk_empty">장바구니 상품 없음</li>'.PHP_EOL;
?>
    </ul>



</aside>
<? */ ?>
<?
	 $hsql =  sql_fetch(" select count(*) as cnt from {$g5['g5_shop_cart_table']} where ct_status = '쇼핑' and mb_id = '$member[mb_id]' and ct_select = '0'  ");

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
<div style="position:relative;width:80px;height:69px;background:url('/img/main/quick/quick_cart.jpg') no-repeat;">
	
	<div style="position:absolute;padding:2px 7px;top:10px;left:48px;text-align:center;background:#475268;color:#fff;border-radius: 50%;"><?=$hsql['cnt']?></div>
	<a href="/shop/cart.php" style="position:absolute;left:0;top:0;display:inline-block;width:80px;height:69px;"></a>	
</div>
<div style="position:relative;width:80px;height:70px;background:url('/img/main/quick/quick_point.jpg') no-repeat;">
	<div style="position:absolute;width:100%;top:45px;text-align:center;"><?=number_format($member['mb_point'])?>p</div>
	<a href="/bbs/point.php" style="position:absolute;left:0;top:0;display:inline-block;width:80px;height:69px;"></a>
</div>
<div style="position:relative;width:80px;height:69px;background:url('/img/main/quick/quick_coupon.jpg') no-repeat;">
	<div style="position:absolute;padding:2px 7px;top:10px;left:48px;text-align:center;background:#475268;color:#fff;border-radius: 50%;"><?php echo number_format($cp_count); ?></div>
	<a href="/shop/coupon.php" style="position:absolute;left:0;top:0;display:inline-block;width:80px;height:69px;"></a>
</div>
<!-- } 장바구니 간략 보기 끝 -->