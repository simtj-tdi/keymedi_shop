<?
function cafe24_smssned()
{
	global $g5, $_POST;
	/******************** 인증정보 ********************/
	if ($g5['https_url'])
		$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
	else
		$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	
	/*	jinam23
	$sms['user_id']		= base64_encode("medimembers"); //SMS 아이디.
	$sms['secure']		= base64_encode("6c5b96ee47f81fa883f773b604def61f") ;//인증키
	*/
	$sms['user_id']		= base64_encode("keymedi00"); //SMS 아이디.
	$sms['secure']		= base64_encode("2aac862fb1bc6115c63a575fd5394024") ;//인증키

	//	06.22 - jinam23 
	/*
	$_POST['sphone1'] = "070";
	$_POST['sphone2'] = "7599";
	$_POST['sphone3'] = "2547";
	*/
	//	0701 - jinam23
	$_POST['sphone1'] = "02";
	$_POST['sphone2'] = "540";
	$_POST['sphone3'] = "0703";

	$sms['rphone']		= base64_encode($_POST['r_phone']);
	$sms['sphone1']		= base64_encode($_POST['sphone1']);
	$sms['sphone2']		= base64_encode($_POST['sphone2']);
	$sms['sphone3']		= base64_encode($_POST['sphone3']);
	$sms['destination'] = urlencode(base64_encode($_POST['destination'])); //	메세지에 받는 사람 이름을 넣고 싶을 때 이용 ("휴대폰번호|이름"과 같이 '|'문자로 구분 입력, 치환변수 "{name}"
	$sms['msg']			= base64_encode(stripslashes($_POST['txtMessage']));
	if( $_POST['smsType'] == "L"){
		 $sms['subject'] =  base64_encode($_POST['subject']);
	}
	$sms['smsType'] = base64_encode($_POST['smsType']); // LMS일경우 L
	$sms['rdate']		= base64_encode($_POST['rdate']); // 예약발송 시 (예 : 20120124)
	$sms['rtime']		= base64_encode($_POST['rtime']); // 예약발송 시 (예 : 173030 으로 최소 10분 이상으로 설정)
	$sms['returnurl']	= base64_encode($_POST['returnurl']); // 메세지 전송 후 이동할 페이지 (http:// 를 붙여야 함)
	$returnurl			= $_POST['returnurl'];
	$sms['testflag']	= base64_encode($_POST['testflag']); // 테스트일 경우에만 "Y" 설정
	$sms['repeatFlag']	= base64_encode($_POST['repeatFlag']);
	$sms['repeatNum']	= base64_encode($_POST['repeatNum']);
	$sms['repeatTime']	= base64_encode($_POST['repeatTime']);
	$nointeractive		= 1; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
	//$nointeractive		= $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

	$sms['mode']		= base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.


	$host_info = explode("/", $sms_url);
	$host = $host_info[2];
	$path = $host_info[3]."/".$host_info[4];

	srand((double)microtime()*1000000);
	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	//print_r($sms);

	// 헤더 생성
	$header = "POST /".$path ." HTTP/1.0\r\n";
	$header .= "Host: ".$host."\r\n";
	$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	// 본문 생성
	foreach($sms AS $index => $value){
		$data .="--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		$data .= "\r\n".$value."\r\n";
		$data .="--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

	$fp = fsockopen($host, 80);

	if ($fp) 
	{ 
		fputs($fp, $header.$data);
		$rsp = '';
		while(!feof($fp)) { 
			$rsp .= fgets($fp,8192); 
		}
		fclose($fp);
		$msg = explode("\r\n\r\n",trim($rsp));
		$rMsg = explode(",", $msg[1]);
		$Result= $rMsg[0]; //발송결과
		$Count= $rMsg[1]; //잔여건수

		//발송결과 알림
		if($Result=="success")
			$alert = "성공!! 잔여건수는 ".$Count."건 입니다.";
		elseif($Result=="reserved")
			$alert = "성공적으로 예약되었습니다. 잔여건수는 ".$Count."건 입니다.".$Result;
		elseif($Result=="3205")
			$alert = "잘못된 번호형식입니다.".$Result;
		elseif($Result=="0044")
			$alert = "스팸문자는 발송되지 않습니다.".$Result;
		else if($Result=="-101")
			$alert = "변수 부족 에러".$Result;
		else if($Result=="-201")
			$alert = "잔여 SMS 건수가 부족합니다.".$Result; 
		else if($Result=="0004")
			$alert = "문자 내용이 너무 깁니다.".$sms['msg'].$Result;
		else
			$alert = "[ErrorSMS]".$Result;
	}
	else
	{
		$alert = "Connection Failed";
	}

	return $alert;
}
 
?>