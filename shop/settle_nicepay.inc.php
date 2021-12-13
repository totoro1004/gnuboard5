<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($default['de_card_test']) {
    // nicepay00m 을 이용하여 테스트 하시는 경우 
    // https://npg.nicepay.co.kr/logIn.do 나이스페이 가맹점 관리자로 접근하여 nicepay00 / nicepay00 으로 로그인 할 수 있습니다.
    $default['de_nicepay_mid']     = "nicepay00m"; // 상점아이디
    $default['de_nicepay_mertkey'] = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // 상점키 // "nicepay00m"
} else {
    if (!$default['de_nicepay_mid']) {
        alert("나이스페이 상점아이디가 설정되지 않았습니다.\\n쇼핑몰설정 > 결제설정에서 나이스페이 상점아이디를 입력하세요.");
    }
    if (!$default['de_nicepay_mertkey']) {
        alert("나이스페이 상점키가 설정되지 않았습니다.\\n쇼핑몰설정 > 결제설정에서 나이스페이 상점키를 입력하세요.");
    }
    $default['de_nicepay_mid'] = 'sir'.$default['de_nicepay_mid'];
}

$PAY_METHOD = array(
    'CARD'      => '신용카드',
    'BANK'      => '계좌이체',
    'VBANK'     => '가상계좌',
    'CELLPHONE' => '휴대폰'
);
?>