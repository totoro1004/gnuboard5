<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_MSHOP_PATH.'/settle_nicepay.inc.php');

$post_p_hash = isset($_POST['P_HASH']) ? $_POST['P_HASH'] : '';

// 세션비교
$hash = md5(get_session('P_TID').$default['de_nicepay_mid'].get_session('P_AMT'));

if($hash !== $post_p_hash)
    alert('결제 정보가 일치하지 않습니다. 올바른 방법으로 이용해 주십시오.');

//최종결제요청 결과 성공 DB처리
$tno             = get_session('P_TID');
$amount          = get_session('P_AMT');
$app_time        = isset($PAY['AuthDate'])  ? $PAY['AuthDate']  : '';
$pay_method      = isset($PAY['PayMethod']) ? $PAY['PayMethod'] : '';
$pay_type        = isset($PAY_METHOD[$pay_method]) ? $PAY_METHOD[$pay_method] : '';
$depositor       = isset($PAY['BuyerName']) ? $PAY['BuyerName'] : '';
// $commid          = isset($_POST['P_HPP_CORP'])    ? $_POST['P_HPP_CORP'] : ''; // 통신사
$mobile_no       = isset($PAY['BuyerTel'])  ? $PAY['BuyerTel']  : '';
$app_no          = isset($PAY['AuthCode'])  ? $PAY['AuthCode']  : '';
$card_name       = isset($PAY['CardName'])  ? $PAY['CardName']  : '';

if ($default['de_escrow_use'] == 1) {
    $escw_yn = 'Y';
}

switch($pay_type) {
    case '계좌이체':
        $bank_name = isset($PAY['BankName']) ? $PAY['BankName'] : '';
        break;
    case '가상계좌':
        $bankname  = isset($PAY['VbankBankName']) ? $PAY['VbankBankName'] : '';
        $account   = isset($PAY['VbankNum'])      ? $PAY['VbankNum'] : '';
        $app_no    = $account;
        break;
    default:
        break;
}

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');
?>