$(function(){
	
		if( window.console == undefined ){ console = { log : function(){} }; }
		
		var imgLoadedNumber=0;
		
		var totalBanner;
		var currentBanner=0;
		var winW;
		
		var nTimer;
		var TIME_INTERVAL=5000;
		
		var movement=false;
		var direction="left";
		
		totalBanner=$("#slider ul li").length;
		
		$(window).resize(function(){
			
			if($(window).width()>1220){
				//winW=$(window).width();
				winW=900;
			}else{
				winW=900;
			}
			
			$("#slider ul li").each(function(index,element){
				$(this).css({width:winW});
				
				if(index==currentBanner){
					$(this).css({left:0});
				}else{
					$(this).css({left:winW});
				}
			});
			
			$(".slider_gp1 ul li").each(function(index,element){
				$(this).css({width:winW});
				if(index==currentBanner){
					$(this).css({left:0});
				}else{
					$(this).css({left:winW});
				}
			});
			
			$(".slider_gp2 ul li").each(function(index,element){
				$(this).css({width:winW});
				if(index==currentBanner){
					$(this).css({left:0});
				}else{
					$(this).css({left:winW});
				}
			});
			
		});
		
		//네비버튼투명하게...
		$("#contents_nav_container .btn li").css({opacity:0});
		/*
		//네비버튼라인그리기
		$("#contents_nav_container .line li").each(function(index,element){
		//	$(this).css({left:66*(index+1),opacity:0.2});
		});
		*/
		//바로가기 ------------------------------------------------------------------------------------ 2-1
		$(".slider_gp2 ul li div.box1 .nav .btn div").css({opacity:0});
		$(".slider_gp2 ul li div.box1 .nav .btn div").hover(
			function(){
				clearTimer();
			},
			function(){
				setTimer();
			}
		);
		/*
		$(".slider_gp2 ul li div.box1 .nav .btn div").click(function(){
			window.open($(this).attr("data-url"),"_self");
		});
		*/
		//바로가기 ------------------------------------------------------------------------------------ 1-2
		$(".slider_gp1 ul li div.box2 .over").css({opacity:0});
		$(".slider_gp1 ul li div.box2").hover(
			function(){
				clearTimer();
				$(this).children(".over").stop().animate({opacity:1},500,"easeOutQuint");
			},
			function(){
				setTimer();
				$(this).children(".over").stop().animate({opacity:0},500,"easeOutQuint");
			}
		);
		/*
		$(".slider_gp1 ul li div.box2").click(function(){		
			window.open($(this).attr("data-url"),"_self");
		});
		*/
		//바로가기 ------------------------------------------------------------------------------------ 2-3
		$(".slider_gp2 ul li div.box3 .nav").hover(
			function(){
				clearTimer();
				//$(this).children(".arrow").stop().animate({left:262, opacity:1},500,"easeOutBack");
				//$(this).children(".go").stop().animate({left:262, opacity:0},500,"easeOutQuint");
			},
			function(){
				setTimer();
				//$(this).children(".arrow").stop().animate({left:242, opacity:0},500,"easeInOutQuint");
				//$(this).children(".go").stop().animate({left:242, opacity:1},500,"easeInOutBack");
			}
		);
		/*
		$(".slider_gp2 ul li div.box3 .nav").click(function(){
			window.open($(this).attr("data-url"),"_self");
		});
		*/
		//바로가기 ------------------------------------------------------------------------------------ 2-4
	/*
		$(".slider_gp2 ul li div.box4 .nav").hover(
			function(){
				clearTimer();
				//$(this).children(".arrow").stop().animate({left:282, opacity:1},500,"easeOutBack");
				//$(this).children(".go").stop().animate({left:282, opacity:0},500,"easeOutQuint");
			},
			function(){
				setTimer();
				//$(this).children(".arrow").stop().animate({left:262, opacity:0},500,"easeInOutQuint");
				//$(this).children(".go").stop().animate({left:262, opacity:1},500,"easeInOutBack");
			}
		);
		*/
		
		//이전 / 다음 ------------------------------------------------------------------------------------
		
		$("#arrowPrev div.img").css({opacity:0.5});
		$("#arrowPrev div.square").css({opacity:0});
		
		$("#arrowPrev div.square").hover(
			function(){
				clearTimer();
				$(this).siblings("div").stop().animate({left:-10, opacity:1},500,"easeOutQuint");
			},
			function(){
				setTimer();
				$(this).siblings("div").stop().animate({left:0, opacity:0.5},500,"easeOutQuint");
			}
		);
		
		$("#arrowPrev div.square").click(function(){
			prevBanner();
		});
		
		//******************************************
		
		$("#arrowNext div.img").css({opacity:0.5});
		$("#arrowNext div.square").css({opacity:0});
		
		$("#arrowNext div.square").hover(
			function(){
				clearTimer();
				$(this).siblings("div").stop().animate({left:10, opacity:1},500,"easeOutQuint");
			},
			function(){
				setTimer();
				$(this).siblings("div").stop().animate({left:0, opacity:0.5},500,"easeOutQuint");
			}
		);
		
		$("#arrowNext div.square").click(function(){
			nextBanner();
		});
		
		//네비 ------------------------------------------------------------------------------------

		$("#contents_nav_container .btn li").hover(
			function(){
				clearTimer();
				$("#contents_nav_container .over").stop().animate({left:145*$(this).index()},500,"easeOutQuint");
				$("#contents_nav_container .over ul").stop().animate({left:-145*$(this).index()},500,"easeOutQuint");
			},
			function(){
				setTimer();
				$("#contents_nav_container .over").stop().animate({left:145*currentBanner},500,"easeInOutQuint");
				$("#contents_nav_container .over ul").stop().animate({left:-145*currentBanner},500,"easeInOutQuint");
			}
		);
		
		$("#contents_nav_container .btn li").bind("mouseover",function(){
			if($(this).index()!=currentBanner){
				bannerSlide($(this).index(),"btnClick");
			}
		});
		$("#contents_nav_container .btn li").bind("click",function(){
			if($(this).index()!=currentBanner){
				bannerSlide($(this).index(),"btnClick");
			}
		});
		
		//-----------------------------------------------------------------------------------------
		
		var timer_temp1=setTimeout(myFunc,500);
		var timer_temp2=setTimeout(myFunc,1000);
		var timer_temp3=setTimeout(myFunc,1500);
		var timer_temp4=setTimeout(myFunc,2000);
		var timer_temp5=setTimeout(myFunc,5000);
		
		function myFunc(){
			$(window).resize();
		}
		$(window).resize();
		
		//$("#contents_nav_container ul li:eq("+currentBanner+")").children("img").css({top:-28});
		$("#contents_nav_container ul li:eq("+currentBanner+")").css({cursor:"default"});
		
		setTimer();
		
		$("#contents_ms").css({display:"block"});
		
		//-----------------------------------------------------------------------------------------
		
		function bannerSlide(num,sel){
			//console.log("num="+num);
			if(!movement){
				//$("#contents_nav_container ul li:eq("+currentBanner+")").children("img").css({top:0});
				$("#contents_nav_container .btn li:eq("+currentBanner+")").css({cursor:"pointer"});
				
				if(sel=="btnClick"){
					if(num>currentBanner){
						direction="left"
					}else{
						direction="right"
					}
				}else{
					direction=sel;
				}
				
				if(direction=="left"){ // next 클릭시
				
					movement=true;
					
					var $old=$("#slider ul li:eq("+currentBanner+")");
					var $old_gp1=$(".slider_gp1 ul li:eq("+currentBanner+")");
					var $old_gp2=$(".slider_gp2 ul li:eq("+currentBanner+")");
					var pos_old=-winW;
					$old.css({left:0}).stop().animate({left:pos_old},800,"easeInOutQuint");
					$old_gp1.css({left:0}).stop().animate({left:pos_old},600,"easeInOutQuint");
					$old_gp2.css({left:0}).stop().animate({left:pos_old},400,"easeInOutQuint");
					
					currentBanner=num;
					
					var $new=$("#slider ul li:eq("+currentBanner+")");
					var $new_gp1=$(".slider_gp1 ul li:eq("+currentBanner+")");
					var $new_gp2=$(".slider_gp2 ul li:eq("+currentBanner+")");

					var pos_new=0;
					$new.css({left:winW}).stop().animate({left:pos_new},800,"easeInOutQuint");
					$new_gp1.css({left:winW}).stop().animate({left:pos_new},1200,"easeInOutQuint");
					$new_gp2.css({left:winW}).stop().animate({left:pos_new},1600,"easeInOutQuint",function(){
						movement=false;
					});
				}else{ // prev 클릭시
				
					movement=true;
					
					var $old=$("#slider ul li:eq("+currentBanner+")");
					var $old_gp1=$(".slider_gp1 ul li:eq("+currentBanner+")");
					var $old_gp2=$(".slider_gp2 ul li:eq("+currentBanner+")");

					var pos_old=winW;
					$old.css({left:0}).stop().animate({left:pos_old},800,"easeInOutQuint");
					$old_gp1.css({left:0}).stop().animate({left:pos_old},400,"easeInOutQuint");
					$old_gp2.css({left:0}).stop().animate({left:pos_old},400,"easeInOutQuint");
					
					currentBanner=num;
					
					var $new=$("#slider ul li:eq("+currentBanner+")");
					var $new_gp1=$(".slider_gp1 ul li:eq("+currentBanner+")");
					var $new_gp2=$(".slider_gp2 ul li:eq("+currentBanner+")");

					var pos_new=0;
					$new.css({left:-winW}).stop().animate({left:pos_new},800,"easeInOutQuint");
					$new_gp1.css({left:-winW}).stop().animate({left:pos_new},1200,"easeInOutQuint");
					$new_gp2.css({left:-winW}).stop().animate({left:pos_new},1600,"easeInOutQuint",function(){
						movement=false;
					});
				}
				
				currentBanner=num;
				//$("#contents_nav_container ul li:eq("+currentBanner+")").children("img").css({top:-28});
				$("#contents_nav_container .btn li:eq("+currentBanner+")").css({cursor:"default"});
				
				$("#contents_nav_container .over").stop().animate({left:145*currentBanner},500,"easeOutQuint");
				$("#contents_nav_container .over ul").stop().animate({left:-145*currentBanner},500,"easeOutQuint");
			
			}
			
			
			
			
		}
	
		function moveNavArrow(num,easing){
			$("#nav #navMask").stop().animate({left:66*num},500,easing);
			$("#nav #navMask img").stop().animate({left:-66*num},500,easing);
		}
		
		function prevBanner(){
			var temp=currentBanner-1;
			if(temp<0){
				temp=totalBanner-1;
			}
			bannerSlide(temp,"right");
		}
		
		function nextBanner(){
			var temp=currentBanner+1;
			if(temp>=totalBanner){
				temp=0;
			}
			bannerSlide(temp,"left");
		}
		
		function setTimer(){
			if(nTimer!=0){
				clearInterval(nTimer);
			}
			nTimer=setInterval(nextBanner,TIME_INTERVAL,"left");
		}
		
		function clearTimer(){
			clearInterval(nTimer);
		}
	});