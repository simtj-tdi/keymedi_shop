<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;

/*
배송업체에 데이터를 추가하는 경우 아래 형식으로 추가하세요.
.'(배송업체명^택배조회URL^연락처)'
 
define('G5_DELIVERY_COMPANY',
     '(경동택배^http://www.kdexp.com/sub3_shipping.asp?stype=1&p_item=^080-873-2178)'
    .'(대신택배^http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp?billno1=^043-222-4582)'
    .'(동부택배^http://www.dongbups.com/delivery/delivery_search_view.jsp?item_no=^1588-8848)'
    .'(로젠택배^http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=^1588-9988)'
    .'(우체국^http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=^1588-1300)'
    .'(이노지스택배^http://www.innogis.co.kr/tracking_view.asp?invoice=^1566-4082)'
    .'(한진택배^http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=^1588-0011)'
    .'(롯데택배^http://www.hlc.co.kr/personalService/tracking/06/tracking_goods_result.jsp?InvNo=^1588-2121)'
    .'(CJ대한통운^https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=^1588-1255)'
    .'(CVSnet편의점택배^http://was.cvsnet.co.kr/_ver2/board/ctod_status.jsp?invoice_no=^1577-1287)'
    .'(KG옐로우캡택배^http://www.yellowcap.co.kr/custom/inquiry_result.asp?invoice_no=^1588-0123)'
    .'(KGB택배^http://www.kgbls.co.kr/sub5/trace.asp?f_slipno=^1577-4577)'
    .'(KG로지스^http://www.kglogis.co.kr/contents/waybill.jsp?item_no=^1588-8848)'
	.'(고려택배^http://http://www.klogis.com/main.asp?item_no=^031-240-2400)'
);
*/

define('G5_DELIVERY_COMPANY',
	 '(CJ대한통운^https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=^1588-1255)'
    .'(경동택배^https://kdexp.com/main.kd?p_item=^080-873-2178)'
	.'(고려택배^http://www.klogis.com/main.asp?item_no=^031-240-2400)'
	.'(대신택배^http://www.ds3211.co.kr/?billno1=^043-222-4582)'
	.'(로젠택배^http://www.ilogen.com/web/?slipno=^1588-9988)'
    .'(롯데택배^http://www.hlc.co.kr/personalService/tracking/06/tracking_goods_result.jsp?InvNo=^1588-2121)'
	.'(용마로지스^http://yongmalogis.co.kr/?item_no=^)'	
	.'(우체국^https://service.epost.go.kr/iservice/usr/trace/usrtrc001k01.jsp?sid1=^1588-1300)'
	.'(일양로지스^https://www.ilyanglogis.com/functionality/tracking.asp?item_no=^1588-8848)'
	.'(한진택배^http://www.hanjin.co.kr/Delivery_html/inquiry/personal_inquiry.jsp?wbl_num=^1588-0011)'
 





);


include_once(G5_LIB_PATH.'/shop.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

//==============================================================================
// 쇼핑몰 미수금 등의 주문정보
//==============================================================================
/*
$info = get_order_info($od_id);

$info['od_cart_price']      // 장바구니 주문상품 총금액
$info['od_send_cost']       // 배송비
$info['od_coupon']          // 주문할인 쿠폰금액
$info['od_send_coupon']     // 배송할인 쿠폰금액
$info['od_cart_coupon']     // 상품할인 쿠폰금액
$info['od_tax_mny']         // 과세 공급가액
$info['od_vat_mny']         // 부가세액
$info['od_free_mny']        // 비과세 공급가액
$info['od_cancel_price']    // 주문 취소상품 총금액
$info['od_misu']            // 미수금액
*/
//==============================================================================
// 쇼핑몰 미수금 등의 주문정보
//==============================================================================

// 매출전표 url 설정
if($default['de_card_test']) {
    define('G5_BILL_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('G5_CASH_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
} else {
    define('G5_BILL_RECEIPT_URL', 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('G5_CASH_RECEIPT_URL', 'https://admin.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
}

// 상품상세 페이지에서 재고체크 실행 여부 선택
// 상품의 옵션이 많아 로딩 속도가 느린 경우 false 로 설정
define('G5_SOLDOUT_CHECK', true);

// 주문폼의 상품이 재고 차감에 포함되는 기준 시간설정
// 0 이면 재고 차감에 계속 포함됨
define('G5_CART_STOCK_LIMIT', 3);

// 아이코드 코인 최소금액 설정
// 코인 잔액이 설정 금액보다 작을 때는 주문시 SMS 발송 안함
define('G5_ICODE_COIN', 100);
?>