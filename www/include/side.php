<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

tp_read($_SERVER["DOCUMENT_ROOT"] . "/include/side.html");

// 현재 URL 에 해당하는 중메뉴를 구함
$current_menu_1_name = "";
$current_menu_2_name = "";
$current_menu_3_name = "";
$current_menu_3_url  = "";

foreach ($MENU_ARRAY as $key => $val) {
    $menu_name_1    =   $key;           // 대메뉴명
    $MENU_2_ARRAY   =   $val;           // 중메뉴 배열

    foreach ($MENU_2_ARRAY as $key2 => $val2) { 
        $menu_name_2    =   $key2;      // 중메뉴명
        $MENU_3_ARRAY   =   $val2;      // 소메뉴 배열

        foreach ($MENU_3_ARRAY as $key3 => $val3) { 
            $menu_name_3    =   $key3;  // 소메뉴명
            $menu_url_3     =   $val3;  // 소메뉴URL

            // 현재URL의 대/중/소메뉴명, 소메뉴URL
            if ($_SERVER["PHP_SELF"] == $menu_url_3) {
                $current_menu_1_name = $menu_name_1;
                $current_menu_2_name = $menu_name_2;
                $current_menu_3_name = $menu_name_3;
                $current_menu_3_url  = $menu_url_3;
            }
        }
    }
}

/*
echo "<br />PHP_SELF : " . $_SERVER["PHP_SELF"];
echo "<br />current_menu_1_name : " . $current_menu_1_name;
echo "<br />current_menu_2_name : " . $current_menu_2_name;
echo "<br />current_menu_3_name : " . $current_menu_3_name;
echo "<br />current_menu_3_url : " . $current_menu_3_url;
*/

$template = "row_side";
tp_dynamic($template);

// 현재 중메뉴 배열
$CURRENT_MENU_2_ARRAY = $MENU_ARRAY[$current_menu_1_name];

foreach ($CURRENT_MENU_2_ARRAY as $key2 => $val2) {

    $side_menu_name_2   =   $key2;      // 중메뉴명
    $MENU_3_ARRAY       =   $val2;      // 소메뉴 배열
    $side_menu_link_3   =   current($MENU_3_ARRAY);         // 첫번째배열 항목값

    // 현재 메뉴 여부
    if ($current_menu_2_name == $side_menu_name_2) {
        $class_nav_active = "active";
        $span_nav_current = "<span class=\"sr-only\">(current)</span>";
    }
    else {
        $class_nav_active = "";
        $span_nav_current = "";
    }

    tp_set($template, array(
        "class_nav_active"  =>  $class_nav_active,
        "side_menu_link_3"  =>  $side_menu_link_3,
        "side_menu_name_2"  =>  $side_menu_name_2,
        "span_nav_current"  =>  $span_nav_current
    ));
    tp_parse($template);

}

tp_print();

?>