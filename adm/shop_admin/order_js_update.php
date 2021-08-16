<?
include_once('./_common.php');


if($mode =="D"){

	for($j = 0; $j < count($chk); $j++){  
		
		$k = $_POST['chk'][$j];
		
		$row = sql_fetch( "select od_id from g5_shop_js where wr_id = '{$_POST[wr_id][$k]}' ");
		
		$array = explode("||",$row[od_id]);
		$od_id_tmp = "";
		for($x = 0 ; $x < count($array); $x++){
			if($od_id_tmp == ""){
				$od_id_tmp = "'".$array[$x]."'";
			}else{
				$od_id_tmp = $od_id_tmp.",'".$array[$x]."'";
			}
		}

		sql_query("delete from g5_shop_js where wr_id = '{$_POST[wr_id][$k]}' ");
		sql_query( "delete from g5_shop_cart_js where od_id in ({$od_id_tmp}) ");
		sql_query( "delete from g5_shop_order_js where od_id in ({$od_id_tmp}) ");
 
	}
	alert("삭제되었습니다..","order_js_list.php");
}

?>