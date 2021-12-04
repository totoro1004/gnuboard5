<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<div id="display_pay_button" class="btn_confirm">
    <span id="show_req_btn"><input type="button" name="submitChecked" onClick="pay_approval();" value="결제등록요청" class="btn_submit"></span>
    <span id="show_pay_btn" style="display:none;"><input type="button" onClick="forderform_check();" value="주문하기" class="btn_submit"></span>
    <a href="javascript:history.go(-1);" class="btn01">취소</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo G5_URL; ?>/shop/img/loading.gif" alt="">
    <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>