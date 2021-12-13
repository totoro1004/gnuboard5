<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$PAY = array_map('trim', $PAY);
$PAY = array_map('strip_tags', $PAY);
$PAY = array_map('get_search_string', $PAY);

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');

$res_cd         = $_POST['AuthResultCode'];
$oid            = isset($PAY['Moid']) ? trim($PAY['Moid']) : '';   // 주문번호
$tid            = isset($PAY['TID'])  ? trim($PAY['TID'])  : '';   // 거래번호
// $amt            = isset($PAY['Amt'])  ? trim($PAY['Amt'])  : 0;    // 결제금액
$result_msg     = isset($PAY['ResultMsg'])  ? trim($PAY['ResultMsg'])   : '';   // 결제결과 메세지
$result_code    = isset($PAY['ResultCode']) ? trim($PAY['ResultCode'])  : '';   // 결제결과 코드 : (결제수단별 성공코드) 신용카드: 3001, 계좌이체: 4000, 가상계좌: 4100, 휴대폰: A000, SSG은행계좌: 0000, SSG복합결제: 0000, CMS계좌간편결제: 0000
$pay_method     = isset($PAY['PayMethod'])  ? trim($PAY['PayMethod'])   : '';   // 결제수단 코드 : CARD(신용카드), BANK(계좌이체), VBANK(가상계좌), CELLPHONE(휴대폰)
$od_settle_case = $PAY_METHOD[$pay_method];
$app_time       = $PAY['AuthDate']; // 승인일시
$app_no         = '';
$commid         = '';
$mobile_no      = '';
$account        = '';
$bankname       = '';
$card_name      = '';
$depositor      = ''; // 계좌 예금주

$success = false;
if ($pay_method === 'CARD') {

    $card_cd    = $PAY['CardCode']; // 카드사코드
    $card_name  = $PAY['CardName']; // 카드종류
    $app_no     = $PAY['AuthCode']; // 승인번호
    $card_mny   = $amt; // 카드결제금액

    if ($result_code === '3001') $success = true;

} else if ($pay_method === 'BANK') {

    $bankname   = $PAY['BankName']; // 결제은행명
    $bank_code  = $PAY['BankCode']; // 결제은행코드
    $bk_mny     = $amt; // 계좌이체결제금액

    if ($result_code === '4000') $success = true;

} else if ($pay_method === 'VBANK') {

    $bankname   = $PAY['VbankBankName']; // 입금할 은행 이름
    $account    = $PAY['VbankNum']; // 입금할 계좌 번호

    if ($result_code === '4100') $success = true;

} else if ($pay_method === 'CELLPHONE') {

    $commid     = $PAY['Carrier']; // 통신사 코드
    $mobile_no  = $PAY['DstAddr']; // 휴대폰 번호

    if ($result_code === 'A000') $success = true;

} 

// 결제 실패
if (!$success) {
    throw new Exception("{$od_settle_case} : {$result_msg} ({$result_code})");
    // alert("{$od_settle_case} : {$result_msg} ({$result_code})");
    exit;
}

$hash = md5($tid.$default['de_nicepay_mid'].$amt);
set_session('P_TID',  $tid);
set_session('P_AMT',  $amt);
set_session('P_HASH', $hash);

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = isset($row['dt_data']) ? unserialize(base64_decode($row['dt_data'])) : array();

if (isset($data['pp_id']) && $data['pp_id']) {

    $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];

} else {

    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/orderform.php';

    error_log(print_r($_SESSION, true));

    if(get_session('ss_direct')) {
        $page_return_url .= '?sw_direct=1';
    }

    // 장바구니가 비어있는가?
    if (get_session('ss_direct')) {
        $tmp_cart_id = get_session('ss_cart_direct');
    } else {
        $tmp_cart_id = get_session('ss_cart_id');
    }

    if (get_cart_count($tmp_cart_id) == 0) { // 장바구니에 담기
        throw new Exception('세션을 잃거나 다른 브라우저에서 데이터가 변경된 경우입니다. 장바구니 상태를 확인후에 다시 시도해 주세요.', 1);
        // alert('세션을 잃거나 다른 브라우저에서 데이터가 변경된 경우입니다. 장바구니 상태를 확인후에 다시 시도해 주세요.', G5_SHOP_URL.'/cart.php');
        exit;
    }

    $error = "";
    // 장바구니 상품 재고 검사
    $sql = " select it_id,
                    ct_qty,
                    it_name,
                    io_id,
                    io_type,
                    ct_option
               from {$g5['g5_shop_cart_table']}
              where od_id = '$tmp_cart_id'
                and ct_select = '1' ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        // 상품에 대한 현재고수량
        if($row['io_id']) {
            $it_stock_qty = (int)get_option_stock_qty($row['it_id'], $row['io_id'], $row['io_type']);
        } else {
            $it_stock_qty = (int)get_it_stock_qty($row['it_id']);
        }
        // 장바구니 수량이 재고수량보다 많다면 오류
        if ($row['ct_qty'] > $it_stock_qty) {
            $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
        }
    }

    if ($i == 0) {
        throw new Exception("장바구니가 비어 있습니다.", 1);
        // alert('장바구니가 비어 있습니다.', G5_SHOP_URL.'/cart.php');
        exit;
    }

    if ($error != "") {
        $error .= "결제진행이 중단 되었습니다.";
        throw new Exception($error, 1);
        // alert($error, G5_SHOP_URL.'/cart.php');
        exit;
    }
}

$params = array();

//개인결제
if (isset($data['pp_id']) && !empty($data['pp_id'])) {

    // 개인결제 정보
    $pp_check = false;
    $sql = " select * from {$g5['g5_shop_personalpay_table']} where pp_id = '{$PAY['P_OID']}' and pp_tno = '{$PAY['P_TID']}' and pp_use = '1' ";
    $pp = sql_fetch($sql);

    if (!$pp['pp_tno'] && $data['pp_id'] == $oid) {
        $res_cd = $PAY['P_STATUS'];
        $pp_id = $oid;

        $exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'LGD_PAYKEY', 'pp_id', 'good_mny', 'pp_name', 'pp_email', 'pp_hp', 'pp_settle_case');

        foreach($data as $key=>$v) {
            if( !in_array($key, $exclude) ){
                $_POST[$key] = $params[$key] = clean_xss_tags(strip_tags($v));
            }
        }

        $good_mny       = $amt;
        $pp_name        = clean_xss_tags($data['pp_name']);
        $pp_email       = clean_xss_tags($data['pp_email']);
        $pp_hp          = clean_xss_tags($data['pp_hp']);
        $pp_settle_case = clean_xss_tags($data['pp_settle_case']);

        $_POST['P_HASH']      = $hash;
        $_POST['P_AUTH_NO']   = isset($PAY['P_AUTH_NO']) ? $PAY['P_AUTH_NO'] : '';
        $_POST['pp_id']       = isset($PAY['P_OID']) ? $PAY['P_OID'] : '';
        $_POST['good_mny']    = isset($PAY['P_AMT']) ? $PAY['P_AMT'] : 0;

        $_POST['P_TYPE']      = isset($PAY['P_TYPE']) ? $PAY['P_TYPE'] : '';
        $_POST['P_AUTH_DT']   = isset($PAY['P_AUTH_DT']) ? $PAY['P_AUTH_DT'] : '';
        $_POST['P_HPP_CORP']  = isset($PAY['P_HPP_CORP']) ? $PAY['P_HPP_CORP'] : '';
        $_POST['P_APPL_NUM']  = isset($PAY['P_APPL_NUM']) ? $PAY['P_APPL_NUM'] : '';
        $_POST['P_VACT_NUM']  = isset($PAY['P_VACT_NUM']) ? $PAY['P_VACT_NUM'] : '';
        $_POST['P_VACT_NAME'] = isset($PAY['P_VACT_NAME']) ? iconv_utf8($PAY['P_VACT_NAME']) : '';
        $_POST['P_VACT_BANK'] = (isset($PAY['P_VACT_BANK_CODE']) && isset($BANK_CODE[$PAY['P_VACT_BANK_CODE']])) ? $BANK_CODE[$PAY['P_VACT_BANK_CODE']] : '';
        $_POST['P_CARD_ISSUER'] = isset($CARD_CODE[$PAY['P_CARD_ISSUER_CODE']]) ? $CARD_CODE[$PAY['P_CARD_ISSUER_CODE']] : '';
        $_POST['P_UNAME'] = isset($PAY['P_UNAME']) ? iconv_utf8($PAY['P_UNAME']) : '';

        include_once( G5_MSHOP_PATH.'/personalpayformupdate.php' );
    }

} else {
    
    // 상점 결제
    $exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'P_AUTH_NO');

    foreach($data as $key=>$value) {
        if(!empty($exclude) && in_array($key, $exclude))
            continue;

        if(is_array($value)) {
            foreach($value as $k=>$v) {
                $_POST[$key][$k] = $params[$key][$k] = clean_xss_tags(strip_tags($v));
            }
        } else {
            $_POST[$key] = $params[$key] = clean_xss_tags(strip_tags($value));
        }
    }

    // $oid            = isset($PAY['Moid']) ? trim($PAY['Moid']) : '';   // 주문번호
    // $tid            = isset($PAY['TID'])  ? trim($PAY['TID'])  : '';   // 거래번호
    // $amt            = isset($PAY['Amt'])  ? trim($PAY['Amt'])  : 0;    // 결제금액
    // $result_code    = isset($PAY['ResultCode']) ? trim($PAY['ResultCode'])  : '';   // 결제결과 코드 : (결제수단별 성공코드) 신용카드: 3001, 계좌이체: 4000, 가상계좌: 4100, 휴대폰: A000, SSG은행계좌: 0000, SSG복합결제: 0000, CMS계좌간편결제: 0000
    // $result_msg     = isset($PAY['ResultMsg'])  ? trim($PAY['ResultMsg'])   : '';   // 결제결과 메세지
    // $pay_method     = isset($PAY['PayMethod'])  ? trim($PAY['PayMethod'])   : '';   // 결제수단 코드 : CARD(신용카드), BANK(계좌이체), VBANK(가상계좌), CELLPHONE(휴대폰)
    // $od_settle_case = $PAY_METHOD[$pay_method];
    // $res_cd         = $_POST['AuthResultCode'];
    // $app_time       = $PAY['AuthDate']; // 승인일시
    

    $P_HASH        = $_POST['P_HASH']        = $hash;
    $P_TYPE        = $_POST['P_TYPE']        = isset($PAY['PayMethod'])     ? $PAY['PayMethod'] : '';
    $P_AUTH_DT     = $_POST['P_AUTH_DT']     = isset($PAY['AuthDate'])      ? $PAY['AuthDate'] : '';
    $P_AUTH_NO     = $_POST['P_AUTH_NO']     = isset($PAY['AuthCode'])      ? $PAY['AuthCode'] : '';
    $P_HPP_CORP    = $_POST['P_HPP_CORP']    = isset($PAY['buyerTel'])      ? $PAY['buyerTel'] : '';
    $P_APPL_NUM    = $_POST['P_APPL_NUM']    = isset($PAY['buyerTel'])      ? $PAY['buyerTel'] : '';
    $P_UNAME       = $_POST['P_UNAME']       = isset($PAY['BuyerName'])     ? $PAY['BuyerName'] : '';
    $P_VACT_NUM    = $_POST['P_VACT_NUM']    = isset($PAY['VbankNum'])      ? $PAY['VbankNum'] : '';
    $P_VACT_BANK   = $_POST['P_VACT_BANK']   = isset($PAY['VbankBankName']) ? $PAY['VbankBankName'] : '';
    // $P_VACT_NAME   = $_POST['P_VACT_NAME']   = isset($PAY['BuyerName'])     ? $PAY['BuyerName'] : '';  // 입금자명(계좌주명)
    $P_CARD_ISSUER = $_POST['P_CARD_ISSUER'] = isset($PAY['CardName'])      ? $PAY['CardName'] : ''; // 카드사명
    $res_cd        = $_POST['res_cd']        = isset($_POST['AuthResultCode']) ? $_POST['AuthResultCode'] : '';

    $check_keys = array('od_name', 'od_tel', 'od_pwd', 'od_hp', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon', 'od_email', 'ad_default', 'ad_subject', 'od_hope_date', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon', 'od_memo', 'od_settle_case', 'max_temp_point', 'od_temp_point', 'od_send_cost', 'od_send_cost2', 'od_bank_account', 'od_deposit_name', 'od_test', 'od_ip');

    foreach($check_keys as $key){
        $$key = isset($params[$key]) ? $params[$key] : '';
    }

    include_once( G5_MSHOP_PATH.'/orderformupdate.php' );

}
exit;
?>