<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 880;
$thumb_height = 600;
?>

<div class="lt_review">
    <h2><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <div class="lt_rv_wr">
    <ul>
    <?php
    for ($i=0; $i<count($list); $i++) {

    ?>
        <li>
            <div class="lt_wr">
                <span class="lt_img"><?php echo get_member_profile_img($list[$i]['mb_id']); ?></span>
                <?php
                echo "<a href=\"".$list[$i]['href']."\" class=\"lt_tit\"><strong>";
                if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";
                if ($list[$i]['is_notice'])
                    echo "<strong>".$list[$i]['subject']."</strong>";
                else
                    echo $list[$i]['subject'];
                 if ($list[$i]['icon_new']) echo " <span class=\"new_icon\">NEW</span>";
                echo "</strong></a>";

                ?>
                <p class="lt_detail"> <?php echo get_text(cut_str(strip_tags($list[$i]['wr_content']), 85), 1); ?></p>
                <span class="lt_date"><?php echo $list[$i]['name'] ?>  <span class="date"><?php echo $list[$i]['datetime2']; ?></span></span>
            </div>                                                                   
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
    </div>
</div>
