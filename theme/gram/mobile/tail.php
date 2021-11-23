<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
	    </div>
	</div>
	
	<div id="ft">
		<ul class="gram_fix_btn">
			<li><a href="<?php echo G5_URL ?>" class="gf_btn gf_home"><span class="sound_only">메인페이지로</span></a></li>
			<li class="ft_sch">
				<button type="button" id="user_btn" class="hd_opener gf_btn gf_sch"><span class="sound_only">검색</span></button>
		        <div id="user_menu" class="hd_div">
		            <div id="ft_sch">
		                <h2>사이트 내 전체검색</h2>
		                <form name="fsearchbox" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);" method="get">
		                <input type="hidden" name="sfl" value="wr_subject||wr_content">
		                <input type="hidden" name="sop" value="and">
		                <input type="text" name="stx" id="sch_stx" placeholder="검색어를 입력해주세요" required maxlength="20">
		                <button type="submit" value="검색" id="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
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

				        $(function () {
				            $(".hd_opener").on("click", function() {
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
				
				            $("#container").on("click", function() {
				                $(".hd_div").hide();
				
				            });
				
				            $(".btn_gnb_op").click(function(){
				                $(this).toggleClass("btn_gnb_cl").next(".gnb_2dul").slideToggle(300);
				                
				            });
				
				            $(".hd_closer").on("click", function() {
				                var idx = $(".hd_closer").index($(this));
				                $(".hd_div:visible").hide();
				                $(".hd_opener:eq("+idx+")").find("span").text("열기");
				            });
				        });

		                </script>
		            </div>
		        </div>
			</li>
			<li><a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=gram" class="gf_btn gf_wrt"><span class="sound_only">글쓰기</span></a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/new.php" class="gf_btn gf_new"><span class="sound_only">새글</span></a></li>
			<?php if ($is_member) {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/logout.php" class="gf_btn gf_logout"><span class="sound_only">로그아웃</span></a></li> 
            <?php } else {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/login.php" class="gf_btn gf_log"><span class="sound_only">로그인</span></a></li>
            <?php }  ?>
		</ul>
	</div>
</div>
<script>
jQuery(function($) {

    $( document ).ready( function() {

        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        
        //상단고정
        if( $(".top").length ){
            var jbOffset = $(".top").offset();
            $( window ).scroll( function() {
                if ( $( document ).scrollTop() > jbOffset.top ) {
                    $( '.top' ).addClass( 'fixed' );
                }
                else {
                    $( '.top' ).removeClass( 'fixed' );
                }
            });
        }
    });
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>