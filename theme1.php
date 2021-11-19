<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('_THEME_PREVIEW_', true);

// 테마
if(!function_exists('get_theme_dir')) {
    function get_theme_dir()
    {
        $result_array = array();

        $dirname = G5_PATH.'/'.G5_THEME_DIR.'/';
        $handle = opendir($dirname);
        while ($file = readdir($handle)) {
            if($file == '.'||$file == '..') continue;

            if (is_dir($dirname.$file)) {
                $theme_path = $dirname.$file;
                if(is_file($theme_path.'/index.php') && is_file($theme_path.'/head.php') && is_file($theme_path.'/tail.php'))
                    $result_array[] = $file;
            }
        }
        closedir($handle);
        natsort($result_array);

        return $result_array;
    }
}


// 테마설정 정보
if(!function_exists('get_theme_config_value')) {
    function get_theme_config_value($dir, $key='*')
    {
        $tconfig = array();

        $theme_config_file = G5_PATH.'/'.G5_THEME_DIR.'/'.$dir.'/theme.config.php';
        if(is_file) {
            include($theme_config_file);

            if($key == '*') {
                $tconfig = $theme_config;
            } else {
                $keys = array_map('trim', explode(',', $key));
                foreach($keys as $v) {
                    $tconfig[$v] = trim($theme_config[$v]);
                }
            }
        }

        return $tconfig;
    }
}

if(isset($_GET['theme'])) {
    $theme = strip_tags(trim($_GET['theme']));
} else {
    if($_SESSION['preview_theme']) {
        $_GET['theme'] = $_SESSION['preview_theme'];
        $theme = $_SESSION['preview_theme'];
    } else {
        $_GET['theme'] = 'basic';
        $theme = 'basic';
    }
}

$theme_dir = get_theme_dir();

if(!$theme || !in_array($theme, $theme_dir))
    alert('테마가 존재하지 않거나 올바르지 않습니다.', G5_URL.'/demo/basic');

set_session('preview_theme', $theme);
?>