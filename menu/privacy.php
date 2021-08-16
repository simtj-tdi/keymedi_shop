<?
// 내용
if( $_SERVER['SERVER_NAME']=='shop.keymedi.com' ) { 
    //$sql = " select * from g5_content where co_id = '$co_id' ";
    $sql = " select * from g5_content where co_id = 'privacy_km' ";
} else {
    $sql = " select * from g5_content where co_id = 'privacy_ob' ";
}

$co = sql_fetch($sql);
$str = conv_content($co['co_content'], $co['co_html'], $co['co_tag_filter_use']);
$str = str_replace("width:","",$str);
$str = str_replace("word-break:keep-all;","",$str);
 
?>
<style>
#ctt_con { position:relative;width:100%;overflow: hidden;}
#ctt_con table {width:100%;}
#ctt_con table td { width:25%;}
</style>
<div id="ctt_con">
<?=$str?>

</div>