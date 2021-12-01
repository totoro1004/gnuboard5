<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// $merchantKey = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // 상점키
// $merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="; // 상점키
// $MID         = "nicepay00m"; // 상점아이디
// $MID         = "nictest00m"; // 상점아이디
// $goodsName   = "나이스페이test"; // 결제상품명
// $price       = "1004"; // 결제상품금액
// $buyerName   = "홍길동"; // 구매자명 
// $buyerTel	 = "01099813619"; // 구매자연락처
// $buyerEmail  = "admin@sir.kr"; // 구매자메일주소        
// $moid        = "mnoid1234567890"; // 상품주문번호                     
// $returnURL	 = "http://php8.gnuboard.link/test/nicepay/result.php"; // 결과페이지(절대경로) - 모바일 결제창 전용

$goodsName = $goods;
$price = $tot_price;
$moid = $od_id;

/*
*******************************************************
* <해쉬암호화> (수정하지 마세요)
* SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
*******************************************************
*/ 
$ediDate = date("YmdHis");
$hashString = bin2hex(hash('sha256', $ediDate.$MID.$price.$merchantKey, true));
?>

<input type="hidden" name="PayMethod" value="">
<input type="hidden" name="GoodsName" value="<?php echo($goodsName)?>">
<input type="hidden" name="Amt" value="<?php echo($price)?>">
<input type="hidden" name="MID" value="<?php echo($MID)?>">
<input type="hidden" name="Moid" value="<?php echo($moid)?>">
<input type="hidden" name="BuyerName" value="">
<input type="hidden" name="BuyerEmail" value="">
<input type="hidden" name="BuyerTel" value="">
<!-- (모바일 결제창 전용)PC 결제창 사용시 필요 없음 -->
<!-- <input type="hidden" name="ReturnURL" value="<?php //echo($returnURL)?>">  -->
<input type="hidden" name="ReturnURL" value=""> 

<input type="hidden" name="VbankExpDate" value=""> <!-- 가상계좌입금만료일(YYYYMMDD) -->
<!-- 옵션 -->	 
<input type="hidden" name="GoodsCl" value="1"/>     <!-- 상품구분(실물(1),컨텐츠(0)) -->
<input type="hidden" name="TransType" value="0"/>   <!-- 일반(0)/에스크로(1) --> 
<input type="hidden" name="CharSet" value="utf-8"/><!-- 응답 파라미터 인코딩 방식 -->
<input type="hidden" name="ReqReserved" value=""/>  <!-- 상점 예약필드 -->
        
<!-- 변경 불가능 -->
<input type="hidden" name="EdiDate" value="<?php echo($ediDate)?>"/> <!-- 전문 생성일시 -->
<input type="hidden" name="SignData" value="<?php echo($hashString)?>"/> <!-- 해쉬값 -->
