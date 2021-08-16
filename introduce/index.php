<?PHP
/**	
 * 	201230 - jinam23
 */
$home_url = "https://".$_SERVER['SERVER_NAME'] ;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>키메디몰</title>
	<link rel="stylesheet" type="text/css" href="/css/introduce/reset.css"/>
	<link rel="stylesheet" type="text/css" href="/css/introduce/style_common.css"/>
	<link rel="stylesheet" type="text/css" href="/css/introduce/style_keymedi.css?ver=1.11"/>
	<link rel="stylesheet" type="text/css" href="/css/introduce/slick.css"/>

	<script src="/js/jquery-1.9.1.min.js"></script>
	<script src="/js/slick.min.js"></script>
	<script type="text/javascript" src="/js/jquery.keymedi.js"></script>

	<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
	
</head>

<body>

<!-- Header -->
<header class="ab">
	<div class="wrap">
		<a href='<?=$home_url?>'><img src="/img/introduce/top_logo.png" alt="키메디몰" class="blk fl"></a>
		<nav class="fr">
			<a href="#" id='slt_text_0'>가입혜택</a>
			<a href="#" id='slt_text_1'>키메디몰 소개</a>
			<a href="#" id='slt_text_2'>회원혜택</a>
		</nav>
	</div>	
</header>
<!-- E O Header -->

<!-- mid -->
<section class="topper center wh">
	<h3 class="demilight">병원에 진료에 필요한 모든 것,<br><strong class="medium">키메디몰을 소개합니다</strong></h3>
	<p class="demilight">병원에 진료에 필요한 모든 것을  ONE STOP으로!<br>70여 공급사 입점과 약 8,000여가지의 다양한 상품을 최저가 수준으로 한번에 구매가  가능합니다.</p>
	<ul class="slider-nav">
		<li class="active">
			<img src="/img/introduce/thumb_1.png" alt="가입혜택">
		</li>
		<li>
			<img src="/img/introduce/thumb_2.png" alt="키메디몰 소개">
		</li>
		<li>
			<img src="/img/introduce/thumb_3.png" alt="회원혜택">
		</li>
	</ul>
</section>
<section class="sliderContainer">
	<ul class="slider slider-for">
		<li style="background-image:url(/img/introduce/slide_1.png);">
		</li>
		<li style="background-image:url(/img/introduce/slide_2.png);">
		</li>
		<li style="background-image:url(/img/introduce/slide_3.png);">
		</li>
	</ul>
</section>
<!-- E O mid -->


<!--Footer-->
<footer class="center wh">
	<h4 class="medium">다양한 혜택을 누리세요!</h4>
	<p class="demilight">키메디몰에서는 매월 다양한 상품과 할인 혜택이 제공됩니다.<br>지금 바로 가입해 보세요.</p>
	<div style="margin-top:30px;"><a href='https://shop.keymedi.com/bbs/register.php'><img src="/img/introduce/btn_0630.gif"></a></div>
</footer>
<!--E O Footer-->
<!-- E O Contents -->


<script>
var
$slick = $('.slider-for');
//슬라이드 
$slick.on('reInit afterChange', function (event, slick, currentSlide, nextSlide) {		
	var n = (currentSlide ? currentSlide : 0);
	$('.slider-nav li').removeClass('active');
	$('.slider-nav').find('li').eq(n).addClass('active');
	slt_text_change(n) ;
});
$slick.slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: true,
	dots: true
});
$('.slider-nav li').click(function() {
	var slideNo = $(this).index();
	$('.slider-nav li').removeClass('active');
	$(this).addClass('active');
	$('.slider-for').slick('slickGoTo', slideNo);
});

$('.slider-nav li').removeClass('active');
$('.slider-nav').find('li').eq(1).addClass('active');
$('.slider-for').slick('slickGoTo', 1);


$('.fr a').click(function() {
    var slideNo = $(this).index();
    $('.slider-for').slick('slickGoTo', slideNo);
});


function slt_text_change(no)
{
	$("#slt_text_0").css('color','#FFFFFF') ;		
	$("#slt_text_1").css('color','#FFFFFF') ;		
	$("#slt_text_2").css('color','#FFFFFF') ;		
	$("#slt_text_"+no).css('color','#FFC300') ;		
}
</script>
</body>
</html>
