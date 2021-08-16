<?php
	//************************************************************************
	//																		//
	//		본 샘플소스는 개발 및 테스트를 위한 목적으로 만들어졌으며,		//
	//																		//
	//		실제 서비스에 그대로 사용하는 것을 금합니다.					//
	//																		//
	//************************************************************************

	/************************************************************************************/
	/* - 결과값 복호화를 위해 IV 값을 Random하게 생성함.(반드시 필요함!!)				*/
	/* - input박스 certNum의 value값을  echo $CurTime.$RandNo  형태로 지정				*/
 	/************************************************************************************/
    $CurTime = date('YmdHis');
	$RandNo = rand(100000, 999999);

	//요청 번호 생성
	$reqNum = $CurTime.$RandNo;
?>
<html>
    <head>
        <title>본인인증서비스 </title>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="robots" content="noindex">
        <style>
            <!--
            body,p,ol,ul,td
            {
                font-family: 굴림;
                font-size: 12px;
            }

            a:link { size:9px;color:#000000;text-decoration: none; line-height: 12px}
            a:visited { size:9px;color:#555555;text-decoration: none; line-height: 12px}
            a:hover { color:#ff9900;text-decoration: none; line-height: 12px}

            .style1 {
                color: #6b902a;
                font-weight: bold;
            }
            .style2 {
                color: #666666
            }
            .style3 {
                color: #3b5d00;
                font-weight: bold;
            }
            -->
        </style>
		<script>
		function end(){
			document.reqForm.submit();
		}
		</script>
    </head>
    <body bgcolor="#FFFFFF" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 onload="javascript:end()">
        <center> 

            <form name="reqForm" method="post" action="http://<?=$_SERVER['HTTP_HOST'] ?>/plugin/kmchp/kmcis_web_sample_step02.php" >
			<input type="hidden" name="cpId" size='41' maxlength ='10' value = "KMDM1001">
            <input type="hidden" name="urlCode" size='41' maxlength ='6' value="006001">
            <input type="hidden" name="certNum" size='41' maxlength ='40' value='<?php echo $reqNum ?>'>
            <input type="hidden" name="date" size="41" maxlength ='14' value="<?php echo ($CurTime)  ?>">
			<input type="hidden" name="certMet" size="41" maxlength ='14' value="M">
            <input type="hidden" name="name"  size="41" maxlength ='20' value="">
            <input type="hidden" name="phoneNo" id="textfield" style="width:160px;" class="hpinput" maxlength="11"/>
			<input type="hidden" name="phoneCorp" />
			<input type="hidden" name="birthDay" id="textfield" style="width:160px;" class="hpinput" maxlength="8"/>
			<input type="hidden" name="gender" id="radio" value="">
			<input type="hidden" name="nation" />
            <input type="hidden" name="plusInfo"  size="41" maxlength ='320' value="">
            <input type="hidden" name="tr_url" size="41" value="http://<?=$_SERVER['HTTP_HOST'] ?>/plugin/kmchp/kmcis_web_sample_step03.php">
            <input type="hidden" name="tr_add" value="N"/>

                 
            </form> 
        </center>
    </body>
</html>