<?php
if(isset($_POST['theme']) && $_POST['theme']) {
    define('_THEME_PREVIEW_', true);
}

include_once('./_common.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');

$url = strip_tags(trim($_SERVER['HTTP_REFERER']));
$urlencode = urlencode($url);

$place = $_POST['place'];

ob_start();
?>

<?php if($place == 'head') { ?>
<button type="button" id="user_close" class="hd_closer"><span class="sound_only">메뉴닫기</span><i class="fa fa-times" aria-hidden="true"></i></button>
<div class="user_menu_inner">
<?php } ?>

<?php echo outlogin("theme/basic"); // 로그인, 로그아웃 ?>

<ul class="hd_nb">
    <li><a href="<?php echo G5_BBS_URL ?>/faq.php"><i class="fa fa-question-circle"></i> 자주묻는 질문</a></li>
    <li><a href="<?php echo G5_BBS_URL ?>/qalist.php"><i class="fa fa-comments"></i> 1:1문의</a></li>
    <li class="hd_visit">
        <a href="<?php echo G5_BBS_URL ?>/current_connect.php"><i class="fa fa-users"></i> 접속자 <span class="visit_num"><?php echo connect("theme/basic"); // 현재 접속자수 ?></span></a>
        <button class="visit_btn visit_open"><span class="sound_only">접속자집계 보기</span></button>
        <div class="open_area oa_open oa_close">
            <?php echo visit("theme/basic"); // 방문자수 ?>
        </div>
    </li>
    <li><a href="<?php echo G5_BBS_URL ?>/new.php"><i class="fa fa-history"></i> 새글</a></li>
</ul>
<?php echo poll("theme/basic"); // 설문조사 ?>

<?php if($place == 'head') { ?>
</div>
<?php }

$content = ob_get_contents();
ob_end_clean();

echo $content;
?>
