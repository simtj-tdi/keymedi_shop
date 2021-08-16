<?php
include_once('./_common.php');

if($sort==2){
    $where = "where left(ca_id,2) = '$val' and LENGTH(ca_id) = '4' ";
}else{
    $where = "where left(ca_id,4) = '$val'  and LENGTH(ca_id) >= '6' ";
}

$sql1 = " select ca_id, ca_name from {$site_fix}.{$g5['g5_shop_category_table']}  {$where}  order by ca_id , ca_order ";
$result1 = sql_query($sql1);
echo "<option value=''>".$sort."차분류</option>";
for ($i=0; $row1=sql_fetch_array($result1); $i++) {
    echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca_ck, $row1['ca_id']).'>'.$row1['ca_name'].'</option>'.PHP_EOL;
}

?>