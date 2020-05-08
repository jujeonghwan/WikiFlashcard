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
tp_set("search_mf_cardset", $_REQUEST["search_mf_cardset"]);
tp_set("search_mf_boxstep", $_REQUEST["search_mf_boxstep"]);
tp_set("page", $_REQUEST["page"]);


// QUERY_STRING
$QUERY_STRING = "dummy=dummy";
$QUERY_STRING .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
$QUERY_STRING .= "&search_mf_boxstep=" . $_REQUEST["search_mf_boxstep"];
$QUERY_STRING .= "&page=" . $_REQUEST["page"];


// 박스단계 추천수량 (Box Step Recommended Quantity)
$boxstep_recommended_quantity = "";
foreach ($count_mf_boxstep_array as $key => $val) {

    $boxstep_recommended_quantity .= "Step" . $key . "(" . number_format($val) . ")";
    $boxstep_recommended_quantity .= "&nbsp;&nbsp;&nbsp;";
}
tp_set("boxstep_recommended_quantity", $boxstep_recommended_quantity);


// 카드세트
$search_mf_cardset = trim($_REQUEST["search_mf_cardset"]);

// 박스단계 (Box Step)
$search_mf_boxstep = trim($_REQUEST["search_mf_boxstep"]);
if ($search_mf_boxstep == "") {
    $search_mf_boxstep = current($db_mf_boxstep_array);     // 배열의 첫번째 값
}

// 박스단계 (Box Step) 탭
$template = "row_tab";
tp_dynamic($template);

foreach ($db_mf_boxstep_array as $key => $val) {
    $tab_name = $key;
    $mf_boxstep = $val;

    // 탭 현재 여부
    if ($search_mf_boxstep == $mf_boxstep) {
        $class_tab_active = "active";
    }
    else {
        $class_tab_active = "";
    }

    // 탭 링크
    $tab_link = "quizbox_myflashcard_quiz.php?dummy=dummy";
    $tab_link .= "&search_mf_cardset=" . $search_mf_cardset;
    $tab_link .= "&search_mf_boxstep=" . $mf_boxstep;

    // 박스단계 (Box Step) 별 항목수
    $tab_count = get_myflashcard_count($mf_boxstep, $search_mf_cardset);

    tp_set($template, array(
        "class_tab_active"  =>  $class_tab_active,
        "tab_link"          =>  $tab_link,
        "tab_name"          =>  $tab_name,
        "tab_count"         =>  number_format($tab_count)
    ));
    tp_parse($template);
}

tp_set("mf_boxstep_text", array_search($mf_boxstep, $db_mf_boxstep_array));

// 남은 카드 갯수 (Remaining Card Count)
$flashcard_total_count = get_myflashcard_count($search_mf_boxstep, $search_mf_cardset);
tp_set("flashcard_total_count", $flashcard_total_count);


// 내플래시카드번호 (My Flashcard ID)
$mf_id = trim($_REQUEST["mf_id"]);

// 뒷면항목내용 (Back Content) 입력 항목
$mf_backcontent_check = trim($_REQUEST["mf_backcontent_check"]);



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

$query .= "where mf_id = '" . $mf_id . "' ";
$query .= "and mf_user = '" . $_SESSION["session_u_id"] . "' ";
$query .= "and mf_boxstep = '" . $search_mf_boxstep . "' ";
if ($search_mf_cardset != "") {
    $query .= "and mf_cardset = '" . $search_mf_cardset . "' ";   
}

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    alert_back("The item does not exist.");
}

tp_set("mf_id", $row["mf_id"]);
$fc_id = $row["mf_flashcard"];          // 플래시카드번호 (Flashcard ID)

tp_set("cs_setname", $row["cs_setname"]);

tp_set("cs_frontitemname_title", nl2br($row["cs_frontitemname"]));
tp_set("mf_frontcontent", nl2br($row["mf_frontcontent"]));

$mf_frontcontent_searchlink_button_list = get_searchlink_button_list($row["mf_frontcontent"]);
tp_set("mf_frontcontent_searchlink_button_list", $mf_frontcontent_searchlink_button_list);

tp_set("cs_backitemname_title", nl2br($row["cs_backitemname"]));
tp_set("mf_backcontent", nl2br($row["mf_backcontent"]));

$mf_backcontent_searchlink_button_list = get_searchlink_button_list($row["mf_backcontent"]);
tp_set("mf_backcontent_searchlink_button_list", $mf_backcontent_searchlink_button_list);

// 입력내용 (your input)
if (trim($row["mf_backcontent"]) == trim($mf_backcontent_check)) {
    $color_mf_backcontent_check = "blue";
}
else {
    $color_mf_backcontent_check = "red";
}
tp_set("color_mf_backcontent_check", $color_mf_backcontent_check);
tp_set("mf_backcontent_check", nl2br($mf_backcontent_check));



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

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>