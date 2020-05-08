<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

html_meta_charset_utf8();

$fc_id = trim($_REQUEST["fc_id"]);

// 플래시카드 내플래시카드 등록
$flashcard_myflashcard_add = set_flashcard_myflashcard_add($fc_id);

if ($flashcard_myflashcard_add == true) {
    // alert("등록되었습니다. (Registered.)");
    // echo "<br /><br /><br /><span align=\"center\"><strong>등록되었습니다. (Registered.)</strong></span>";
    // sleep(1);
}
else {
    alert_back("등록하는데 실패했습니다. (Failed to register.)"); 
}

// 페이지 이동
$location_href = "flashcard_study.php?dummy=dummy";
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];

$location_href .= "&cs_id=" . $_REQUEST["cs_id"];
$location_href .= "&sorttype=" . $_REQUEST["sorttype"];
$location_href .= "&fc_id=" . $_REQUEST["fc_id"];

location_href($location_href);

?>