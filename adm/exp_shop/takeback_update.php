<?php
include_once('./_common.php');



if($mode){	 
	print_r($_POST[wr_id]);
	switch($mode){
		case "I1" : $status = "반품요청"; break;
		case "I2" : $status = "반품확인"; break;
		case "I3" : $status = "반품진행중"; break;
		case "I4" : $status = "반품수령"; break;
		case "I5" : $status = "반품완료"; break;
        case "I6" : $status = "반품철회"; break;
	}
	for ($i=0; $i<count($_POST['chk']); $i++){
		// 실제 번호를 넘김
         $k = $_POST['chk'][$i];
 
			 $sql = "update shop.g5_takeback set  status = '{$status}'  , update_mb_id = '$member[mb_id]' , wr_update_datetime = now()  where wr_id = '{$_POST[wr_id][$k]}' ";
			sql_query($sql);
			
			 
		 

	} 
	alert("처리 되었습니다.","/adm/exp_shop/takeback.php");
}else{
	alert("잘못된요청입니다.","/adm/exp_shop/takeback.php");
}

?>