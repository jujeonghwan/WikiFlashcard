<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$cs_id = trim($_POST["cs_id"]);

// 플래시카드 (Flashcard) 존재여부 확인
$query = "select count(*) as total_count ";
$query .= "from flashcard_tb ";
$query .= "where fc_cardset = '" . $cs_id . "' ";

$result_count = db_query($query);
$row_count = db_fetch_array($result_count);

if ($row_count["total_count"] > 0) {
    alert_back("Failed to Delete.\\nThere is a Flashcard registered in the Cardset."); 
    exit;
}


// 삭제
$query = "delete from cardset_tb ";
$query .= "where cs_id = '" . $cs_id . "' ";
$query .= "limit 1 ";

if ($result = db_query($query)) {    
    alert("Deleted.");
}
else {
    alert_back("Failed to Delete."); 
}

// 페이지 이동
// $location_href = "cardset_list.php?dummy=dummy";
$location_href = "/index.php?dummy=dummy";
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>