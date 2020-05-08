<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

// 카드세트번호 (Cardset ID)
$cs_id = trim($_REQUEST["cs_id"]);

$query = "select ";
$query .= "fc_id ";
$query .= "from flashcard_tb ";         // 플래시카드 (Flashcard)
$query .= "where fc_cardset = '" . $cs_id . "' ";
$query .= "order by fc_order, fc_id ";

$result = db_query($query);

$success_count = 0;
$failure_count = 0;

while ($row = db_fetch_array($result)) {
    $fc_id = $row["fc_id"]; 

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
    alert("내 플래시카드에 " . number_format($success_count) . "항목이 등록되었습니다. (" . number_format($success_count) . " Items Registered.)");    
}
else {
    // alert_back("등록하는데 실패했습니다. (Failed to register.)"); 
    alert("내 플래시카드에 " . number_format($failure_count) . "항목이 이미 등록되어 있습니다. (" . number_format($failure_count) . " Items Duplicated.)");    
}

// 페이지 이동
$location_href = "/?dummy=dummy";
$location_href .= "&search_type=" . $_REQUEST["search_type"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>