<?php
include("/_common.php");

$ret = array();
$location = $_GET['location'];
$count = if($_GET['count']) ? $_GET['count'] : 0;

//# 테스트 데이터
for($i=0; $i<10; $i++) {
    $it_id = $i+1;
    $ret["list"][$i]['it_id'] = $it_id;
    $ret["list"][$i]['it_img'] = $it_id."_img";
    $ret["list"][$i]['it_name'] = $it_id."_상품명";
    $ret["list"][$i]['it_std'] = $it_id."_규격";
    $ret["list"][$i]['it_unit'] = $it_id."_단위";
}


$ret["location"] = $location;

echo "<xmp>";
print_r($ret);
echo "</xmp>";