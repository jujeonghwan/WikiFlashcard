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


// 앞면항목명, 뒷면항목명 타이틀 '기본값'
$cs_frontitemname_title = "Front Content";
$cs_backitemname_title = "Back Content";


// 카드세트 (Cardset)
$search_fc_cardset = trim($_REQUEST["search_fc_cardset"]);

$query = "select ";
$query .= "cs_id, ";
$query .= "cs_setname, ";
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname ";
$query .= "from cardset_tb ";
$query .= "order by cs_setname, cs_id ";

$result = db_query($query);

$db_fc_cardset_array = array();
while ($row = db_fetch_array($result)) {
    $db_fc_cardset_array[$row["cs_id"]] = $row["cs_setname"];

    if ($row["cs_id"] == $search_fc_cardset) {
        // 앞면항목명, 뒷면항목명 타이틀
        $cs_frontitemname_title = $row["cs_frontitemname"];
        $cs_backitemname_title = $row["cs_backitemname"];
    }
}
$option_array = $db_fc_cardset_array;
$option = get_select_option("--Select--", $option_array, $search_fc_cardset);
tp_set("option_fc_cardset", $option);


// 앞면항목명, 뒷면항목명 타이틀
tp_set("cs_frontitemname_title", nl2br($cs_frontitemname_title));
tp_set("cs_backitemname_title", nl2br($cs_backitemname_title));


// 이전 등록 항목 (Last registered item)
$query = "select ";
$query .= "fc_frontcontent, ";
$query .= "fc_backcontent ";
$query .= "from flashcard_tb ";
$query .= "where fc_cardset = '" . $search_fc_cardset . "' ";
$query .= "order by fc_id desc ";
// $query .= "limit 1 ";

$result_last = db_query($query);
$rows_last = db_num_rows($result_last);

if ($row_last = db_fetch_array($result_last)) {  
    $last_fc_count = $rows_last;  
    $last_fc_frontcontent = $row_last["fc_frontcontent"];
    $last_fc_backcontent = $row_last["fc_backcontent"];
}
else {    
    $last_fc_count = "";
    $last_fc_frontcontent = "";
    $last_fc_backcontent = "";
}

tp_set("last_fc_count", number_format($last_fc_count));
tp_set("last_fc_frontcontent", nl2br($last_fc_frontcontent));
tp_set("last_fc_backcontent", nl2br($last_fc_backcontent));

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>