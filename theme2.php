<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// theme.config.php 미리보기 게시판 스킨이 설정돼 있다면
$keys = 'set_default_skin, preview_board_skin, preview_mobile_board_skin, cf_member_skin, cf_mobile_member_skin, cf_new_skin, cf_mobile_new_skin, cf_search_skin, cf_mobile_search_skin, cf_connect_skin, cf_mobile_connect_skin, cf_faq_skin, cf_mobile_faq_skin, bo_gallery_cols, bo_gallery_width, bo_gallery_height, bo_mobile_gallery_width, bo_mobile_gallery_height, bo_image_width';

$tconfig = get_theme_config_value($theme, $keys);

if($tconfig['set_default_skin']) {
    $fields = array_map('trim', explode(',', $keys));

    foreach($fields as $val) {
        if($val == 'set_default_skin')
            continue;

        if(strlen($tconfig[$val])) {
            if($val == 'preview_board_skin') {
                $board['bo_skin'] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
                if($board['bo_table'] == 'gallery')
                    $board['bo_skin'] = 'theme/gallery';
                continue;
            }

            if($val == 'preview_mobile_board_skin') {
                $board['bo_mobile_skin'] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
                if($board['bo_table'] == 'gallery')
                    $board['bo_mobile_skin'] = 'theme/gallery';
                continue;
            }

            if(preg_match('#^cf_.+$#', $val)) {
                $config[$val] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
                continue;
            }

            if(preg_match('#^bo_.+$#', $val)) {
                $board[$val] = (int)$tconfig[$val];
                continue;
            }
        }
    }

    unset($fields);
}

unset($keys);
unset($tconfig);
unset($theme_dir);
?>