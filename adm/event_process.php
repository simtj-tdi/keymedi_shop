<?
include_once('./_common.php');

$V = $_POST;
$F = $_FILES;
$_File_Dir = G5_DATA_PATH.'/event_banner';

function file_exec($fname){
    $file = @explode('.', $fname);
    $exec = $file[(count($file) - 1)];
    return $exec;
}

$arr = array_merge(range(65, 90), range(97, 122));
shuffle($arr);


switch ($V['type']){

    case "write":

        if($F['e_file']['tmp_name']){
            $ext		= file_exec($F['e_file']['name']);
            $file_name	= date("YmdHis").chr($arr[0]).'.'.$ext;
            @move_uploaded_file($F['e_file']['tmp_name'], $_File_Dir.'/'.$file_name);
        }

        $reg_date = date("Y-m-d H:i:s");
        $_e_idx_exp = explode("||",$V['e_idx']);
        $_e_cat_title = "";

        for($i=0 ; $i<$V['step_id']; $i++){
            if($V['subject'.$i]){
                $_e_cat_title .= $V['subject' . $i] . ",";
            }
        }

        for($j=1;$j<5;$j++){
            if($V['step_id'] >= $j) {
                if ($V['select_good_no' . $j]) {
                    ${"select_goods_no" . $j} = $V['select_good_no' . $j];
                } else {
                    ${"select_goods_no" . $j} = '';
                }
            }else{
                ${"select_goods_no" . $j} = '';
            }
        }

        $value = array(
            'e_idx'	    => $_e_idx_exp[0],
            'e_name'	=> $_e_idx_exp[1],
            'e_title'	=> $V['e_title'],
            'e_cat_title'=> $_e_cat_title,
            'e_goods1'	=> $select_goods_no1,
            'e_goods2'	=> $select_goods_no2,
            'e_goods3'	=> $select_goods_no3,
            'e_goods4'	=> $select_goods_no4,
            'e_view'    => $V['view_ck'],
            'e_file'    => $file_name,
            'e_date'	=> $reg_date
        );

        //입력 정보 정리
        $input = array();
        foreach($value as $key => $val){
            $input[] = $key . "='" . $val . "'";
        }

        $input = @implode(', ', $input);

        $result = sql_query("INSERT INTO {$g5['event_table']} SET ".$input);

        if($result){
            alert("이벤트 상품 등록이 완료되었습니다");
            goURL("event_goods.php");
        }else{
            alert("이벤트 상품 등록이 실패했습니다");
            goURL("event_form.php");
        }
        break;

    case "modify":

        if($F['e_file']['tmp_name']){
            $ext		= file_exec($F['e_file']['name']);
            $file_name	= date("YmdHis").chr($arr[0]).'.'.$ext;
            @move_uploaded_file($F['e_file']['tmp_name'], $_File_Dir.'/'.$file_name);
            @unlink($_File_Dir.'/'.$V['old_file']);
        }else{
            $file_name	= $V['old_file'];
        }

        $_e_idx_exp = explode("||",$V['e_idx']);
        $_e_cat_title = "";


        for($i=0 ; $i<=$V['step_id']; $i++){
            if($V['subject'.$i]){
                $_e_cat_title .= $V['subject' . $i] . ",";
            }
        }

        for($j=1;$j<5;$j++){
            if($V['step_id'] >= $j) {
                if ($V['select_good_no' . $j]) {
                    ${"select_goods_no" . $j} = $V['select_good_no' . $j];
                } else {
                    ${"select_goods_no" . $j} = '';
                }
            }else{
                ${"select_goods_no" . $j} = '';
            }
        }

        $value = array(
            'e_idx'	    => $_e_idx_exp[0],
            'e_name'	=> $_e_idx_exp[1],
            'e_title'	=> $V['e_title'],
            'e_cat_title'=> $_e_cat_title,
            'e_goods1'	=> $select_goods_no1,
            'e_goods2'	=> $select_goods_no2,
            'e_goods3'	=> $select_goods_no3,
            'e_goods4'	=> $select_goods_no4,
            'e_view'    => $V['view_ck'],
            'e_file'    => $file_name,
        );

        //입력 정보 정리
        $input = array();
        foreach($value as $key => $val){
            $input[] = $key . "='" . $val . "'";
        }

        $input = @implode(', ', $input);

        $result = sql_query("UPDATE {$g5['event_table']} SET ".$input." WHERE idx='".$V['idx']."'");

        if($result){
            alert("이벤트 상품 수정이 완료되었습니다");
            goURL("event_goods.php");
        }else{
            alert("이벤트 상품 수정에 실패했습니다");
            goURL("event_form.php");
        }

    break;

    case "del" :
    break;

    case "seldel" :
    break;
}
