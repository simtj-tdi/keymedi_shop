<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/event.php');
    return;
}

//$sql = "select it_8 from g5_shop_item where it_type5 = '1' and it_use = '1'  group by it_8  ";

if ($com_id){
    $where11 .= ", (select it_id from g5_shop_item a where a.it_8 = g5_shop_item.it_id  and it_use = '1' and it_10 = '$com_id' ) as it_ids   ";
	$where22 .= " where it_ids != '' ";
}

if ($ca_id)
    $where .= " and ca_id like '$ca_id%' ";

//echo " select it_id from g5_shop_item where  it_8 = '' and it_type5 = '1' and it_use = '1' " . $where ." group by it_id ";
//echo " select * from ( select it_id {$where11 } from g5_shop_item where  it_8 = '' and it_type5 = '1' and it_use = '1' " . $where ." group by it_id ) m {$where22} ";

//$sql_to = sql_query(" select it_id from g5_shop_item where it_8 = '' and it_type5 = '1' and it_use = '1' " . $where ." group by it_8 ") ;
$sql_to = sql_query(" select * from ( select it_id {$where11 } from g5_shop_item where  it_8 = '' and it_type5 = '1' and it_use = '1' " . $where ." group by it_id ) m {$where22} ") ;
$it_id_ar = "";


while($rwd = sql_fetch_array($sql_to)){
	if($it_id_ar == ""){
		$it_id_ar = $rwd['it_id'];
	}else{
		$it_id_ar = $it_id_ar.",".$rwd['it_id'];
	}
}
//echo $it_id_ar;
$sql_where = " where it_id in ( {$it_id_ar} ) ";

// 총몇개 = 한줄에 몇개 * 몇줄
$default['de_search_list_mod'] = 5;

$items = $default['de_search_list_mod'] * $default['de_search_list_row'];
// 페이지가 없으면 첫 페이지 (1 페이지)
if ($page < 1) $page = 1;
// 시작 레코드 구함
$from_record = ($page - 1) * $items;


$sql = " select COUNT(*) as cnt from g5_shop_item $sql_where group by it_id";
$row = sql_fetch($sql);


//$total_count = $row['cnt'];
$total_count = sql_num_rows(sql_query($sql));
$total_page  = ceil($total_count / $items); // 전체 페이지 계산


$sql = " select * from {$g5['g5_shop_event_table']}
          where ev_where = 'shop_skin'
    
            and ev_use = 1  order by ev_id desc limit 1"  ;
$ev = sql_fetch($sql);



if (!$ev['ev_id']){
    alert('등록된 이벤트가 없습니다.');
}else{
	$ev_id = $ev['ev_id'];
}
$g5['title'] = $ev['ev_subject'];
include_once('./_head.php');

if ($is_admin)
   // echo '<div class="sev_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/itemeventform.php?w=u&amp;ev_id='.$ev['ev_id'].'" class="btn_admin">이벤트 관리</a></div>';
?>

<script>
var itemlist_ca_id = "<?php echo $ev_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>
<div id="sub_top_new_menu_title">
	<h2>특가상품</h2>
</div>

<?
//if($_SERVER['REMOTE_ADDR']=="116.124.131.177"){

 // 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) <= '2' ";

$sql .= " order by ca_id , ca_order";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'" '.get_selected($ca_id, $row['ca_id']).'>'.$nbsp.$row['ca_name'].' </option>'.PHP_EOL;
}

?>

<div id="new_search_frm" style="border-top:2px solid #4d4d4d;margin-top:25px;">
	<form name="s_frm" action="" method="get">
		<p>
			공급사&nbsp;&nbsp;&nbsp;
			<select name="com_id" class="new_input4">
				<option value="" <?php echo get_selected($com_id, ''); ?>>전체</option>
			<?
				$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4' order by mb_nick asc";
				$mres = sql_query($msql);
				while($mrow = sql_fetch_array($mres)){
			?>
				 <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($com_id, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
			<? } ?>
			</select>
			&nbsp;&nbsp;&nbsp;카테고리&nbsp;&nbsp;&nbsp;
			<select name="ca_id" class="new_input4">
			<?=$ca_list?>
			</select>
			<input type="image" src="/img/board/serch_btn.png" alt=""  class="new_input3" >
			<a href="/shop/event.php"><img src="/img/board/reset_btn.png" alt="" ></a>
		</p>
	</form>
</div>
<? //} ?>


<!-- 이벤트 시작 { -->
<?php
$himg = G5_DATA_PATH.'/event/'.$ev_id.'_h';
if (file_exists($himg))
    echo '<div id="sev_himg" class="sev_img"><img src="'.G5_DATA_URL.'/event/'.$ev_id.'_h" alt=""></div>';

// 상단 HTML
echo '<div id="sev_hhtml">'.conv_content($ev['ev_head_html'], 1).'</div>';

// 상품 출력순서가 있다면
if ($sort != "")
    $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
else
    //$order_by = 'b.it_order, b.it_id desc';
	$order_by = 'it_update_time desc';

if ($skin) {
    $skin = preg_replace('#\.+/#', '', $skin);
    $ev['ev_skin'] = $skin;
}

define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);

// 리스트 유형별로 출력
$list_file = G5_SHOP_SKIN_PATH."/{$ev['ev_skin']}";
if (file_exists($list_file))
{
	$evs = "1";
	//include G5_SHOP_SKIN_PATH.'/list.sort.skin.php';

    // 상품 보기 타입 변경 버튼
    //include G5_SHOP_SKIN_PATH.'/list.sub.skin.php';

    // 총몇개 = 한줄에 몇개 * 몇줄
    $items = $ev['ev_list_mod'] * $ev['ev_list_row'];
    // 페이지가 없으면 첫 페이지 (1 페이지)
    if ($page < 1) $page = 1;
    // 시작 레코드 구함
    $from_record = ($page - 1) * $items;




    $list = new item_list(G5_SHOP_SKIN_PATH.'/'.$ev['ev_skin'], $ev['ev_list_mod'], $ev['ev_list_row'], $ev['ev_img_width'], $ev['ev_img_height']);

	/*
    $list->set_event($ev['ev_id']);

	$list->set_category($ca_id, 1);
	$list->set_com_id($com_id);


    $list->set_is_page(true);
    $list->set_order_by($order_by);
    $list->set_from_record($from_record);
    $list->set_view('it_img', true);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_cust_price', false);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', false);
    $list->set_view('sns', true);
	echo $list->run();
    */
	//echo " select * from shop.g5_shop_item $sql_where order by {$order_by} limit $from_record, $items  ";

	$list->set_query(" select * from g5_shop_item $sql_where order by {$order_by}  limit $from_record, $items ");
	$list->set_is_page(true);
	$list->set_view('it_img', true);
	$list->set_view('it_id', false);
	$list->set_view('it_name', true);
	$list->set_view('it_basic', false);
	$list->set_view('it_cust_price', false);
	$list->set_view('it_price', true);
	$list->set_view('it_icon', false);
	$list->set_view('sns', true);
	$list->set_cnt($total_count);
	echo $list->run();
    // where 된 전체 상품수
    //$total_count = $list->total_count;
    // 전체 페이지 계산
    $total_page  = ceil($total_count / $items);


}
else
{
    echo '<div align="center">'.$ev['ev_skin'].' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
}
?>

<?php
$qstr .= 'skin='.$skin.'&amp;ev_id='.$ev_id.'&amp;sort='.$sort.'&amp;sortodr='.$sortodr;
echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");
?>

<?php
// 하단 HTML
echo '<div id="sev_thtml">'.conv_content($ev['ev_tail_html'], 1).'</div>';

$timg = G5_DATA_PATH.'/event/'.$ev_id.'_t';
if (file_exists($timg))
    echo '<div id="sev_timg" class="sev_img"><img src="'.G5_DATA_URL.'/event/'.$ev_id.'_t" alt=""></div>';
?>
<!-- } 이벤트 끝 -->

<?php
include_once('./_tail.php');
?>
