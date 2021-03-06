<?php
include_once('./_common.php');
include_once(G5_SHOP_PATH.'/settle_inicis.inc.php');
require_once(G5_SHOP_PATH.'/inicis/libs/HttpClient.php');
require_once(G5_SHOP_PATH.'/inicis/libs/json_lib.php');

include_once(G5_LIB_PATH.'/cafe24.sms.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

try {

    //#############################
    // 인증결과 파라미터 일괄 수신
    //#############################
    //      $var = $_REQUEST["data"];

    //#####################
    // 인증이 성공일 경우만
    //#####################

	

    if (strcmp('0000', $_REQUEST['resultCode']) == 0) {
		
        //############################################
        // 1.전문 필드 값 설정(***가맹점 개발수정***)
        //############################################
		
        $charset = 'UTF-8';        // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)

        $format = 'JSON';        // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)
        // 추가적 noti가 필요한 경우(필수아님, 공백일 경우 미발송, 승인은 성공시, 실패시 모두 Noti발송됨) 미사용
        //String notiUrl    = "";

        $authToken = $_REQUEST['authToken'];   // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
		
        $authUrl = $_REQUEST['authUrl'];    // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)

        $netCancel = $_REQUEST['netCancelUrl'];   // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
		
        ///$mKey = $util->makeHash(signKey, "sha256"); // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
        $mKey = hash("sha256", $signKey);

        //#####################
        // 2.signature 생성
        //#####################
        $signParam['authToken'] = $authToken;  // 필수
        $signParam['timestamp'] = $timestamp;  // 필수
        // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
        $signature = $util->makeSignature($signParam);

		//#####################
        // 3.API 요청 전문 생성
        //#####################
        $authMap['mid'] = $mid;   // 필수
        $authMap['authToken'] = $authToken; // 필수
        $authMap['signature'] = $signature; // 필수
        $authMap['timestamp'] = $timestamp; // 필수
        $authMap['charset'] = $charset;  // default=UTF-8
        $authMap['format'] = $format;  // default=XML
        //if(null != notiUrl && notiUrl.length() > 0){
        //  authMap.put("notiUrl"       ,notiUrl);
        //}


		//#####################
        // 4. jun2ys 추가
        //#####################
		$tmp_od = $_REQUEST['orderNumber'];
		$tmp_res = sql_fetch("select * from {$g5['g5_shop_order_data_table']} where od_id = '$tmp_od' ");
		$tmp_data = unserialize(base64_decode($tmp_res['dt_data']));
 
		$tmp_it_id_tmp = array_unique($tmp_data[it_id]);
		
		$tmp_it_id_arr = "";
		foreach($tmp_it_id_tmp  as $key => $value){
			if($tmp_it_id_arr==""){
				 $tmp_it_id_arr .= $value;
			}else{
				 $tmp_it_id_arr .= ",".$value;
			}				   
		} 
		$tmp_cnt = sql_fetch("select count(*) as cnt from {$g5['g5_shop_cart_table']} where it_id in (".$tmp_it_id_arr.") and od_id = '".$tmp_res['cart_id']."' and ct_status = '쇼핑'");
		
		if($tmp_cnt[cnt] != count(array_unique($tmp_data[it_id]))) {
			
			$tmp_httpUtil = new HttpClient();

			$netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
			if ($tmp_httpUtil->processHTTP($netCancel, $authMap)) {
				$netcancelResultString = $tmp_httpUtil->body;
			} else {
				echo "Http Connect Error\n";
				echo $tmp_httpUtil->errormsg;

				throw new Exception("Http Connect Error");
			}
			
			//echo "## 망취소 API 결과 ##";

			$netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
			$netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);

			//echo "<pre>", $netcancelResultString . "</pre>";
			
			?>
			<script>
				alert("통신오류로 인한 주문취소.");
				document.location.href = "/shop/cart.php"; 
			</script>
			<?
			exit;
		}


        


        try {

            $httpUtil = new HttpClient();

            //#####################
            // 4.API 통신 시작
            //#####################

            $authResultString = "";
            if ($httpUtil->processHTTP($authUrl, $authMap)) {
                $authResultString = $httpUtil->body;
            } else {
                echo "Http Connect Error\n";
                echo $httpUtil->errormsg;

                throw new Exception("Http Connect Error");
            }

            //############################################################
            //5.API 통신결과 처리(***가맹점 개발수정***)
            //############################################################

            $resultMap = json_decode($authResultString, true);

            $tid = $resultMap['tid'];
            $oid = $resultMap['MOID'];

            /*************************  결제보안 추가 2016-05-18 START ****************************/
            $secureMap['mid']       = $mid;                         //mid
            $secureMap['tstamp']    = $timestamp;                   //timestemp
            $secureMap['MOID']      = $resultMap['MOID'];           //MOID
            $secureMap['TotPrice']  = $resultMap['TotPrice'];       //TotPrice

            // signature 데이터 생성
            $secureSignature = $util->makeSignatureAuth($secureMap);
            /*************************  결제보안 추가 2016-05-18 END ****************************/
			/*
            $sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
            $row = sql_fetch($sql);

            $data = unserialize(base64_decode($row['dt_data']));

            if(isset($data['pp_id']) && $data['pp_id']) {
                $order_action_url = G5_HTTPS_SHOP_URL.'/personalpayformupdate.php';
                $page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];
            } else {
                $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
                $page_return_url  = G5_SHOP_URL.'/orderform.php';
                if($_SESSION['ss_direct'])
                    $page_return_url .= '?sw_direct=1';
            }
			*/
				$od_id = $oid;	

				$res = sql_fetch("select * from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' ");

				$pod_id = $od_id;

				$data = unserialize(base64_decode($res['dt_data']));

				$com_od_id_tmp = array_unique($data[com_od_id]);
				$it_id_tmp = array_unique($data[it_id]);


				$mb_id			  = $res[mb_id];
				$od_email         = get_email_address($data[od_email]);
				$od_name          = clean_xss_tags($data[od_name]);
				$od_tel           = clean_xss_tags($data[od_tel]);
				$od_hp            = clean_xss_tags($data[od_hp]);
				$od_zip           = preg_replace('/[^0-9]/', '', $data[od_zip]);
				$od_zip1          = substr($data[od_zip], 0, 3);
				$od_zip2          = substr($data[od_zip], 3);
                $od_b_zip1        = substr($data[od_b_zip], 0, 3);
                $od_b_zip2        = substr($data[od_b_zip], 3);
				$od_addr1         = clean_xss_tags($data[od_addr1]);
				$od_addr2         = clean_xss_tags($data[od_addr2]);
				$od_addr3         = clean_xss_tags($data[od_addr3]);
				$od_addr_jibeon   = preg_match("/^(N|R)$/", $data[od_addr_jibeon]) ? $data[od_addr_jibeon] : '';
				$od_b_name        = clean_xss_tags($data[od_b_name]);
				$od_b_tel         = clean_xss_tags($data[od_b_tel]);
				$od_b_hp          = clean_xss_tags($data[od_b_hp]);
				$od_b_addr1       = clean_xss_tags($data[od_b_addr1]);
				$od_b_addr2       = clean_xss_tags($data[od_b_addr2]);
				$od_b_addr3       = clean_xss_tags($data[od_b_addr3]);
				$od_b_addr_jibeon = preg_match("/^(N|R)$/", $data[od_b_addr_jibeon]) ? $data[od_b_addr_jibeon] : '';
				$od_memo          = clean_xss_tags($data[od_memo]);
				$od_deposit_name  = clean_xss_tags($data[od_deposit_name]);
				$od_tax_flag      = $default['de_tax_flag_use'];
				$od_settle_case	  = $data[od_settle_case];
				$od_time		  = $res[dt_time];
				

				$full_price = clean_xss_tags($data[price]); 

				$f_cp_id		  = clean_xss_tags($data[f_cp_id]);
				$f_cp_price	      = clean_xss_tags($data[f_cp_price]);
				$f_point		  = clean_xss_tags($data[f_point]);	

				$bankname  = $BANK_CODE[$resultMap['VACT_BankCode']];
				$account   = $resultMap['VACT_Num'].' '.$resultMap['VACT_Name'];
				$depositor  = $resultMap['VACT_InputName'];
				
				$od_tno = $tid; 
				$od_pg = "inicis";				
				

				$od_app_no     = $resultMap['applNum'];

				$od_bank_account    = $bankname.' '.$account;
				$od_deposit_name    = $depositor; 


				if($od_settle_case == "신용카드"){
					$od_status = "입금";
					$od_receipt_time  = $res[dt_time];
					$od_app_no     = $resultMap['applNum'];
				}else{
					$od_status = "주문";
					$od_app_no    = $resultMap['VACT_Num'];
				}

				foreach($com_od_id_tmp  as $key => $value){
					$com_od_id[] = $value;
				}
				
				$it_id_arr = "";
				foreach($it_id_tmp  as $key => $value){
					if($it_id_arr==""){
						 $it_id_arr .= $value;
					}else{
						 $it_id_arr .= ",".$value;
					}				   
				}

            if ((strcmp('0000', $resultMap['resultCode']) == 0) && (strcmp($secureSignature, $resultMap['authSignature']) == 0) ) { //결제보안 추가 2016-05-18
                /*                         * ***************************************************************************
                 * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.

                  [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
                  처리중 에러 발생시 망취소를 한다.
                 * **************************************************************************** */
				 /*
				print_r($resultMap);

                // 결제결과 session에 저장
                set_session('resultMap', $resultMap);

                require G5_SHOP_PATH.'/inicis/INIStdPayResult.php';
                exit;
				*/
				// 쿠폰사용내역기록
					if($is_member) {
						$it_cp_cnt = count($data['cp_id']);
						for($i=0; $i<$it_cp_cnt; $i++) {
							$cid = $data['cp_id'][$i];
							$cp_it_id = $data['it_id'][$i];
							$cp_prc = (int)$data['cp_price'][$i];
							$od_id = $data['com_od_id'][$i];		

							if(trim($cid)) {
								$sql = " insert into {$g5['g5_shop_coupon_log_table']}
											set cp_id       = '$cid',
												mb_id       = '$mb_id',
												od_id       = '$od_id',
												cp_price    = '$cp_prc',
												cl_datetime = '".G5_TIME_YMDHIS."' ";
								sql_query($sql);
							

							// 쿠폰사용금액 cart에 기록
							//$cp_prc = (int)$data['cp_price'][$i];
							$sql = " update {$g5['g5_shop_cart_table']}
										set cp_price = '$cp_prc'
										where com_od_id = '$od_id'
										  and it_id = '$cp_it_id'
										order by ct_id asc
										limit 1 ";
							sql_query($sql);
					 

							}
						}

					} 
		


					for($ii = 0 ; $ii < count($com_od_id);$ii++){
						$sql_i = "select count(*) as cnt , od_id , com_od_id , com_id , sum(ct_price * ct_qty) as ct_price from {$g5['g5_shop_cart_table']} where com_od_id = '$com_od_id[$ii]' and ct_status = '쇼핑' and it_id in (".$it_id_arr.") group by com_od_id";
						$res_i = sql_query($sql_i);
						while($row_i = sql_fetch_array($res_i)){
							
							
							$od_id = $row_i[com_od_id];
							
							$od_misu = $row_i[ct_price];
							
							$od_send_cost = get_sendcost($row_i[com_od_id]);
							
							//제고 감소
							$sqla = "select it_id , ct_qty from {$g5['g5_shop_cart_table']} where com_od_id = '$row_i[com_od_id]' and it_id in (".$it_id_arr.") ";
							$resa = sql_query($sqla);
							while($rowa = sql_fetch_array($resa)){
								$sqlaa = " update {$g5['g5_shop_item_table']}
												set it_stock_qty = it_stock_qty - '{$rowa['ct_qty']}'
												where it_id = '{$rowa['it_id']}' ";
								sql_query($sqlaa);
							}

							

							// 장바구니 상태변경
							// 신용카드로 주문하면서 신용카드 포인트 사용하지 않는다면 포인트 부여하지 않음
							$cart_status = $od_status;
							$sql_card_point = "";
							if ($od_receipt_price > 0 && !$default['de_card_point']) {
								$sql_card_point = " , ct_point = '0' ";
							}
							$sql_c = "update {$g5['g5_shop_cart_table']}
									   set od_id = '$row_i[com_od_id]',
										   ct_status = '$cart_status'
										   $sql_card_point
									 where com_od_id = '$row_i[com_od_id]'
									   and it_id in (".$it_id_arr.") ";
							sql_query($sql_c);
							
							if($od_settle_case == "가상계좌" || $od_settle_case == "신용카드"){
								$od_receipt_price = $row_i[ct_price] + $od_send_cost;
								$od_misu = 0;
							}
							$tot_od_cp_price = 0;
							// 주문쿠폰사용내역기록
							if(trim($f_cp_id[$ii])) {

								$tot_od_cp_price = $f_cp_price[$ii];

								$sql = " insert into {$g5['g5_shop_coupon_log_table']}
											set cp_id       = '{$f_cp_id[$ii]}',
												mb_id       = '$mb_id',
												od_id       = '$od_id',
												cp_price    = '$tot_od_cp_price',
												cl_datetime = '".G5_TIME_YMDHIS."' ";
								sql_query($sql);
							}
							$tmp_point = $f_point[$ii]; 

							insert_point($mb_id, $tmp_point*-1, "상품구매", "@shop", $mb_id, microtime()); 

					$sql = " insert {$g5['g5_shop_order_table']}
								set od_id             = '$od_id',
									mb_id             = '$mb_id',
									pod_id			  = '$pod_id',
									com_id			  = '$row_i[com_id]',
									od_pwd            = '$od_pwd',
									od_name           = '$od_name',
									od_email          = '$od_email',
									od_tel            = '$od_tel',
									od_hp             = '$od_hp',
									od_zip1           = '$od_zip1',
									od_zip2           = '$od_zip2',
									od_addr1          = '$od_addr1',
									od_addr2          = '$od_addr2',
									od_addr3          = '$od_addr3',
									od_addr_jibeon    = '$od_addr_jibeon',
									od_b_name         = '$od_b_name',
									od_b_tel          = '$od_b_tel',
									od_b_hp           = '$od_b_hp',
									od_b_zip1         = '$od_b_zip1',
									od_b_zip2         = '$od_b_zip2',
									od_b_addr1        = '$od_b_addr1',
									od_b_addr2        = '$od_b_addr2',
									od_b_addr3        = '$od_b_addr3',
									od_b_addr_jibeon  = '$od_b_addr_jibeon',
									od_deposit_name   = '$od_deposit_name',
									od_memo           = '$od_memo',
									od_cart_count     = '$row_i[cnt]',
									od_cart_price     = '$row_i[ct_price]',
									od_cart_coupon    = '$tot_it_cp_price',
									od_send_cost      = '$od_send_cost',
									od_send_coupon    = '$tot_sc_cp_price',
									od_send_cost2     = '$od_send_cost2',
									od_coupon         = '$tot_od_cp_price',
									od_receipt_price  = '$od_receipt_price',
									od_receipt_point  = '$f_point[$ii]',
									od_bank_account   = '$od_bank_account',
									od_receipt_time   = '$od_receipt_time',
									od_misu           = '$od_misu',
									od_pg             = '$od_pg',
									od_tno            = '$od_tno',
									od_app_no         = '$od_app_no',
									od_escrow         = '$od_escrow',
									od_tax_flag       = '$od_tax_flag',
									od_tax_mny        = '$od_tax_mny',
									od_vat_mny        = '$od_vat_mny',
									od_free_mny       = '$od_free_mny',
									od_status         = '$od_status',
									od_shop_memo      = '',
									od_hope_date      = '$od_hope_date',
									od_time           = '$od_time',
									od_ip             = '$data[REMOTE_ADDR]',
									od_settle_case    = '$od_settle_case',
									od_test           = '{$default['de_card_test']}'
									";
						$result = sql_query($sql, false);

							//$com_mem = get_member($row_i[com_id]);
							$com_mem = sql_fetch("select * from shop.g5_member where mb_id = '{$row_i[com_id]}'");
							
							if($com_mem["mb_24"]=="Y"){

								
								//$admin = get_member('admin');

								$admin = sql_fetch("select * from shop.g5_member where mb_id = 'admin'");
								$sender_name  = $config['cf_title'];
								$sender_email = $config['cf_admin_email'];

								$phone_ext	  = explode("-", $admin[mb_tel]);

								if($com_mem["mb_25"]=="SMS"){
									// 보내는 번호
									$_POST['sphone1'] = $phone_ext[0];
									$_POST['sphone2'] = $phone_ext[1];
									$_POST['sphone3'] = $phone_ext[2];
									// 받는 번호
									$_POST['r_phone'] = $com_mem["mb_hp"];
									// 보내는 메세지
									$_POST['txtMessage'] = "키메디 쇼핑몰 주문안내\n주문내역 확인바랍니다.";

									$result_sms = cafe24_smssned();
	
									$sql_sms = "insert into portal.rc_sms_log set
											sms_name	= '{$com_mem[mb_nick]}',
											sms_sender	= '$sender_name',
											sms_sphone	= '{$phone_ext[0]}-{$phone_ext[1]}-{$phone_ext[2]}',
											sms_rphone	= '$_POST[r_phone]',
											sms_message = '$_POST[txtMessage]',
											sms_ip		= '$_SERVER[REMOTE_ADDR]',
											sms_result	= '$result_sms',
											sms_jijum	= '$ca_name',
											sms_datetime= now() ";
									sql_query($sql_sms);

								}
								if($com_mem["mb_26"]=="EMAIL"){
 
									
									$subject = "[키메디] 쇼핑몰 주문안내";
									$in_reply = "키메디 쇼핑몰 주문발생, 주문내역 확인바랍니다.";

									$wr_email_1 = $com_mem["mb_email"];
									
									mailer($sender_name, $sender_email, $wr_email_1, $subject, $in_reply, 1);

								}

							}


						}
					} 
					
					$dateis = "00000000000000";
					//if($mb_id == "admin"){
						if($od_settle_case == "신용카드"){
							kakao_talk("2019010300014344" , "AT" , 0 , $od_hp , $dateis , $od_name  , $od_time , $full_price, $od_id,"");
						}else{
							kakao_talk("2019010300014341" , "AT" , 0 , $od_hp , $dateis , $od_name  , $od_time , $full_price, $od_id,$od_bank_account);
						}
					//}

					$sql = " delete from {$g5['g5_shop_order_data_table']} where od_id = '$pod_id' ";
					sql_query($sql);
					
					?>
					<script>
						alert("구매완료되었습니다.");
						document.location.href = "/shop/mypage.php"; 
					</script>
					<?				
					exit;

            } else {
                $s = '(오류코드:'.$resultMap['resultCode'].') '.$resultMap['resultMsg'];
                alert($s, $page_return_url);
            }

            // 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
            // 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
            // payViewType을 popup으로 해서 결제를 하셨을 경우
            // 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
            //throw new Exception("강제 Exception");
        } catch (Exception $e) {
            //    $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
            //####################################
            // 실패시 처리(***가맹점 개발수정***)
            //####################################
            //---- db 저장 실패시 등 예외처리----//
            $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
            echo $s;

            //#####################
            // 망취소 API
            //#####################

            $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
            if ($httpUtil->processHTTP($netCancel, $authMap)) {
                $netcancelResultString = $httpUtil->body;
            } else {
                echo "Http Connect Error\n";
                echo $httpUtil->errormsg;

                throw new Exception("Http Connect Error");
            }

            echo "## 망취소 API 결과 ##";

            $netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
            $netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);

            echo "<pre>", $netcancelResultString . "</pre>";
            // 취소 결과 확인
        }
    } else {

        //#############
        // 인증 실패시
        //#############
        echo "<br/>";
        echo "####인증실패####";

        echo "<pre>" . var_dump($_REQUEST) . "</pre>";

		?>
		<script>
			alert("####인증실패####");
			document.location.href = "/shop/mypage.php"; 
		</script>
		<?	

    }
} catch (Exception $e) {
    $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
    echo $s;
}
?>