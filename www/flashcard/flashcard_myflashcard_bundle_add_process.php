<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$success_count = 0;
$failure_count = 0;

foreach ($_POST["check_fc_id"] as $key => $val) {
    $fc_id = trim($val);   

    // 플래시카드 내플래시카드 등록
    $flashcard_myflashcard_add = set_flashcard_myflashcard_add($fc_id);

    if ($flashcard_myflashcard_add == true) {
        $success_count++;
    }
    else {
        $failure_count++;
    }
}

if ($success_count > 0) {
    alert(number_format($success_count) . "항목 등록되었습니다. (" . number_format($success_count) . " Items Registered.)");    
}
else {
    // alert_back("등록하는데 실패했습니다. (Failed to register.)"); 
    alert(number_format($failure_count) . "항목 이미 등록되어 있습니다. (" . number_format($failure_count) . " Items Duplicated.)");    
}

// 페이지 이동
$location_href = "flashcard_list.php?dummy=dummy";
$location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>