<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_INDEX_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>
<div id="main_vs">
<!-- 메인이미지 시작 { -->
<?php echo display_banner('메인', 'mainbanner.20.skin.php'); ?>
<!-- } 메인이미지 끝 -->
	<div id="new_items">
		<div class="right_btn2"><a href="#cdot1"><img src="<?php echo G5_IMG_URL ?>/main/newproduct_prev.png"/></a></div>
		<div class="left_btn2"><a href="#cdot1"><img src="<?php echo G5_IMG_URL ?>/main/newproduct_next.png"/></a></div>
		<div class="mContents">
			<ul class="mWrapper">
			<?
			$sql = "select * from {$g5['g5_shop_item_table']}  where it_use = '1' and it_8 = '' and it_type3 = '1' order by it_time desc limit 10  ";

			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? if($i%2==0 || $i==0) { echo "<li class='mList'>";}?>
				
				<? $pri = sql_fetch("select it_price , it_img1 , it_img2 ,it_id from shop.{$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
				<dl class="<?if($i==0){ echo "first"; }?>">
					<dt class="pic">
						<a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">
						<?if($row[it_img1]){?>
							<img src="/data/item/<?=$row[it_img1]?>" alt="" width="120" height="120"/>
						<?}else if($row[it_img2]){?>
							<img src="/data/item/<?=$row[it_img2]?>" alt="" width="120" height="120"/>
						<?}else{?>
							<img src="/shop/img/no_image.gif" width="120" height="120" >
						<?}?>
						</a>
					</dt> 
					<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=cut_str($row[it_name],10,"")?></a></dd> 
					<? if($member[mb_id]){?>
					<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=number_format($pri[it_price])?>원</a></dd>
					<? }else{?>
					<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">회원공개</a></dd>
					<? } ?>
				</dl>
				<? if($i%2!=0) { echo "</li>";}?>
			<? } ?>
			</ul>
		</div>
	</div>
</div>
<div id="main_wrap">
    <?
    echo display_banner('베스트상품_상단부', 'mainbanner.100.skin.php');
    ?>
	<div id="main_01">
		<h4 class="main_titlie">베스트상품</h4>
		<?

		$sql = "select * from {$g5['g5_shop_item_table']} where it_use = '1' and it_type1 = '1' and it_8 = '' order by it_order, it_id desc limit 5 ";
		/*
		$res_cnt = sql_query("select it_id  , sum(ct_qty) as cnt from shop.g5_shop_cart group by it_id order by cnt desc limit 5");
		$it_id_arr = "";
		while($aaa = sql_fetch_array($res_cnt)){
			if(!$it_id_arr){
				$it_id_arr = $aaa['it_id'];
			}else{
				$it_id_arr = $it_id_arr.",".$aaa['it_id'];
			}
		} 
		$sql = "select * from {$g5['g5_shop_item_table']} m where it_id in($it_id_arr) order by field(it_id,{$it_id_arr}) ";
		*/
		$res = sql_query($sql);
		for($i=0;$row = sql_fetch_array($res);$i++){ ?>
			<? $pri = sql_fetch("select it_price , it_img1 , it_img2 , it_5 , it_6 ,it_id from shop.{$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
		<dl class="new_item_first <?if($i==0){ echo "first"; }?>">
			<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">
				<?if($row[it_img1]){?>
					<img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/>
				<?}else if($row[it_img2]){?>
					<img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/>
				<? } ?>
				</a></dt>
			<dd class="item_num">0<?=$i+1?></dd>
			<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?></a></dd>
			<dd class="item_box"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_5]?>/<?=$row[it_6]?></a></dd>
			<? if($member[mb_id]){?>
			<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=number_format($pri[it_price])?>원</a></dd>
			<? }else{?>
			<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">회원공개</a></dd>
			<? } ?>
		</dl>
		<? } ?>
		<script>
			$(document).ready(function(){ 
				$(".new_item_first").on('mouseover',function(){ 
					$(".new_item_first").removeClass("first");
					$(this).addClass("first");
				}); 

			});
		</script>
	</div> 
</div> 

	<div id="main_03_wrap">
		<div id="main_03">
			<ul>
			<? 
				$sql = "select * from shop.g5_shop_banner where bn_position = '공통메인_이벤트' and ( bn_device = 'pc' or bn_device = 'both') and bn_begin_time <= now() and bn_end_time >= now() order by bn_order asc limit 3";
				$res = sql_query($sql);
				while($row = sql_fetch_array($res)){
			?>
				<li class="fL" style="margin-right:5px"><a href="<?=G5_SHOP_URL?>/bannerhit.php?bn_id=<?=$row['bn_id']?>&amp;url=<?=urlencode($row['bn_url'])?>"  target="_blank" ><img src="http://obgys.keymedi.com/data/banner/<?=$row[bn_id]?>" alt="" width="290" height="290" /></a></li>
			<? } ?>
			</ul> 
			<div id="main_0304">
				<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0401"><img src="<?php echo G5_IMG_URL ?>/nt.png" alt="" class="more"/></a>
				<dl>
				<? 
						$sql = "select wr_id , wr_subject , wr_datetime  from g5_write_0401 where wr_is_comment = 0 and (ca_name='산부인과 협동조합' or ca_name='전체') order by wr_id desc limit 5"; 
						$res = sql_query($sql);
						while($row = sql_fetch_array($res)){
					?>
					<dt><span>ㆍ</span><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0401&wr_id=<?=$row[wr_id]?>"><?=$row[wr_subject]?></a></dt>
					<dd><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0401&wr_id=<?=$row[wr_id]?>"><?=substr($row[wr_datetime],0,10)?></a></dd>
				<? } ?>
				</dl>
			</div>
		</div>
	</div>

	<div id="main_02_title">
		<h4>추천상품</h4>
	</div>
	<div id="main_02">
		<div id="main_02_menu">
			<ul>
				<li><a href="#" onclick="main_02('1');return false;" class="overs"  id="main_02_menu_btn1">IVNT 주사제</a></li>
				<li><a href="#" onclick="main_02('2');return false;" id="main_02_menu_btn2">백신</a></li>
				<li><a href="#" onclick="main_02('3');return false;" id="main_02_menu_btn3">비급여의약품</a></li>
				<li><a href="#" onclick="main_02('4');return false;" id="main_02_menu_btn4">급여의약품</a></li>								
				<li><a href="#" onclick="main_02('5');return false;" id="main_02_menu_btn5">소모품</a></li>
				<li><a href="#" onclick="main_02('6');return false;" class="last" id="main_02_menu_btn6">화장품</a></li>
			</ul>
		</div>
		<div class="main_02_itembox" id="main_02_item_1">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like 'j0%' or ca_id2 like 'j0%' or ca_id3 like 'j0%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		<div class="main_02_itembox" id="main_02_item_2">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like '10%' or ca_id2 like '10%' or ca_id3 like '10%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		<div class="main_02_itembox" id="main_02_item_3">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like '30%' or ca_id2 like '30%' or ca_id3 like '30%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		<div class="main_02_itembox" id="main_02_item_4">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like '20%' or ca_id2 like '20%' or ca_id3 like '20%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		
		
		<div class="main_02_itembox" id="main_02_item_5">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like 'b0%' or ca_id2 like 'b0%' or ca_id3 like 'b0%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		<div class="main_02_itembox" id="main_02_item_6">
			<?
			$sql = "select * from ( select * , (SELECT COUNT(*) FROM g5_shop_item WHERE it_8 = m.it_id) AS cnt from {$g5['g5_shop_item_table']} m where it_use = '1' and ( ca_id like 'k0%' or ca_id2 like 'k0%' or ca_id3 like 'k0%' ) and it_8 ='' and it_type2 = '1' order by it_order, it_id desc ) a WHERE a.cnt > 0 limit 10 ";
			$res = sql_query($sql);
			for($i=0;$row = sql_fetch_array($res);$i++){ ?>
				<? $pri = sql_fetch("select it_price from {$g5['g5_shop_item_table']} where  it_use = '1' and it_8 = '{$row[it_id]}' order by it_price asc limit 1");?>
			<dl>
				<dt class="pic"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?if(is_file(G5_PATH."/data/item/".$row[it_img1])){?><img src="/data/item/<?=$row[it_img1]?>" alt="" width="200" height="200"/><?}else if(is_file(G5_PATH."/data/item/".$row[it_img2])){?><img src="/data/item/<?=$row[it_img2]?>" alt="" width="200" height="200"/><?}else{?><img src="/shop/img/no_image.gif" width="200" height="200" ><?}?></a></dt>
				<dd class="item_name"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main"><?=$row[it_name]?> <?=$row[it_5]?></a></dd>
				<dd class="item_price"><a href="/shop/search.php?q=<?=$row[it_id]?>&q_where=main">￦<?=number_format($pri[it_price])?></a></dd>
			</dl>
			<? } ?>
		</div>
		<script type="text/javascript">
			function main_02(num){
				for(var i = 1; i <= 6; i++){
					if(i == num){
						$("#main_02_menu_btn"+i).addClass("overs");
						document.getElementById("main_02_item_"+i).style.display = "block";
					}else{
						$("#main_02_menu_btn"+i).removeClass("overs");
						document.getElementById("main_02_item_"+i).style.display = "none";
					}
				}
			}
			main_02('1');
		</script>
	</div>
	

	<div id="main07">
		<div id="main_07_title">
			<h4>추천VOD</h4>
		</div> 
		<div class="review_list">
		<?
			$today = date("Y-m-d");
			$totime = date("H:i");
			$sql = "select * from portal.g5_write_0101 where wr_is_comment = 0 and wr_2 < '$today' and wr_4 != '' and wr_8 = '' and wr_12 = '' and wr_30 = '' order by wr_2 desc limit 5";
			$res = sql_query($sql);				
		?>
			<ul>
				<? 
				for ($i=0; $row=sql_fetch_array($res); $i++) {
					//$list_file = get_file("0101", $row['wr_id'] );
					$sql_f = sql_fetch("select * from portal.g5_board_file where bo_table = '0101' and wr_id = '$row[wr_id]' and bf_no = '2' ");
				?>
				<li id="medi_list_id_3<?=$i?>">
					<dl>
						<dt><a href="https://keymedi.com/bbs/board.php?bo_table=0101&wr_id=<?=$row[wr_id]?>&type=4" target="_blank"><img src="http://keymedi.com/data/file/0101/<?=$sql_f[bf_file]?>" width="175" height="100" alt="" ></a></dt>
						<dd class="live_hit" id="live_hit_3<?=$i?>"><img src="/img/new_main/view_icon.png" alt="" />&nbsp;<?=$row[wr_hit]?></dd>
						<dd class="live_subject"><a href="https://keymedi.com/bbs/board.php?bo_table=0101&wr_id=<?=$row[wr_id]?>&type=4" target="_blank"><?=$row[wr_subject]?></a></dd>
						<dd class="live_date <?=$style_class?>"><?=substr($row[wr_2], 2, 10)?>(<?=get_yoil($row[wr_2])?>) <?=$row[wr_5]?></dd> 				 
						<dd class="live_name"><?=$row[wr_1]?><!--  <span><?php  echo $row['wr_3'] ?></span> --></dd>   
					</dl>
				</li> 
				<? } ?>
			</ul>
		</div>
	</div>

</div>
 


<script type="text/javascript">
		 $(document).ready(function(){
		 $(".mContents").scrollBanner({
		  //컨텐츠 영역 정의
		  "cContentsClass" : "mContents",
		  "cContentsWidth" : "150px",
		  "cContentsHeight" : "470px",
		  //ul
		  "cWrapperClass" : "mWrapper",
		  //li
		  "cListClass" : "mList",
		  //한번에 노출할 리스트의 개수
		  "viewItemCnt" : "1",
		  //한번에 움직일 배너의 개수
		  "moveItemCnt" : "1",
		  //좌우버튼 정의
		  "leftBtnClass" : "left_btn2",
		  "rightBtnClass" : "right_btn2",
		  //dot영역 정의
		  "dotMoveYn" : true,
		  "dotWrap" : "cdot1",
		  "dotElementClass" : "dotList1",
		  "dotActClass" : "cdot_on1",
		  //자동롤링
		  "autoScrollYn" : false,
		  "scorllTimer" : "5000000",
		  //터치이벤트
		  "touchEvent" : true,
		  //상하롤링
		  "verticalMove" : false
		 });
		 });
		 function b_stop() {
		clearInterval(tmpTmr);
		}
		</script>

<?php// echo poll('theme/shop_basic'); // 설문조사 ?>

<?php// echo visit('theme/shop_basic'); // 접속자 ?>

<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>