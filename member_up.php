<style>
#member_up_wraps { position:fixed;width:100%;height:700px;top:100px;left:0;z-index:1001;}
#member_up { position:relative;width:600px;margin:0 auto;z-index:9999;border:2px solid #4a5376;background:#fff;border-radius:15px;}
#member_up .btn_close { position:absolute;right:12px;top:12px;}
#member_up h2 { font-size:28px;text-align:center;margin-top:40px;}
#member_up h3 { font-size:18px;text-align:center;font-weight:normal;margin-top:10px;}
#member_up h3 span { color:red;}
#member_up p.sub_txt { font-size:14px;text-align:center;margin-top:20px;}
#member_up p.sub_txt span { font-size:12px;}
#member_up .btn_confirm { margin-top:20px;margin-bottom:20px;}

table.write_tb { position:relative;width:90%;border-top:1px solid #a0a0a0;border-collapse: collapse; border-spacing: 0;left:5%;margin-top:20px;}
table.write_tb td { height:40px;border-bottom:1px solid #d1d1d1;font-size:14px;text-indent:20px;}
table.write_tb th { width:130px;height:40px;border-bottom:1px solid #d1d1d1;font-size:14px;background:#ebebeb;}

table.write_tb td.upline {border-bottom:1px solid #a0a0a0;}
table.write_tb th.upline {border-bottom:1px solid #a0a0a0;}
table.write_tb .new_v th {background:#979797;color:#fff;border-bottom:1px solid #d8d8d8;}
table.write_tb .new_v td {background:#f8f8f8;}
table.write_tb .new_v td input { background:#fff;}

</style>
 
 <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div id="member_up_wraps" style="display:none;">
	<form id="fregisterform" name="fregisterform" action="/bbs/new_memup.php" onsubmit="return fregisterform_submit_new(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	
	<input type="hidden" name="w" value="u">
	<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
	<input type="hidden" name="mb_17" value="<?=$member[mb_17]?>" />

	<input type="hidden" name="mb_agree_date" value="<?=($member["mb_agree_date"])?$member["mb_agree_date"]:date("Y-m-d H:i:s")?>" />
	<input type="hidden" name="mb_agree_who" value="본인" />

	<div id="member_up">
		<a onclick="show_mem_reg_cl();return false;" href="#"><img title="" class="btn_close" alt="" src="/img/main/top/close.png"></a>
		<h2>키메디몰 이용안내</h2>
		<h3>저희 키메디몰은 <span>개원의</span>를 위한 병의원 전문 쇼핑몰입니다.</h3>
		<p class="sub_txt">이용을 윈하신다면 아래에 사업장 정보 입력과 사업자등록증 사본 첨부해주십시오.<br><span>(ID 성함과 사업자정보의 대표자명이 일치해야 이용가능)</span></p>


<div class="mbskin">
	<table class="write_tb"> 
        <tbody> 
 

		<tr id="new_v_1" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_5">병원주소<strong class="sound_only">필수</strong></label></th>
			<td style="text-indent:0;padding-left:20px;line-height:2em;">
				<input type="text" name="mb_5" value="<?php echo $member['mb_5']; ?>" id="reg_mb_5"  class="frm_input " size="5" maxlength="6" style="margin-top:5px;" required>
				<button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_5', 'mb_6', 'mb_7', 'mb_7_tmp', '');" style="margin-top:5px;">주소 검색</button><br>
				<input type="text" name="mb_6" value="<?php echo get_text($member['mb_6']) ?>" id="reg_mb_6" class="frm_input frm_address" size="20" required><br>
				<input type="text" name="mb_7" value="<?php echo get_text($member['mb_7']) ?>" id="reg_mb_7" class="frm_input frm_address" size="20"  style="margin-bottom:5px;">
				<input type="hidden" name="mb_7_tmp" />	
				<br>
			</td>
		</tr>
		<tr id="new_v_2" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_8">병의원 전화번호<strong class="sound_only">필수</strong></label></th>
			<td>
				<input type="text" name="mb_8" value="<?php echo $member['mb_8']; ?>" id="reg_mb_8" required  class="frm_input " maxlength="20" size="10">
			</td>
		</tr>

		<tr id="new_v_4" class="new_v">
			<th scope="row"><span style="color:#e63232;">*</span><label for="reg_mb_15">사업자등록번호<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="mb_15" id="reg_mb_15" value="<?=$member[mb_15]?>"  class="frm_input "  minlength="3" maxlength="20" required size="10"> 예)000-00-00000</td>
		</tr>
		<tr id="new_v_5" class="new_v">
			<th scope="row"><label for="reg_mb_18">요양기관번호<strong class="sound_only">필수</strong></label></th>
			<td><input type="text" name="mb_18" value="<?php echo $member['mb_18']; ?>" id="reg_mb_18"  class=" frm_input" maxlength="20" size="10"></td>
		</tr>
		<tr id="new_v_6" class="new_v">
            <th scope="row"><label for="reg_mb_email"><span style="color:#e63232;">*</span>전자세금계산서 수취 이메일<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">

                <?$wr_email = explode('@' , $member['mb_email']);?>
				<input type="text" maxlength="255" size="5" class="frm_input " required="" id="wr_email1" value="<?=$wr_email[0]?>" name="wr_email1">
				@ <input type="text" maxlength="255" size="5" class="frm_input " required="" id="wr_email2" value="<?=$wr_email[1]?>" name="wr_email2">
				<select name="wr_email3" id="wr_email3" onchange="javascript:set_email2(this.value);" class="frm_input" style="height:24px;width:150px;background:#fff;" >
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
			<td  style="text-indent:0;padding-left:20px;padding-top:10px;padding-bottom:10px;line-height:1.5em;" >
				<?php
				if(!$member['mb_id']){
					$mb_ids = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";
				}else{
					$mb_ids = $member['mb_id'];
				}

				$mb_dir = substr($mb_ids,0,2);
				//$icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_ids."_saup";
				//$icon_file2 = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_ids."_saup.pdf";
				
				$icon_file = '/data/was/portal/data/member/'.$mb_dir.'/'.$mb_ids."_saup";
				$icon_file2 = '/data/was/portal/data/member/'.$mb_dir.'/'.$mb_ids."_saup.pdf";

				if (file_exists($icon_file) || file_exists($icon_file2)) {
					
					$icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_ids."_saup";
					$icon_url2 = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_ids."_saup.pdf";

					if (file_exists($icon_file)){
						echo '<a href="'.$icon_url.'" target="_blank"><img src="'.$icon_url.'" width="100" alt=""></a>';
					}
					if (file_exists($icon_file2)){
						echo '<a href="'.$icon_url2.'" target="_blank">사업자등록증</a> ';
					}
					echo '<input type="checkbox" id="del_mb_icon2" name="del_mb_icon2" value="1">삭제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>';
				}
				?>
				
				<input type="file" name="mb_icon2" id="reg_mb_icon2" class="frm_input"  style="width:250px;margin-top:5px;" >


				<br>
				<p style="font-size:12px;margin-top:5px;text-indent:0;">이미지, PDF 파일 형식으로 용량은 최대4MB 이하여야하며 육안으로 식별이 가능한 파일이어야 합니다.	</p>
				<p style="font-size:12px;margin-top:5px;text-indent:0;">파일첨부가 어려우신 분들은 팩스 or 카카오톡 발송 부탁드립니다. <br>Fax : 02-6280-6393  카톡 플러스친구: ㈜키닥 [검색]</p>
				<p style="font-size:12px;margin-top:5px;text-indent:0;">관리자가 승인 후 물품 구매가 가능합니다.</p>

				
			</td>
		</tr>
		 </tbody> 
		</table>
		<div class="btn_confirm">
			<input type="submit" value="정보수정" id="btn_submit" class="btn_submit" accesskey="s">
			<a href="#" class="btn_cancel" onclick="show_mem_reg_cl();return false;" >취소</a>
		</div>
	</div>

	</div>
	</form>
	<script>
	function fregisterform_submit_new(f){
		f.mb_email.value = f.wr_email1.value+"@"+f.wr_email2.value; 
		f.mb_17.value = f.mb_email.value;

		if (typeof f.mb_icon2 != "undefined") {
           
			if (f.mb_icon2.value) {
                if (!f.mb_icon2.value.toLowerCase().match(/.(gif|jpg|png|bmp|JPG|PNG|pdf)$/i)) {
                    alert("이미지 혹은 PDF 파일만 첨부할 수 있습니다.");
                    f.mb_icon2.focus();
                    return false;
                }
            }
        }

	}

	</script>
</div> 