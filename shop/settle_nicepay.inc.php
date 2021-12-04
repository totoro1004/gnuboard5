<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$MID         = ""; // 상점아이디
$merchantKey = "";

// if ($default['de_card_test']) 
{
    $MID         = "nictest00m"; // 상점아이디
    $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="; // 상점키 // nictest00m
}    

$default['de_nicepay_mid']     = $MID;
$default['de_nicepay_mertkey'] = $merchantKey;

$PAY_METHOD = array(
    'CARD'      => '신용카드',
    'BANK'      => '계좌이체',
    'VBANK'     => '가상계좌',
    'CELLPHONE' => '휴대폰'
);
?>