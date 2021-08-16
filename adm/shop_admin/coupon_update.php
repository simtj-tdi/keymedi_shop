<?php
$sub_menu = "400810";
include_once('./_common.php');

	if($mode =="U"){
	$sql = "update g5_coupon set ";
	$sql .= "title = '$title' , ";
	$sql .= "tel = '$tel' , ";
	$sql .= "on_hp = '$on_hp' , ";
	$sql .= "re_hp = '$re_hp' , ";
	$sql .= "orderby = '$orderby' , ";
	$sql .= "pass = '$pass' , ";
	$sql .= "email = '$email',  ";
	$sql .= "no_date = '$no_date'  ";
	$sql .= "where idx = '$idx' ";
	
	sql_query($sql);
	
	

	alert("수정되었습니다.","/adm/shop_admin/coupon_view.php?wr_id=".$wr_id);

	}else if($mode =="D"){
	
		for($j = 0; $j < count($chk); $j++){ 
			$tmp = $chk[$j];
			sql_query(" delete from g5_coupon where wr_id = '$tmp' ");
		}
		alert("삭제되었습니다.","/adm/shop_admin/coupon_list.php");
		
	}else{
		
		$sql = "insert into  g5_coupon set ";
		$sql .= "wr_subject = '$wr_subject' , ";
		$sql .= "wr_target = '$wr_target' , ";
		$sql .= "wr_method = '$wr_method' , ";
		$sql .= "wr_sdate = '$wr_sdate' , ";
		$sql .= "wr_edate = '$wr_edate' , ";
		$sql .= "wr_price = '$wr_price' , ";
		$sql .= "wr_min = '$wr_min' , ";
		$sql .= "wr_num = '$wr_num' , "; 
		$sql .= "wr_datetime = now()  ";
		sql_query($sql);
	 	$wr_id = sql_insert_id();

		function acoupon($couponNum,$wr_id,$wr_subject,$wr_target,$wr_method,$wr_sdate,$wr_edate,$wr_price,$wr_min) {
			$couponArray=array(
				0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9",
				10=>"A",11=>"B",12=>"C",13=>"D",14=>"E",15=>"F",16=>"G",17=>"H",18=>"I",19=>"J",
				20=>"K",21=>"L",22=>"M",23=>"N",24=>"O",25=>"P",26=>"Q",27=>"R",28=>"S",29=>"T",
				30=>"U",31=>"V",32=>"W",33=>"X",34=>"Y",35=>"Z"
			);

			mt_srand(microtime(true)*1000000); //난수값 초기화
			for($k=0;$k<$couponNum;$k++){ //생성 할 쿠폰의 갯수
				$resultStr="";
					for($i=0;$i<16;$i++){ //생성할 쿠폰의 자릿수 반드시 짝수여야 암호화된 뒷자리를 얻을 수 있다.
						$randNo = rand(0,35); //0과 35사이의 난수를 구한다
						$resultStr .= $couponArray[$randNo];
					}
				$resultStr = substr($resultStr,0,4)."-".substr($resultStr,4,4)."-".substr($resultStr,8,4)."-".substr($resultStr,12,4);

				$c_sql = "insert into  g5_coupon_list set ";
				$c_sql .= "wr_paren_id = '$wr_id' , ";
				$c_sql .= "wr_code = '$resultStr' , ";
				$c_sql .= "wr_subject = '$wr_subject' , ";
				$c_sql .= "wr_target = '$wr_target' , ";
				$c_sql .= "wr_method = '$wr_method' , ";
				$c_sql .= "wr_sdate = '$wr_sdate' , ";
				$c_sql .= "wr_edate = '$wr_edate' , ";
				$c_sql .= "wr_price = '$wr_price' , "; 
				$c_sql .= "wr_min = '$wr_min' , ";
				$c_sql .= "wr_datetime = now() ";
				sql_query($c_sql);
 
			}
		}
		acoupon($wr_num,$wr_id,$wr_subject,$wr_target,$wr_method,$wr_sdate,$wr_edate,$wr_price,$wr_min);
		alert("등록되었습니다..","/adm/shop_admin/coupon_list.php");
	}


?>