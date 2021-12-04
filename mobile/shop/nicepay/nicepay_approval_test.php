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

$resultCode     = $PAY['ResultCode'];	// 결과 코드
$resultMsg      = $PAY['ResultMsg'];	// 결과메시지
$mid            = $PAY['MID'];  // 상점ID
$tid            = $PAY['TID'];  // 거래ID
$amt            = $PAY['Amt'];  // 결제상품금액
$moid           = $PAY['Moid'];  // 주문번호
$authDate       = $PAY['AuthDate']; // 승인 시간
$authCode       = $PAY['AuthCode']; // 승인 번호

$tno            = $tid;
$amount         = $amt;
$res_cd         = $resultCode;
$res_msg        = $resultMsg;

$paySuccess = false;		// 결제 성공 여부
if ($PayMethod == 'CARD') { //신용카드

    $card_cd        = $PAY['CardCode']; // 카드사 코드
    $card_name      = $PAY['CardName']; // 카드 종류
    $app_time       = $authDate; // 승인 시간
    $app_no         = $authCode; // 승인 번호
    $card_mny       = $amt; // 카드결제금액

	if ($resultCode == '3001') 
        $paySuccess = true;	// 결과코드 (정상 :3001 , 그 외 에러)

} else if ($PayMethod == 'BANK') { // 계좌이체

    $app_time       = $authDate; // 승인 시간
    $bankname       = $PAY['BankName']; // 은행명
    $bank_code      = $PAY['BankCode']; // 은행코드
    $bk_mny         = $amt; // 계좌이체결제금액

	if ($resultCode == '4000') 
        $paySuccess = true;	// 결과코드 (정상 :4000 , 그 외 에러)

} else if ($PayMethod == 'CELLPHONE') { // 휴대폰
    
    $app_time       = $authDate; // 승인 시간
    $commid         = $PAY['Carrier']; //  통신사 코드
    $mobile_no      = $PAY['DstAddr']; // 휴대폰 번호

	if ($resultCode == 'A000') 
        $paySuccess = true;	//결과코드 (정상 : A000, 그 외 비정상)

} else if ($PayMethod == 'VBANK') { // 가상계좌
    
    $bankname       = $PAY['VbankBankName']; // 입금할 은행 이름
    $depositor      = ''; // 입금할 계좌 예금주
    $account        = $PAY['VbankNum']; // 입금할 계좌 번호
    $va_date        = $PAY['VbankExpDate']; // 가상계좌 입금마감시간

	if ($resultCode == '4100') 
        $paySuccess = true;	// 결과코드 (정상 :4100 , 그 외 에러)

}

$result_txt = "";
$result_txt .= "resultCode:".$resultCode."|";
$result_txt .= "resultMsg:".$resultMsg."|";
$result_txt .= "authDate:".$authDate."|";
$result_txt .= "authCode:".$authCode."|";
$result_txt .= "mid:".$mid."|";
$result_txt .= "tid:".$tid."|";
$result_txt .= "moid:".$moid."|";
$result_txt .= "amt:".$amt."|";

/** 위의 응답 데이터 외에도 전문 Header와 개별부 데이터 Get 가능 */

$tno            = $tid;
$amount         = $amt;
$res_cd         = $resultCode;
$res_msg        = $resultMsg;

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');

$oid  = trim($moid);

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

if(isset($data['pp_id']) && $data['pp_id']) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];
} else {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/orderform.php';
    if (get_session('ss_direct'))
        $page_return_url .= '?sw_direct=1';

    if (get_session('ss_direct'))
        $tmp_cart_id = get_session('ss_cart_direct');
    else
        $tmp_cart_id = get_session('ss_cart_id');
    if (!$tmp_cart_id) {
        print_r2($_SESSION);
    }
}

if ($paySuccess == true) { // 결제성공
    
    $hash = md5($tno.$g_conf_site_cd.$amount);
    set_session('P_TID',  $tno);
    set_session('P_AMT',  $amount);
    set_session('P_HASH', $hash);

}
else {
    alert('오류 : '.$res_msg.' 코드 : '.$res_cd, $page_return_url);
}

$g5['title'] = '나이스페이 결제';
include_once(G5_PATH.'/head.sub.php');

$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_AUTH_NO', 'P_HPP_CORP', 'P_APPL_NUM', 'P_VACT_NUM', 'P_VACT_NAME', 'P_VACT_BANK', 'P_CARD_ISSUER', 'P_UNAME');

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

echo make_order_field($data, $exclude);

echo '<input type="hidden" name="res_cd"        value="'.$res_cd.'">'.PHP_EOL;
echo '<input type="hidden" name="P_HASH"        value="'.$hash.'">'.PHP_EOL;
echo '<input type="hidden" name="P_TYPE"        value="'.$PayMethod.'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_DT"     value="'.$app_time.'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_NO"     value="'.$app_no.'">'.PHP_EOL;
echo '<input type="hidden" name="P_HPP_CORP"    value="'.$commid.'">'.PHP_EOL;
echo '<input type="hidden" name="P_APPL_NUM"    value="'.$mobile_no.'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_NUM"    value="'.$account.'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_NAME"   value="">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_BANK"   value="'.$bankname.'">'.PHP_EOL;
echo '<input type="hidden" name="P_CARD_ISSUER" value="'.$card_name.'">'.PHP_EOL;
echo '<input type="hidden" name="P_UNAME"       value="">'.PHP_EOL;

echo '</form>'.PHP_EOL;
exit;
?>

<div id="show_progress">
    <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt=""></span>
    <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
window.onload = function() {
    setPAYResult();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>