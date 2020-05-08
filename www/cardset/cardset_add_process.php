<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그인 체크
user_login_check();

html_meta_charset_utf8();

$cs_setname         =   trim($_POST["cs_setname"]);
$cs_frontitemname   =   trim($_POST["cs_frontitemname"]);
$cs_backitemname    =   trim($_POST["cs_backitemname"]);
$cs_reguser         =   $_SESSION["session_u_id"];
$cs_regtime         =   current_datetime();
$cs_updateuser      =   $_SESSION["session_u_id"];
$cs_updatetime      =   current_datetime();

// 등록
$query = "insert into cardset_tb ( ";
$query .= "cs_setname, ";
$query .= "cs_frontitemname, ";
$query .= "cs_backitemname, ";
$query .= "cs_reguser, ";
$query .= "cs_regtime, ";
$query .= "cs_updateuser, ";
$query .= "cs_updatetime ";
$query .= ") values ( ";
$query .= "'" . addslashes($cs_setname) . "', ";
$query .= "'" . addslashes($cs_frontitemname) . "', ";
$query .= "'" . addslashes($cs_backitemname) . "', ";
$query .= "'" . $cs_reguser . "', ";
$query .= "'" . $cs_regtime . "', ";
$query .= "'" . $cs_updateuser . "', ";
$query .= "'" . $cs_updatetime . "' ";
$query .= ")";   

if ($result = db_query($query)) {    
    alert("Registered.");
}
else {
    alert_back("Failed to register."); 
}

// 페이지 이동
// $location_href = "cardset_list.php?dummy=dummy";
$location_href = "/index.php?dummy=dummy";
$location_href .= "&search_keyword=" . urlencode($_REQUEST["search_keyword"]);
$location_href .= "&page=" . $_REQUEST["page"];
location_href($location_href);

?>