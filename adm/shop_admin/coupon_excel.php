<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$g5['title'] = '엑셀파일로 쿠폰 일괄 등록';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>
            엑셀파일을 이용하여 쿠폰을 일괄 등록할 수 있습니다.<br>
            형식은 <strong>쿠폰일괄등록용 엑셀파일</strong>을 다운로드하여 쿠폰 정보를 입력하시면 됩니다.<br>
            수정 완료 후 엑셀파일을 업로드하시면 쿠폰이 일괄 수정됩니다.<br>
            엑셀파일을 다른이름저장 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
        </p>

        <p>
           <a href="<?php echo G5_URL; ?>/<?php echo G5_LIB_DIR; ?>/Excel/couponexcel.xls">쿠폰일괄 등록용 엑셀파일 다운로드</a>
        </p>
    </div>

    <form name="fitemexcel" method="post" action="./coupon_excel_up.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">

    <div id="excelfile_upload">
        <label for="excelfile">파일선택</label>
        <input type="file" name="excelfile" id="excelfile">
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="쿠폰 엑셀파일 등록" class="btn_submit">
        <button type="button" onclick="window.close();">닫기</button>
    </div>

    </form>

</div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>