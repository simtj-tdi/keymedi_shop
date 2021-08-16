<?php
$sub_menu = '400300';
include_once('./_common.php');

// 상품이 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

auth_check($auth[$sub_menu], "w");

function only_number($n)
{
    return preg_replace('/[^0-9]/', '', $n);
}

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

 
 

    $data->read($file);

    /*


     $data->sheets[0]['numRows'] - count rows
     $data->sheets[0]['numCols'] - count columns
     $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

     $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */
 

    error_reporting(E_ALL ^ E_NOTICE);

    $dup_it_id = array();
    $fail_it_id = array();
    $dup_count = 0;
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

    for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {
        $total_count++;
		 $j = 1;

		$cp_subject              = addslashes($data->sheets[0]['cells'][$i][$j++]);
		$cp_method			= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$cp_target			= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_id			= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$cp_start				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$cp_end				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$cp_price				= addslashes(only_number($data->sheets[0]['cells'][$i][$j++]));
		$cp_minimum			= addslashes(only_number($data->sheets[0]['cells'][$i][$j++])); 
		 
		$cp_start = explode("/",$cp_start); 
		$cp_start = $cp_start[2]."-".$cp_start[1]."-".$cp_start[0];
		$cp_start = date("Y-m-d", strtotime(date($cp_start)."-1 days"));  

		$cp_end = explode("/",$cp_end); 
		$cp_end = $cp_end[2]."-".$cp_end[1]."-".$cp_end[0];
		$cp_end = date("Y-m-d", strtotime(date($cp_end)."-1 days"));  

        if(!$cp_subject) {
            $fail_count++; 
            continue;
        }
		
		if($mb_id=="1") {
			$mb_id = '전체회원';
		}else if($mb_id=="2") {
			$mb_id = '산부인과 협동조합';
		}else if($mb_id=="3") {
			$mb_id = '메디포털';
		} 

		$j = 0;
		do {
			$cp_id = get_coupon_id();

			$sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
			$row3 = sql_fetch($sql3);

			if(!$row3['cnt'])
				break;
			else {
				if($j > 20)
					die('Coupon ID Error');
			}
		} while(1);

		$sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
					( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime )
				VALUES
					( '$cp_id', '$cp_subject', '$cp_method', '$cp_target', '$mb_id', '$cp_start', '$cp_end', '0', '$cp_price', '1', '$cp_minimum', '0', '".G5_TIME_YMDHIS."' ) ";

		sql_query($sql);

        //echo $sql; 

        $succ_count++;
    }
}

$g5['title'] = '쿠폰 엑셀일괄등록 결과';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>쿠폰을 등록했습니다.</p>
    </div>

    <dl id="excelfile_result">
        <dt>총수</dt>
        <dd><?php echo number_format($total_count); ?></dd>
        <dt>완료건수</dt>
        <dd><?php echo number_format($succ_count); ?></dd>
        <dt>실패건수</dt>
        <dd><?php echo number_format($fail_count); ?></dd> 
      
    </dl>

    <div class="btn_win01 btn_win">
        <button type="button" onclick="window.close();">창닫기</button>
    </div>

</div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>