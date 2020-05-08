<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$fc_id = trim($_POST["fc_id"]);

// 삭제
$query = "delete from flashcard_tb ";
$query .= "where fc_id = '" . $fc_id . "' ";
$query .= "limit 1 ";

if ($result = db_query($query)) {    
    alert("Deleted.");
}
else {
    alert_back("Failed to Delete."); 
}

// 페이지 이동
$location_href = "flashcard_list.php?dummy=dummy";
$location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>