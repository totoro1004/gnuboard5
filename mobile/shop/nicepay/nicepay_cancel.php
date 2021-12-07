<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 거래승인 취소 
throw new Exception($cancel_msg, 1);

// include_once(G5_MSHOP_PATH.'/settle_nicepay.inc.php');

// // 세션비교
// $hash = md5(get_session('P_TID').$default['de_nicepay_mid'].get_session('P_AMT'));
// if($hash != $_POST['P_HASH']) {
//     throw new Exception('결제 정보 불일치 오류 발생');
//     alert("결제 정보가 일치하지 않습니다. 올바른 방법으로 이용해 주십시오.");
// }
?>