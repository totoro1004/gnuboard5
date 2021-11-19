<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('_INDEX_', true);

include_once(G5_THEME_MOBILE_PATH.'/head.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

add_javascript('<script src="'.G5_JS_URL.'/jquery.bxslider.js"></script>', 10);
?>
<div class="gal_tab tabs" style="width:100%;">
	<div id="tab_top">
		<div class="pic_tab_heading" role="tablist" aria-label="Entertainment">
			<button role="tab" aria-selected="true" aria-controls="nils-tab" id="nils">Gallery</button>
			<button role="tab" aria-selected="false" aria-controls="agnes-tab" id="agnes" tabindex="-1">Topic</button>
			<button role="tab" aria-selected="false" aria-controls="ctc-tab" id="contact" tabindex="-2">Contact</button>
		</div>
	</div>

	<!-- 메인 최신글 시작 -->
	<div id="tab_bottom">
		<div tabindex="0" role="tabpanel" id="nils-tab" aria-labelledby="nils" class="tab_list">
			<!-- gallery 시작 { -->
		    <?php
		    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
		    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
		    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
		    echo latest('theme/pic_basic', 'gram', 9, 23);
		    ?>
		    <!-- } 탭 갤러리 최신글1 끝 -->
		</div>

		<div tabindex="0" role="tabpanel" id="agnes-tab" aria-labelledby="agnes" hidden="" class="tab_list">
			<!-- gallery 시작 { -->
		    <?php
		    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
		    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
		    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
		    echo latest('theme/basic', 'free', 6, 23);
		    ?>
		    <!-- } topic 끝 -->
	    </div>
	    
	    <div tabindex="0" role="tabpanel" id="ctc-tab" aria-labelledby="ctc-tab" hidden="" class="tab_list">
			<!-- contact 시작 { -->
		    <div id="contact_from">
	            <form name="fcontact" action="<?php echo G5_THEME_URL; ?>/contact_send.php" method="post" onsubmit="return fcontact_submit(this);">
	                <ul>           
	                    <li>
	                    	<label for="con_name">이름</label>
	                    	<input type="text" name="con_name" id="con_name" required class="frm_input required" minlength="2" maxlength="100" placeholder=" 보내실 분의 이름을 입력해 주세요.">
	                    </li>
	                    <li>
	                    	<label for="con_name">이메일</label>
	                    	<input type="text" name="con_email" id="con_email" required class="frm_input required email" maxlength="100" placeholder=" 보내실 분의 이메일을 입력해 주세요.">
	                    </li>
	                    <li>
	                    	<label for="con_tel">연락처</label>
	                    	<input type="text" name="con_tel" id="con_tel" required class="frm_input required telnum" maxlength="20" placeholder=" 예) 010-1234-5678">
	                    </li>
	                    <li>
	                    	<label for="con_message">메시지</label>
	                    	<textarea name="con_message" rows="10" cols="100%" id="con_message" title="내용쓰기" required class="required" placeholder=" 내용을 입력해주세요."></textarea>
	                    </li>
	                    <li class="captcha">
	                    	<?php echo captcha_html(); ?>
	                    </li>
	            	</ul>
	            <button type="submit" id="btn_submit" class="btn_submit">보내기</button>

	        </div>
		    <!-- } contact 끝 -->
	    </div>
    </div>
    <!-- 메인 최신글 끝 -->
</div>

<!-- 최신글 탭 스타일 -->
<script src="<?php echo G5_THEME_JS_URL ?>/latest_tab.js"></script>
<!-- 게시판 슬라이더 -->
<script>
$('.lt_slider').each(function(){
	$(this).bxSlider({
		pager:true,
		hideControlOnEnd: true,
		nextText: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
		prevText: '<i class="fa fa-angle-left" aria-hidden="true"></i>'
	});
});
</script>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>
