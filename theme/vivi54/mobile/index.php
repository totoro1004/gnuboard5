<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('_INDEX_', true);

include_once(G5_THEME_MOBILE_PATH.'/head.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

add_javascript('<script src="'.G5_JS_URL.'/jquery.bxslider.js"></script>', 10);

?>

<div id="fullpage">
	<div id="firstPage" class="section auto_page auto_page1 ">
		<h2><?php echo outlogin("theme/hello"); ?></h2>
		<div class="auto_page_innr fp-scroller">
			<div class="left">
				<div class="border_box margin_bottom10">
					<?php
				    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
				    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
				    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
				    echo latest('theme/photo', 'free', 1, 20);
				    ?>
			    </div>
			    <div class="">
				    <?php
				    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
				    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
				    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
				    echo latest('theme/slide', 'gallery', 2, 15);
				    ?>
			    </div>
			</div>
			<div class="right mobile_display">
				<div class="left margin_bottom10">
					<div class="border_box">
						<?php echo outlogin("theme/1page"); ?>
						<?php echo visit("theme/basic"); // 방문자수 ?>
					</div>
				</div>
				<div class="right margin_bottom10">
					<?php echo poll("theme/basic"); // 설문조사 ?>
				</div>
				<div class="inx_banner">
					<?php
				    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
				    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
				    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
				    echo latest('theme/banner', 'gallery', 3, 20);
				    ?>
				</div>
		    </div>
		</div>
	</div>
	
	<div class="section auto_page auto_page2">
		<div class="auto_page_innr fp-scroller">
			<?php
		    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
		    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
		    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
		    echo latest('theme/gallery', 'gallery', 8, 20);
		    ?>
		 </div>
	</div>
	
	<div class="section auto_page auto_page3">
		<div class="auto_page_innr fp-scroller">
			<div class="slide">
				<?php
			    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
			    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
			    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
			    echo latest('theme/basic', 'free', 8, 15);
			    ?>
			</div>
			<div class="slide">
				<?php
			    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
			    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
			    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
			    echo latest('theme/basic', 'free', 8, 15);
			    ?>
			</div>
		</div>
	</div>
	
	<div class="section auto_page auto_page4">
		<div class="auto_page_innr fp-scroller">
			<h2>오시는길</h2>
			<p><span>주소 : 서울 역삼동 에스아이알 병원</span>대표전화 : 1234-1234</p>
			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25325.27576056437!2d127.02928809999999!3d37.4923615!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2z7JeQ7Y6g7YOR!5e0!3m2!1sko!2skr!4v1551171326131" width="" height="" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
	</div>
	
	<div class="section auto_page auto_page_last fp-auto-height fp-section fp-table fp-completely">
		<div class="fp-tableCell">
        <div id="idx_ft_copy">
	        <div class="ft_company">
	            <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a>
	            <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보처리방침</a>
	            <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">서비스이용약관</a>
	        </div>
	        Copyright &copy; <b>소유하신 도메인.</b> All rights reserved.<br>
	    </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
	$('#fullpage').fullpage({
		sectionsColor: ['#f8b202', '#314fa3', '#4CAF50', '#9C27B0', '#fff'],
		//slidesNavigation: true,
		scrollOverflow: true,
		navigation: true,
		navigationPosition: 'right',
		navigationTooltips: ['환영합니다!', '갤러리', '자유게시판', '오시는길'],
	});
});
$("#container").removeClass("container").addClass("idx-container");
$("html").addClass("idx-html");
</script>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>