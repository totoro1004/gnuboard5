<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/jquery.fancylist.js"></script>

<!-- 게시판 목록 시작 -->
<div id="bo_gall">

    <?php if ($is_category) { // 분류 링크 표시 ?>
    <nav id="bo_cate">
        <h2><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']) ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>
	
    <div id="bo_top_opt">
    	<div class="bo_list_total">
        	<span>전체 <?php echo number_format($total_count) ?>건</span>
        	<?php echo $page ?> 페이지
        </div>
        
        <?php if ($rss_href || $write_href) { // 게시판 상단 버튼 ?>
		<ul class="<?php echo isset($view) ? 'view_is_list btn_top' : 'btn_top top';?>">
		    <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
		    <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin">관리자</a></li><?php } ?>
		    <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a></li><?php } ?>
		</ul>
		<?php } ?>
    </div>

    <form name="fboardlist"  id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <h2>이미지 목록</h2>

    <?php if ($is_checkbox) { ?>
    <div id="gall_allchk" class="list_chk all_chk">
        <label for="chkall"><span class="sound_only">현재 페이지 게시물</span>전체선택</label>
        <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
    </div>
    <?php } ?>

    <ul id="gall_ul">
        <?php for ($i=0; $i<count($list); $i++) { // 게시글 목록 시작 ?>
        <li class="gall_li <?php if ($wr_id == $list[$i]['wr_id']) { ?>gall_now<?php } ?>">
            <div class="gall_li_wr">
                <?php if ($is_checkbox) { // 게시글별 체크박스 ?>
                <span class="gall_li_chk bo_chk li_chk">
                    <label for="chk_wr_id_<?php echo $i ?>"><span class="sound_only"><?php echo $list[$i]['subject'] ?></span></label>
                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                </span>
                <?php } ?>
                <span class="sound_only">
                    <?php  // 열람중, 글번호
                    if ($wr_id == $list[$i]['wr_id'])
                        echo "<span class=\"bo_current\">열람중</span>";
                    else
                        echo $list[$i]['num'];
                    ?>
                </span>
                
                <div class="gall_img_area">
                	<a href="<?php echo $list[$i]['href'] ?>" class="gall_img">
	                <?php
	                if ($list[$i]['is_notice']) { // 공지사항 ?>
	                    <strong style="width:<?php echo $board['bo_mobile_gallery_width'] ?>px;height:<?php echo $board['bo_mobile_gallery_height'] ?>px">공지</strong>
	                <?php
	                } else {
	                    $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_mobile_gallery_width'], $board['bo_mobile_gallery_height']);
	
	                    if($thumb['src']) {
	                        $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" width="'.$board['bo_mobile_gallery_width'].'" height="'.$board['bo_mobile_gallery_height'].'">';
	                    } else {
	                        $img_content = '<span class="no-img">no image</span>';
	                    }
	
	                    echo $img_content;
	                }
	                ?>
	                </a>
	                <div class="gall_recomm">
                        <?php if ($is_good) { ?><span class="reco reco_good"><span class="sound_only">추천</span><strong><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $list[$i]['wr_good'] ?></strong></span><?php } ?>
                        <?php if ($is_nogood) { ?><span class="reco reco_nogood"><span class="sound_only">비추천</span><strong><i class="fa fa-thumbs-o-down" aria-hidden="true"></i> <?php echo $list[$i]['wr_nogood'] ?></strong></span><?php } ?>
                    </div>   	
                </div>
                
                <div class="gall_text_href">
                    <?php if ($is_category && $list[$i]['ca_name']) { ?>
                    <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                    <?php } ?>
                    <a href="<?php echo $list[$i]['href'] ?>" class="gall_li_tit">
                        <?php echo $list[$i]['subject'] ?>
                        <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sound_only">개</span><?php } ?>
                    </a>
                    <div class="gall_writer">
                    	<span class="sound_only">작성자 </span><?php echo $list[$i]['name'] ?>
	                    <?php
	                    // if ($list[$i]['link']['count']) { echo '['.$list[$i]['link']['count']}.']'; }
	                    // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
	                    if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
	                    //if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
	                    //if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
	                    //if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];
	                    ?>
	                    <?php if ($list[$i]['icon_new']) { ?>
			            <span class="new_icon">N<span class="sound_only">새글</span></span>
				        <?php } ?>
	                    <div class="gall_info">
	                    	<span class="sound_only">작성일 </span><span class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $list[$i]['datetime2'] ?></span>
							<span class="sound_only">조회 </span><strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $list[$i]['wr_hit'] ?></strong>  
						</div>
                    </div>
                </div>
            </div>
        </li>
        <?php } ?>
        <?php if (count($list) == 0) { echo "<li class=\"empty_list\">게시물이 없습니다.</li>"; } ?>
    </ul>

    <?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="btn_top">
        <ul class="btn_bo_adm">
            <?php if ($list_href) { ?>
            <li><a href="<?php echo $list_href ?>" class="btn_b01 btn"> 목록</a></li>
            <?php } ?>
            <?php if ($is_checkbox) { ?>
            <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_b01">선택삭제</button></li>
            <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn_b01">선택복사</button></li>
            <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn_b01">선택이동</button></li>
            <?php } ?>
            <?php if ($write_href) { ?>
            <li class="btn_align_right"><a href="<?php echo $write_href ?>" class="btn_b02 btn">글쓰기</a><?php } ?>
        </ul>
    </div>
    <?php } ?>
    </form>
    
    <button class="sch_tog"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색창</span></button> <!-- 검색창 팝업 버튼 -->

    <!-- 게시판 검색 시작 { -->
	<fieldset id="bo_sch">
	    <legend>게시물 검색</legend>
	    <form name="fsearch" method="get">
	    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	    <input type="hidden" name="sca" value="<?php echo $sca ?>">
	    <input type="hidden" name="sop" value="and">
	    <label for="sfl" class="sound_only">검색대상</label>
	    <select name="sfl" id="sfl">
            <option value="wr_subject"<?php echo $list['sfl_wr_subject'] ?>>제목</option>
            <option value="wr_content"<?php echo $list['sfl_wr_content'] ?>>내용</option>
            <option value="wr_subject||wr_content"<?php echo $list['sfl_wr_subject||wr_content'] ?>>제목+내용</option>
            <option value="mb_id,1"<?php echo $list['sfl_mb_id,1'] ?>>회원아이디</option>
            <option value="mb_id,0"<?php echo $list['sfl_mb_id,0'] ?>>회원아이디(코)</option>
            <option value="wr_name,1"<?php echo $list['sfl_wr_name,1'] ?>>글쓴이</option>
            <option value="wr_name,0"<?php echo $list['sfl_wr_name,0'] ?>>글쓴이(코)</option>
	    </select>
	    <input name="stx" value="<?php echo $list['input_search'] ?>" placeholder="검색어" required id="stx" class="sch_input" size="15" maxlength="20">
	    <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i> <span class="sound_only">검색</span></button>
	    </form>
	</fieldset>
	<script>
		$(document).ready(function(){
			$(".sch_tog").click(function(){
				$("#bo_sch").toggle();
			});
		});
	</script>
	<!-- } 게시판 검색 끝 -->
</div>


<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $write_pages; ?>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- 게시판 목록 끝 -->
