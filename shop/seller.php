<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/search.php');
    return;
}

$g5['title'] = "상품 검색 결과";
include_once('./_head.php');

// QUERY 문에 공통적으로 들어가는 내용
// 상품명에 검색어가 포한된것과 상품판매가능인것만
$sql_common = " from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b ";

$where = array();
$where[] = " (a.ca_id = b.ca_id and a.it_use = 1 and b.ca_use = 1) ";

$search_all = true;

switch($_GET[s_select]){
    case "all"      : $search_all = true; break;
    case "qname"    : $_GET['qname'] ="1"; break;
    case "qexplan"  : $_GET['qexplan'] ="1"; break;
    case "qid"      : $_GET['qid'] ="1"; break;
    case "it_5"     : $_GET['it_5'] ="1"; break;
    case "it_10"    : $_GET['it_10'] ="1"; break;
}
// 상세검색 이라면
if (isset($_GET['qname']) || isset($_GET['qexplan']) || isset($_GET['qid']) || isset($_GET['it_5']) || isset($_GET['it_10'])) $search_all = false;
/*
$q       = utf8_strcut(get_search_string(trim($_GET['q'])), 30, "");
*/
$q      = utf8_strcut(trim($_GET['q2']), 30, "");
$qname  = isset($_GET['qname'])    ? trim($_GET['qname']) : '';
$qexplan= isset($_GET['qexplan'])  ? trim($_GET['qexplan']) : '';
$qid    = isset($_GET['qid'])      ? trim($_GET['qid']) : '';
$it_5   = isset($_GET['it_5'])     ? trim($_GET['it_5']) : '';
$it_10  = isset($_GET['it_10'])    ? trim($_GET['it_10']) : '';
$qcaid  = isset($_GET['qcaid'])    ? preg_replace('#[^a-z0-9]#i', '', trim($_GET['qcaid'])) : '';
$qfrom  = isset($_GET['qfrom'])    ? preg_replace('/[^0-9]/', '', trim($_GET['qfrom'])) : '';
$qto    = isset($_GET['qto'])      ? preg_replace('/[^0-9]/', '', trim($_GET['qto'])) : '';
$qsort  = '';
$qorder = '';
$order_by = '';

if (isset($_GET['qsort']))  {
    $qsort = trim($_GET['qsort']);
    $qsort = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $qsort);
}
if (isset($_GET['qorder'])) $qorder = preg_match("/^(asc|desc)$/i", $qorder) ? $qorder : '';

if(!($qname || $qexplan || $qid || $it_5 || $it_10 )) $search_all = true;

// 검색범위 checkbox 처리
$qname_check    = false;
$qexplan_check  = false;
$qid_check      = false;
$it5_check      = false;


if($search_all) {
    $qname_check    = true;
    $qexplan_check  = true;
    $qid_check      = true;
    $it5_check      = true;
} else {
    if($qname)  $qname_check    = true;
    if($qexplan)$qexplan_check  = true;
    if($qid)    $qid_check      = true;
    if($it_5)   $it5_check      = true;
}

if ($q) {

    $arr = explode(" ", $q);
    $detail_where = array();

    for ($i=0; $i<count($arr); $i++) {

        $word = trim($arr[$i]);
        if (!$word) continue;

        $concat = array();
        if ($search_all || $qname)  $concat[] = "a.it_name";
        if ($search_all || $qid)    $concat[] = "a.it_id";
        if ($search_all || $it_5)   $concat[] = "a.it_5";
        if ($search_all || $it_10)  $concat[] = "a.it_10";

        $concat_fields = "concat(".implode(",' ',",$concat).")";

        $detail_where[] = $concat_fields." = '$word' ";

        // 인기검색어
        insert_popular($concat, $word);
    }

    $where[] = "(".implode(" and ", $detail_where).")";
}

$where[] = " a.it_8 != '' ";
$where[] = " (SELECT COUNT(it_id) FROM g5_shop_item WHERE it_id = a.it_8  and it_use = '1' ) > 0 ";

if ($qfrom && $qto)     $where[] = " a.it_price between '$qfrom' and '$qto' ";
if ($q_where=="main")   $where[] = " a.it_id = '$q' ";
if($it_maker)           $where[] = " a.it_maker like '%{$it_maker}%' ";
if ($qcaid)             $where2[] = " and a.ca_id like '$qcaid%' ";

 
 // 총몇개 = 한줄에 몇개 * 몇줄
$default['de_search_list_mod'] = 5;

$items = $default['de_search_list_mod'] * $default['de_search_list_row'];
// 페이지가 없으면 첫 페이지 (1 페이지)
if ($page < 1) $page = 1;
// 시작 레코드 구함
$from_record = ($page - 1) * $items;

$sql_to = sql_query(" select it_8 as it_id {$sql_common} where " . implode(" and ", $where)." group by it_8 order by it_order asc ".$sql_order . $sql_limit ) ;
$it_id_ar = "";
while($rwd = sql_fetch_array($sql_to)){
	if($it_id_ar == ""){
		$it_id_ar = $rwd['it_id'];
	}else{
		$it_id_ar = $it_id_ar.",".$rwd['it_id'];
	}
}

$sql_to = sql_query(" select it_id {$sql_common} where " . implode(" and ", $where) . implode(" and ", $where2)." group by it_8 limit ".$from_record.",". $items ) ;
$it_id_ar2 = "";
while($rwd = sql_fetch_array($sql_to)){
	if($it_id_ar2 == ""){
		$it_id_ar2 = $rwd['it_id'];
	}else{
		$it_id_ar2 = $it_id_ar2.",".$rwd['it_id'];
	}
}

$sql_where = " where it_id in ( {$it_id_ar} ) and " . implode(" and ", $where) . implode(" and ", $where2) ;
$sql_where2 = " where it_id in ( {$it_id_ar} ) and " . implode(" and ", $where) ;
$sql_where3 = " where it_id in ( {$it_id_ar2} ) and " . implode(" and ", $where) . implode(" and ", $where2) ;



// 상품 출력순서가 있다면
$qsort  = strtolower($qsort);
$qorder = strtolower($qorder);
$order_by = "";
// 아래의 $qsort 필드만 정렬이 가능하게 하여 다른 필드로 하여금 유추해 볼수 없게함
if (($qsort == "it_sum_qty" || $qsort == "it_price" || $qsort == "it_use_avg" || $qsort == "it_use_cnt" || $qsort == "it_update_time") &&
    ($qorder == "asc" || $qorder == "desc")) {
    $order_by = ' order by ' . $qsort . ' ' . $qorder . ' , it_order asc';
}else{
	$order_by = " order by it_order asc";
}



// 검색된 내용이 몇행인지를 얻는다
$sql = " select COUNT(*) as cnt $sql_common where " . implode(" and ", $where);

$row = sql_fetch($sql);
$total_count2 = $row['cnt'];

$sql = " select COUNT(*) as cnt $sql_common where " . implode(" and ", $where) . implode(" and ", $where2);
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$total_page  = ceil($total_count / $items); // 전체 페이지 계산

if ($is_admin) {
   // echo '<div class="sit_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/configform.php#anc_scf_etc'.'" class="btn_admin">검색 설정</a></div>';
}
?>



<!-- 검색 시작 { -->
<div id="ssch">

    <!-- 상세검색 항목 시작 { -->
	 <form name="frmdetailsearch">
        <input type="hidden" name="qsort" id="qsort" value="<?php echo $qsort ?>">
        <input type="hidden" name="qorder" id="qorder" value="<?php echo $qorder ?>">
        <input type="hidden" name="qcaid" id="qcaid" value="<?php echo $qcaid ?>">
		<input type="hidden" name="q2" id="q2" value="<?php echo $q2 ?>">
		<input type="hidden" name="s_select" id="s_select" value="<?php echo $s_select ?>">
	</form>
    <div id="seller_frm">
     <?
		$sell = sql_fetch("select * from shop.g5_member where mb_v = '4' and mb_leave_date = '' and mb_id = '$q2' "); 
		switch($sell[mb_13]){
			case "1":$mb_13 = "제약";break;
			case "2":$mb_13 = "도매";break;
			case "3":$mb_13 = "의료기기";break;
			case "4":$mb_13 = "소모품";break;
		}
	 ?>
		<h4><?=$mb_13?></h4>
		<h2><?=$sell[mb_nick]?></h2>
		<div class="sell_box">
			<div class="sell_box_left">
				공급사 정보<br>
				<a href="/bbs/board.php?bo_table=0402"><img src="/img/board/qna_btn.png"></a>
			</div>
			<div class="sell_box_right">
				<p>
                    <? if($sell['mb_tel']){ echo "<span>TEL</span>".$sell['mb_tel']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }?>
                    <? if($sell['mb_2']){ echo "<span>FAX</span>".$sell['mb_2']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }?>
                    <? if($sell['mb_3']){ echo "<span>담당자</span>".$sell['mb_3']; }?>
				</p>
				<!-- <p>
					<? if($sell[mb_profile]){?><span>교환/반품</span><?=$sell[mb_profile]?><?}?>
				</p> -->
				<p>
                    <? if($sell[mb_11]){ echo "<span>배송비정책금액</span>".($sell[mb_11]/10000)."만원 이상 구매시 무료배송&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }?>
                    <? if($sell[mb_12]){ echo "<span>배송비</span>".number_format($sell[mb_12])."원"; }?>
				</p>
				<p>
                    <? if($sell[mb_14] == "1"){ echo "<span>제주도배송비</span>".number_format($sell[mb_15])."원"; }?>
				</p>
			</div>
		</div>
    </div>
    <!-- } 상세검색 항목 끝 -->
	<div id="sct_ct_1">&nbsp;</div>
    <!-- 검색된 분류 시작 { -->
    <div id="ssch_cate" style="position:relative;width:1180px;border-bottom: 1px solid #000000; ">
		<div style="position:relative;width:1178px;border:1px solid #c8c8c8;margin-bottom:30px;height:40px;overflow:hidden;">
		<div style="position:relative;width:195px;height:40px;float:left;border-right:1px solid #c8c8c8;text-align:center;line-height:40px;">
			<?  
				$title = sql_fetch("select ca_name from g5_shop_category where ca_id = '{$qcaid}'" );

				//if($title['ca_name']){
				//	echo '<a href="#" onclick="set_ca_id(\'\'); return false;" style="color:#01a3e4;">'.$title[ca_name].' <span>('.$total_count.')</span></a>'.PHP_EOL; 
				//}else{
					echo '<a href="#" onclick="set_ca_id(\'\'); return false;" style="color:#01a3e4;">전체분류 <span>('.$total_count2.')</span></a>'.PHP_EOL; 
				//}
			?>
		</div>
		<div style="position:relative;width:950px;height:40px;float:left;">
        <ul style="border:none;margin:0;">

        <?php
        $sql = " select b.ca_id, b.ca_name, count(*) as cnt $sql_common where 1 = 1 and (a.ca_id = b.ca_id and a.it_use = 1 and b.ca_use = 1) and (concat(a.it_10) = '{$q2}' )  and a.it_8 != '' group by left(b.ca_id,2) order by b.ca_id ";
        $result = sql_query($sql);
        $total_cnt = 0;
        for ($i=0; $row=sql_fetch_array($result); $i++) {
			 
			$title = sql_fetch("select ca_name from g5_shop_category where ca_id = '".substr($row[ca_id],0,2)."'" );
			if($qcaid == substr($row[ca_id],0,2) ){
				$aclass ="color: #01a3e4;";
			}else{
				$aclass ="";
			}
			echo "<li><a href=\"#\" onclick=\"set_ca_id('".substr($row[ca_id],0,2)."'); return false;\" style=\"{$aclass}\">{$title['ca_name']} (".$row['cnt'].")</a></li>\n";
			 
            $total_cnt += $row['cnt'];
        }
		//echo '<li><a href="#" onclick="set_ca_id(\'\'); return false;" >전체분류 <span>('.$total_cnt.')</span></a></li>'.PHP_EOL;;
        ?>
        </ul>
		</div>
		</div>
    </div>
    <!-- } 검색된 분류 끝 -->

    <!-- 검색결과 시작 { -->
    <div>
        <?php
        

		if($is_admin){
		//echo "select * $sql_common $sql_where3 {$order_by} ";
		}

		$default['de_search_img_width'] = 100;
		$default['de_search_img_height'] = 100;
        // 리스트 유형별로 출력
        $list_file = G5_SHOP_SKIN_PATH.'/list_seller.skin.php';

        if (file_exists($list_file)) {
            define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);
            $list = new item_list($list_file, $default['de_search_list_mod'], $default['de_search_list_row'], $default['de_search_img_width'], $default['de_search_img_height']);
            $list->set_query(" select * $sql_common $sql_where3 {$order_by}  ");
            $list->set_is_page(true);
            $list->set_view('it_img', true);
            $list->set_view('it_id', true);
            $list->set_view('it_name', true);
            $list->set_view('it_basic', true);
            $list->set_view('it_cust_price', false);
            $list->set_view('it_price', true);
            $list->set_view('it_icon', true);
            $list->set_view('sns', true);
			$list->set_cnt($total_count);
            echo $list->run();
        }
        else
        {
            $i = 0;
            $error = '<p class="sct_nofile">'.$list_file.' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</p>';
        }

        if ($i==0)
        {
            echo '<div>'.$error.'</div>';
        }

        $query_string = 'qname='.$qname.'&amp;qexplan='.$qexplan.'&amp;qid='.$qid;
        if($qfrom && $qto) $query_string .= '&amp;qfrom='.$qfrom.'&amp;qto='.$qto;
        $query_string .= '&amp;qcaid='.$qcaid.'&amp;q='.urlencode($q);
        $query_string .='&amp;qsort='.$qsort.'&amp;qorder='.$qorder;
		$query_string .='&amp;it_maker='.$it_maker.'&amp;s_select='.$s_select.'&amp;q2='.$q2;
        echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$query_string.'&amp;page=');
        ?>
    </div>
    <!-- } 검색결과 끝 -->

</div>
<!-- } 검색 끝 -->

<script>
function set_sort(qsort, qorder)
{
    var f = document.frmdetailsearch;
    f.qsort.value = qsort;
    f.qorder.value = qorder;
    f.submit();
}

function set_ca_id(qcaid)
{
    var f = document.frmdetailsearch;
    f.qcaid.value = qcaid;
    f.submit();
}
</script>

<?php
include_once('./_tail.php');
?>