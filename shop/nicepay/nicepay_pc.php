<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_SHOP_PATH.'/settle_nicepay.inc.php');

// header("Content-Type:text/html; charset=utf-8;"); 
/*
****************************************************************************************
* <인증 결과 파라미터>
****************************************************************************************
*/
$authResultCode = $_POST['AuthResultCode']; // 인증결과 : 0000(성공)
$authResultMsg  = $_POST['AuthResultMsg'];  // 인증결과 메시지
$nextAppURL     = $_POST['NextAppURL'];     // 승인 요청 URL
$txTid          = $_POST['TxTid'];          // 거래 ID
$authToken      = $_POST['AuthToken'];      // 인증 TOKEN
$payMethod      = $_POST['PayMethod'];      // 결제수단
$mid            = $_POST['MID'];            // 상점 아이디
$moid           = $_POST['Moid'];           // 상점 주문번호
$amt            = $_POST['Amt'];            // 결제 금액
$reqReserved    = $_POST['ReqReserved'];    // 상점 예약필드
$netCancelURL   = $_POST['NetCancelURL'];   // 망취소 요청 URL
//$authSignature = $_POST['Signature'];     // Nicepay에서 내려준 응답값의 무결성 검증 Data

// error_log($nextAppURL);

/*  
****************************************************************************************
* Signature : 요청 데이터에 대한 무결성 검증을 위해 전달하는 파라미터로 허위 결제 요청 등 결제 및 보안 관련 이슈가 발생할 만한 요소를 방지하기 위해 연동 시 사용하시기 바라며 
* 위변조 검증 미사용으로 인해 발생하는 이슈는 당사의 책임이 없음 참고하시기 바랍니다.
****************************************************************************************
 */
$merchantKey = $default['de_nicepay_mertkey']; // 상점키 

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

// 인증 응답으로 받은 Signature 검증을 통해 무결성 검증을 진행하여야 합니다.
if($authResultCode === "0000" /* && $authSignature == $authComparisonSignature*/){
	/*
	****************************************************************************************
	* <해쉬암호화> (수정하지 마세요)
	* SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
	****************************************************************************************
	*/	
	$ediDate = date("YmdHis");
	$signData = bin2hex(hash('sha256', $authToken . $mid . $amt . $ediDate . $merchantKey, true));

	try {
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
		$resp_arr = json_decode($response, true); // true 일 경우 배열(array)로 반환

		error_log(print_r($data, true));

		// error_log("resp_arr");
		// error_log(print_r($resp_arr, true));

		$result_code = $resp_arr['ResultCode'];
		$result_msg  = $resp_arr['ResultMsg'];
		$pay_method  = $resp_arr['PayMethod']; 	// CARD, BANK, VBANK, CELLPHONE
		$pay_type    = $PAY_METHOD[$pay_method]; // 신용카드, 계좌이체, 가상계좌, 휴대폰
		
		if ($pay_type === '신용카드' && $result_code === '3001') {
			// 신용카드 결제 성공
		} else if ($pay_type === '계좌이체' && $result_code === '4000') {
			// 계좌이체 결제 성공
		} else if ($pay_type === '가상계좌' && $result_code === '4100') {
			// 가상계좌 발급 성공
		} else if ($pay_type === '휴대폰' && $result_code === 'A000') {
			// 휴대폰결제 처리 성공
		} else {
			alert("{$result_msg} ({$result_code})");
			exit;
		}

		$tno         = $resp_arr['TID']; 		// nicepay 원거래번호
		$amount      = $resp_arr['Amt']; 		// 금액
		$app_time    = $resp_arr['AuthDate']; 	// 승인일시

		// 입금자명 : 예금주로 해야 하는데 예금주가 반환되지 않아 주문자명으로 대체합니다.
		// 원래는 이체할때 예금주가 "나이스페이먼츠 주식회사"로 표시됩니다.
        $depositor   = $resp_arr['BuyerName'];  
        $mobile_no   = $resp_arr['BuyerTel'];	// 주문자 휴대폰번호
        $card_name   = isset($resp_arr['CardName'])      ? $resp_arr['CardName']      : ''; // 카드명 (예) KB국민
		$bank_name   = isset($resp_arr['BankName'])      ? $resp_arr['BankName']      : ''; // 은행명 (예) KB국민
		$bankname    = $bank_name; // PHP8에서 Undefined variable $bankname 나오는 오류 방지
     	$app_no      = isset($resp_arr['AuthCode'])      ? $resp_arr['AuthCode']      : ''; // 승인번호
		$vbankname   = isset($resp_arr['VbankBankName']) ? $resp_arr['VbankBankName'] : ''; // 가상계좌 입금할 은행명
		$vbanknum    = isset($resp_arr['VbankNum'])      ? $resp_arr['VbankNum']      : ''; // 가상계좌 입금할 계좌번호
		$account     = $vbankname.' '.$vbanknum; // 은행명과 계좌번호
		$escw_yn     = $default['de_escrow_use'] ? 'Y' : 'N';
		
	} catch(Exception $e) {
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
		
		jsonRespDump($response); //response json dump example
	}	
	
} else /*if($authComparisonSignature == $authSignature)*/ {
	//인증 실패 하는 경우 결과코드, 메시지
	$ResultCode = $authResultCode; 	
	$ResultMsg = $authResultMsg;
	error_log("{$ResultCode}={$ResultMsg}");
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
?>