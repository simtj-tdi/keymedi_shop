<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/class.private.php');

$private = new privateMall;

switch($_POST['mode']){
    case "write" :
        $private->write();
        alert('등록되었습니다.','/adm/private_mall.php');
        break;

    case "update" :
        $private->update();
        alert('수정되었습니다.','/adm/private_mall_write.php?idx='.$_POST['idx']);
        break;

    case "del" :
        $private->del();
        alert('삭제되었습니다.','/adm/private_mall.php');
        break ;

    case "multidel" :
        $private->multidel();
        alert('삭제되었습니다.','/adm/private_mall.php');
        break ;
}
