<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
	if($mb_where =="메디포털"){
		$g5['member_table'] = "portal.g5_member";
	}else{
		$g5['member_table'] = "shop.g5_member";
	}

    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $required_mb_password = '';
    $html_title = '수정';
 
    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
	$mb['mb_11'] = get_text($mb['mb_11']);
	$mb['mb_12'] = get_text($mb['mb_12']);
	$mb['mb_13'] = get_text($mb['mb_13']);
	$mb['mb_14'] = get_text($mb['mb_14']);
	$mb['mb_15'] = get_text($mb['mb_15']);
	$mb['mb_16'] = get_text($mb['mb_16']);
	$mb['mb_17'] = get_text($mb['mb_17']);
	$mb['mb_18'] = get_text($mb['mb_18']);
	$mb['mb_19'] = get_text($mb['mb_19']);
	$mb['mb_20'] = get_text($mb['mb_20']);

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if(isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if(!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if(!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if(!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

if ($mb['mb_intercept_date']) $g5['title'] = "폐업한 ";//$g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;
include_once('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<input type="hidden" name="return_url" value="member_form.php">
<input type="hidden" name="mb_shop" value="2">
<input type="hidden" name="mb_v" value="1">
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15" minlength="3" maxlength="20">
            <?php if ($w=='u'){ ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>">접근가능그룹보기</a><?php } ?>
			<select name="mb_where" readonly>
				<option value="" <?=($mb[mb_where]=="")?"selected":"" ?> >선택하세요.</option>
				<option value="산부인과 협동조합" <?=($mb[mb_where]=="산부인과 협동조합")?"selected":"" ?> >산부인과몰	</option>
				<option value="메디포털" <?=($mb[mb_where]=="메디포털")?"selected":"" ?> >키메디</option>
			</select>
			

        </td>
        <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
        <td><input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15" minlength="2" maxlength="20"></td>
        <th scope="row"><label for="mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required frm_input" size="15" minlength="2" maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_level">회원 권한</label></th>
        <td><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></td>
        <th scope="row">포인트</th>
        <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 점</td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
        <th scope="row"><label for="mb_homepage">홈페이지</label></th>
        <td><input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" class="frm_input" maxlength="255" size="15"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_hp">휴대폰번호</label></th>
        <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_tel">전화번호</label></th>
        <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_birth">생년월일</label></th>
        <td><input type="text" name="mb_birth" value="<?php echo $mb['mb_birth'] ?>" id="mb_birth" class="frm_input" size="15" maxlength="20"></td>
		  <th scope="row"><label for="mb_13">추천인</label></th>
        <td><input type="text" name="mb_13" value="<?php echo $mb['mb_13'] ?>" id="mb_13" class="frm_input" size="15" maxlength="20"></td>
    </tr> 

	<tr>
        <th scope="row"><label for="mb_11">소속 의료기관명</label></th>
        <td><input type="text" name="mb_11" value="<?php echo $mb['mb_11'] ?>" id="mb_11" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_18">요양기관번호</label></th>
        <td><input type="text" name="mb_18" value="<?php echo $mb['mb_18'] ?>" id="mb_18" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	<tr>
        <th scope="row"><label for="mb_1">의사면허번호</label></th>
        <td><input type="text" name="mb_1" value="<?php echo $mb['mb_1'] ?>" id="mb_1" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_12">전문의면허번호</label></th>
        <td><input type="text" name="mb_12" value="<?php echo $mb['mb_12'] ?>" id="mb_12" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	
	<tr>
        <th scope="row"><label for="mb_17">세금계산서 받을 E-mail</label></th>
        <td><input type="text" name="mb_17" value="<?php echo $mb['mb_17'] ?>" id="mb_17" class="frm_input" size="30" maxlength="120"></td>
        <th scope="row"><label for="mb_4">근무형태</label></th>
        <td><input type="text" name="mb_4" value="<?php echo $mb['mb_4'] ?>" id="mb_4" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	
	<tr>
        <th scope="row">근무처</th>
        <td colspan="3" class="td_addr_line">
            <label for="mb_5" class="sound_only">우편번호</label>
            <input type="text" name="mb_5" value="<?php echo $mb['mb_5'] ?>" id="mb_5" class="frm_input readonly" size="5" maxlength="6"> <button type="button"  style="text-indent:0;margin-top:5px;" class="btn_frmline" onclick="win_zip('fmember', 'mb_5', 'mb_6', 'mb_7', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <input type="text" name="mb_6" value="<?php echo $mb['mb_6'] ?>" id="mb_6" class="frm_input readonly" size="60">
            <label for="mb_6">기본주소</label><br>
            <input type="text" name="mb_7" value="<?php echo $mb['mb_7'] ?>" id="mb_7" class="frm_input" size="60">
            <label for="mb_7">상세주소</label> 
        </td>
    </tr>

	<tr>
        <th scope="row"><label for="mb_8">병의원 전화번호</label></th>
        <td><input type="text" name="mb_8" value="<?php echo $mb['mb_8'] ?>" id="mb_8" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_14">FAX</label></th>
        <td><input type="text" name="mb_14" value="<?php echo $mb['mb_14'] ?>" id="mb_14" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	<tr>
        <th scope="row"><label for="mb_9">전문진료분야</label></th>
        <td><input type="text" name="mb_9" value="<?php echo $mb['mb_9'] ?>" id="mb_9" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_2">출신대학</label></th>
        <td><input type="text" name="mb_2" value="<?php echo $mb['mb_2'] ?>" id="mb_2" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	<tr>
        <th scope="row"><label for="mb_3">졸업년도</label></th>
        <td><input type="text" name="mb_3" value="<?php echo $mb['mb_3'] ?>" id="mb_3" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_10">기초의학</label></th>
        <td><input type="text" name="mb_10" value="<?php echo $mb['mb_10'] ?>" id="mb_10" class="frm_input" size="15" maxlength="20"></td>
    </tr>
	<tr>
        <th scope="row"><label for="mb_19">배당금 지급 또는 출자금 은행명</label></th>
        <td><input type="text" name="mb_19" value="<?php echo $mb['mb_19'] ?>" id="mb_19" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_20">계좌번호</label></th>
        <td><input type="text" name="mb_20" value="<?php echo $mb['mb_20'] ?>" id="mb_20" class="frm_input" size="15" maxlength="20"></td>
    </tr> 


    <tr>
        <th scope="row">주소</th>
        <td colspan="3" class="td_addr_line">
            <label for="mb_zip" class="sound_only">우편번호</label>
            <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6"> <button type="button"  style="text-indent:0;margin-top:5px;" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
            <label for="mb_addr1">기본주소</label><br>
            <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
            <label for="mb_addr2">상세주소</label>
            <br>
            <input type="hidden" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
            <!-- <label for="mb_addr3">참고항목</label> -->
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
        </td>
    </tr>
	<tr>
        <th scope="row"><label for="mb_15">사업자등록번호</label></th>
        <td><input type="text" name="mb_15" value="<?php echo $mb['mb_15'] ?>" id="mb_15" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"></th>
        <td></td>
    </tr> 
    <tr>
        <th scope="row"><label for="mb_icon">사업자등록증 첨부</label></th>
        <td colspan="3"> 
            
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb['mb_id'];
            if (file_exists($icon_file)) {
                $icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb['mb_id'];
                echo '<img src="'.$icon_url.'" width="800" alt="">';
                echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
            }
            ?>
			<br><input type="file" name="mb_icon" id="mb_icon">
        </td>
    </tr>


	<tr>
        <th scope="row">본인확인방법</th>
        <td colspan="3">
            <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if($mb['mb_certify'] == 'ipin') echo 'checked="checked"'; ?>>
            <label for="mb_certify_ipin">아이핀</label>
            <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if($mb['mb_certify'] == 'hp') echo 'checked="checked"'; ?>>
            <label for="mb_certify_hp">휴대폰</label>
        </td>
    </tr>
    <tr>
        <th scope="row">본인확인</th>
        <td>
            <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
            <label for="mb_certify_yes">예</label>
            <input type="radio" name="mb_certify" value="" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
            <label for="mb_certify_no">아니오</label>
        </td>
        <th scope="row"><label for="mb_adult">성인인증</label></th>
        <td>
            <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
            <label for="mb_adult_yes">예</label>
            <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
            <label for="mb_adult_no">아니오</label>
        </td>
    </tr>
    <tr>
        <th scope="row">메일 수신</th>
        <td>
            <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
            <label for="mb_mailling_yes">예</label>
            <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
            <label for="mb_mailling_no">아니오</label>
        </td>
        <th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
        <td>
            <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
            <label for="mb_sms_yes">예</label>
            <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
            <label for="mb_sms_no">아니오</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_open">정보 공개</label></th>
        <td colspan="3">
            <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
            <label for="mb_open_yes">예</label>
            <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
            <label for="mb_open_no">아니오</label>
        </td>
    </tr>
   

    <?php if ($w == 'u') { ?>
    <tr>
        <th scope="row">회원가입일</th>
        <td><?php echo $mb['mb_datetime'] ?></td>
        <th scope="row">최근접속일</th>
        <td><?php echo $mb['mb_today_login'] ?></td>
    </tr>
    <tr>
        <th scope="row">IP</th>
        <td><?php echo $mb['mb_ip'] ?></td>
		<th scope="row">등급수정일</th>
        <td><?php echo $mb['level_datetime'] ?></td>
    </tr>
   
    <?php } ?>

   

    <tr>
        <th scope="row"><label for="mb_leave_date">탈퇴일자</label></th>
        <td>
            <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="frm_input" maxlength="8">
            <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) {
this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
            <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
        </td>
        <th scope="row">폐업<!-- 접근차단 -->일자</th>
        <td>
            <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="frm_input" maxlength="8">
            <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if
(this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else {
this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
            <label for="mb_intercept_date_set_today">폐업<!-- 접근차단 -->일을 오늘로 지정</label>
        </td>
    </tr>


    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey='s'>
    <a href="./member_list.php?<?php echo $qstr ?>">목록</a>
</div>
</form>

<script>
function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.gif|jpg|png|bmp$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
