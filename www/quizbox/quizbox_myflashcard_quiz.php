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
$query .= "and mf_boxstep = '" . $search_mf_boxstep . "' ";
if ($search_mf_cardset != "") {
    $query .= "and mf_cardset = '" . $search_mf_cardset . "' ";   
}

// $query .= "order by mf_testtime, mf_id ";
$query .= "order by mf_testtime, rand() ";
$query .= "limit 1 ";

$result = db_query($query);

if (!$row = db_fetch_array($result)) {
    alert("The item does not exist.");

    // 페이지 이동
    $location_href = "quizbox_myflashcard_list.php?dummy=dummy";
    $location_href .= "&search_mf_cardset=" . $_REQUEST["search_mf_cardset"];
    $location_href .= "&search_mf_boxstep=" . $_REQUEST["search_mf_boxstep"];
    $location_href .= "&page=" . $_REQUEST["page"];
    location_href($location_href);
    exit;
}

$mf_id = $row["mf_id"];
tp_set("mf_id", $mf_id);

tp_set("cs_setname", $row["cs_setname"]);

tp_set("cs_frontitemname_title", nl2br($row["cs_frontitemname"]));
tp_set("mf_frontcontent", nl2br($row["mf_frontcontent"]));

tp_set("cs_backitemname_title", nl2br($row["cs_backitemname"]));

tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>