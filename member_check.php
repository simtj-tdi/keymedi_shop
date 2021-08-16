<style>
	#layer_agree {position:relative;top:50px;width:500px;background-color:#f8f8f8;border:2px solid #37aad9;z-index:10006;margin:0 auto;}
	#layer_agree .layer_close_btn {position:absolute;right:20px;top:20px;}
	#layer_agree h2 {text-align:center;font-size:15px;font-weight:normal;}
	#layer_agree .all_agree {margin:0 auto;text-align:left;width:428px;background-color:#fff;font-size:17px;font-weight:normal;margin-top:20px;padding:20px;border:1px solid #ccc;}
	#layer_agree .agree_01 {margin:0 auto;text-align:left;width:428px;background-color:#fff;font-size:17px;font-weight:normal;position: relative;margin-top:10px;padding:20px;border:1px solid #ccc;}
	#layer_agree .agree_box {margin-top:20px;margin-bottom:30px;text-align:center;}
	#agree_view01 , #agree_view02 {position: absolute;right:20px;font-size:13px;color:#37aad9;top:24px;text-decoration:underline;cursor: pointer;}
	#agree_all_img , #agree_img , #agree2_img {vertical-align: top;position: relative;margin-right:8px;cursor: pointer;}
	#agree_text01 , #agree_text02 {position: relative;background-color:#d7d7d7}
	#layer_agree .agree_text_style {width:380px;height:100px;border:1px solid #d1d1d1;left:0;top:15px;margin:0;padding:10px;background:#f8f8f8;overflow-y:scroll;margin-bottom:10px;}
	#layer_agree .agree_text_style p {font-size:12px;}
	#black_box {position:fixed;width:100%;height:2000px;left:0px;top:0px;background-color:#000;z-index:10005;opacity:0.7;}
</style>

<div id="black_box" style="display:none;" onclick="close_layer();"></div>
<div id="layer_agree" style="display:none">
	<form  name="agree_form" id="agree_form" action="/member_agree.php" onsubmit="return agree_form_submit(this);" method="POST" autocomplete="off">
		<input type="hidden" name="agree_all" value="0" id="agree_all">
		<input type="hidden" name="agree" value="0" id="agree">
		<input type="hidden" name="agree2" value="0" id="agree2">
		<input type="hidden" name="pointzone" value="0" id="pointzone">
		
		<p style="text-align:center;font-size:20px;margin-top:35px;margin-bottom:25px">지식몰(키메디)에 가입 동의하고,<br>다양한 의료학술 세미나를 이용해보세요.</p>

		<p id="pointzone_bn" style="display:none;"><img src="<?php echo G5_IMG_URL ?>/main/layer_agree/popup_point_br.jpg" /></p>
		
		<a href="#" onclick="close_layer();return false;"><img src="<?php echo G5_IMG_URL ?>/main/layer_agree/close.png" alt="닫기" class="layer_close_btn" /></a>
		
		<p class="all_agree"><img src="<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png" alt="전체동의" id="agree_all_img" onclick="ck_agree_all();return false;" />약관에 전체 동의합니다.</p>
		<div class="agree_01">
			<img src="<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png" alt="동의" id="agree_img" onclick="ck_agree();return false;"/>키메디 서비스 이용약관 동의 <span id="agree_view01" onclick="aaa();"><small>▼</small>전문보기</span>
			<? //서비스 이용약관
				$sql = " select * from portal.g5_content where co_id = 'provision' ";
				$co = sql_fetch($sql);		
			?>
			<div id="agree_text01" style="display:none;" class="agree_text_style"><?php echo $co['co_content'] ?></div>
		</div>
		<div class="agree_01">
			<img src="<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png" alt="동의" id="agree2_img" onclick="ck_agree2();return false;"/>개인정보 수집 및 이용 동의 <span id="agree_view02" onclick="bbb();"><small>▼</small>전문보기</span>
			<? //개인정보 수집 및 이용
				$sql = " select * from portal.g5_content where co_id = 'privacy' ";
				$co = sql_fetch($sql);		
			?>
			<div id="agree_text02" style="display:none;" class="agree_text_style"><?php echo $co['co_content'] ?></div>
		</div>
		<div class="agree_box"><input type="image" value="가입완료" class="agree_btn" src="<?php echo G5_IMG_URL ?>/main/layer_agree/join_btn.png"></div>
	</form>
</div>

<script>
	function open_layer(id,url,aa){
		document.getElementById("layer_agree").style.display = "block";
		document.getElementById("black_box").style.display = "";
		document.getElementById("pointzone").value = "0";
		if(aa == 'point'){
			document.getElementById("pointzone_bn").style.display = "block";
			document.getElementById("pointzone").value = "1";
		}

		document.getElementById(id).style.display = "";
	}

	function close_layer(){
		document.getElementById("layer_agree").style.display = "none";
		$("#black_box").css("display","none");
		$("#layer_agree").css("display","none");
		$("#pointzone_bn").css("display","none");
	}

	var agree_view01_txt = document.getElementById("agree_view01");
	var agree_view02_txt = document.getElementById("agree_view02");

	function aaa(){
		if(document.getElementById("agree_text01").style.display == "none"){
			if(document.getElementById("agree_text02").style.display == "block"){							
				document.getElementById("agree_text02").style.display = "none";
				agree_view02_txt.innerHTML = "<small>▼</small>전문보기";
			}
			document.getElementById("agree_text01").style.display = "block";
			agree_view01_txt.innerHTML = "<small>△</small>전문보기";
		}else {
			document.getElementById("agree_text01").style.display = "none";
			agree_view01_txt.innerHTML = "<small>▼</small>전문보기";
		}
	}
	function bbb(){
		if(document.getElementById("agree_text02").style.display == "none"){
			if(document.getElementById("agree_text01").style.display == "block"){							
				document.getElementById("agree_text01").style.display = "none";
				agree_view01_txt.innerHTML = "<small>▼</small>전문보기";
			}
			document.getElementById("agree_text02").style.display = "block";
			agree_view02_txt.innerHTML = "<small>△</small>전문보기";
		}else{
			document.getElementById("agree_text02").style.display = "none";
			agree_view02_txt.innerHTML = "<small>▼</small>전문보기";
		}
	}

	function ck_agree(){
		if(document.getElementById("agree").value == "0"){
			document.getElementById("agree_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png";
			document.getElementById("agree").value = "1";
		}else{
			document.getElementById("agree_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
			document.getElementById("agree").value = "0";
			if(document.getElementById("agree_all_img").src == "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png"){
				document.getElementById("agree_all_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
				document.getElementById("agree_all").value = "0";
			}
		}
		
	}
	function ck_agree2(){
		if(document.getElementById("agree2").value == "0"){
			document.getElementById("agree2_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png";
			document.getElementById("agree2").value = "1";
		}else{
			document.getElementById("agree2_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
			document.getElementById("agree2").value = "0";
			if(document.getElementById("agree_all_img").src == "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png"){
				document.getElementById("agree_all_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
				document.getElementById("agree_all").value = "0";
			}
		}
		
	}
	function ck_agree_all(){
		if(document.getElementById("agree_all").value == "0"){
			document.getElementById("agree_all_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png";
			document.getElementById("agree_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png";
			document.getElementById("agree2_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btnov.png";
			document.getElementById("agree_all").value = "1";
			document.getElementById("agree").value = "1";
			document.getElementById("agree2").value = "1";
		}else{
			document.getElementById("agree_all_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
			document.getElementById("agree_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
			document.getElementById("agree2_img").src = "<?php echo G5_IMG_URL ?>/main/layer_agree/check_btn.png";
			document.getElementById("agree_all").value = "0";
			document.getElementById("agree").value = "0";
			document.getElementById("agree2").value = "0";
		}
	}

	function agree_form_submit(f)
	{
		if (f.agree.value != "1") {
			alert("웹 사이트 이용약관에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree.focus();
			return false;
		}

		if (f.agree2.value != "1") {
			alert("개인정보 수집 및 이용에 대한 안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree2.focus();
			return false;
		}

		return true;
	}
</script>