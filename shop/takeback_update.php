<?php
include_once('./_common.php');



if($mode == "B"){

	for ($i=0; $i<count($_POST['chk']); $i++){
		// 실제 번호를 넘김
        $k = $_POST['chk'][$i];
		
		if($ct_qty[$k] > 0){
			$sql = "insert into shop.g5_takeback set 
					od_id = '$od_id' , 
					ct_id = '{$ct_id[$k]}' , 
					mb_id = '$mb_id' , 
					wr_name = '$wr_name' , 
					com_id = '$com_id' ,
					ca_name = '반품' , 
					ct_qty = '{$ct_qty[$k]}' , 
					memo = '$memo' , 
					status = '반품요청' , 
					wr_datetime = now() 
				";
			sql_query($sql);
		}else{

		}

	}
	alert("반품요청이 되었습니다.","/shop/takeback.php");
}else{
	alert("잘못된요청입니다.","/shop/mypage.php");
}



?>