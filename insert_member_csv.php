<?php
 include_once('./_common.php');
 //require_once(G5_THEME_PATH.'/head.sub.php');
 exit;

$sql = "select * from portal.union_member2";
$res = sql_query($sql);

while($row = sql_fetch_array($res)){
	// print_r($row);


	$wr_name	= $row[tmp2];
	$wr_hp		= $row[tmp3];
	$mb_birth	= $row[tmp4];
	$mb_1		= $row[tmp5];
	$mb_12		= $row[tmp6];	
	$mb_email	= $row[tmp7];	
	$mb_id		= $row[tmp8];	
	$mb_11		= $row[tmp9];	
	$mb_6		= $row[tmp10];	
	$mb_8		= $row[tmp11];	
	$mb_14		= $row[tmp12];
	$mb_15		= $row[tmp13]; 
	$mb_17		= $row[tmp14];	
	$mb_18		= $row[tmp15];	
	
	$tmp = explode("/" , $row[tmp16]);

	$mb_19		= $tmp[0];
	$mb_20		= $tmp[1];		
	$mb_13		= $row[tmp17];	
	
	
	$mb_pass = $mb_1;
	

	$sqld = "insert into portal.g5_member ";
	$sqld .="set mb_id = '$mb_id' , ";
	$sqld .=" mb_password = password('$mb_pass') , ";
	$sqld .=" mb_name = '$wr_name' , ";
	$sqld .=" mb_nick = '$wr_name' , ";
	$sqld .=" mb_email = '$mb_email' , ";
	$sqld .=" mb_level = '4' , ";
	$sqld .=" mb_hp = '$wr_hp' , ";

	$sqld .=" mb_birth = '$mb_birth' , ";
	$sqld .=" mb_1 = '$mb_1' , ";
	$sqld .=" mb_12 = '$mb_12' , ";
	$sqld .=" mb_11 = '$mb_11' , ";
	$sqld .=" mb_6 = '$mb_6' , ";
	$sqld .=" mb_8 = '$mb_8' , ";
	$sqld .=" mb_14 = '$mb_14' , ";
	$sqld .=" mb_15 = '$mb_15' , ";
	$sqld .=" mb_17 = '$mb_17' , ";
	$sqld .=" mb_18 = '$mb_18' , ";
	$sqld .=" mb_19 = '$mb_19' , ";
	$sqld .=" mb_20 = '$mb_20' , ";
	$sqld .=" mb_13 = '$mb_13' , ";

	$sqld .=" mb_shop = '2' , ";
	$sqld .=" mb_v = '1' , ";
	$sqld .=" mb_recommend = '산부인과 협동조합' , ";


	$sqld .=" mb_datetime = now() ";
		
	echo $sqld."<br>";

	//sql_query($sqld);

	
 } 




?>