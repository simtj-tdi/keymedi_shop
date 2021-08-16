<?php
$sub_menu = "300900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if($type=="modify") {
    $sql_common = " from {$g5['event_table']} a ";
    $sql_search = " where a.idx = '$idx' ";

    $_sql = " select * {$sql_common} {$sql_search}  ";
    $_row = sql_fetch($_sql);

    $_view[$_row['e_view']] = "checked" ;

    $step = count(explode(",",$_row['e_cat_title']));
    $step_res = $step-1;
    $cat_title = explode(",",$_row['e_cat_title']);
}

$g5['title'] = $type == "write" ? '이벤트상품등록' : '이벤트상품수정' ;
$_btn_value = $type == "write" ? '등록' : '수정' ;

include_once('./admin.head.php');

$b_data = array();

$b_sql = " select bn_id,bn_alt from {$g5['g5_shop_banner_table']} where bn_position='공통메인_이벤트' and bn_end_time > now() and bn_device='pc' order by bn_id desc ";
$b_res = sql_query($b_sql);

for ($i=0; $row=sql_fetch_array($b_res); $i++) {
    $b_data[] = $row;
}


?>
<style>
    

</style>
<form action="./event_process.php"  method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return fitemformcheck(this)">
<input type="hidden" name="mode" value="<?php echo $sst ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">


<input type="hidden" name="idx" value="<?php echo $idx ?>">
<input type="hidden" name="old_file" value="<?php echo $_row[e_file] ?>">
<input type="text" name="select_good_no1" id="select_good_no1" value="<?=$_row['e_goods1']?>">
<input type="text" name="select_good_no2" id="select_good_no2" value="<?=$_row['e_goods2']?>">
<input type="text" name="select_good_no3" id="select_good_no3" value="<?=$_row['e_goods3']?>">
<input type="text" name="select_good_no4" id="select_good_no4" value="<?=$_row['e_goods4']?>">
<table class="table table-bordered">
    <thead><caption> 글쓰기 </caption></thead>
    <tbody>
    <tr>
        <th>제목</th>
        <td><input name='e_title' type='text' value='<?=$_row['e_title']?>' placeholder='제목을 입력하세요' class='col-sm-6 form-control' required/><span style="color: red;font-weight: bold;">* 리스트에서 보여질 제목</span></td>
    </tr>
    <tr>
        <th  width="20%">노출유무</th>
        <td>
            <input type="radio" name="view_ck" id="view_ck1" value="1" <?if(!$_view['1']){echo "checked";}else{echo $_view['1'];}?>> 노출
            <input type="radio" name="view_ck" id="view_ck2" value="2" <?=$_view['2']?>> 비노출
        </td>
    </tr>
    <tr>
        <th  width="20%">이벤트 선택<?=$row['e_idx']?></th>
        <td>
            <select name="e_idx" class="form-control form-control-sm  col-sm-3" required>
                <option value="">:::: 선택해 주세요 ::::</option>
                <?
                foreach ($b_data as $key => $value){
                    $selected = $_row['e_idx'] == $value[bn_id] ? "selected" : "" ;
                    echo "<option value='".$value[bn_id]."||".$value[bn_alt]."'".$selected.">".$value[bn_alt]."</option>";
                }
                ?>
            </select>

        </td>
    </tr>

    <tr>
        <th>이벤트 단수</th>
        <td>
            <select name="step_id" id="step_id" class="form-control form-control-sm  col-sm-3" required>
                <option value="">:::: 선택해 주세요 ::::</option>
                <?
                for($i=1;$i<5;$i++){
                    $selected = $i == $step_res ? "selected" : "" ;
                    echo "<option value='".$i."'".$selected.">".$i."단</option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <?
    for($j=1;$j<5;$j++){
        if($type=="modify"){
            if($step_res > 0){
                $subject_display = $j <= $step_res ? "table-row" : "none" ;
            }
        }else{
            $subject_display = $j == 1 ? "table-row" : "none" ;
        }

        $e = $j-1;

        echo  "<tr id='choice_subject".$j."' style='display:".$subject_display.";'>
                <th>제목</th>
                <td><input id='subject".$j."' name='subject".$j."' type='text' value='".$cat_title[$e]."' placeholder='제목을 입력하세요' class='col-sm-6 form-control'/><span style='color: red;font-weight: bold;'>*이벤트 페이지에서 노출될 제목(단수)</span></td>
               </tr>";
    }

    for($i=1;$i<5;$i++){
        if($type=="modify"){
            if($step_res > 0){
                $goods_display = $i <= $step_res ? "table-row" : "none" ;
            }
        }else {
            $goods_display = $i == 1 ? "table-row" : "none";
        }

        echo "<tr id='choice_goods".$i."' style='display:".$goods_display.";'>
                <th>이벤트 제품 등록시 선택해 주세요</th>
                <td>
                <a href=\"javascript:openModal('modal1','".$i."');\" class=\"btn btn-success btn-sm\" id='btn_good".$i."'>선택</a>
                <div id='view_goods".$i."'>";
                if($_row['e_goods'.$i]){
                    $goods_exp = explode(",",$_row['e_goods'.$i]);
                    for($g=0;$g<count($goods_exp)-1;$g++) {
                        $_g_query = "select it_id,it_name from {$g5['g5_shop_item_table']} where it_id = '$goods_exp[$g]' limit 1";
                        $_g_row = sql_fetch($_g_query);
                        $view_img = get_it_image($_g_row['it_id'], 50, 50);
                        echo "<p id=img".$_g_row['it_id'].">".$view_img."&nbsp;&nbsp;:&nbsp;&nbsp;".$_g_row[it_name]."&nbsp;&nbsp;<a href='#' onclick=\"remove_goods('".$_g_row['it_id']."');\"><i class='fa fa-times' aria-hidden='true' ></i></a></p>";
                    }
                }
        echo "</div>
                </td>
              </tr>";
    }
    ?>

    <tr>
        <th>상단배너</th>
        <td>
            <?
            if($type=="modify" && $_row[e_file]){
                $img = resize_image('/data/was/shop/data/event_banner/'.$_row[e_file], 500, 500); // 파일경로, 폭, 높이를 입력하세요
                ob_start();
                switch($img[1]){
                    case "png":
                        imagepng($img[0]);
                        break;
                    case "jpeg":
                    case "jpg":
                        imagejpeg($img[0]);
                        break;
                    case "gif":
                        imagegif($img[0]);
                        break;
                    default:
                        imagejpeg($img[0]);
                        break;
                }
                $output = base64_encode(ob_get_contents());
                ob_end_clean();

                echo "<img src=\"data:image/".$img[1].";base64,".$output."\"/><br>";
            }
            ?>
            <input type='file' name='e_file' id='e_file' class='col-sm-6 form-control-sm'/>
        </td>
    </tr>

    </tbody>


</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="text-align: center;border: white;">
            <input type="submit"  class="btn btn-primary btn-sm" value="<?=$_btn_value?>"/>
            <input type="button"  class="btn btn-danger btn-sm" value="취소" onclick="javascript:history.back();"/>
        </td>
    </tr>
</table>
</form>

<div id="modal"></div>
<div class="modal-con modal1">
    <form id="frm_layer" class="form-inline" role="form">
    <input type="hidden" name="good_id" id="good_id">
    <a href="javascript:;" class="close">X</a>
    <p class="title" style="width: 100%;">검색&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="search_good" onFocus="this.value=''" class="form-control">&nbsp;&nbsp;<input type="button" class="btn btn-info btn-sm" value="검색" onclick="search_ajax();"></p>
    <div class="con" id="search_res">
        검색한 상품명이 표시 됩니다.
    </div>
    </form>
</div>




<script>
    $(document).ready(function () {
       $('#step_id').on('change', function(){

           var step_no = parseInt(this.value);

           if(step_no < 1 || this.value == ''){
               alert("이벤트 단수를 1개 이상 선택해 주세요");
               return false;
           }

           for (i = 1; i <= 4; i++) {
               $("#choice_subject" + i).css("display", "none");
           }

            for (i = 1; i <= step_no; i++) {
                $("#choice_subject" + i).css("display", "table-row");
            }

           for (i = 1; i <= 4; i++) {
               $("#choice_goods" + i).css("display", "none");
           }

           for (i = 1; i <= step_no; i++) {
               $("#choice_goods" + i).css("display", "table-row");
           }

       });
   });

   function openModal(modalname,id){
       $("#modal").fadeIn(300);
       $("."+modalname).fadeIn(300);
       $('#good_id').val(id);
   }

   $("#modal, .close").on('click',function(){
       $("#modal").fadeOut(300);
       $(".modal-con").fadeOut(300);
   });

</script>
<?
$b_res = sql_query($b_sql);

for ($i=0; $row=sql_fetch_array($b_res); $i++) {
    $b_data[] = $row;
}

/*$query = "select it_id,it_name from {$g5['g5_shop_item_table']} order by it_id limit 10";
$res = sql_query($query);
for ($i=0; $row=sql_fetch_array($res); $i++) {

    $it_name = str_replace(".","",$row[it_name]);
    $it_name = str_replace("/","",$it_name);
    $it_name = str_replace("-","",$it_name);
    $it_name = str_replace("(","",$it_name);
    $it_name = str_replace(")","",$it_name);
    $it_name = str_replace(" ","",$it_name);
    $it_name = str_replace("*","",$it_name);

    $_data .= "{'label':'".$it_name."','value':'".$row[it_id]."'},";

//    $title .= "'".$data['title']."',";
//    $content .= "'".$data['content']."',";
}*/
?>

<script>
    /*var availableTags = [
        <?=$_data?>
    ];

    $( "#search_good" ).autocomplete({
        source: availableTags,
        select: function(event, ui) {

            event.preventDefault() // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label);
            modal_data(ui.item.label);

        },

        focus: function(event, ui) {
            return false;
            //event.preventDefault();\
        }
    });
    $('#modal').modal('show');
    $( "#search_good" ).autocomplete( "option", "appendTo", ".eventInsForm" );

    function modal_data(no){
        //console.log($(this).val());
        $.get("./event_ajax.php", {mode:"search", wd:no}, function(data){
            // data = data.replace(/(?:\r\n|\r|\n)/g, '<br />');
            $("#search_res").val(data);
        });
    }*/


    function search_ajax(){
        var wd = $('#search_good').val();
        $.get("./event_ajax.php", {mode:"search", wd:wd}, function(data){
            $("#search_res").html(data);
        });
    }

    function append_goods(gname,gsno,gimg){
        var g_insert_no = "";
        var g_id = $('#good_id').val();
        var g_no = $('#select_good_no'+g_id).val();
        console.log(g_no);
        if(g_no){
            g_no_array = g_no.split(",");
            if(g_no_array.length < 6 && g_no_array.length > 0){
                g_insert_no = g_no + gsno + ',' ;
            }else{
                if(g_no_array.length > 4){
                    $('#btn_good'+g_id).prop('disabled', true);
                }
                alert('1개의 카테고리에 최하 1개 최대 5개 제품만 등록이 가능합니다.');
                return false;
            }
        }else{
            g_insert_no = gsno+',';
        }
        $('#view_goods'+g_id).append('<p id="img'+gsno+'" data-catno='+g_id+'>'+gimg+'&nbsp;&nbsp;:&nbsp;&nbsp;'+ gname +'&nbsp;&nbsp;<a href="#" onclick="remove_goods('+gsno+');"><i class="fa fa-times" aria-hidden="true" ></i></a></p>');
        $('#select_good_no'+g_id).val(g_insert_no);
    }

    function remove_goods(gno){
        var g_id = $('#img'+gno).data('catno');
        var g_no = $('#select_good_no'+g_id).val();
        if(g_no){
            if (g_no.indexOf(gno) != -1) {

                g_no = g_no.replace(gno+',',""); //변경작업
                console.log(g_no);
            }else {
                alert("Not Found!!");
            }
        }
        $('#select_good_no'+g_id).val(g_no);
        $('#img'+gno).remove();
    }
</script>

<?php
include_once('./admin.tail.php');
?>
