<?php
include_once('./_common.php');
include_once(G5_MSHOP_PATH.'/settle_nicepay.inc.php');

/*
****************************************************************************************
* <인증 결과 파라미터>
****************************************************************************************
*/
$authResultCode = $_POST['AuthResultCode']; // 인증결과 : 0000(성공)
$authResultMsg  = $_POST['AuthResultMsg'];  // 인증결과 메시지
$authToken      = $_POST['AuthToken'];      // 인증 TOKEN
$payMethod      = $_POST['PayMethod'];      // 결제수단
$mid            = $_POST['MID'];            // 상점 아이디
$moid           = $_POST['Moid'];           // 상점 주문번호
$amt            = $_POST['Amt'];            // 결제 금액
$reqReserved    = $_POST['ReqReserved'];    // 상점 예약필드
$txTid          = $_POST['TxTid'];          // 거래 ID
$nextAppURL     = $_POST['NextAppURL'];     // 승인 요청 URL
$netCancelURL   = $_POST['NetCancelURL'];   // 망취소 요청 URL
$authSignature  = $_POST['Signature'];      // Nicepay에서 내려준 응답값의 무결성 검증 Data

/*  
****************************************************************************************
* Signature : 요청 데이터에 대한 무결성 검증을 위해 전달하는 파라미터로 허위 결제 요청 등 결제 및 보안 관련 이슈가 발생할 만한 요소를 방지하기 위해 연동 시 사용하시기 바라며 
* 위변조 검증 미사용으로 인해 발생하는 이슈는 당사의 책임이 없음 참고하시기 바랍니다.
****************************************************************************************
 */
// $merchantKey = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // 상점키

// 인증 응답 Signature = hex(sha256(AuthToken + MID + Amt + MerchantKey)
//$authComparisonSignature = bin2hex(hash('sha256', $authToken. $mid. $amt. $merchantKey, true)); 

/*
****************************************************************************************
* <승인 결과 파라미터 정의>
* 샘플페이지에서는 승인 결과 파라미터 중 일부만 예시되어 있으며, 
* 추가적으로 사용하실 파라미터는 연동메뉴얼을 참고하세요.
****************************************************************************************
*/

$response = "";
$PAY      = "";

// 인증 응답으로 받은 Signature 검증을 통해 무결성 검증을 진행하여야 합니다.
if($authResultCode === "0000" /* && $authSignature == $authComparisonSignature*/){
    /*
    ****************************************************************************************
    * <해쉬암호화> (수정하지 마세요)
    * SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
    ****************************************************************************************
    */	
    $ediDate = G5_TIME_YMD;
    $signData = bin2hex(hash('sha256', $authToken . $mid . $amt . $ediDate . $merchantKey, true));

    try{
        $data = Array(
            'TID' => $txTid,
            'AuthToken' => $authToken,
            'MID' => $mid,
            'Amt' => $amt,
            'EdiDate' => $ediDate,
            'SignData' => $signData,
            'CharSet' => 'utf-8'
        );		
        $response = reqPost($data, $nextAppURL); //승인 호출
        
        // jsonRespDump($response); //response json dump example
        
    }catch(Exception $e){
        $e->getMessage();
        $data = Array(
            'TID' => $txTid,
            'AuthToken' => $authToken,
            'MID' => $mid,
            'Amt' => $amt,
            'EdiDate' => $ediDate,
            'SignData' => $signData,
            'NetCancel' => '1',
            'CharSet' => 'utf-8'
        );
        $response = reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
        
        // jsonRespDump($response); //response json dump example
    }
    
    $PAY = json_decode($response, true); // true 일 경우 배열(array)로 반환

    // print_r2($_POST);
    // print_r2($PAY);
    // exit;
    
}else /*if($authComparisonSignature == $authSignature)*/{
    //인증 실패 하는 경우 결과코드, 메시지
    $ResultCode = $authResultCode; 	
    $ResultMsg = $authResultMsg;
}/*else{
    echo('인증 응답 Signature : '. $authSignature.'</br>');
    echo('인증 생성 Signature : '. $authComparisonSignature);
}*/


// API CALL foreach 예시
function jsonRespDump($resp){
    //global $mid, $merchantKey;
    $respArr = json_decode($resp);
    foreach ( $respArr as $key => $value ){
        /*if($key == "Amt" || $key == "CancelAmt"){
            $payAmt = $value;
        }
        *if($key == "TID"){
            $tid = $value;
        }
        // 승인 응답으로 받은 Signature 검증을 통해 무결성 검증을 진행하여야 합니다.
        if($key == "Signature"){
            $paySignature = bin2hex(hash('sha256', $tid. $mid. $payAmt. $merchantKey, true));
            if($value != $paySignature){
                echo '비정상 거래! 취소 요청이 필요합니다.</br>';
                echo '승인 응답 Signature : '. $value. '</br>';
                echo '승인 생성 Signature : '. $paySignature. '</br>';
            }
        }*/
        echo "$key=". $value."<br />";
    }
}

//Post api call
function reqPost(Array $data, $url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));	//POST data
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);	 
    return $response;
}
 

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');

$oid = isset($PAY['Moid']) ? trim($PAY['Moid']) : '';
$tid = isset($PAY['TID'])  ? trim($PAY['TID'])  : '';
// $p_req_url 	= isset($_REQUEST['P_REQ_URL']) ? trim($_REQUEST['P_REQ_URL']) : '';

// if( ! $p_req_url || !preg_match('/^https\:\/\//i', $p_req_url)){
//     alert("잘못된 요청 URL 입니다.");
// }

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = isset($row['dt_data']) ? unserialize(base64_decode($row['dt_data'])) : array();

if(isset($data['pp_id']) && $data['pp_id']) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];
} else {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/orderform.php';
    if(get_session('ss_direct'))
        $page_return_url .= '?sw_direct=1';

    // 장바구니가 비어있는가?
    if (get_session('ss_direct'))
        $tmp_cart_id = get_session('ss_cart_direct');
    else
        $tmp_cart_id = get_session('ss_cart_id');

    if (get_cart_count($tmp_cart_id) == 0)// 장바구니에 담기
        alert("({$tmp_cart_id}) 세션을 잃거나 다른 브라우저에서 데이터가 변경된 경우입니다. 장바구니 상태를 확인후에 다시 시도해 주세요.", G5_SHOP_URL.'/cart.php');

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
        if ($row['ct_qty'] > $it_stock_qty)
            $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
    }

    if($i == 0)
        alert('장바구니가 비어 있습니다.', G5_SHOP_URL.'/cart.php');

    if ($error != "") {
        $error .= "결제진행이 중단 되었습니다.";
        alert($error, G5_SHOP_URL.'/cart.php');
    }
}

$res_cd = "";
// 결제성공
if (($PAY['PayMethod'] === "CARD"      && $PAY['ResultCode'] === '3001') || 
    ($PAY['PayMethod'] === "BANK"      && $PAY['ResultCode'] === '4000') || 
    ($PAY['PayMethod'] === "VBANK"     && $PAY['ResultCode'] === '4100') || 
    ($PAY['PayMethod'] === "CELLPHONE" && $PAY['ResultCode'] === 'A000')) 
{
    // 결제방식별로 결과값이 모두 다르게 나오므로 값을 0000 으로 통일
    $res_cd = $_POST['res_cd'] = "0000";
} else  {
    $error_msg = "{$PAY['PayMethod']} 오류 : ".iconv_utf8($PAY['ResultMsg'])."({$PAY['ResultCode']})";
    error_log($error_msg.', return url : '.$page_return_url);
    alert($error_msg, $page_return_url);
    exit;
}

$PAY = array_map('trim', $PAY);
$PAY = array_map('strip_tags', $PAY);
$PAY = array_map('get_search_string', $PAY);

$hash = md5($PAY['TID'].$PAY['MID'].$PAY['Amt']);
set_session('P_TID',  $PAY['TID']);
set_session('P_AMT',  $PAY['Amt']);
set_session('P_HASH', $hash);

$params = array();

//개인결제
if (isset($data['pp_id']) && !empty($data['pp_id'])) {
    // 개인결제 정보
    $pp_check = false;
    $sql = " select * from {$g5['g5_shop_personalpay_table']} where pp_id = '{$oid}' and pp_tno = '{$tid}' and pp_use = '1' ";
    $pp = sql_fetch($sql);

    if (!$pp['pp_tno'] && $data['pp_id'] == $oid) {
        $pp_id  = $oid;

        $exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'LGD_PAYKEY', 'pp_id', 'good_mny', 'pp_name', 'pp_email', 'pp_hp', 'pp_settle_case');

        foreach($data as $key=>$v) {
            if( !in_array($key, $exclude) ){
                $_POST[$key] = $params[$key] = clean_xss_tags(strip_tags($v));
            }
        }

        $good_mny       = isset($PAY['Amt']) ? $PAY['Amt'] : 0;
        $pp_name        = clean_xss_tags($data['pp_name']);
        $pp_email       = clean_xss_tags($data['pp_email']);
        $pp_hp          = clean_xss_tags($data['pp_hp']);
        $pp_settle_case = clean_xss_tags($data['pp_settle_case']);

        $_POST['P_HASH']        = $hash;
        $_POST['pp_id']         = $oid;     // 주문번호
        $_POST['good_mny']      = isset($PAY['Amt'])        ? $PAY['Amt']        :  0; // 거래금액     
        // $_POST['P_AUTH_NO']     = isset($PAY['AuthCode'])   ? $PAY['AuthCode']   : ''; // 승인번호
        // $_POST['P_TYPE']        = isset($PAY['PayMethod'])  ? $PAY['PayMethod']  : ''; // 지불수단
        // $_POST['P_AUTH_DT']     = isset($PAY['AuthDate'])   ? $PAY['AuthDate']   : ''; // 승인일시
        // // $_POST['P_HPP_CORP']    = isset($PAY['P_HPP_CORP']) ? $PAY['P_HPP_CORP'] : ''; // 휴대폰 통신사
        // $_POST['P_APPL_NUM']    = isset($PAY['BuyerTel'])   ? $PAY['BuyerTel']   : ''; // 휴대폰 번호
        // $_POST['P_VACT_NUM']    = isset($PAY['VbankNum'])   ? $PAY['VbankNum']   : ''; // 입금할 계좌번호
        // $_POST['P_VACT_NAME']   = isset($PAY['VbankBankName']) ? iconv_utf8($PAY['VbankBankName']) : ''; // 계좌주명
        // $_POST['P_VACT_BANK']   = (isset($PAY['VbankBankCode']) && isset($BANK_CODE[$PAY['VbankBankCode']])) ? $BANK_CODE[$PAY['VbankBankCode']] : ''; // 은행코드
        // $_POST['P_CARD_ISSUER'] = isset($CARD_CODE[$PAY['AcquCardCode']]) ? $CARD_CODE[$PAY['AcquCardCode']] : ''; // 신용카드(발급사) 코드
        // $_POST['P_UNAME']       = isset($PAY['BuyerName']) ? iconv_utf8($PAY['BuyerName']) : ''; // 고객성명

        include_once(G5_MSHOP_PATH.'/personalpayformupdate.php');
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

    $P_HASH         = $_POST['P_HASH']        = $hash;
    // $P_TYPE         = $_POST['P_TYPE']        = isset($PAY['PayMethod'])  ? $PAY['PayMethod']  : ''; // 지불수단
    // $P_AUTH_NO      = $_POST['P_AUTH_NO']     = isset($PAY['AuthCode'])   ? $PAY['AuthCode']   : ''; // 승인번호
    // $P_AUTH_DT      = $_POST['P_AUTH_DT']     = isset($PAY['AuthDate'])   ? $PAY['AuthDate']   : ''; // 승인일시
    // // $P_HPP_CORP     = $_POST['P_HPP_CORP'] = isset($PAY['P_HPP_CORP']) ? $PAY['P_HPP_CORP'] : ''; // 휴대폰 통신사
    // $P_APPL_NUM     = $_POST['P_APPL_NUM']    = isset($PAY['BuyerTel'])   ? $PAY['BuyerTel']   : ''; // 휴대폰 번호
    // $P_VACT_NUM     = $_POST['P_VACT_NUM']    = isset($PAY['VbankNum'])   ? $PAY['VbankNum']   : ''; // 입금할 계좌번호
    // $P_VACT_NAME    = $_POST['P_VACT_NAME']   = isset($PAY['VbankBankName']) ? iconv_utf8($PAY['VbankBankName']) : ''; // 계좌주명
    // $P_VACT_BANK    = $_POST['P_VACT_BANK']   = isset($PAY['VbankBankName']) ? iconv_utf8($PAY['VbankBankName']) : ''; // 은행명
    // $P_CARD_ISSUER  = $_POST['P_CARD_ISSUER'] = isset($PAY['AcquCardName']) ? $PAY['AcquCardName'] : ''; // 신용카드사명
    // $P_UNAME        = $_POST['P_UNAME']       = isset($PAY['BuyerName']) ? iconv_utf8($PAY['BuyerName']) : ''; // 고객명

    $check_keys = array('od_name', 'od_tel', 'od_pwd', 'od_hp', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon', 'od_email', 'ad_default', 'ad_subject', 'od_hope_date', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon', 'od_memo', 'od_settle_case', 'max_temp_point', 'od_temp_point', 'od_send_cost', 'od_send_cost2', 'od_bank_account', 'od_deposit_name', 'od_test', 'od_ip');

    foreach($check_keys as $key){
        $$key = isset($params[$key]) ? $params[$key] : '';
    }

    include_once( G5_MSHOP_PATH.'/orderformupdate.php' );
}
exit;
?>