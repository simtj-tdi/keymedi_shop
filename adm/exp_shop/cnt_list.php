<?php
$sub_menu = '600300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

//shop_juny
if(!$site_code){
	$site_fix = "shop";
}else{
	$site_fix = $site_code;
}

$g5['title'] = '재고수량 및 판매금관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$site_fix}.{$g5['g5_shop_category_table']} ";
//if ($is_admin != 'super')
//    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
//$sql .= " order by ca_order, ca_id ";
$sql .= " order by ca_id , ca_order";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].' ('.$row['ca_id'].')</option>'.PHP_EOL;
}

$where = " and ";
$sql_search = "";
/*
if ($stx != "") {
    if($sfl == "it_8" || $sfl == "it_9") {
		 $sql_search .= " $where $sfl = '$stx' ";
        $where = " and ";
	}else if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}*/
if ($stx != "") {
    if($sfl == "it_8" || $sfl == "it_9") {
		//$sql_search .= " $where (select $sfl from g5_shop_item where it_id = a.it_8)  = '$stx' ";
		$sql_search .= " $where $sfl = '$stx' ";
        $where = " and ";
	}else if ($sfl != "") {
        $sql_search .= " $where (select $sfl from {$site_fix}.g5_shop_item where it_id = a.it_8) like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
} 

if ($sca != "") {
   // $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%' or a.ca_id3 like '$sca%') ";
   $sql_search .= " $where ((select ca_id from {$site_fix}.g5_shop_item where it_id = a.it_8) like '$sca%' or (select ca_id2 from {$site_fix}.g5_shop_item where it_id = a.it_8) like '$sca%' or (select ca_id3 from {$site_fix}.g5_shop_item where it_id = a.it_8) like '$sca%') ";
   
}


if($mb_s_date){
	 $sql_search .= " and  '{$mb_s_date}' <= left(it_time,10)   ";
}
if($mb_e_date){
	 $sql_search .= " and  left(it_time,10) <= '{$mb_e_date}' ";
}


if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$site_fix}.{$g5['g5_shop_item_table']} a ,
                     {$site_fix}.{$g5['g5_shop_category_table']} b
               where (a.ca_id = b.ca_id";
if($is_admin){
	$sql_common .= " and a.it_8 != '' ";
}else{
	$sql_common .= " and a.it_8 != '' ";
	$sql_common .= " and a.it_10 = '{$member['mb_id']}'";
}



if($m_class){
	$sql_common .= " and a.it_10 = '{$m_class}'";
}

$sql_common .= ") ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    $sst  = "it_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select * 
			, (select ca_id from {$site_fix}.g5_shop_item where it_id = a.it_8) as mca_id 
			, (select ca_id2 from {$site_fix}.g5_shop_item where it_id = a.it_8) as mca_id2
			, (select ca_id3 from {$site_fix}.g5_shop_item where it_id = a.it_8) as mca_id3
           $sql_common
           $sql_order
           limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;
//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;m_class='.$m_class.'&amp;page='.$page.'&amp;save_stx='.$stx;
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;sca1='.$sca1.'&amp;sca2='.$sca2.'&amp;sca3='.$sca3.'&amp;m_class='.$m_class.'&amp;page='.$page.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    등록된 상품 <?php echo $total_count; ?>건
</div>

<form name="flist" class="local_sch02 local_sch">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
 
<p>
<label for="sca">분류선택 : </label>
<input type="hidden" name="sca" id="sca" value="<?=$sca?>">
 
<select name="sca1" id="sca1" onchange="ajax_cate(this.value,2,'');">
<option value="">1차분류</option>
    <?php
    $sql1 = " select ca_id, ca_name from {$site_fix}.{$g5['g5_shop_category_table']} where LENGTH(ca_id) = '2'  order by ca_id , ca_order ";
    $result1 = sql_query($sql1);
    for ($i=0; $row1=sql_fetch_array($result1); $i++) { 
        echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca1, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
    }
    ?>
</select>
<select name="sca2" id="sca2" onchange="ajax_cate(this.value,3,'');">

</select>
<select name="sca3" id="sca3" onchange="ajax_cate(this.value,4,'');">

</select>
</p>
<script>
function ajax_cate(val,dep,dep2){
	document.getElementById("sca").value = val;
	var g5_url       = "<?php echo G5_URL ?>";
	var save_result = ""; 
	if(dep < 4){
		$.ajax({
			type: "POST",
			data: {
					"site_fix":"<?=$site_fix?>",
					"val":val,
					"dep":dep,
					"ck":dep2 
				},
			url: g5_url+"/adm/exp_shop/ajax.cate.php",
			cache: false,
			async: false,
			success: function(data) {
				save_result = data;
			}
		}); 
		 
		if(save_result) {
			 $("#sca"+dep).html(save_result);
 		} 
	}
}
<? if($sca1){ ?>
ajax_cate(<?=$sca1?>,"2",<?=$sca2?>);
<? } ?>
<? if($sca2){ ?>
ajax_cate(<?=$sca2?>,"3",<?=$sca3?>);
<? } ?> 
</script>
<p>
<!-- mb_datetime  -->등록기간 : <input type="text" name="mb_s_date" id="mb_s_date" value="<?=$mb_s_date?>" class="frm_input"/> ~ <input type="text" name="mb_e_date" id="mb_e_date" value="<?=$mb_e_date?>" class="frm_input"/>

<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
<button type="button" onclick="javascript:set_date('어제');">어제</button>
<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
<button type="button" onclick="javascript:set_date('전체');">전체</button>

</p>
<? if($is_admin) { ?>
<p>
업체명 : 
<select name="m_class" id="m_class">
	<option value="" <?php echo get_selected($m_class, ''); ?>>전체</option>
	<option value="admin" <?php echo get_selected($m_class, 'admin'); ?>>ADMIN</option>
<?
	$msql = "select mb_id , mb_nick from {$g5['member_table']} where mb_v = '4'";
	$mres = sql_query($msql);
	while($mrow = sql_fetch_array($mres)){

?>
	 <option value="<?=$mrow[mb_id]?>" <?php echo get_selected($m_class, $mrow[mb_id]); ?>><?=$mrow[mb_nick]?></option>
<? } ?> 
</select>
</p>
<? } ?>
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
	<option value="it_8" <?php echo get_selected($sfl, 'it_8'); ?>>마스터코드</option>
    <option value="it_9" <?php echo get_selected($sfl, 'it_9'); ?>>업체코드</option>
    <option value="it_maker" <?php echo get_selected($sfl, 'it_maker'); ?>>제조사</option>
    <option value="it_origin" <?php echo get_selected($sfl, 'it_origin'); ?>>원산지</option>
    <option value="it_sell_email" <?php echo get_selected($sfl, 'it_sell_email'); ?>>판매자 e-mail</option>
</select>


<label for="stx" class="sound_only">검색어</label>
<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="list_name" value="list2">
<input type="hidden" name="m_class" value="<?php echo $m_class; ?>">
<input type="hidden" name="site_code"  value="<?=$site_fix?>">

<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr> 
		<th scope="col" width="10%">업체명</th>
		
        <th scope="col" width="5%"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>마스터코드</a></th>
		<?if($is_admin){?>
		<th scope="col" width="5%">셀러상품코드</th>
		<? } ?>
		<th scope="col" width="5%">업체코드</th>
		<th scope="col"  id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품명</a></th>	
		
		<th scope="col" width="10%">규격</th>
		<th scope="col" width="5%">단위</th>
		<th scope="col" width="14%">제조사</th>
		<th scope="col" width="5%">구분</th>
		<th scope="col" width="5%"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>재고총계</a></th>

		<th scope="col" width="5%"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>산부및메디몰</a></th>
		<th scope="col" width="5%"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>피부비만</a></th>

        <th scope="col" width="3%">관리</th>
    </tr>
    
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    { 
		 
		$rows1 = sql_fetch("select it_id , it_price , it_stock_qty from shop_skin.{$g5['g5_shop_item_table']} where it_9 = '{$row[it_9]}' and it_8 = '$row[it_8]' ");


		$rows = sql_fetch("select it_name,it_2,it_5, it_6, it_maker,it_cust_price from shop.{$g5['g5_shop_item_table']} where it_id = '{$row[it_8]}'");
        $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $bg = 'bg'.($i%2);

        $it_point = $row['it_point'];
        if($row['it_point_type'])
            $it_point .= '%';
    ?>
    <tr class="<?php echo $bg; ?>">  
		<td style="width:100px;text-align:center;" rowspan="2">
			<?
			$msql = "select mb_nick from {$g5['member_table']} where mb_id = '{$row[it_10]}'";
			$mem = sql_fetch($msql);
			echo $mem[mb_nick];
		?> 
		</td>
		<?if($is_admin){?>
		<td rowspan="2" style="text-align:center;"><?php echo $row['it_8']; ?></td>
		<?}?>
		<td rowspan="2" style="text-align:center;"><?php echo $row['it_id']; ?></td>
		<td rowspan="2" style="text-align:center;"><?php echo $row['it_9']; ?></td>
		<td rowspan="2" style="text-align:center;"><?php echo htmlspecialchars2(cut_str($rows['it_name'],250, "")); ?></td>

		<td rowspan="2" style="text-align:center;"><?php echo $rows['it_5']; ?></td>
		<td rowspan="2" style="text-align:center;"><?php echo $rows['it_6']; ?></td>
		<td rowspan="2" style="text-align:center;"><?php echo $rows['it_maker']; ?></td>
		<td style="text-align:center;">재고</td>
		<td style="text-align:center;"><?=($row[it_stock_qty]+$rows1[it_stock_qty])?></td>
		<td style="width:150px;text-align:center;"><input type="text" name="shop_cnt" id="shop_cnt_<?=$i?>"   class="frm_input " value="<?=$row[it_stock_qty]?>" ></td>
		<td style="width:150px;text-align:center;"><input type="text" name="shop_skin_cnt" id="shop_skin_cnt_<?=$i?>"  class="frm_input "  value="<?=$rows1[it_stock_qty]?>" ></td>
		<td style="width:100px;text-align:center;"  rowspan="2"><a href="#" onclick="chang_tmp('<?=$i?>','<?=$row[it_id]?>','<?=$rows1[it_id]?>');return false;" class="btn btn-primary btn-xs">수정</a></td>
   </tr>
   </tr>
		<td style="text-align:center;">가격</td>
		<td style="text-align:center;"></td>
		<td style="width:150px;text-align:center;"><input type="text" name="shop_price"  id="shop_price_<?=$i?>"  class="frm_input " value="<?=$row[it_price]?>" ></td>
		<td style="width:150px;text-align:center;"><input type="text" name="shop_skin_price" id="shop_skin_price_<?=$i?>"  class="frm_input "  value="<?=$rows1[it_price]?>" ></td>
		
    </tr> 
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
    <?php } ?>
</div>
<!-- <div class="btn_confirm01 btn_confirm">
    <input type="submit" value="일괄수정" class="btn_submit" accesskey="s">
</div> -->
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
function fitemlist_submit(f)
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

$(function() {
    $(".itemcopy").click(function() {
        var href = $(this).attr("href");
        window.open(href, "copywin", "left=100, top=100, width=300, height=200, scrollbars=0");
        return false;
    });
});

function excelform(url)
{
    var opt = "width=600,height=450,left=10,top=10";
    window.open(url, "win_excel", opt);
    return false;
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
function chang_tmp(num,it_id,it_id2){

	var g5_url       = "<?php echo G5_URL ?>";
	var save_result = ""; 
	 if(confirm("선택한 자료를 정말 수정하시겠습니까?")) {
		$.ajax({
			type: "POST",
			data: {
					"it_id":it_id,
					"it_id2":it_id2,
					"shop_cnt":document.getElementById("shop_cnt_"+num).value,
					"shop_skin_cnt":document.getElementById("shop_skin_cnt_"+num).value,
					"shop_price":document.getElementById("shop_price_"+num).value,
					"shop_skin_price":document.getElementById("shop_skin_price_"+num).value
				},
			url: g5_url+"/adm/exp_shop/cnt_list_up.php",
			cache: false,
			async: false,
			success: function(data) {
				save_result = data;
			}
		}); 
		 
		if(save_result) {
			alert(save_result);
			document.location.reload();
		} 
	}
}
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
