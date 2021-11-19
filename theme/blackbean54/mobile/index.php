<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<section class="idx">
	<div class="idx_head_gall">
	    <!-- 사진 최신글1 { -->
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/gallery', 'gallery', 3, 23);
	    ?>
	    <!-- } 사진 최신글1 끝 -->
	</div>
	
	<div class="idx_gall">
	    <!--  사진 최신글2 { -->
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/gallery_line', 'free', 4, 23);
	    ?>
	    <!-- } 사진 최신글2 끝 -->
	
	    <!--  사진 최신글2 { -->
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/gallery_block', 'gallery', 4, 23);
	    ?>
	    <!-- } 사진 최신글2 끝 -->
	</div>
	
	<div class="idx_head_gall">
	    <!-- 최신글 { -->
	    <?php
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	    echo latest('theme/basic', 'free', 9, 23);
	    ?>
	    <!-- } 최신글 끝 -->
	</div>
</section>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>