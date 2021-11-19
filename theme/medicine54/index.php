<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="container_middle" class="idx_section idx_section2">
	<div class="innr">
		<div class="block block1">
			<h3>의료진/진료과 보기</h3>
			<p>원하는 정보와 진료예약을<br>빠르게 찾을 수 있습니다.</p>
			<a href="#" class="go_bd">조회하기</a>
			<span class="bg"></span>
		</div>
		<div class="block block2">
			<h3>온라인 진료예약</h3>
			<p>나의 일정에 맞춰 자유롭게<br>진료 예약을 할 수 있습니다.</p>
			<a href="#" class="go_bd">예약하기</a>
			<span class="bg"></span>
		</div>
		<div class="block block3">
			<h3>건강검진 예약</h3>
			<p>전문 상담사를 통하여<br>빠른 예약을 도와드립니다.</p>
			<a href="#" class="go_bd">예약하기</a>
			<span class="bg"></span>
		</div>
		<div class="block block4">
			<?php echo outlogin('theme/main'); // 외부 로그인 ?>
		</div>
		<div class="block block5">
		    <?php
		    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
		    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
		    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
		    echo latest('theme/news', 'medicine_theme1', 5, 23);
		    ?>
		</div>
		<div class="block block6">
			<h3>인터넷증명서 발급</h3>
			<p>위임장, 동의서, 증명서,<br>의무기록 사본을 받아보실 수<br>있습니다.</p>		
			<a href="#" class="go_bd">조회하기</a>
			<span class="bg"></span>
		</div>
		<div class="block block7">
			<h3>Q&A</h3>
			<p>의견 또는 건의사항을<br>등록하실 수 있습니다.</p>
			<a href="<?php echo G5_BBS_URL ?>/qalist.php" class="go_bd">문의등록</a>
			<span class="bg"></span>
		</div>
	</div>
</section>
		    
<section class="idx_section">
	<div class="innr">
	<?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    echo latest('theme/basic', 'medicine_theme2', 4, 23);
    ?>
    </div>
</section>

<section class="idx_section idx_section2">
	<div class="innr">
	<?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    echo latest('theme/gallery_block', 'medicine_theme3', 6, 20);
    ?>
    </div>
</section>

<section id="contact" class="idx_section">
	<div class="innr">
		<h2>오시는길</h2>
		<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25325.27576056437!2d127.02928809999999!3d37.4923615!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2z7JeQ7Y6g7YOR!5e0!3m2!1sko!2skr!4v1551171326131" width="1100" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
		<p>주소 : <span>서울 역삼동 에스아이알 병원</span></p>
		<p>대표전화 : <span>1234-1234</span></p>
	</div>
</section>

<script>
	$("#container").removeClass("container").addClass("idx-container");
</script>
<?php include_once(G5_THEME_PATH.'/tail.php'); ?>