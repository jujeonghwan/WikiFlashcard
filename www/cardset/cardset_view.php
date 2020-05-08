<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read();

// 경로
tp_set("menu_navigator", get_menu_navigator());


// 목록 페이지 조회조건
tp_set("search_keyword", $_REQUEST["search_keyword"]);
tp_set("page", $_REQUEST["page"]);


// QUERY_STRING
$QUERY_STRING = "dummy=dummy";
$QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$QUERY_STRING .= "&page=" . $_REQUEST["page"];

// 목록보기 (List) 버튼 링크
// $list_link_href = "cardset_list.php?" . $QUERY_STRING;
$list_link_href = "/index.php?" . $QUERY_STRING;
tp_set("list_link_href", $list_link_href);

// 수정하기 (Edit) 버튼 링크
$edit_link_href = "cardset_edit.php?" . $QUERY_STRING . "&cs_id=" . $_REQUEST["cs_id"];
tp_set("edit_link_href", $edit_link_href);


// 카드세트번호 (Cardset ID)
$cs_id = trim($_REQUEST["cs_id"]);
tp_set("cs_id", $cs_id);

// 조회
$query = "select ";
$query .= "cs_id, ";
$query .= "cs_setname, ";
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname, ";
$query .= "cs_reguser, ";
$query .= "cs_regtime, ";
$query .= "cs_updateuser, ";
$query .= "cs_updatetime, ";

$query .= "u1.u_accountid as cs_reguser_accountid, ";
$query .= "u2.u_accountid as cs_updateuser_accountid ";

$query .= "from cardset_tb ";

$query .= "left outer join user_tb u1 ";
$query .= "on cs_reguser = u1.u_id ";

$query .= "left outer join user_tb u2 ";
$query .= "on cs_updateuser = u2.u_id ";

$query .= "where cs_id = '" . $cs_id . "' ";

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    alert_back("The item does not exist.");
}

tp_set("cs_setname", $row["cs_setname"]);
tp_set("cs_frontitemname", nl2br($row["cs_frontitemname"]));
tp_set("cs_backitemname", nl2br($row["cs_backitemname"]));

tp_set("cs_reguser_accountid", $row["cs_reguser_accountid"]);
tp_set("cs_regtime", get_datetime_format($row["cs_regtime"]));

tp_set("cs_updateuser_accountid", $row["cs_updateuser_accountid"]);
tp_set("cs_updatetime", get_datetime_format($row["cs_updatetime"]));

// 플래시카드 갯수 (Flashcard Count)
$flashcard_count = get_flashcard_count($row["cs_id"]);
tp_set("flashcard_count", number_format($flashcard_count));

// 내 플래시카드 갯수 (My Flashcard Count)
$myflashcard_count = get_myflashcard_count("", $row["cs_id"]);
tp_set("myflashcard_count", number_format($myflashcard_count));

// 학습하기 (Study Flashcard) 링크
$study_link_href = "/study/flashcard_study.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];
tp_set("study_link_href", $study_link_href);

// 플래시카드 관리 (Manage Flashcard) 링크
// $flashcard_view_link_href = "/flashcard/flashcard_list.php?" . $QUERY_STRING . "&search_fc_cardset=" . $row["cs_id"];
$flashcard_view_link_href = "/flashcard/flashcard_list.php?dummy=dummy&search_fc_cardset=" . $row["cs_id"];
tp_set("flashcard_view_link_href", $flashcard_view_link_href);

// 내 플래시카드 추가 (Add To My Flashcard) 링크
$myflashcard_add_link_href = "/cardset/cardset_myflashcard_add_process.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];
tp_set("myflashcard_add_link_href", $myflashcard_add_link_href);

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>