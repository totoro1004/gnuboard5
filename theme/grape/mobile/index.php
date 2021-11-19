<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<!-- 배너 최신글 -->
<?php
// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
echo latest('theme/banner', 'banner', 2, 33);
?>

<div class="idx_rs">
   <h2><i class="fa fa-calendar"></i> 진료예약</h2>
   <p class="tel">01-234-5678</p>
   <div class="online-btn">
       <p>홈페이지에서 빠르고 쉽게 진료예약을 하실 수 있습니다.</p>
       <a href="#">온라인예약</a>
   </div>
</div>

<div class="idx_con">
    <ul>
        <li>
            <h2><i class="fa fa-hospital-o"></i><span>Short title </span></h2>
            <p>간략한 설명을 입력해 주세요. <a href="https://fontawesome.com/v4.7.0/icons/">fontawesome</a>에서 원하시는 아이콘으로 변경해주세요 </p>
            <a href="">자세히보기</a>
        </li>
        <li>
            <h2><i class="fa fa-user-md"></i><span>Short title </span></h2>
            <p>간략한 설명을 입력해 주세요.<a href="https://fontawesome.com/v4.7.0/icons/">fontawesome</a>에서 원하시는 아이콘으로 변경해주세요 </p>
            <a href="">자세히보기</a>
        </li>   
         <li>
            <h2><i class="fa fa-hotel"></i><span>Short title </span></h2>
            <p>간략한 설명을 입력해 주세요.<a href="https://fontawesome.com/v4.7.0/icons/">fontawesome</a>에서 원하시는 아이콘으로 변경해주세요 </p>
            <a href="">자세히보기</a>
        </li>   
    </ul>
</div>


<!-- 진료과목  -->   
<?php
// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
echo latest('theme/service', 'service', 4, 33);
?>


<!-- 의료진  -->   
<?php
// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
echo latest('theme/team', 'team', 6, 10);
?>

<!-- 후기  -->   
<?php
// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
echo latest('theme/review', 'review', 4, 33);
?>

<!--  최신글 -->
<div class="idx_lt">
    <div class="bg"><span></span><span></span><span></span></div>
    <div class="lt_wr">
        <h2>진료예약</h2>
        <strong class="tel">01-234-5678</strong>
        <p>홈페이지에서 빠르고 쉽게 진료예약을 하실 수 있습니다.</p>
        <a href="" class="btn_m btn_b02">온라인예약</a>
    </div>

    <div class="lt_wr time">
        <h2>진료시간안내</h2>
        <ul>
            <li><strong><i class="fa fa-clock-o"></i> 진료시간</strong> 09:00 ~ 18:00</li>
            <li><strong><i class="fa fa-clock-o"></i> 점심시간</strong> 09:00 ~ 18:00</li>
            <li><strong><i class="fa fa-clock-o"></i> 토요일</strong> 09:00 ~ 18:00</li>
        </ul>
    </div>

    <?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    echo latest('theme/basic', 'notice', 4, 33);
    ?>

</div>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>