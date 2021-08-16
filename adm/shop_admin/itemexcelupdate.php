<?php
$sub_menu = '400300';
include_once('./_common.php');

// 상품이 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

auth_check($auth[$sub_menu], "w");

require_once G5_LIB_PATH.'/PHPExcel.php';
$objPHPExcel = new PHPExcel();
require_once G5_LIB_PATH.'/PHPExcel/IOFactory.php';
$filename =$_FILES['excelfile']['tmp_name'];

function only_number($n)
{
    return preg_replace('/[^0-9]/', '', $n);
}


try {
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
    $objReader -> setReadDataOnly(true);
    $objExcel = $objReader->load($filename);
    $objExcel->setActiveSheetIndex(0);

    $objWorkSheet = $objExcel->getActiveSheet();
    $rowIterrator = $objWorkSheet->getRowIterator();

    foreach($rowIterrator as $row){
        $cellIterator = $row->getCellIterator();
        $cellIterator -> setIterateOnlyExistingCells(false);
    }

    $maxRow = $objWorkSheet->getHighestDataRow();

    $dup_it_id = array();
    $fail_it_id = array();
    $dup_count = 0;
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

    for($i=3;$i<=$maxRow;$i++){

        $total_count++;

        $it_id          = addslashes($objWorkSheet->getCell('A'.$i)->getValue()); //상품코드
        $ca_id          = addslashes($objWorkSheet->getCell('B'.$i)->getValue()); //기본분류
        $ca_id2         = addslashes($objWorkSheet->getCell('C'.$i)->getValue()); //분류2
        $ca_id3         = addslashes($objWorkSheet->getCell('D'.$i)->getValue()); //분류3
        $it_name        = addslashes($objWorkSheet->getCell('E'.$i)->getValue()); //상품명
        $it_maker       = addslashes($objWorkSheet->getCell('F'.$i)->getValue()); //제조사
        $it_origin      = addslashes($objWorkSheet->getCell('G'.$i)->getValue()); //원산지
        $it_brand       = addslashes($objWorkSheet->getCell('H'.$i)->getValue()); //브랜드
        $it_model       = addslashes($objWorkSheet->getCell('I'.$i)->getValue()); //모델
        $it_basic       = addslashes($objWorkSheet->getCell('J'.$i)->getValue()); //기본설명
        $it_explan      = addslashes($objWorkSheet->getCell('K'.$i)->getValue()); //상품설명
        $it_cust_price  = addslashes(only_number($objWorkSheet->getCell('L'.$i)->getValue())); //시중가격
        $it_price       = addslashes(only_number($objWorkSheet->getCell('M'.$i)->getValue())); //판매가격
        $it_use         = addslashes($objWorkSheet->getCell('N'.$i)->getValue()); //판매가능
        $it_stock_qty   = addslashes(only_number($objWorkSheet->getCell('O'.$i)->getValue())); //재고수량
        $it_noti_qty    = addslashes(only_number($objWorkSheet->getCell('P'.$i)->getValue())); //재고통보수량
        $it_buy_min_qty = addslashes(only_number($objWorkSheet->getCell('Q'.$i)->getValue())); //최소구매수량
        $it_buy_max_qty = addslashes(only_number($objWorkSheet->getCell('R'.$i)->getValue())); //최대구매수량
        $it_notax       = addslashes(only_number($objWorkSheet->getCell('S'.$i)->getValue())); //과세유형
        $it_1           = addslashes($objWorkSheet->getCell('T'.$i)->getValue()); //보험코드
        $it_2           = addslashes($objWorkSheet->getCell('U'.$i)->getValue()); //품목기준코드
        $it_3           = addslashes($objWorkSheet->getCell('V'.$i)->getValue()); //효능/효과
        $it_4           = addslashes($objWorkSheet->getCell('W'.$i)->getValue()); //주요성분
        $it_5           = addslashes($objWorkSheet->getCell('X'.$i)->getValue()); //규격
        $it_6           = addslashes($objWorkSheet->getCell('Y'.$i)->getValue()); //단위
        $it_8           = addslashes($objWorkSheet->getCell('Z'.$i)->getValue()); //부모마스터코드
        $it_9           = addslashes($objWorkSheet->getCell('AA'.$i)->getValue()); //제약사코드
        $it_10          = addslashes($objWorkSheet->getCell('AB'.$i)->getValue()); //아이디
        $it_order       = addslashes(only_number($objWorkSheet->getCell('AC'.$i)->getValue())); //정렬순서
        $it_img1        = addslashes($objWorkSheet->getCell('AD'.$i)->getValue()); //이미지1
        $it_img2        = addslashes($objWorkSheet->getCell('AE'.$i)->getValue()); //이미지2
        $it_img3        = addslashes($objWorkSheet->getCell('AF'.$i)->getValue()); //이미지3
        $it_img4        = addslashes($objWorkSheet->getCell('AG'.$i)->getValue()); //이미지4
        $it_img5        = addslashes($objWorkSheet->getCell('AH'.$i)->getValue()); //이미지5
        $it_img6        = addslashes($objWorkSheet->getCell('AI'.$i)->getValue()); //이미지6
        $it_img7        = addslashes($objWorkSheet->getCell('AJ'.$i)->getValue()); //이미지7
        $it_img8        = addslashes($objWorkSheet->getCell('AK'.$i)->getValue()); //이미지8
        $it_img9        = addslashes($objWorkSheet->getCell('AL'.$i)->getValue()); //이미지9
        $it_img10       = addslashes($objWorkSheet->getCell('AM'.$i)->getValue()); //이미지10

        $it_explan2         = strip_tags(trim($it_explan));

        if(!$it_id || !$ca_id || !$it_name) {
            $fail_count++;

            continue;
        }

        // it_id 중복체크
        $sql2 = " select count(*) as cnt from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
        $row2 = sql_fetch($sql2);
        if($row2['cnt']) {
            $fail_it_id[] = $it_id;
            $dup_it_id[] = $it_id;
            $dup_count++;
            $fail_count++;

            continue;
        }

        // 기본분류체크
        $sql2 = " select count(*) as cnt from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
        $row2 = sql_fetch($sql2);
        if(!$row2['cnt']) {
            $fail_it_id[] = $it_id;
            $fail_count++;

            continue;
        }

        $sql = "INSERT INTO {$g5['g5_shop_item_table']}
                SET it_id = '$it_id',
                    ca_id = '$ca_id',
                    ca_id2 = '$ca_id2',
                    ca_id3 = '$ca_id3',
                    it_name = '$it_name',
                    it_maker = '$it_maker',
                    it_origin = '$it_origin',
                    it_brand = '$it_brand',
                    it_model = '$it_model',
                    it_basic = '$it_basic',
                    it_explan = '$it_explan',
                    it_explan2 = '$it_explan2',
                    it_cust_price = '$it_cust_price',
                    it_price = '$it_price',
                    it_stock_qty = '$it_stock_qty',
                    it_noti_qty = '$it_noti_qty',
                    it_buy_min_qty = '$it_buy_min_qty',
                    it_buy_max_qty = '$it_buy_max_qty',
                    it_notax = '$it_notax',
                    it_use = '$it_use',
                    it_time = '".G5_TIME_YMDHIS."',
                    it_ip = '{$_SERVER['REMOTE_ADDR']}',
                    it_order = '$it_order',
                    it_img1 = '$it_img1',
                    it_img2 = '$it_img2',
                    it_img3 = '$it_img3',
                    it_img4 = '$it_img4',
                    it_img5 = '$it_img5',
                    it_img6 = '$it_img6',
                    it_img7 = '$it_img7',
                    it_img8 = '$it_img8',
                    it_img9 = '$it_img9',
                    it_img10 = '$it_img10',
					it_1	=	'$it_1',
					it_2	=	'$it_2',
					it_3	=	'$it_3',
					it_4	=	'$it_4',
					it_5	=	'$it_5',
					it_6	=	'$it_6',
					it_8	=	'$it_8',
					it_9	=	'$it_9',
					it_10	=	'$it_10'
					 ";
        
        sql_query($sql);

        $succ_count++;

    }
}
catch(exception $e){
    echo '엑셀파일을 읽는 도중 오류가 발생했습니다.';
}


$g5['title'] = '상품 엑셀일괄등록 결과';
include_once(G5_PATH.'/head.sub.php');
?>

    <div class="new_win">
        <h1><?php echo $g5['title']; ?></h1>

        <div class="local_desc01 local_desc">
            <p>상품등록을 완료했습니다.</p>
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
            <?php if($dup_count > 0) { ?>
                <dt>상품코드중복건수</dt>
                <dd><?php echo number_format($dup_count); ?></dd>
                <dt>중복상품코드</dt>
                <dd><?php echo implode(', ', $dup_it_id); ?></dd>
            <?php } ?>
        </dl>

        <div class="btn_win01 btn_win">
            <button type="button" onclick="window.close();">창닫기</button>
        </div>

    </div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>