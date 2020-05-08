<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$success_count = 0;
$failure_count = 0;

foreach ($_POST["check_mf_id"] as $key => $val) {
    $mf_id = trim($val);   

    // 내플래시카드 삭제
    $myflashcard_delete = set_myflashcard_delete($mf_id);

    if ($myflashcard_delete == true) {
        $success_count++;
    }
    else {
        $failure_count++;
    }
}

if ($success_count > 0) {
    alert(number_format($success_count) . " Items Deleted.");    
}
else {
    alert_back("Failed to Delete."); 
}

// 페이지 이동
$location_href = "myflashcard_list.php?dummy=dummy";
$location_href .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>