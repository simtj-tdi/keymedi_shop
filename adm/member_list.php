<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
 
if(!$mb_where) $mb_where = "산부인과 협동조합";

if($mb_where =="메디포털"){
	$sql_common = " from portal.g5_member ";
}else{
	$sql_common = " from {$g5['member_table']} ";
}

//$sql_search = " where (mb_shop = '2' and mb_level >= '4') and ( mb_where = '산부인과 협동조합' or mb_where = '메디포털' ) and ( mb_level >= '4' or mb_level <='6' ) ";

$sql_search = " where (mb_shop = '2' and mb_level >= '4') and ( mb_where = '$mb_where' ) and ( mb_level >= '4' or mb_level <='6' ) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
if($mb_s_date){
	 $sql_search .= " and  '{$mb_s_date}' <= left(level_datetime,10)   ";
}
if($mb_e_date){
	 $sql_search .= " and  left(level_datetime,10) <= '{$mb_e_date}' ";
}
/*
if($mb_where){
	 $sql_search .= " and mb_where = '{$mb_where}' ";
}
*/
if($mb_level){
	 $sql_search .= " and mb_level = '{$mb_level}' ";
}
if($mb_9){
	 $sql_search .= " and mb_9 = '{$mb_9}' ";
}
if($mb_21){
	 $sql_search .= " and mb_21 = '{$mb_21}' ";
}
if($mb_intercept_date =="Y"){
	 $sql_search .= " and mb_intercept_date <> '' ";
}
if($mb_leave_date =="Y"){
	 $sql_search .= " and mb_leave_date <> '' ";
} 
/*
if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
*/

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";

//echo $sql;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총회원수 <?php echo number_format($total_count) ?>명 중,
    <!-- <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">폐업 <?php echo number_format($intercept_count) ?></a>명, -->
	<!-- <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">탈퇴 <?php echo number_format($leave_count) ?></a>명 -->
	<a href="?mb_intercept_date=Y&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">폐업 <?php echo number_format($intercept_count) ?></a>명,    
	<a href="?mb_leave_date=Y&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">탈퇴 <?php echo number_format($leave_count) ?></a>명
</div>

<form id="fsearch" name="fsearch" class="local_sch02 local_sch" method="get">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">

<p>
<!-- mb_datetime  -->등급수정일 : <input type="text" name="mb_s_date" id="mb_s_date" value="<?=$mb_s_date?>" class="frm_input"/> ~ <input type="text" name="mb_e_date" id="mb_e_date" value="<?=$mb_e_date?>" class="frm_input"/>

<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
<button type="button" onclick="javascript:set_date('어제');">어제</button>
<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
<button type="button" onclick="javascript:set_date('전체');">전체</button>

</p>
<p>
<label for="sfl" class="sound_only">검색대상</label>

<select name="mb_where"> 
	<option value="산부인과 협동조합" <?php echo get_selected($_GET['mb_where'], "산부인과 협동조합"); ?>>산부인과몰</option>
	<option value="메디포털" <?php echo get_selected($_GET['mb_where'], "메디포털"); ?>>키메디</option>
</select>

<select name="mb_level">
	<option value="" <?php echo get_selected($_GET['mb_level'], ""); ?> >전체회원등급</option>
	<!-- <option value="1" <?php echo get_selected($_GET['mb_level'], "1"); ?> >준회원</option>
	<option value="2" <?php echo get_selected($_GET['mb_level'], "2"); ?> >기업회원</option>
	<option value="3" <?php echo get_selected($_GET['mb_level'], "3"); ?> >학회회원</option> -->
	<option value="4" <?php echo get_selected($_GET['mb_level'], "4"); ?> >봉직의</option>
	<option value="5" <?php echo get_selected($_GET['mb_level'], "5"); ?> >건기식회원</option>
	<option value="6" <?php echo get_selected($_GET['mb_level'], "6"); ?> >의사회원</option>
	<!-- <option value="7" <?php echo get_selected($_GET['mb_level'], "7"); ?> >7</option>
	<option value="8" <?php echo get_selected($_GET['mb_level'], "8"); ?> >8</option>
	<option value="9" <?php echo get_selected($_GET['mb_level'], "9"); ?> >9</option>
	<option value="10" <?php echo get_selected($_GET['mb_level'], "10"); ?> >최고관리자</option> -->
</select>

<select name="mb_9">
	<option value="" <?php echo get_selected($_GET['mb_9'], ""); ?> >대표진료과</option>
	<option value="가정의학과" <?php echo get_selected($_GET['mb_9'], "가정의학과"); ?> >가정의학과</option>
	<option value="결핵과" <?php echo get_selected($_GET['mb_9'], "결핵과"); ?> >결핵과</option>
	<option value="내과" <?php echo get_selected($_GET['mb_9'], "내과"); ?> >내과</option>
	<option value="마취통증의학과" <?php echo get_selected($_GET['mb_9'], "마취통증의학과"); ?> >마취통증의학과</option>
	<option value="방사선종양학과" <?php echo get_selected($_GET['mb_9'], "방사선종양학과"); ?> >방사선종양학과</option>
	<option value="병리과" <?php echo get_selected($_GET['mb_9'], "병리과"); ?> >병리과</option>
	<option value="비뇨기과" <?php echo get_selected($_GET['mb_9'], "비뇨기과"); ?> >비뇨기과</option>
	<option value="산부인과" <?php echo get_selected($_GET['mb_9'], "산부인과"); ?> >산부인과</option>
	<option value="성형외과" <?php echo get_selected($_GET['mb_9'], "성형외과"); ?> >성형외과</option>
	<option value="소아청소년과" <?php echo get_selected($_GET['mb_9'], "소아청소년과"); ?> >소아청소년과</option>
	<option value="신경과" <?php echo get_selected($_GET['mb_9'], "신경과"); ?> >신경과</option>
	<option value="신경외과" <?php echo get_selected($_GET['mb_9'], "신경외과"); ?> >신경외과</option>
	<option value="안과" <?php echo get_selected($_GET['mb_9'], "안과"); ?> >안과</option>
	<option value="영상의학과" <?php echo get_selected($_GET['mb_9'], "영상의학과"); ?> >영상의학과</option>
	<option value="예방의학과" <?php echo get_selected($_GET['mb_9'], "예방의학과"); ?> >예방의학과</option>
	<option value="외과" <?php echo get_selected($_GET['mb_9'], "외과"); ?> >외과</option>
	<option value="응급의학과" <?php echo get_selected($_GET['mb_9'], "응급의학과"); ?> >응급의학과</option>
	<option value="이비인후과" <?php echo get_selected($_GET['mb_9'], "이비인후과"); ?> >이비인후과</option>
	<option value="일반의" <?php echo get_selected($_GET['mb_9'], "일반의"); ?> >일반의</option>
	<option value="재활의학과" <?php echo get_selected($_GET['mb_9'], "재활의학과"); ?> >재활의학과</option>
	<option value="정신건강의학과" <?php echo get_selected($_GET['mb_9'], "정신건강의학과"); ?> >정신건강의학과</option>
	<option value="정형외과" <?php echo get_selected($_GET['mb_9'], "정형외과"); ?> >정형외과</option>
	<option value="직업환경의학과" <?php echo get_selected($_GET['mb_9'], "직업환경의학과"); ?> >직업환경의학과</option>
	<option value="진단검사의학과" <?php echo get_selected($_GET['mb_9'], "진단검사의학과"); ?> >진단검사의학과</option>
	<option value="피부과" <?php echo get_selected($_GET['mb_9'], "피부과"); ?> >피부과</option>
	<option value="핵의학과" <?php echo get_selected($_GET['mb_9'], "핵의학과"); ?> >핵의학과</option>
	<option value="흉부외과" <?php echo get_selected($_GET['mb_9'], "흉부외과"); ?> >흉부외과</option>
</select>

<select name="mb_21">
	<option value="" <?php echo get_selected($_GET['mb_21'], ""); ?> >학회명</option>
	<option value="대한검진의학회" <?php echo get_selected($_GET['mb_21'], "대한검진의학회"); ?> >대한검진의학회</option>
	<option value="대한노인의학회" <?php echo get_selected($_GET['mb_21'], "대한노인의학회"); ?> >대한노인의학회</option>
	<option value="대한밸런스의학회" <?php echo get_selected($_GET['mb_21'], "대한밸런스의학회"); ?> >대한밸런스의학회</option>
	<option value="대한산부인과의사회" <?php echo get_selected($_GET['mb_21'], "대한산부인과의사회"); ?> >대한산부인과의사회</option>
	<option value="대한성장의학회" <?php echo get_selected($_GET['mb_21'], "대한성장의학회"); ?> >대한성장의학회</option>
	<option value="대한약물영양의학회" <?php echo get_selected($_GET['mb_21'], "대한약물영양의학회"); ?> >대한약물영양의학회</option>
	<option value="대한여성성의학회" <?php echo get_selected($_GET['mb_21'], "대한여성성의학회"); ?> >대한여성성의학회</option>
	<option value="대한외과의사회" <?php echo get_selected($_GET['mb_21'], "대한외과의사회"); ?> >대한외과의사회</option>
	<option value="대한일차진료학회" <?php echo get_selected($_GET['mb_21'], "대한일차진료학회"); ?> >대한일차진료학회</option>
	<option value="한국임상고혈압학회" <?php echo get_selected($_GET['mb_21'], "한국임상고혈압학회"); ?> >한국임상고혈압학회</option>
	<option value="대한흉부심장혈관외과의사회" <?php echo get_selected($_GET['mb_21'], "대한흉부심장혈관외과의사회"); ?> >대한흉부심장혈관외과의사회</option>
	<option value="최소침습성형연구회" <?php echo get_selected($_GET['mb_21'], "최소침습성형연구회"); ?> >최소침습성형연구회</option>
	<option value="대한정주의학회" <?php echo get_selected($_GET['mb_21'], "대한정주의학회"); ?> >대한정주의학회</option>
	<option value="대한임상암대사의학회" <?php echo get_selected($_GET['mb_21'], "대한임상암대사의학회"); ?> >대한임상암대사의학회</option>
	<option value="제암거슨의학회" <?php echo get_selected($_GET['mb_21'], "제암거슨의학회"); ?> >제암거슨의학회</option>
	<option value="하지정맥류연구회" <?php echo get_selected($_GET['mb_21'], "하지정맥류연구회"); ?> >하지정맥류연구회</option>
	<option value="없음" <?php echo get_selected($_GET['mb_21'], "없음"); ?> >없음</option>
	<option value="기타" <?php echo get_selected($_GET['mb_21'], "기타"); ?> >기타</option>
</select>

<select name="mb_l" class="frm_input">
	<option value="" <?=($mb_l =="")?"selected":""?>>승인</option>
	<option value="mb_leave_date" <?=($mb_l =="mb_leave_date")?"selected":""?>>탈퇴</option>
	<option value="mb_intercept_date" <?=($mb_l =="mb_intercept_date")?"selected":""?>>폐업</option>
</select>

<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
	<option value="mb_11"<?php echo get_selected($_GET['sfl'], "mb_11"); ?>>병의원명</option>
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
    <option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option>
    <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
    <option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>포인트</option>
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
    <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
    <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">



<input type="submit" class="btn_submit" value="검색">
</p>
</form>

<div class="local_desc01 local_desc">
    <p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>
</div>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
	<a href="#" onclick="down_excel('./member_excel_new.php');" id="member_add">엑셀다운 신규</a>
	<a href="#" onclick="down_excel('./member_excel1.php');" id="member_add">엑셀다운</a>
    <a href="./member_form.php" id="member_add">회원추가</a>
</div>
<?php } ?>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
	<?php if ($is_admin == 'super') { ?>
        <th scope="col" rowspan="2" id="mb_list_chk" width="2%">
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
	<? } ?>
        <th scope="col" rowspan="2" id="mb_list_id" width="6%"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
		<th scope="col" rowspan="2" id="mb_list_id" width="10%">회원구분</th>
        <th scope="col" id="mb_list_name" width="10%"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
		<th scope="col" id="mb_list_name" width="4%">생년월일</th>

       
        <th scope="col" id="mb_list_mobile" width="5%">휴대폰</th>
		<th scope="col" id="mb_list_mobile" width="9%">이메일</th>
		<th scope="col" id="mb_list_mobile">학회</th>
		<th scope="col" id="mb_list_mobile" width="5%">근무형태</th>
		<th scope="col" id="mb_list_mobile" width="5%">가입경로</th>


        <th scope="col" id="mb_list_auth" width="8%">상태/<?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th>
		<th scope="col" colspan="2" id="mb_list_cert" width="8%"><?php echo subject_sort_link('mb_certify', '', 'desc') ?>본인확인</a></th>
        <th scope="col" id="mb_list_lastcall" width="8%"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
        <th scope="col" rowspan="2" id="mb_list_lastcall" width="3%">사업자등록</th>
		<th scope="col" rowspan="2" id="mb_list_grp" width="3%">접근<br>그룹</th>
<?php if ($is_admin == 'super') { ?>
        <th scope="col" rowspan="2" id="mb_list_mng" width="5%">관리</th>
<? } ?>
    </tr>
    <tr>
        <th scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?>닉네임</a></th>
		<th scope="col" id="mb_list_nick">성별</th>
        
        

        <th scope="col" id="mb_list_tel">전화번호</th>
		<th scope="col" id="mb_list_tel">면허번호</th>
		<th scope="col" id="mb_list_tel">근무처</th>
		<th scope="col" id="mb_list_tel">대표진료과</th>
		<th scope="col" id="mb_list_tel">메디몰이용</th>


		<th scope="col" id="mb_list_point"><?php echo subject_sort_link('mb_point', '', 'desc') ?> 마일리지/포인트</th>
        <th scope="col" id="mb_list_sms"><?php echo subject_sort_link('mb_sms', '', 'desc') ?>SMS<br>수신</a></th>

        <th scope="col" id="mb_list_mailr"><?php echo subject_sort_link('mb_mailling', '', 'desc') ?>메일<br>수신</a></a></th>
        <!-- <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th> -->
		<th scope="col" id="mb_list_join"><?php echo subject_sort_link('level_datetime', '', 'desc') ?>등급수정일</a></th>
	
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
        $row2 = sql_fetch($sql2);
        $group = '';
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'&amp;mb_where='.$mb_where.'"  class="btn btn-primary btn-xs">수정</a>';
        }
        $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn btn-success btn-xs">그룹</a>';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
        }
        else if ($row['mb_intercept_date']) {
            $mb_id = $mb_id;
            //$intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
			$intercept_msg = '<span class="mb_intercept_msg">폐업함</span>';
            $intercept_title = '차단해제';
        }
        if ($intercept_title == '')
            $intercept_title = '차단하기';

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        switch($row['mb_certify']) {
            case 'hp':
                $mb_certify_case = '휴대폰';
                $mb_certify_val = 'hp';
                break;
            case 'ipin':
                $mb_certify_case = '아이핀';
                $mb_certify_val = '';
                break;
            case 'admin':
                $mb_certify_case = '관리자';
                $mb_certify_val = 'admin';
                break;
            default:
                $mb_certify_case = '&nbsp;';
                $mb_certify_val = 'admin';
                break;
        }
    ?>

    <tr class="<?php echo $bg; ?>">
		<?php if ($is_admin == 'super') { ?>
        <td headers="mb_list_chk" class="td_chk" rowspan="2">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			<input type="hidden" name="mb_where[<?php echo $i ?>]" value="<?php echo $row['mb_where'] ?>" id="mb_where_<?php echo $i ?>">
			
        </td>
		<? } ?>
        <td headers="mb_list_id" rowspan="2" class="td_name sv_use"><?php echo $mb_id ?></td>
		<td headers="mb_list_id" rowspan="2" class="td_name sv_use">
            <?php
            switch($row[mb_where]) {
                case "메디포털" : echo "키메디"; break;
                case "산부인과 협동조합" : echo "산부인과몰"; break;
            }
            ?>
            
        </td>
		<td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
        
		<td headers="mb_list_id" class="td_name"><?php echo $row[mb_birth] ?></td>

		
        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
		<td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_email']); ?></td>
		
		<td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_21']); ?></td>
		<td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_4']); ?></td>
		<td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_22']); ?></td>

        <td headers="mb_list_auth" class="td_mbstat">
            <?php
            if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
            else echo "정상";
            ?>
            <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
        </td>

		<td headers="mb_list_cert" colspan="2" class="td_mbcert">
            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="ipin" id="mb_certify_ipin_<?php echo $i; ?>" <?php echo $row['mb_certify']=='ipin'?'checked':''; ?>>
            <label for="mb_certify_ipin_<?php echo $i; ?>">아이핀</label>
            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="hp" id="mb_certify_hp_<?php echo $i; ?>" <?php echo $row['mb_certify']=='hp'?'checked':''; ?>>
            <label for="mb_certify_hp_<?php echo $i; ?>">휴대폰</label>
        </td>

        <td headers="mb_list_lastcall" class="td_date"><?php echo $row['mb_today_login']; ?></td>
		<td headers="mb_list_lastcall" class="td_date" rowspan="2">
		<?php
		if($row[mb_where] == "메디포털"){
			$mb_dir = substr($row['mb_id'],0,2);
			$icon_file = preg_replace("/\s+/", "",'/data/was/portal/data/member/'.$mb_dir.'/'.$row['mb_id']."_saup");
			$icon_file2 = preg_replace("/\s+/", "",'/data/was/portal/data/member/'.$mb_dir.'/'.$row['mb_id']."_saup.pdf");

			$fileUrl = preg_replace("/\s+/", "", $mb_dir."/".$row[mb_id]);
			if (file_exists($icon_file) || file_exists($icon_file2)) {
				if(file_exists($icon_file)){
					echo "<a href='http://www.keymedi.com/data/member/{$fileUrl}_saup' target='_blank'>O</a>";
				}
				if(file_exists($icon_file2)){
					echo "<a href='http://www.keymedi.com/data/member/{$fileUrl}_saup.pdf' target='_blank'>O</a>";
				}
			}else{
				echo "X";
			}
		}else if($row[mb_where] == "산부인과 협동조합"){
			$mb_dir = substr($row['mb_id'],0,2);
			$icon_file = preg_replace("/\s+/", "", '/data/was/union/data/member/'.$mb_dir.'/'.$row['mb_id']);
			
			if (file_exists($icon_file)) {
                $fileUrl = preg_replace("/\s+/", "", $mb_dir."/".$row[mb_id]);
				echo "<a href='http://obgy.keymedi.com/data/member/{$fileUrl}' target='_blank'>O</a>";
			}else{
				echo "X";
			}
		}
		?>
		</td>
        <td headers="mb_list_grp" rowspan="2" class="td_numsmall"><?php echo $group ?></td>
		<?php if ($is_admin == 'super') { ?>
        <td headers="mb_list_mng" rowspan="2" class="td_mngsmall"><?php echo $s_mod ?> <?php echo $s_grp ?></td>
		<? } ?>
    </tr>
    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_nick" class="td_name sv_use"><div><?php echo $mb_nick ?></div></td>

		<td headers="mb_list_id" class="td_name"><?php echo $row[mb_sex] ?></td>

        

        <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td>
		<td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_1']); ?></td>

		<td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_11']); ?></td>
		<td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_9']); ?></td>
		<td headers="mb_list_tel" class="td_tel"><?=($row['mb_shop']=="2")?"예":"아니오" ?></td>

        <td headers="mb_list_point" class="td_num">
		<?
			$points1 = sql_fetch("select po_mb_point from shop.g5_point where mb_id = '{$row['mb_id']}' order by po_id desc limit 1");
			$points2 = sql_fetch("select po_mb_point from portal.g5_point where mb_id = '{$row['mb_id']}' order by po_id desc limit 1");
		?>
			<a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?=number_format($points1['po_mb_point'])?></a>/<?php echo number_format($points2['po_mb_point']) ?>
		</td>
		 <td headers="mb_list_mailr" class="td_chk">
            <label for="mb_mailling_<?php echo $i; ?>" class="sound_only">메일수신</label>
            <input type="checkbox" name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling']?'checked':''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_sms" class="td_chk">
            <label for="mb_sms_<?php echo $i; ?>" class="sound_only">SMS수신</label>
            <input type="checkbox" name="mb_sms[<?php echo $i; ?>]" <?php echo $row['mb_sms']?'checked':''; ?> value="1" id="mb_sms_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_join" class="td_date"><?//php echo $row['mb_datetime']; ?><?=$row['level_datetime']?></td>
		
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;mb_where='.$mb_where.'&amp;mb_level='.$mb_level.'&amp;mb_9='.$mb_9.'&amp;mb_21='.$mb_21.'&amp;mb_s_date='.$mb_s_date.'&amp;mb_e_date='.$mb_e_date.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>
<script>
$(function() {
    $("#mb_s_date, #mb_e_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        maxDate: "+0d"
    });
});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("mb_s_date").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("mb_e_date").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("mb_s_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("mb_e_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("mb_s_date").value = "";
        document.getElementById("mb_e_date").value = "";
    }
}
function down_excel(src){
	document.fsearch.action = src;
	document.fsearch.submit();
	document.fsearch.action = "./member_list.php";
}
</script>
<?php
include_once ('./admin.tail.php');
?>
