<style>
#ctt  table { position:relative;width:100%;border-top:2px solid #000;border-collapse: collapse; border-spacing: 0;}
#ctt  table th { height:50px;border-bottom:1px solid #c8c8c8;font-size:12px;}
#ctt  table td { height:50px;border-bottom:1px solid #c8c8c8;text-align:center;font-size:12px;}
</style>

<article id="ctt" class="ctt_<?php echo $co_id; ?>">

<table>
	<thead>
		<tr>
			<th width="80">업체구분</th>
			<th width="120">업체명</th>
			<th width="120">사업자번호</th>
			<th width="150">연락처/팩스</th> 
			<th>주소</th>
		</tr>
	</thead>
	<tbody>
<? 
	$sql = "select * from shop.g5_member where mb_v = '4' and mb_leave_date = '' and mb_28 like '%shop%'  order by mb_13 asc , mb_nick asc"; 
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)){
		$mb_13 = "";
		switch($row[mb_13]){
			case "1":$mb_13 = "의약품";break;
			case "2":$mb_13 = "화장품/건기식";break;
			case "3":$mb_13 = "의료기기/장비";break;
			case "4":$mb_13 = "소모품";break;
		}
?>
		<tr>
			<td><?=$mb_13?></td>
			<td><?=$row[mb_nick]?></td>
			<td><?=$row[mb_1]?></td>
			<td>TEL : <?=$row[mb_tel]?><br>FAX : <?=$row[mb_2]?></td>
			<td style="text-align:left;">[<?=$row[mb_zip1]?><?=$row[mb_zip2]?>] <?=$row[mb_addr1]?><br><?=$row[mb_addr2]?></td>
		</tr>
<? } ?>
	</tbody>
</table>

</article>