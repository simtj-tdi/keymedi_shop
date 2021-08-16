<? if($dep_1th == "01"){ ?>
	  <!-- <div id="sub_m01" class="left_menu">
		<span><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0101"><img src="<?php echo G5_IMG_URL ?>/sub/sub01_btn_01<?=($co_id=="0101")?"ov":""?>.png" ></a></span>
		<span><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0102"><img src="<?php echo G5_IMG_URL ?>/sub/sub01_btn_02<?=($co_id=="0102")?"ov":""?>.png" ></a></span>
		<span><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0103"><img src="<?php echo G5_IMG_URL ?>/sub/sub01_btn_03<?=($co_id=="0103")?"ov":""?>.png" ></a></span>
		<span><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0104"><img src="<?php echo G5_IMG_URL ?>/sub/sub01_btn_04<?=($co_id=="0104")?"ov":""?>.png" ></a></span>
</div>  -->
<!-- <div id="sub_top_new_menu"> 
	<span class="<?=($co_id=="0101")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0101">협동조합소개</a></span>
	<span class="<?=($co_id=="0102")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0102">이사장인사말</a></span>
	<span class="<?=($co_id=="0103")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0103">조직도</a></span>
	<span class="<?=($co_id=="0104")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=0104">가입철자</a></span> 
</div> -->

<? } ?>
<? if($dep_1th == "04"){ ?>

<div id="sub_top_new_menu"> 
	<span class="<?=($bo_table=="0401")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0401">공지사항</a></span>
	<span class="<?=($_SERVER['PHP_SELF']=="/bbs/faq.php")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a></span>
	<span class="<?=($bo_table=="0402")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0402">문의하기</a></span>
	<span class="<?=($bo_table=="0403")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0403">물품요청</a></span> 
	<? if($_SERVER['HTTP_HOST'] == "obgys.keymedi.com" || $_SERVER['HTTP_HOST'] == "obgys.keymedi.co.kr"){ ?>
	<span class="<?=($bo_table=="0405")?"ov":""?>"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0405">최저가모니터링</a></span> 
	<? } ?>
</div>

	<!-- <div id="sub_m04" class="left_menu">
		<span><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0401"><img src="<?php echo G5_IMG_URL ?>/sub/sub04_btn_01<?=($bo_table=="0401")?"ov":""?>.png" ></a></span>
		<span><a href="<?php echo G5_BBS_URL ?>/faq.php"><img src="<?php echo G5_IMG_URL ?>/sub/sub04_btn_02<?=($_SERVER['PHP_SELF']=="/bbs/faq.php")?"ov":""?>.png" ></a></span>
		<span><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0402"><img src="<?php echo G5_IMG_URL ?>/sub/sub04_btn_03<?=($bo_table=="0402")?"ov":""?>.png" ></a></span> 
		<span><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=0403"><img src="<?php echo G5_IMG_URL ?>/sub/sub04_btn_04<?=($bo_table=="0403")?"ov":""?>.png" ></a></span> 	 
	</div> -->

<? } ?>