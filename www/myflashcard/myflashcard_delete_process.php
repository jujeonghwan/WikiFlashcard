<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$mf_id = trim($_REQUEST["mf_id"]);

// 삭제
$query = "delete from myflashcard_tb ";
$query .= "where mf_id = '" . $mf_id . "' ";
$query .= "limit 1 ";

if ($result = db_query($query)) {    
    alert("Deleted.");
}
else {
    alert_back("Failed to Delete."); 
}

// 페이지 이동
$location_href = "myflashcard_study.php?dummy=dummy";
$location_href .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];

$location_href .= "&sorttype=" . $_REQUEST["sorttype"];

location_href($location_href);

?>