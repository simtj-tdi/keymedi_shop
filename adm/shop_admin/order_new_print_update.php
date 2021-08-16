<?
include_once('./_common.php');
$aaa = "od_id,mb_id,it_id,com_id,com_od_id,it_name,it_sc_type,it_sc_method,it_sc_price,it_sc_minimum,it_sc_qty,ct_status,ct_history,ct_price,ct_point,cp_price,ct_point_use,ct_stock_use,ct_option,ct_qty,ct_notax,io_id,io_type,io_price,ct_time,ct_ip,ct_send_cost,ct_direct,ct_select,ct_select_time";



if($mode =="U"){

	$od_id_array = "";
	$com_id = "";
	for($j = 0; $j < count($chk); $j++){  
		
		$k = $_POST['chk'][$j];
	
		sql_query(" insert into g5_shop_order_js select * from g5_shop_order where od_id = '{$_POST[od_id][$k]}' ");
		
		if(substr($_POST['od_id'][$k],0,2)=="98"){
			sql_query(" insert into g5_shop_cart_js ($aaa) select $aaa from shop_skin.g5_shop_cart where od_id = '{$_POST[od_id][$k]}' and ct_status != '취소' ");
		}else{
			sql_query(" insert into g5_shop_cart_js ($aaa) select $aaa from g5_shop_cart where od_id = '{$_POST[od_id][$k]}' and ct_status != '취소' ");
		}
		
		if($com_id == ""){
			$com_id = $_POST[com_id][$k];
		}

		if($od_id_array == ""){
			$od_id_array = $_POST[od_id][$k];
		}else{
			$od_id_array = $od_id_array.",".$_POST[od_id][$k];
		}
		
	}
	

	$sql = " select com_id from g5_shop_order_js where od_id in ({$od_id_array}) group by com_id ";
	$res = sql_query($sql);

	$od_id_array2 = "";
	$com_id2 = "";

	for($x = 0 ; $row = sql_fetch_array($res);$x++){
		if($com_id2[$x] == ""){
			$com_id2[$x] = $row['com_id'];	
		}
	}
	
	for($xx = 0; $xx < count($com_id2); $xx++){  
		$sql = " select od_id from g5_shop_order_js where od_id in ({$od_id_array}) and com_id = '{$com_id2[$xx]}' ";
		$res = sql_query($sql);
		while($row = sql_fetch_array($res)){
			if($od_id_array2[$xx] == ""){
				$od_id_array2[$xx] = $row['od_id'];
			}else{
				$od_id_array2[$xx] = $od_id_array2[$xx]."||".$row['od_id'];
			}
		}
		
		$cmem = get_member($com_id2[$xx]);

		sql_query("insert g5_shop_js set wr_subject = '{$fr_date_tmp}~{$to_date_tmp}/{$cmem[mb_nick]}' , wr_sdate = '$fr_date_tmp' , wr_edate = '$to_date_tmp' , od_id = '{$od_id_array2[$xx]}' , com_id = '{$com_id2[$xx]}' , wr_datetime = now()" );
	}





	for($j = 0; $j < count($chk); $j++){  
		$k = $_POST['chk'][$j];
 
		for($x= 0 ; $x < count($it_id); $x++ ){
			sql_query ("update g5_shop_cart_js set ct_js = '{$commission[$k][$x]}' where it_id = '{$it_id[$k][$x]}' and   od_id = '{$_POST[od_id][$k]}'");
		}

		echo "<BR>";
	} 
	alert("정산되었습니다.","order_new_print.php");

}else{
	alert("잘못된요청입니다.","order_new_print.php");
}

?>