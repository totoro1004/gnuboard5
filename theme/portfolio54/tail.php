<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

    </div>
</div>
<!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
<footer>
    <div class="container text-center">
        <button id="ft_top"><i class="fa fa-angle-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
        <?php if ($admin['mb_1']) { ?><h4><strong><?php echo $admin['mb_1']; ?> 포트폴리오</strong></h4><?php } ?>
        <?php if ($admin['mb_2']) { ?><Address><?php echo $admin['mb_2']; ?></Address><?php } ?>
        <ul class="list-unstyled footer-cnt">
            <?php if ($admin['mb_4']) { ?><li><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $admin['mb_4']; ?></li><?php } ?>
            <?php if ($admin['mb_3']) { ?><li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:<?php echo $admin['mb_3']; ?>"><?php echo $admin['mb_3']; ?></a></li><?php } ?>
        </ul>
        <br>
        <ul class="list-inline footer-sns">
            <?php if ($admin['mb_8']) { ?><li class="s_facebook"><a href="<?php echo $admin['mb_8']; ?>" target="_blank"><i class="fa fa-lg fa-facebook" aria-hidden="true"></i><span class="sound_only"">페이스북</span></a></li><?php } ?>
            <?php if ($admin['mb_9']) { ?><li class="s_twitter"><a href="<?php echo $admin['mb_9']; ?>" target="_blank"><i class="fa fa-lg fa-twitter" aria-hidden="true"></i><span class="sound_only"">트위터</span></a></li><?php } ?>
            <?php if ($admin['mb_10']) { ?><li class="s_google"><a href="<?php echo $admin['mb_10']; ?>" target="_blank"><i class="fa fa-lg fa-google" aria-hidden="true"></i><span class="sound_only"">구글</span></a></li><?php } ?>
        </ul>
    </div>
</footer>

<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>
<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});

$(function() {
    $("#ft_top").on("click", function() {
        $("html, body").animate({scrollTop:0}, '500');
        return false;
    });
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>