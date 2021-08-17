<?php
ob_start();
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('G5_SET_TIME_LIMIT')) define('G5_SET_TIME_LIMIT', 0);
@set_time_limit(G5_SET_TIME_LIMIT);


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================


function g5_path()
{
    $result['path'] = str_replace('\\', '/', dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$g5_path = g5_path();

include_once($g5_path['path'].'/config.php');   // 설정 파일

unset($g5_path);


// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}


//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if (get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(G5_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(G5_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(G5_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(G5_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================


// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);


// 완두콩님이 알려주신 보안관련 오류 수정
// $member 에 값을 직접 넘길 수 있음
$config = array();
$member = array();
$board  = array();
$group  = array();
$g5     = array();


//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = G5_DATA_PATH.'/'.G5_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once(G5_LIB_PATH.'/common.lib.php');    // 공통 라이브러리

    $connect_db = sql_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(G5_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $g5 배열에 저장 - 명랑폐인님 제안
    $g5['connect_db'] = $connect_db;

    sql_set_charset('utf8', $connect_db);
    if(defined('G5_MYSQL_SET_MODE') && G5_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if (defined(G5_TIMEZONE)) sql_query(" set time_zone = '".G5_TIMEZONE."'");
} else {
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>오류! <?php echo G5_VERSION ?> 설치하기</title>
<link rel="stylesheet" href="install/install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">Message</span>
</div>
<h1>그누보드5를 먼저 설치해주십시오.</h1>
<div class="ins_inner">
    <p>다음 파일을 찾을 수 없습니다.</p>
    <ul>
        <li><strong><?php echo G5_DATA_DIR.'/'.G5_DBCONFIG_FILE ?></strong></li>
    </ul>
    <p>그누보드 설치 후 다시 실행하시기 바랍니다.</p>
    <div class="inner_btn">
        <a href="<?php echo G5_URL; ?>/install/"><?php echo G5_VERSION ?> 설치하기</a>
    </div>
</div>
<div id="ins_ft">
    <strong>GNUBOARD5</strong>
    <p>GPL! OPEN SOURCE GNUBOARD</p>
</div>

</body>
</html>

<?php
    exit;
}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

session_save_path(G5_SESSION_PATH);

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0,"/", G5_COOKIE_DOMAIN);
ini_set("session.cookie_domain", G5_COOKIE_DOMAIN);


if( ! class_exists('XenoPostToForm') ){
	class XenoPostToForm
	{
		public static function check() {
			return !isset($_COOKIE['PHPSESSID']) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']) || (! $_SERVER['HTTP_REFERER'] && isset($_POST['P_NOTI'])) ));
		}

		public static function submit($posts) {
			echo '<html><head><meta charset="UTF-8"></head><body>';
			echo '<form id="f" name="f" method="post">';
			echo self::makeInputArray($posts);
			echo '</form>';
			echo '<script>';
					echo 'document.f.submit();';
					echo '</script></body></html>';
			exit;
		}

		public static function makeInputArray($posts) {
			$res = array();
			foreach($posts as $k => $v) {
				$res[] = self::makeInputArray_($k, $v);
			}
			return implode('', $res);
		}

		private static function makeInputArray_($k, $v) {
			if(is_array($v)) {
				$res = array();
				foreach($v as $i => $j) {
					$res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
				}
				return implode('', $res);
			}
			return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
		}
	}
}

if( !function_exists('shop_check_is_pay_page') ){
	function shop_check_is_pay_page(){
		$shop_dir = 'shop';
		$mobile_dir = G5_MOBILE_DIR;

		// PG 결제사의 리턴페이지 목록들
		$pg_checks_pages = array(
			$shop_dir.'/inicis/INIStdPayReturn.php',	// 영카트 5.2.9.5 이하에서 사용됨, 그 이상버전에서는 파일 삭제됨
			$shop_dir.'/inicis/inistdpay_return.php',	// 영카트 5.2.9.6 이상에서 사용됨
			$mobile_dir.'/'.$shop_dir.'/inicis/pay_return.php',
			$mobile_dir.'/'.$shop_dir.'/inicis/pay_approval.php',
			$shop_dir.'/lg/returnurl.php',
			$mobile_dir.'/'.$shop_dir.'/lg/returnurl.php',
			$mobile_dir.'/'.$shop_dir.'/lg/xpay_approval.php',
		);

		$server_script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

		// PG 결제사의 리턴페이지이면
		foreach( $pg_checks_pages as $pg_page ){
			if( preg_match('~'.preg_quote($pg_page).'$~i', $server_script_name) ){
				return true;
			}
		}

		return false;
	}
}

// PG 결제시에 세션이 없으면 내 호출페이지를 다시 호출하여 쿠키 PHPSESSID를 살려내어 세션값을 정상적으로 불러오게 합니다.
// 위와 같이 코드를 전부 한페이지에 넣은 이유는 이전 버전 사용자들이 패치시 어려울수 있으므로 한페이지에 코드를 다 넣었습니다.
if(XenoPostToForm::check()) {
	if ( shop_check_is_pay_page() ){	// PG 결제 리턴페이지에서만 사용
		XenoPostToForm::submit($_POST); // session_start(); 하기 전에
	}
}

@session_start();
//==============================================================================


//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch(" select * from {$g5['config_table']} ");

define('G5_HTTP_BBS_URL',  https_url(G5_BBS_DIR, false));
define('G5_HTTPS_BBS_URL', https_url(G5_BBS_DIR, true));
if ($config['cf_editor'])
    define('G5_EDITOR_LIB', G5_EDITOR_PATH."/{$config['cf_editor']}/editor.lib.php");
else
    define('G5_EDITOR_LIB', G5_LIB_PATH."/editor.lib.php");

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if (isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(G5_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if (isset($_REQUEST['sca']))  {
    $sca = clean_xss_tags(trim($_REQUEST['sca']));
    if ($sca) {
        $sca = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sca);
        $qstr .= '&amp;sca=' . urlencode($sca);
    }
} else {
    $sca = '';
}

if (isset($_REQUEST['sfl']))  {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    if ($sfl)
        $qstr .= '&amp;sfl=' . urlencode($sfl); // search field (검색 필드)
} else {
    $sfl = '';
}


if (isset($_REQUEST['stx']))  { // search text (검색어)
    $stx = get_search_string(trim($_REQUEST['stx']));
    if ($stx)
        $qstr .= '&amp;stx=' . urlencode(cut_str($stx, 20, ''));
} else {
    $stx = '';
}

if (isset($_REQUEST['sst']))  {
    $sst = trim($_REQUEST['sst']);
    $sst = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sst);
    if ($sst)
        $qstr .= '&amp;sst=' . urlencode($sst); // search sort (검색 정렬 필드)
} else {
    $sst = '';
}

if (isset($_REQUEST['sod']))  { // search order (검색 오름, 내림차순)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : '';
    if ($sod)
        $qstr .= '&amp;sod=' . urlencode($sod);
} else {
    $sod = '';
}

if (isset($_REQUEST['sop']))  { // search operator (검색 or, and 오퍼레이터)
    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : '';
    if ($sop)
        $qstr .= '&amp;sop=' . urlencode($sop);
} else {
    $sop = '';
}

if (isset($_REQUEST['spt']))  { // search part (검색 파트[구간])
    $spt = (int)$spt;
    if ($spt)
        $qstr .= '&amp;spt=' . urlencode($spt);
} else {
    $spt = '';
}

if (isset($_REQUEST['page'])) { // 리스트 페이지
    $page = (int)$_REQUEST['page'];
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

if (isset($_REQUEST['w'])) {
    $w = substr($w, 0, 2);
} else {
    $w = '';
}

if (isset($_REQUEST['wr_id'])) {
    $wr_id = (int)$_REQUEST['wr_id'];
} else {
    $wr_id = 0;
}

if (isset($_REQUEST['bo_table'])) {
    $bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['bo_table']));
    $bo_table = substr($bo_table, 0, 20);
} else {
    $bo_table = '';
}

// URL ENCODING
if (isset($_REQUEST['url'])) {
    $url = strip_tags(trim($_REQUEST['url']));
    $urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if (G5_DOMAIN) {
        $p = @parse_url(G5_DOMAIN);
        $urlencode = G5_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}

if (isset($_REQUEST['gr_id'])) {
    if (!is_array($_REQUEST['gr_id'])) {
        $gr_id = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['gr_id']));
    }
} else {
    $gr_id = '';
}
//===================================

//if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){
////if($_SERVER['HTTP_HOST'] == "shop.mainp.kr"){
//	$g5['member_table'] = "portal.g5_member";
//	$g5['point_table'] = "portal.g5_point";
//	$member_confirm_link = "http://www.keymedi.com";
//	$point_txt = "키메디포인트";
//}else{
//	$g5['member_table'] = "shop.g5_member";
//	$g5['point_table'] = "shop.g5_point";
//	$member_confirm_link = "http://obgy.keymedi.com";
//	$point_txt = "마일리지";
//}

//echo $g5['point_table'];
// 자동로그인 부분에서 첫로그인에 포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if ($_SESSION['ss_mb_id']) { // 로그인중이라면

    $member = get_member($_SESSION['ss_mb_id']);

	if($member[mb_where] != "산부인과 협동조합" ){
		$g5['point_table'] = "portal.g5_point";
	}

	$member['mb_point'] =  get_point_sum( $member['mb_id']);

    // 차단된 회원이면 ss_mb_id 초기화
    if($member['mb_intercept_date'] && $member['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
        set_session('ss_mb_id', '');
        $member = array();
    } else {
        // 오늘 처음 로그인 이라면
        if (substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
            // 첫 로그인 포인트 지급
            //insert_point($member['mb_id'], $config['cf_login_point'], G5_TIME_YMD.' 첫로그인', '@login', $member['mb_id'], G5_TIME_YMD);

            // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
            // 해당 회원의 접근일시와 IP 를 저장
            $sql = " update {$g5['member_table']} set mb_today_login = '".G5_TIME_YMDHIS."', mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where mb_id = '{$member['mb_id']}' ";
            sql_query($sql);
        }
    }
} else {
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if ($tmp_mb_id = get_cookie('ck_mb_id')) {

        //$tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        /*
		if (strtolower($tmp_mb_id) != strtolower($config['cf_admin'])) {
            $sql = " select mb_password, mb_intercept_date, mb_leave_date, mb_email_certify from {$g5['member_table']} where mb_id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
            // 쿠키에 저장된 키와 같다면
            $tmp_key = get_cookie('ck_auto');
            if ($tmp_key === $key && $tmp_key) {
                // 차단, 탈퇴가 아니고 메일인증이 사용이면서 인증을 받았다면
                if ($row['mb_intercept_date'] == '' &&
                    $row['mb_leave_date'] == '' &&
                    (!$config['cf_use_email_certify'] || preg_match('/[1-9]/', $row['mb_email_certify'])) ) {
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session('ss_mb_id', $tmp_mb_id);

                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
		*/
    }
    // 자동로그인 end ---------------------------------------
}
// 1 1 2 
$PG_IP = $_SERVER['REMOTE_ADDR'];

//if( $PG_IP != "203.238.37.3" && $PG_IP != "203.238.37.15" && $PG_IP != "203.238.37.16" && $PG_IP != "203.238.37.25" && $PG_IP != "39.115.212.9" )  //PG에서 보냈는지 IP로 체크
//{
//	if(!$member[mb_id]){
//		if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){
//		//if($_SERVER['HTTP_HOST'] == "shop.mainp.kr"){
//			//alert("키메디 로그인 이후 이용부탁드립니다.","http://www.keymedi.com/bbs/login.php?url=".urlencode("http://shop.keymedi.com".$_SERVER['REQUEST_URI']));
//			//alert("키메디 로그인 이후 이용부탁드립니다.","http://portal.mainp.kr/bbs/login.php?url=".urlencode("http://portal.mainp.kr".$_SERVER['REQUEST_URI']));
//		}else{
//			//alert("협동조합몰 로그인 이후 이용부탁드립니다.","http://obgy.keymedi.com");
//		}
//	}
//
//	if($member[mb_level] < 10 && $member[mb_v] != "4"){
//
//		if($member[mb_where] == "메디포털"){
//			if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){ }else{
//			//if($_SERVER['HTTP_HOST'] == "shop.mainp.kr" || $_SERVER['HTTP_HOST'] == "shop.mainp.kr"){ }else{
//				alert("키메디 회원이신분들은 메디몰로 이동합니다.","http://shop.keymedi.com");
//			}
//
//			if($member[mb_shop] != "2"){
//				 //alert("접근하실수 없습니다.","http://www.keymedi.com/");
//			}
//
//			if($member[mb_v] == "4"){
//
//			}else if($member[mb_v] == "1"){
//				if($member[mb_level] < 4){
//					alert("승인처리중 접근하실수 없습니다.","http://www.keymedi.com/");
//					//alert("승인처리중 접근하실수 없습니다.","http://portal.mainp.kr");
//				}
//			}
//
//		}else if($member[mb_where] == "산부인과 협동조합"){
//			if($_SERVER['HTTP_HOST'] == "obgys.keymedi.com" || $_SERVER['HTTP_HOST'] == "obgys.keymedi.co.kr"){ }else{
//			//if($_SERVER['HTTP_HOST'] == "obgys.mainp.kr" || $_SERVER['HTTP_HOST'] == "obgys.mainp.kr"){ }else{
//				alert("협동조합몰 회원이신분들은 해당조합몰로 이동합니다.","http://obgys.keymedi.com");
//			}
//
//			if($member[mb_shop] != "2"){
//				alert("접근하실수 없습니다.","http://obgy.keymedi.com/");
//                //alert("접근하실수 없습니다.","http://obgy.mainp.kr");
//			}
//
//			if($member[mb_v] == "4"){
//
//			}else if($member[mb_v] == "1"){
//				if($member[mb_level] < 4){
//					alert("승인처리중 접근하실수 없습니다.","http://obgy.keymedi.com");
//				}
//			}
//
//		}else{
//			if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){ }else{
//                // alert("접근하실수 없습니다.","http://obgy.keymedi.com/");
//
//                echo '<meta http-equiv="refresh" content="0; url=http://obgy.keymedi.com/"></meta>'; exit;
//			}
//		}
//
//	}
//
//
//}



$write = array();
$write_table = "";
if ($bo_table) {
    $board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");
    if ($board['bo_table']) {
        set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
        $gr_id = $board['gr_id'];
        $write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        //$comment_table = $g5['write_prefix'] . $bo_table . $g5['comment_suffix']; // 코멘트 테이블 전체이름
        if (isset($wr_id) && $wr_id)
            $write = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");
    }
}

if ($gr_id) {
    $group = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id' ");
}


// 회원, 비회원 구분
$is_member = $is_guest = false;
$is_admin = '';
if ($member['mb_id']) {
    $is_member = true;
    $is_admin = is_admin($member['mb_id']);
    $member['mb_dir'] = substr($member['mb_id'],0,2);
} else {
    $is_guest = true;
    $member['mb_id'] = '';
    $member['mb_level'] = 1; // 비회원의 경우 회원레벨을 가장 낮게 설정
}


if ($is_admin != 'super') {
    // 접근가능 IP
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $cf_possible_ip);
        for ($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if (empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if ($is_possible_ip)
                break;
        }
        if (!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['cf_intercept_ip']));
    for ($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if ($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}
//shop_juny
if($member[mb_level] < 10 && $member[mb_v] != "4" ){
	if($member[mb_where] == "메디포털"){
		 $config['cf_theme'] = "basic2";
		 define('SITE_CODE', "mall");
	}else if($member[mb_where] == "산부인과 협동조합"){
		 $config['cf_theme'] = "basic";
		 define('SITE_CODE', "obgys");
	}else{
		 $config['cf_theme'] = "basic2";
		 define('SITE_CODE', "mall");
	}
}else{
	//if($_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){
	if($_SERVER['HTTP_HOST'] == "shop.mainp.kr" || $_SERVER['HTTP_HOST'] == "shop.keymedi.com" || $_SERVER['HTTP_HOST'] == "shop.keymedi.co.kr"){
		$config['cf_theme'] = "basic2";
		 define('SITE_CODE', "mall");
	}else{
		$config['cf_theme'] = "basic";
		 define('SITE_CODE', "obgys");
	}

}
// 테마경로
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true)
    $config['cf_theme'] = trim($_GET['theme']);

if(isset($config['cf_theme']) && trim($config['cf_theme'])) {
    $theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$config['cf_theme'];
    if(is_dir($theme_path)) {
        define('G5_THEME_PATH',        $theme_path);
        define('G5_THEME_URL',         G5_URL.'/'.G5_THEME_DIR.'/'.$config['cf_theme']);
        define('G5_THEME_MOBILE_PATH', $theme_path.'/'.G5_MOBILE_DIR);
        define('G5_THEME_LIB_PATH',    $theme_path.'/'.G5_LIB_DIR);
        define('G5_THEME_CSS_URL',     G5_THEME_URL.'/'.G5_CSS_DIR);
        define('G5_THEME_IMG_URL',     G5_THEME_URL.'/'.G5_IMG_DIR);
        define('G5_THEME_JS_URL',      G5_THEME_URL.'/'.G5_JS_DIR);
    }
    unset($theme_path);
}


// 테마 설정 로드
if(is_file(G5_THEME_PATH.'/theme.config.php'))
    include_once(G5_THEME_PATH.'/theme.config.php');


// 쇼핑몰 설정
if (defined('G5_USE_SHOP') && G5_USE_SHOP)
    include_once(G5_PATH.'/shop.config.php');

//=====================================================================================
// 사용기기 설정
// 테마의 G5_THEME_DEVICE 설정에 따라 사용자 화면 제한됨
// 테마에 별도 설정이 없는 경우 config.php G5_SET_DEVICE 설정에 따라 사용자 화면 제한됨
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//-------------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('G5_THEME_DEVICE') && G5_THEME_DEVICE != '') {
    switch(G5_THEME_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}

if(defined('G5_SET_DEVICE') && $set_device) {
    switch(G5_SET_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}
//==============================================================================

//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
// G5_MOBILE_AGENT : config.php 에서 선언
//------------------------------------------------------------------------------
if (G5_USE_MOBILE && $set_device) {
    if ($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if ($_REQUEST['device']=='mobile')
        $is_mobile = true;
    else if (isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if (is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('G5_IS_MOBILE', $is_mobile);
define('G5_DEVICE_BUTTON_DISPLAY', $set_device);
if (G5_IS_MOBILE) {
    $g5['mobile_path'] = G5_PATH.'/'.$g5['mobile_dir'];
}
//==============================================================================


//==============================================================================
// 스킨경로
//------------------------------------------------------------------------------
if (G5_IS_MOBILE) {
    $board_skin_path    = get_skin_path('board', $board['bo_mobile_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_mobile_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_mobile_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_mobile_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_mobile_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_mobile_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_mobile_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_mobile_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_mobile_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_mobile_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_mobile_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_mobile_faq_skin']);
} else {
    $board_skin_path    = get_skin_path('board', $board['bo_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_faq_skin']);
}
//==============================================================================


// 방문자수의 접속을 남김
include_once(G5_BBS_PATH.'/visit_insert.inc.php');


// 일정 기간이 지난 DB 데이터 삭제 및 최적화
include_once(G5_BBS_PATH.'/db_table.optimize.php');


// common.php 파일을 수정할 필요가 없도록 확장합니다.
$extend_file = array();
$tmp = dir(G5_EXTEND_PATH);
while ($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(G5_EXTEND_PATH.'/'.$file);
    }
}
unset($extend_file);


function Encrypt($str, $secret_key='secret key', $secret_iv='secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32)    ;

    return str_replace("=", "", base64_encode(
            openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
    );
}


function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32);

    return openssl_decrypt(
        base64_decode($str), "AES-256-CBC", $key, 0, $iv
    );
}


$part_arr = array (
    '가정의학과',
    '결핵과',
    '내과',
    '마취통증의학과',
    '방사선종양학과',
    '병리과',
    '비뇨기과',
    '산부인과',
    '성형외과',
    '소아청소년과',
    '신경과',
    '신경외과',
    '안과',
    '영상의학과',
    '예방의학과',
    '외과',
    '응급의학과',
    '이비인후과',
    '일반의',
    '재활의학과',
    '정신건강의학과',
    '정형외과',
    '직업환경의학과',
    '진단검사의학과',
    '피부과',
    '핵의학과',
    '흉부외과',
    '치과'
);

$jobpart_arr = array (
    '개원의',
    '봉직의',
    '군의관',
    '공보의',
    '기타(휴직 등)'
);


$scholaship_arr = array (
    '대한검진의학회',
    '대한노인의학회',
    '대한밸런스의학회',
    '대한산부인과의사회',
    '대한성장의학회',
    '대한약물영양의학회',
    '대한여성성의학회',
    '대한외과의사회',
    '대한일차진료학회',
    '한국임상고혈압학회',
    '대한흉부심장혈관외과의사회',
    '최소침습성형연구회',
    '대한정주의학회',
    '하지정맥류연구회',
    '대한당뇨병학회',
    '없음'
);

$area_arr = array (
    1=>'서울특별시',
    2=>'부산광역시',
    3=>'대구광역시',
    4=>'인천광역시',
    5=>'대전광역시',
    6=>'광주광역시',
    7=>'울산광역시',
    8=>'세종특별자치시',
    9=>'경기도',
    10=>'강원도',
    11=>'경상남도',
    12=>'경상북도',
    13=>'전라남도',
    14=>'전라북도',
    15=>'충청남도',
    16=>'충청북도',
    17=>'제주특별자치도',
    18=>'기타',
    19=>'해외'
);


$level_arr = array (
    1=>'준회원',
    2=>'기업회원',
    3=>'학회회원',
    4=>'의사회원',
    5=>'건기식회원',
    6=>'의사회원(몰)',
    7=>'공급사',
    8=>'직원',
    10=>'최고관리자'
);

$_new_medical_category = array(
    "10"=>"10",
    "1050"=>"1050",
    "1060"=>"1060",
    "106010"=>"106010",
    "1070"=>"1070",
    "1080"=>"1080",
    "1090"=>"1090",
    "10a0"=>"10a0",
    "10b0"=>"10b0",
    "10c0"=>"10c0",
    "10d0"=>"10d0",
    "10e0"=>"10e0",
    "10f0"=>"10f0",
    "10g0"=>"10g0",
    "10h0"=>"10h0",
    "10i0"=>"10i0",
    "10j0"=>"10j0",
    "20"=>"20",
    "2000"=>"2000",
    "2010"=>"2010",
    "201010"=>"201010",
    "201020"=>"201020",
    "201030"=>"201030",
    "201040"=>"201040",
    "201050"=>"201050",
    "201060"=>"201060",
    "201080"=>"201080",
    "201090"=>"201090",
    "2010a0"=>"2010a0",
    "2010b0"=>"2010b0",
    "2010c0"=>"2010c0",
    "2010d0"=>"2010d0",
    "2010e0"=>"2010e0",
    "2010f0"=>"2010f0",
    "2010g0"=>"2010g0",
    "2010h0"=>"2010h0",
    "2020"=>"2020",
    "2030"=>"2030",
    "203010"=>"203010",
    "203020"=>"203020",
    "203030"=>"203030",
    "203040"=>"203040",
    "203050"=>"203050",
    "203060"=>"203060",
    "203070"=>"203070",
    "203080"=>"203080",
    "203090"=>"203090",
    "2030a0"=>"2030a0",
    "2030b0"=>"2030b0",
    "2030c0"=>"2030c0",
    "2030d0"=>"2030d0",
    "2030e0"=>"2030e0",
    "2030f0"=>"2030f0",
    "2030g0"=>"2030g0",
    "2030h0"=>"2030h0",
    "2030i0"=>"2030i0",
    "2040"=>"2040",
    "204010"=>"204010",
    "204020"=>"204020",
    "204030"=>"204030",
    "204040"=>"204040",
    "204050"=>"204050",
    "2050"=>"2050",
    "2060"=>"2060",
    "2070"=>"2070",
    "207010"=>"207010",
    "207020"=>"207020",
    "207030"=>"207030",
    "207040"=>"207040",
    "207050"=>"207050",
    "207060"=>"207060",
    "207070"=>"207070",
    "207080"=>"207080",
    "2080"=>"2080",
    "208010"=>"208010",
    "208020"=>"208020",
    "208030"=>"208030",
    "208040"=>"208040",
    "208050"=>"208050",
    "208060"=>"208060",
    "2090"=>"2090",
    "20a0"=>"20a0",
    "20a010"=>"20a010",
    "20a020"=>"20a020",
    "20a030"=>"20a030",
    "20a040"=>"20a040",
    "20a050"=>"20a050",
    "20a060"=>"20a060",
    "20a070"=>"20a070",
    "20a080"=>"20a080",
    "20b0"=>"20b0",
    "20c0"=>"20c0",
    "20d0"=>"20d0",
    "20d010"=>"20d010",
    "20e0"=>"20e0",
    "20f0"=>"20f0",
    "20g0"=>"20g0",
    "20h0"=>"20h0",
    "20i0"=>"20i0",
    "20j0"=>"20j0",
    "20k0"=>"20k0",
    "20l0"=>"20l0",
    "20m0"=>"20m0",
    "20n0"=>"20n0",
    "20o0"=>"20o0",
    "20o010"=>"20o010",
    "20o020"=>"20o020",
    "20o030"=>"20o030",
    "20p0"=>"20p0",
    "20p010"=>"20p010",
    "20p020"=>"20p020",
    "20p030"=>"20p030",
    "20p040"=>"20p040",
    "20p050"=>"20p050",
    "20p060"=>"20p060",
    "20p070"=>"20p070",
    "20p080"=>"20p080",
    "20p090"=>"20p090",
    "20p0a0"=>"20p0a0",
    "20p0b0"=>"20p0b0",
    "20p0c0"=>"20p0c0",
    "20p0d0"=>"20p0d0",
    "20p0e0"=>"20p0e0",
    "20p0f0"=>"20p0f0",
    "20p0g0"=>"20p0g0",
    "20q0"=>"20q0",
    "20q010"=>"20q010",
    "20q020"=>"20q020",
    "20q030"=>"20q030",
    "20q040"=>"20q040",
    "20q050"=>"20q050",
    "20q060"=>"20q060",
    "20r0"=>"20r0",
    "20s0"=>"20s0",
    "20s010"=>"20s010",
    "20s020"=>"20s020",
    "20s030"=>"20s030",
    "20s040"=>"20s040",
    "20s050"=>"20s050",
    "20s060"=>"20s060",
    "20s070"=>"20s070",
    "20s080"=>"20s080",
    "20s090"=>"20s090",
    "20s0a0"=>"20s0a0",
    "20t0"=>"20t0",
    "20u0"=>"20u0",
    "20u010"=>"20u010",
    "20u020"=>"20u020",
    "20u030"=>"20u030",
    "20u040"=>"20u040",
    "20u050"=>"20u050",
    "20u060"=>"20u060",
    "20v0"=>"20v0",
    "20w0"=>"20w0",
    "20w010"=>"20w010",
    "20w020"=>"20w020",
    "20w030"=>"20w030",
    "20w040"=>"20w040",
    "20w050"=>"20w050",
    "20w060"=>"20w060",
    "20w070"=>"20w070",
    "20x0"=>"20x0",
    "20x010"=>"20x010",
    "20x020"=>"20x020",
    "20x030"=>"20x030",
    "20x040"=>"20x040",
    "20x050"=>"20x050",
    "20x060"=>"20x060",
    "20x070"=>"20x070",
    "20x080"=>"20x080",
    "20x090"=>"20x090",
    "20y0"=>"20y0",
    "20y010"=>"20y010",
    "20y020"=>"20y020",
    "20y030"=>"20y030",
    "20y040"=>"20y040",
    "20y050"=>"20y050",
    "20y060"=>"20y060",
    "20y070"=>"20y070",
    "20y080"=>"20y080",
    "20y090"=>"20y090",
    "20y0a0"=>"20y0a0",
    "20y0b0"=>"20y0b0",
    "20y0c0"=>"20y0c0",
    "20y0d0"=>"20y0d0",
    "20y0e0"=>"20y0e0",
    "20z0"=>"20z0",
    "20z010"=>"20z010",
    "20z020"=>"20z020",
    "20z030"=>"20z030",
    "20z040"=>"20z040",
    "20z050"=>"20z050",
    "20z1"=>"20z1",
    "20z110"=>"20z110",
    "20z120"=>"20z120",
    "20z130"=>"20z130",
    "20z140"=>"20z140",
    "20z150"=>"20z150",
    "30"=>"30",
    "3000"=>"3000",
    "3010"=>"3010",
    "3020"=>"3020",
    "3030"=>"3030",
    "3040"=>"3040",
    "3050"=>"3050",
    "305010"=>"305010",
    "3060"=>"3060",
    "3070"=>"3070",
    "307010"=>"307010",
    "307020"=>"307020",
    "3090"=>"3090",
    "30a0"=>"30a0",
    "30b0"=>"30b0",
    "30c0"=>"30c0",
    "30d0"=>"30d0",
    "30e0"=>"30e0",
    "30e010"=>"30e010",
    "30f0"=>"30f0",
    "30g0"=>"30g0",
    "30i0"=>"30i0",
    "30i010"=>"30i010",
    "30i020"=>"30i020",
    "30i030"=>"30i030",
    "30i040"=>"30i040",
    "30i050"=>"30i050",
    "30i060"=>"30i060",
    "30i070"=>"30i070",
    "30j0"=>"30j0",
    "30k0"=>"30k0",
    "30l0"=>"30l0",
    "30m0"=>"30m0",
    "30n0"=>"30n0",
    "30o0"=>"30o0",
    "30p0"=>"30p0",
    "30p010"=>"30p010",
    "30p020"=>"30p020",
    "30p030"=>"30p030",
    "30q0"=>"30q0",
    "30r0"=>"30r0",
    "30r010"=>"30r010",
    "30r020"=>"30r020",
    "30s0"=>"30s0",
    "30s010"=>"30s010",
    "30s020"=>"30s020",
    "30s030"=>"30s030",
    "30s040"=>"30s040",
    "30t0"=>"30t0",
    "30u0"=>"30u0",
    "30v0"=>"30v0",
    "30w0"=>"30w0",
    "30x0"=>"30x0",
    "30y0"=>"30y0",
    "30z0"=>"30z0",
    "30z010"=>"30z010",
    "3100"=>"3100",
    "3101"=>"3101",
    "3120"=>"3120",
    "40"=>"40",
    "4010"=>"4010",
    "4020"=>"4020",
    "4030"=>"4030",
    "4040"=>"4040",
    "4050"=>"4050",
    "a0"=>"a0",
    "a010"=>"a010",
    "a01010"=>"a01010",
    "a01020"=>"a01020",
    "a01030"=>"a01030",
    "a01050"=>"a01050",
    "a01060"=>"a01060",
    "a01070"=>"a01070",
    "a01080"=>"a01080",
    "a01090"=>"a01090",
    "a010a0"=>"a010a0",
    "a010b0"=>"a010b0",
    "a010c0"=>"a010c0",
    "a010d0"=>"a010d0",
    "a010e0"=>"a010e0",
    "a010f0"=>"a010f0",
    "a090"=>"a090",
    "d0"=>"d0",
    "d020"=>"d020",
    "d030"=>"d030",
    "d040"=>"d040",
    "d050"=>"d050",
    "d060"=>"d060",
    "d070"=>"d070",
    "j0"=>"j0",
    "j010"=>"j010",
    "j020"=>"j020",
    "j030"=>"j030",
    "j040"=>"j040",
    "j041"=>"j041",
    "j050"=>"j050",
    "j060"=>"j060",
    "j070"=>"j070",
    "j080"=>"j080",
    "j090"=>"j090",
    "j0a0"=>"j0a0",
    "j0b0"=>"j0b0",
    "j0c0"=>"j0c0",
    "j0d0"=>"j0d0",
    "j0e0"=>"j0e0",
    "j0f0"=>"j0f0",
    "j0g0"=>"j0g0",
    "j0h0"=>"j0h0",
    "j0i0"=>"j0i0",
    "j0j0"=>"j0j0",
    "j0k0"=>"j0k0",
    "j0l0"=>"j0l0",
    "j0m0"=>"j0m0",
    "j0n0"=>"j0n0",
    "j0o0"=>"j0o0"
);

$_new_normal_category = array(
    "50"=>"50",
    "5010"=>"5010",
    "5020"=>"5020",
    "5030"=>"5030",
    "5040"=>"5040",
    "5050"=>"5050",
    "70"=>"70",
    "7010"=>"7010",
    "701010"=>"701010",
    "701020"=>"701020",
    "70102010"=>"70102010",
    "70102020"=>"70102020",
    "70102030"=>"70102030",
    "70102040"=>"70102040",
    "70102050"=>"70102050",
    "70102060"=>"70102060",
    "70102070"=>"70102070",
    "7020"=>"7020",
    "7030"=>"7030",
    "7040"=>"7040",
    "7050"=>"7050",
    "7060"=>"7060",
    "7070"=>"7070",
    "7080"=>"7080",
    "7090"=>"7090",
    "70a0"=>"70a0",
    "90"=>"90",
    "9010"=>"9010",
    "9020"=>"9020",
    "9030"=>"9030",
    "9040"=>"9040",
    "9050"=>"9050",
    "9060"=>"9060",
    "9070"=>"9070",
    "9080"=>"9080",
    "9090"=>"9090",
    "90a0"=>"90a0",
    "a0"=>"a0",
    "a010"=>"a010",
    "a01010"=>"a01010",
    "a01020"=>"a01020",
    "a01030"=>"a01030",
    "a01050"=>"a01050",
    "a01060"=>"a01060",
    "a01070"=>"a01070",
    "a01080"=>"a01080",
    "a01090"=>"a01090",
    "a010a0"=>"a010a0",
    "a010b0"=>"a010b0",
    "a010c0"=>"a010c0",
    "a010d0"=>"a010d0",
    "a010e0"=>"a010e0",
    "a010f0"=>"a010f0",
    "a090"=>"a090",
    "a0a0"=>"a0a0",
    "a0a010"=>"a0a010",
    "a0a020"=>"a0a020",
    "a0a030"=>"a0a030",
    "a0a040"=>"a0a040",
    "a0b0"=>"a0b0",
    "a0c0"=>"a0c0",
    "a0c010"=>"a0c010",
    "a0c020"=>"a0c020",
    "a0c030"=>"a0c030",
    "a0c040"=>"a0c040",
    "a0d0"=>"a0d0",
    "a0d010"=>"a0d010",
    "a0d020"=>"a0d020",
    "a0e0"=>"a0e0",
    "a0f0"=>"a0f0",
    "a0g0"=>"a0g0",
    "a0h0"=>"a0h0",
    "b0"=>"b0",
    "b010"=>"b010",
    "b01010"=>"b01010",
    "b01020"=>"b01020",
    "b01030"=>"b01030",
    "b020"=>"b020",
    "b02010"=>"b02010",
    "b02020"=>"b02020",
    "b02030"=>"b02030",
    "b02040"=>"b02040",
    "b02050"=>"b02050",
    "b02060"=>"b02060",
    "b02070"=>"b02070",
    "b02080"=>"b02080",
    "b02090"=>"b02090",
    "b020a0"=>"b020a0",
    "b020b0"=>"b020b0",
    "b020c0"=>"b020c0",
    "b020d0"=>"b020d0",
    "b020e0"=>"b020e0",
    "b020f0"=>"b020f0",
    "b020g0"=>"b020g0",
    "b030"=>"b030",
    "b03010"=>"b03010",
    "b03020"=>"b03020",
    "b03030"=>"b03030",
    "b03040"=>"b03040",
    "b03050"=>"b03050",
    "b03060"=>"b03060",
    "b03070"=>"b03070",
    "b03080"=>"b03080",
    "b03090"=>"b03090",
    "b030a0"=>"b030a0",
    "b030b0"=>"b030b0",
    "b030c0"=>"b030c0",
    "b030d0"=>"b030d0",
    "b040"=>"b040",
    "b04010"=>"b04010",
    "b04020"=>"b04020",
    "b04030"=>"b04030",
    "b04040"=>"b04040",
    "b04050"=>"b04050",
    "b050"=>"b050",
    "b05010"=>"b05010",
    "b05020"=>"b05020",
    "b05030"=>"b05030",
    "b05040"=>"b05040",
    "b05050"=>"b05050",
    "b060"=>"b060",
    "b06010"=>"b06010",
    "b06020"=>"b06020",
    "b06030"=>"b06030",
    "b06040"=>"b06040",
    "b06050"=>"b06050",
    "b06060"=>"b06060",
    "b06070"=>"b06070",
    "b06080"=>"b06080",
    "b070"=>"b070",
    "b07010"=>"b07010",
    "b07020"=>"b07020",
    "b07030"=>"b07030",
    "b07040"=>"b07040",
    "b07050"=>"b07050",
    "b07060"=>"b07060",
    "b07070"=>"b07070",
    "b07080"=>"b07080",
    "b07090"=>"b07090",
    "b070a0"=>"b070a0",
    "b070b0"=>"b070b0",
    "b070c0"=>"b070c0",
    "b070c010"=>"b070c010",
    "b070c020"=>"b070c020",
    "b070c030"=>"b070c030",
    "b070c040"=>"b070c040",
    "b070c050"=>"b070c050",
    "b070d0"=>"b070d0",
    "b070e0"=>"b070e0",
    "b070f0"=>"b070f0",
    "b070g0"=>"b070g0",
    "b070h0"=>"b070h0",
    "b080"=>"b080",
    "b08010"=>"b08010",
    "b08020"=>"b08020",
    "b0802010"=>"b0802010",
    "b0802020"=>"b0802020",
    "b0802030"=>"b0802030",
    "b0802040"=>"b0802040",
    "b0802050"=>"b0802050",
    "b0802060"=>"b0802060",
    "b0802070"=>"b0802070",
    "b0802080"=>"b0802080",
    "b0802090"=>"b0802090",
    "b08030"=>"b08030",
    "b08040"=>"b08040",
    "b08050"=>"b08050",
    "b08060"=>"b08060",
    "b0806010"=>"b0806010",
    "b0806020"=>"b0806020",
    "b0806030"=>"b0806030",
    "b08070"=>"b08070",
    "b08080"=>"b08080",
    "b0808030"=>"b0808030",
    "b0808040"=>"b0808040",
    "b0808050"=>"b0808050",
    "b0808060"=>"b0808060",
    "b0808070"=>"b0808070",
    "b0808080"=>"b0808080",
    "b0808090"=>"b0808090",
    "b08090"=>"b08090",
    "b0809010"=>"b0809010",
    "b0809020"=>"b0809020",
    "b0809030"=>"b0809030",
    "b0809040"=>"b0809040",
    "b0809050"=>"b0809050",
    "b0809060"=>"b0809060",
    "b0809070"=>"b0809070",
    "b0809080"=>"b0809080",
    "b0809090"=>"b0809090",
    "b08090a0"=>"b08090a0",
    "b08090b0"=>"b08090b0",
    "b08090c0"=>"b08090c0",
    "b08090d0"=>"b08090d0",
    "b08090d010"=>"b08090d010",
    "b08090d020"=>"b08090d020",
    "b08090d030"=>"b08090d030",
    "b08090d040"=>"b08090d040",
    "b08090d050"=>"b08090d050",
    "b08090e0"=>"b08090e0",
    "b08090f0"=>"b08090f0",
    "b08090g0"=>"b08090g0",
    "b08090h0"=>"b08090h0",
    "b080a0"=>"b080a0",
    "b080b0"=>"b080b0",
    "b080b010"=>"b080b010",
    "b080b020"=>"b080b020",
    "b080b030"=>"b080b030",
    "b080b040"=>"b080b040",
    "b080b050"=>"b080b050",
    "b080b060"=>"b080b060",
    "b080b070"=>"b080b070",
    "b080c0"=>"b080c0",
    "b080c010"=>"b080c010",
    "b080c020"=>"b080c020",
    "b080c030"=>"b080c030",
    "b080c040"=>"b080c040",
    "b080c050"=>"b080c050",
    "b080d0"=>"b080d0",
    "b080d010"=>"b080d010",
    "b080d020"=>"b080d020",
    "b080d030"=>"b080d030",
    "b080d040"=>"b080d040",
    "b080d050"=>"b080d050",
    "b080d060"=>"b080d060",
    "b080d070"=>"b080d070",
    "b080d080"=>"b080d080",
    "b080d090"=>"b080d090",
    "b080d0a0"=>"b080d0a0",
    "b080d0b0"=>"b080d0b0",
    "b080d0c0"=>"b080d0c0",
    "b080e0"=>"b080e0",
    "b080e010"=>"b080e010",
    "b080e020"=>"b080e020",
    "b080e030"=>"b080e030",
    "b080e040"=>"b080e040",
    "b080e050"=>"b080e050",
    "b080e060"=>"b080e060",
    "b080e070"=>"b080e070",
    "b080e080"=>"b080e080",
    "b080e090"=>"b080e090",
    "b080e0a0"=>"b080e0a0",
    "b080e0b0"=>"b080e0b0",
    "b080e0c0"=>"b080e0c0",
    "b080f0"=>"b080f0",
    "b080g0"=>"b080g0",
    "b080h0"=>"b080h0",
    "b080i0"=>"b080i0",
    "b090"=>"b090",
    "b09010"=>"b09010",
    "b09020"=>"b09020",
    "b09030"=>"b09030",
    "b09040"=>"b09040",
    "b0a0"=>"b0a0",
    "b0a010"=>"b0a010",
    "b0a020"=>"b0a020",
    "b0a030"=>"b0a030",
    "b0a040"=>"b0a040",
    "b0a050"=>"b0a050",
    "b0a060"=>"b0a060",
    "b0a070"=>"b0a070",
    "b0a080"=>"b0a080",
    "b0a090"=>"b0a090",
    "b0b0"=>"b0b0",
    "b0b010"=>"b0b010",
    "b0b01010"=>"b0b01010",
    "b0b01020"=>"b0b01020",
    "b0b01030"=>"b0b01030",
    "b0b01040"=>"b0b01040",
    "b0b020"=>"b0b020",
    "b0b030"=>"b0b030",
    "b0b040"=>"b0b040",
    "b0b050"=>"b0b050",
    "b0b05010"=>"b0b05010",
    "b0b05020"=>"b0b05020",
    "b0b05030"=>"b0b05030",
    "b0b05040"=>"b0b05040",
    "b0b05050"=>"b0b05050",
    "b0b05060"=>"b0b05060",
    "b0b05070"=>"b0b05070",
    "b0b05080"=>"b0b05080",
    "b0b060"=>"b0b060",
    "b0b06010"=>"b0b06010",
    "b0b06020"=>"b0b06020",
    "b0b06030"=>"b0b06030",
    "b0b06040"=>"b0b06040",
    "b0b070"=>"b0b070",
    "b0b07010"=>"b0b07010",
    "b0b07020"=>"b0b07020",
    "b0b07030"=>"b0b07030",
    "b0b080"=>"b0b080",
    "b0b090"=>"b0b090",
    "b0b0a0"=>"b0b0a0",
    "b0b0a010"=>"b0b0a010",
    "b0b0a020"=>"b0b0a020",
    "b0b0a030"=>"b0b0a030",
    "b0b0a040"=>"b0b0a040",
    "b0c0"=>"b0c0",
    "b0c010"=>"b0c010",
    "b0c020"=>"b0c020",
    "b0c030"=>"b0c030",
    "b0c040"=>"b0c040",
    "b0c050"=>"b0c050",
    "b0c060"=>"b0c060",
    "b0c070"=>"b0c070",
    "b0c07010"=>"b0c07010",
    "b0c07020"=>"b0c07020",
    "b0c07030"=>"b0c07030",
    "b0c080"=>"b0c080",
    "b0c090"=>"b0c090",
    "b0c0a0"=>"b0c0a0",
    "b0c0b0"=>"b0c0b0",
    "b0c0c0"=>"b0c0c0",
    "b0c0d0"=>"b0c0d0",
    "b0c0e0"=>"b0c0e0",
    "b0c0f0"=>"b0c0f0",
    "b0c0g0"=>"b0c0g0",
    "b0c0h0"=>"b0c0h0",
    "b0c0i0"=>"b0c0i0",
    "b0c0j0"=>"b0c0j0",
    "b0c0k0"=>"b0c0k0",
    "b0c0k010"=>"b0c0k010",
    "b0c0l0"=>"b0c0l0",
    "b0c0l010"=>"b0c0l010",
    "b0c0l020"=>"b0c0l020",
    "b0c0l030"=>"b0c0l030",
    "b0c0l040"=>"b0c0l040",
    "b0c0l050"=>"b0c0l050",
    "b0c0l060"=>"b0c0l060",
    "b0c0l070"=>"b0c0l070",
    "b0c0l080"=>"b0c0l080",
    "b0c0l090"=>"b0c0l090",
    "b0c0m0"=>"b0c0m0",
    "b0c0m010"=>"b0c0m010",
    "b0c0n0"=>"b0c0n0",
    "b0c0n010"=>"b0c0n010",
    "b0c0n020"=>"b0c0n020",
    "b0c0n030"=>"b0c0n030",
    "b0c0n040"=>"b0c0n040",
    "b0d0"=>"b0d0",
    "b0d010"=>"b0d010",
    "b0d020"=>"b0d020",
    "b0d030"=>"b0d030",
    "b0d040"=>"b0d040",
    "b0d050"=>"b0d050",
    "b0d060"=>"b0d060",
    "b0d070"=>"b0d070",
    "b0d080"=>"b0d080",
    "b0d090"=>"b0d090",
    "b0d0a0"=>"b0d0a0",
    "b0d0b0"=>"b0d0b0",
    "b0d0c0"=>"b0d0c0",
    "b0d0c010"=>"b0d0c010",
    "b0d0c020"=>"b0d0c020",
    "b0d0c030"=>"b0d0c030",
    "b0d0c040"=>"b0d0c040",
    "b0d0c050"=>"b0d0c050",
    "b0d0d0"=>"b0d0d0",
    "b0d0e0"=>"b0d0e0",
    "b0d0f0"=>"b0d0f0",
    "b0e0"=>"b0e0",
    "b0e010"=>"b0e010",
    "b0e020"=>"b0e020",
    "b0e030"=>"b0e030",
    "b0e040"=>"b0e040",
    "b0e050"=>"b0e050",
    "b0f0"=>"b0f0",
    "b0f010"=>"b0f010",
    "b0f020"=>"b0f020",
    "b0f030"=>"b0f030",
    "b0f040"=>"b0f040",
    "b0f04010"=>"b0f04010",
    "b0f04020"=>"b0f04020",
    "b0f04030"=>"b0f04030",
    "b0f04040"=>"b0f04040",
    "b0f04050"=>"b0f04050",
    "b0f04060"=>"b0f04060",
    "b0f04070"=>"b0f04070",
    "b0f04080"=>"b0f04080",
    "b0f050"=>"b0f050",
    "b0f060"=>"b0f060",
    "b0f070"=>"b0f070",
    "b0f080"=>"b0f080",
    "b0f090"=>"b0f090",
    "b0f0a0"=>"b0f0a0",
    "b0f0b0"=>"b0f0b0",
    "b0f0c0"=>"b0f0c0",
    "b0f0d0"=>"b0f0d0",
    "b0f0e0"=>"b0f0e0",
    "b0g0"=>"b0g0",
    "b0g010"=>"b0g010",
    "b0g020"=>"b0g020",
    "b0g030"=>"b0g030",
    "b0g040"=>"b0g040",
    "b0g050"=>"b0g050",
    "b0g060"=>"b0g060",
    "b0g070"=>"b0g070",
    "b0g080"=>"b0g080",
    "b0h0"=>"b0h0",
    "b0h010"=>"b0h010",
    "b0h020"=>"b0h020",
    "b0h02010"=>"b0h02010",
    "b0h02020"=>"b0h02020",
    "b0h02030"=>"b0h02030",
    "b0h02040"=>"b0h02040",
    "b0h02050"=>"b0h02050",
    "b0h02060"=>"b0h02060",
    "b0h02070"=>"b0h02070",
    "b0h030"=>"b0h030",
    "b0h040"=>"b0h040",
    "b0h050"=>"b0h050",
    "b0i0"=>"b0i0",
    "b0i010"=>"b0i010",
    "b0i020"=>"b0i020",
    "b0i030"=>"b0i030",
    "b0i040"=>"b0i040",
    "b0i050"=>"b0i050",
    "b0i060"=>"b0i060",
    "b0i070"=>"b0i070",
    "b0i080"=>"b0i080",
    "b0i090"=>"b0i090",
    "b0j0"=>"b0j0",
    "b0j010"=>"b0j010",
    "b0j020"=>"b0j020",
    "b0j030"=>"b0j030",
    "b0j040"=>"b0j040",
    "b0j050"=>"b0j050",
    "b0j060"=>"b0j060",
    "b0j070"=>"b0j070",
    "b0j080"=>"b0j080",
    "b0k0"=>"b0k0",
    "b0k010"=>"b0k010",
    "b0l0"=>"b0l0",
    "b0l010"=>"b0l010",
    "b0l020"=>"b0l020",
    "b0l030"=>"b0l030",
    "b0l040"=>"b0l040",
    "b0l050"=>"b0l050",
    "b0l060"=>"b0l060",
    "b0m0"=>"b0m0",
    "b0m010"=>"b0m010",
    "b0m020"=>"b0m020",
    "b0m030"=>"b0m030",
    "b0m040"=>"b0m040",
    "b0m050"=>"b0m050",
    "b0n0"=>"b0n0",
    "b0n010"=>"b0n010",
    "b0n020"=>"b0n020",
    "b0n030"=>"b0n030",
    "b0n040"=>"b0n040",
    "b0n050"=>"b0n050",
    "b0n060"=>"b0n060",
    "b0n070"=>"b0n070",
    "b0n080"=>"b0n080",
    "b0n090"=>"b0n090",
    "b0n0a0"=>"b0n0a0",
    "b0n0b0"=>"b0n0b0",
    "b0n0c0"=>"b0n0c0",
    "b0n0d0"=>"b0n0d0",
    "b0n0d010"=>"b0n0d010",
    "b0n0d020"=>"b0n0d020",
    "b0n0e0"=>"b0n0e0",
    "b0n0f0"=>"b0n0f0",
    "b0n0g0"=>"b0n0g0",
    "b0o0"=>"b0o0",
    "b0o010"=>"b0o010",
    "b0o020"=>"b0o020",
    "b0o030"=>"b0o030",
    "b0o03010"=>"b0o03010",
    "b0o040"=>"b0o040",
    "b0o050"=>"b0o050",
    "b0p0"=>"b0p0",
    "b0p010"=>"b0p010",
    "b0p020"=>"b0p020",
    "b0p030"=>"b0p030",
    "b0q0"=>"b0q0",
    "b0q010"=>"b0q010",
    "b0q020"=>"b0q020",
    "b0q030"=>"b0q030",
    "b0q040"=>"b0q040",
    "b0q050"=>"b0q050",
    "b0q060"=>"b0q060",
    "b0r0"=>"b0r0",
    "b0s0"=>"b0s0",
    "c0"=>"c0",
    "c090"=>"c090",
    "c09010"=>"c09010",
    "c09020"=>"c09020",
    "c09030"=>"c09030",
    "c09040"=>"c09040",
    "c0b0"=>"c0b0",
    "c0c0"=>"c0c0",
    "c0d0"=>"c0d0",
    "c0e0"=>"c0e0",
    "c0f0"=>"c0f0",
    "c0g0"=>"c0g0",
    "d0"=>"d0",
    "d020"=>"d020",
    "d030"=>"d030",
    "d040"=>"d040",
    "d050"=>"d050",
    "d060"=>"d060",
    "d070"=>"d070",
    "f0"=>"f0",
    "f1"=>"f1",
    "f2"=>"f2",
    "f3"=>"f3",
    "f4"=>"f4",
    "f5"=>"f5",
    "f6"=>"f6",
    "f7"=>"f7",
    "f8"=>"f8",
    "f9"=>"f9",
    "g0"=>"g0",
    "g010"=>"g010",
    "g020"=>"g020",
    "i0"=>"i0",
    "j0"=>"j0",
    "j010"=>"j010",
    "j020"=>"j020",
    "j030"=>"j030",
    "j040"=>"j040",
    "j041"=>"j041",
    "j050"=>"j050",
    "j060"=>"j060",
    "j070"=>"j070",
    "j080"=>"j080",
    "j090"=>"j090",
    "j0a0"=>"j0a0",
    "j0b0"=>"j0b0",
    "j0c0"=>"j0c0",
    "j0d0"=>"j0d0",
    "j0e0"=>"j0e0",
    "j0f0"=>"j0f0",
    "j0g0"=>"j0g0",
    "j0h0"=>"j0h0",
    "j0i0"=>"j0i0",
    "j0j0"=>"j0j0",
    "j0k0"=>"j0k0",
    "j0l0"=>"j0l0",
    "j0m0"=>"j0m0",
    "j0n0"=>"j0n0",
    "j0o0"=>"j0o0",
    "k0"=>"k0",
    "k010"=>"k010",
    "k01010"=>"k01010",
    "k01020"=>"k01020",
    "k01030"=>"k01030",
    "k01040"=>"k01040",
    "k01050"=>"k01050",
    "k01070"=>"k01070",
    "k020"=>"k020",
    "k030"=>"k030",
    "k040"=>"k040",
    "k050"=>"k050",
    "k060"=>"k060",
    "k070"=>"k070",
    "k080"=>"k080",
    "k090"=>"k090",
    "k0a0"=>"k0a0",
    "k0b0"=>"k0b0",
    "l0"=>"l0",
    "l010"=>"l010",
    "l020"=>"l020",
    "l030"=>"l030",
    "l040"=>"l040",
    "l050"=>"l050",
    "l060"=>"l060",
    "l070"=>"l070",
    "l080"=>"l080",
    "l090"=>"l090",
    "l0a0"=>"l0a0",
    "l0b0"=>"l0b0",
    "l0c0"=>"l0c0",
    "l0d0"=>"l0d0",
    "l0e0"=>"l0e0",
    "l0f0"=>"l0f0",
    "l0g0"=>"l0g0",
    "l0h0"=>"l0h0",
    "l0i0"=>"l0i0",
    "l0j0"=>"l0j0",
    "l0k0"=>"l0k0",
    "m0"=>"m0",
    "n0"=>"n0",
    "n010"=>"n010",
    "n01010"=>"n01010",
    "n0101010"=>"n0101010",
    "n010101010"=>"n010101010",
    "n010101020"=>"n010101020",
    "n010101030"=>"n010101030",
    "n010101040"=>"n010101040",
    "n010101050"=>"n010101050",
    "n0101020"=>"n0101020",
    "n010102010"=>"n010102010",
    "n010102020"=>"n010102020",
    "n010102030"=>"n010102030",
    "n01020"=>"n01020",
    "n0102010"=>"n0102010",
    "n0102020"=>"n0102020",
    "n01030"=>"n01030",
    "n0103010"=>"n0103010",
    "n0103020"=>"n0103020",
    "n01040"=>"n01040",
    "n0104010"=>"n0104010",
    "n0104020"=>"n0104020",
    "n01050"=>"n01050",
    "n0105010"=>"n0105010",
    "n0105020"=>"n0105020",
    "n01060"=>"n01060",
    "n01070"=>"n01070",
    "n0107010"=>"n0107010",
    "n0107020"=>"n0107020",
    "n0107030"=>"n0107030",
    "n0107040"=>"n0107040",
    "n0107050"=>"n0107050",
    "n0107060"=>"n0107060",
    "n0107070"=>"n0107070",
    "n020"=>"n020",
    "n02010"=>"n02010",
    "n0201010"=>"n0201010",
    "n0201020"=>"n0201020",
    "n02020"=>"n02020",
    "n0202010"=>"n0202010",
    "n0202020"=>"n0202020",
    "n02030"=>"n02030",
    "n0203010"=>"n0203010",
    "n0203020"=>"n0203020",
    "n02040"=>"n02040",
    "n030"=>"n030",
    "n040"=>"n040",
    "n04010"=>"n04010",
    "n0401010"=>"n0401010",
    "n0401020"=>"n0401020",
    "n0401030"=>"n0401030",
    "n0401040"=>"n0401040",
    "n0401050"=>"n0401050",
    "n0401060"=>"n0401060",
    "n0401070"=>"n0401070",
    "n04020"=>"n04020",
    "n04030"=>"n04030",
    "n04040"=>"n04040",
    "n04050"=>"n04050",
    "n04060"=>"n04060",
    "n04070"=>"n04070",
    "n04080"=>"n04080",
    "n0408010"=>"n0408010",
    "n0408020"=>"n0408020",
    "n0408030"=>"n0408030",
    "n04090"=>"n04090",
    "n040a0"=>"n040a0",
    "n050"=>"n050",
    "n05010"=>"n05010",
    "n0501010"=>"n0501010",
    "n0501020"=>"n0501020",
    "n0501030"=>"n0501030",
    "n0501040"=>"n0501040",
    "n05020"=>"n05020",
    "n05030"=>"n05030",
    "n060"=>"n060",
    "n06010"=>"n06010",
    "n06020"=>"n06020",
    "n06030"=>"n06030",
    "n06040"=>"n06040",
    "n06050"=>"n06050",
    "n070"=>"n070",
    "o0"=>"o0",
    "o010"=>"o010",
    "p0"=>"p0",
    "p010"=>"p010",
    "p020"=>"p020",
    "p02010"=>"p02010",
    "p02020"=>"p02020",
    "p02030"=>"p02030",
    "p02040"=>"p02040",
    "p02050"=>"p02050",
    "p030"=>"p030",
    "p03010"=>"p03010",
    "p03020"=>"p03020",
    "p03030"=>"p03030",
    "p03040"=>"p03040",
    "p040"=>"p040",
    "p04010"=>"p04010",
    "p04020"=>"p04020",
    "p04030"=>"p04030",
    "p04040"=>"p04040",
    "p04050"=>"p04050",
    "p04060"=>"p04060",
    "p04070"=>"p04070",
    "q0"=>"q0",
    "q010"=>"q010",
    "q020"=>"q020",
    "q030"=>"q030",
    "q040"=>"q040"
);

ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

$html_process = new html_process();
?>