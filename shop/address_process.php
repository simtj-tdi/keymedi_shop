<?php
include_once('./_common.php');
// 기본배송지 체크
if($ad_default) {
    $sql = " update {$g5['g5_shop_order_address_table']}
                    set ad_default = '0'
                    where mb_id = '{$member['mb_id']}' ";
    sql_query($sql);
}

$od_b_zip   = preg_replace('/[^0-9]/', '', $od_b_zip);
$od_b_zip1  = substr($od_b_zip, 0, 3);
$od_b_zip2  = substr($od_b_zip, 3);
$zipcode = $od_b_zip;

$ad_subject = clean_xss_tags($ad_subject);

if($mode=="modify"){
    $alert_txt = "수정";
    $location_url = './new_orderaddress_form.php?idx='.$ad_id;
    $sql = " update {$g5['g5_shop_order_address_table']}
                    set ad_subject  = '$ad_subject',
                        ad_default  = '$ad_default',
                        ad_name     = '$od_b_name',
                        ad_tel      = '$od_b_tel',
                        ad_hp       = '$od_b_hp',
                        ad_zip1     = '$od_b_zip1',
                        ad_zip2     = '$od_b_zip2',
                        ad_addr1    = '$od_b_addr1',
                        ad_addr2    = '$od_b_addr2'
                    where mb_id = '{$member['mb_id']}'
                    and ad_id = '{$ad_id}' ";
} else if($mode=="write") {
    $alert_txt = "등록";
    $location_url = './new_orderaddress.php';
    $sql = " insert into {$g5['g5_shop_order_address_table']}
                    set mb_id       = '{$member['mb_id']}',
                        ad_subject  = '$ad_subject',
                        ad_default  = '$ad_default',
                        ad_name     = '$od_b_name',
                        ad_tel      = '$od_b_tel',
                        ad_hp       = '$od_b_hp',
                        ad_zip1     = '$od_b_zip1',
                        ad_zip2     = '$od_b_zip2',
                        ad_addr1    = '$od_b_addr1',
                        ad_addr2    = '$od_b_addr2',
                        ad_jibeon   = '$od_b_addr_jibeon' ";
}

$_res = sql_query($sql);

if($_res){
    $data = array(
        'r_type'=>$mode,
        'r_status'=>'success',
        'sql' => $sql
    );
}else{
    $data = array(
        'r_type'=>$mode,
        'r_status'=>'fail',
        'sql' => $sql
    );
}

echo json_encode($data);
?>