<?php
/**
 *  210304 - jinam23
 * 
 *  Params
 * 	    obj.mb_id = '<?=$member['mb_id']?>' ;
 *      obj.type = type ;
 */
include_once('./_common.php');

if (!$is_member) {
    $output['stat'] = 0 ;
} else {
    ( isset($_POST['mb_id']) ) ? $mb_id = $_POST['mb_id'] : $mb_id = $member['mb_id'];
    //$mb_agree     = $_POST['mb_agree'];   //  필요없슴. 
    ( isset($_POST['type']) ) ? $type = $_POST['type'] : $type = 0 ;

    if( $mb_id && $type!=0 ) {
        $saveQry = "INSERT INTO g5_mov_popup_banner_log_tbl(mb_id, type, reg_dt) VALUES(\"".$mb_id."\", ".$type.", ".strtotime(date("Y-m-d H:i")).")" ; 
        sql_query($saveQry) ;        
        $output['stat'] = 1 ;        
    } else {
        $output['stat'] = 2 ;        
    }
}

echo json_encode($output);
?>