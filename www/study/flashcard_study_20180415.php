<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

////////////////////////////////////////////////////////////////////////////////
// 정렬순서 없을 경우 설정 화면 보여줌
if ($_REQUEST["sorttype"] == "") {
    
    tp_read("flashcard_study_sorttype.html");               // 정렬순서 설정

    // 경로
    tp_set("menu_navigator", get_menu_navigator());


    // QUERY_STRING
    $QUERY_STRING = "dummy=dummy";
    $QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
    $QUERY_STRING .= "&page=" . $_REQUEST["page"];

    $QUERY_STRING .= "&cs_id=" . $_REQUEST["cs_id"];
    // $QUERY_STRING .= "&sorttype=" . $_REQUEST["sorttype"];

    // 정방향 학습하기 (Study Ascend)
    $study_sorttype_1_link_href = "flashcard_study.php?" . $QUERY_STRING . "&sorttype=" . $db_common_sorttype_array["Ascend"];
    tp_set("study_sorttype_1_link_href", $study_sorttype_1_link_href);

    // 역방향 학습하기 (Study Descend)
    $study_sorttype_2_link_href = "flashcard_study.php?" . $QUERY_STRING . "&sorttype=" . $db_common_sorttype_array["Descend"];
    tp_set("study_sorttype_2_link_href", $study_sorttype_2_link_href);


    // 카드세트번호 (Cardset ID)
    $cs_id = trim($_REQUEST["cs_id"]);
    tp_set("cs_id", $cs_id);


    // 카드세트 조회
    $query = "select ";
    $query .= "cs_id, ";
    $query .= "cs_setname, ";
    $query .= "cs_frontitemname, ";
    $query .= "cs_backitemname ";
    $query .= "from cardset_tb ";       // 카드세트 (Cardset)
    $query .= "where cs_id = '" . $cs_id . "' ";

    $result = db_query($query);

    if (!$row = db_fetch_array($result)) {
        // alert_back("해당 항목이 존재하지 않습니다.");
    }

    tp_set("cs_setname", $row["cs_setname"]);
    tp_set("cs_frontitemname", nl2br($row["cs_frontitemname"]));
    tp_set("cs_backitemname", nl2br($row["cs_backitemname"]));

    tp_print();

    require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

    exit;

}
// 정렬순서 없을 경우 설정 화면 보여줌
////////////////////////////////////////////////////////////////////////////////


tp_read();    

// 경로
tp_set("menu_navigator", get_menu_navigator());


// 목록 페이지 조회조건
tp_set("search_keyword", $_REQUEST["search_keyword"]);
tp_set("page", $_REQUEST["page"]);

// 카드세트번호 (Cardset ID)
$cs_id = trim($_REQUEST["cs_id"]);
tp_set("cs_id", $cs_id);

// 정렬순서 (Sort)
$sorttype = trim($_REQUEST["sorttype"]);
tp_set("sorttype", $sorttype);


// QUERY_STRING
$QUERY_STRING = "dummy=dummy";
$QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$QUERY_STRING .= "&page=" . $_REQUEST["page"];

$QUERY_STRING .= "&cs_id=" . $_REQUEST["cs_id"];
$QUERY_STRING .= "&sorttype=" . $_REQUEST["sorttype"];


// 현재, 이전, 다음 플래시카드 플래시카드번호 (Flashcard ID)
// 현재 플래시카드번호 (Flashcard ID)
$fc_id = trim($_REQUEST["fc_id"]);
if ($fc_id == "") {
    $fc_id = get_first_flashcard($cs_id);                   // 첫번째 순서의 플래시카드를 구함
}
tp_set("fc_id", $fc_id);


// 이전
$prev_fc_id = get_prev_flashcard($fc_id, $cs_id);           // 이전 순서의 플래시카드를 구함

// 이전 (Prev) 버튼
if ($prev_fc_id != "") {
    $button_prev_link_href = "flashcard_study.php?" . $QUERY_STRING . "&fc_id=" . $prev_fc_id;
    $button_prev = "<a class=\"btn btn-info\" href=\"" . $button_prev_link_href . "\" role=\"button\">Prev</a>";
}
else {
    $button_prev = "<strong>First</strong>";
}
tp_set("button_prev", $button_prev);


// 다음
$next_fc_id = get_next_flashcard($fc_id, $cs_id);           // 다음 순서의 플래시카드를 구함

// 다음 (Next) 버튼
if ($next_fc_id != "") {
    $button_next_link_href = "flashcard_study.php?" . $QUERY_STRING . "&fc_id=" . $next_fc_id;
    $button_next = "<a class=\"btn btn-primary\" href=\"" . $button_next_link_href . "\" role=\"button\">Next</a>";
}
else {   
    $button_next = "<strong>Last</strong>";
    
    // 다른 카드세트 조회 (다른 카드세트 학습하기 (Study Random Cardset))
    $query = "select ";
    $query .= "cs_id ";
    $query .= "from cardset_tb ";       // 카드세트 (Cardset)
    $query .= "where cs_id != '" . $cs_id . "' ";
    $query .= "order by rand() ";
    $query .= "limit 1 ";

    $result_random = db_query($query);

    if ($row_random = db_fetch_array($result_random)) {
        $button_random_link_href = "flashcard_study.php?";

        $button_random_link_href .= "dummy=dummy";
        $button_random_link_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
        $button_random_link_href .= "&page=" . $_REQUEST["page"];

        $button_random_link_href .= "&cs_id=" . $row_random["cs_id"];
        // $button_random_link_href .= "&sorttype=" . $_REQUEST["sorttype"];
        $button_random_link_href .= "&sorttype=";
    
        $button_next .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
        $button_next .= "<a class=\"btn btn-primary\" href=\"" . $button_random_link_href . "\" role=\"button\">Study Random Cardset</a>";
    }
}
tp_set("button_next", $button_next);


// 플래시카드의 현재위치 및 전체갯수(위치)를 구함
$flashcard_current_pos = get_flashcard_current_pos($fc_id, $cs_id);
tp_set("flashcard_current_pos", $flashcard_current_pos);



// 현재 플래시카드 조회
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
$query .= "u2.u_accountid as fc_updateuser_accountid, ";

$query .= "ifnull(mf_id, '') as mf_id ";                    // 내플래시카드번호 (My Flashcard ID)

$query .= "from flashcard_tb ";         // 카드세트 (Cardset)

$query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
$query .= "on fc_cardset = cs_id ";

$query .= "left outer join user_tb u1 ";
$query .= "on fc_reguser = u1.u_id ";

$query .= "left outer join user_tb u2 ";
$query .= "on fc_updateuser = u2.u_id ";

$query .= "left outer join myflashcard_tb ";                // 내플래시카드 (My Flashcard)
$query .= "on fc_cardset = mf_cardset ";
$query .= "and fc_frontcontent = mf_frontcontent ";
$query .= "and fc_backcontent = mf_backcontent ";
$query .= "and mf_user = '" . $_SESSION["session_u_id"] . "' ";

$query .= "where fc_id = '" . $fc_id . "' ";

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    // alert_back("해당 항목이 존재하지 않습니다.");
}


tp_set("cs_setname", $row["cs_setname"]);

tp_set("color_sorttype", $color_common_sorttype_array[$sorttype]);
tp_set("sorttype_text", array_search($sorttype, $db_common_sorttype_array));



// 플래시카드 수정하기 (Edit Flashcard) 버튼 링크
$flashcard_edit_link_href = "/flashcard/flashcard_edit.php?fc_id=" . $fc_id;
tp_set("flashcard_edit_link_href", $flashcard_edit_link_href);


// 내 플래시카드 추가 (Add To My Flashcard) 버튼
if ($row["mf_id"] == "") {
    $myflashcard_add_process_link_href = "flashcard_myflashcard_add_process.php?" . $QUERY_STRING . "&fc_id=" . $fc_id;

    $button_myflashcard_add = "<a class=\"btn btn-success\" href=\"" . $myflashcard_add_process_link_href . "\" role=\"button\">Add To My Flashcard</a>";
}
else {
    $button_myflashcard_add = "";
}
tp_set("button_myflashcard_add", $button_myflashcard_add);



// 제목/내용 배열 초기화
$no = 0;
$title_array                    =   array();
$content_array                  =   array();
$searchlink_button_list_array   =   array();

$no++;
$title_array[$no]                   =   $row["cs_frontitemname"];
$content_array[$no]                 =   $row["fc_frontcontent"];
$searchlink_button_list_array[$no]  =   get_searchlink_button_list($row["fc_frontcontent"]);

$no++;
$title_array[$no]                   =   $row["cs_backitemname"];
$content_array[$no]                 =   $row["fc_backcontent"];
$searchlink_button_list_array[$no]  =   get_searchlink_button_list($row["fc_backcontent"]);

// 목록
$template = "row";
tp_dynamic($template);

// 정렬
if ($sorttype == $db_common_sorttype_array["Ascend"]) {
    ksort($title_array);
}
else if ($sorttype == $db_common_sorttype_array["Descend"]) {
    krsort($title_array);
}

$no = 0;
foreach ($title_array as $key => $val) {
    $no++;

    // 로딩시 내용(content) 보여줄지 여부
    if ($no == 1) {
        $calss_show = "show";
    }
    else {
        $calss_show = "";
    }
       
    // $calss_show = "";                   // 처음에 모두 숨김

    tp_set($template, array(
        "no"                        =>  $no,
        "title"                     =>  nl2br($title_array[$key]),
        "calss_show"                =>  $calss_show,
        "content"                   =>  nl2br($content_array[$key]),
        "searchlink_button_list"    =>  $searchlink_button_list_array[$key]
    ));
    tp_parse($template);
}

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>