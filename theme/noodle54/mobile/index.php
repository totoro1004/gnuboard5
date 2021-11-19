<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<!-- 메인배너 { -->
<section id="sbn_idx" class="sbn">
   <h2>메인 배너</h2>
   <ul class="bxslider">
       <li><a href=""><img src="<?php echo G5_THEME_URL; ?>/img/bn_img.jpg" alt="배너이미지 설명입력" /></a></li>
       <li><a href=""><img src="<?php echo G5_THEME_URL; ?>/img/bn_img2.jpg" alt="배너이미지 설명입력" /></a></li>
       <li><a href=""><img src="<?php echo G5_THEME_URL; ?>/img/bn_img.jpg" alt="배너이미지 설명입력" /></a></li>
       <li><a href=""><img src="<?php echo G5_THEME_URL; ?>/img/bn_img2.jpg" alt="배너이미지 설명입력" /></a></li>
   </ul>
</section>
<script>
jQuery(function($){
    var slider = $('.bxslider').show().bxSlider({
        speed:800,
        //pagerCustom: '#bx_pager',
        auto: true,
        useCSS : false,
        controls:false,
        onSlideAfter : function(){
            slider.startAuto();
        }
    });
});
</script>
<!-- } 메인배너 -->

<section id="idx_ct">
    <div class="idx_cnt">
        <div class="col50">
            <!-- 공지사항 -->
            <div class="notice">
            <?php
            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
            echo latest('theme/notice', 'notice', 5, 25);
            ?>
            </div>
            <!-- 매장안내 -->
            <div class="contact">
                <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=contact">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1856.1938793138402!2d2.297369605154015!3d48.8571700097845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa3435bdd46e7a910!2sPizza+la+Gourmandise!5e0!3m2!1sko!2skr!4v1507604629566" width="100%" height="300px" frameborder="0" style="border:0" allowfullscreen></iframe>
            	</a>
            </div> 
        </div>
        
        <!-- 회사소개 -->
        <div class="company col50">
            <p><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company"><img src="<?php echo G5_THEME_URL; ?>/img/company.png" alt="회사소개"><br>자세히보기 <i class="fa fa-angle-right fa-fw"></i></a></p>
        </div>
    </div>
    
	<div class="idx_cnt">
		<!-- 이벤트 -->
        <div class="col100">
            <?php
            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
            $options = array(
                'thumb_width'    => 950, // 썸네일 width
                'thumb_height'   => 300,  // 썸네일 height
                'content_length' => 0   // 간단내용 길이
            );
            echo latest('theme/gallery', 'free', 1, 25, 1, $options);
            ?> 
        </div>
        <!-- 최신글 -->
        <div class="col100">
            <div class="food food1 col50">
                <?php
                // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
                // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
                // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
                $options = array(
                    'thumb_width'    => 500, // 썸네일 width
                    'thumb_height'   => 300,  // 썸네일 height
                    'content_length' => 0   // 간단내용 길이
                );
                echo latest('theme/basic', 'gallery', 9, 25, 1, $options);
                ?> 
			</div>
          	<div class="food food2 col50">
               <?php
                // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
                // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
                // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
                $options = array(
                    'thumb_width'    => 476, // 썸네일 width
                    'thumb_height'   => 300,  // 썸네일 height
                    'content_length' => 0   // 간단내용 길이
                );
                echo latest('theme/gallery', 'noodle', 1, 25, 1, $options);
                ?>  
			</div>
        </div>
    </div>
</section>

<script>
	$("#container_ct").addClass("idx-container");
</script>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>