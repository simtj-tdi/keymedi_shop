<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<?php
include_once('./_common.php');
include_once(G5_PATH . '/head.sub.php');

$_f_title = $_GET['idx'] != '' ? "배송지 수정" : "신규 배송지 등록";
$_b_title = $_GET['idx'] != '' ? "수정" : "등록";
$_mode = $_GET['idx'] != '' ? "modify" : "write";
$_link = "location.href='new_orderaddress.php';";

if ($_GET['idx']) {
    $_row_data = sql_fetch("select * from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' and ad_id='{$_GET['idx']}'");
    $_checked = $_row_data['ad_default'] == 1 ? "checked" : "";
}
?>

<style>
    input[type=submit] {
        border: 1px solid #1f326a;
        color: #fff;
        background: #1f326a;
        margin-top: 10px;
        padding: 10px 20px;
        border-radius: 3px;
        position: absolute;
        left: 45%;
        transform: translateX(-50%);
    }

    input[type=submit]:hover {
        background: #0a6fce;
    }

    #test_btn2 {
        border: 1px solid #3a3a3a;
        color: #fff;
        background: #3a3a3a;
        margin-top: 10px;
        padding: 10px 20px;
        border-radius: 3px;
        position: absolute;
        left: 55%;
        transform: translateX(-50%);
    }

    #test_btn2:hover {
        background: #6e7f88;
    }

    .warning_text {
        font-size: 15px;
        color: #1f326a;
        font-weight: bold;
        padding-left:20px;
        padding-bottom:10px;
    }
</style>


<section id="sod_frm_taker">
    <h2 style="font-size:18px;margin-top:20px;margin-bottom:20px;padding-left: 15px;">- <?= $_f_title ?></h2>
    <span class="warning_text">※ 섬지역, 도서 산간지역으로는 배송지 변경이 불가합니다.</span>
    <div class="tbl_frm01 tbl_wrap">
        <form name="addressForm" id="addressForm" method="post" autocomplete="off">
            <input type="hidden" name="mode" id="mode" value="<?= $_mode ?>">
            <input type="hidden" name="ad_id" id="ad_id" value="<?= $_row_data['ad_id'] ?>">
            <table>
                <tbody>
                <?php if ($is_member) { ?>
                    <tr>
                        <th scope="row"><label for="ad_subject">배송지명</label></th>
                        <td>
                            <input type="text" name="ad_subject" id="ad_subject" class="frm_input"
                                   value="<?= $_row_data['ad_subject'] ?>" maxlength="20">&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="ad_default" id="ad_default" value="1" <?= $_checked ?>>&nbsp;&nbsp;<label
                                    for="ad_default">기본배송지로 설정</label>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><label for="od_b_name">이름<strong class="sound_only"> 필수</strong></label></th>
                    <td><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" value="<?= $_row_data['ad_name'] ?>" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_b_tel">전화번호<strong class="sound_only"> 필수</strong></label></th>
                    <td><input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input required" value="<?= $_row_data['ad_tel'] ?>" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_b_hp">핸드폰</label></th>
                    <td><input type="text" name="od_b_hp" id="od_b_hp" class="frm_input required" value="<?= $_row_data['ad_hp'] ?>" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row">주소</th>
                    <td id="sod_frm_addr">
                        <label for="od_b_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                        <input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" value="<?= $_row_data['ad_zip1'] . $_row_data['ad_zip2'] ?>" maxlength="6" readonly>
                        <button type="button" class="btn_frmline" onclick="win_zip('addressForm', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">
                            주소 검색
                        </button>
                        <br>
                        <input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" value="<?= $_row_data['ad_addr1'] ?>" size="30" readonly>
                        <label for="od_b_addr1">기본주소<strong class="sound_only"> 필수</strong></label><br>
                        <input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address" size="30" value="<?= $_row_data['ad_addr2'] ?>">
                        <label for="od_b_addr2">상세주소</label>
                        <br>
                        <input type="hidden" name="od_b_addr_jibeon" value="">
                    </td>
                </tr>
                </tbody>
        </form>
        </table>

        <div id="btn_group">
            <input type="submit" name="act_button" value="<?= $_b_title ?>" id="action_btn">&nbsp;&nbsp;
            <button type="button" id="test_btn2" onclick="list_go();">취소</button>
        </div>

    </div>
</section>

<script>
    $(document).ready(function () {
        $('#action_btn').click(function () {

            if($('#od_b_name').val()==""){
                alert('이름을 입력해 주세요');
                $('#od_b_name').focus();
                return false;
            }

            if($('#od_b_tel').val()==""){
                alert('전화번호를 입력해 주세요');
                $('#od_b_tel').focus();
                return false;
            }

            if($('#od_b_hp').val()==""){
                alert('핸드폰 번호를 입력해 주세요');
                $('#od_b_hp').focus();
                return false;
            }

            if($('#od_b_zip').val()==""){
                alert('주소를 입력해 주세요');
                $('#od_b_zip').focus();
                return false;
            }

            if($('#od_b_addr1').val()==""){
                alert('주소를 입력해 주세요');
                $('#od_b_addr1').focus();
                return false;
            }

            if($('#od_b_addr2').val()==""){
                alert('주소를 입력해 주세요');
                $('#od_b_addr2').focus();
                return false;
            }

            $.ajax({
                url: './address_process.php',
                type: "POST",
                dataType: "json",
                data: $('#addressForm').serialize(),
                success: function (data) {
                    var r_type = data['r_type'];
                    var r_status = data['r_status'];
                    var r_txt = "";

                    if (r_type == "write") {
                        r_txt = "등록";
                    } else {
                        r_txt = "수정";
                    }

                    if (r_status == "success") {
                        alert('배송지 ' + r_txt + '이 완료되었습니다.');
                    } else {
                        alert('배송지 ' + r_txt + '이 실패되었습니다.');
                    }
                    <?=$_link?>
                }
            });
        });
    });

    function list_go() {
        location.href = './new_orderaddress.php';
    }
</script>
