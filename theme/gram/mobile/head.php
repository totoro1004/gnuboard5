<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<div id="all_wrap">
<header id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div class="to_content"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>

    <div id="hd_wrapper">
		<?php if ($is_admin == 'super' || (isset($is_auth) && $is_auth)) { ?>
			<a href="<?php echo G5_ADMIN_URL ?>" class="hd_admin"><span class="sound_only">관리자</span></a>
		<?php } ?>
		
		<?php echo outlogin('theme/name'); // 로고 ?>
		
		<?php if ($is_member) {  ?>
		<a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php" class="hd_modi"><span class="sound_only">정보수정</span></a>
        <?php }  ?>    

        <div id="menu">
        	<?php echo outlogin('theme/profile'); // 외부 로그인 ?>
        	<div class="menu_right">
				<?php echo outlogin('theme/menu'); // 외부 로그인 ?>
				<button type="button" id="gnb_open" class="hd_opener">카테고리<span class="sound_only"> 메뉴열기</span></button>
				<div id="gnb" class="hd_div">
		            <button type="button" id="gnb_close" class="hd_closer"><span class="sound_only">메뉴 닫기</span><i class="fa fa-times" aria-hidden="true"></i></button>
		            <ul id="gnb_1dul">
		            <?php
		            $sql = " select *
		                        from {$g5['menu_table']}
		                        where me_mobile_use = '1'
		                          and length(me_code) = '2'
		                        order by me_order, me_id ";
		            $result = sql_query($sql, false);
		
		            for($i=0; $row=sql_fetch_array($result); $i++) {
		            ?>
		                <li class="gnb_1dli">
		                    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_1da"><?php echo $row['me_name'] ?></a>
		                    <?php
		                    $sql2 = " select *
		                                from {$g5['menu_table']}
		                                where me_mobile_use = '1'
		                                  and length(me_code) = '4'
		                                  and substring(me_code, 1, 2) = '{$row['me_code']}'
		                                order by me_order, me_id ";
		                    $result2 = sql_query($sql2);
		
		                    for ($k=0; $row2=sql_fetch_array($result2); $k++) {
		                        if($k == 0)
		                            echo '<button type="button" class="btn_gnb_op"><span class="sound_only">하위분류</span></button><ul class="gnb_2dul">'.PHP_EOL;
		                    ?>
		                        <li class="gnb_2dli"><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><span></span><?php echo $row2['me_name'] ?></a></li>
		                    <?php
		                    }
		
		                    if($k > 0)
		                        echo '</ul>'.PHP_EOL;
		                    ?>
		                </li>
		            <?php
		            }
		
		            if ($i == 0) {  ?>
		                <li id="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하세요.<?php } ?></li>
		            <?php } ?>
		            </ul>
		        </div>
			</div>
        </div>
    </div>
</header>

<div id="wrapper">
    <div id="container">
		<?php if (!defined("_INDEX_")) { ?>
    	<h2 id="container_title" class="top" title="<?php echo get_text($g5['title']); ?>">
    		<a href="javascript:history.back();"><i class="fa fa-chevron-left" aria-hidden="true"></i><span class="sound_only">뒤로가기</span></a> <?php echo get_head_title($g5['title']); ?>
    	</h2>
    	<?php } ?>