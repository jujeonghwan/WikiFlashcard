<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();


// 엑셀저장
// $filename = iconv_utf8_euckr("내플래시카드_" . current_datetime() . ".xls");
$filename = "MyFlashcard_" . current_datetime() . ".xls";
excel_header($filename);

html_meta_charset_utf8();

tp_read();


// 카드세트 (Cardset)
$search_mf_cardset = trim($_REQUEST["search_mf_cardset"]);

// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);


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

// $orderby_query = "order by mf_studytime, mf_id ";
$orderby_query = "order by mf_id ";

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

$result = db_query($query);

$no = 0;
while ($row = db_fetch_array($result)) {
    $no++;
    
    tp_set($template, array(
        "no"                =>  $no,
        "cs_setname"        =>  $row["cs_setname"],

        "mf_frontcontent"   =>  nl2br($row["mf_frontcontent"]),
        "mf_backcontent"    =>  nl2br($row["mf_backcontent"]),
        "color_mf_boxstep"  =>  $color_mf_boxstep_array[$row["mf_boxstep"]],  
        "mf_boxstep"        =>  array_search($row["mf_boxstep"], $db_mf_boxstep_array),
        "mf_studytime"      =>  get_datetime_format($row["mf_studytime"])
    ));
    tp_parse($template);
}

tp_print();

?>