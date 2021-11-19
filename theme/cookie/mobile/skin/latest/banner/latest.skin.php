<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
global $is_admin; 
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
add_javascript('<script src="'.G5_JS_URL.'/jquery.bxslider.js"></script>', 10);

$thumb_width = 300;
$thumb_height = 200;
?>

<!-- <?php echo $bo_subject; ?> 최신글 시작 { -->
<div class="lt_bn">
    <ul>
    <?php
    for ($i=0; $i<count($list); $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['ori']) {
        $img = $thumb['ori'];
    } else {
        $img = G5_IMG_URL.'/no_img.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    ?>
        <li class="list_<?php echo $i ?>" style="background-image:url('<?php echo $img; ?>')">
            <div class="bg"></div>
            <div class="bn_txt">
                <div class="txt_wr">
                        <?php
                        echo "<div class=\"bn_tit\">";
                            echo $list[$i]['subject'];
                        echo "</div>";
                        ?>

                        <div class="bn_detail pc_view"> <?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), 50), 1); ?></div>

                        <?php if ($list[$i]['wr_link1']) { ?>
                        <a href="<?php echo $list[$i]['wr_link1']; ?>" class="bn_view">VEIW</a>
                        <?php } ?>

                </div>
            </div>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
    <?php if ($is_admin) { ?><a href="<?php echo get_pretty_url($bo_table); ?>" class="bn_link"><i class="fa fa-cog"></i><span class="sound_only"><?php echo $bo_subject ?></span></a> <?php }  ?>

    <div id="bx_pager">
        <?php
        for ($i=0; $i<count($list); $i++) { 
        $i1 = $i1 + 1;
        ?>
        <a data-slide-index="<?php echo $i ?>" href="">0<?php echo $i1 ?><span></span></a>
        <?php 
        }  ?>

        <?php if (count($list) == 0) { //게시물이 없을 때  ?>
        
        <?php }  ?>
    </div>

    <button type="button" class="btn_bottom">아래로</button>
</div>


<script>
$('.lt_bn ul').show().bxSlider({

    pagerCustom: '#bx_pager',
    controls:false,
    auto:true,
    pause: 8000,
    speed: 800,

     onSliderLoad: function () {
        $('.lt_bn .bn_txt').eq(1).addClass('active-slide');
        $(".bn_txt.active-slide").addClass("test");
    },
    onSlideAfter: function (currentSlideNumber, totalSlideQty, currentSlideHtmlObject) {
        //console.log(currentSlideHtmlObject);
        $('.active-slide').removeClass('active-slide');
        $('.lt_bn .bn_txt').eq(currentSlideHtmlObject + 1).addClass('active-slide');
        $(".bn_txt.active-slide").addClass("test");

    },
    onSlideBefore: function () {
        $(".bn_txt.active-slide").removeClass("test");
        $(".one.bn_txt.active-slide").removeAttr('style');

    }
});

 function parallax(){
    var scrolled = $(window).scrollTop();
    $('.lt_bn ul li').css('background-position',"0 "+  (scrolled * 1 ) + 'px');
}
$(window).scroll(function(){
    parallax();
});


jQuery(document).ready(function($) {
    $(".btn_bottom").on("click", function() {           
        var position=$('#index').offset(); // 위치값
        $('html,body').animate({scrollTop:position.top},400); // 이동
    });
});
</script>

<!-- } <?php echo $bo_subject; ?> 최신글 끝 -->