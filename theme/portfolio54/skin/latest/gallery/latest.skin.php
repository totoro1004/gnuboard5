<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_THEME_LIB_PATH.'/thumbnail2.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);

$thumb_width  = isset($options['thumb_width']) ? $options['thumb_width'] : $board['bo_gallery_width'];
$thumb_height = isset($options['thumb_height']) ? $options['thumb_height'] : $board['bo_gallery_height'];
$content_length = isset($options['content_length']) ? $options['content_length'] : 30;

?>

<div class="arrows-inside highlight-selected mb-6 flickity-enabled is-draggable">
	<div class="gallery flickity-viewport">
	    <ul class="main_works flickity-slider" data-flickity="{ &quot;autoPlay&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }">
	        <?php
	        for ($i=0; $i<count($list); $i++) {
	            $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);
	    
	            if($thumb['src']) {
	                $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
	            } else {
	                $noimg = $latest_skin_path.'/img/no_img.gif';
	                $img_content = '<span>'.get_noimage_thumbnail($bo_table, $noimg, $thumb_width, $thumb_height, $class='no_img').'</span>';
	            }
	
	            $href_id = 'p_player_btn'.$i;
	        ?>
	            <li class="carousel-cell col-lg-4 col-md-5 col-9 px-2 py-3">
	                <div class="lt_image">
	                    <?php
	                    echo "<a href=\"".$list[$i]['href']."\" class=\"lt_tit\">";
	                    echo $img_content;
	                    echo "</a>";
						?>
					</div>
	            </li>
	        <?php }  ?>
	    
	        <?php if ($i == 0) { //게시물이 없을 때  ?>
	            <li class="no_bd">게시물이 없습니다.</li>
	        <?php }  ?>
	    </ul>
	</div>
</div>
<!-- } <?php echo $bo_subject; ?> 최신글 끝 -->