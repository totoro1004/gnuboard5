<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

if (!defined("_DONT_WRAP_IN_CONTAINER_")) {
?>

<header id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div class="to_content"><a href="#container">본문 바로가기</a></div>
    <div id="mobile-indicator"></div>
    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>

    <div id="hd_wrapper">

        <div id="logo">
            <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
        </div>

        <div class="hd_sch_wr">
            <fieldset id="hd_sch">
                <legend>사이트 내 전체검색</legend>
                <form name="fsearchbox" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);">
                <input type="hidden" name="sfl" value="wr_subject||wr_content">
                <input type="hidden" name="sop" value="and">
                <label for="sch_stx" class="sound_only">검색어 필수</label>
                <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
                <button type="submit" id="sch_submit" value="검색"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
                </form>
                <?php echo popular("theme/basic"); // 인기검색어, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  ?>
            </fieldset>
        </div>

        <button id="user_btn" class="hd_opener m_side_ct">
			<i class="fa fa-ellipsis-v"></i><span class="sound_only">사용자메뉴</span>
		</button>

		<aside id="user_menu" class="hd_div">
		</aside>

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

        // 반응형일 떄 모바일 모드인지 알아 본다.
        function is_mobile_width() {
            return $('#mobile-indicator').is(':visible');
        }

        // 윈도우 리사이즈 전의 상태를 기록한 변수
        var before_status = '';

        // 반응형 메뉴를 추가한다.
        function response_menu()
        {
            var current_status = is_mobile_width() ? 'mobile' : 'pc';
            // 720px를 기준으로 모바일/pc구분
            if(before_status != current_status) {
                if(current_status == 'mobile') {
                    // 모바일 모드
                    ajax_get_side_menu('user_menu', 'head');
                    ajax_get_search_bar('m_hd_sch', 'head');
                    $(".popular_cls").attr('id', 'popular_hd');
                    $("#con_right").empty();
                    $("#hd_sch").empty();
                } else if(before_status != '' && current_status == 'pc'){
                    // PC 모드
                    ajax_get_side_menu('con_right');
                    ajax_get_search_bar('hd_sch');
                    $(".popular_cls").attr('id', 'popular');
                    $("#user_menu").empty();
                    $("#m_hd_sch").empty();
                }

                before_status = current_status;
            }
        }

        // 사이드 메뉴를 ajax로 가져온다.
        function ajax_get_side_menu(id, place)
        {
            var $el = $("#" + id);
            $.ajax({
                url: g5_theme_mobile_url + "/ajax.sidemenu.php",
                type: 'post',
                data: {
                    'place': place,
                    'theme': '<?php echo $theme ?>'
                },
                datatype: 'json',
                async: false,
                cache: false,
                success: function(data) {
                    $el.html(data);
                }
            });
        }

        // 검색 바를 ajax로 가져온다.
        function ajax_get_search_bar(id, place)
        {
            var $el = $("#" + id);
            $.ajax({
                url: g5_theme_mobile_url + "/ajax.searchbar.php",
                type: 'post',
                data: {
                    'place': place,
                    'theme': '<?php echo $theme ?>'
                },
                datatype: 'json',
                async: false,
                cache: false,
                success: function(data) {
                    $el.html(data);
                }
            });
        }

        // 브라우저 크기 resize()
        $(window).resize(function (){
            response_menu();
        });

        $(function ($) {

            response_menu();

            $(".hd_opener").on("click", function(e) {
                var $this = $(this);
                var $hd_layer = $this.next(".hd_div");

                if($hd_layer.is(":visible")) {
                    $hd_layer.hide();
                    $this.find("span").text("열기");
                } else {
                    var $hd_layer2 = $(".hd_div:visible");
                    $hd_layer2.prev(".hd_opener").find("span").text("열기");
                    $hd_layer2.hide();

                    $hd_layer.show();
                    $this.find("span").text("닫기");
                }
            });

            $("#container").on("click", function(e) {
                $(".hd_div").hide();
	        });

	        $(".btn_gnb_op").click(function(e){
	            $(this).toggleClass("btn_gnb_cl").next(".gnb_2dul").slideToggle(300);
	        });

            $(document).on("click", ".hd_closer", function() {
	            var idx = $(".hd_closer").index($(this));
	            $(".hd_div:visible").hide();
                $("#gnb_all").hide();
	            $(".hd_opener:eq("+idx+")").find("span").text("열기");
            });

            $(document).on("click", ".bg", function(e) {
                if($(e.target).attr('id') == 'm_gnb_all') {
                    $(".hd_div:visible").hide();
                }
            });

            $(document).on("click", ".hd_visit button", function() {
                $('.hd_visit button').toggle(function(){
                    $(this).addClass('visit_close');
                    $(this).next('div.oa_open').removeClass('oa_close');
                },function(){
                    $(this).removeClass('visit_close');
                    $(this).next('div.oa_open').addClass('oa_close');
                });
            });
	    });
        </script>

		<div id="tnb">
        	<?php if ($is_admin == 'super' || $is_auth) {  ?><a href="<?php echo G5_ADMIN_URL ?>" class="adm_btn">관리자</a><?php }  ?>
        	<?php if ($is_member) {  ?>
            <a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a>
            <?php } else {  ?>
            <?php /* ?><a href="<?php echo G5_BBS_URL ?>/login.php">로그인</a><?php */ ?>
            <?php }  ?>

			<div id="m_sch">
	        	<button class="sch_more">
		        	<i class="fa fa-search"></i>
		        </button>
		        <fieldset id="m_hd_sch">
                </fieldset>
            </div>
	        <script>
				$(document).ready(function(){
                    $(document).on("click", ".sch_more", function() {
                        // 검색바 토글
				        $("#m_hd_sch").toggle();
				    });
                    $(document).on("click", ".sch_more_close", function() {
				        $("#m_hd_sch").hide();
				    });
				});
			</script>
	    </div>
	</div>

    <div class="gnb_wrap">
    	<!-- 전체 gnb -->
	    <nav id="gnb" class="gnb_pc">
        <h2>메인메뉴</h2>
        <button class="gnb_menu_btn"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button>

        <div id="gnb_bar">
        	<div id="swipe_gnb_menu" class="gnb_inner swiper-container">
	            <ul class="gnb_1dul swiper-wrapper">
	                <?php
	                $sql = " select *
	                            from {$g5['menu_table']}
	                            where me_use = '1'
	                              and length(me_code) = '2'
	                            order by me_order, me_id ";
	                $result = sql_query($sql, false);
	                $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
	                $menu_datas = array();

	                for ($i=0; $row=sql_fetch_array($result); $i++) {
	                    $menu_datas[$i] = $row;

	                    $sql2 = " select *
	                                from {$g5['menu_table']}
	                                where me_use = '1'
	                                  and length(me_code) = '4'
	                                  and substring(me_code, 1, 2) = '{$row['me_code']}'
	                                order by me_order, me_id ";
	                    $result2 = sql_query($sql2);
	                    for ($k=0; $row2=sql_fetch_array($result2); $k++) {
	                        $menu_datas[$i]['sub'][$k] = $row2;
	                    }

	                }

	                $i = 0;
	                foreach( $menu_datas as $row ){
	                    if( empty($row) ) continue;
	                ?>
	                <li class="gnb_1dli swiper-slide" style="z-index:<?php echo $gnb_zindex--; ?>">
	                    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_1da"><?php echo $row['me_name'] ?></a>
	                </li>
	                <?php
	                $i++;
	                }   //end foreach $row

	                if ($i == 0) {  ?>
	                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
	                <?php } ?>
	            </ul>
            </div>
		</div>

        <div id="pc_gnb_all">
        	<div id="gnb_all">
            <h2>전체메뉴</h2>
            <ul class="gnb_al_ul">
                <?php

                $i = 0;
                foreach( $menu_datas as $row ){
                ?>
                <li class="gnb_al_li">
                    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_al_a"><?php echo $row['me_name'] ?></a>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){
                        if($k == 0)
                            echo '<ul>'.PHP_EOL;
                    ?>
                        <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $row2['me_name'] ?></a></li>
                    <?php
                    $k++;
                    }   //end foreach $row2

                    if($k > 0)
                        echo '</ul>'.PHP_EOL;
                    ?>
                </li>
                <?php
                $i++;
                }   //end foreach $row

                if ($i == 0) {  ?>
                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
            </ul>
            <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
    </div>

        <div class="gnb_mobile">
			<?php include_once(G5_THEME_MOBILE_PATH.'/category.php'); ?>
		</div>
    </nav>
</div>
    <script>
	$(function(){
	    $(".gnb_menu_btn").click(function(){
	        $("#gnb_all").show();
	    });
	    $(".gnb_close_btn").click(function(){
	        $("#gnb_all").hide();
	        $("#m_gnb_all").hide();
	    });

        // 현재 선택한 메뉴 탭에 강조표시
        $('.swiper-slide').each(function() {
            var menu_a_tag = $(this).find('a'),
                menu_href = menu_a_tag.attr('href');

            if(menu_href == window.location.href) {
                menu_a_tag.addClass('gnb_sl');
                return false;
            }
        });
	});
	</script>
</header>
<!-- } 상단 끝 -->

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
    <div id="container">
    	<div id="container_inner">

			<div id="con_left">
			<?php } // if (!defined("_DONT_WRAP_IN_CONTAINER_")) 의 끝 ?>
			<?php if (!defined("_INDEX_") && !defined("_DONT_WRAP_IN_CONTAINER_")) {?>
                <h2 id="container_title" class="top"><?php echo get_head_title($g5['title']) ?></h2>
            <?php } ?>
