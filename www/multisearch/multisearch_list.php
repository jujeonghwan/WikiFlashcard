<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/nav.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/side.php");

tp_read();

// 경로
tp_set("menu_navigator", get_menu_navigator());


// Multi Search Type 탭
$search_ms_type = trim($_REQUEST["search_ms_type"]);
if ($search_ms_type == "") {
    $search_ms_type = 1;
}
tp_set("search_ms_type", $search_ms_type);

// 검색어
$search_keyword = trim($_REQUEST["search_keyword"]);
tp_set("search_keyword", $search_keyword);


// Multi Search Type
$template = "row_tab";
tp_dynamic($template);

$ms_type = 0;

foreach ($MULTISEARCH_TYPE_ARRAY as $key => $val) {
    $ms_type++;
     
    $tab_name = $key;
    
    // 탭 현재 여부
    if ($search_ms_type == $ms_type) {
        $class_tab_active = "active";

        $multisearch_type_val = $val;   // 현재 탭
    }
    else {
        $class_tab_active = "";
    }

    // 탭 링크
    $tab_link = "multisearch_list.php?dummy=dummy";
    $tab_link .= "&search_ms_type=" . $ms_type;
    $tab_link .= "&search_keyword=" . urlencode($search_keyword);


    tp_set($template, array(
        "class_tab_active"  =>  $class_tab_active,
        "tab_link"          =>  $tab_link,
        "tab_name"          =>  $tab_name
    ));
    tp_parse($template);
}


// Search URL
if (trim($search_keyword) != "") {
    $multisearch_url = str_replace("{query}", urlencode(trim($search_keyword)), $multisearch_type_val);
}
else {
    $multisearch_url = "about:blank";
}

tp_set("multisearch_url", $multisearch_url);


tp_print();

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

?>