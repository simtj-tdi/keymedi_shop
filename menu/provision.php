<?
// 내용
if( $_SERVER['SERVER_NAME']=='shop.keymedi.com' ) { 
    //$sql = " select * from g5_content where co_id = '$co_id' ";
    $sql = " select * from g5_content where co_id = 'provision_km' ";
} else {
    $sql = " select * from g5_content where co_id = 'provision_ob' ";
}
$co = sql_fetch($sql);
$str = conv_content($co['co_content'], $co['co_html'], $co['co_tag_filter_use']);
?>
<style>
#ctt_con table td { width:25%;}
</style>
<div id="ctt_con">
<?=$str?>

</div>