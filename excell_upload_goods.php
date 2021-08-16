<?php
include_once('./_common.php');

function only_number($n)
{
    return preg_replace('/[^0-9]/', '', $n);
}


$csvLoad = file($_FILES['excel_new_file']['tmp_name']);
$csvArray = split("\n",implode($csvLoad));

$k = 0;

for($i=0;$i<count($csvArray);$i++) {

    $_fld =explode(",", $csvArray[$i]);

    /*$it_id          = addslashes(trim($_fld[0]));       // 상품코드
    $ca_id          = addslashes(trim($_fld[1]));       // 기본분류
    $ca_id2         = addslashes(trim($_fld[2]));       // 분류2
    $ca_id3         = addslashes(trim($_fld[3]));       // 분류3
    $it_name        = addslashes(trim($_fld[4]));       // 상품평
    $it_maker       = addslashes(trim($_fld[5]));  // 제조사
    $it_origin      = addslashes(trim($_fld[6]));  // 원산지
    $it_brand       = addslashes(trim($_fld[7]));  // 브랜드
    $it_model       = addslashes(trim($_fld[8]));  // 모델
    $it_basic       = addslashes(trim($_fld[9]));  // 기본설명
    $it_explan      = addslashes(trim($_fld[10]));  // 상품설명
    $it_cust_price  = addslashes(only_number(trim($_fld[12]))); // 시중가격
    $it_use         = addslashes(only_number(trim($_fld[13]))); // 판매가능
    $it_stock_qty   = addslashes(only_number(trim($_fld[14]))); // 재고수
    $it_noti_qty    = addslashes(only_number(trim($_fld[15]))); // 재고통보
    $it_buy_min_qty = addslashes(only_number(trim($_fld[16]))); // 최소구매수량
    $it_buy_max_qty = addslashes(only_number(trim($_fld[17]))); // 최대구매수량
    $it_notax       = addslashes(only_number(trim($_fld[18]))); // 과세유형
    $it_1			= addslashes(trim($_fld[19])); // 보험코드
    $it_2			= addslashes(trim($_fld[20])); // 품목기준코드
    $it_3			= addslashes(trim($_fld[21])); // 효능/효과
    $it_4			= addslashes(trim($_fld[22])); // 주요성분
    $it_5           = addslashes(trim($_fld[23]));       // 규격
    $it_6           = addslashes(trim($_fld[24]));       // 단위
    $it_8	        = addslashes(trim($_fld[25]));       // 부모마스터코드
    $it_9	        = addslashes(trim($_fld[26]));       // 제약사코드
    $it_10	        = addslashes(trim($_fld[27]));       // 아이디
    $it_order       = addslashes(only_number(trim($_fld[28]))); // 정렬순
    $it_img1        = addslashes(trim($_fld[29]));      // 이미지1*/

    $it_id          = addslashes(trim($_fld[0]));       // 상품코드
    $ca_id          = addslashes(trim($_fld[1]));       // 기본분류
    $ca_id1         = addslashes(trim($_fld[2]));       // 분류1
    $ca_id2         = addslashes(trim($_fld[3]));       // 분류2
    $ca_id3         = addslashes(trim($_fld[4]));       // 분류3

    $it_name        = addslashes(trim($_fld[5]));  // 상품평
    $it_maker       = addslashes(trim($_fld[6]));  // 제조사


    $it_origin      = addslashes(trim($_fld[7]));  // 원산지
    $it_brand       = addslashes(trim($_fld[8]));  // 브랜드
    $it_model       = addslashes(trim($_fld[9]));  // 모델
    $it_1			= addslashes(trim($_fld[10])); // 보험코드
    $it_2			= addslashes(trim($_fld[11])); // 품목기준코드
    $it_3			= addslashes(trim($_fld[12])); // 효능/효과
    $it_4			= addslashes(trim($_fld[13])); // 주요성분
    $it_5           = addslashes(trim($_fld[14]));       // 규격
    $it_6           = addslashes(trim($_fld[15]));       // 단위
    $it_9	        = addslashes(trim($_fld[16]));       // 제약사코드
    $it_img1        = addslashes(trim($_fld[17]));      // 이미지1
    $it_cust_price  = addslashes(only_number(trim($_fld[18]))); // 시중가격
    $it_use         = 1z; // 판매가능

    /*$it_basic       = addslashes(trim($_fld[10]));  // 기본설명
    $it_explan      = addslashes(trim($_fld[10]));  // 상품설명


    $it_noti_qty    = addslashes(only_number(trim($_fld[15]))); // 재고통보
    $it_buy_min_qty = addslashes(only_number(trim($_fld[16]))); // 최소구매수량
    $it_buy_max_qty = addslashes(only_number(trim($_fld[17]))); // 최대구매수량
    $it_notax       = addslashes(only_number(trim($_fld[18]))); // 과세유형
    $it_8	        = addslashes(trim($_fld[25]));       // 부모마스터코드
    $it_10	        = addslashes(trim($_fld[27]));       // 아이디
    $it_order       = addslashes(only_number(trim($_fld[28]))); // 정렬순
    */

    $it_img_dir = G5_DATA_PATH.'/item';
    $_goods_folder = $it_img_dir."/".$it_id;

    echo $ca_id;
    echo "<br>";

    echo $_goods_folder;
    echo "<br>";


    /*if(!is_dir($_goods_folder)){
        @mkdir($_goods_folder, 0777);
        @chmod($_goods_folder, 0777);
    }*/

    $is_file_exist = file_exists('/data/was/shop/tmp_image/'.$it_img1);
    $_tmp_goods_file = '/data/was/shop/tmp_image/'.$it_img1 ;
    $_move_goods_file = $_goods_folder.'/'.$it_img1;

    /*if ($is_file_exist) {
        if(!empty($it_img1)) {
            if(!copy($_tmp_goods_file, $_move_goods_file)) {
                echo "파일 복사에 실패하였습니다.<br>";
            } else if(file_exists($_move_goods_file)) {
                echo "파일 복사에 성공하였습니다.<br>";
            }
        }
    }*/

   /* $_del_sql = "delete from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";

    echo $_del_sql;
    echo "<br>";
    sql_fetch($_del_sql);*/

    /*for($k=1;$k<11;$k++){
        $_tmp_goods_file = '/data/was/shop/tmp_image/'.${"it_img".$k} ;
        $_move_goods_file = $_goods_folder.'/'. ${"it_img".$k};


        /*if(!empty(${"it_img".$k})) {
            if(!copy($_tmp_goods_file, $_move_goods_file)) {
                echo "파일 복사에 실패하였습니다.";
            } else if(file_exists($_move_goods_file)) {
                echo "파일 복사에 성공하였습니다.";
            }
        }*/
//    }

    $it_img1    = $it_id."/".$it_img1; // 이미지1
    /*$it_img2    = $it_id."/".$it_img2; // 이미지2
    $it_img3    = $it_id."/".$it_img3; // 이미지3
    $it_img4    = $it_id."/".$it_img4; // 이미지4
    $it_img5    = $it_id."/".$it_img5; // 이미지5
    $it_img6    = $it_id."/".$it_img6; // 이미지6
    $it_img7    = $it_id."/".$it_img7; // 이미지7
    $it_img8    = $it_id."/".$it_img8; // 이미지8
    $it_img9    = $it_id."/".$it_img9; // 이미지9
    $it_img10   = $it_id."/".$it_img10; // 이미지10*/


    // it_id 중복체크
    $sql2 = " select count(*) as cnt from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $row2 = sql_fetch($sql2);
    if($row2['cnt']) {
        $fail_it_id[] = $it_id;
        $dup_it_id[] = $it_id;
        $dup_count++;
        $fail_count++;

        echo "it_id중복";
        echo "<br>";

        continue;
    }

    // 기본분류체크
    $sql2 = " select count(*) as cnt from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $row2 = sql_fetch($sql2);
    if(!$row2['cnt']) {
        $fail_it_id[] = $it_id;
        $fail_count++;

        echo "기본분류체크 중복";
        echo "<br>";

        continue;
    }


    $_sql = " INSERT INTO {$g5['g5_shop_item_table']}
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
                         it_cust_price = '0',
                         it_price = '$it_cust_price',
                         it_stock_qty = '$it_stock_qty',
                         it_use = '$it_use',
                         it_time = '".G5_TIME_YMDHIS."',
                         it_ip = '{$_SERVER['REMOTE_ADDR']}',
                         it_order = '$it_order',
                         it_img1 = '$it_img1',
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


    echo $_sql;
//    sql_query($_sql);
    echo "<br><br>";

    $k++;
}

exit;
?>