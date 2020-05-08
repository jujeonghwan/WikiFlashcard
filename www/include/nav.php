<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

tp_read($_SERVER["DOCUMENT_ROOT"] . "/include/nav.html");

/* 드롭다운 메뉴 숨김 (메뉴가 많아지면 그때 적용)
// 드롭다운 메뉴 형태 표시
$template = "row_dropdown_nav";
tp_dynamic($template);

$nav_no = 0;

foreach ($MENU_ARRAY as $key => $val) {   

    $nav_no++;

    $nav_menu_name_1    =   $key;       // 대메뉴명
    $MENU_2_ARRAY       =   $val;       // 중메뉴 배열

    // 초기화
    $class_nav_active = "";
    $span_nav_current = "";


    // 중메뉴 부분
    $template_sub = "row_dropdown_nav_sub";
    tp_dynamic($template_sub, $template);

    foreach ($MENU_2_ARRAY as $key2 => $val2) {  
        // 초기화
        $nav_menu_link_3 = "";

        $nav_menu_name_2    =   $key2;  // 중메뉴명
        $MENU_3_ARRAY       =   $val2;  // 소메뉴 배열

        if ($nav_menu_link_3 == "") {
            $nav_menu_link_3    =   current($MENU_3_ARRAY); // 첫번째배열 항목값    
        }        

        if (in_array($_SERVER["PHP_SELF"], $MENU_3_ARRAY)) {
            $class_nav_active = "active";
            $span_nav_current = "<span class=\"sr-only\">(current)</span>";
        }

        tp_set($template_sub, array(
            "nav_menu_link_3"   =>  $nav_menu_link_3,
            "nav_menu_name_2"   =>  $nav_menu_name_2
        ));
        tp_parse($template_sub);
    } 

    
    tp_set($template, array(
        "class_nav_active"  =>  $class_nav_active,
        "nav_no"            =>  $nav_no,
        "nav_menu_name_1"   =>  $nav_menu_name_1,
        "span_nav_current"  =>  $span_nav_current
    ));
    tp_parse($template);
}   
드롭다운 메뉴 숨김 (메뉴가 많아지면 그때 적용) */


// 중(Step2) 메뉴 형태 표시
// 현재 URL 에 해당하는 중메뉴를 구함
$current_menu_1_name = "";
$current_menu_2_name = "";
$current_menu_3_name = "";
$current_menu_3_url  = "";

// 중메뉴 배열을 구함
foreach ($MENU_ARRAY as $key => $val) {
    $menu_name_1    =   $key;           // 대메뉴명
    $MENU_2_ARRAY   =   $val;           // 중메뉴 배열

    foreach ($MENU_2_ARRAY as $key2 => $val2) { 
        $menu_name_2    =   $key2;      // 중메뉴명
        $MENU_3_ARRAY   =   $val2;      // 소메뉴 배열

        foreach ($MENU_3_ARRAY as $key3 => $val3) { 
            $menu_name_3    =   $key3;  // 소메뉴명
            $menu_url_3     =   $val3;  // 소메뉴URL

            // 현재URL의 중/소메뉴명, 소메뉴URL
            if ($_SERVER["PHP_SELF"] == $menu_url_3) {
                $current_menu_1_name = $menu_name_1;
                $current_menu_2_name = $menu_name_2;
                $current_menu_3_name = $menu_name_3;
                $current_menu_3_url  = $menu_url_3;
            }
        }
    }
}

$template = "row_step2_nav";
tp_dynamic($template);

// 현재 중메뉴 배열
$CURRENT_MENU_2_ARRAY = $MENU_ARRAY[$current_menu_1_name];

foreach ($CURRENT_MENU_2_ARRAY as $key2 => $val2) {

    $nav_menu_name_2    =   $key2;      // 중메뉴명
    $MENU_3_ARRAY       =   $val2;      // 소메뉴 배열
    $nav_menu_link_3   =   current($MENU_3_ARRAY);         // 첫번째배열 항목값

    // 현재 메뉴 여부
    if ($current_menu_2_name == $nav_menu_name_2) {
        $class_nav_active = "active";
        $span_nav_current = "<span class=\"sr-only\">(current)</span>";
    }
    else {
        $class_nav_active = "";
        $span_nav_current = "";
    }

    tp_set($template, array(
        "class_nav_active"  =>  $class_nav_active,
        "nav_menu_link_3"   =>  $nav_menu_link_3,
        "nav_menu_name_2"   =>  $nav_menu_name_2,
        "span_nav_current"  =>  $span_nav_current
    ));
    tp_parse($template);
}


// 사용자 로그인 상태
if (user_login_status()) {
    $session_u_accountid    =   $_SESSION["session_u_accountid"];
    $user_login_logout_url  =   $PATH_VAR["user_logout_url"];
    $user_login_logout_text =   "Logout";
}
else {
    $session_u_accountid    =   "Guest";
    $user_login_logout_url  =   $PATH_VAR["user_login_url"] . "?login_return_url=" . urlencode($_SERVER["REQUEST_URI"]);
    $user_login_logout_text =   "Login";
}

tp_set("session_u_accountid", $session_u_accountid);
tp_set("user_login_logout_url", $user_login_logout_url);
tp_set("user_login_logout_text", $user_login_logout_text);

tp_print();

?>
