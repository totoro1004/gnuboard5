<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
include_once(G5_THEME_LIB_PATH.'/new_lastest.lib.php');

add_javascript('<script src="'.G5_THEME_JS_URL.'/swiper.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_JS_URL.'/swiper.min.css">', 0);

?>

<!--메인이미지-->
<div class="main_bn_box swiper-container">
    <ul class="main_bn_ul swiper-wrapper">
        <li class="swiper-slide">
            <div class="bn_img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" />
            </div>
        </li>
        <li class="swiper-slide">
            <div class="bn_img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" />
            </div>
        </li>
        <li class="swiper-slide">
            <div class="bn_img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" />
            </div>
        </li>
        <li class="swiper-slide">
            <div class="bn_img">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" />
            </div>
        </li>
    </ul>  
	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
</div>

<script>
var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    paginationClickable: true
});
</script>
<!--} 메인배너 끝-->

<!-- 메인화면 최신글 시작 -->
<?php
//  최신글
$sql = " select bo_table
            from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
            where a.bo_device <> 'pc' ";
if(!$is_admin) {
    $sql .= " and a.bo_use_cert = '' ";
}
$sql .= " order by b.gr_order, a.bo_order ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 스킨은 입력하지 않을 경우 관리자 > 환경설정의 최신글 스킨경로를 기본 스킨으로 합니다.

    // 사용방법
    // latest(스킨, 게시판아이디, 출력라인, 글자수);
    echo latest('theme/basic', $row['bo_table'], 12, 25);
}
?>
<!-- 메인화면 최신글 끝 -->

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>