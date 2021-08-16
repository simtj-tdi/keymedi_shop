/*
 * Create by kimyb86
 * Last modified date : 2013-07-30
 * version 1.0.2
 * 수정내용
 * 상하롤링 기능추가 : verticalMove 값을 true로 설정하면 슬라이드 방향이 상하로 변경
 * 메소드명을 scroll에서 scrollBanner로 수정 : jQuery내장함수명과 겹치는 버그 수정
 * Usage
 * $(".cContents").scrollBanner({
		"cContentsClass" : "cContents", //스크롤을 감싸고 있는 영역(DIV)의 클래스명
		"cContentsWidth" : "100%",		//스크롤을 감싸고 있는 영역(DIV)의 넓이
		"cContentsHeight" : "160px",	//스크롤을 감싸고 있는 영역(DIV)의 높이
		"cWrapperClass" : "cWrapper",	//리스트Wrap(UL)의 클래스명
		"cListClass" : "cList",			//배너(LI)의 클래스명
		"leftBtnClass" : "cLeft",		//왼쪽 이동버튼의 클래스명
		"rightBtnClass" : "cRight",		//오른쪽 이동버튼의 클래스명
		"dotWrap" : "cdot",				//Dot 인디게이터를 감싸고 있는 영역(UL)의 클래스명
		"dotElementClass" : "dotList",	//Dot리스트(LI)의 클래스명
		"dotActClass" : "active",		//활성화면 인디게이터(LI)에 추가될 클래스명
		"dotMoveYn" : true,				//Dot 인디게이터 사용유무
		"viewItemCnt" : "1",			//화면에 노출될 배너의 수
		"moveItemCnt" : "1",			//페이지 이동시 이동될 배너의 수
		"autoScrollYn" : false,			//배너자동롤링 여부
		"scorllTimer" : "2000",			//배너자동롤리 간격
		"touchEvent" : true,			//터치이벤트 사용 여부
		"verticalMove" : false			//상하롤링
	});
 */

(function($){
	$.fn.scrollBanner = function(options){		
		
		//전역 변수 설정
		var settings = {
			//컨텐츠 영역 정의
			"cContentsClass" : "",
			"cContentsWidth" : "100%",
			"cContentsHeight" : "200px",
			//ul
			"cWrapperClass" : "",
			//li
			"cListClass" : "",
			//한번에 노출할 리스트의 개수
			"viewItemCnt" : "1",
			//한번에 움직일 배너의 개수
			"moveItemCnt" : "1",
			//좌우버튼 정의
			"leftBtnClass" : "",
			"rightBtnClass" : "",
			//dot영역 정의
			"dotMoveYn" : true,
			"dotElementClass" : "",
			"dotActClass" : "",
			//자동롤링
			"autoScrollYn" : true,
			"scorllTimer" : "9000",
			//터치이벤트
			"touchEvent" : true,
			//상하롤링
			"verticalMove" : false
		};
		
		
		//메인함수
		return this.each(function(){
			
			//설정값 merge
			$.extend(settings, options);
			
			//리스트의 넓이와 움직일 아이템의 수 변수 설정
			var listWid = "";
			var listHgt = "";
			var moveItemCnt = "";
			var tmpTmr = null; 
	
			//화면 초기화
			var fn_init = function(){
				
				//플러그인 초기화 성공여부 리턴값
				var rtnVal = true;
				
				//한번에 리스트를 몇개씩 움직일것인지 정의 ( 한번의 움직일 아이템개수 * 아이템하나의 넓이 )
				moveItemCnt = settings.moveItemCnt;
				/*
				if ( settings.viewItemCnt < settings.moveItemCnt ){
					moveItemCnt = settings.viewItemCnt;
				}
				*/
				
				try {
					//Content(전체를 감싸고 있는 영역) Style
					var contHgt = settings.cContentsHeight;
					$("." + settings.cContentsClass).css({"position": "relative", "overflow": "hidden", "width" : settings.cContentsWidth, "height": contHgt});
					
					//화면에 노출될 Content영역 안에 크기
					var contWid = $("." + settings.cContentsClass).innerWidth();
					//리스트 하나의 크기(li) 정의 (화면넓이/한번에노출할리스트의개수)
					listWid = contWid / settings.viewItemCnt;
					var contHgt = $("." + settings.cContentsClass).innerHeight();
					listHgt = contHgt / settings.viewItemCnt;
					
					if ( settings.verticalMove ){
						$("." + settings.cListClass).css({"height": listHgt,"width": listWid});
					} else {
						$("." + settings.cListClass).css({"float": "left", "width": listWid});
					}
					
					//리스트를 감싸고있는 영역(ul)의 넓이 (리스트하나의넓이 * 리스트의 개수)
					if ( settings.verticalMove ){
						var totListHgt = listHgt * $("." + settings.cListClass).length;
						$("." + settings.cWrapperClass).css({"position": "absolute", "height": totListHgt});
					} else {
						var totListWid = listWid * $("." + settings.cListClass).length;
						$("." + settings.cWrapperClass).css({"position": "absolute", "width": totListWid});
					}
				} catch ( msg ) {
					console.log("필수값 누락 cContentsClass(DIV) / cWrapperClass(UL) / cListClass(LI)");
					rtnVal = false;
				}
				
				return rtnVal;
				
			};
			
			if ( !fn_init() ){
				return false;
			}
			
			$(window).bind('resize', fn_init);
			$(window).bind('load', fn_init);
			$(window).bind('orientationchange', fn_init);
			
			//배너이동 한번만 가능하도록 세마포 적용
			var scrollingYn = true;
			
			//dot네비게이션에 대한 예외처리
			var dotError = false;
			try {
				//활성화 도트 클래스 설정
				var $dotActClass = settings.dotActClass;
				
					
				//dot에 속성값 적용
				for ( var ii=0; ii < $("." + settings.cListClass).length; ii++) {
					$("." + settings.dotElementClass).eq(ii).attr("pos-index", ii);
					$("." + settings.cListClass).eq(ii).attr("pos-index", ii);
				}
			
				//dot를 클릭했을때 배너가 움직이도록 설정
				if ( settings.dotMoveYn ){
					
					$("." + settings.dotElementClass).unbind().bind("click", function(){
						//현재위치
						var curPos = $("." + settings.cListClass).eq(0).attr("pos-index");
						//선택한 위치
						var clkPos = $(this).attr("pos-index");
						var movePos = clkPos - curPos;
						fn_func.setMove(movePos);
					});
					
				}	
			} catch( msg ) {
				dotError = true;
				console.log("dot navi와 관련된 값이 누락되었습니다. 네비게이션 리스트(li)의 클래스명 입력(dotElementClass)");
			}
			
			
			//배너이동버튼에 대한 바인드 예외처리
			try {
				
				//배너 좌측 이동 버튼
				$("." + settings.leftBtnClass).unbind().bind("click", function(){
	
					fn_func.setMove(1);
			
				});
				 
				//배너 우측 이동 버튼
				$("." + settings.rightBtnClass).unbind().bind("click", function(){
					
					fn_func.setMove(-1);
					
				});

			} catch ( msg ){
				console.log("배너이동버튼에 대한 클래스명이 누락되었습니다. leftBtnClass / rightBtnClass");
			}

			try
			{
				
				$("." + settings.cContentsClass).bind("mouseover", function(){
						clearInterval(tmpTmr);
				});
				
				$("." + settings.cContentsClass).bind("mouseout", function(){
								window.clearInterval(tmpTmr);
								tmpTmr = setInterval(function(){
				            		fn_func.setMove(1);
				            	}, settings.scorllTimer );				
				});
			} catch ( msg ){
				console.log("마우스 오버/아웃에 문제가 있습니다.");
			}
			




			//자동롤링
			if ( settings.autoScrollYn ){
				if ( settings.scorllTimer ) {
	            	tmpTmr = setInterval(function(){
	            		fn_func.setMove(1);
	            	}, settings.scorllTimer );
	            }
			}
			
			//터치 이벤트
			if ( settings.touchEvent ) {
				$(this).touchwipe({
				     wipeLeft: function() { fn_func.setMove(1); },
				     wipeRight: function() { fn_func.setMove(-1); },
				     //wipeUp: function() { alert("up"); },
				     //wipeDown: function() { alert("down"); },
				     //min_move_x: 20,
				     //min_move_y: 20,
				     preventDefaultEvents: true
				});
            }
			
			var fn_func = {
				
				//배너의 위치에 도트를 찍어준다.
				setDots : function(){
					if ( !dotError ) {
						var dotNum = $("." + settings.cListClass).eq(0).attr("pos-index");
						$("." + settings.dotElementClass).removeClass($dotActClass);
						$("." + settings.dotElementClass).eq(dotNum).addClass($dotActClass);
					}
				},
				
				//인덱스에 따라 배너가 움직인다.
				setMove : function(idx){
					
					if ( scrollingYn ){
						
						//자동스크롤링 정지
						if ( settings.autoScrollYn ){
							clearInterval(tmpTmr);
						}
						
						scrollingYn = false;
						
						//리스트를 옮길 횟수
						var moveCnt = Math.abs(idx) * moveItemCnt;
						
						//배너 이동방향 정의
						var forwardYn = (idx>0)?true:false;
						
						//리스트를 움직일 넓이
						var moveWid = moveItemCnt * listWid;
						var moveW = idx * moveWid;
						var moveHgt = moveItemCnt * listHgt;
						var moveH = idx * moveHgt;
						
						var $wrapper = function(){
							if ( settings.verticalMove ){
								return {"top" : ( $("." + settings.cWrapperClass).position().top ) - moveH};
							} else {
								return {"left" : ( $("." + settings.cWrapperClass).position().left ) - moveW};
							}
						};
						
						if ( forwardYn ){
							$("." + settings.cWrapperClass).animate(
								$wrapper(),
								function(){
									scrollingYn = true;
									for ( var iii=0; iii<moveCnt; iii++ ){
										$("." + settings.cWrapperClass).append($("." + settings.cListClass).eq(0));
									}
									$("." + settings.cWrapperClass).css({"left" : "0", "top" : "0"});
									fn_func.setDots();
								}
							);
						} else {
							for ( var iii=0; iii<moveCnt; iii++ ){
								$("." + settings.cWrapperClass).prepend($("." + settings.cListClass).eq(-1));
							}
							if ( settings.verticalMove ){
								$("." + settings.cWrapperClass).css("top", $("." + settings.cWrapperClass).position().top + moveH);
							} else {
								$("." + settings.cWrapperClass).css("left", $("." + settings.cWrapperClass).position().left + moveW);
							}
							$("." + settings.cWrapperClass).animate(
								$wrapper(),
								function(){ 
									scrollingYn = true;
									fn_func.setDots();
								}
							);
						}
						
						//자동롤링 다시 시작
						if ( settings.autoScrollYn ){
							if ( settings.scorllTimer ) {
				            	tmpTmr = setInterval(function(){
				            		fn_func.setMove(1);
				            	}, settings.scorllTimer );
				            }
						}
					}
					
				}
			}
			
		});
	};
})(jQuery);