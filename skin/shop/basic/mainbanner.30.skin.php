<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

?>

<?php
for ($i=0; $row=sql_fetch_array($result); $i++)
{
  
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
    if (file_exists($bimg))
    {
        $banner = '';
        $size = getimagesize($bimg);

        if ($row['bn_url'][0] == '#')
            $banner .= '<a href="'.$row['bn_url'].'">';
        else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
            $banner .= '<a href="/shop/bannerhit.php?bn_id='.$row['bn_id'].'&amp;url='.urlencode($row['bn_url']).'"'.$bn_new_win.'>';
        }
        echo $banner.'<div class=popbox></div>';
        if($banner)
            echo '</a>'.PHP_EOL;
    }
	$imgggg = $row['bn_id'];
} 
?>
<?php if($imgggg){ ?>
<style>
#popup_zone1 { background:url("<?=G5_DATA_URL?>/banner/<?=$imgggg?>") center top no-repeat;height:95px;}
#popup_zone1 .popbox {
    position: relative;
    width: 1180px;
    height: 95px;
    margin: 0 auto;
}
</style>
<? } ?>