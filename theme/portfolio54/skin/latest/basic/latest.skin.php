<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<div class="lat">
    <h2 class="lat_title"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <ul class="main_works">
    <?php for ($i=0; $i<count($list); $i++) {  ?>
        <li class="col-sm-6 col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
        	<div class="lat_innr">
	            <?php
	            if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";
	
	            echo "<a href=\"".$list[$i]['href']."\" class=\"font-fam\"> ";
	            if ($list[$i]['is_notice'])
	                echo "<strong>".$list[$i]['subject']."</strong>";
	            else
	                echo $list[$i]['subject'];
	
	            echo "</a>";
				
				if ($list[$i]['icon_hot']) echo "<span class=\"hot_icon\"><i class=\"fa fa-heart\" aria-hidden=\"true\"></i><span class=\"sound_only\">인기글</span></span>";
				if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">새글</span></span>";

	            ?>
	            <div class="lat_detail">
	            	<?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), $content_length), 1); ?>
	            </div>
	            <div class="lat_info">
					<span class="lt_nick"><?php echo $list[$i]['name'] ?></span>
	            	<span class="lt_date"><?php echo $list[$i]['datetime2'] ?></span>              
	            </div>
            </div>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
</div>
<script>
$('.main_works').bxSlider({
	auto: true,
    maxSlides: 3,
    slideMargin:5,
    pager:false
});
</script>