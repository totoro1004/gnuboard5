<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');

add_javascript('<script src="'.G5_THEME_JS_URL.'/unslider.min.js"></script>', 10);
?>

<!--메인배너 {-->
<div id="main_bn_box">
    <div id="main_bn">
        <ul class="bn_ul">
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
        </ul>
    </div>
</div>
<!--} 메인배너-->
<script>
$(function() {
    $("#main_bn").unslider({
        speed: 700,               //  The speed to animate each slide (in milliseconds)
        delay: 3000,              //  The delay between slide animations (in milliseconds)
        keys: true,               //  Enable keyboard (left, right) arrow shortcuts
        dots: true,               //  Display dot navigation
        fluid: false              //  Support responsive design. May break non-responsive designs
    });
    $('.unslider-arrow').click(function() {
        var fn = this.className.split(' ')[1];

        //  Either do unslider.data('unslider').next() or .prev() depending on the className
        unslider.data('unslider')[fn]();
        });
    });
</script>


<section class="idx_cnt">
	
	<div class="lt_li lt_li_left">
		<!-- 전체 게시판 최신글 -->
		<div class="lt">
		    <h2 class="lt_title"><a href="<?php echo G5_BBS_URL ?>/new.php">전체 게시판 최신글</a></h2>
			    <?php
			    // new_latest('스킨', '출력라인', '글자수', 'is_comment', cache_minute)
			    echo new_latest('theme/new_latest', 6, 20, false, 5);
			    ?>
			<div class="lt_more"><a href="<?php echo G5_BBS_URL ?>/new.php"><span class="sound_only">전체 게시판 최신글</span>더보기</a></div>
		</div>
	</div>

	<div class="lt_li">
		<!-- 최신댓글 -->
		<div class="lt">
		    <h2 class="lt_title"><a href="<?php echo G5_BBS_URL ?>/new.php">최신 댓글</a></h2>
			    <?php
			    // new_latest('스킨', '출력라인', '글자수', 'is_comment', cache_minute)
			    echo new_latest('theme/new_latest', 6, 20, true, 5);
			    ?>
			<div class="lt_more"><a href="<?php echo G5_BBS_URL ?>/new.php"><span class="sound_only">최신 댓글</span>더보기</a></div>
		</div>
	</div>

	<div class="lt_li lt_li_left">
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/basic', 'commu', 7, 20);
	    ?>
	</div>
	
	<div class="lt_li">
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/basic', 'free', 7, 20);
	    ?>
	</div>
	
	<div class="lt_li lt_li_left">
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/basic', 'fun', 7, 20);
	    ?>
	</div>
	
	<div class="lt_li">
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/basic', 'free', 7, 20);
	    ?>
	</div>
	
	<?php
	// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	$options = array(
		'thumb_width'    => 170, // 썸네일 width
		'thumb_height'   => 149,  // 썸네일 height
		'content_length' => 40   // 간단내용 길이
	);
	echo latest('theme/gallery', 'gallery', 5, 20, 1, $options);
	?>
</section>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>