<?php
$sub_menu = '300001';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

include_once(G5_LIB_PATH.'/class.private.php');
include_once(G5_LIB_PATH.'/class.resize.php');

$private = new privateMall;

auth_check($auth[$sub_menu], "w");

$_pc_size_array = array(
    "logo_w" => 150,
    "logo_h" => 60,
    "banner_w" => 1180,
    "banner_h" => 220,
);

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
$sql .= " order by ca_id , ca_order";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].' ('.$row['ca_id'].')</option>'.PHP_EOL;
}


if($idx) {
    $g5['title'] = '전용관페이지 관리 수정';
    $mode="update";
    $btn = "수정";
    $_data = $private->info($idx);
}else{
    $mode="write";
    $btn = "등록";
    $g5['title'] = '전용관페이지 관리 등록';
}

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frmfaqform" action="./private_mall_process.php" onsubmit="return frmform_check(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="mode" value="<?php echo $mode; ?>">
    <input type="hidden" name="idx" value="<?php echo $idx; ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="fa_order">제약사</label></th>
                <td>
                    <input type="text" name="mall_name" value="<?php echo $_data['mall_name']; ?>" id="mall_name" class="frm_input" size="80">
                </td>
            </tr>
            <tr>
                <th scope="row">제약사로고</th>
                <td>
                    <?
                    if($_data['mall_logo']) {
                        $output = image_resize_return(G5_DATA_PATH . '/file/private/'.$_data['mall_logo'],'/data/file/private/'.$_data['mall_logo'], $_pc_size_array['logo_w'], $_pc_size_array['logo_h']);
                        if ($output) {
                            echo $output."&nbsp;&nbsp;<input type='checkbox' name='del_chk_logo' value='Y' id='del_chk_logo'>&nbsp;&nbsp;삭제";
                            echo "<br><br>";
                        }
                    }
                    echo "<input type='file' name='mall_logo' id='mall_logo' class='frm_input' size='50' style='width: 40%;'/>&nbsp;&nbsp;<strong>PC</strong> : ".$_pc_size_array['logo_w']." * ".$_pc_size_array['logo_h'];

                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row">제약사슬로건</th>
                <td>
                    <input type="text" name="mall_word" value="<?php echo $_data['mall_word']; ?>" id="mall_word" class="frm_input" size="80">
                </td>
            </tr>
            <tr>
                <th scope="row">배너링크</th>
                <td>
                    <input type="text" name="mall_link" value="<?php echo $_data['mall_link']; ?>" id="mall_link" class="frm_input" size="80">
                </td>
            </tr>
            <tr>
                <th scope="row">배너</th>
                <td>
                    <?
                    if($_data['mall_banner']) {
                        $output = image_resize_return(G5_DATA_PATH . '/file/private/'.$_data['mall_banner'],'/data/file/private/'.$_data['mall_banner'], $_pc_size_array['bannerw'], $_pc_size_array['banner_h']);
                        if ($output) {
                            echo $output."&nbsp;&nbsp;<input type='checkbox' name='del_chk_banner' value='Y' id='del_chk_banner'>&nbsp;&nbsp;삭제";
                            echo "<br><br>";
                        }
                    }
                    echo "<input type='file' name='mall_banner' id='mall_banner' class='frm_input' size='50' style='width: 40%;'/>&nbsp;&nbsp;<strong>PC</strong> : ".$_pc_size_array['banner_w']." * ".$_pc_size_array['banner_h'];

                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row">링크분류코드</th>
                <td>
                    <select name="mall_ca_id" id="mall_ca_id" style="width:260px;">
                        <?php echo conv_selected_option($ca_list, $_data['mall_ca_id']); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">상태</th>
                <td>
                    <input type="radio" name="mall_status" value="Y" <?if($_data['mall_status']=="Y"){echo "checked";}?>>&nbsp;노출&nbsp;&nbsp;
                    <input type="radio" name="mall_status" value="N" <?if($_data['mall_status']=="N"){echo "checked";}?>>&nbsp;미노출
                </td>
            </tr>

            </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인" class="btn_submit" accesskey="s">
        <a href="./private_mall.php?fm_id=<?php echo $fm_id; ?>">목록</a>
    </div>

</form>

<script>
    function frmform_check(f)
    {


        return true;
    }

    // document.getElementById('fa_order').focus(); 포커스 해제
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
