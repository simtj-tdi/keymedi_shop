<?
	include_once('./_common.php');

if(count($chk) <= 0){

	alert("오류입니다.","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}
	
if($mode == "coupon"){
	for($i = 0; $i < count($chk); $i++){
		
 
		$wr_date = $years.$months;
		$rowd = sql_fetch("select count(*) as cnt from g5_grade where mb_id = '{$chk[$i]}' and wr_date = '$wr_date' ");

		if($rowd[cnt] > 0){
			$sql = "update g5_grade set ";
			$sql .= "wr_class = '$grades' , wr_datetime = now() "; 
			$sql .= "where mb_id = '{$chk[$i]}' and wr_date = '$wr_date' ";
			 
			sql_query($sql);

		}else{
			$sql = "insert into  g5_grade set ";
			$sql .= "wr_class = '$grades' , ";
			$sql .= "wr_date = '$wr_date' , ";
			$sql .= "mb_id = '{$chk[$i]}' , ";
			$sql .= "mb_where = '$site' , ";
			$sql .= "wr_datetime = now() "; 
			 
			 
			sql_query($sql);
		}
 
	}
	alert("등급적용완료","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}else{
	alert("오류입니다.","/adm/member_level.php?year=".$years."&month=".$months."&grade=".$grades);
}
?>