<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$fc_cardset         =   trim($_POST["fc_cardset"]);
$fc_frontcontent    =   trim($_POST["fc_frontcontent"]);
$fc_backcontent     =   trim($_POST["fc_backcontent"]);
$fc_order           =   0;

$fc_reguser         =   $_SESSION["session_u_id"];
$fc_regtime         =   current_datetime();
$fc_updateuser      =   $_SESSION["session_u_id"];
$fc_updatetime      =   current_datetime();

// 등록
$query = "insert into flashcard_tb ( ";
$query .= "fc_cardset, ";
$query .= "fc_frontcontent, ";
$query .= "fc_backcontent, ";
$query .= "fc_order, ";
$query .= "fc_reguser, ";
$query .= "fc_regtime, ";
$query .= "fc_updateuser, ";
$query .= "fc_updatetime ";
$query .= ") values ( ";
$query .= "'" . $fc_cardset . "', ";
$query .= "'" . addslashes($fc_frontcontent) . "', ";
$query .= "'" . addslashes($fc_backcontent) . "', ";
$query .= "'" . $fc_order . "', ";
$query .= "'" . $fc_reguser . "', ";
$query .= "'" . $fc_regtime . "', ";
$query .= "'" . $fc_updateuser . "', ";
$query .= "'" . $fc_updatetime . "' ";
$query .= ")";   

if ($result = db_query($query)) { 
    $fc_id = db_insert_id();
    $fc_order = $fc_id;

    // 순서 (Order) 설정
    $query = "update flashcard_tb set ";        
    $query .= "fc_order = '" . $fc_order . "' ";
    $query .= "where fc_id = '" . $fc_id . "' ";
    $query .= "limit 1 ";

    db_query($query);

    sleep(1);

    // 플래시카드 내플래시카드 등록
    /*
    if ($_SESSION["session_u_id"] == $USER_VAR["choiyoungsil_user_id"]) {
        // 예외로 통과
    }
    else {
        set_flashcard_myflashcard_add($fc_id);
    }
    */
    

    alert("Registered.");
}
else {
    alert_back("Failed to register."); 
}

// 페이지 이동
if ($_REQUEST["add_tag"] == "Y") {      // 등록후 계속등록 여부
    $location_href = "flashcard_add.php?dummy=dummy";
    $location_href .= "&search_fc_cardset=" . $_POST["fc_cardset"];
}
else {
    $location_href = "flashcard_list.php?dummy=dummy";
    $location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
}
// $location_href = "flashcard_search.php?dummy=dummy";
// $location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>