<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read();

// 경로
tp_set("menu_navigator", get_menu_navigator());


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
$option = get_select_option("--All--", $option_array, $search_fc_cardset);
tp_set("option_search_fc_cardset", $option);


// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);
tp_set("search_keyword", $search_keyword);

// 페이지 초기화
$page = page_init();

// 페이지 표시 항목수
if ($search_fc_cardset != "") {
    $PAGE_VAR["list_count"] = 1000;
}
else {
    // 기본값
    // $PAGE_VAR["list_count"] = 20;
}


// PAGE_STRING
$PAGE_STRING = "dummy=dummy";
$PAGE_STRING .= "&search_fc_cardset=" . $search_fc_cardset;
$PAGE_STRING .= "&search_keyword=" . urlencode($search_keyword);

// QUERY_STRING
$QUERY_STRING = $PAGE_STRING;
$QUERY_STRING .= "&page=" . $page;

// 등록하기 (Add) 버튼 링크
$add_link_href = "flashcard_add.php?" . $QUERY_STRING;
tp_set("add_link_href", $add_link_href);


// 앞면항목명, 뒷면항목명 타이틀
tp_set("cs_frontitemname_title", nl2br($cs_frontitemname_title));
tp_set("cs_backitemname_title", nl2br($cs_backitemname_title));


// 쿼리
$where_query = "where 1 = 1 ";
if ($search_fc_cardset != "") {
    $where_query .= "and fc_cardset = '" . $search_fc_cardset . "' ";
}
if ($search_keyword != "") {
    $where_query .= "and (fc_frontcontent like '%" . $search_keyword . "%' ";   // 앞면항목내용 (Front Content)
    $where_query .= "or fc_backcontent like '%" . $search_keyword . "%') ";     // 뒷면항목내용 (Back Content)
}

// 정렬순서
if ($search_fc_cardset != "") {
    $orderby_query = "order by fc_order, fc_id ";
}
else {
    $orderby_query = "order by fc_id desc ";
}


// 전체개수
$query = "select count(*) as total_count ";
$query .= "from flashcard_tb ";
$query .= $where_query;

$result = db_query($query);
$row = db_fetch_array($result);

$page_query = "flashcard_list.php?" . $PAGE_STRING . "&page=";
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
$query .= "ifnull(mf_id, '') as mf_id ";                    // 내플래시카드번호 (My Flashcard ID)

$query .= "from flashcard_tb ";         // 플래시카드 (Flashcard)

$query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
$query .= "on fc_cardset = cs_id ";

$query .= "left outer join myflashcard_tb ";                // 내플래시카드 (My Flashcard)
$query .= "on fc_cardset = mf_cardset ";
$query .= "and fc_frontcontent = mf_frontcontent ";
$query .= "and fc_backcontent = mf_backcontent ";
$query .= "and mf_user = '" . $_SESSION["session_u_id"] . "' ";

$query .= $where_query;
$query .= $orderby_query;
$query .= "limit " . $begin_row . ", " . $PAGE_VAR["list_count"] . " ";

$result = db_query($query);

while ($row = db_fetch_array($result)) {
    $no++;

    // 체크박스
    if ($row["mf_id"] == "") {
        $checkbox_fc_id = "<input type=\"checkbox\" id=\"check_fc_id_" . $row["fc_id"] . "\" name=\"check_fc_id[]\" value=\"" . $row["fc_id"] . "\" />";
    }
    else {
        $checkbox_fc_id = "";    
    }
    
    // 조회하기 링크
    $view_link_href = "flashcard_view.php?" . $QUERY_STRING . "&fc_id=" . $row["fc_id"];

    // 순서변경 (Change Order) 버튼
    if ($search_fc_cardset != "") {
        if ($prev_fc_id != "") {
            $order_change_link_href = "flashcard_order_change.php?" . $QUERY_STRING . "&fc_id=" . $row["fc_id"] . "&prev_fc_id=" . $prev_fc_id;
            
            $button_order_change = "<a class=\"btn btn-info btn-xs\" href=\"" . $order_change_link_href . "\" role=\"button\"> ▲ </a>";    
        }
        else {
            $button_order_change = "";    
        }
    }
    else {
        $button_order_change = ""; 
    }        
    
    tp_set($template, array(
        "checkbox_fc_id"        =>  $checkbox_fc_id,
        // "fc_id"              =>  $row["fc_id"],
        "no"                    =>  $no,
        "cs_setname"            =>  $row["cs_setname"],
        "view_link_href"        =>  $view_link_href,
        "fc_frontcontent"       =>  nl2br($row["fc_frontcontent"]),
        "fc_backcontent"        =>  nl2br($row["fc_backcontent"]),
        "button_order_change"   =>  $button_order_change
    ));
    tp_parse($template);

    $prev_fc_id = $row["fc_id"];        // 이전 플래시카드번호 (Flashcard ID)
}

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>