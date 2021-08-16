<div id="smb_my">
	<div id="sub_top_new_menu"> 
		<span><a href="/shop/mypage.php">마이페이지</a></span>
		<span><a href="/shop/orderinquiry.php">주문내역</a></span>
		<span><a href="/shop/takeback.php">반품신청</a></span>
		<span><a href="/shop/wishlist.php">위시리스트</a></span>
		<span><a href="/shop/reorder.php">주문상품 재주문</a></span>
		<span><a href="/shop/coupon.php">쿠폰/<?=$point_txt?></a></span>
		<span><a href="/shop/mylist.php">문의내역</a></span>
		<? if($member[mb_v] == "4"){?>
		<span class="ov"><a href="/bbs/content.php?co_id=0902">정보관리</a></span>
		<?}else{?>
		<span class="ov"><a href="<?=$member_confirm_link?>/bbs/member_confirm.php?url=register_form.php">정보관리</a></span>
		<? } ?>
	</div>
	<form name="nomember_form" id="nomember_form" action="/bbs/memberg_up.php" onsubmit="return nomember_check(this);" method="POST" autocomplete="off">
	<section id="smb_my_ov">
        <h2>회원정보 개요</h2>

         <dl>
            <dt style="height:25px;">공급사코드</dt>
            <dd style="height:25px;"><?=$member['mb_name']?></dd> 
			<dt style="height:25px;">업체명</dt>
            <dd style="height:25px;"><?=$member['mb_nick']?></dd> 
			<dt style="height:25px;"></dt>
            <dd style="height:25px;"></dd> 

			<dt style="height:25px;">담당자</dt>
            <dd style="height:25px;"><input type="text" name="mb_3" value="<?php echo $member['mb_3'] ?>" id="mb_3" class="frm_input required" required size="15" maxlength="20"></dd> 
			<dt style="height:25px;">담당자SMS</dt>
            <dd style="height:25px;"><input type="text" name="mb_hp" value="<?php echo $member['mb_hp'] ?>" id="mb_hp" class="frm_input required" required size="15" maxlength="20"></dd> 
			<dt style="height:25px;">담당자E-MAIL</dt>
            <dd style="height:25px;"><input type="text" name="mb_email" value="<?php echo $member['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="15"></dd> 

			<dt style="height:25px;">비밀번호</dt>
            <dd style="height:25px;"><input type="password" name="mb_password" id="mb_password"  class="frm_input" size="15" maxlength="20"></dd> 
			<dt style="height:25px;">비밀번호확인</dt>
            <dd style="height:25px;"><input type="password" name="mb_password_re" id="mb_password_re"  class="frm_input" size="15" maxlength="20"></dd> 
			<dt style="height:25px;"></dt>
            <dd style="height:25px;"></dd> 
        </dl>
    </section>

	<div id="sod_ws_act">
        <button type="submit" class="btn02" >수정하기</button> 
    </div>
	</form>
</div>

<script>
function nomember_check(f){
	if( f.mb_password.value != ""){
		if(f.mb_password.value != f.mb_password_re.value){
			alert("비밀번호가 일치하지 않습니다.");
			f.mb_password.focus();
			return false;
		}
	}
}
</script>