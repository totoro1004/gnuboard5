<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH.'/settle_nicepay.inc.php');

// print_r2($_POST); exit;

// Array
// (
//     [AuthResultCode] => 0000
//     [AuthResultMsg] => 정상처리 되었습니다.
//     [AuthToken] => NICETOKN008BA57187086EC3D3A3BB1825E4CA17
//     [PayMethod] => VBANK
//     [MID] => nictest00m
//     [Moid] => 2021120507585227
//     [Amt] => 1004
//     [ReqReserved] => 
//     [TxTid] => nictest00m03012112050803333674
//     [NextAppURL] => https://webapi.nicepay.co.kr/webapi/pay_process.jsp
//     [NetCancelURL] => https://webapi.nicepay.co.kr/webapi/cancel_process.jsp
//     [Signature] => 70366c05aee7a1f904734c2c7e4cd6b902d3a443537947d6f86c1bf782fc2a12
// )

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

$oid            = isset($PAY['Moid']) ? trim($PAY['Moid']) : '';   // 주문번호
$tid            = isset($PAY['TID'])  ? trim($PAY['TID'])  : '';   // 거래번호
$amt            = isset($PAY['Amt'])  ? trim($PAY['Amt'])  : 0;    // 결제금액
$result_code    = isset($PAY['ResultCode']) ? trim($PAY['ResultCode'])  : '';   // 결제결과 코드 : (결제수단별 성공코드) 신용카드: 3001, 계좌이체: 4000, 가상계좌: 4100, 휴대폰: A000, SSG은행계좌: 0000, SSG복합결제: 0000, CMS계좌간편결제: 0000
$result_msg     = isset($PAY['ResultMsg'])  ? trim($PAY['ResultMsg'])   : '';   // 결제결과 메세지
$pay_method     = isset($PAY['PayMethod'])  ? trim($PAY['PayMethod'])   : '';   // 결제수단 코드 : CARD(신용카드), BANK(계좌이체), VBANK(가상계좌), CELLPHONE(휴대폰)
$od_settle_case = $PAY_METHOD[$pay_method];
$res_cd         = $_POST['AuthResultCode'];
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
    alert("{$od_settle_case} : {$result_msg} ({$result_code})");
}

$hash = md5($tid.$default['de_nicepay_mid'].$amt);
set_session('P_TID',  $tid);
set_session('P_AMT',  $amt);
set_session('P_HASH', $hash);

$g5['title'] = '나이스페이 결제';
$g5['body_script'] = ' onload="setPAYResult();"';
include_once(G5_PATH.'/head.sub.php');

if (isset($data['pp_id']) && $data['pp_id']) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
} else {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
}

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

// 제외할 필드
$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_AUTH_NO', 'P_HPP_CORP', 'P_APPL_NUM', 'P_VACT_NUM', 'P_VACT_NAME', 'P_VACT_BANK', 'P_CARD_ISSUER', 'P_UNAME');

$sql = " select dt_data from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = isset($row['dt_data']) ? unserialize(base64_decode($row['dt_data'])) : array();

echo make_order_field($data, $exclude);

foreach($_POST as $key=>$value) {
    echo '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
}

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
?>

<div id="pay_working" style="display:none;">
     <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt=""></span>
    <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>