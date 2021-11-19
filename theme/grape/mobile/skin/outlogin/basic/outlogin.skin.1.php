<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>
<div class="ol">
<a href="<?php echo G5_BBS_URL ?>/login.php" class="btn_b02 btn_s">로그인</a>
</div>
