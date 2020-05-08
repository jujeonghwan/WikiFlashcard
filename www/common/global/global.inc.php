<?php

/*
echo "Server operation in progress.";
exit;
*/

// Session start
session_start();

// 쿠키허용 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"'); 

// DB
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/db.mariadb.inc.php");

// Constant
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/constant.inc.php");

// Function
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/function.inc.php");

// Site Function
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/site.inc.php");

// Template
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/template.inc.php");
/*
// Advertisement
require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/ad.inc.php");
*/

////////////////////////////////////////////////////////////////////////////////
// Session set

// 링크주소 (Referer)
if ($_SESSION["session_u_referer"] == "") {
    foreach (getallheaders() as $header_name => $header_value) {
        // Referer : http://www.wst.kr/

        if ($header_name == "Referer") {
            $referer_value = trim($header_value);

            $_SESSION["session_u_referer"] = $referer_value;
            if (    ($referer_value != "") && 
                    (strpos($referer_value, $SITE_VAR["host"]) === false)   ) { // === 3개인것 중요함    
                // echo "<br />AAA referer : " . $referer_value;   
            }
            else {
                // echo "<br />BBB referer : " . $referer_value;   
                // Session set
                $_SESSION["session_u_referer"] = $referer_value;
            }
        }
    }

    // echo "<br />SITE_VAR host : " . $SITE_VAR["host"];
    // echo "<br />referer : " . $referer_value;
}
// echo "<br />session_u_referer : " . $_SESSION["session_u_referer"];


// Guest (손님) 계정아이디(이메일) (Account ID)
if ($_SESSION["session_u_accountid"] == "") {

    if ($_COOKIE["cookie_guest_accountid"] != "") {
        $session_u_accountid = $_COOKIE["cookie_guest_accountid"];
    }
    else {
        // $session_u_accountid = "guest_" . current_datetime();
        $session_u_accountid = "guest_" . current_date();
    }

    // 사용자 정보 등록/수정
    $u_usertype     =   $db_u_usertype_array["Guest"];      // 손님
    $u_accountid    =   trim($session_u_accountid);
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


    // 세션설정
    $_SESSION["session_u_id"]           =   $u_id;
    $_SESSION["session_u_usertype"]     =   $u_usertype;
    $_SESSION["session_u_accountid"]    =   $u_accountid;


    // 쿠키설정 : Account ID
    $cookie_guest_accountid = $_SESSION["session_u_accountid"];
    $expire = 60 * 60 * 24 * 100;       // 100일
    setcookie("cookie_guest_accountid", $cookie_guest_accountid, time() + $expire, "/", "");

}

// 세션설정
////////////////////////////////////////////////////////////////////////////////


// 도메인체크 해서 메인도메인으로 이동
if ($_SERVER["HTTP_HOST"] != $SITE_VAR["domain"]) {
    // 예)
    // 이동전 : http://wikiflashcard.kr/?dummy=dummy
    // 이동후 : http://www.wikiflashcard.kr/?dummy=dummy
    // $_SERVER["REQUEST_URI"] : /?dummy=dummy
    
    $location_href = "http://" . $SITE_VAR["domain"] . $_SERVER["REQUEST_URI"];
    // echo $location_href;    
    location_href($location_href);
}

?>
