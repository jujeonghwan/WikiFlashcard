<?php


require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read($_SERVER["DOCUMENT_ROOT"] . "/main.html");

// 경로
tp_set("menu_navigator", get_menu_navigator());


// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);
tp_set("search_keyword", $search_keyword);

// 페이지 초기화
$page = page_init();

// 페이지 표시 항목수
if ($search_keyword != "") {
    $PAGE_VAR["list_count"] = 1000;
}
else {
    $PAGE_VAR["list_count"] = 10;
}

// PAGE_STRING
$PAGE_STRING = "dummy=dummy";
$PAGE_STRING .= "&search_keyword=" . urlencode($search_keyword);

// QUERY_STRING
$QUERY_STRING = $PAGE_STRING;
$QUERY_STRING .= "&page=" . $page;

// 등록하기 (Add) 버튼 링크
$cardset_add_link_href = "/cardset/cardset_add.php?" . $QUERY_STRING;
tp_set("cardset_add_link_href", $cardset_add_link_href);


// 쿼리
$where_query = "where 1 = 1 ";
if ($search_keyword != "") {
    $where_query .= "and (cs_setname like '%" . $search_keyword . "%' ";
    $where_query .= "or cs_frontitemname like '%" . $search_keyword . "%' ";
    $where_query .= "or cs_backitemname like '%" . $search_keyword . "%') ";
}

// 정렬순서
$orderby_query = "order by cs_id desc ";
// $orderby_query = "order by rand() ";
/*
if ($search_keyword != "") {
    $orderby_query = "order by cs_setname, cs_id ";
}
else {
    $orderby_query = "order by rand() ";
}
*/

// 전체개수
$query = "select count(*) as total_count ";
$query .= "from cardset_tb ";
$query .= $where_query;

$result = db_query($query);
$row = db_fetch_array($result);

$page_query = "index.php?" . $PAGE_STRING . "&page=";
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

    // 카드세트 (Cardset) 조회 링크
    $cardset_view_link_href = "/cardset/cardset_view.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];

    // 플래시카드 (Flashcard) 갯수
    $flashcard_count = get_flashcard_count($row["cs_id"]);

    // 내 플래시카드 갯수 (My Flashcard Count)
    $myflashcard_count = get_myflashcard_count("", $row["cs_id"]);

    // 카드세트 학습하기 (Study Cardset) 링크
    $study_link_href = "/study/flashcard_study.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];

    // 플래시카드 관리 (Manage Flashcard) 링크
    $flashcard_view_link_href = "/flashcard/flashcard_list.php?dummy=dummy&search_fc_cardset=" . $row["cs_id"];

    // 내 플래시카드 추가 (Add To My Flashcard) 링크
    $myflashcard_add_link_href = "/cardset_myflashcard_add_process.php?" . $QUERY_STRING . "&cs_id=" . $row["cs_id"];
    
    tp_set($template, array(
        "cardset_view_link_href"    =>  $cardset_view_link_href,
        "cs_setname"                =>  $row["cs_setname"],
        "flashcard_count"           =>  number_format($flashcard_count),
        "myflashcard_count"         =>  number_format($myflashcard_count),

        "cs_frontitemname"          =>  nl2br($row["cs_frontitemname"]),
        "cs_backitemname"           =>  nl2br($row["cs_backitemname"]),

        "study_link_href"           =>  $study_link_href,
        "flashcard_view_link_href"  =>  $flashcard_view_link_href,
        "myflashcard_add_link_href" =>  $myflashcard_add_link_href
    ));
    tp_parse($template);
}


tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");



////////////////////////////////////////////////////////////////////////////////
// 자동 실행 처리할 항목

$rand_value = rand(1, 2);
// echo "<br />rand_value " . $rand_value;

switch ($rand_value) {
    case 1:
        // 1       
        // 위키플래시카드 DB파일 정리 (내플래시카드, 사용자)
        set_wikiflashcard_db_auto_delete();

        break;

    case 2:
        // 2
        // 위키플래시카드 DB파일 정리 (내플래시카드, 사용자)
        set_wikiflashcard_db_auto_delete();
        
        break;

    default:
        // 

        break;
}

// 자동 실행 처리할 항목
////////////////////////////////////////////////////////////////////////////////

?>