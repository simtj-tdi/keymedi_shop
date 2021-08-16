<?php
include_once('./_common.php');

if(!$is_member)
    alert_close('회원이시라면 회원로그인 후 이용해 주십시오.');

if($w == 'd') {
    $sql = " delete from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' and ad_id = '$ad_id' ";
    sql_query($sql);
    goto_url($_SERVER['SCRIPT_NAME']);
}

$sql_common = " from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' ";

$sql = " select count(ad_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            $sql_common
            order by ad_default desc, ad_id desc
            limit $from_record, $rows";


$result = sql_query($sql);

if(!sql_num_rows($result)){
    $address_num = 0;
} else{
    $address_num = 1;
}


$order_action_url = G5_HTTPS_SHOP_URL.'/orderaddressupdate.php';

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderaddress.php');
    return;
}

// 테마에 orderaddress.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_orderaddress_file = G5_THEME_SHOP_PATH.'/orderaddress.php';
    if(is_file($theme_orderaddress_file)) {
        include_once($theme_orderaddress_file);
        return;
        unset($theme_orderaddress_file);
    }
}

$g5['title'] = '배송지 목록';
include_once(G5_PATH.'/head.sub.php');
?>
    <form name="forderaddress" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
        <div id="sod_addr" class="new_win">

            <h1 id="win_title">배송지 목록</h1>

            <div class="tbl_head01 tbl_wrap">
                <table>
                    <thead>
                    <tr>
                        <th scope="col">
                            선택
                        </th>
                        <th scope="col">배송지이름</th>
                        <!--<th scope="col">기본<br>배송지</th>-->
                        <th scope="col">주소</th>
                        <th scope="col">받는사람</th>
                        <th scope="col">연락처</th>

                        <!--<th scope="col">관리</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($address_num > 0){

                        $sep = '||';
                        for($i=0; $row=sql_fetch_array($result); $i++) {
                            $addr = $row['ad_name'].$sep.$row['ad_tel'].$sep.$row['ad_hp'].$sep.$row['ad_zip1'].$sep.$row['ad_zip2'].$sep.$row['ad_addr1'].$sep.$row['ad_addr2'].$sep.$row['ad_addr3'].$sep.$row['ad_jibeon'].$sep.$row['ad_subject'].$sep.$row['ad_subject'];
                            $addr = get_text($addr);

                            $_del_ref = $_SERVER['SCRIPT_NAME']."?w=d&amp;ad_id=" ;
                            ?>
                            <input type="hidden" id="addr_<?=$row['ad_id']?>" value="<?php echo $addr;?>">
                            <tr>
                                <td class="td_chk">
                                    <label for="chk_<?php echo $i;?>" class="sound_only">배송지선택</label>
                                    <input type="radio" name="chk" value="<?php echo $row['ad_id'];?>">
                                </td>
                                <td class="td_name">
                                    <label for="ad_subject<?php echo $i;?>" class="sound_only">배송지명</label>
                                    <?php echo get_text($row['ad_subject']); ?>
                                </td>
                                <td><?php echo print_address($row['ad_addr1'], $row['ad_addr2'], $row['ad_addr3'], $row['ad_jibeon']); ?></td>
                                <td class="td_namesmall"><?php echo get_text($row['ad_name']); ?></td>
                                <td class="td_numbig"><?php echo $row['ad_tel']; ?><br><?php echo $row['ad_hp']; ?></td>
                            </tr>
                            <?php
                        }
                    }else{
                        echo "<tr>
                                <td colspan='5' style='height: 200px;width: 100%; text-align: center;'>
                                등록된 배송지 정보가 없습니다.
                                </td>
                            </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>


            <div class="win_btn">
                <button type="button" style="float: left;margin-left: 20px; background-color: #9bbb59;color: white" onclick="address_write();">배송지추가</button>
                <button type="button" style="float: left;margin-left: 20px; background-color: #4f818c;color: white" onclick="address_modify();">수정</button>
                <button type="button" style="float: left;margin-left: 20px; background-color: #8a8a8a;color: white" onclick="confirm_process('배송지 정보를 삭제하시겠습니까? \n삭제 후 재 등록시 동일한 주소 사용가능합니다.','<?=$_del_ref?>');">삭제</button>
                <button type="button" style="float: right;margin-left: 20px; background-color: #ca0519;color: white" id="shipping_addr">배송지로선택</button>
                <!--<input type="submit" name="act_button" value="선택수정" class="btn_submit">
                <button type="button" onclick="self.close();">닫기</button>-->
            </div>
        </div>
    </form>

<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

    <script>
        $(function() {
            $("#shipping_addr").on("click", function() {

                var addr_ad_id = $('input[name=chk]:checked').val();
                var addr_val = $("#addr_"+addr_ad_id).val();
                var addr = addr_val.split('||');

                var f = window.opener.forderform;
                f.ad_subject.value      = addr[10]
                f.od_b_name.value       = addr[0];
                f.od_b_tel.value        = addr[1];
                f.od_b_hp.value         = addr[2];
                f.od_b_zip.value        = addr[3] + addr[4];
                f.od_b_addr1.value      = addr[5];
                f.od_b_addr2.value      = addr[6];
                // f.od_b_addr3.value      = addr[7];
                f.od_b_addr_jibeon.value= addr[8];
                f.ad_subject.value      = addr[9];

                var zip1 = addr[3].replace(/[^0-9]/g, "");
                var zip2 = addr[4].replace(/[^0-9]/g, "");

                if(zip1 != "" && zip2 != "") {
                    var code = String(zip1) + String(zip2);

                    if(window.opener.zipcode != code) {
                        window.opener.zipcode = code;
                        window.opener.calculate_sendcost(code);
                    }
                }

                parent.self.close();
            });
        });

        function address_write(){
            location.href="./new_orderaddress_form.php?idx=";
        }

        function address_modify(){

            $("#mycheckbox").each(function(){
                alert("foo");
                if (jQuery(this).is(":checked"))
                    ReturnVal = true;
            });

            var idx = $('input[name=chk]:checked').val();

            if(idx=="" || idx==undefined){
                alert('배송지를 선택해 주세요');
                return false;
            }

            location.href="./new_orderaddress_form.php?idx="+idx;
        }

        function confirm_process(msg,ref)
        {
            var chk_ad_id = $('input[name=chk]:checked').val();
            ref = ref+chk_ad_id;

            if(chk_ad_id=="" || chk_ad_id==undefined){
                alert('배송지를 선택해 주세요');
                return false;
            }

            if(confirm(msg)){
                location.href = ref ;
            }else{
                return false ;
            }
        }

    </script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>