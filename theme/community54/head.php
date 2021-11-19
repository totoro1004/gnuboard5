<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_THEME_LIB_PATH.'/new_lastest.lib.php');
?>

<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
    
	<div id="hd_top">
        <div id="hd_tnb">
            <ul id="tnb">
                <?php if ($is_member) {  ?>
                <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보수정</a></li>
                <li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
                <?php } else {  ?>
                <li><a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a></li>
                <li><a href="<?php echo G5_BBS_URL ?>/login.php"><b>로그인</b></a></li>
                <?php }  ?>
                <li><a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a></li>
                <li><a href="<?php echo G5_BBS_URL ?>/qalist.php">1:1문의</a></li>
                <li><a href="<?php echo G5_BBS_URL ?>/current_connect.php">접속자 <?php echo connect('theme/basic'); // 현재 접속자수, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  ?></a></li>
            </ul>
            <p class="hd_home">
            	<a href="<?php echo G5_URL ?>"><i class="fa fa-home" aria-hidden="true"></i><span class="sound_only">메인페이지로 가기</span></a>
            	<?php if ($is_admin) {  ?>
                <a href="<?php echo G5_ADMIN_URL ?>" class="admin"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">관리자</span></a>
                <?php }  ?>
            </p>
        </div>
    </div>

    <div id="hd_wrapper">
        <div id="logo">
            <!-- 
            	관리자 설정에서 로고를 등록할 경우엔 아래 코드를 사용하세요.
            	<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
             -->
        	<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.gif" alt="<?php echo $config['cf_title']; ?>"></a>
        </div>
        <fieldset id="hd_sch">
            <legend>사이트 내 전체검색</legend>
            <form name="fsearchbox" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);">
            <input type="hidden" name="sfl" value="wr_subject||wr_content">
            <input type="hidden" name="sop" value="and">
            <label for="sch_stx" class="sound_only">검색어 필수</label>
            <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
            <button type="submit" id="sch_submit" value="검색"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
            </form>
            <script>
            function fsearchbox_submit(f)
            {
                if (f.stx.value.length < 2) {
                    alert("검색어는 두글자 이상 입력하십시오.");
                    f.stx.select();
                    f.stx.focus();
                    return false;
                }

                // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
                var cnt = 0;
                for (var i=0; i<f.stx.value.length; i++) {
                    if (f.stx.value.charAt(i) == ' ')
                        cnt++;
                }

                if (cnt > 1) {
                    alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                    f.stx.select();
                    f.stx.focus();
                    return false;
                }

                return true;
            }
            </script>
        </fieldset>
    </div>
    
    <nav id="gnb">
        <h2>메인메뉴</h2>
        <ul id="gnb_1dul">
            <?php
            $sql = " select *
                        from {$g5['menu_table']}
                        where me_use = '1'
                          and length(me_code) = '2'
                        order by me_order, me_id ";
            $result = sql_query($sql, false);
            $gnb_zindex = 999; // gnb_1dli z-index 값 설정용

            for ($i=0; $row=sql_fetch_array($result); $i++) {
            ?>
            <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex--; ?>">
                <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_1da"><?php echo $row['me_name'] ?></a>
                <?php
                $sql2 = " select *
                            from {$g5['menu_table']}
                            where me_use = '1'
                              and length(me_code) = '4'
                              and substring(me_code, 1, 2) = '{$row['me_code']}'
                            order by me_order, me_id ";
                $result2 = sql_query($sql2);

                for ($k=0; $row2=sql_fetch_array($result2); $k++) {
                    if($k == 0)
                        echo '<ul class="gnb_2dul">'.PHP_EOL;
                ?>
                    <li class="gnb_2dli"><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
                <?php
                }

                if($k > 0)
                    echo '</ul>'.PHP_EOL;
                ?>
            </li>
            <?php
            }

            if ($i == 0) {  ?>
                <li id="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
            <?php } ?>
        </ul>
    </nav>
</div>
<!-- } 상단 끝 -->

<hr>

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
	<aside id="aside">
        <?php echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
        <?php echo poll('theme/basic'); // 설문조사, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
	    <!-- 사이드메뉴 {-->
	    <div id="aside_ct">
	        <div class="short_cut">
	            <ul>
	                <li class="f_short_cut"><strong><a href="#">바로가기 메뉴 1</a></strong></li>
	                <li><strong><a href="#">바로가기 메뉴 2</a></strong></li>
	                <li><strong><a href="#">바로가기 메뉴 3</a></strong></li>
	            </ul>
	        </div>
	        <div class="aside_lt">
	            <?php
	            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
	            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
	            echo latest('theme/basic', 'notice', 5, 12);
	            ?>
	        </div>
	    </div>
	    <!-- }사이드메뉴-->
    </aside>

    <div id="container">
        <?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php } ?>

