<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read();

// 경로
tp_set("menu_navigator", get_menu_navigator());


// 검색어구분
$search_type_array = array (
    "cs_setname"        =>  "세트명 (Cardset Name)",
    "cs_frontitemname"  =>  "앞면항목명 (Front Item Name)",
    "cs_backitemname"   =>  "뒷면항목명 (Back Item Name)"
);

$search_type = trim($_REQUEST["search_type"]);
if ($search_type == "") {
    $search_type = key($search_type_array);                 // 배열의 첫번째 키값
}
$option_array = $search_type_array;
$option = get_select_option("", $option_array, $search_type);
tp_set("option_search_type", $option);

// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);
tp_set("search_keyword", $search_keyword);

// 페이지 초기화
$page = page_init();

// 페이지 표시 항목수
$PAGE_VAR["list_count"] = 1000;

// PAGE_STRING
$PAGE_STRING = "dummy=dummy";
$PAGE_STRING .= "&search_type=" . $search_type;
$PAGE_STRING .= "&search_keyword=" . urlencode($search_keyword);

// QUERY_STRING
$QUERY_STRING = $PAGE_STRING;
$QUERY_STRING .= "&page=" . $page;

// 등록하기 (Add) 버튼 링크
$add_link_href = "cardset_add.php?" . $QUERY_STRING;
tp_set("add_link_href", $add_link_href);


// 쿼리
$where_query = "where 1 = 1 ";
if (($search_type != "") && ($search_keyword != "")) {
    $where_query .= "and " . $search_type . " like '%" . $search_keyword . "%' ";
}

$orderby_query = "order by cs_id desc ";

// 전체개수
$query = "select count(*) as total_count ";
$query .= "from cardset_tb ";
$query .= $where_query;

$result = db_query($query);
$row = db_fetch_array($result);

$page_query = "cardset_list.php?" . $PAGE_STRING . "&page=";
$total_rows = $row["total_count"];
$total_page = calc_total_page($total_rows, $PAGE_VAR["list_count"]); 
$begin_row = calc_begin_row($page, $PAGE_VAR["list_count"]); 
$no = calc_begin_no($total_rows, $begin_row);

tp_set("total_rows", number_format($total_rows));
tp_set("page", $page);   
tp_set("total_page", number_format($total_page));
tp_set("pagination_link_list", pagination_link_list($page_query, $total_page, $page, $PAGE_VAR["page_count"], $PAGE_VAR["list_count"]));

// 목록
$template = "row";
tp_dynamic($template);

$query = "select ";
$query .= "cs_id, ";
$query .= "cs_setname, ";
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname, ";
$query .= "cs_reguser, ";
$query .= "cs_regtime, ";
$query .= "cs_updateuser, ";
$query .= "cs_updatetime ";
$query .= "from cardset_tb ";
$query .= $where_query;
$query .= $orderby_query;
$query .= "limit " . $begin_row . ", " . $PAGE_VAR["list_count"] . " ";

$result = db_query($query);

while ($row = db_fetch_array($result)) {
    $no--;
    
    // 조회하기 링크
    $view_link_href = "cardset_view.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];

    // 학습하기 (Study Flashcard) 링크
    $study_link_href = "/study/flashcard_study.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];

    // 플래시카드 관리 (Manage Flashcard) 링크
    $flashcard_view_link_href = "/flashcard/flashcard_list.php?dummy=dummy&search_fc_cardset=" . $row["cs_id"];

    // 내 플래시카드 추가 (Add To My Flashcard) 링크
    $myflashcard_add_link_href = "/cardset/cardset_myflashcard_add_process.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];
    
    tp_set($template, array(
        "no"                        =>  $no,
        "view_link_href"            =>  $view_link_href,
        "cs_setname"                =>  $row["cs_setname"],
        "cs_frontitemname"          =>  nl2br($row["cs_frontitemname"]),
        "cs_backitemname"           =>  nl2br($row["cs_backitemname"]),

        "cs_regtime"                =>  get_list_datetime_format($row["cs_regtime"]),
        "cs_updatetime"             =>  get_list_datetime_format($row["cs_updatetime"]),

        "study_link_href"           =>  $study_link_href,
        "flashcard_view_link_href"  =>  $flashcard_view_link_href,
        "myflashcard_add_link_href" =>  $myflashcard_add_link_href
    ));
    tp_parse($template);
}

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>