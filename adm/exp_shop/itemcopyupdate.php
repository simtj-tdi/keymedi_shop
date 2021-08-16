<?php
$sub_menu = '600300';
include_once('./_common.php'); 
auth_check($auth[$sub_menu], "w");
 
 
check_admin_token();
/*
if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");
*/
//shop_juny
if(!$site_code){
	alert("잘못된요청입니다.");
}else{
	$site_fix = $site_code;
}
if (!trim($it_id))
	alert("복사할 상품코드가 없습니다.");

$t_it_id = preg_replace("/[A-Za-z0-9\-_]/", "", $new_it_id);
if($t_it_id)
    alert("상품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");

$t_it_9 = preg_replace("/[A-Za-z0-9\-_]/", "", $new_it_9);
if($t_it_9)
    alert("업체코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");

$t_it_price = preg_replace("/[A-Za-z0-9\-_]/", "", $new_it_price);
if($t_it_price)
    alert("판매가격는 숫자만 사용할 수 있습니다.");

$t_it_stock_qty = preg_replace("/[A-Za-z0-9\-_]/", "", $new_it_stock_qty);
if($t_it_stock_qty)
    alert("재고수량는 숫자만 사용할 수 있습니다.");






$row = sql_fetch(" select count(*) as cnt from {$site_fix}.{$g5['g5_shop_item_table']} where it_id = '$new_it_id' ");
if ($row['cnt']){
    alert('이미 존재하는 상품코드 입니다.');
}

$dis_cnt = sql_fetch(" select count(*) as cnt from {$site_fix}.{$g5['g5_shop_item_table']} where it_8 = '$it_id' and it_10 = '$member[mb_id]' ");
if($dis_cnt[cnt] > 0){
	 alert('이미 존재하는 상품입니다.','/adm/exp_shop/itemlist.php');exit;
}

$sql = " select * from {$site_fix}.{$g5['g5_shop_item_table']} where it_id = '$it_id' limit 1 ";
$cp = sql_fetch($sql);


// 상품테이블의 필드가 추가되어도 수정하지 않도록 필드명을 추출하여 insert 퀴리를 생성한다. (상품코드만 새로운것으로 대체)
$sql_common = "";
$fields = sql_field_names($site_fix.".".$g5['g5_shop_item_table']);
 
foreach($fields as $fld) {
    if ($fld == 'it_id' || $fld == 'it_sum_qty' || $fld == 'it_use_cnt' || $fld == 'it_use_avg')
        continue;

    $sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
}

$sql = " insert {$site_fix}.{$g5['g5_shop_item_table']}
			set it_id = '$new_it_id'
                $sql_common ";
sql_query($sql);

 




$sql_u = "update {$site_fix}.{$g5['g5_shop_item_table']} set it_8 = '$it_id' , it_10 = '$member[mb_id]' ,it_9 = '$new_it_9' ,  it_price = '$new_it_price' ,it_stock_qty = '$new_it_stock_qty' where it_id = '$new_it_id' ";

sql_query($sql_u);

// 선택/추가 옵션 copy
$opt_sql = " insert ignore into {$site_fix}.{$g5['g5_shop_item_option_table']} ( io_id, io_type, it_id, io_price, io_stock_qty, io_noti_qty, io_use )
                select io_id, io_type, '$new_it_id', io_price, io_stock_qty, io_noti_qty, io_use
                    from {$site_fix}.{$g5['g5_shop_item_option_table']}
                    where it_id = '$it_id'
                    order by io_no asc ";
sql_query($opt_sql);

// html 에디터로 첨부된 이미지 파일 복사
if($cp['it_explan']) {
    $matchs = get_editor_image($cp['it_explan'], false);

    // 파일의 경로를 얻어 복사
    for($i=0;$i<count($matchs[1]);$i++) {
        $p = parse_url($matchs[1][$i]);
        if(strpos($p['path'], "/data/") != 0)
            $src_path = preg_replace("/^\/.*\/data/", "/data", $p['path']);
        else
            $src_path = $p['path'];

        $srcfile = G5_PATH.$src_path;
        $dstfile = preg_replace("/\.([^\.]+)$/", "_".$new_it_id.".\\1", $srcfile);

        if(is_file($srcfile)) {
            copy($srcfile, $dstfile);

            $newfile = preg_replace("/\.([^\.]+)$/", "_".$new_it_id.".\\1", $matchs[1][$i]);
            $cp['it_explan'] = str_replace($matchs[1][$i], $newfile, $cp['it_explan']);
        }
    }

    $sql = " update {$site_fix}.{$g5['g5_shop_item_table']} set it_explan = '".addslashes($cp['it_explan'])."' where it_id = '$new_it_id' ";
    sql_query($sql);
}

if($cp['it_mobile_explan']) {
    $matchs = get_editor_image($cp['it_mobile_explan'], false);

    // 파일의 경로를 얻어 복사
    for($i=0;$i<count($matchs[1]);$i++) {
        $p = parse_url($matchs[1][$i]);
        if(strpos($p['path'], "/data/") != 0)
            $src_path = preg_replace("/^\/.*\/data/", "/data", $p['path']);
        else
            $src_path = $p['path'];

        $srcfile = G5_PATH.$src_path;
        $dstfile = preg_replace("/\.([^\.]+)$/", "_".$new_it_id.".\\1", $srcfile);

        if(is_file($srcfile)) {
            copy($srcfile, $dstfile);

            $newfile = preg_replace("/\.([^\.]+)$/", "_".$new_it_id.".\\1", $matchs[1][$i]);
            $cp['it_mobile_explan'] = str_replace($matchs[1][$i], $newfile, $cp['it_mobile_explan']);
        }
    }

    $sql = " update {$site_fix}.{$g5['g5_shop_item_table']} set it_mobile_explan = '".addslashes($cp['it_mobile_explan'])."' where it_id = '$new_it_id' ";
    sql_query($sql);
}

// 상품이미지 복사
function copy_directory($src_dir, $dest_dir)
{
    if($src_dir == $dest_dir)
        return false;

    if(!is_dir($src_dir))
        return false;

    if(!is_dir($dest_dir)) {
        @mkdir($dest_dir, G5_DIR_PERMISSION);
        @chmod($dest_dir, G5_DIR_PERMISSION);
    }

    $dir = opendir($src_dir);
    while (false !== ($filename = readdir($dir))) {
        if($filename == "." || $filename == "..")
            continue;

        $files[] = $filename;
    }

    for($i=0; $i<count($files); $i++) {
        $src_file = $src_dir.'/'.$files[$i];
        $dest_file = $dest_dir.'/'.$files[$i];
        if(is_file($src_file)) {
            copy($src_file, $dest_file);
            @chmod($dest_file, G5_FILE_PERMISSION);
        }
    }
}

// 파일복사
$G5_DATA_PATH2 = "/data/was/".$site_fix."/data";

$dest_path = $G5_DATA_PATH2.'/item/'.$new_it_id;
@mkdir($dest_path, G5_DIR_PERMISSION);
@chmod($dest_path, G5_DIR_PERMISSION);
$comma = '';
$sql_img = '';

for($i=1; $i<=10; $i++) {
    $file = $cp['it_img'.$i];
    $new_img = '';

    if(is_file($file)) {
        $dstfile = $dest_path.'/'.basename($file);
        copy($file, $dstfile);
        @chmod($dstfile, G5_FILE_PERMISSION);
        $new_img = $G5_DATA_PATH2.'/item/'.$new_it_id.'/'.basename($file);
    }

    $sql_img .= $comma." it_img{$i} = '$new_img' ";
    $comma = ',';
}

$sql = " update {$site_fix}.{$g5['g5_shop_item_table']}
            set $sql_img
            where it_id = '$new_it_id' ";
sql_query($sql);

$qstr = "ca_id=$ca_id&amp;sfl=$sfl&amp;sca=$sca&amp;page=$page&amp;stx=".urlencode($stx)."&amp;save_stx=".urlencode($save_stx);

goto_url("itemlist2.php?$qstr");
?>