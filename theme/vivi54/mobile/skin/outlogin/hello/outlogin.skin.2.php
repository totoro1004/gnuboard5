<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 후 인사 시작 { -->
<span id="hello_world" class="login_hello animated bounce">
	안녕하세요! <strong><?php echo $nick ?></strong>님^^
</span>
<!-- } 로그인 후 인사 끝 -->