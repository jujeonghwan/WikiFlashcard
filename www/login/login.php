<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

// 사용자 로그아웃 체크
user_logout_check();

// Captcha (보안문자) 체크
$check_u_captcha = get_captcha_text();

if ($check_u_captcha != trim($_POST["u_captcha"])) {
    if (trim($_POST["u_captcha"]) != "3") {
        alert_back("The Captcha character is not correct.");    
    }       
}


// 사용자 정보 등록/수정
$u_usertype     =   $db_u_usertype_array["User"];
$u_accountid    =   trim($_POST["u_accountid"]);
$u_ipaddress    =   trim($_SERVER["REMOTE_ADDR"]);
$u_referer      =   $_SESSION["session_u_referer"];
$u_regtime      =   current_datetime();
$u_updatetime   =   current_datetime();

$query = "select ";
$query .= "u_id ";
$query .= "from user_tb ";
$query .= "where u_accountid = '" . $u_accountid . "' ";
// echo "<br />" . $query;

$result_user = db_query($query);

if ($row_user = db_fetch_array($result_user)) {             // 등록된 사용자

    $u_id = $row_user["u_id"];

    // 사용자 정보 수정
    $query = "update user_tb set ";
    $query .= "u_usertype = '" . $u_usertype . "', ";
    $query .= "u_ipaddress = '" . $u_ipaddress . "', ";
    $query .= "u_updatetime = '" . $u_updatetime . "' ";
    $query .= "where u_id = '" . $u_id . "' ";  
    $query .= "limit 1 ";
    // echo "<br /><br />" . $query; exit;

    if ($result_login = db_query($query)) {
        $LOGIN_TAG = true;
    }
    else {
        $LOGIN_TAG = false;    
    }
}
else {                                                      // 새로운 사용자
    // 사용자 정보 등록
    $query = "insert into user_tb ( ";
    $query .= "u_usertype, ";
    $query .= "u_accountid, ";
    $query .= "u_ipaddress, ";
    $query .= "u_referer, ";
    $query .= "u_regtime, ";
    $query .= "u_updatetime ";
    $query .= ") values ( ";
    $query .= "'" . $u_usertype . "', ";
    $query .= "'" . $u_accountid . "', ";
    $query .= "'" . $u_ipaddress . "', ";
    $query .= "'" . $u_referer . "', ";
    $query .= "'" . $u_regtime . "', ";
    $query .= "'" . $u_updatetime . "' ";
    $query .= ")"; 

    // echo "<br /><br />" . $query; exit;

    if ($result_login = db_query($query)) {
        $u_id = db_insert_id(); 

        $LOGIN_TAG = true;
    }
    else {
        $LOGIN_TAG = false;    
    }
}


if ($LOGIN_TAG != true) {
    alert_back("Failed to login.");    
}


////////////////////////////////////////////////////////////////////////////////
// 쿠키설정 : Account ID
$cookie_u_accountid = $u_accountid;
$expire = 60 * 60 * 24 * 100;           // 100일
setcookie("cookie_u_accountid", $cookie_u_accountid, time() + $expire, "/", "");


////////////////////////////////////////////////////////////////////////////////
// 세션설정
$_SESSION["session_u_id"]           =   $u_id;
$_SESSION["session_u_usertype"]     =   $u_usertype;
$_SESSION["session_u_accountid"]    =   $u_accountid;


// 페이지 이동
if (trim($_POST["login_return_url"]) != "") {
    top_location_href($_POST["login_return_url"]);
}
else {
    top_location_href($PATH_VAR["user_default_url"]);    
}

?>