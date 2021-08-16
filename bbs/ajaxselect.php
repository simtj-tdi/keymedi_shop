<?php
include_once('./_common.php');

$ajax_part = $_POST['optVal'];
$ajax_sub_part = $_POST['optSubVal'];

switch($ajax_part){
    case "내과" :
        $_part_array = array('호흡기내과','순환기내과','소화기내과','혈액종양내과','내분비내과','알레르기내과','신장내과','감염내과','류마티스내과');
        break;
    case "외과" :
        $_part_array = array('소화기외과','내분비외과','이식혈관외과','유방외과','소아외과');
        break;
    case "산부인과" :
        $_part_array = array('산과','부인과');
        break;
    case "피부과" :
        $_part_array = array('피부질환','피부미용');
        break;
    case "치과" :
        $_part_array = array('구강내과','구강외과','치주과','보철과','보존과','교정과','소아치과','영상치의학과','치과마취과','통합치의학과','예방치과','구강병리과');
        break;
}
$option="";


foreach($_part_array as $value)
{

    $_selected = $ajax_sub_part == $value ? "selected" : "" ;

    $option=$option."<option value='".$value."' ".$_selected.">".$value."</option>";

}

echo $option;

?>