<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 250;
$thumb_height = 250;
?>

<div class="gall_b_lt">
    <h2 class="lat_title"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <ul>
    <?php
    for ($i=0; $i<count($list); $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['src']) {
        $img = $thumb['src'];
    } else {
        $img = G5_IMG_URL.'/no_img.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" >';
    ?>
    <li>
    	<div class="pic_inner">
            <a href="<?php echo $list[$i]['href'] ?>" class="lt_img">
            	<?php echo $img_content; ?>
            	<span class="more">자세히</span>
            </a>
            <?php
            echo "<a href=\"".$list[$i]['href']."\" class=\"all_b_tit\"> ";
            if ($list[$i]['is_notice'])
                echo "<span>".$list[$i]['subject']."</span>";
            else
                echo $list[$i]['subject'];

            echo "</a>";
            ?>
            <div class="lat_detail">
            	<?php echo get_text(strip_tags($list[$i]['wr_content']), 100); ?>
            </div>
        </div>
    </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
</div>
