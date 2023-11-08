<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<aside id="ol_before" class="ol">
    <h2>회원로그인</h2>
    <!-- 로그인 전 외부로그인 시작 -->
    <form name="foutlogin" action="<?php echo $outlogin_action_url ?>" onsubmit="return fhead_submit(this);" method="post" autocomplete="off">
    <fieldset>
        <input type="hidden" name="url" value="<?php echo $outlogin_url ?>">
        <input type="text" name="mb_id" id="ol_id" placeholder="아이디" required maxlength="20">
        <input type="password" id="ol_pw" name="mb_password" placeholder="비밀번호" required  maxlength="20">
        
        <button type="submit" id="ol_submit" value="로그인" class="btn_submit">로그인</button>

        <div class="ol_auto_wr">
	        <div id="ol_auto">
	        	<label for="auto_login" id="auto_login_label"><span class="agree_ck"></span>자동로그인</label>
	            <input type="checkbox" name="auto_login" value="1" id="auto_login">
	        </div>

	        <div class="ol_before_btn">
	            <a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a>
	            <a href="<?php echo G5_BBS_URL ?>/password_lost.php" id="ol_password_lost">정보찾기</a>
	        </div>
        </div>
    </fieldset>
    
    <?php @include_once(get_social_skin_path().'/social_outlogin.skin.1.php'); // 소셜로그인 사용시 소셜로그인 버튼 ?>

    </form>
</aside>

<script>
<?php if (!G5_IS_MOBILE) { ?>
$omi = $('#ol_id');
$omp = $('#ol_pw');
$omp.css('display','inline-block').css('width',104);
$omi_label = $('#ol_idlabel');
$omi_label.addClass('ol_idlabel');
$omp_label = $('#ol_pwlabel');
$omp_label.addClass('ol_pwlabel');
$omi.focus(function() {
    $omi_label.css('visibility','hidden');
});
$omp.focus(function() {
    $omp_label.css('visibility','hidden');
});
$omi.blur(function() {
    $this = $(this);
    if($this.attr('id') == "ol_id" && $this.attr('value') == "") $omi_label.css('visibility','visible');
});
$omp.blur(function() {
    $this = $(this);
    if($this.attr('id') == "ol_pw" && $this.attr('value') == "") $omp_label.css('visibility','visible');
});
<?php } ?>

$("#auto_login").click(function(){
    if (this.checked) {
        this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
    }
});

function fhead_submit(f)
{
    return true;
}

// 인풋 체크박스
$(document).ready(function(){
    $("#auto_login").on('click', function(){
        if ($(this).is(":checked")) {
            if(!confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?")) {
                $(".agree_ck").removeClass("click_on");
                return false;
            }
        }
    });

    $("#auto_login_label").click(function(){
        $(".agree_ck").toggleClass("click_on");
    });
});
</script>
<!-- 로그인 전 외부로그인 끝 -->
