<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!G5_IS_MOBILE) {
    // 아래 js는 PC 결제창 전용 js입니다.(모바일 결제창 사용시 필요 없음) 
    add_javascript('<script language="javascript" type="text/javascript" src="https://web.nicepay.co.kr/v3/webstd/js/nicepay-3.0.js" charset="UTF-8"></script>', 10);
}
?>
<script type="text/javascript">
//결제창 최초 요청시 실행됩니다.
function nicepayStart(){
    if(checkPlatform(window.navigator.userAgent) == "mobile"){//모바일 결제창 진입
        // document.payForm.action = "https://web.nicepay.co.kr/v3/v3Payment.jsp";
        // document.payForm.submit();
        document.forderform.action = "https://web.nicepay.co.kr/v3/v3Payment.jsp";
        document.forderform.submit();
    }else{//PC 결제창 진입
        // goPay(document.payForm);
        goPay(document.forderform);
    }
}

//[PC 결제창 전용]결제 최종 요청시 실행됩니다. <<'nicepaySubmit()' 이름 수정 불가능>>
function nicepaySubmit(){
    // document.payForm.submit();
    document.forderform.submit();
}

//[PC 결제창 전용]결제창 종료 함수 <<'nicepayClose()' 이름 수정 불가능>>
function nicepayClose(){
    alert("결제가 취소 되었습니다");
}

//pc, mobile 구분(가이드를 위한 샘플 함수입니다.)
function checkPlatform(ua) {
    if(ua === undefined) {
        ua = window.navigator.userAgent;
    }
    
    ua = ua.toLowerCase();
    var platform = {};
    var matched = {};
    var userPlatform = "pc";
    var platform_match = /(ipad)/.exec(ua) || /(ipod)/.exec(ua) 
        || /(windows phone)/.exec(ua) || /(iphone)/.exec(ua) 
        || /(kindle)/.exec(ua) || /(silk)/.exec(ua) || /(android)/.exec(ua) 
        || /(win)/.exec(ua) || /(mac)/.exec(ua) || /(linux)/.exec(ua)
        || /(cros)/.exec(ua) || /(playbook)/.exec(ua)
        || /(bb)/.exec(ua) || /(blackberry)/.exec(ua)
        || [];
    
    matched.platform = platform_match[0] || "";
    
    if(matched.platform) {
        platform[matched.platform] = true;
    }
    
    if(platform.android || platform.bb || platform.blackberry
            || platform.ipad || platform.iphone 
            || platform.ipod || platform.kindle 
            || platform.playbook || platform.silk
            || platform["windows phone"]) {
        userPlatform = "mobile";
    }
    
    if(platform.cros || platform.mac || platform.linux || platform.win) {
        userPlatform = "pc";
    }
    
    return userPlatform;
}
</script>
