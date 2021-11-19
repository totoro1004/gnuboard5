<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

global $is_admin;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$visit_skin_url.'/style.css">', 0);
?>

<!-- 접속자집계 시작 { -->
<section id="visit">
    <h2>접속자집계</h2>
    <dl>
        <dt>오늘</dt>
        <dd><span><?php echo number_format($visit[1]) ?></span></dd>
        <dt>어제</dt>
        <dd><span><?php echo number_format($visit[2]) ?></span></dd>
        <dt>최대</dt>
        <dd><span><?php echo number_format($visit[3]) ?></span></dd>
        <dt class="last_child">전체</dt>
        <dd class="last_child"><span><?php echo number_format($visit[4]) ?></span></dd>
    </dl>
</section>
<!-- } 접속자집계 끝 -->