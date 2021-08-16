<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$g5['title'] = '엑셀파일로 포인트 일괄지급';
include_once(G5_PATH.'/head.sub.php');
 
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>
            엑셀파일을 이용하여 포인트를 일괄지급 할 수 있습니다.<br>
            형식은 <strong>포인트 일괄지급용 엑셀파일</strong>을 다운로드하여 정보를 입력하시면 됩니다.<br>
            수정 완료 후 엑셀파일을 업로드하시면 포인트가 일괄지급됩니다.<br>
            엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
        </p>

        <p>
            <a href="<?php echo G5_URL; ?>/<?php echo G5_LIB_DIR; ?>/Excel/pointexcel.xls">포인트 일괄지급용 엑셀파일 다운로드</a>
        </p>
    </div>

    <form name="fitemexcel" method="post" action="./pointexcel_update.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">

    <div id="excelfile_upload">
        <label for="excelfile">파일선택</label>
        <input type="file" name="excelfile" id="excelfile">
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="엑셀파일 등록" class="btn_submit">
        <button type="button" onclick="window.close();">닫기</button>
    </div>

    </form>

</div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>