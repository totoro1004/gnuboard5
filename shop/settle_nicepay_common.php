<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/etc.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$_POST = array_map('iconv_utf8', $_POST);

error_log($_SERVER['REMOTE_ADDR']);
error_log(print_r($_POST, true));

// INBOUND IP가 나이스페이 서버 IP가 아니라면(나이스페이에서 온 데이터가 아니라면) 오류
if ($_SERVER['REMOTE_ADDR'] === '121.133.126.10' || 
    $_SERVER['REMOTE_ADDR'] === '121.133.126.11' ||
    $_SERVER['REMOTE_ADDR'] === '211.33.136.39') {
        ;
} else {
    die('ERROR.');
}

if ($_POST['PayMethod'] !== 'VBANK') {
    die('ERROR..');
}

$nicepayHome = G5_SHOP_PATH.'/nicepay'; // 나이스페이 홈디렉터리
$nicepayLog  = true;                    // 로그를 기록하려면 true 로 수정


// error_log(print_r($_POST, true));

$tid  = $_POST['TID'];  // 거래 ID
$mid  = $_POST['MID'];  // 상점 아이디
$moid = $_POST['MOID']; // 상점 주문번호
$amt  = $_POST['Amt'];  // 결제 금액
$receipt_time = '20'.$_POST['AuthDate']; // 211213154055 (년월일시분초 2자리씩 넘어오므로 년도 앞에 20을 붙임)


// 개인결제 인지 확인?
$sql = " select pp_id, od_id from {$g5['g5_shop_personalpay_table']} where pp_id = '$moid' and pp_app_no = '$tid' ";
$row = sql_fetch($sql);

$result = false;

if (isset($row['pp_id']) && $row['pp_id']) {
    // 개인결제 UPDATE
    $sql = " update {$g5['g5_shop_personalpay_table']} 
                set pp_receipt_price    = '$amt',
                    pp_receipt_time     = '$receipt_time'
                where pp_id = '$moid'
                  and pp_app_no = '$tid' ";
    $result = sql_query($sql, false);

    if($row['od_id']) {
        // 주문서 UPDATE
        $receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $receipt_time);
        $sql = " update {$g5['g5_shop_order_table']}
                    set od_receipt_price = od_receipt_price + '$amt',
                        od_receipt_time = '$receipt_time',
                        od_shop_memo = concat(od_shop_memo, \"\\n개인결제 ".$row['pp_id']." 로 결제완료 - ".$receipt_time."\")
                  where od_id = '{$row['od_id']}' ";
        $result = sql_query($sql, false);
    }
} else {
    // 주문서 UPDATE
    $sql = " update {$g5['g5_shop_order_table']}
                set od_receipt_price = '$amt',
                    od_receipt_time = '$receipt_time'
              where od_id = '$moid'
                and od_tno = '$tid' ";
    $result = sql_query($sql, false);
}

if ($result) {

    if (isset($row['od_id']) && $row['od_id'])
        $od_id = $row['od_id'];
    else 
        $od_id = $moid;

    // 주문정보 체크
    $sql = " select count(od_id) as cnt
                from {$g5['g5_shop_order_table']}
                where od_id = '$od_id'
                  and od_status = '주문' ";
    $row = sql_fetch($sql);

    if ($row['cnt'] == 1) {
        // 미수금 정보 업데이트
        $info = get_order_info($od_id);

        $sql = " update {$g5['g5_shop_order_table']}
                    set od_misu = '{$info['od_misu']}' ";
        if ($info['od_misu'] == 0)
            $sql .= " , od_status = '입금' ";
        $sql .= " where od_id = '$od_id' ";
        sql_query($sql, false);

        // 장바구니 상태변경
        if ($info['od_misu'] == 0) {
            $sql = " update {$g5['g5_shop_cart_table']}
                        set ct_status = '입금'
                        where od_id = '$od_id' ";
            sql_query($sql, false);
        }
    }
}

if ($nicepayLog) {
    // $_POST 배열에서 제외할 값
    $exclude = array('MerchantKey', 'VbankInputName');
    foreach($_POST as $key=>$value) {
        if( !in_array($key, $exclude) ){
            $_POST[$key] = $params[$key] = clean_xss_tags(strip_tags($value));
        }
    }

    $logfile = fopen($nicepayHome . "/log/result.log", "a+");
    fwrite($logfile, "************************************************\n");
    fwrite($logfile, G5_TIME_YMDHIS."\n");
    fwrite($logfile, print_r($_POST, true));
    fclose($logfile );
}

//************************************************************************************

//위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 "OK"를 나이스페이로
//리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
//(주의) OK를 리턴하지 않으시면 나이스페이 지불 서버는 "OK"를 수신할때까지 10회 이하로 재전송을 시도합니다
//기타 다른 형태의 PRINT( echo )는 하지 않으시기 바랍니다

if ($result) {
    die("OK");                        // 절대로 지우지마세요
} else {
    die("DB Error");
}

//*************************************************************************************
