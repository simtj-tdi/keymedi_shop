<?php
/* 2021년 03월 03일 - ykchoi
 * 제약사 전용관 클래스
 * write - 등록
 * update - 수정
 * del - 삭제
 * attachfile - 첨부파일 등록/수정
 * set_flds - 필드 체크 및 value 값 정리
 * 차후 필드 추가시 $r_flds에 필드 추가 하면 바로 적용
 * */
class privateMall
{
    var $flds = "";
    var $r_flds = array("mall_name", "mall_word", "mall_ca_id", "mall_url","mall_link");
    public static $_table = "shop_private_mall";

    function write(){
        $_table = self::$_table ;

        $this->set_flds();


        if($_FILES['mall_logo']) {
            $mall_logofile = $this->attachfile($_FILES,'mall_logo');
        }

        if($_FILES['mall_banner']) {
            $mall_bannerfile = $this->attachfile($_FILES,'mall_banner');
        }

        $sql = "insert into {$_table} set $this->flds  
                mall_logo = '".$mall_logofile."',
                mall_banner = '".$mall_bannerfile."',
                mall_status = '".$_POST['mall_status']."',
                reg_dt = '".G5_TIME_YMDHIS."'";

        sql_query($sql);
    }

    function update(){
        $_table = self::$_table ;

        $_mall_data = $this->info($_POST['idx']);

        $this->set_flds();


        if($_POST['del_chk_logo']=="Y") {
            unlink(G5_DATA_PATH . '/file/private/' . $_mall_data['mall_logo']);
            sql_query("update {$_table} set mall_logo = '' where idx = '{$_POST['idx']}'");
        }

        if($_POST['del_chk_banner']=="Y"){
            unlink(G5_DATA_PATH . '/file/private/'.$_mall_data['mall_banner']);
            sql_query("update {$_table} set mall_banner = '' where idx = '{$_POST['idx']}'");
        }



        $sql = "update {$_table} set $this->flds
                mall_status = '".$_POST['mall_status']."'";

        if($_FILES['mall_logo']['name']) {

            $mall_logofile = $this->attachfile($_FILES,'mall_logo');
            $sql .= ",mall_logo = '".$mall_logofile."'";
        }

        if($_FILES['mall_banner']['name']) {

            $mall_bannerfile = $this->attachfile($_FILES,'mall_banner');
            $sql .= ",mall_banner = '".$mall_bannerfile."'";
        }


        $sql .= " where idx = '{$_POST['idx']}'";

        sql_query($sql);
    }

    function del(){
        $_table = self::$_table ;

        $_mall_data = $this->info($_POST['idx']);
        unlink(G5_DATA_PATH . '/file/private/'.$_mall_data['mall_logo']);
        unlink(G5_DATA_PATH . '/file/private/'.$_mall_data['mall_banner']);

        $sql = "delete from {$_table} where idx = '{$_POST['idx']}'";
        sql_query($sql);
    }


    function multidel(){
        $_table = self::$_table ;

        $_del_count = count($_POST[chk]);

        if($_del_count > 0) {
            for($i=0;$i<$_del_count;$i++) {
                $_idx = $_POST['chk'][$i];
                $_brand_data = $this->info($_idx);
                unlink(G5_DATA_PATH . '/file/private/'.$_brand_data['mall_logo']);
                unlink(G5_DATA_PATH . '/file/private/'.$_brand_data['mall_banner']);;

                $sql = "delete from {$_table} where idx = '{$_idx}'";
                sql_query($sql);
            }
        }
    }

    function info($idx){
        $_table = self::$_table ;

        $sql = "select * from {$_table} where idx = '$idx'";
        $row = sql_fetch($sql);

        return $row;
    }

    function set_flds(){

        $flds = array();
        foreach ($this->r_flds as $v){
            $_T_POST = trim($_POST[$v]);
            $flds[] = "$v = '{$_T_POST}'";
        }
        $this->flds .= implode(",", $flds).",";
    }


    function attachfile($_FILES,$_NAME){

        $ext_str = "hwp,xls,doc,xlsx,docx,pdf,jpg,gif,png,txt,ppt,pptx";
        $allowed_extensions = explode(',', $ext_str);
        $max_file_size = 5242880;

        $ext_exp = explode('.', $_FILES[$_NAME]['name']);
        $ext = $ext_exp[1];

        // 확장자 체크
        if(!in_array($ext, $allowed_extensions)) {
            echo "업로드할 수 없는 확장자 입니다1.";
        }

        // 파일 크기 체크
        if($_FILES[$_NAME]['size'] >= $max_file_size) {
            echo "5MB 까지만 업로드 가능합니다.";
        }

        $path = md5(microtime()) . '.' . $ext;

        $filename = iconv("UTF-8", "EUC-KR",$_FILES[$_NAME]['name']);
        $folder = G5_DATA_PATH . '/file/private/'.$path;

        if(move_uploaded_file($_FILES[$_NAME]['tmp_name'],$folder)){
            return $path;
        }
    }
}