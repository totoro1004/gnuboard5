<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>

<div id="bo_list">
    <?php if ($category_option) { ?>
    <!-- 카테고리 시작 { -->
    <nav id="bo_cate">
        <h2><?php echo $qaconfig['qa_title'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <!-- } 카테고리 끝 -->
    <?php } ?>
    
	<div class="bo_option">
	    <?php if ($admin_href || $write_href) { ?>
	    <ul class="btn_bo_user">
	        <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn">관리자</a></li><?php } ?>
	        <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn">문의등록</a></li><?php } ?>
	    </ul>
	    <?php } ?>
	</div>
	
    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <div id="bo_list_total" class="sound_only">
            <span>Total <?php echo number_format($total_count) ?>건</span>
            <?php echo $page ?> 페이지
        </div>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">

    <?php if ($is_checkbox) { ?>
    <div class="list_chk all_chk">
        <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
        <label for="chkall"><span class="sound_only">게시물</span>전체선택</label>
    </div>
    <?php } ?>

    <div class="list_03">
        <ul>
            <?php
            for ($i=0; $i<count($list); $i++) {
            ?>
            <li class="bo_li<?php if ($is_checkbox) echo ' bo_adm'; ?>">
                <div class="li_title">                
	                <?php if ($is_checkbox) { ?>
	                <span class="bo_chk li_chk">
	                    <label for="chk_qa_id_<?php echo $i ?>"><span class="sound_only"><?php echo $list[$i]['subject']; ?></span></label>
	                    <input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>">
	                </span>
	                <?php } ?>
	                <div class="li_stat <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? '답변완료' : '답변대기'); ?></div>
					<strong><?php echo $list[$i]['category']; ?></strong>
					<a href="<?php echo $list[$i]['view_href']; ?>" class="li_sbj">
                        <?php echo $list[$i]['subject']; ?><span> <?php echo $list[$i]['icon_file']; ?></span>
                    </a>
                </div> 
                <div class="li_info">
                    <span><?php echo $list[$i]['name']; ?></span>
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $list[$i]['date']; ?></span>
                </div>
            </li>
            <?php
            }
            ?>

            <?php if ($i == 0) { echo '<li class="empty_list">게시물이 없습니다.</li>'; } ?>
        </ul>
    </div>

    <div class="btn_top">
        <?php if ($is_checkbox) { ?>
        <ul class="btn_bo_adm">
            <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_b01">선택삭제</button></li>
        	<li><?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn">문의등록</a></li><?php } ?></li>
        </ul>
        <?php } ?>
    </div>
    </form>
    
    <button class="sch_tog"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색창</span></button>
    
    <!-- qa 검색 시작 { -->
	<fieldset id="bo_sch">
	    <legend>게시물 검색</legend>
	    <form name="fsearch" method="get">
    	    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    	    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    	    <input type="text" name="stx" value="<?php echo $list['input_search'] ?>" placeholder="검색어" required id="stx" class="sch_input" size="15" maxlength="15">
    	    <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
	    </form>
	</fieldset>
	<script>
		$(document).ready(function(){
			$(".sch_tog").click(function(){
				$("#bo_sch").toggle();
			});
		});
	</script>
	<!-- } qa 검색 끝 -->
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $list_pages;  ?>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->