<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>


	<div id="index_content">

	<!-- 메인화면 최신글 시작 -->
	<?php
	//  최신글
	$sql = " select bo_table
	            from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
	            where a.bo_device <> 'pc' ";
	if(!$is_admin)
	    $sql .= " and a.bo_use_cert = '' ";
	$sql .= " order by b.gr_order, a.bo_order ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {
	    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
	    // 스킨은 입력하지 않을 경우 관리자 > 환경설정의 최신글 스킨경로를 기본 스킨으로 합니다.

	    // 사용방법
	    // latest(스킨, 게시판아이디, 출력라인, 글자수);
	    echo latest('theme/basic', $row['bo_table'], 10, 25);
	}
	?>

	<!--  사진 최신글 { -->
    <?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    //echo latest('theme/pic_basic', 'gallery', 4, 23);
    ?>
    <!-- } 사진 최신글 끝 -->

	<!-- 메인화면 최신글 끝 -->
	</div>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>
