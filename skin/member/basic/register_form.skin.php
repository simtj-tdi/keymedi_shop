<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($member[mb_where] != "피부비만" && $w == "u"){
	alert("키메디 회원만 접근하실수 있습니다.","http://www.keymedi.com/");
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
#register_wrap h1 { position:relative;width:100%;margin:20px 0;font-size: 38px;font-weight: 500;text-align: center; }
h4 { position:relative;width:100%;height:45px;font-size:25px;}

h5 { position:relative;width:100%;height:30px;font-size:18px;}

table.write_tb { position:relative;width:100%;border-top:1px solid #a0a0a0;border-collapse: collapse; border-spacing: 0;}
table.write_tb td { height:40px;border-bottom:1px solid #d1d1d1;font-size:14px;text-indent:20px;}
table.write_tb th { width:130px;height:40px;border-bottom:1px solid #d1d1d1;font-size:14px;background:#ebebeb;}

table.write_tb td.upline {border-bottom:1px solid #a0a0a0;}
table.write_tb th.upline {border-bottom:1px solid #a0a0a0;}
table.write_tb .new_v th {background:#979797;color:#fff;border-bottom:1px solid #d8d8d8;}
table.write_tb .new_v td {background:#f8f8f8;}
table.write_tb .new_v td input { background:#fff;}

#register_wrap { position:relative;background:#fbfbfb;}
#register_wrap .mbskin { position:relative;width:900px;height:auto;border:1px solid #d1d1d1;border-radius:10px;margin-top:50px;background:#fff;padding:45px;}

</style>

<? if($_SERVER['HTTPS'] == "on"){ ?>
<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<? }else{?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<? } ?>
 
<style type="text/css">
<!--
.ui-datepicker { font:12px dotum; }
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 70px;}
.ui-datepicker-trigger { margin:0 0 -5px 2px; cursor:pointer;}
-->
</style> 

<script type="text/javascript">
jQuery(function($){
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
		'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yymmdd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ko']);

    $('.datepicker').datepicker({
        changeMonth: true,
		changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
//        minDate: '+2d',
		onSelect: function(dateText, inst) { } 
	  
    });
});

$(document).ready(function () {
    $('#mb_9_sub').css('display', 'none');

    $('#mb_9').on('change', function () {

        var part = this.value;
        var sub_part='<?=$member['mb_9_sub']?>';
        var part_array = new Array("내과", "외과", "산부인과", "피부과", "치과");

        if (part_array.indexOf(part) != -1) {
            $.post("/bbs/ajaxselect.php", {optVal: this.value,optSubVal: sub_part}, function (data) {
                $('#mb_9_sub').css('display', '');
                $('#mb_9_sub').empty();
                $('#mb_9_sub').append('<option value="">선택해주세요</option>');
                $('#mb_9_sub').append(data);
            });
        } else {
            $('#mb_9_sub').css('display', 'none');
        }
    });
});
</script>
<!-- 회원정보 입력/수정 시작 { -->
 <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div id="register_wrap">
	<!-- <h1>회원가입</h1> -->
<? if(!$member[mb_id]){?>
	<h2><img src="http://www.keymedi.com/img/etc/process02.png" alt=""></h2>
<? }else{ ?>
<style>#register_wrap .mbskin {margin-top:0;}</style>
<?  }?>
<div class="mbskin">
	
    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js"></script>
    <?php } ?>

    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="url" value="<?php echo $urlencode ?>">
    <input type="hidden" name="agree" value="<?php echo $agree ?>">
    <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="agree3" id="agree3" value="<?php echo $agree3 ?>">
    <input type="hidden" name="agree4" id="agree4" value="<?php echo $agree4 ?>">
 

    <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
    <input type="hidden" name="cert_no" value="">
    <?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
    <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
    <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
    <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
    <?php }  ?>
	<input type="hidden" name="mb_v" value="<?=($member[mb_v]=="")?$member_v:$member[mb_v]?>" />
	<input type="hidden" name="mb_where" value="<?=($member[mb_where])?$member[mb_where]:"메디포털"?>" /> 
    <input type="hidden" name="mb_17" value="<?=$member[mb_17]?>" />
    <div>
		<h4><?=($member[mb_id])?"":"02."?> 가입정보 <?=($member[mb_id])?"수정":"입력"?></h4>
		<p>저희 키메디몰은 <span style="color:#e63232;">병의원 사업자</span>를 위한 쇼핑몰로써 사업자 회원만 구매가 가능합니다.</p>
		<h5>개인정보</h5>
        <table class="write_tb">
        <!-- <caption>사이트 이용정보 입력</caption> -->
        <tbody>
		<tr>
            <th scope="row"><label for="reg_mb_name"><span style="color:#e63232;">*</span>이름<strong class="sound_only">필수</strong></label></th>
            <td>
                
                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" size="10" <? if($config[cf_cert_use]!="0"){?>readonly="readonly"  onclick="ck_names(this.value);return false;" <? } ?> >
                <?php
                if($config['cf_cert_use']) {
                    if($config['cf_cert_ipin'])
                        echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>'.PHP_EOL;
                    if($config['cf_cert_hp'])
                        //echo '<button type="button" id="win_hp_cert" class="btn_frmline" style="text-indent:0;">휴대폰 본인확인</button>'.PHP_EOL;
					
					//if($_SERVER['REMOTE_ADDR'] == "121.134.72.163"){
						
						if($agree3 == "1" && $agree4 =="1"){
							echo '<button type="button" id="win_hp_cert_new2" class="btn_frmline" style="text-indent:0;">키메디 가입확인</button>'.PHP_EOL;
						}else{
							echo '<button type="button" id="win_hp_cert_new" class="btn_frmline" style="text-indent:0;">휴대폰 본인확인</button>'.PHP_EOL;
						}
					//}

                    echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                }
                ?>
                <?php
                if ($config['cf_cert_use'] && $member['mb_certify']) {
                    if($member['mb_certify'] == 'ipin')
                        $mb_cert = '아이핀';
                    else
                        $mb_cert = '휴대폰';
                ?>
                <div id="msg_certify">
                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                </div>
                <?php } ?>
				<!-- <span style="color:#e63232;font-size:13px;">휴대폰 본인확인은 인터넷 익스플로러에서만 가능합니다.</span> -->
            </td>
        </tr>
		<input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
		<input type="hidden" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
	
		<!-- 
        <?php if ($req_nick) {  ?>
        <tr>
            <th scope="row"><label for="reg_mb_nick"><span style="color:#e63232;">*</span>닉네임<strong class="sound_only">필수</strong></label></th>
            <td> 
				<? if($w=="u"){?>
				<input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
				<input type="hidden" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
				<?php echo $member['mb_nick'] ?>
				<?}else{?>
                <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
                <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="frm_input required nospace" size="10" maxlength="20">
                <span id="msg_mb_nick"></span>
				<span class="frm_info">
                    공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)  
                </span>
				<? } ?>
            </td>
        </tr>
        <?php }  ?> -->
		<? if($config['cf_cert_use'] == 0){ ?>
		<tr>
			<th scope="row"><label for="mb_sex"><span style="color:#e63232;">*</span>성별<strong class="sound_only">필수</strong></label></th>
            <td>
				<input type="radio" name="mb_sex" value="M" required>&nbsp;남&nbsp;&nbsp;&nbsp;<input type="radio" name="mb_sex" value="F" required>&nbsp;여
			</td>        
		</tr>
		<? } ?>
		<tr>
			<th scope="row"><label for="reg_mb_birth"><span style="color:#e63232;">*</span>생년월일<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="mb_birth" id="reg_mb_birth" value="<?=$member[mb_birth]?>" <?php echo $required ?>  class="frm_input required  <? if($config[cf_cert_use]=="0"){?>datepicker<? } ?>" required <? if($config[cf_cert_use]!="0"){?>readonly="readonly"<? } ?> size="10"> 예) 19600101 </td>        
		</tr>
        <tr>
            <th scope="row"><label for="reg_mb_id"><span style="color:#e63232;">*</span>아이디<strong class="sound_only">필수</strong></label></th>
            <td>
                <? if($readonly=="readonly"){?>
				<?php echo $member['mb_id'] ?>
				<input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20">
				<?}else{?>
                <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20">
                <span id="msg_mb_id"></span>
				<?
				if($agree3 == "1" && $agree4 =="1"){ }else{
					//echo '<button type="button" onclick="mem_ck();" class="btn_frmline" style="text-indent:0;">중복확인</button>'.PHP_EOL;
				}
				?>
				
				<span class="frm_info">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
				<? } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="reg_mb_password"><span style="color:#e63232;">*</span>비밀번호<strong class="sound_only">필수</strong></label></th>
            <td><input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="reg_mb_password_re"><span style="color:#e63232;">*</span>비밀번호 확인<strong class="sound_only">필수</strong></label></th>
            <td><input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20"></td>
        </tr>
     
 
 

        <tr>
            <th scope="row" class="upline"><label for="reg_mb_hp"><span style="color:#e63232;">*</span>휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td class="upline">
				
				<!-- <select name="wr_hp1" id="wr_hp1" class="frm_input" style="height:24px;width:90px;" >
					<option value="010" <?=($mb_hp[0]=="010")?"selected":"" ?>>010&nbsp;</option>
					<option value="011" <?=($mb_hp[0]=="011")?"selected":"" ?>>011</option>
					<option value="016" <?=($mb_hp[0]=="016")?"selected":"" ?>>016</option>
					<option value="017" <?=($mb_hp[0]=="017")?"selected":"" ?>>017</option>
					<option value="018" <?=($mb_hp[0]=="018")?"selected":"" ?>>018</option>
					<option value="019" <?=($mb_hp[0]=="019")?"selected":"" ?>>019</option>
				</select> -
				<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_hp2" value="<?=$mb_hp[1]?>" name="wr_hp2"> -
				<input type="text" maxlength="4" size="5" class="frm_input required" required="" id="wr_hp3" value="<?=$mb_hp[2]?>" name="wr_hp3"> -->
				
				<? if($w=='u'){ ?>
					<input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp"  class="frm_input required" required maxlength="20" readonly>
				<? }else{ ?>
					<input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp"  class="frm_input required" required maxlength="20" >
				<? } ?>                
                <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">				
                <?php } ?>
				<? if($w=='u'){ ?>
				<span style="color:#e63232;font-size:13px;">휴대폰 변경이 필요하신 경우, 휴대폰본인확인을 해주세요.</span>
				<? } ?>
            </td>
        </tr> 
		

		<tr>
			<th scope="row"><label for="reg_mb_1"><span style="color:#e63232;">*</span>의사면허번호<strong class="sound_only">필수</strong></label></th>
			<td>
				<? if($w=='u'){?>
				<? echo $member[mb_1]; ?>
				<input type="hidden" name="mb_1" value="<?php echo $member['mb_1'] ?>" id="reg_mb_1" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20">
				<?}else{?>
				<input type="text" name="mb_1" id="reg_mb_1" value="<?=$member[mb_1]?>" required class="frm_input required" minlength="3" maxlength="20">
				<span style="color:#e63232;font-size:13px;">의사번호를 입력해주세요.</span>
				<? } ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="reg_mb_9"><span style="color:#e63232;">*</span>대표진료과</label></th>
			<td>
                <select name="mb_9" id="mb_9" required class="frm_input required">
                    <option value="" <?= ($member[mb_9] == "") ? "selected" : "" ?> >- 선택해주세요 -</option>
                    <?
                    for ($i = 0; $i < count($part_arr); $i++) {
                        $selected = $member[mb_9] == $part_arr[$i] ? selected : "";
                        echo "<option value='" . $part_arr[$i] . "' " . $selected . ">" . $part_arr[$i] . "</option>";
                    }
                    ?>
                </select>
                &nbsp;
                <select name="mb_9_sub" id="mb_9_sub" class="frm_input">
                </select>
			</td>
		</tr>
		<tbody>
		</table>

		<h5>사업자 정보</h5>

		<table class="write_tb">
        <!-- <caption>사이트 이용정보 입력</caption> -->
        <tbody>

		<!-- 
		<tr>
			<th scope="row"><label for="reg_mb_11">근무처<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="mb_11" value="<?php echo $member['mb_11']; ?>" id="reg_mb_11"  class="frm_input" maxlength="20"></td>
		</tr> 
		-->

		<!--  
		<tr>
            <th scope="row">
                주소
                <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php }  ?>
            </th>
            <td>
                <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6" style="margin-top:5px;">
                <button type="button"  style="text-indent:0;margin-top:5px;" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address <?php echo $config['cf_req_addr']?"required":""; ?>" size="50">
                <label for="reg_mb_addr1">기본주소<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address" size="50">
                <label for="reg_mb_addr2">상세주소</label>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address" size="50" readonly="readonly" style="margin-bottom:5px;">
                <label for="reg_mb_addr3">참고항목</label>
                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
            </td>
        </tr>
		<? $mb_tel = explode('-' , $member['mb_tel']);?>
		<tr>
            <th scope="row"><label for="reg_mb_tel">전화번호<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td>
			<select name="mb_tel1" id="mb_tel1" class="frm_input" style="height:24px;width:90px;" >
					<option value="02" <?=($mb_tel[0]=="02")?"selected":"" ?>>02&nbsp;</option>
					<option value="031" <?=($mb_tel[0]=="031")?"selected":"" ?>>031</option>
					<option value="032" <?=($mb_tel[0]=="032")?"selected":"" ?>>032</option>
					<option value="033" <?=($mb_tel[0]=="033")?"selected":"" ?>>033</option>
					<option value="041" <?=($mb_tel[0]=="041")?"selected":"" ?>>041</option>
					<option value="042" <?=($mb_tel[0]=="042")?"selected":"" ?>>042</option>
					<option value="043" <?=($mb_tel[0]=="043")?"selected":"" ?>>043</option>
					<option value="051" <?=($mb_tel[0]=="051")?"selected":"" ?>>051</option>
					<option value="052" <?=($mb_tel[0]=="052")?"selected":"" ?>>052</option>
					<option value="053" <?=($mb_tel[0]=="053")?"selected":"" ?>>053</option>
					<option value="054" <?=($mb_tel[0]=="054")?"selected":"" ?>>054</option>
					<option value="055" <?=($mb_tel[0]=="055")?"selected":"" ?>>055</option>
					<option value="061" <?=($mb_tel[0]=="061")?"selected":"" ?>>061</option>
					<option value="062" <?=($mb_tel[0]=="062")?"selected":"" ?>>062</option>
					<option value="063" <?=($mb_tel[0]=="063")?"selected":"" ?>>063</option>
					<option value="064" <?=($mb_tel[0]=="064")?"selected":"" ?>>064</option>
					<option value="044" <?=($mb_tel[0]=="044")?"selected":"" ?>>044</option>
					<option value="070" <?=($mb_tel[0]=="070")?"selected":"" ?>>070</option>

				</select> -
				<input type="text" maxlength="4" size="5" class="frm_input" id="mb_tel2" value="<?=$mb_tel[1]?>" name="mb_tel2"> -
				<input type="text" maxlength="4" size="5" class="frm_input" id="mb_tel3" value="<?=$mb_tel[2]?>" name="mb_tel3">
				<input type="hidden" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel"  class="frm_input" maxlength="20">
				</td>
        </tr>


		
		<tr>
			<th scope="row"><label for="reg_mb_4">근무형태<strong class="sound_only">필수</strong></label></th>
			<td>
				<select name="mb_4" required class="frm_input required">
					<option value="" <?=($member[mb_4]=="")?"selected":""?>>- 선택해주세요 -</option>
					<option value="개원의" <?=($member[mb_4]=="개원의")?"selected":""?>>개원의</option>
					<option value="봉직의" <?=($member[mb_4]=="봉직의")?"selected":""?>>봉직의</option>
					<option value="인턴" <?=($member[mb_4]=="인턴")?"selected":""?>>인턴</option>
					<option value="레지던트" <?=($member[mb_4]=="레지던트")?"selected":""?>>레지던트</option>
					<option value="교직" <?=($member[mb_4]=="교직")?"selected":""?>>교직</option>
					<option value="전임의" <?=($member[mb_4]=="전임의")?"selected":""?>>전임의</option>
					<option value="공보의" <?=($member[mb_4]=="공보의")?"selected":""?>>공보의</option>
					<option value="군의관" <?=($member[mb_4]=="군의관")?"selected":""?>>군의관</option>
					<option value="휴직" <?=($member[mb_4]=="휴직")?"selected":""?>>휴직</option>
					<option value="해외" <?=($member[mb_4]=="해외")?"selected":""?>>해외</option>
					<option value="은퇴" <?=($member[mb_4]=="은퇴")?"selected":""?>>은퇴</option>
					<option value="기타" <?=($member[mb_4]=="기타")?"selected":""?>>기타</option>
				</select>
			</td>
		</tr> -->
	<? /* ?>
		<tr>
			<th scope="row"><label for="reg_mb_21">학회선택<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" maxlength="20" size="20" class="frm_input "  id="mb_21" value="<?=$member[mb_21]?>" name="mb_21"  readonly="readonly">
				<select name="mb_21_1" class="frm_input" onchange="javascript:set_mb_21(this.value);">
					<option value=" " >- 선택해주세요 -</option>
					<option value="대한검진의학회" <?=($member[mb_21]=="대한검진의학회")?"selected":""?>>대한검진의학회</option>
					<option value="대한노인의학회" <?=($member[mb_21]=="대한노인의학회")?"selected":""?>>대한노인의학회</option>
					<option value="대한밸런스의학회" <?=($member[mb_21]=="대한밸런스의학회")?"selected":""?>>대한밸런스의학회</option>
					<option value="대한산부인과의사회" <?=($member[mb_21]=="대한산부인과의사회")?"selected":""?>>대한산부인과의사회</option>
					<option value="대한성장의학회" <?=($member[mb_21]=="대한성장의학회")?"selected":""?>>대한성장의학회</option>
					<option value="대한약물영양의학회" <?=($member[mb_21]=="대한약물영양의학회")?"selected":""?>>대한약물영양의학회</option>
					<option value="대한여성성의학회" <?=($member[mb_21]=="대한여성성의학회")?"selected":""?>>대한여성성의학회</option>
					<option value="대한외과의사회" <?=($member[mb_21]=="대한외과의사회")?"selected":""?>>대한외과의사회</option>
					<option value="대한일차진료학회" <?=($member[mb_21]=="대한일차진료학회")?"selected":""?>>대한일차진료학회</option>
					<option value="한국임상고혈압학회" <?=($member[mb_21]=="한국임상고혈압학회")?"selected":""?>>한국임상고혈압학회</option>
					<option value="대한흉부심장혈관외과의사회" <?=($member[mb_21]=="대한흉부심장혈관외과의사회")?"selected":""?>>대한흉부심장혈관외과의사회</option>
					<option value="최소침습성형연구회" <?=($member[mb_21]=="최소침습성형연구회")?"selected":""?>>최소침습성형연구회</option>
					<option value="대한정주의학회" <?=($member[mb_21]=="대한정주의학회")?"selected":""?>>대한정주의학회</option>
					<!-- <option value="대한임상암대사의학회" <?=($member[mb_21]=="대한임상암대사의학회")?"selected":""?>>대한임상암대사의학회</option>
					<option value="제암거슨의학회" <?=($member[mb_21]=="제암거슨의학회")?"selected":""?>>제암거슨의학회</option> -->
					<option value="하지정맥류연구회" <?=($member[mb_21]=="하지정맥류연구회")?"selected":""?>>하지정맥류연구회</option>
					<option value="없음" <?=($member[mb_21]=="없음")?"selected":""?>>없음</option>
					<option value="" >기타</option>
				</select>
				<script>
					function set_mb_21(val) {
						if(val){
							$("#mb_21").val(val).attr("readonly", true);
						}else{
							$("#mb_21").val("").attr("readonly", false);
						}
					}
				</script>
			</td>
		</tr>

		<tr>
			<th scope="row" class="upline"><label for="mb_icon">의사인증파일<strong class="sound_only">필수</strong></label></th>
			<td class="upline">
							
				<?php
				if(!$member['mb_id']){
					$mb_ids = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";
				}else{
					$mb_ids = $member['mb_id'];
				}

				$mb_dir = substr($mb_ids,0,2);
				$icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_ids;
				if (file_exists($icon_file)) {
					$icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_ids;
					echo '<a href="'.$icon_url.'" target="_blank"><img src="'.$icon_url.'" width="100" alt=""></a>';
					echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				?>
				
				<input type="file" name="mb_icon" id="reg_mb_icon" class="frm_input"  style="margin-top:5px;">

				<!-- <?php if ($w == 'u' && file_exists($mb_icon_path)) {  ?>
				<img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
				<input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
				<label for="del_mb_icon">삭제</label> 
				<?php }  ?> -->
				<br>
				<span style="font-size:12px;margin-top:5px;padding-left:20px;">
					이미지 파일 형식으로 용량은 최대4MB 이하여야하며 육안으로 식별이 가능한 파일이어야 합니다.
					&nbsp;&nbsp;&nbsp;<input type="checkbox" name="mb_24" value="1" <?=($member['mb_24']=="1")?"checked":""?> /> 추후제출예정
				</span>
			</td>
		</tr> 

		<tr>
			 <th scope="row"><label for="reg_mb_22">가입경로<strong class="sound_only">필수</strong></label></th>
			 <td>
				<? if($w=='u'){?>
				<? echo $member['mb_22']; ?>
				<input type="hidden" name="mb_22" value="<?php echo $member['mb_22'] ?>" id="reg_mb_22" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="2" maxlength="20">
				<?}else{?>
				<input type="radio" name="mb_22" value="인터넷 검색"   <?=($member['mb_22']=="인터넷 검색")?"checked":""?> onclick="ch_r(this.value);"/> 인터넷 검색&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mb_22" value="지인추천"   <?=($member['mb_22']=="지인추천")?"checked":""?> onclick="ch_r(this.value);"/> 지인추천&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mb_22" value="영업사원"   <?=($member['mb_22']=="영업사원")?"checked":""?> onclick="ch_r(this.value);"/> 영업사원&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mb_22" value="SMS"   <?=($member['mb_22']=="SMS")?"checked":""?> onclick="ch_r(this.value);"/> SMS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mb_22" value="기타"   <?=($member['mb_22']=="기타")?"checked":""?> onclick="ch_r(this.value);"/> 기타&nbsp;
				<input type="text" name="mb_23" id="reg_mb_23"  value="<?=$member['mb_23']?>" class="frm_input" />
				<? } ?>				
			 </td>
		</tr>
		<tr id="reg_mb_recommend_box" style="">
			<th scope="row"><label for="reg_mb_recommend">추천인아이디</label></th>
			<td><input type="text" name="mb_recommend" value="<?php echo $member['mb_recommend']; ?>" id="reg_mb_recommend"  class="frm_input" maxlength="20"></td>
		</tr>
		<tr id="reg_mb_26_box" style="display:none">
			<th scope="row"><label for="reg_mb_26">영업사원</label></th>
			<td><input type="text" name="mb_26" value="<?php echo $member['mb_26']; ?>" id="reg_mb_26"  class="frm_input" maxlength="20"></td>
		</tr>
		<script type="text/javascript">
			function ch_r(val){
				if(val == "지인추천"){ 
					document.getElementById("reg_mb_recommend_box").style.display = "";
					document.getElementById("reg_mb_23").style.display = "none";
					document.getElementById("reg_mb_26_box").style.display = "none";
				}else if(val == "기타"){
					document.getElementById("reg_mb_recommend_box").style.display = "none";
					document.getElementById("reg_mb_23").style.display = "";
					document.getElementById("reg_mb_26_box").style.display = "none";
				}else if(val == "영업사원"){
					document.getElementById("reg_mb_recommend_box").style.display = "none";
					document.getElementById("reg_mb_23").style.display = "none";
					document.getElementById("reg_mb_26_box").style.display = "";
				}else{
					document.getElementById("reg_mb_recommend_box").style.display = "none";
					document.getElementById("reg_mb_23").style.display = "none";
					document.getElementById("reg_mb_26_box").style.display = "none";
				}	
			}
			document.getElementById("reg_mb_recommend_box").style.display = "none";
			document.getElementById("reg_mb_23").style.display = "none";
			document.getElementById("reg_mb_26_box").style.display = "none";
		</script>
 <? */ ?>
		<input type="hidden" name="mb_shop" value="2" />

		<tr class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_11">소속 의료기관명<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" name="mb_11" value="<?php echo $member['mb_11']; ?>" required id="reg_mb_11"  class="frm_input" maxlength="20">
				<select name="mb_29" class="" id="wr_addr1" required onChange="a_change(this);"> 
					<option value="">지역선택</option> 
					<option value="1" <? if ($member[mb_29]==1) echo "selected"; ?>>서울특별시</option> 
					<option value="2" <? if ($member[mb_29]==2) echo "selected"; ?>>부산광역시</option> 
					<option value="3" <? if ($member[mb_29]==3) echo "selected"; ?>>대구광역시</option> 
					<option value="4" <? if ($member[mb_29]==4) echo "selected"; ?>>인천광역시</option> 
					<option value="5" <? if ($member[mb_29]==5) echo "selected"; ?>>대전광역시</option> 
					<option value="6" <? if ($member[mb_29]==6) echo "selected"; ?>>광주광역시</option> 
					<option value="7" <? if ($member[mb_29]==7) echo "selected"; ?>>울산광역시</option> 
					<option value="8" <? if ($member[mb_29]==8) echo "selected"; ?>>세종특별자치시</option> 
					<option value="9" <? if ($member[mb_29]==9) echo "selected"; ?>>경기도</option> 
					<option value="10" <? if ($member[mb_29]==10) echo "selected"; ?>>강원도</option> 
					<option value="11" <? if ($member[mb_29]==11) echo "selected"; ?>>경상남도</option> 
					<option value="12" <? if ($member[mb_29]==12) echo "selected"; ?>>경상북도</option> 
					<option value="13" <? if ($member[mb_29]==13) echo "selected"; ?>>전라남도</option> 
					<option value="14" <? if ($member[mb_29]==14) echo "selected"; ?>>전라북도</option> 
					<option value="15" <? if ($member[mb_29]==15) echo "selected"; ?>>충청남도</option> 
					<option value="16" <? if ($member[mb_29]==16) echo "selected"; ?>>충청북도</option> 
					<option value="17" <? if ($member[mb_29]==17) echo "selected"; ?>>제주특별자치도</option>
					<option value="18" <? if ($member[mb_29]==18) echo "selected"; ?>>기타</option>
				</select>
								  
				<select name="mb_30" class="" id="wr_addr2" required onChange="inputIndex(this)"> 
					<option value="">시/군/구</option> 
					<? 
					if( $member[mb_30] ) echo "<option value='".$member[mb_30]."'selected>".$member[mb_30]."</option>"; 
					?> 
				</select>

				<SCRIPT LANGUAGE="JavaScript"> 
				var a_value,b_value,c_value,f_value; //전역변수선언 
				var address1 = new Array(); //시군구배열생성 

				//01서울특별시 
				address1[1]='강남구,강동구,강북구,강서구,관악구,광진구,구로구,금천구,노원구,도봉구,동대문구,동작구,마포구,서대문구,서초구,성동구,성북구,송파구,양천구,영등포구,용산구,은평구,종로구,중구,중랑구'; 
				//02부산광역시 
				address1[2]='강서구,금정구,기장군,남구,동구,동래구,부산진구,북구,사상구,사하구,서구,수영구,연제구,영도구,중구,해운대구'; 
				//03대구광역시 
				address1[3]='남구,달서구,달성군,동구,북구,서구,수성구,중구'; 
				//04인천광역시 
				address1[4]='강화군,계양구,남구,남동구,동구,부평구,서구,연수구,옹진군,중구'; 
				//05대전광역시 
				address1[5]='대덕구,동구,서구,유성구,중구'; 
				//06광주광역시 
				address1[6]='광산구,남구,동구,북구,서구'; 
				//07울산광역시 
				address1[7]='남구,동구,북구,울주군,중구'; 
				//08세종시 
				address1[8]='세종'; 
				//09경기도 
				address1[9]='가평군,고양시 덕양구,고양시 일산서구,고양시 일산동구,과천시,광명시,광주시,구리시,군포시,김포시,남양주시,동두천시,부천시 소사구,부천시 오정구,부천시 원미구,성남시 분당구,성남시 수정구,성남시 중원구,수원시 권선구,수원시 영통구,수원시 장안구,수원시 팔달구,시흥시,안산시 단원구,안산시 상록구,안성시,안양시 동안구,안양시 만안구,양주시,양평군,여주군,연천군,오산시,용인시 기흥구,용인시 수지구,용인시 처인구,의왕시,의정부시,이천시,파주시,평택시,포천시,하남시,화성시'; 
				//10강원도 
				address1[10]='강릉시,고성군,동해시,삼척시,속초시,양구군,양양군,영월군,원주시,인제군,정선군,철원군,춘천시,태백시,평창군,홍천군,화천군,횡성군'; 
				//11경상남도 
				address1[11]='거제시,거창군,고성군,김해시,남해군,마산시,밀양시,사천시,산청군,양산시,의령군,진주시,진해시,창녕군,창원시,통영시,하동군,함안군,함양군,합천군'; 
				//12경상북도 
				address1[12]='경산시,경주시,고령군,구미시,군위군,김천시,문경시,봉화군,상주시,성주군,안동시,영덕군,영양군,영주시,영천시,예천군,울릉군,울진군,의성군,청도군,청송군,칠곡군,포항시 남구,포항시 북구'; 
				//13전라남도 
				address1[13]='강진군,고흥군,곡성군,광양시,구례군,나주시,담양군,목포시,무안군,보성군,순천시,신안군,여수시,영광군,영암군,완도군,장성군,장흥군,진도군,함평군,해남군,화순군'; 
				//14전라북도 
				address1[14]='고창군,군산시,김제시,남원시,무주군,부안군,순창군,완주군,익산시,임실군,장수군,전주시 덕진구,전주시 완산구,정읍시,진안군'; 
				//15충청남도 
				address1[15]='계룡시,공주시,금산군,논산시,당진군,보령시,부여군,서산시,서천군,아산시,연기군,예산군,천안시,청양군,태안군,홍성군'; 
				//16충청북도 
				address1[16]='괴산군,단양군,보은군,영동군,옥천군,음성군,제원군,제천시,진천군,청원군,청주시 상당구,청주시 흥덕구,충주시'; 
				//17제주도 
				address1[17]='서귀포시,제주시';
				//18기타
				address1[18]='기타';

				//콤보박스에 배열 넣는 함수 (콤보박스,배열) EX>>list_in(document.form_search.address1_code,address1[si]); 
				function change_action(object,string){  
					var obj = object; 
					var imsi = string.split(",") 
					obj.length = 1; 

					for (i=1;i < imsi.length + 1 ;i++) 
					{ 
				  obj.length++; 
					obj.options[i].value = i; 
					obj.options[i].text =  imsi[i-1]; 
					obj.options[i].value = imsi[i-1]; 
					}; 
				} 

				function a_change(object){ 
					a_value = parseInt(object.options[object.selectedIndex].value,10); 
					if (a_value == '') return; 
					change_action(document.fregisterform.mb_30,address1[a_value]); 
				}
				</script> 
			
			</td>
		</tr>

		<tr id="new_v_1" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_5">병원주소<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" name="mb_5" value="<?php echo $member['mb_5']; ?>" id="reg_mb_5"  class="frm_input " size="5" maxlength="6" style="margin-top:5px;" required>
				<button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_5', 'mb_6', 'mb_7', 'mb_7_tmp', '');" style="margin-top:5px;">주소 검색</button><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" name="mb_6" value="<?php echo get_text($member['mb_6']) ?>" id="reg_mb_6" class="frm_input frm_address" size="50" required><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" name="mb_7" value="<?php echo get_text($member['mb_7']) ?>" id="reg_mb_7" class="frm_input frm_address" size="50"  style="margin-bottom:5px;">
				<input type="hidden" name="mb_7_tmp" />	
				<br>
			</td>
		</tr>
		<tr id="new_v_2" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_8">병의원 전화번호<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" name="mb_8" value="<?php echo $member['mb_8']; ?>" id="reg_mb_8" required  class="frm_input " maxlength="20">
			</td>
		</tr>
		<!-- <tr id="new_v_3" class="new_v">
			<th scope="row"><label for="reg_mb_14">병의원 팩스번호<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" name="mb_14" value="<?php echo $member['mb_14']; ?>" id="reg_mb_14"  class="frm_input " maxlength="20">
			</td>
		</tr> -->
		<tr id="new_v_4" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_15">사업자등록번호<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="mb_15" id="reg_mb_15" value="<?=$member[mb_15]?>"  class="frm_input "  minlength="3" maxlength="20" required> 예)000-00-00000</td>
		</tr>
		<tr id="new_v_5" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_18">요양기관번호<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="mb_18" value="<?php echo $member['mb_18']; ?>" id="reg_mb_18"  class=" frm_input" maxlength="20"></td>
		</tr>
		<tr id="new_v_6" class="new_v">
            <th scope="row"><label for="reg_mb_email"><span style="color:#e63232;">*</span>전자세금계산서 수취 이메일<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">

                <?$wr_email = explode('@' , $member['mb_email']);?>
				<input type="text" maxlength="255" size="10" class="frm_input required" required="" id="wr_email1" value="<?=$wr_email[0]?>" name="wr_email1">
				@ <input type="text" maxlength="255" size="10" class="frm_input required" required="" id="wr_email2" value="<?=$wr_email[1]?>" name="wr_email2">
				<select name="wr_email3" id="wr_email3" onchange="javascript:set_email2(this.value);" class="frm_input" style="height:24px;width:150px;" >
					<option value="" <?=($wr_email[1]=="")?"selected":"" ?> >- 이메일 선택 -</option>
					<option value="naver.com" <?=($wr_email[1]=="naver.com")?"selected":"" ?> >naver.com</option>
					<option value="daum.net" <?=($wr_email[1]=="daum.net")?"selected":"" ?> >daum.net</option>
					<option value="nate.com" <?=($wr_email[1]=="nate.com")?"selected":"" ?> >nate.com</option>
					<option value="hotmail.com" <?=($wr_email[1]=="hotmail.com")?"selected":"" ?> >hotmail.com</option>
					<option value="yahoo.com" <?=($wr_email[1]=="yahoo.com")?"selected":"" ?> >yahoo.com</option>
					<option value="empas.com" <?=($wr_email[1]=="empas.com")?"selected":"" ?> >empas.com</option>
					<option value="korea.com" <?=($wr_email[1]=="korea.com")?"selected":"" ?> >korea.com</option>
					<option value="dreamwiz.com" <?=($wr_email[1]=="dreamwiz.com")?"selected":"" ?> >dreamwiz.com</option>
					<option value="gmail.com" <?=($wr_email[1]=="gmail.com")?"selected":"" ?> >gmail.com</option>
					<option value="" >기타(직접입력)</option>
				</select>
				<input type="hidden" name="mb_email" value="<?php echo get_text($member['mb_email']) ?>" id="reg_mb_email"  class="frm_input" maxlength="20">
				<script>
					function set_email2(val) {
						document.getElementById('wr_email2').value = val;
					}
				</script>
            </td>
        </tr> 
		<tr id="new_v_7" class="new_v">
			<th scope="row"><!-- <span style="color:#e63232;">*</span> --><label for="reg_mb_icon2">사업자등록증<strong class="sound_only">필수</strong></label></th>
			<td>
				<?php
				if(!$member['mb_id']){
					$mb_ids = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";
				}else{
					$mb_ids = $member['mb_id'];
				}

				$mb_dir = substr($mb_ids,0,2);
				$icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_ids."_saup";
				$icon_file2 = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_ids."_saup.pdf";
				if (file_exists($icon_file) || file_exists($icon_file2)) {
					$icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_ids."_saup";
					$icon_url2 = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_ids."_saup.pdf";
					if (file_exists($icon_file)){
						echo '<a href="'.$icon_url.'" target="_blank"><img src="'.$icon_url.'" width="100" alt=""></a>';
					}
					if (file_exists($icon_file2)){
						echo '<a href="'.$icon_url2.'" target="_blank">사업자등록증</a> ';
					}
					echo '<input type="checkbox" id="del_mb_icon2" name="del_mb_icon2" value="1">삭제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				?>
				
				<input type="file" name="mb_icon2" id="reg_mb_icon2" class="frm_input"  style="margin-top:5px;" >

				<!-- <?php if ($w == 'u' && file_exists($mb_icon_path)) {  ?>
				<img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
				<input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
				<label for="del_mb_icon">삭제</label> 
				<?php }  ?> -->
				<br>
				<span style="font-size:12px;margin-top:5px;padding-left:20px;">
					이미지, PDF 파일 형식으로 용량은 최대4MB 이하여야하며 육안으로 식별이 가능한 파일이어야 합니다.				
				</span><br>
				<span style="font-size:12px;margin-top:5px;padding-left:20px;">
					사업자등록증 첨부가 어려우신분들은 팩스 02-6280-6393로 전송 바랍니다.
				</span>
			</td>
		</tr>
		<script type="text/javascript">
		/*
			function ch_r2(val){
				if(val == "2"){ 
					document.getElementById("new_v_1").style.display = ""; 
					document.getElementById("new_v_2").style.display = ""; 
					document.getElementById("new_v_3").style.display = ""; 
					document.getElementById("new_v_4").style.display = ""; 
					document.getElementById("new_v_5").style.display = ""; 
					document.getElementById("new_v_6").style.display = ""; 
					document.getElementById("new_v_7").style.display = ""; 
					<? if(!$member[mb_id]){?>
					//document.getElementById("reg_mb_5").value = document.getElementById("reg_mb_zip").value;
					//document.getElementById("reg_mb_6").value = document.getElementById("reg_mb_addr1").value;
					//document.getElementById("reg_mb_7").value = document.getElementById("reg_mb_addr2").value;
					<? } ?>

				}else{
					document.getElementById("new_v_1").style.display = "none";
					document.getElementById("new_v_2").style.display = "none";
					document.getElementById("new_v_3").style.display = "none";
					document.getElementById("new_v_4").style.display = "none";
					document.getElementById("new_v_5").style.display = "none";
					document.getElementById("new_v_6").style.display = "none";
					document.getElementById("new_v_7").style.display = "none";
				}	
			}
			document.getElementById("new_v_1").style.display = "none";
			document.getElementById("new_v_2").style.display = "none";
			document.getElementById("new_v_3").style.display = "none";
			document.getElementById("new_v_4").style.display = "none";
			document.getElementById("new_v_5").style.display = "none";
			document.getElementById("new_v_6").style.display = "none";
			document.getElementById("new_v_7").style.display = "none";
			ch_r2(<?=$member['mb_shop']?>);
		*/
		</script>
		
		<tr>
			<th scope="row">광고수신동의</th>
            <td>
                <input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?>><label for="reg_mb_mailling">메일</label>
				<input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?>><label for="reg_mb_sms">SMS</label>
            </td>
		</tr>

		<? /* ?>
        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능  ?>
        <tr>
            <th scope="row"><label for="reg_mb_open">정보공개</label></th>
            <td>
                <!-- <span class="frm_info">
                    정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>일 이내에는 변경이 안됩니다.
                </span> -->
                <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
                <input type="checkbox" name="mb_open" value="1" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?> id="reg_mb_open">
                다른분들이 나의 정보를 볼 수 있도록 합니다.
            </td>
        </tr>
        <?php } else {  ?>
        <tr>
            <th scope="row">정보공개</th>
            <td>
                <span class="frm_info">
                    정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
                    이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
                </span>
                <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
            </td>
        </tr>
        <?php }  ?>
		<? */ ?>
		</tbody>
		</table>

		<table class="write_tb">
        <!-- <caption>사이트 이용정보 입력</caption> -->
        <tbody>
        <tr>
            <th scope="row"><span style="color:#e63232;">*</span>자동등록방지</th>
            <td><?php echo captcha_html(); ?></td>
        </tr>
        </tbody>
        </table>
    </div>
	<br>
    <div class="btn_confirm">
        <input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_submit" accesskey="s">
        <a href="<?php echo G5_URL ?>" class="btn_cancel">취소</a>
    </div>
    </form>

    <script>
    $(function() {
        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                case 'lg':
                    $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                    $cert_type = 'lg-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
		$("#win_hp_cert_new").click(function() {
			certify_win_open("kmc-hp", "/plugin/kmchp/kmcis_web_sample_step01.php");
            return;
		});

		$("#win_hp_cert_new2").click(function() {
			certify_win_open("kmc-hp", "/help/keymedi_use.php");
            return;
		});

        <?php } ?>
    });
	function mem_ck(){
		var msg = reg_mb_id_check2();
		if (msg) {
			if(confirm(msg)){
				certify_win_open("kmc-hp", "/help/keymedi_use.php");
			}else{
				document.fregisterform.mb_id.value = "";
			}


			document.fregisterform.mb_id.select();
			return false;
		}else{
			alert("입력하신 ID: "+document.fregisterform.mb_id.value+" 는 사용가능합니다 \n사용하시겠습니까?");
		}
	}
    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
		f.mb_email.value = f.wr_email1.value+"@"+f.wr_email2.value; 
		f.mb_17.value = f.mb_email.value;
		<?php if ($w=='')  {?>
		f.mb_nick_default.value = f.mb_name.value;
		f.mb_nick.value = f.mb_name.value;
		<? } ?>
		
		var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/; 
		if( !regExp.test( f.mb_hp.value ) ) {
			  alert("잘못된 휴대폰 번호입니다. 숫자, - 를 포함한 숫자만 입력하세요.");
			  return false
		}
		var num_regExp = /^[0-9]{1,20}$/;
		if(!num_regExp.test( f.mb_1.value ) ) {
			  alert("의사면허번호는 숫자만 입력하세요.");
			  return false
		}
		// if(f.mb_18.value != ""){
			if(!num_regExp.test( f.mb_18.value ) ) {
				  alert("요양기관번호는 숫자만 입력하세요.");
				  return false
			}
		// }
		var mb_15_regExp = /^([0-9]{3})-?([0-9]{2})-?([0-9]{5})$/; 
		if( !mb_15_regExp.test( f.mb_15.value ) ) {
			  alert("잘못된 사업자등록번호 입니다.");
			  return false
		}	  
		var date_regExp = /^([0-9]{4})-?([0-9]{2})-?([0-9]{2})$/; 
		if( !date_regExp.test( f.mb_birth.value ) ) {
			  alert("잘못된 생년월일입니다. 숫자, - 를 포함한 숫자만 입력하세요.");
			  return false
		}

        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }
		<? if($w != 'u'){?>
		/*
        if (f.mb_icon.value == "") {
            if (!f.mb_24.checked) {
                alert("의사인증파일이 없을 경우 추후제출예정에 체크해 주십시요.");
                f.mb_24.focus();
                return false;
            }
        }
		*/
		<? } ?>
        if (f.w.value == "") {
            if (f.mb_password.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert("비밀번호가 같지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
        if (f.w.value=="") {
            if (f.mb_name.value.length < 1) {
                alert("이름을 입력하십시오.");
                f.mb_name.focus();
                return false;
            }

           /*
            var pattern = /([^가-힣\x20])/i;
            if (pattern.test(f.mb_name.value)) {
                alert("이름은 한글로 입력하십시오.");
                f.mb_name.select();
                return false;
            }
            */
        } 
        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?> 
        // 닉네임 검사
		/*
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }
		*/
		// 의사번호 체크
		
        if ((f.w.value == "") || (f.w.value == "u" && f.reg_mb_1.defaultValue != f.reg_mb_1.value)) {
            var msg = reg_mb_1_check();
            if (msg) {
                alert(msg);
                f.reg_mb_1.select();
                return false;
            }
        }
		
        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
          
			var msg = reg_mb_email_check(); 

            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
        // 휴대폰번호 체크
		/*
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }
		*/
        <?php } ?>

        if (typeof f.mb_icon != "undefined") {
           
			if (f.mb_icon.value) {
                if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpg|png|bmp|JPG|PNG)$/i)) {
                    alert("회원아이콘이 gif|jpg|png|bmp 파일이 아닙니다.");
                    f.mb_icon.focus();
                    return false;
                }
            }
        }
		if (typeof f.mb_icon2 != "undefined") {
           
			if (f.mb_icon2.value) {
                if (!f.mb_icon2.value.toLowerCase().match(/.(gif|jpg|png|bmp|JPG|PNG|pdf)$/i)) {
                    alert("이미지 혹은 PDF 파일만 첨부할 수 있습니다.");
                    f.mb_icon2.focus();
                    return false;
                }
            }
        }
		

        if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
            if (f.mb_id.value == f.mb_recommend.value) {
                alert("본인을 추천할 수 없습니다.");
                f.mb_recommend.focus();
                return false;
            }

            var msg = reg_mb_recommend_check();
            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }
		<?php if ($w=='u') { ?>
		if(f.mb_shop[0].checked){
			if(f.mb_5.value == ""){
				alert("병원주소를 입력해주세요");
				f.mb_5.focus();
				return false;

			}
			if(f.mb_8.value == ""){
				alert("병의원 전화번호를 입력해주세요");
				f.mb_8.focus();
				return false;

			}
			if(f.mb_14.value == ""){
				alert("병의원 팩스번호를 입력해주세요");
				f.mb_14.focus();
				return false;

			}
			if(f.mb_15.value == ""){
				alert("사업자등록번호를 입력해주세요");
				f.mb_15.focus();
				return false;

			}
			if(f.mb_18.value == ""){
				alert("요양기관번호를 입력해주세요");
				f.mb_18.focus();
				return false;

			}
			if(f.mb_17.value == ""){
				alert("세금계산서 이메일을 입력해주세요");
				f.mb_17.focus();
				return false;

			}

		} 
		<? } ?>

        <?php echo chk_captcha_js();  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

	function ck_names(val){
		if(!val){
			alert("본인인증시 자동입력 됩니다");
		}
	}
    </script>

</div>
</div>
<!-- } 회원정보 입력/수정 끝 -->