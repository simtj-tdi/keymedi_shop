<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>
<div id="wrap">
	<div id="head_wrap2">
		<div id="head2">
			<h1><a href="<?php echo G5_URL ?>"><img src="/img2/top_logo.png" alt="<?php echo $config['cf_title']; ?>"></a></h1>

		</div>
	</div>

<!-- HEAD E -->
<script>
$(document).ready(function(){

var clientheight = $(document).height();
//alert(clientheight);
//$("#quick").height( clientheight );
/*	
$(window).scroll(function() {	
	if($('#head_wrap')){
		fty = $(window).scrollTop();
		scrollSystem(fty);
	};
});

function scrollSystem(_top){
		if(_top >= 300){ 
			$('#sub_menu .left_menu').css({'position':'fixed','top':0});
		}else{
			$('#sub_menu .left_menu').css({'position':'relative','top':0});
		}

}
*/
});	
</script>
<?php 
if(basename($_SERVER['PHP_SELF']) != "index.php" ){

if($co_id){
	$dep_1th = substr($co_id,0,2);
	$dep_2th = substr($co_id,2,2);
	$dep_3th = substr($co_id,4,2);
	$dep_4th = substr($co_id,6,2);
}else{
	$dep_1th = substr($bo_table,0,2);
	$dep_2th = substr($bo_table,2,2);
	
	if($bo_table == "online" ){
		$dep_1th = "06";
		$dep_2th = "01";
	}
	if($bo_table == "reservation" ){
		$dep_1th = "06";
		$dep_2th = "04";
	}
}

if($_SERVER['PHP_SELF']=="/sitemap.php" || $_SERVER['PHP_SELF']=="/bbs/login.php" || $_SERVER['PHP_SELF']=="/bbs/register.php" || $_SERVER['PHP_SELF']=="/bbs/register_form.php"  || $co_id =="provision" || $co_id =="privacy" || $co_id =="site" || $_SERVER['PHP_SELF']=="/bbs/register_result.php" || $_SERVER['PHP_SELF']=="/bbs/register_result2.php"){
	$dep_1th = "10";

	if($_SERVER['PHP_SELF']=="/bbs/login.php"){
		$dep_2th = "01";
	}
	if($_SERVER['PHP_SELF']=="/bbs/register.php"){
		$dep_2th = "03";
	}
	if($_SERVER['PHP_SELF']=="/bbs/register_form.php"){
		$dep_2th = "03";
	}
	if($co_id =="provision"){
		$dep_2th = "05";
		$dep_3th = "";
	}
	if($co_id =="privacy"){
		$dep_2th = "06";
		$dep_3th = "";
	}
	
 
}
include_once(G5_PATH.'/sub_title.php');
?>
	<script>
		$("#head_menu .dep1_<?=$dep_1th?>").addClass("on1");
	</script>
	<style>
		#tail_wrap { margin-top:0;}
	</style>

	<div id="sub_wrap2">
	<? if( $_SERVER['PHP_SELF'] !="/bbs/register_result2.php"){ ?>
		<!-- <div id="sub_menu"><?php// include_once(G5_PATH.'/menu.php'); ?> </div> -->
		<div id="sub_top2">
			<h2>회원가입</h2>
			<p>키메디 회원가입을 환영합니다<br>회원가입을 통해 다양한 혜택을 누려보세요</p>
		</div>
	<? } ?>
		<div id="sub2">
			
			<div id="sub_content3">
			
		 


<?php } ?>