<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
/*
if (!defined('_SHOP_')) {
    $pop_division = 'comm';
} else {
    $pop_division = 'shop';
}*/
if(SITE_CODE == "mall"){
	$pop_division = 'comm';
}else{
	$pop_division = 'shop';
}

$sql = " select * from {$g5['new_win_table']}
          where '".G5_TIME_YMDHIS."' between nw_begin_time and nw_end_time
            and nw_device IN ( 'both', 'pc' ) and nw_division IN ( 'both', '".$pop_division."' ) and ( nw_ids = '' or nw_ids like '%".$member['mb_id']."&&%' )
          order by nw_id asc ";
$result = sql_query($sql, false);
?>
<script>

/**
*   210304 - jinam23 - 동영상배너 로그 저장 처리. 
*   Params  :   type(1 click, 2 buy )
                mv_url 
                mode( 1 href, 2 _new )
*/
function mov_popup_banner_log(type, move_url, mode)
{
    var postUrl = '/bbs/ajax.mov_popup_banner_log_proc.php';
    var obj = new Object();
    obj.mb_id = '<?=$member['mb_id']?>';
    if( obj.mb_id=='' ) {
        console.log("needs login !!!") ;
        window.document.location.href = 'bbs/login.php' ;
        return false ;
    }
    obj.type = type ;

    $.ajax({
        type:'POST',
        url:postUrl,
        data:obj,
        success: function(data) {
            //console.log(data) ;
            if( mode==1 ) {
                window.document.location.href = move_url ;
            } else {
                window.open(move_url, "_blank") ;
            }
        }
    });
}

</script>
<!-- 팝업레이어 시작 { -->
<div id="hd_pop">
    <h2>팝업레이어 알림</h2>

<?php
for ($i=0; $nw=sql_fetch_array($result); $i++)
{
    // 이미 체크 되었다면 Continue
    if ($_COOKIE["hd_pops_{$nw['nw_id']}"])
        continue;

	$dateis = ($nw['nw_disable_hours'] / 24 );
?>

    <div id="hd_pops_<?php echo $nw['nw_id'] ?>" class="hd_pops" style="top:<?php echo $nw['nw_top']?>px;left:<?php echo $nw['nw_left']?>px">
        <div class="hd_pops_con" style="width:<?php echo $nw['nw_width'] ?>px;height:<?php echo $nw['nw_height'] ?>px">
            <?php 
            //  210305 - jinam23 
            //    echo conv_content($nw['nw_content'], 1); 
                echo $nw['nw_content']; 
            ?>
        </div>
        <div class="hd_pops_footer">
        <?  
            $non_view_data = array(110, 111, 114, 115) ;
            if( array_search($nw['nw_id'], $non_view_data)===false ) {
        ?>
            <button class="hd_pops_reject hd_pops_<?php echo $nw['nw_id']; ?> <?php echo $nw['nw_disable_hours']; ?>"><strong><?php echo $dateis; ?></strong>일 동안 다시 열람하지 않습니다.</button>
        <?  }   ?>
            <button class="hd_pops_close hd_pops_<?php echo $nw['nw_id']; ?>">닫기</button>
        </div>
    </div>
<?php }
if ($i == 0) echo '<span class="sound_only">팝업레이어 알림이 없습니다.</span>';
?>
</div>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#"+id[1]).css("display", "none");
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
    });
    $('.hd_pops_close').click(function() {
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
    });
    $("#hd").css("z-index", 1000);
});
</script>
<!-- } 팝업레이어 끝 -->