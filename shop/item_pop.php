<?
include_once('./_common.php');  
include_once(G5_THEME_PATH.'/head.sub.php');
$row_v = sql_fetch("select * from shop.{$g5['g5_shop_item_table']} where it_id = '$it_id' ");
 
?> 

<?php if ($row_v['it_basic']) { // 상품 기본설명 ?>
<h3 style="padding:20px;">상품 기본설명</h3>
<div id="sit_inf_basic" style="border-bottom:1px solid #d7d7d7;border-top:1px solid #d7d7d7;padding:20px;line-height:1.5me;">
	 <?php echo $row_v['it_basic']; ?>
</div>
<?php } ?>

<?php if ($row_v['it_explan']) { // 상품 상세설명 ?>
	<style>
		#sit_inf_explan img { max-width:540px;}
	</style>
 <h3 style="padding:20px;">상품 상세설명</h3>
<div id="sit_inf_explan" style="border-bottom:1px solid #d7d7d7;border-top:1px solid #d7d7d7;padding:20px;line-height:1.5me;">
	<?php echo conv_content($row_v['it_explan'], 1); ?>
</div> 
<?php } ?>