<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 245;
$thumb_height = 200;
add_javascript('<script src="'.G5_THEME_JS_URL.'/owl.carousel.min.js"></script>', 10);
?>

<div class="lt_slider_li">
	<h2>
		<a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a>
		<p>최신 갤러리 게시판 설명 내용입니다.</p>
		<a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_more"><span class="sound_only"><?php echo $bo_subject ?></span>더보기</a>
	</h2>
	<ul>
	<?php 
		for ($i=0; $i<count($list); $i++) {
		    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);
		
		    if($thumb['src']) {
		        $img = $thumb['src'];
		    } else {
		        $img = G5_THEME_IMG_URL.'/no_img.png';
		        $thumb['alt'] = '이미지가 없습니다.';
		    }
		    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" >';
	?>
	    <li>
	    	<div class="lt_cnt border_box">
	    		
			    <a href="<?php echo $list[$i]['href'] ?>" class="lt_img"><?php echo $img_content; ?></a>
		        <div class="lt_cate">
			        <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $list[$i]['bo_table'] ?>" class="lt_cate_link <?php echo $list[$i]['bo_table'] ?>"><?php echo $bo_subject; ?></a>
			    </div>
			    <?php
		        if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i> ";
		        echo "<a href=\"".$list[$i]['href']."\" class=\"lt_tit\">";
		        if ($list[$i]['is_notice'])
		            echo "<strong>".$list[$i]['subject']."</strong>";
		        else
		            echo $list[$i]['subject'];
		        echo "<span class=\"icon\">";
	            if ($list[$i]['icon_new']) echo " <span class=\"new_icon\"><b class=\"sound_only\">새글</b>N</span>";
	            if ($list[$i]['icon_hot']) echo " <span class=\"heart_icon\"><b class=\"sound_only\">인기글</b>H</span>";
				echo "</span>";
				echo "</a>";
	
		        ?>
		        <div class="lt_detail">
	            	<?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), $content_length), 1); ?>
	            </div>
	            <div class="lt_info">
	            	<span><?php echo $list[$i]['name'] ?></span>
					<span class="lt_date"><i class="far fa-clock"></i> <?php echo $list[$i]['datetime'] ?></span>
	        	</div>
	        </div>
	    </li>
	<?php } ?>
	<?php if (count($list) == 0) { //게시물이 없을 때 ?>
	<li class="empty_li">게시물이 없습니다.</li>
	<?php } ?>
	</ul>
</div>
<script>
$(document).ready(function(){
    $('.lt_slider_li ul').bxSlider({
        maxSlides:4,
        minSlides:2,
        pager:false,
        controls:false,
        auto:true,
        slideMargin:20
    });
});
</script>
