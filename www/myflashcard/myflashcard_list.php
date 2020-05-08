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


// 카드세트 (Cardset)
$search_mf_cardset = trim($_REQUEST["search_mf_cardset"]);

$query = "select ";
$query .= "cs_id, ";
$query .= "cs_setname ";
$query .= "from cardset_tb ";

$query .= "inner join myflashcard_tb "; // 내플래시카드 (My Flashcard)
$query .= "on cs_id = mf_cardset ";

$query .= "where mf_user = '" . $_SESSION["session_u_id"] . "' ";

$query .= "group by cs_setname, cs_id ";
$query .= "order by cs_setname, cs_id ";

$result = db_query($query);

$db_mf_cardset_array = array();
while ($row = db_fetch_array($result)) {
    $db_mf_cardset_array[$row["cs_id"]] = $row["cs_setname"];
}
$option_array = $db_mf_cardset_array;
$option = get_select_option("--All--", $option_array, $search_mf_cardset);
tp_set("option_search_mf_cardset", $option);


// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);
tp_set("search_keyword", $search_keyword);

// 페이지 초기화
$page = page_init();

// 페이지 표시 항목수
// $PAGE_VAR["list_count"] = 100;

// PAGE_STRING
$PAGE_STRING = "dummy=dummy";
$PAGE_STRING .= "&search_mf_cardset=" . $search_mf_cardset;
$PAGE_STRING .= "&search_keyword=" . urlencode($search_keyword);

// QUERY_STRING
$QUERY_STRING = $PAGE_STRING;
$QUERY_STRING .= "&page=" . $page;

// 내플래시카드 학습하기 (Study My Flashcard) 버튼 링크
$myflashcard_study_link_href = "myflashcard_study.php?" . $QUERY_STRING;
tp_set("myflashcard_study_link_href", $myflashcard_study_link_href);

// 내플래시카드 등록하기 (Add My Flashcard) 버튼 링크
$flashcard_list_link_href = "/flashcard/flashcard_list.php?";
tp_set("flashcard_list_link_href", $flashcard_list_link_href);


// 쿼리
$where_query = "where 1 = 1 ";
$where_query .= "and mf_user = '" . $_SESSION["session_u_id"] . "' ";
if ($search_mf_cardset != "") {
    $where_query .= "and mf_cardset = '" . $search_mf_cardset . "' ";
}
if ($search_keyword != "") {
    $where_query .= "and (mf_frontcontent like '%" . $search_keyword . "%' ";   // 앞면항목내용 (Front Content)
    $where_query .= "or mf_backcontent like '%" . $search_keyword . "%') ";     // 뒷면항목내용 (Back Content)
}

$orderby_query = "order by mf_studytime, mf_id ";

// 전체개수
$query = "select count(*) as total_count ";
$query .= "from myflashcard_tb ";
$query .= $where_query;

$result = db_query($query);
$row = db_fetch_array($result);

$page_query = "myflashcard_list.php?" . $PAGE_STRING . "&page=";
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
$query .= "mf_id, ";
$query .= "mf_user, ";
$query .= "mf_boxstep, ";
$query .= "mf_cardset, ";
$query .= "mf_flashcard, ";             // 플래시카드번호 (Flashcard ID)
$query .= "mf_frontcontent, ";
$query .= "mf_backcontent, ";
$query .= "mf_regtime, ";
$query .= "mf_studytime, ";
$query .= "mf_testtime, ";

$query .= "cs_setname ";                // 세트명 (Cardset Name)

$query .= "from myflashcard_tb ";       // 내플래시카드 (My Flashcard)

$query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
$query .= "on mf_cardset = cs_id ";

$query .= $where_query;
$query .= $orderby_query;
$query .= "limit " . $begin_row . ", " . $PAGE_VAR["list_count"] . " ";

$result = db_query($query);

while ($row = db_fetch_array($result)) {
    $no++;

    // 플래시카드 수정하기 [새창] (Edit Flashcard [New Window]) 버튼
    if ( ($row["mf_flashcard"] != "") && ($row["mf_flashcard"] != 0) ) {
        $flashcard_edit_link_href = "/flashcard/flashcard_edit.php?fc_id=" . $row["mf_flashcard"];
        $button_flashcard_edit = "<a class=\"btn btn-warning btn-sm\" href=\"" . $flashcard_edit_link_href . "\" target=\"_blank\" role=\"button\">Edit [New Window]</a>";
    }
    else {
        $button_flashcard_edit = "";
    }
        
    tp_set($template, array(
        "mf_id"                 =>  $row["mf_id"],
        "no"                    =>  $no,
        "cs_setname"            =>  $row["cs_setname"],
        "mf_frontcontent"       =>  nl2br($row["mf_frontcontent"]),
        "mf_backcontent"        =>  nl2br($row["mf_backcontent"]),
        "color_mf_boxstep"      =>  $color_mf_boxstep_array[$row["mf_boxstep"]],  
        "mf_boxstep"            =>  array_search($row["mf_boxstep"], $db_mf_boxstep_array),
        "mf_studytime"          =>  get_list_datetime_format($row["mf_studytime"]),
        "button_flashcard_edit" =>  $button_flashcard_edit
    ));
    tp_parse($template);
}

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>