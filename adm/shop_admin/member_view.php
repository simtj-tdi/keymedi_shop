<?php
include_once('./_common.php');
 
if( $od_id == "99" ){
	$mb = sql_fetch("select * from portal.g5_member where mb_id = '$mb_id' "); 
	
}else if( $od_id == "98" ){
	$mb = sql_fetch("select * from shop_skin.g5_member where mb_id = '$mb_id' "); 
}else{
	$mb = sql_fetch("select * from {$g5['member_table']} where mb_id = '$mb_id' ");
	if(!$mb){
        $mb = sql_fetch("select * from portal.g5_member where mb_id = '$mb_id' ");
    }
}

 
?>
<style>
* {font-size:12px;}
</style>
<link rel="stylesheet" href="/adm/css/admin.css">
<h2 style="margin-top:50px;font-size:20px;"><?php echo $mb['mb_name'] ?>님 회원정보</h2>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>회원정보</caption>
    <colgroup>
        <col width="20%">
        <col width="30%">
        <col width="20%">
        <col width="30%">
    </colgroup>
    <tbody>
		<tr>
			<th scope="row">아이디</th>
			<td><?php echo $mb['mb_id'] ?></td>
			<th scope="row">고객명</th>
			<td><?php echo $mb['mb_name'] ?></td>
		</tr>
		<tr>
			<th scope="row">병원명</th>
			<td><?php echo $mb['mb_11'] ?></td>
			<th scope="row">사업자번호</th>
			<td><?php echo $mb['mb_15'] ?></td>
		</tr>
		<tr>
			<th scope="row">병원전화</th>
			<td><?php echo $mb['mb_8'] ?></td>
			<th scope="row">병원팩스</th>
			<td><?php echo $mb['mb_14'] ?></td>
		</tr>
		<tr>
			<th scope="row">메일주소</th>
			<td><?php echo $mb['mb_17'] ?></td>
			<th scope="row">요양기관번호</th>
			<td><?php echo $mb['mb_18'] ?></td>
		</tr>
		<tr>
			<th scope="row">병원주소</th>
			<td colspan="3">[<?php echo $mb['mb_5'] ?>] <?php echo $mb['mb_6'] ?> <?php echo $mb['mb_7'] ?></td> 
		</tr>

		<?php
		if($mb[mb_where] == "메디포털"){
			$mb_dir = substr($mb['mb_id'],0,2);
			$icon_file = '/data/was/portal/data/member/'.$mb_dir.'/'.$mb['mb_id']."_saup";
			$icon_file2 = '/data/was/portal/data/member/'.$mb_dir.'/'.$mb['mb_id']."_saup.pdf";
			if (file_exists($icon_file)) {
		?>
		<tr>
			<th scope="row">사업자등록증</th>
			<td colspan="3"><img src="http://www.keymedi.com/data/member/<?=$mb_dir?>/<?=$mb[mb_id]?>_saup" width="500"></td> 
		</tr>
		<?		
			}
			if (file_exists($icon_file2)) {
		?>
		<tr>
			<th scope="row">사업자등록증</th>
			<td colspan="3"><a href='http://www.keymedi.com/data/member/<?=$mb_dir?>/<?=$mb[mb_id]?>_saup.pdf' target='_blank'>사업자등록증</a></td> 
		</tr>
		<?		
			}
		}else if($mb[mb_where] == "산부인과 협동조합"){
			$mb_dir = substr($mb['mb_id'],0,2);
			$icon_file = '/data/was/union/data/member/'.$mb_dir.'/'.$mb['mb_id'];
			if (file_exists($icon_file)) {
		?>
		<tr>
			<th scope="row">사업자등록증</th>
			<td colspan="3"><img src="http://obgy.keymedi.com/data/member/<?=$mb_dir?>/<?=$mb[mb_id]?>" width="500"></td> 
		</tr>
		<?	
			}
		}else if($mb[mb_where] == "피부비만"){ 
	
			$mb_dir = substr($mb['mb_id'],0,2);
			$icon_file = '/data/was/shop/data/member/'.$mb_dir.'/'.$mb['mb_id'];
			if (file_exists($icon_file)) {
		?>
		<tr>
			<th scope="row">사업자등록증</th>
			<td colspan="3"><img src="http://obgys.keymedi.com/data/member/<?=$mb_dir?>/<?=$mb[mb_id]?>" width="500"></td> 
		</tr>
		<?} } ?>
	</tbody>
	</table>
</div>