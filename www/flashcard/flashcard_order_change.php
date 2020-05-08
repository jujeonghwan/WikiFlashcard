<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$fc_id_1    =   trim($_REQUEST["fc_id"]);
$fc_id_2    =   trim($_REQUEST["prev_fc_id"]);
/*
echo "<br />fc_id_1 : " . $fc_id_1;
echo "<br />fc_id_2 : " . $fc_id_2;
exit;
*/

// 기존순서 구함(1)
$query = "select ";
$query .= "fc_order as fc_order_1 ";
$query .= "from flashcard_tb ";
$query .= "where fc_id = '" . $fc_id_1 . "' ";

$result = db_query($query);

if ($row = db_fetch_array($result)) {
    $fc_order_2 = $row["fc_order_1"];   // 순서 바꿈
}

// 기존순서 구함(2)
$query = "select ";
$query .= "fc_order as fc_order_2 ";
$query .= "from flashcard_tb ";
$query .= "where fc_id = '" . $fc_id_2 . "' ";

$result = db_query($query);

if ($row = db_fetch_array($result)) {
    $fc_order_1 = $row["fc_order_2"];   // 순서 바꿈
}


// 순서 변경(1)
$query = "update flashcard_tb set ";
$query .= "fc_order = '" . $fc_order_1 . "' ";
$query .= "where fc_id = '" . $fc_id_1 . "' ";
$query .= "limit 1 ";

db_query($query);

// 순서 변경(2)
$query = "update flashcard_tb set ";
$query .= "fc_order = '" . $fc_order_2 . "' ";
$query .= "where fc_id = '" . $fc_id_2 . "' ";
$query .= "limit 1 ";

db_query($query);

// 페이지 이동
$location_href = "flashcard_list.php?dummy=dummy";
$location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>