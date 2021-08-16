<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<div id="contents_ms" tabindex="-1">
	<div id="slider" tabindex="-1">
		<ul tabindex="-1">
<?php
$max_width = $max_height = 0;
$bn_first_class = ' class="sbn_first"';

for ($i=0; $row=sql_fetch_array($result); $i++)
{
   // if ($i==0) echo '<section id="sbn_idx" class="sbn">'.PHP_EOL.'<h2>쇼핑몰 배너</h2>'.PHP_EOL.'<ul>'.PHP_EOL;
    //print_r2($row);
    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';
 
	if ($row['bn_url'][0] == '#')
		$banner .= '<a href="'.$row['bn_url'].'">';
	else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
		$banner .= '<a href="'.G5_SHOP_URL.'/bannerhit.php?bn_id='.$row['bn_id'].'&amp;url='.urlencode($row['bn_url']).'"'.$bn_new_win.'>';
	} 
	echo $banner."<li style='background-image:url(http://obgys.keymedi.com/data/banner/".$row['bn_id'].");  background-position:top center ;' tabindex='-1'></li>";

	if($banner)
		echo '</a>'.PHP_EOL; 

	$bn_first_class = '';
 
	$total = $i;
	$title[$i] = $row[bn_alt];
}
?>
	</ul>
	</div>
	<div class="slider_gp1">
		<ul>
<? for ($i=0; $i <= $total; $i++) {?>
			<li><div class="box<?=$i+1?>"></div></li>
<? }?>
		</ul>
	</div>   
	<div class="slider_gp2">
		<ul>
<? for ($i=0; $i <= $total; $i++) {?>
			<li><div class="box<?=$i+1?>"></div></li>
<? }?>
		</ul>
	</div>
	
	
	<div id="arrowPrev">
		<div class="img">
			<img src="<?php echo G5_IMG_URL ?>/main/vs/vs_btn_01.png" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='crop',src='<?php echo G5_IMG_URL ?>/main/vs/vs_btn_01.png')" />
		</div>
		<div class="square">
		</div>
	</div>
	
	<div id="arrowNext">
		<div class="img">
			<img src="<?php echo G5_IMG_URL ?>/main/vs/vs_btn_02.png" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='crop',src='<?php echo G5_IMG_URL ?>/main/vs/vs_btn_02.png')" />
		</div>
		<div class="square">
		</div>
	</div>


	<div id="contents_nav_container">
		<ul class="default">
			<? for ($i=0; $i <= $total; $i++) {?>
			<li><img src="<?php echo G5_IMG_URL ?>/main/vs/vs_btn.jpg" width="145" height="35" alt="" /><span><?=$title[$i]?></span></li>
			<? }?>			
		</ul>
		<div class="over">
			<ul>
			<? for ($i=0; $i <= $total; $i++) {?>
				<li><img src="<?php echo G5_IMG_URL ?>/main/vs/vs_btnov.jpg" width="145" height="35"  alt="" /><span><?=$title[$i]?></span></li>
			<? }?>
			</ul>
		</div>
		<ul class="btn">
			<? for ($i=0; $i <= $total; $i++) {?>
			<li></li>
			<? }?>
		</ul>
	</div>
	
</div>
<? $width = 145 * ($total+1)?>
<style>
#contents_nav_container { width:<?=$width?>px;}
#contents_nav_container .default { width:<?=$width?>px;}
#contents_nav_container .over ul { width:<?=$width?>px; }
#contents_nav_container .line { width:<?=$width?>px; }
#contents_nav_container .btn { width:<?=$width?>px; }
</style>