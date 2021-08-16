<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
#fregister section {
    margin: 0 0 20px;
    padding: 20px 0;
}

#register_wrap { position:relative;background:#fbfbfb;}
#register_wrap .mbskin { position:relative;width:1000px;height:640px;border:1px solid #d1d1d1;border-radius:10px;margin-top:50px;background:#fff;}

#register_txt { position:absolute;width:910px;height:40px;border-bottom:1px solid #d1d1d1;left:45px;top:10px;}
#register_txt h2 {position:absolute;font-size:25px;top:20px;color:#282828;left:0;}
#register_txt label { position:absolute;font-size:15px;color:#626262;top:30px;right:45px;}
#agree_all_img { position:absolute;right:0;top:25px;cursor:pointer;}


#fregister_term { position:absolute;width:440px;height:400px;left:45px;top:120px;}
#fregister_term h2 {position:absolute;font-size:18px;color:#666666;left:0;top:0;}
#fregister_term .all_view_btn {position:absolute;font-size:13px;color:#808080;border:1px solid #d1d1d1;right:0;top:0;padding:5px 10px;}
#fregister_term textarea { position:absolute;width:420px;height:290px;border:1px solid #d1d1d1;left:0;top:40px;margin:0;padding:10px;background:#fff;}
#fregister_term .texta { position:absolute;width:420px;height:290px;border:1px solid #d1d1d1;left:0;top:40px;margin:0;padding:10px;background:#fff;overflow-y:scroll;}

#fregister_term label { position:absolute;font-size:15px;color:#626262;top:365px;right:45px;}
#agree_img { position:absolute;right:0;top:360px;cursor:pointer;}

#fregister_private { position:absolute;width:440px;height:400px;right:45px;top:120px;}
#fregister_private h2 {position:absolute;font-size:18px;color:#666666;left:0;top:0;}
#fregister_private .all_view_btn {position:absolute;font-size:13px;color:#808080;border:1px solid #d1d1d1;right:0;top:0;padding:5px 10px;}
#fregister_private textarea { position:absolute;width:420px;height:290px;border:1px solid #d1d1d1;left:0;top:40px;margin:0;padding:10px;background:#fff;}
#fregister_private .texta { position:absolute;width:420px;height:290px;border:1px solid #d1d1d1;left:0;top:40px;margin:0;padding:10px;background:#fff;overflow-y:scroll;}
#fregister_private label { position:absolute;font-size:15px;color:#626262;top:365px;right:45px;}
#agree2_img { position:absolute;right:0;top:360px;cursor:pointer;}

#register_wrap .btn_confirm { position:absolute;width:100%;top:580px;text-align:center;}
#fregister p { text-align:left;color:#333333;}

.sub_boxs { position:relative;width:1027px;height:330px;margin:0 auto;}
.sub_boxs  a { position:absolute;left:100px;top:220px;background:#1e75d6;color:#ffffff;font-size:16px;padding: 13px 40px;}
.dr_box { position:relative;width:342px;height:330px;background:url("http://www.keymedi.com/img/etc/dr_join.jpg");float:left;}
.acd_box { position:relative;width:343px;height:330px;background:url("http://www.keymedi.com/img/etc/acd_join.jpg");float:left;}
.cop_box { position:relative;width:342px;height:330px;background:url("http://www.keymedi.com/img/etc/cop_join.jpg");float:left;}

#popup_reg_wrap_block { position:fixed;width:100%;height:2000px;top:0;left:0;background:#000000;z-index:1000;opacity:0.6; }
#popup_reg_wrap { position:fixed;width:100%;height:2000px;left:0;top:0;z-index:1000;}
#popup_reg { position:relative;width:490px;height:515px;background:url("/img/agree_login_pop_pc.png") center top no-repeat;margin:0 auto;top:150px;}
#popup_reg .pop_btn01 { position:absolute;right:30px;top:35px;width:50px;height:50px;}
#popup_reg .pop_btn02 { position:absolute;left:145px;top:416px;width:200px;height:50px;}
p.si_txt { position:absolute;left:45px;top:520px;font-size:15px;color:#626262 !important;}
</style>
<!-- 회원가입약관 동의 시작 { -->
<!-- <div id="popup_reg_wrap_block"></div>
<div id="popup_reg_wrap">
	<div id="popup_reg">
		<a href="#" onclick="pop_cl();return false;"><img src="http://www.keymedi.com/img/nt.png" alt="" class="pop_btn01"></a>
		<a href="http://obgy.keymedi.com/"><img src="http://www.keymedi.com/img/nt.png" alt="" class="pop_btn02"></a>
	</div>
</div>

<script>
function pop_cl(){
	document.getElementById("popup_reg_wrap_block").style.display = "none";
	document.getElementById("popup_reg_wrap").style.display = "none";
}
</script>
 -->
<div id="register_wrap">
<h2><img src="http://www.keymedi.com/img/etc/process01.png" alt=""></h2>
<div class="mbskin">
    <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">
	<input type="hidden" name="member_v" value="1" /> 
	<input type="hidden" name="agree_all" value="0" id="agree_all">
	<input type="hidden" name="agree" value="0" id="agree">
	<input type="hidden" name="agree2" value="0" id="agree2">
	<input type="hidden" name="v" value="<?=$v?>" >
	<section id="register_txt">
		<h2>01.약관 동의</h2>
		 <fieldset class="register_txt_agree">
            <label for="agree10">서비스 이용약관, 개인정보처리방침의 약관에 모두 동의합니다.</label>
            <img src="http://www.keymedi.com/img/etc/check_btn.png" alt="" id="agree_all_img" onclick="ck_agree_all();return false;">
        </fieldset>
	</section>

   <section id="fregister_term">
        <h2>키메디몰 서비스 이용약관</h2>
		<?
			//	210315 - jinam23 - 변경
			//$sql = " select * from {$g5['content_table']} where co_id = 'provision' ";
			//$sql = "select * from portal.g5_write_provision where 1=1 and wr_10 = '1' order by wr_id desc limit 1";
			$sql = " select * from g5_content where co_id = 'provision_km' ";
			$co = sql_fetch($sql);		
		?>
		<!-- <a href="#" class="all_view_btn">전체보기</a> -->
		<div class="texta"><?php echo $co['co_content'] ?></div>
        <fieldset class="fregister_agree">
            <label for="agree11">동의합니다.</label>
            <img src="http://www.keymedi.com/img/etc/check_btn.png" alt="" id="agree_img" onclick="ck_agree();return false;">
        </fieldset>
    </section>

    <section id="fregister_private">
        <h2>개인정보 수집 및 이용에 대한 안내</h2>
		<?
			//	210315 - jinam23 - 변경		
			//$sql = " select * from {$g5['content_table']} where co_id = 'privacy' ";
			//$sql = "select * from portal.g5_write_privacy where 1=1 and wr_10 = '1' order by wr_id desc limit 1";
			$sql = " select * from g5_content where co_id = 'privacy_km' ";
			$co = sql_fetch($sql);		
		?>
		<!-- <a href="#" class="all_view_btn">전체보기</a> -->
		<div class="texta"><?php echo $co['co_content'] ?></div>
        <fieldset class="fregister_agree">
            <label for="agree22">동의합니다.</label>
            <img src="http://www.keymedi.com/img/etc/check_btn.png" alt="" id="agree2_img" onclick="ck_agree2();return false;">
        </fieldset>
    </section>
	<p class="si_txt">
		※ 전문 의료지식 포털사이트 '키메디'에 동시 가입됩니다.<br>
		키메디란? 학술세미나 또는 유익한 강의를 들을수 있는 의료지식포털사이트!
	</p>
<!--
	<div class="sub_boxs">
		<div class="dr_box">
			<a href="#" onclick="member_sub('1');return false;">가입하기</a>
		</div>
		<div class="acd_box">
			<a href="#" onclick="member_sub('2');return false;">가입하기</a>
		</div>
		<div class="cop_box">
			<a href="#" onclick="member_sub('3');return false;">가입하기</a>
		</div>
	</div> -->

    <div class="btn_confirm">
        <input type="submit" class="btn_submit" value="회원가입">
    </div>

    </form>

    <script>
	function ck_agree(){
		if(document.getElementById("agree").value == "0"){
			document.getElementById("agree_img").src = "http://www.keymedi.com/img/etc/check_btnov.png";
			document.getElementById("agree").value = "1";
		}else{
			document.getElementById("agree_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
			document.getElementById("agree").value = "0";
			if(document.getElementById("agree_all_img").src == "http://www.keymedi.com/img/etc/check_btnov.png"){
				document.getElementById("agree_all_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
				document.getElementById("agree_all").value = "0";
			}
		}
		
	}
	function ck_agree2(){
		if(document.getElementById("agree2").value == "0"){
			document.getElementById("agree2_img").src = "http://www.keymedi.com/img/etc/check_btnov.png";
			document.getElementById("agree2").value = "1";
		}else{
			document.getElementById("agree2_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
			document.getElementById("agree2").value = "0";
			if(document.getElementById("agree_all_img").src == "http://www.keymedi.com/img/etc/check_btnov.png"){
				document.getElementById("agree_all_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
				document.getElementById("agree_all").value = "0";
			}
		}
		
	}
	function ck_agree_all(){
		if(document.getElementById("agree_all").value == "0"){
			document.getElementById("agree_all_img").src = "http://www.keymedi.com/img/etc/check_btnov.png";
			document.getElementById("agree_img").src = "http://www.keymedi.com/img/etc/check_btnov.png";
			document.getElementById("agree2_img").src = "http://www.keymedi.com/img/etc/check_btnov.png";
			document.getElementById("agree_all").value = "1";
			document.getElementById("agree").value = "1";
			document.getElementById("agree2").value = "1";
		}else{
			document.getElementById("agree_all_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
			document.getElementById("agree_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
			document.getElementById("agree2_img").src = "http://www.keymedi.com/img/etc/check_btn.png";
			document.getElementById("agree_all").value = "0";
			document.getElementById("agree").value = "0";
			document.getElementById("agree2").value = "0";
		}
	}
	function member_sub(num){
		var f = document.fregister;
		f.member_v.value = num;

		if (f.agree.value != "1") {
            alert("웹 사이트 이용약관에 동의하셔야 회원가입 하실 수 있습니다.");
             
            return false;
        }

        if (f.agree2.value != "1") {
            alert("개인정보 수집 및 이용에 대한 안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
             
            return false;
        }
		f.submit();
		
	}

    function fregister_submit(f)
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
</div>
</div>
<!-- } 회원가입 약관 동의 끝 -->