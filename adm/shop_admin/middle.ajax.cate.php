<?php
include_once('./_common.php');

$_dep_array = array('ca_id_13','ca_id_23','ca_id_33');

if(in_array($dep,$_dep_array)){
    $_chk = '3';
}else{
    $_chk = '2';
}

if($_chk==2){
    $where = "where left(ca_id,2) = '$val' and LENGTH(ca_id) = '4' ";
}else if($_chk==3){
    $where = "where left(ca_id,4) = '$val'  and LENGTH(ca_id) >= '6' ";
}

$sql1 = " select ca_id, ca_name from {$site_fix}.{$g5['g5_shop_category_table']}  {$where}  order by ca_id , ca_order ";
$result1 = sql_query($sql1);
echo "<option value=''>".$_chk."차분류</option>";
for ($i=0; $row1=sql_fetch_array($result1); $i++) {
    echo '<option value="'.$row1['ca_id'].'" '.get_selected($row1['ca_id']).'>'.$row1['ca_name'].'</option>'.PHP_EOL;
}

?>