<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
		</div>
		<?php if (!defined("_DONT_WRAP_IN_CONTAINER_")) { ?>
	    <aside id="con_right">
	        <?php //echo outlogin('theme/basic'); // 로그인 ?>

	        <ul class="hd_nb">
			    <li><a href='http://www.filemaru.com/?p_id=torrentmaru' target='_blank'><img src='http://partner.filemaru.com/files/img/banner/200x200.gif' border='0'></a></li>
			</ul>

			<?php //echo poll('theme/basic'); // 설문조사 ?>
	    </aside>
		</div><!-- } container_inner 끝 -->
	</div><!-- } container 끝 -->
</div><!-- } wrapper 끝 -->
<?php } ?>


<div id="ft">
    <div id="ft_copy">
        Copyright &copy; <b>torrentmaru.com.</b> All rights reserved.<br>
        <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
    </div>

    <script>
    $(function() {
    	// 폰트 리사이즈 쿠키있으면 실행
        font_resize("html_wrap", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));

        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });
    });
    </script>
</div>


<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>
