<?php
include_once('./_common.php');

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");

$filename = date("Y-m-d")."_CATEGORY.xls";

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP4 Generated Data" ); 

?>
<table border="1">
	<tr>
		<td>카테고리코드</td>
		<td>카테고리명</td>
		<td>사용여부</td>
		<td>사이트종류</td> 
	</tr>
<? 
	$sql = "select * from g5_shop_category order by ca_id asc"; 
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){
	
		$site_code = explode(",",$row[site_code]);
		$site_name = "";

		for($i = 0 ; $i < count($site_code);$i++ ){ 
			if($i==0){
				$site_name = site_code_name($site_code[$i]);
			}else{
				$site_name = $site_name .",". site_code_name($site_code[$i]);
			}
			
		}	
?>
	<tr>
		<td><?=$row[ca_id]?>&nbsp;</td>
		<td><?=$row[ca_name]?>&nbsp;</td>
		<td><?=($row[ca_use]=="1")?"예":"아니오"?></td>
		<td><?=$site_name ?>&nbsp;</td>
	</tr>
<? } ?>
</table>




<?
function site_code_name($code){
 
	$sql = "select * from site_code where code = '$code' ";
	$res = sql_fetch($sql);
	return $res[code_name];
}
?>
