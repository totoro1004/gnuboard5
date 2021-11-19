<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
        </div>
        <div id="idx_right">

            <?php echo outlogin('theme/basic'); // 외부 로그인 ?>

            <?php
            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
            echo latest('theme/notice', 'notice', 4, 33);
            ?>

            <ul id="hd_nb" class="box">
                <li class="hd_nb1"><a href="<?php echo G5_BBS_URL ?>/qalist.php" id="snb_qa"><i class="fa fa-comments" aria-hidden="true"></i>1:1문의</a></li>
                <li class="hd_nb2"><a href="<?php echo G5_BBS_URL ?>/faq.php" id="snb_faq"><i class="fa fa-question-circle" aria-hidden="true"></i>FAQ</a></li>
                <li class="hd_nb3"><a href="<?php echo G5_BBS_URL ?>/current_connect.php" id="snb_cnt"><i class="fa fa-users" aria-hidden="true"></i>접속자 <span><?php echo connect('theme/basic'); // 현재 접속자수 ?></span></a></li>
                <li class="hd_nb4"><a href="<?php echo G5_BBS_URL ?>/new.php" id="snb_new"><i class="fa fa-history" aria-hidden="true"></i>새글</a></li>
                
            </ul>

            <?php echo visit('theme/basic'); // 방문자수 ?>
        </div>

    </div>
</div>


<div id="ft">
    <div class="ft_wr">
        <div id="ft_company">
            <a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a>
            <a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a>
            <a href="<?php echo get_pretty_url('content', 'provision'); ?>">서비스이용약관</a>
        </div>
        <div id="ft_copy">Copyright &copy; <b>소유하신 도메인.</b> All rights reserved.<br>
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

$(function () {
    
    $("#gnb_open").on("click", function() {
        $("#gnb").show();
    });

    $("#gnb_close, .gnb_bg").on("click", function() {
        $("#gnb").hide();
    });

    $("#container").on("click", function() {
        $(".hd_div").hide();
    });

    $(".btn_gnb_op").click(function(){
        $(this).toggleClass("btn_gnb_cl").next(".gnb_2dul").slideToggle(300);
        
    });

    $("#sch_op_btn").on("click", function() {
        $("#hd_sch").show();
    });

    $(".sch_cl_btn").on("click", function() {
        $("#hd_sch").hide();
    });

    $("#user_open").on("click", function() {
        $(".ol").show();
    });

    $(".ol .btn_close, .ol_bg").on("click", function() {
        $(".ol").hide();
    });
});


jQuery(function($) {

    $( document ).ready( function() {

        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        
        //상단고정
        if( $(".top").length ){
            var jbOffset = $(".top").offset();
            $( window ).scroll( function() {
                if ( $( document ).scrollTop() > jbOffset.top ) {
                    $( '.top' ).addClass( 'fixed' );
                }
                else {
                    $( '.top' ).removeClass( 'fixed' );
                }
            });
        }

        //상단으로
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });

    });
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>