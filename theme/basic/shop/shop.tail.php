<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
    return;
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
<?php if(basename($_SERVER['PHP_SELF']) != "index.php" ){ ?>


</div>



</div>
</div>
<script type="text/javascript">
var tmp_h = $("#sub_wrap");
var menu_h = $("#sub");
var menu_h2 = $("#sct_ct_1");
var menu_h3 = $("#ssch_frm"); 
var menu_h4 = $("#ssch_cate"); 
var menu_h5 = $("#seller_frm");     

if( menu_h.outerHeight() < 1200){ 
}else{ 
	$("#sub_content2").css("height",menu_h.outerHeight()-107);
}
if(menu_h2.outerHeight() > 50 ){
	$("#sub_content2").css("margin-top",menu_h2.outerHeight()+menu_h3.outerHeight()+menu_h4.outerHeight()+menu_h5.outerHeight()+117);
}else{
	<? 
		$wh = explode("/",$_SERVER['SCRIPT_NAME']);
		if($wh[2] == "seller.php"){
	?> 
		$("#sub_content2").css("margin-top",menu_h2.outerHeight()+menu_h3.outerHeight()+menu_h4.outerHeight()+menu_h5.outerHeight()+79);
	<? }else if($wh[2] == "search.php"){ ?>
		$("#sub_content2").css("margin-top",menu_h2.outerHeight()+menu_h3.outerHeight()+menu_h4.outerHeight()+menu_h5.outerHeight()+97);
	<? }else{ ?>
		$("#sub_content2").css("margin-top",menu_h2.outerHeight()+menu_h3.outerHeight()+menu_h4.outerHeight()+menu_h5.outerHeight()+107);
	<? } ?>

}	
</script>
<? } ?>
 
<!-- 
<div id="sub_banner2">
	<div class="bContents" style="margin:0 auto;">
		<ul class="bWrapper">
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_01.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_02.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_03.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_04.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_05.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_06.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_07.jpg" alt="" width="150" height="100"/></a></li>
			<li class="bList"><a href="#"><img src="<?php echo G5_IMG_URL ?>/banner/academy_banner_08.jpg" alt="" width="150" height="100"/></a></li>
		</ul>
	</div>	
</div>
	 -->
<div id="tail_wrap">
	<div id="tail_top_wrap">
		<div id="tail_top">
			<ul>
				<!--<li class="fL"><a href="/bbs/content.php?co_id=0101">회사소개</a></li>
				 <li class="fL bars">|</li>
				<li class="fL"><a href="#">이용안내</a></li>
				<li class="fL bars">|</li> -->

                <li class="fL"><a href="/bbs/content.php?co_id=privacy"><strong>개인정보처리방침</strong></a></li>
				<li class="fL bars">|</li>
				<li class="fL"><a href="/bbs/content.php?co_id=provision">이용약관</a></li>
				
				<!-- <li class="fL bars">|</li> -->
			</ul>
		</div>
	</div>
	<div id="tail">
		<h1>고객센터<br>02-540-1350</h1>
		<address>
			<p>법인명:(주)키메디&nbsp;&nbsp;|&nbsp;&nbsp;서울시 강남구 논현로 416, 6층 운기빌딩(역삼동)&nbsp;&nbsp;|&nbsp;&nbsp;대표:김명진&nbsp;&nbsp;|&nbsp;&nbsp;사업자등록번호:388-81-00767&nbsp;&nbsp;|&nbsp;&nbsp;팩스:02-6280-6393&nbsp;&nbsp;<!-- |&nbsp;&nbsp;이메일:keymedi@keydoc.co.kr --></p>
			<p><!-- 키메디 대표전화:02-549-9985&nbsp;&nbsp;|&nbsp;&nbsp;키메디몰 대표전화:070-4467-9738&nbsp;&nbsp;|&nbsp;&nbsp; -->고객센터:09:00~18:00 운영(점심시간:12:30~13:30) 토,일,공휴일 휴무</p>
			<p>통신판매번호:제2017-서울강남-03514호&nbsp;&nbsp;|&nbsp;&nbsp;개인정보관리책임자:엄동일&nbsp;&nbsp;|&nbsp;&nbsp;메일:keymedi@keydoc.co.kr&nbsp;&nbsp;|&nbsp;&nbsp;Copyrightⓒkeymedi All Right Reserved.</p>
		</address>
	</div>
</div>




<script type="text/javascript">
$(document).ready(function(){
$(".bContents").scrollBanner({
//컨텐츠 영역 정의
"cContentsClass" : "bContents",
"cContentsWidth" : "1200px",
"cContentsHeight" : "100px",
//ul
"cWrapperClass" : "bWrapper",
//li
"cListClass" : "bList",
//한번에 노출할 리스트의 개수
"viewItemCnt" : "8",
//한번에 움직일 배너의 개수
"moveItemCnt" : "1",
//좌우버튼 정의
//"leftBtnClass" : "right_btn",
//"rightBtnClass" : "left_btn",
//dot영역 정의
//"dotMoveYn" : true,
//"dotWrap" : "adot",
//"dotElementClass" : "adotList",
//"dotActClass" : "adot_on",
//자동롤링
"autoScrollYn" : true,
"scorllTimer" : "5000",
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


</div>
<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->
 
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122992646-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date()); 
  gtag('set', {'user_id': '<?=$member[mb_id]?>' , 'title': '<?=$g5_head_title?>' }); // 로그인한 User-ID를 사용하여 User-ID를 설정합니다.
  gtag('config', 'UA-122992646-1');
</script>

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>
