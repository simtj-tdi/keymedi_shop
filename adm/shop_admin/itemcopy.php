<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '상품 복사';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="new_win">
    <h1>상품 복사</h1>
    <form name="fitemcopy">

    <div id="sit_copy">
        <label for="new_it_id">업체코드</label>
		<input type="hidden" name="new_it_id" value="<?php echo time(); ?>" id="new_it_id" />
        <input type="text" name="new_it_9" value="" id="new_it_9" class="frm_input" maxlength="20">
    </div>

	<div id="sit_copy">
        <label for="new_it_price">판매가격</label>
        <input type="text" name="new_it_price" value="" id="new_it_price" class="frm_input" maxlength="20">
    </div>

	<div id="sit_copy">
        <label for="new_it_stock_qty">재고수량</label>
        <input type="text" name="new_it_stock_qty" value="" id="new_it_stock_qty" class="frm_input" maxlength="20">
    </div>
	

    <div class="btn_confirm01 btn_confirm">
        <input type="button" value="복사하기" class="btn_submit" onclick="_copy('itemcopyupdate.php?it_id=<?php echo $it_id; ?>&amp;ca_id=<?php echo $ca_id; ?>');">
        <button type="button" onclick="self.close();">창닫기</button>
    </div>

    </form>
</div>

<script src="<?php echo G5_ADMIN_URL ?>/admin.js"></script>

<script>
// <![CDATA[
function _copy(link)
{
    var new_it_id = document.getElementById('new_it_id').value;
    var t_it_id = new_it_id.replace(/[A-Za-z0-9\-_]/g, "");
    if(t_it_id.length > 0) {
        alert("상품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
        return false;
    }
	
	var new_it_9 = document.getElementById('new_it_9').value;
	var it_9 = new_it_9.replace(/[A-Za-z0-9\-_]/g, "");
    if(it_9.length > 0) {
        alert("업체코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
        return false;
    }
	var new_it_price = document.getElementById('new_it_price').value;
	var it_price = new_it_price.replace(/[A-Za-z0-9\-_]/g, "");
    if(it_price.length > 0) {
        alert("판매가격는 숫자만 사용할 수 있습니다.");
        return false;
    }
	var new_it_stock_qty = document.getElementById('new_it_stock_qty').value;
	var it_stock_qty = new_it_stock_qty.replace(/[A-Za-z0-9\-_]/g, "");
    if(it_stock_qty.length > 0) {
        alert("재고수량는 숫자만 사용할 수 있습니다.");
        return false;
    }


    var token = get_ajax_token();
    if(!token) {
        alert("토큰 정보가 올바르지 않습니다.");
        return false;
    }
	 
	opener.parent.location.href = encodeURI(link+"&new_it_id="+new_it_id+"&new_it_9="+new_it_9+"&new_it_price="+new_it_price+"&new_it_stock_qty="+new_it_stock_qty+"&token="+token);
    self.close();
}
// ]]>
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>