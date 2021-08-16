<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<style>
    .list_div {
        width: 1180px;
        /*height: 70px;*/
        /*border: 1px solid #e1e1e1;*/
        position: relative;
        min-height: 1px;
        margin: 0 auto;
        margin-top: 10px;
        margin-bottom: -40px;

    }


</style>


<div class="list_div" id="list_div" style="display: none;">

    <?php
    $max_width = $max_height = 0;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {

        if($row['bn_id']){
            $_best_banner_count += 1;
        }

        if ($i==0) echo '<ul>'.PHP_EOL;
        //print_r2($row);
        // 테두리 있는지
        $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
        // 새창 띄우기인지
        $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

        $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
        if (file_exists($bimg))
        {

            $banner = '';
            $size = getimagesize($bimg);

            if($size[2] < 1 || $size[2] > 16)
                continue;

            if($max_width < $size[0])
                $max_width = $size[0];

            if($max_height < $size[1])
                $max_height = $size[1];

            echo '<li>'.PHP_EOL;
            if ($row['bn_url'][0] == '#')
                $banner .= '<a href="'.$row['bn_url'].'">';
            else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
                $banner .= '<a href="'.$row['bn_url'].'">';
            }
            echo $banner.'<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" alt="'.$row['bn_alt'].'" width="1180" '.$bn_border.'>';
            if($banner)
                echo '</a>'.PHP_EOL;
            echo '</li>'.PHP_EOL;
        }
    }
    if ($i>0) echo '</ul>'.PHP_EOL;
    ?>

</div>


<script>
    $( document ).ready(function() {
        var count = parseInt(<?=$_best_banner_count?>);
        if(count > 0) {
            $('#list_div').css("display", "block");
        }else{
            $('#list_div').css("display", "none");
        }
    });

</script>
