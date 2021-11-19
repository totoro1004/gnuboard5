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

add_javascript('<script src="'.G5_THEME_JS_URL.'/unslider.min.js"></script>', 10);
?>
<link rel="stylesheet" href="//cdn.rawgit.com/hiun/NanumSquare/master/nanumsquare.css">
<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
    
    <?php if ($is_admin) {  ?>
    <div id="hd_admin">
		<span class="hello_adm"><b>관리자</b>로 접속하셨습니다.</span>
    	<a href="<?php echo G5_ADMIN_URL ?>" class="admin_btn">관리자모드</a>
    </div>
    <?php }  ?>

    <div id="hd_wrapper">
		
		<div id="hd_wr">
	        <div id="logo">
	            <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
	        </div>
	        
	        <div id="hd_qnb">
				<button class="sch_btn"><i class="fa fa-search"></i><span class="sound_only">검색</span></button>
    
		        <div class="hd_cnt_sch">
		        	<div class="hd_cnt_sch_innr">
			            <fieldset id="hd_sch">
			                <legend>사이트 내 전체검색</legend>
			                <form name="fsearchbox" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);">
			                <input type="hidden" name="sfl" value="wr_subject||wr_content">
			                <input type="hidden" name="sop" value="and">
			                
			                <label for="sch_stx" class="sound_only">검색어 필수</label>
			                <div class="sch_ipt">
				                <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
			                	<button type="submit" id="sch_submit" value="검색"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
		                	</div>
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
			            <?php echo popular('theme/basic'); // 인기검색어, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  ?>
		        	</div>
		        	<button class="sch_close_btn"><span class="sound_only">닫기</span><i class="fa fa-times"></i></button>
		        	<div class="bg"></div>
		        </div>
	        	<div class="hd_cnt_login">
	        		<?php if ($is_member) {  ?>
					<button class="login_btn">나의메뉴</button>
			        <?php } else {  ?>
					<button class="login_btn">로그인</button>
			        <?php }  ?>
	        		<div id="member_menu">
						<div class="member_div">
							<?php echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>  
						</div>
					</div>
					<script>
				    $(function(){
				        $(".login_btn").click(function(){
				            $("#member_menu").toggle();
				        });
				        $(".login_cls_btn").click(function(){
				            $("#member_menu").hide();
				        });
				    });
					</script>
	        	</div>
	        </div>  
		</div>
		<script>
	    $(function(){
	        $(".sch_btn").click(function(){
	            $(".hd_cnt_sch").show();
	        });
	        $(".sch_close_btn").click(function(){
	            $(".hd_cnt_sch").hide();
	        });
	        $('.hd_cnt_sch .bg').click(function(){
			    $('.hd_cnt_sch').hide();
			});
	    });
		</script>
    </div>
    
    <nav id="gnb">
        <h2>메인메뉴</h2>
        <div class="gnb_wrap">
        	<button class="gnb_menu_btn"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button>
            <ul id="gnb_1dul">
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
                }   //end foreach $row2
	
                if ($i == 0) {  ?>
                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
            </ul>
        </div>
        
        <div id="gnb_all">
            <h2>전체메뉴</h2>
	        <div class="innr">
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
	                    <li class="gnb_empty"><?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
	                <?php } ?>
	            </ul>
	            <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
	        </div>
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
	
		});
		</script>
    </nav>
</div>
<!-- } 상단 끝 -->

<!-- 콘텐츠 시작 { -->
<div id="wrapper">

	<?php if(defined('_INDEX_')) { ?>
	<!--메인배너 {-->
    <div id="main_bn">
        <ul class="bn_ul">
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
            <li class="bn_bg1">
                <div class="bn_wr"><a href="#none"><img src="<?php echo G5_THEME_IMG_URL ?>/main_banner.png" alt="메인베너" /></a></div>
            </li>
        </ul>
    </div>
	<!--} 메인배너-->
	<script>
	$('#main_bn .bn_ul').bxSlider({
	    auto: true,
	    autoControls: true
	});
	</script>
	<?php } ?>

    <?php if (!defined("_INDEX_")) { ?>
    	<h2 id="cnt_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2>
    <?php } ?>
    	
    <div id="container" class="container">

