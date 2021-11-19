<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<div class="lt_slide">
    <h2><strong><a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_title"><?php echo $bo_subject ?></strong></a></h2>
    <ul>
    <?php for ($i=0; $i<count($list); $i++) { ?>
        <li>
        	<div class="border_box">
				<div class="lt_cate">
			        <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $list[$i]['bo_table'] ?>" class="lt_cate_link <?php echo $list[$i]['bo_table'] ?>"><?php echo $bo_subject; ?></a>
			    </div>
	            <?php
	            if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i> ";
	
	            echo "<a href=\"".$list[$i]['href']."\" class=\"lt_tit\">";
	            if ($list[$i]['is_notice'])
	                echo "<span>".$list[$i]['subject']."</span>";
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
