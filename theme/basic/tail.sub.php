<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<?php if ($is_admin == 'super') {  ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime()-$begin_time; ?><br></div> --><?php }  ?>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position", "relative");
        count = count - 1;
    });
});
</script>
<![endif]-->


<?php

if ($member['mb_where']=='산부인과 협동조합' && $member['mb_id'] && $_SERVER['PHP_SELF'] == '/index.php') {

    $agreeRes = sql_fetch("select count(*) as cnt from new_agree_ck where mb_id='".$member['mb_id']."'");

    //if ($agreeRes['cnt'] < 1 || $agreeRes['cnt']=='') {
    if (1==1) {
?>
        <style>
            .hd_pops_agree {
                position: absolute;
                z-index: 1002;
                background: #fff;
                top: 50%;
                margin-top: -137px;
                left: 50%;
                margin-left: -227px;
            }
            .agree_popup {
                width: 450px;
                height: 350px;
                margin: 0px auto;
                border: 2px solid #4176cb;
                border-radius: 5px;
            }
            .point_plus{  padding: 10px 20px 10px;
                text-align: center;
                font-size: 16px;
                line-height: 1.5;background: #1e75d6;
                color: #fff;}
            .point_plus b{font-weight: 800;color: #d5fd06;}
            .popup_head {
                padding: 20px;
                text-align: left;
                font-size: 14px;
                line-height: 1.5;
            }
            .popup_head b {
                color: #1f4788;
                font-size: 14px;
            }
            .popup_body {
                margin-top: 10px;
                padding-bottom: 10px;
                text-align: center;
            }
            .popup_body ul li {
                display: inline-block;
                text-align: center;
            }
            .popup_body ul li a {
                padding: 12px 40px;
                background: #1f4788;
                border-radius: 3px;
                color: #fff;
                font-weight: 700;
                margin-left: 10px;
                font-size: 12px;
            }
            .popup_body ul li a:hover {
                background: #4176cb;
            }
            .popup_body ul li label {
                font-weight: 700;
                font-size: 14px;
            }
            .popup_body ul li input {
                margin-left: 5px;
            }
            .popup_footer {
                margin-top: 20px;
                text-align: center;
                clear: both;
                border-top: 1px solid #eee;
                padding: 15px 20px 0px 20px;
            }
            .popup_footer button {
                background: #f7f7f7;
                padding: 10px 20px;
                border: 1px solid #777;
                border-radius: 5px;
            }
            .popup_footer .yes {
                background: #0f89ca;
                color: #fff;
                border-color: #0f89ca;
                padding: 10px 35px;
            }
            .agree_popup2{height:210px}
            .agree_popup2 .popup_head{text-align: center;}
            .agree_popup2 .popup_head b{font-size: 20px;color:#2074d6}
            .agree_popup2 .popup_head small{font-size: 12px; color:#999999}
            .agree_popup2 .popup_footer {
                margin-top: 0;}
            /* .gray{background: #000;  position: fixed; z-index: 1001;width:100%; height:100%; opacity: 0.5;} */
            .gray.off{display: none}
        </style>
        <div  class="hd_pops_agree" style="display:none;">
            <div class="hd_pops_con" id="hd_pops_agree_ck">
                <div class="agree_popup">
                    <div class="point_plus">이용 동의시 <b>10,000원 쿠폰</b>을 드립니다</div>
                    <div class="popup_head">
                        개정된 이용약관과 개인정보 수집 및 이용 동의는<b> “예＂</b>를, <br>
                        기존 약관과 개인정보 이용 동의는 <b>“아니오＂</b>를 눌러주세요. <br>
                        본 동의를 진행하지 않으신 회원님의  개인정보는 파기되어 <br>
                        <b>2021년 6월 25일부로 사용할 수 없습니다.</b>
                        <br><br>
                        <b>이후 산부인과몰 이용 시 신규가입의 절차가 필요합니다.</b>
                    </div>
                    <div class="popup_body">
                        <ul>
                            <li>
                                <a href="http://obgys.keymedi.com/bbs/content.php?co_id=provision" target="_blank">이용약관</a>
                            </li>
                            <li>
                                <a href="http://obgys.keymedi.com/bbs/content.php?co_id=privacy" target="_blank">개인정보이용 및 수집</a>

                            </li>
                        </ul>
                    </div>
                    <div class="popup_footer">
                        <button class="agree_close agree_not_confirm">아니요</button>
                        <button class="yes agree_confirm">예</button>
                    </div>
                </div>
            </div>



            <div class="hd_pops_con" id="hd_pops_confirm_ck" style="display: none;">
                <div class="agree_popup agree_popup2">
                    <div class="popup_head">
                        이용약관과 개인정보 수집 및 이용에 동의해 주셔서 감사합니다.<br>
                        <b>10,000원 쿠폰이 발행되었습니다.</b><br>
                        <small>5만원 이상 구매시 사용하실 수 있으며,<br>마이페이지에서 확인 가능합니다.</small>
                    </div>

                    <div class="popup_footer">
                        <button class="agree_close">확인</button>
                    </div>
                </div>
            </div>
        </div>


<!--<style type="text/css">
.hd_pops_agree {
    position: absolute;
    z-index: 1000;
    background: #fff;
    top: 50%;
    margin-top: -137px;
    left: 50%;
    margin-left: -227px;
}
.agree_popup {
    width: 450px;
    height: 240px;
    margin: 0px auto;
    border: 2px solid #4176cb;
}
.popup_head {
    padding: 30px 20px 20px;
    text-align: left;
    font-size: 14px;
    line-height: 1.5;
}
.popup_head b {
    color: #1f4788;
    font-size: 14px;
}
.popup_body {
    margin-top: 10px;
    padding-bottom: 20px;
}
.popup_body ul li {
    float: left;
    width: 48%;
    text-align: center;
}
.popup_body ul li a {
    padding: 5px 12px;
    background: #1f4788;
    border-radius: 3px;
    color: #fff;
    font-weight: 700;
    margin-left: 10px;
    font-size: 12px;
}
.popup_body ul li a:hover {
    background: #4176cb;
}
.popup_body ul li label {
    font-weight: 700;
    font-size: 14px;
}
.popup_body ul li input {
    margin-left: 5px;
}
.popup_footer {
    margin-top: 20px;
    text-align: center;
    clear: both;
    border-top: 1px solid #eee;
    padding: 10px 20px 0px 20px;
}
.popup_footer button {
    background: #fff;
    padding: 10px 20px;
    border: 1px solid #777;
    border-radius: 5px;
}
.popup_footer .yes {
    background: #0f89ca;
    color: #fff;
    border-color: #0f89ca;
    padding: 10px 35px;
}
</style>

<div id="hd_pops_agree_ck" class="hd_pops_agree">
    <div class="hd_pops_con">

    
        <div class="agree_popup">
            <div class="popup_head">
                개정된 이용 약관과 개인정보수집 및 이용 동의는 “예＂를, <br />
                기존약관과 개인정보 이용 동의는 “아니오＂를 눌러주세요. <br />
                본 동의로 산부인과협동조합몰에서 변경된 키메디산부인과몰<br />
                을 동일한 정보로 계속해서 이용하실 수 있습니다.-->
                <!--산부인과몰 운영사인 "㈜키닥"이 "㈜키메디"로 상호 변경되었습니다.<br />
                이에 따른 <b>이용약관과 개인정보이용 및 수집 동의</b>가 필요합니다.-->
                <!-- 이미 사용하고 계시는 회원 정보가 존재합니다.<br />
                <b>키메디몰 약관과 개인정보이용 및 수집 동의</b>가 필요합니다 -->
            <!--</div>
            <div class="popup_body">
                <ul>
                    <li>
                        <a href="http://obgys.keymedi.com/bbs/content.php?co_id=provision" target="_blank">이용약관</a>-->
                        <!--<span>
                            <label>동의 <input type="checkbox" name="popup_agree[]" /></label
                        ></span>-->
                    <!--</li>
                    <li>
                        <a href="http://obgys.keymedi.com/bbs/content.php?co_id=privacy" target="_blank">개인정보이용 및 수집</a>-->
                        <!--<span>
                            <label>동의 <input type="checkbox" name="popup_agree[]" /></label
                        ></span>-->
                    <!--</li>
                </ul>
            </div>
            <div class="popup_footer">
                <button class="agree_close agree_not_confirm">아니요</button>
                <button class="yes agree_confirm">예</button>
            </div>
        </div>


    </div>-->
    <!--<div class="hd_pops_footer">
        <button class="hd_pops_reject" id="agree_today_close"><strong>1</strong>일 동안 다시 열람하지 않습니다.</button>
        <button class="hd_pops_close agree_close">닫기</button>
    </div>-->
<!--</div>-->

<script type="text/javascript">
$(document).ready(function() {
    /*$('#agree_today_close').click(function() {
        $("#hd_pops_agree_ck").css("display", "none");
        set_cookie('hd_pops_agree_ck', 1, 24, g5_cookie_domain);
    });*/

    $('.agree_close').click(function() {
        $("#hd_pops_confirm_ck").css("display", "none");
    });

    $('.agree_confirm').click(function() {
        /*if ($('[name^=popup_agree]:checked').length < 2) {
            alert('약관에 모두 동의 하시기 바랍니다.');
            return false;
        }*/

        $.ajax({
            type: "POST",
            url: "/bbs/ajax.mb_agree_ck.php",
            cache: false,
            async: false,
            data: { new_stat: "1" },
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                $("#hd_pops_agree_ck").css("display", "none");
                $("#hd_pops_confirm_ck").css("display", "");

                $("#fullgray").removeClass("gray");
            }
        });
    });

    $('.agree_not_confirm').click(function() {
        /*if ($('[name^=popup_agree]:checked').length < 2) {
            alert('약관에 모두 동의 하시기 바랍니다.');
            return false;
        }*/

        $.ajax({
            type: "POST",
            url: "/bbs/ajax.mb_agree_ck.php",
            cache: false,
            async: false,
            data: { old_stat : "1" },
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                $("#hd_pops_agree_ck").css("display", "none");
                $("#hd_pops_confirm_ck").css("display", "");

                $("#fullgray").removeClass("gray");
            }
        });
    });
});
</script>


<?php
    }
}
?>


</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>