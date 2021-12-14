<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$goodsName  = $goods;
$price      = $tot_sell_price;
$moid       = $od_id;
$buyerName  = '';
$buyerEmail = '';
$buyerTel   = '';
$TransType  = $default['de_escrow_use'] ? 1 : 0;
$MallUserID = $member['mb_id'];
$mid        = $default['de_nicepay_mid'];
$mertkey    = $default['de_nicepay_mertkey'];

/*
*******************************************************
* <해쉬암호화> (수정하지 마세요)
* SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
*******************************************************
*/ 
// $ediDate = date("YmdHis");
$ediDate      = G5_TIME_YMDHIS;
$returnURL    = '';
$VbankExpDate = date('Ymd', strtotime(G5_TIME_YMD.' +1 days')); // 가상계좌입금만료일 오늘부터 1일 (오늘이 20211202 인 경우 20211203 까지)
$hashString   = bin2hex(hash('sha256', $ediDate.$mid.$price.$mertkey, true));
?>
    
<input type="hidden" name="PayMethod"   value="">                           <!-- 결제 수단 -->
<input type="hidden" name="GoodsName"   value="<?php echo($goodsName)?>">   <!-- 결제 상품명 -->
<input type="hidden" name="Amt"         value="<?php echo($price)?>">       <!-- 결제 상품금액 -->
<input type="hidden" name="MID"         value="<?php echo($mid)?>">         <!-- 상점 아이디 -->
<input type="hidden" name="Moid"        value="<?php echo($moid)?>">        <!-- 상품 주문번호 -->
<input type="hidden" name="BuyerName"   value="<?php echo($buyerName)?>">   <!-- 구매자명 -->
<input type="hidden" name="BuyerEmail"  value="<?php echo($buyerEmail)?>">  <!-- 구매자명 이메일 -->
<input type="hidden" name="BuyerTel"    value="<?php echo($buyerTel)?>">    <!-- 구매자 연락처 -->
<input type="hidden" name="MallUserID"  value="<?php echo($MallUserID)?>">  <!-- 쇼핑몰 회원아이디 -->
<!-- (모바일 결제창 전용)PC 결제창 사용시 필요 없음 -->
<input type="hidden" name="ReturnURL"   value="<?php echo($returnURL)?>">   <!-- 인증완료 결과처리 URL -->
<input type="hidden" name="VbankExpDate" value="<?php echo($VbankExpDate)?>"><!-- 가상계좌입금만료일(YYYYMMDD) -->
<!-- 옵션 -->	 
<input type="hidden" name="GoodsCl"     value="1"/>		                    <!-- 상품구분(실물(1),컨텐츠(0)) -->
<input type="hidden" name="TransType"   value="<?php echo($TransType)?>"/>  <!-- 일반(0)/에스크로(1) --> 
<input type="hidden" name="CharSet"     value="utf-8"/>				        <!-- 응답 파라미터 인코딩 방식 -->
<input type="hidden" name="ReqReserved" value=""/>					        <!-- 상점 예약필드 -->
<!-- 변경 불가능 -->
<input type="hidden" name="EdiDate"     value="<?php echo($ediDate)?>"/>    <!-- 전문 생성일시 -->
<input type="hidden" name="SignData"    value="<?php echo($hashString)?>"/> <!-- 해쉬값 -->
<!-- <a href="#" class="btn_blue" onClick="nicepayStart();">요 청</a> -->
<?php if ($default['de_tax_flag_use']) { // 복합과세 ?>
    <input type="hidden" name="SupplyAmt"   value="<?php echo($comm_tax_mny)?>">   <!-- 공급가액 -->
    <input type="hidden" name="GoodsVat"    value="<?php echo($comm_vat_mny)?>">   <!-- 부가가치세 -->
    <input type="hidden" name="ServiceAmt"  value="0">                              <!-- 봉사료 -->
    <input type="hidden" name="TaxFreeAmt"  value="<?php echo($comm_free_mny)?>">  <!-- 비과세 금액 -->
<?php } ?>
