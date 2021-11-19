<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 400;
$thumb_height = 500;
?>

<div class="lt_team">
    <h2 class="lt_title"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <div class="bg"><span></span><span></span><span></span></div>
    <ul>
    <?php
    for ($i=0; $i<count($list); $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['src']) {
        $img = $thumb['src'];
    } else {
        $img = G5_THEME_IMG_URL.'/noimage.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" >';
    ?>
        <li class="team_li">
            <a href="<?php echo $list[$i]['href'] ?>" class="lt_img"><?php echo $img_content; ?></a>  
            <div class="lt_wr">
                <i class="fa fa-user-md"></i>
                <a href="<?php echo $list[$i]['href'] ?>"  class="lt_tit"> <?php echo $list[$i]['subject']; ?>  </a>
                <div class="lt_detail"><?php echo $list[$i]['wr_content'] ?> </div>
            </div>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>

</div>
