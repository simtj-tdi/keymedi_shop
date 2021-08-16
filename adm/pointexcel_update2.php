<?php
$sub_menu = '400300';
include_once('./_common.php');




for($i = 0; $i < count($chk); $i++){

	$tmp = explode(",",$chk[$i]);
	//for($j = 0; $j < count($tmp); $j++){
	//	echo $tmp[$j]."<BR>";
	//}
	//insert_point($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='', $expire=0);
	insert_point($tmp[0], $tmp[2], $tmp[1], "@passive", $tmp[0], G5_TIME_YMD,$tmp[3]);

	
}

alert("지급완료","./pointexcel.php");

?>