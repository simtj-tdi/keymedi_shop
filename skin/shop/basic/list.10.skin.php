<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<?
	$wh = explode("/",$_SERVER['SCRIPT_NAME']);
 
	if($wh[2] == "search.php"){
?>
<div style="position:relativ;width:100%;height:auto;overflow:hidden;margin-bottom:20px;margin-top:10px;">
	<div style="width:50%;float:left;font-size:16px;font-weight:bold;">
	총 <?=$this->total_count2?>건
	</div>
	<?
		$tmp = explode(",", $this->order_by);
		$tmp[0] = trim($tmp[0]); 
	?>
	<div style="width:45%;float:left;text-align:right;font-size:14px;color:#9b9b9b;">	
		<input type="radio" <?=( ($_GET['qsort']=="it_update_time" && $_GET['qorder']=="desc" ) || $_GET['qsort'] =="" )?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_update_time&qorder=desc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_update_time desc")?"style='color:#57729f;'":""?>>등록순</span>&nbsp;&nbsp;
		<input type="radio" <?=($_GET['qsort']=="it_price" && $_GET['qorder']=="asc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_price&qorder=asc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_price asc")?"style='color:#57729f;'":""?>>낮은가격</span>&nbsp;&nbsp;
		<input type="radio" <?=($_GET['qsort']=="it_price" && $_GET['qorder']=="desc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_price&qorder=desc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_price desc")?"style='color:#57729f;'":""?>>높은가격</span>
	</div>
</div>
<? }else if($wh[2] == "seller.php"){?>
<div style="position:relativ;width:100%;height:auto;overflow:hidden;margin-bottom:20px;margin-top:10px;">
	<div style="width:50%;float:left;font-size:16px;font-weight:bold;">
	총 <?=$this->total_count2?>건
	</div>
	<?
		$tmp = explode(",", $this->order_by);
		$tmp[0] = trim($tmp[0]); 
	?>
	<div style="width:45%;float:left;text-align:right;font-size:14px;color:#9b9b9b;">	
		<input type="radio" <?=( ($_GET['qsort']=="it_update_time" && $_GET['qorder']=="desc" ) || $_GET['qsort'] =="" )?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_update_time&qorder=desc&s_select=<?=$_GET['s_select']?>&q2=<?=$_GET['q2']?>';"> <span <?=($tmp[0]=="it_update_time desc")?"style='color:#57729f;'":""?>>등록순</span>&nbsp;&nbsp;
		<input type="radio" <?=($_GET['qsort']=="it_price" && $_GET['qorder']=="asc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_price&qorder=asc&s_select=<?=$_GET['s_select']?>&q2=<?=$_GET['q2']?>';"> <span <?=($tmp[0]=="it_price asc")?"style='color:#57729f;'":""?>>낮은가격</span>&nbsp;&nbsp;
		<input type="radio" <?=($_GET['qsort']=="it_price" && $_GET['qorder']=="desc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&qsort=it_price&qorder=desc&s_select=<?=$_GET['s_select']?>&q2=<?=$_GET['q2']?>';"> <span <?=($tmp[0]=="it_price desc")?"style='color:#57729f;'":""?>>높은가격</span>
	</div>
</div>
<? }else {?>
<div style="position:relativ;width:100%;height:auto;overflow:hidden;margin-bottom:20px;margin-top:10px;">
	<div style="width:50%;float:left;font-size:16px;font-weight:bold;">
	총 <?=$this->total_count?>건
	</div>
	<?
		$tmp = explode(",", $this->order_by);
		$tmp[0] = trim($tmp[0]); 
	?>
	<div style="width:45%;float:left;text-align:right;font-size:14px;color:#9b9b9b;">	
		<input type="radio" <?=($tmp[0]=="it_update_time desc" ||  $tmp[0]=="it_order")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&sort=it_update_time&sortodr=desc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_update_time desc")?"style='color:#57729f;'":""?>>등록순</span>&nbsp;&nbsp;
		<input type="radio" <?=($tmp[0]=="it_price asc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&sort=it_price&sortodr=asc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_price asc")?"style='color:#57729f;'":""?>>낮은가격</span>&nbsp;&nbsp;
		<input type="radio" <?=($tmp[0]=="it_price desc")?"checked":""?> name="" onclick="document.location.href = '<?=$_SERVER['SCRIPT_NAME']?>?ca_id=<?=$_GET['ca_id']?>&sort=it_price&sortodr=desc&s_select=<?=$_GET['s_select']?>&q=<?=$_GET['q']?>';"> <span <?=($tmp[0]=="it_price desc")?"style='color:#57729f;'":""?>>높은가격</span>
	</div>
</div>
<? } ?>

<!-- 상품진열 10 시작 { -->
 
<table class="list_tb" style="width:95%">
	<caption>상품 목록</caption>
	<thead>
		<tr>
			<th scope="col" width="100">이미지</th>
			<th scope="col">상품명</th>
			<th scope="col" width="100">규격</th>
			<th scope="col" width="100">단위</th>
			<th scope="col" width="100">제조사</th> 
		</tr>
	</thead>
	<tbody>
<?php
for ($i=1; $row=sql_fetch_array($result); $i++) {
    /*
	if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i%$this->list_mod == 0) $sct_last = ' sct_last'; // 줄 마지막
        else if ($i%$this->list_mod == 1) $sct_last = ' sct_clear'; // 줄 첫번째
        else $sct_last = '';
    } else { // 1줄 이미지 : 1개
        $sct_last = ' sct_clear';
    }

	*/
	 
	$rowd = sql_fetch("select * from shop.g5_shop_item where it_id = '{$row['it_8']}' ");

	if($i == 1){
	?>
	<script>
		document.getElementById("itemview").src = "/shop/item.php?it_id=<?=$row['it_id']?>&no_frame=no";
	</script>
	<?

	}
?>  
			<tr class="this_tr" style="cursor:pointer;" onclick="parent.itemview.location.href='<?=$this->href?><?=$row['it_id']?>&no_frame=no';">
				<td class="fr">
				<?
				if ($this->view_it_img) {
					echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
				}
				?>
				</td>
				<td>
					<a href="<?=$this->href?><?=$row['it_id']?>&no_frame=no" target="itemview" class="sct_a">
				<?
				if ($this->view_it_name) {
					echo stripslashes($row['it_name']);
				}
				if ($this->view_it_icon) {
					echo "<div class=\"sct_icon\">".item_icon($row)."</div>\n";
				}
				?>
				</a>
				</td>
				<td><?=stripslashes($row['it_5'])?></td>
				<td><?=stripslashes($row['it_6'])?></td>
				<td><?=cut_str(stripslashes($row['it_maker']),10)?></td>
			</tr> 
<?
/*
    if ($i == 1) {
        if ($this->css) {
            echo "<ul class=\"{$this->css}\">\n";
        } else {
            echo "<ul class=\"sct sct_10\">\n";
        }
    }

    echo "<li class=\"sct_li{$sct_last}\" style=\"width:{$this->img_width}px\">\n";

    if ($this->href) {
        echo "<div class=\"sct_img\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
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

    if ($this->href) {
        echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }

    if ($this->href) {
        echo "</a></div>\n";
    }

    if ($this->view_it_basic && $row['it_basic']) {
        echo "<div class=\"sct_basic\">".stripslashes($row['it_basic'])."</div>\n";
    }

    if ($this->view_it_cust_price || $this->view_it_price) {

        echo "<div class=\"sct_cost\">\n";

        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<strike>".display_price($row['it_cust_price'])."</strike>\n";
        }

        if ($this->view_it_price) {
            echo display_price(get_price($row), $row['it_tel_inq'])."\n";
        }

        echo "</div>\n";

    }

    if ($this->view_sns) {
        $sns_top = $this->img_height + 10;
        $sns_url  = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $sns_title = get_text($row['it_name']).' | '.get_text($config['cf_title']);
        echo "<div class=\"sct_sns\" style=\"top:{$sns_top}px\">";
        echo get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_fb_s.png');
        echo get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_twt_s.png');
        echo get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_goo_s.png');
        echo "</div>\n";
    }

    echo "</li>\n";
*/
}

//if ($i > 1) echo "</ul>\n";

//if($i == 1) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";

if($i == 1) echo "<tr><td colspan='5'>등록된 상품이 없습니다.</td></tr>";
?>
</tbody>
	</table>


<script>
$(function(){
	$(".this_tr").mouseover(function(){
		$(this).addClass("this_tr_n");	
		//$(this).css("height","105px");
		//$(this).css("width","231px");
		//$(this).css("height","337px");
	}).mouseout(function(){
		$(this).removeClass("this_tr_n");	
		//$(this).css("width","235px");
		//$(this).css("height","340px");
	});
});
</script>
 
<style>
table.list_tb th { border-bottom:none;}
table.list_tb tbody {border-top:1px solid #c8c8c8;}
table.list_tb thead { margin-bottom:2px;}
.this_tr { height:105px;}
.this_tr_n { height:105px;border:2px solid #1180eb; border-left:4px solid #1180eb; }  
</style> 

<!-- } 상품진열 10 끝 -->