<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$fc_id              =   trim($_POST["fc_id"]);
// $fc_cardset      =   trim($_POST["fc_cardset"]);
$fc_frontcontent    =   trim($_POST["fc_frontcontent"]);
$fc_backcontent     =   trim($_POST["fc_backcontent"]);
// $fc_order        =   0;

// $fc_reguser      =   $_SESSION["session_u_id"];
// $fc_regtime      =   current_datetime();
$fc_updateuser      =   $_SESSION["session_u_id"];
$fc_updatetime      =   current_datetime();

// 수정
$query = "update flashcard_tb set ";
// $query .= "fc_cardset = '" . $fc_cardset . "', ";
$query .= "fc_frontcontent = '" . addslashes($fc_frontcontent) . "', ";
$query .= "fc_backcontent = '" . addslashes($fc_backcontent) . "', ";
// $query .= "fc_order = '" . $fc_order . "', ";
// $query .= "fc_reguser = '" . $fc_reguser . "', ";
// $query .= "fc_regtime = '" . $fc_regtime . "', ";
$query .= "fc_updateuser = '" . $fc_updateuser . "', ";
$query .= "fc_updatetime = '" . $fc_updatetime . "' ";
$query .= "where fc_id = '" . $fc_id . "' ";
$query .= "limit 1 ";

if ($result = db_query($query)) {  
    
    // 플래시카드수정시 내플래시카드 수정
    $flashcard_myflashcard_update = set_flashcard_myflashcard_update($fc_id);

    alert("Edited.");
}
else {
    alert_back("Failed to Edit.");  
}

// 페이지 이동
$location_href = "flashcard_list.php?dummy=dummy";
$location_href .= "&search_fc_cardset=" . $_REQUEST["search_fc_cardset"];
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>