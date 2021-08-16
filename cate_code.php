<?php
include_once('./_common.php');

?>

<table border="1">
<? 
	$sql = "select * from g5_shop_category order by ca_id , ca_order asc"; 
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){
?>
	<tr>
		<td><?=$row[ca_id]?></td>
		<td><?=$row[ca_name]?></td>
	</tr>
<? } ?>
</table>