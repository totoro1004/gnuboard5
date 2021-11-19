<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 500;
?>

<?php
$count = count($list);
for ($i=0; $i<$count; $i++) {
$bo_subject = mb_substr($list[$i]['bo_subject'],0,8,"utf-8"); // 게시판명 글자수
$thumb = get_list_thumbnail($list[$i]['bo_table'], $list[$i]['wr_id'], $thumb_width, $thumb_height);
if($thumb['src']) {
    $img = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" width="'.$thumb_width.'">';
}
?>
<div class="box lt">
    <div class="lt_cate">
        <a href="<?php echo get_pretty_url($list[$i]['bo_table']); ?>" class="lt_cate_link <?php echo $list[$i]['bo_table'] ?>"><?php echo $bo_subject; ?></a>
        <span class="lt_date"><?php echo $list[$i]['datetime'] ?></span>
    </div>
    <?php if ($thumb['src']) { ?> <a href="<?php echo $list[$i]['href'] ?>" class="lt_img"><?php echo $img; ?></a>  <?php } ?>
    
    <div class="lt_info">
        <span class="lt_name"><?php echo $list[$i]['wr_name'] ?></span>
        <a href="<?php echo $list[$i]['href']; ?>" class="lt_tit"><?php echo $list[$i]['subject']; ?></a>
        <div class="lt_detail"> <?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), 50), 1); ?></div>
        <a href="<?php echo $list[$i]['href']; ?>" class="lt_more">자세히보기</a>
    </div>
</div>
<?php } ?>
<?php if ($i == 0) echo '<div class="empty_lt">게시물이 없습니다.</div>'; ?>
