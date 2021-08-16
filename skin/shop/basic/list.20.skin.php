<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<div style="position:relativ;width:100%;height:auto;overflow:hidden;margin-bottom:20px;margin-top:10px;">
	<div style="width:50%;float:left;font-size:16px;font-weight:bold;">
	총 <?=$this->total_count2?>건
	</div>
	<?
		$tmp = explode(",", $this->order_by);
		$tmp[0] = trim($tmp[0]);
		
	?>
	<div style="width:50%;float:left;text-align:right;font-size:14px;color:#9b9b9b;">	
		<input type="radio" <?=($tmp[0]=="it_update_time desc")?"checked":""?> name="" onclick="document.location.href = '/shop/event.php?ev_id=<?=$this->event?>&com_id=<?=$com_id?>&ca_id=<?=$ca_id?>&sort=it_update_time&sortodr=desc';"> <span <?=($tmp[0]=="it_update_time desc")?"style='color:#57729f;'":""?>>등록순</span>&nbsp;&nbsp;
		<input type="radio" <?=($tmp[0]=="it_price asc")?"checked":""?> name="" onclick="document.location.href = '/shop/event.php?ev_id=<?=$this->event?>&com_id=<?=$com_id?>&ca_id=<?=$ca_id?>&sort=it_price&sortodr=asc';"> <span <?=($tmp[0]=="it_price asc")?"style='color:#57729f;'":""?>>낮은가격</span>&nbsp;&nbsp;
		<input type="radio" <?=($tmp[0]=="it_price desc")?"checked":""?> name="" onclick="document.location.href = '/shop/event.php?ev_id=<?=$this->event?>&com_id=<?=$com_id?>&ca_id=<?=$ca_id?>&sort=it_price&sortodr=desc';"> <span <?=($tmp[0]=="it_price desc")?"style='color:#57729f;'":""?>>높은가격</span>
	</div>
</div>
<!-- 상품진열 20 시작 { -->
<?php
for ($i=1; $row=sql_fetch_array($result); $i++) {
    if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i%$this->list_mod == 0) $sct_last = ' sct_last'; // 줄 마지막
        else if ($i%$this->list_mod == 1) $sct_last = ' sct_clear'; // 줄 첫번째
        else $sct_last = '';
    } else { // 1줄 이미지 : 1개
        $sct_last = ' sct_clear';
    }
	 

    if ($i == 1) {
        if ($this->css) {
            echo "<ul class=\"{$this->css}\">\n";
        } else {
            echo "<ul class=\"sct sct_20\">\n";
        }
    }

    echo "<li class=\"sct_li{$sct_last}\" style=\"position:relative;width:235px;height:340px;\">\n";

    if ($this->href) {
        echo "<div class=\"sct_img\"><a href=\"/shop/search.php?q={$row['it_id']}&q_where=main\" class=\"sct_a\">\n"; 
    }

    if ($this->view_it_img) {
		if($row['it_8']){
			echo get_it_image($row['it_8'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
		}else{
			echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
		}
    }

    if ($this->href) {
        echo "</a></div>\n";
    }

    if ($this->view_it_icon) {
        echo "<div class=\"sct_icon\">".item_icon($row)."</div>\n";
    }

    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;".stripslashes($row['it_id'])."&gt;</div>\n";
    }
	$mems = get_member($row['it_10']);
    if ($this->href) {
        echo "<div class=\"sct_txt\"><a href=\"/shop/search.php?q={$row['it_id']}&q_where=main\" class=\"sct_a\">\n"; 
		echo stripslashes($row['it_name'])."\n";
		echo "</a></div>\n";
		 echo "<div class=\"sct_txt2\"><a href=\"/shop/search.php?q={$row['it_id']}&q_where=main\" class=\"sct_a\">\n"; 
		echo stripslashes($row['it_5'])."/".stripslashes($row['it_6'])."\n";
		echo "</a></div>\n";
		echo "<div class=\"sct_txt3\"><a href=\"/shop/search.php?q={$row['it_id']}&q_where=main\" class=\"sct_a\">\n"; 
		echo stripslashes($row['it_maker'])."\n";
		echo "</a></div>\n";
    }

 

    if ($this->view_it_basic && $row['it_basic']) {
        echo "<div class=\"sct_basic\">".stripslashes($row['it_basic'])."</div>\n";
    }

    if ($this->view_it_cust_price || $this->view_it_price) {

        echo "<div class=\"sct_cost\" style='position:absolute;width:90%;left:5%;bottom:0;padding:0;' >\n";
		echo "<div style='position:relative;width:30%;float:left;font-size:16px;font-weight:normal;'>판매가</div>";
		echo "<div style='position:relative;width:70%;float:left;text-align:right;color:#01a3e4;'>";
        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<strike>".display_price($row['it_cust_price'])."</strike>\n";
        }

        if ($this->view_it_price) {
			$pri = sql_fetch("select it_price , it_img1 ,it_id from shop.{$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");

            //echo display_price(get_price($row), $row['it_tel_inq'])."\n";
			echo number_format($pri[it_price])."원";
        }
		echo "</div>";
        echo "</div>\n";

    }
/*
    if ($this->view_sns) {
        $sns_url  = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $sns_title = get_text($row['it_name']).' | '.get_text($config['cf_title']);
        echo "<div class=\"sct_sns\">";
        echo get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_fb_s.png');
        echo get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_twt_s.png');
        echo get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_goo_s.png');
        echo "</div>\n";
    }
*/
    echo "</li>\n";
}

if ($i > 1) echo "</ul>\n";

if($i == 1) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<script>
$(function(){
	$(".sct_li").mouseover(function(){
		$(this).addClass("upline");	
		$(this).css("width","231px");
		$(this).css("height","337px");
	}).mouseout(function(){
		$(this).removeClass("upline");	
		$(this).css("width","235px");
		$(this).css("height","340px");
	});
});
</script>
<style>
.sct .upline { border:2px solid #1180eb;}
</style>
<!-- } 상품진열 20 끝 -->