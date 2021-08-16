<?php
include_once('./_common.php');


$g5['title'] = '엑셀파일로 상품 일괄 등록';
include_once(G5_PATH.'/head.sub.php');
?>

    <div class="new_win">


        <form name="fitemexcel" method="post" action="./excell_upload_goods.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">

            <div id="excelfile_upload">
                <label for="excel_new_file">파일선택</label>
                <input type="file" name="excel_new_file" id="excel_new_file">
            </div>

            <div class="btn_confirm01 btn_confirm">
                <input type="submit" value="상품 엑셀파일 등록" class="btn_submit">
                <button type="button" onclick="window.close();">닫기</button>
            </div>

        </form>

    </div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>