<?php
include_once('./_common.php');

if ($w != 'u') {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if($w == 'u') $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';

 
$mb_dir ='/data/was/portal/data/member/'.substr($mb_id,0,2);


$msg2 = "";

// 아이콘 업로드
$mb_icon2 = '';
if (isset($_FILES['mb_icon2']) && is_uploaded_file($_FILES['mb_icon2']['tmp_name'])) {
	 
    if (preg_match("/(\.gif|jpg|png|bmp|JPG|PNG|pdf)$/i", $_FILES['mb_icon2']['name'])) {
 
        // 아이콘 용량이 설정값보다 이하만 업로드 가능
        if ($_FILES['mb_icon2']['size'] <= $config['cf_member_icon_size']) {

			$fiel_deldel = " file_date = now() , ";		 

            @mkdir($mb_dir, G5_DIR_PERMISSION);
            @chmod($mb_dir, G5_DIR_PERMISSION);
			
			if (preg_match("/(\.gif|jpg|png|bmp|JPG|PNG)$/i", $_FILES['mb_icon2']['name'])) {
				$dest_path = $mb_dir.'/'.$mb_id."_saup";
				move_uploaded_file($_FILES['mb_icon2']['tmp_name'], $dest_path);
				chmod($dest_path, G5_FILE_PERMISSION);
			}
			if (preg_match("/(\.pdf)$/i", $_FILES['mb_icon2']['name'])) {
				$dest_path = $mb_dir.'/'.$mb_id."_saup.pdf";
				move_uploaded_file($_FILES['mb_icon2']['tmp_name'], $dest_path);
				chmod($dest_path, G5_FILE_PERMISSION);
			}
             
        } else {
            $msg2 .= '사업자등록증을 '.number_format($config['cf_member_icon_size']).'바이트 이하로 업로드 해주십시오.';
        }

    } else {
        $msg2 .= '이미지 혹은 PDF 파일만 첨부할 수 있습니다.';
    }
}
  
$sql = " update {$g5['member_table']}
                set mb_5 = '{$mb_5}',
                    mb_6 = '{$mb_6}',
                    mb_7 = '{$mb_7}',
                    mb_8 = '{$mb_8}',
                    
					mb_15 = '{$mb_15}', 
					mb_17 = '{$mb_17}',		
					mb_18 = '{$mb_18}',		
					
					mb_agree_date = '{$mb_agree_date}',		
					mb_agree_who = '{$mb_agree_who}',		

					mb_shop = '2',
					mb_agree_ck = '1',
					{$fiel_deldel}

					mb_email = '{$mb_email}' 

              where mb_id = '$mb_id' ";
			  //echo $sql;exit;
    sql_query($sql); 

	alert("정보가 수정되었습니다.","/");
?>