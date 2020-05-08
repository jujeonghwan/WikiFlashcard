<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

////////////////////////////////////////////////////////////////////////////////
// 정렬순서 없을 경우 설정 화면 보여줌
if ($_REQUEST["sorttype"] == "") {
    
    tp_read("myflashcard_study_sorttype.html");             // 정렬순서 설정

    // 경로
    tp_set("menu_navigator", get_menu_navigator());


    // QUERY_STRING
    $QUERY_STRING = "dummy=dummy";
    $QUERY_STRING .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
    $QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
    $QUERY_STRING .= "&page=" . $_REQUEST["page"];

    // $QUERY_STRING .= "&sorttype=" . $_REQUEST["sorttype"];

    // 정방향 학습하기 (Study Ascend)
    $study_sorttype_1_link_href = "myflashcard_study.php?" . $QUERY_STRING . "&sorttype=" . $db_common_sorttype_array["Ascend"];
    tp_set("study_sorttype_1_link_href", $study_sorttype_1_link_href);

    // 역방향 학습하기 (Study Descend)
    $study_sorttype_2_link_href = "myflashcard_study.php?" . $QUERY_STRING . "&sorttype=" . $db_common_sorttype_array["Descend"];
    tp_set("study_sorttype_2_link_href", $study_sorttype_2_link_href);


    // 내플래시카드 조회 (1항목)
    $query = "select ";
    $query .= "mf_id, ";
    $query .= "mf_user, ";
    $query .= "mf_boxstep, ";
    $query .= "mf_cardset, ";
    $query .= "mf_frontcontent, ";
    $query .= "mf_backcontent, ";
    $query .= "mf_regtime, ";
    $query .= "mf_studytime, ";
    $query .= "mf_testtime, ";

    $query .= "cs_setname, ";               // 세트명 (Cardset Name)
    $query .= "cs_frontitemname, ";
    $query .= "cs_backitemname ";

    $query .= "from myflashcard_tb ";       // 내플래시카드 (My Flashcard)

    $query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
    $query .= "on mf_cardset = cs_id ";

    $query .= "where mf_user = '" . $_SESSION["session_u_id"] . "' ";
    $query .= "and mf_boxstep < '" . $db_mf_boxstep_array["Completion"] . "' ";
    if ($_REQUEST["search_mf_cardset"] != "") {
        $query .= "and mf_cardset = '" . $_REQUEST["search_mf_cardset"] . "' ";
    }
    if ($_REQUEST["search_keyword"] != "") {
        $query .= "and (mf_frontcontent like '%" . $_REQUEST["search_keyword"] . "%' ";             // 앞면항목내용 (Front Content)
        $query .= "or mf_backcontent like '%" . $_REQUEST["search_keyword"] . "%') ";               // 뒷면항목내용 (Back Content)
    }

    // $query .= "order by mf_studytime, mf_id ";
    $query .= "order by mf_studytime, rand() ";
    $query .= "limit 1 ";

    $result = db_query($query);

    if (!$row = db_fetch_array($result)) {
        // alert_back("The item does not exist.");
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
tp_set("search_mf_cardset", $_REQUEST["search_mf_cardset"]);
tp_set("search_keyword", $_REQUEST["search_keyword"]);
tp_set("page", $_REQUEST["page"]);

// 정렬순서 (Sort)
$sorttype = trim($_REQUEST["sorttype"]);
tp_set("sorttype", $sorttype);


// QUERY_STRING
$QUERY_STRING = "dummy=dummy";
$QUERY_STRING .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
$QUERY_STRING .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$QUERY_STRING .= "&page=" . $_REQUEST["page"];

$QUERY_STRING .= "&sorttype=" . $_REQUEST["sorttype"];


// 내플래시카드의 갯수를 구함
$flashcard_total_count = get_myflashcard_count("", $_REQUEST["search_mf_cardset"], $_REQUEST["search_keyword"]);
tp_set("flashcard_total_count", $flashcard_total_count);


// 내플래시카드 조회 (1항목)
$query = "select ";
$query .= "mf_id, ";
$query .= "mf_user, ";
$query .= "mf_boxstep, ";
$query .= "mf_cardset, ";
$query .= "mf_flashcard, ";
$query .= "mf_frontcontent, ";
$query .= "mf_backcontent, ";
$query .= "mf_regtime, ";
$query .= "mf_studytime, ";
$query .= "mf_testtime, ";

$query .= "cs_setname, ";               // 세트명 (Cardset Name)
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname ";

$query .= "from myflashcard_tb ";       // 내플래시카드 (My Flashcard)

$query .= "inner join cardset_tb ";     // 카드세트 (Cardset)
$query .= "on mf_cardset = cs_id ";

$query .= "where mf_user = '" . $_SESSION["session_u_id"] . "' ";
$query .= "and mf_boxstep < '" . $db_mf_boxstep_array["Completion"] . "' ";
if ($_REQUEST["search_mf_cardset"] != "") {
    $query .= "and mf_cardset = '" . $_REQUEST["search_mf_cardset"] . "' ";
}
if ($_REQUEST["search_keyword"] != "") {
    $query .= "and (mf_frontcontent like '%" . $_REQUEST["search_keyword"] . "%' ";                 // 앞면항목내용 (Front Content)
    $query .= "or mf_backcontent like '%" . $_REQUEST["search_keyword"] . "%') ";                   // 뒷면항목내용 (Back Content)
}

// $query .= "order by mf_studytime, mf_id ";
$query .= "order by mf_studytime, rand() ";
$query .= "limit 1 ";

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    // alert_back("The item does not exist.");
}

$mf_id = $row["mf_id"];
$fc_id = $row["mf_flashcard"];          // 플래시카드번호 (Flashcard ID)

tp_set("cs_setname", $row["cs_setname"]);

tp_set("color_sorttype", $color_common_sorttype_array[$sorttype]);
tp_set("sorttype_text", array_search($sorttype, $db_common_sorttype_array));


// 제목/내용 배열 초기화
$no = 0;
$title_array                    =   array();
$content_array                  =   array();
$searchlink_button_list_array   =   array();

$no++;
$title_array[$no]                   =   $row["cs_frontitemname"];
$content_array[$no]                 =   $row["mf_frontcontent"];
$searchlink_button_list_array[$no]  =   get_searchlink_button_list($row["mf_frontcontent"]);

$no++;
$title_array[$no]                   =   $row["cs_backitemname"];
$content_array[$no]                 =   $row["mf_backcontent"];
$searchlink_button_list_array[$no]  =   get_searchlink_button_list($row["mf_backcontent"]);

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


// 다음 (Next) 버튼
$button_next_link_href = "myflashcard_study.php?" . $QUERY_STRING;
$button_next = "<a class=\"btn btn-primary\" href=\"" . $button_next_link_href . "\" role=\"button\">Next</a>";
tp_set("button_next", $button_next);



// 플래시카드 수정하기 [새창] (Edit Flashcard [New Window]) 버튼
if ( ($fc_id != "") && ($fc_id != 0) ) {
    $flashcard_edit_link_href = "/flashcard/flashcard_edit.php?fc_id=" . $fc_id;
    $button_flashcard_edit = "<a class=\"btn btn-warning\" href=\"" . $flashcard_edit_link_href . "\" target=\"_blank\" role=\"button\">Edit Flashcard [New Window]</a>";
}
else {
    $button_flashcard_edit = "";
}
tp_set("button_flashcard_edit", $button_flashcard_edit);


// 현재 내 플래시카드 삭제 (Delete Current My Flashcard) 버튼
$myflashcard_delete_process_link_href = "/myflashcard/myflashcard_delete_process.php?" . $QUERY_STRING . "&mf_id=" . $mf_id;
$button_myflashcard_delete = "<a class=\"btn btn-danger\" href=\"" . $myflashcard_delete_process_link_href . "\" role=\"button\">Delete Current My Flashcard</a>";
tp_set("button_myflashcard_delete", $button_myflashcard_delete);



// 학습일시 (Study Time) 설정
set_myflashcard_studytime($mf_id);


tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>