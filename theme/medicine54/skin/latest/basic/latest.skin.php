<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
add_javascript('<script src="'.G5_THEME_JS_URL.'/owl.carousel.min.js"></script>', 10);
?>
<div class="lat">
    <h2 class="lat_title"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <ul>
    <?php for ($i=0; $i<count($list); $i++) {  ?>
        <li>
            <?php echo "<a href=\"".$list[$i]['href']."\" class=\"in\"> " ?>
            
            <div class="lat_tit">
            <?php 
            if ($list[$i]['is_notice'])
                echo "<strong>".$list[$i]['subject']."</strong>";
            else
                echo $list[$i]['subject'];
            
			
			if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">새글</span></span>";
            ?>
			</div>
			
			<div class="lat_detail">
            	<?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), $content_length), 1); ?>
            </div>
			<div class="lat_info">
				<span class="lt_date">날짜 <?php echo $list[$i]['datetime2'] ?></span>
				<?php
				if ($list[$i]['comment_cnt'])  echo "
	            <span class=\"lt_cmt\">댓글 ".$list[$i]['comment_cnt']."</span>";
				?>
			</div>
			<?php echo "</a>" ?>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
</div>
<script>

$(document).ready(function(){
    $('.lat ul').bxSlider({
        maxSlides:4,
        minSlides:4,
        pager:false,
        controls:true,
        auto:true,
        //slideMargin:20
    	nextText: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
        prevText: '<i class="fa fa-angle-left" aria-hidden="true"></i>'
    });
});
</script>
