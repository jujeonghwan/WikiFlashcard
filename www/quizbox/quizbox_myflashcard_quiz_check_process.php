<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$mf_id      =   trim($_POST["mf_id"]);
$quiz_check =   trim($_POST["quiz_check"]);

// 시험확인처리 (Check Quiz)
$myflashcard_quiz_check = set_myflashcard_quiz_check($mf_id, $quiz_check);

if ($myflashcard_quiz_check = true) {    
    // alert("Checked.");
}
else {
    alert_back("Failed to Check."); 
}

// 페이지 이동
$location_href = "quizbox_myflashcard_quiz.php?dummy=dummy";
$location_href .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
$location_href .= "&search_mf_boxstep=" . $_REQUEST["search_mf_boxstep"];
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>