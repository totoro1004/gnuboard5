<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 전 인사 시작 { -->
<span id="hello_world" class="logout_hello animated bounce">
방문을 환영합니다!^^
</span>
<!-- } 로그인 전 인사 끝 -->
