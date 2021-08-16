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

function Right($string, $cnt){ 
/*****************************************************************
문자열의 오른쪽부터 정해진 수만큼의 문자를 반환한다.
*****************************************************************/
  $string = substr($string, (strlen($string) - $cnt), strlen($string));
  return $string;
}

function Left($string, $cnt){
/*****************************************************************
문자열의 왼쪽부터 정해진 수만큼의 문자를 반환한다.
*****************************************************************/
  return substr($string, 0, $cnt);
}


function DelLens($types,$str, $cnt) {

$rLen = (strlen($str) - $cnt);
If ($str != "") {
 for($ii=1;$ii<strlen($str);$ii++) {
  //$charat = Mid(str,$ii,1)
  If ($types == "Left") {
   $rcharat = Right($str,$rLen);
  }else if ($types == "Right") {
   $rcharat = Left($str,$rLen);
  }
 }
}
return $rcharat;
}


if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

 
 /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

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

    for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
        $total_count++;
		 $j = 1;

		$it_id              = trim(addslashes(only_number($data->sheets[0]['cells'][$i][$j++])));
		$it_8              = trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$mb_nick			= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_name			= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_maker			= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_5				= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_6				= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_9				= trim(addslashes($data->sheets[0]['cells'][$i][$j++]));
		$it_price			= trim(addslashes(only_number($data->sheets[0]['cells'][$i][$j++])));
		$it_stock_qty		= trim(addslashes(only_number($data->sheets[0]['cells'][$i][$j++])));
		$it_use				= trim(addslashes(only_number($data->sheets[0]['cells'][$i][$j++])));
		$it_soldout				= trim(addslashes(only_number($data->sheets[0]['cells'][$i][$j++])));

		
		$it_9 = DelLens("Right",$it_9, "1");

        if(!$it_id) {
            $fail_count++;
		
            continue;
        }
		
		$sql = "update {$g5['g5_shop_item_table']} set it_9 = '$it_9' , it_price = '$it_price' , it_stock_qty = '$it_stock_qty' , it_use = '$it_use' , it_soldout = '$it_soldout'  where  it_id = '$it_id' ";

        //echo $sql;

        sql_query($sql);

        $succ_count++;
    }
}

$g5['title'] = '상품 엑셀일괄수정 결과';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>상품등록을 수정했습니다.</p>
    </div>

    <dl id="excelfile_result">
        <dt>총상품수</dt>
        <dd><?php echo number_format($total_count); ?></dd>
        <dt>완료건수</dt>
        <dd><?php echo number_format($succ_count); ?></dd>
        <dt>실패건수</dt>
        <dd><?php echo number_format($fail_count); ?></dd>
        <?php if($fail_count > 0) { ?>
        <dt>실패상품코드</dt>
        <dd><?php echo implode(', ', $fail_it_id); ?></dd>
        <?php } ?>
      
    </dl>

    <div class="btn_win01 btn_win">
        <button type="button" onclick="window.close();">창닫기</button>
    </div>

</div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>