<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$cs_id              =   trim($_POST["cs_id"]);
$cs_setname         =   trim($_POST["cs_setname"]);
$cs_frontitemname   =   trim($_POST["cs_frontitemname"]);
$cs_backitemname    =   trim($_POST["cs_backitemname"]);
// $cs_reguser      =   $_SESSION["session_u_id"];
// $cs_regtime      =   current_datetime();
$cs_updateuser      =   $_SESSION["session_u_id"];
$cs_updatetime      =   current_datetime();

// 수정
$query = "update cardset_tb set ";
$query .= "cs_setname = '" . addslashes($cs_setname) . "', ";
$query .= "cs_frontitemname = '" . addslashes($cs_frontitemname) . "', ";
$query .= "cs_backitemname = '" . addslashes($cs_backitemname) . "', ";
// $query .= "cs_reguser = '" . $cs_reguser . "', ";
// $query .= "cs_regtime = '" . $cs_regtime . "', ";
$query .= "cs_updateuser = '" . $cs_updateuser . "', ";
$query .= "cs_updatetime = '" . $cs_updatetime . "' ";
$query .= "where cs_id = '" . $cs_id . "' ";
$query .= "limit 1 ";
// echo "cs_updateuser : " . $cs_updateuser; exit;
if ($result = db_query($query)) {    
    alert("Edited.");
}
else {
    alert_back("Failed to Edit."); 
}

// 페이지 이동
// $location_href = "cardset_list.php?dummy=dummy";
$location_href = "/index.php?dummy=dummy";
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>