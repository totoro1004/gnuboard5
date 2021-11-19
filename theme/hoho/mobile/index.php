<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');

add_javascript('<script src="'.G5_THEME_JS_URL.'/waterfall-light.js"></script>', 10);
?>

<div class="latest_wr">

    <!-- 메인화면 최신글 시작 -->
    <?php
    //  최신글
    $sql = " select bo_table
                from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
                where a.bo_device <> 'pc' ";
    if(!$is_admin)
        $sql .= " and a.bo_use_cert = '' ";
    $sql .= " order by b.gr_order, a.bo_order ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 스킨은 입력하지 않을 경우 관리자 > 환경설정의 최신글 스킨경로를 기본 스킨으로 합니다.

        // 사용방법
        // latest(스킨, 게시판아이디, 출력라인, 글자수);
        echo latest('theme/basic', $row['bo_table'], 5, 25);
    }
    ?>
    <!-- 메인화면 최신글 끝 -->

    <?php echo poll('theme/basic'); // 설문조사 ?>

</div>

<script>

 $('.latest_wr').show().waterfall({
     // top offset
    top : false, 

    // the container witdh
    w : false, 

    // the amount of columns
    col : false, 

    // the space bewteen boxes
    gap : 10,

    // breakpoints in px
    // 0-400: 1 column
    // 400-600: 2 columns
    // 600-800: 3 columns
    // 800-1000: 4 columns
    gridWidth : [0,600,790,970,],

    // the interval to check the screen
    refresh: 0,
    timer : false,

    // execute a function as the page is scrolled to the bottom
    scrollbottom : false
 });

</script>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>