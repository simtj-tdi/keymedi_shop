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

    $dup_mb_id = array();
    $dup_mb_id1 = array();
    $fail_mb_id = array();
	$succ_mb_id = array();
    $dup_count = 0;
    $dup_count1 = 0;
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0; 
	
    for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {		
        $total_count++;

        $j = 1;

        $mb_id              = addslashes($data->sheets[0]['cells'][$i][$j++]);
        $po_content         = addslashes($data->sheets[0]['cells'][$i][$j++]);
        $po_point           = addslashes(only_number($data->sheets[0]['cells'][$i][$j++]));
        $po_expire_term     = addslashes(only_number($data->sheets[0]['cells'][$i][$j++]));
       
        if(!$mb_id || !$po_content || !$po_point) {
            $fail_count++;
		
            continue;
        }

		// mb_id 유효성 체크 - 있는 아이디
        $sql2 = " select count(*) as cnt from {$g5['member_table']} where mb_id = '$mb_id' ";
        $row2 = sql_fetch($sql2);
        if($row2['cnt']) {
            $succ_mb_id[] = $mb_id;
            $dup_mb_id1[] = $mb_id;
            $dup_count1++;
            $succ_count++;
			
            continue;
        }

		// mb_id 유효성 체크 - 없는 아이디
        $sql2 = " select count(*) as cnt from {$g5['member_table']} where mb_id = '$mb_id' ";
        $row2 = sql_fetch($sql2);
        if(!$row2['cnt']) {
            $fail_mb_id[] = $mb_id;
            $dup_mb_id[] = $mb_id;
            $dup_count++;
            $fail_count++;
			
            continue;
        }
		 
 
 

		/*
        $sql = " INSERT INTO {$g5['g5_shop_item_table']}
                     SET it_id = '$it_id',
                         ca_id = '$ca_id',
                         ca_id2 = '$ca_id2',
                         ca_id3 = '$ca_id3',
                        
						 ";
        sql_query($sql);
		*/
        $succ_count++;
    }
}

$g5['title'] = '포인트 일괄지급';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>
	<form name="frm" action="./pointexcel_update2.php" enctype="MULTIPART/FORM-DATA" autocomplete="off" method="post" >

    <div class="local_desc01 local_desc">
        <p>엑셀 업로드 내역 확인</p>
		<table>
			<thead>
				<tr>
					<th>회원아이디</th>
					<th>포인트내용</th>
					<th>포인트</th>
					<th>유효기간</th>
				</tr>
			</thead>
			<tbody>
				<?php
					for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {		

					$j = 1;

					$mb_id              = addslashes($data->sheets[0]['cells'][$i][$j++]);
					$po_content         = addslashes($data->sheets[0]['cells'][$i][$j++]);
					$po_point           = addslashes(only_number($data->sheets[0]['cells'][$i][$j++]));
					$po_expire_term     = addslashes(only_number($data->sheets[0]['cells'][$i][$j++]));

					$sql2 = " select count(*) as cnt from {$g5['member_table']} where mb_id = '$mb_id' ";
					$row2 = sql_fetch($sql2);
					if($row2['cnt']) {
					
				?>
				<input type="hidden" name="chk[]" value="<?=$mb_id?>,<?=$po_content?>,<?=$po_point?>,<?=$po_expire_term?>" />
				<? } ?>
				<tr>
					<td><?=$mb_id?></td>
					<td><?=$po_content?></td>
					<td><?=$po_point?></td>
					<td><?=$po_expire_term?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
    </div>

    <dl id="excelfile_result">
        <dt>총 입력 수</dt>
        <dd><?php echo number_format($total_count); ?></dd>
        <!-- <dt>아이디 검사 성공</dt>
		<dd><?php echo number_format($succ_count); ?></dd> -->		
		<!-- <?php if($succ_count > 0) { ?>
        <dt>존재하는 아이디</dt>        
        <?php } ?> -->
        <!-- <dd><?php echo implode(', ', $succ_mb_id); ?></dd> -->
		<?php if($fail_count > 0) { ?>
        <dt style="color:red">아이디 검사 실패</dt>
        <dd style="color:red"><?php echo number_format($fail_count); ?></dd>
        <dt style="color:red">존재하지 않는 아이디</dt>
        <dd style="color:red"><?php echo implode(', ', $fail_mb_id); ?></dd>
        <?php } ?>       
    </dl>

    <div class="btn_win01 btn_win">
		<?php if(!$fail_count) { ?>
        <button type="submit" onclick="frm">지　급</button>
		<?php } ?>
        <button type="button" onclick="window.close();">닫　기</button>
    </div>

</div>
</form>
<?php
include_once(G5_PATH.'/tail.sub.php');
?>