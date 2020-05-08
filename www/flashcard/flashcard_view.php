<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read();

// 경로
tp_set("menu_navigator", get_menu_navigator());


// 목록 페이지 조회조건
tp_set("search_fc_cardset", $_REQUEST["search_fc_cardset"]);
tp_set("search_keyword", $_REQUEST["search_keyword"]);
tp_set("page", $_REQUEST["page"]);


// QUERY_STRING
$QUERY_STRING = "dummy=dummy";
$QUERY_STRING .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$QUERY_STRING .= "&page=" . $_REQUEST["page"];

// 목록보기 (List) 버튼 링크
$list_link_href = "flashcard_list.php?" . $QUERY_STRING;
tp_set("list_link_href", $list_link_href);

// 수정하기 (Edit) 버튼 링크
$edit_link_href = "flashcard_edit.php?" . $QUERY_STRING . "&fc_id=" . $_REQUEST["fc_id"];
tp_set("edit_link_href", $edit_link_href);


// 플래시카드번호 (Flashcard ID)
$fc_id = trim($_REQUEST["fc_id"]);
tp_set("fc_id", $fc_id);

// 조회
$query = "select ";
$query .= "fc_id, ";
$query .= "fc_cardset, ";
$query .= "fc_frontcontent, ";
$query .= "fc_backcontent, ";
$query .= "fc_order, ";
$query .= "fc_reguser, ";
$query .= "fc_regtime, ";
$query .= "fc_updateuser, ";
$query .= "fc_updatetime, ";

$query .= "cs_setname, ";               // 세트명 (Cardset Name)
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname, ";

$query .= "u1.u_accountid as fc_reguser_accountid, ";
$query .= "u2.u_accountid as fc_updateuser_accountid ";

$query .= "from flashcard_tb ";         // 플래시카드 (Flashcard)

$query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
$query .= "on fc_cardset = cs_id ";

$query .= "left outer join user_tb u1 ";
$query .= "on fc_reguser = u1.u_id ";

$query .= "left outer join user_tb u2 ";
$query .= "on fc_updateuser = u2.u_id ";

$query .= "where fc_id = '" . $fc_id . "' ";

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    alert_back("The item does not exist.");
}

tp_set("cs_setname", $row["cs_setname"]);

tp_set("cs_frontitemname_title", nl2br($row["cs_frontitemname"]));
tp_set("fc_frontcontent", nl2br($row["fc_frontcontent"]));

$fc_frontcontent_searchlink_button_list = get_searchlink_button_list($row["fc_frontcontent"]);
tp_set("fc_frontcontent_searchlink_button_list", $fc_frontcontent_searchlink_button_list);

tp_set("cs_backitemname_title", nl2br($row["cs_backitemname"]));
tp_set("fc_backcontent", nl2br($row["fc_backcontent"]));

$fc_backcontent_searchlink_button_list = get_searchlink_button_list($row["fc_backcontent"]);
tp_set("fc_backcontent_searchlink_button_list", $fc_backcontent_searchlink_button_list);

tp_set("fc_reguser_accountid", $row["fc_reguser_accountid"]);
tp_set("fc_regtime", get_datetime_format($row["fc_regtime"]));

tp_set("fc_updateuser_accountid", $row["fc_updateuser_accountid"]);
tp_set("fc_updatetime", get_datetime_format($row["fc_updatetime"]));

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>