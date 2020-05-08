<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

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

// 돌아가기 (Return) 버튼 링크 (View)
$view_link_href = "cardset_view.php?" . $QUERY_STRING . "&cs_id=" . $_REQUEST["cs_id"];
tp_set("view_link_href", $view_link_href);


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
tp_set("cs_frontitemname", $row["cs_frontitemname"]);
tp_set("cs_backitemname", $row["cs_backitemname"]);

tp_set("cs_reguser_accountid", $row["cs_reguser_accountid"]);
tp_set("cs_regtime", get_datetime_format($row["cs_regtime"]));

tp_set("cs_updateuser_accountid", $row["cs_updateuser_accountid"]);
tp_set("cs_updatetime", get_datetime_format($row["cs_updatetime"]));

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>