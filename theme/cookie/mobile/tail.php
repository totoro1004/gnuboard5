<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
    </div>
</div>


<div id="ft">

    <div class="ft_info">
        <?php
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
        // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
        echo latest('theme/basic', 'notice', 3, 33);
        ?>
        <div id="ft_contact">
            <h2>회사명</h2>
            <ul>
                <li><i class="fa fa-phone"></i>02-123-1234</li>
                <li><i class="fa fa-map-marker"></i>서울특별시 강남구 어딘가</li>
                <li class="time"><i class="fa fa-clock-o"></i><span class="text">평일</span><span class="num">9:00 ~ 18:00</span></li>
                <li class="time"><i class="fa fa-clock-o"></i><span class="text">주말 / 공휴일</span><span class="num">9:00 ~ 18:00</span></li>
                <li class="res"><a href="">예약하기</a></li>
            </ul>
        </div>
    </div>


    <div class="ft_wr">
        <div id="ft_copy">

            Copyright &copy; <b>소유하신 도메인.</b> All rights reserved.<br>
        </div>


    </div>
    <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
    <?php
    if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { ?>
    <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a>
    <?php
    }

    if ($config['cf_analytics']) {
        echo $config['cf_analytics'];
    }
    ?>
</div>



<script>
jQuery(function($) {

    $( document ).ready( function() {

        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        
 
        if ($('#top_btn').length) {
            var scrollTrigger = 100, // px
                backToTop = function () {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $('#top_btn').addClass('show');
                    } else {
                        $('#top_btn').removeClass('show');
                    }
                };
            backToTop();
            $(window).on('scroll', function () {
                backToTop();
            });
            $('#top_btn').on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 700);
            });
        }
    });
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>