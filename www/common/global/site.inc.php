<?php

////////////////////////////////////////////////////////////////////////////////
// 사용자

// 사용자 로그인 상태
function user_login_status() {
    global $_SESSION;
    global $db_u_usertype_array;

    if (isset($_SESSION["session_u_id"]) && $_SESSION["session_u_id"] != "") {
        if ($_SESSION["session_u_usertype"] == $db_u_usertype_array["User"]) {
            return true;
        }
    }

    return;
}

// 사용자 로그인 체크
function user_login_check() {
    global $_SESSION;    
    global $_SERVER;
    global $PATH_VAR;
    
    if (!user_login_status()) {
        top_location_href($PATH_VAR["user_login_url"] . "?login_return_url=" . urlencode($_SERVER["REQUEST_URI"]));
        exit;
    }
}

// 사용자 로그아웃 체크
function user_logout_check() {
    global $_SESSION;
    global $PATH_VAR;
    
    if (user_login_status()) {
        top_location_href($PATH_VAR["user_default_url"]);
        exit;
    }
}

// 보안문자를 구함 (로그인시 보안문자로 사용)
function get_captcha_text() {
    global $_SERVER;
    
    $captcha_text = "";

    $temp_text = $_SERVER["REMOTE_ADDR"];                   // 예) 175.208.158.237 
    $temp_text_array = explode(".", $temp_text);

    $captcha_text = $temp_text_array[2] . $temp_text_array[3];

    return $captcha_text;
}



////////////////////////////////////////////////////////////////////////////////
// 메뉴

// 메뉴 타이틀
function get_menu_navigator() {    
    global $_SERVER;
    global $SITE_VAR;
    global $MENU_ARRAY;
    
    $ret_text = "";
    
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

    // $ret_text .= $SITE_VAR["home_name"] . " &gt; ";
    // $ret_text .= $current_menu_1_name . " &gt; ";
    $ret_text .= "<strong>" . $current_menu_2_name . "</strong> &gt; ";
    $ret_text .= $current_menu_3_name;
    
    return $ret_text;
}


// 사이트 타이틀
function get_site_title() {    
    global $_SERVER;
    global $_REQUEST;
    global $SITE_VAR;
    global $MENU_ARRAY;
    
    $site_title = "";
    
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

    // $site_title .= $SITE_VAR["home_name"] . " &gt; ";
    // $site_title .= $current_menu_1_name . " &gt; ";
    // $site_title .= "<strong>" . $current_menu_2_name . "</strong> &gt; ";
    // $site_title .= $current_menu_3_name;

    $site_title .= $SITE_VAR["title"];

    if ($current_menu_3_name != "") {
        $site_title .= " | " . $current_menu_3_name;   
    }


    $query = "";                        // 쿼리 초기화

    switch ($current_menu_3_url) {     

        ////////////////////////////////////////////////////////////////////////////////
        // 카드세트 (Cardset)
        case "/index.php":                                  // 위키플래시카드 메인 (WikiFlashcard Main)
        case "/cardset/cardset_add.php":                    // 카드세트 등록 (Add Cardset)
            break;

        case "/cardset/cardset_view.php":                   // 카드세트 조회 (View Cardset)
        case "/cardset/cardset_edit.php":                   // 카드세트 수정 (Edit Cardset)
        case "/study/flashcard_study.php":                  // 카드세트 학습 (Study Cardset)
            $query = "select ";
            $query .= "cs_setname as title_text ";
            $query .= "from cardset_tb ";
            $query .= "where cs_id = '" . $_REQUEST["cs_id"] . "' ";
            break;

        ////////////////////////////////////////////////////////////////////////////////
        // 플래시카드 (Flashcard)
        case "/flashcard/flashcard_list.php":               // 플래시카드 목록 (Flashcard List)
        case "/flashcard/flashcard_add.php":                // 플래시카드 등록 (Add Flashcard)
            $query = "select ";
            $query .= "cs_setname as title_text ";
            $query .= "from cardset_tb ";
            $query .= "where cs_id = '" . $_REQUEST["search_fc_cardset"] . "' ";
            break;

        case "/flashcard/flashcard_view.php":               // 플래시카드 조회 (View Flashcard)
        case "/flashcard/flashcard_edit.php":               // 플래시카드 수정 (Edit Flashcard)
            $query = "select ";
            $query .= "concat(cs_setname, ' ', fc_frontcontent, ' ', fc_backcontent) as title_text ";
            $query .= "from flashcard_tb ";

            $query .= "inner join cardset_tb ";
            $query .= "on fc_cardset = cs_id ";

            $query .= "where fc_id = '" . $_REQUEST["fc_id"] . "' ";
            break;

        ////////////////////////////////////////////////////////////////////////////////
        // 내 플래시카드 (My FlashCard)
        case "/myflashcard/myflashcard_list.php":           // 내플래시카드 목록 (My Flashcard List)
        case "/myflashcard/myflashcard_study.php":          // 내플래시카드 학습 (Study My Flashcard)
            $query = "select ";
            $query .= "cs_setname as title_text ";
            $query .= "from cardset_tb ";
            $query .= "where cs_id = '" . $_REQUEST["search_mf_cardset"] . "' ";
            break;

        ////////////////////////////////////////////////////////////////////////////////
        // 내 퀴즈박스 (My QuizBox)
        case "/quizbox/quizbox_myflashcard_list.php":       // 퀴즈박스 플래시카드 목록 (QuizBox Flashcard List)
        case "/quizbox/quizbox_myflashcard_quiz.php":       // 퀴즈박스 플래시카드 시험 (QuizBox Flashcard Quiz)
            $query = "select ";
            $query .= "cs_setname as title_text ";
            $query .= "from cardset_tb ";
            $query .= "where cs_id = '" . $_REQUEST["search_mf_cardset"] . "' ";
            break;

        case "/quizbox/quizbox_myflashcard_quiz_check.php": // 퀴즈박스 플래시카드 시험확인 (QuizBox Flashcard Quiz Check)
            $query = "select ";
            $query .= "concat(cs_setname, ' ', mf_frontcontent, ' ', mf_backcontent) as title_text ";
            $query .= "from myflashcard_tb ";

            $query .= "inner join cardset_tb ";
            $query .= "on mf_cardset = cs_id ";

            $query .= "where mf_id = '" . $_REQUEST["mf_id"] . "' ";
            break;

        default:
            # code...
            break;
    }

    // 쿼리 있을 경우
    if ($query != "") {        
        $result_title = db_query($query);

        if ($row_title = db_fetch_array($result_title)) {
            $site_title .= " | " . $row_title["title_text"]; 
        }
    }
        
    return $site_title;
}


////////////////////////////////////////////////////////////////////////////////
// 검색 링크

// 검색링크 버튼 목록 
function get_searchlink_button_list($string) {    
    global $SEARCHLINK_URL_ARRAY;
    
    $searchlink_button_list = "";
    
    $searchlink_button_list .= "
        <div class=\"btn-group\" role=\"group\">
            <button id=\"btnGroupDrop1\" type=\"button\" class=\"btn btn-secondary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">--View Web Page--</button>
            <div class=\"dropdown-menu\" aria-labelledby=\"btnGroupDrop1\">
    ";

    foreach ($SEARCHLINK_URL_ARRAY as $key => $val) {
        $searchlink_name = $key;

        $searchlink_link = $val;
        $searchlink_link = str_replace("{query}", urlencode($string), $searchlink_link);   

        $searchlink_button_list .= "
            <a class=\"dropdown-item\" href=\"" . $searchlink_link . "\" target=\"_blank\">" . $searchlink_name . "</a>
        ";
    }

    $searchlink_button_list .= "
            </div>
        </div>
    ";
    
    return $searchlink_button_list;
}


////////////////////////////////////////////////////////////////////////////////
// 플래시카드 (Flashcard)

// 첫번째 순서의 플래시카드를 구함
function get_first_flashcard($cs_id = "") {   

    $first_flashcard = "";              // 초기화
    
    // 순서(order)대로 1개를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    $query .= "order by fc_order, fc_id ";
    $query .= "limit 1 ";

    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
        $first_flashcard = $row["fc_id"];
    }
    else {
        $first_flashcard = ""; 
    }
    
    return $first_flashcard;
}

// 첫번째 순서의 임의의 플래시카드를 구함
function get_first_random_flashcard($cs_id = "") {   

    $first_flashcard = "";              // 초기화
    
    // 순서(order)대로 1개를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    // $query .= "order by fc_order, fc_id ";
    $query .= "order by rand() ";                           // 임의의 플래시카드
    $query .= "limit 1 ";

    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
        $first_flashcard = $row["fc_id"];
    }
    else {
        $first_flashcard = ""; 
    }
    
    return $first_flashcard;
}

// 이전 순서의 플래시카드를 구함
function get_prev_flashcard($fc_id, $cs_id = "") {    

    $prev_flashcard = "";               // 초기화
    
    // 순서(order)를 구함
    $query = "select ";
    $query .= "fc_order ";
    $query .= "from flashcard_tb ";
    $query .= "where fc_id = '" . $fc_id . "' ";

    $result_order = db_query($query);

    if ($row_order = db_fetch_array($result_order)) {
        $fc_order = $row_order["fc_order"];
    }

    // 순서(order)대로 1개를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    $query .= "and fc_id != '" . $fc_id . "' ";
    $query .= "and fc_order <= '" . $fc_order . "' ";       // 이전 순서
    $query .= "order by fc_order desc, fc_id desc ";
    $query .= "limit 1 ";

    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
        $prev_flashcard = $row["fc_id"];
    }
    else {
        $prev_flashcard = ""; 
    }
    
    return $prev_flashcard;
}

// 다음 순서의 플래시카드를 구함
function get_next_flashcard($fc_id, $cs_id = "") {    

    $next_flashcard = "";               // 초기화
    
    // 순서(order)를 구함
    $query = "select ";
    $query .= "fc_order ";
    $query .= "from flashcard_tb ";
    $query .= "where fc_id = '" . $fc_id . "' ";

    $result_order = db_query($query);

    if ($row_order = db_fetch_array($result_order)) {
        $fc_order = $row_order["fc_order"];
    }

    // 순서(order)대로 1개를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    $query .= "and fc_id != '" . $fc_id . "' ";
    $query .= "and fc_order >= '" . $fc_order . "' ";       // 다음 순서
    $query .= "order by fc_order, fc_id ";
    $query .= "limit 1 ";

    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
        $next_flashcard = $row["fc_id"];
    }
    else {
        $next_flashcard = ""; 
    }
    
    return $next_flashcard;
}


// 다음 순서의 임의의 플래시카드를 구함
function get_next_random_flashcard($fc_id, $cs_id = "") {    

    $next_flashcard = "";               // 초기화
    
    // 순서(order)를 구함
    $query = "select ";
    $query .= "fc_order ";
    $query .= "from flashcard_tb ";
    $query .= "where fc_id = '" . $fc_id . "' ";

    $result_order = db_query($query);

    if ($row_order = db_fetch_array($result_order)) {
        $fc_order = $row_order["fc_order"];
    }

    // 순서(order)대로 1개를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    $query .= "and fc_id != '" . $fc_id . "' ";
    /*
    $query .= "and fc_order >= '" . $fc_order . "' ";       // 다음 순서
    $query .= "order by fc_order, fc_id ";
    */
    $query .= "order by rand() ";                           // 임의의 순서
    $query .= "limit 1 ";
    
    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
        $next_flashcard = $row["fc_id"];
    }
    else {
        $next_flashcard = ""; 
    }
    
    return $next_flashcard;
}


// 플래시카드의 현재위치 및 전체갯수(위치)를 구함
function get_flashcard_current_pos($fc_id, $cs_id = "") {    

    $flashcard_current_pos_array = array();                 // 초기화
    $flashcard_current_pos_array["current"] = 0;
    $flashcard_current_pos_array["total"] = 0;
    
    // 순서(order)의 앞쪽 항목수를 구함
    $query = "select ";
    $query .= "fc_id ";
    $query .= "from flashcard_tb ";
    $query .= "where 1 = 1 ";
    if ($cs_id != "") {
        $query .= "and fc_cardset = '" . $cs_id . "' ";
    }
    $query .= "order by fc_order, fc_id ";

    $result = db_query($query);
    $flashcard_current_pos_array["total"] = db_num_rows($result);               // 전체위치(갯수)

    $current_count = 0;
    while ($row = db_fetch_array($result)) {
        $current_count++;

        if ($fc_id == $row["fc_id"]) {
            $flashcard_current_pos_array["current"] = $current_count;           // 현재위치
            break;
        }        
    }
    
    // 예) 3 / 50
    $flashcard_current_pos = number_format($flashcard_current_pos_array["current"]) . " / " . number_format($flashcard_current_pos_array["total"]);

    return $flashcard_current_pos;
}

// 플래시카드의 갯수를 구함
function get_flashcard_count($cs_id) {    

    $flashcard_count = 0;               // 초기화
    
    // 순서(order)의 앞쪽 항목수를 구함
    $query = "select ";
    $query .= "count(*) as total_count ";
    $query .= "from flashcard_tb ";
    $query .= "where fc_cardset = '" . $cs_id . "' ";

    $result_count = db_query($query);
    
    if ($row_count = db_fetch_array($result_count)) {
        $flashcard_count = $row_count["total_count"]; 
    }

    return $flashcard_count;
}


////////////////////////////////////////////////////////////////////////////////
// 내플래시카드 (My Flashcard)

// 내플래시카드의 갯수를 구함
function get_myflashcard_count($mf_boxstep = "", $mf_cardset = "", $keyword = "") {    
    global $_SESSION;
    
    $myflashcard_count = 0;             // 초기화
    
    // 항목수를 구함
    $query = "select ";
    $query .= "count(*) as total_count ";
    $query .= "from myflashcard_tb ";
    $query .= "where mf_user = '" . $_SESSION["session_u_id"] . "' ";
    if ($mf_boxstep != "") {
        $query .= "and mf_boxstep = '" . $mf_boxstep . "' ";
    }
    if ($mf_cardset != "") {
        $query .= "and mf_cardset = '" . $mf_cardset . "' ";
    }
    if ($keyword != "") {
        $query .= "and (mf_frontcontent like '%" . $keyword . "%' ";            // 앞면항목내용 (Front Content)
        $query .= "or mf_backcontent like '%" . $keyword . "%') ";              // 뒷면항목내용 (Back Content)
    }

    $result_count = db_query($query);
    
    if ($row_count = db_fetch_array($result_count)) {
        $myflashcard_count = $row_count["total_count"]; 
    }

    return $myflashcard_count;
}

// 플래시카드 내플래시카드 등록
function set_flashcard_myflashcard_add($fc_id) {  
    global $_SESSION;
    global $db_mf_boxstep_array;

    $flashcard_myflashcard_add = "";    // 초기화
    
    // 플래시카드 데이터를 읽음
    $query = "select ";
    $query .= "fc_id, ";
    $query .= "fc_cardset, ";
    $query .= "fc_frontcontent, ";
    $query .= "fc_backcontent, ";
    $query .= "fc_order, ";
    $query .= "fc_reguser, ";
    $query .= "fc_regtime, ";
    $query .= "fc_updateuser, ";
    $query .= "fc_updatetime ";
    $query .= "from flashcard_tb ";     // 플래시카드 (Flashcard)
    $query .= "where fc_id = '" . $fc_id . "' ";

    $result_flashcard = db_query($query);

    if (!$row_flashcard = db_fetch_array($result_flashcard)) {
        $flashcard_myflashcard_add = false;

        return $flashcard_myflashcard_add;
        exit;
    }


    // 내플래시카드 (My Flashcard)
    $mf_user            =   $_SESSION["session_u_id"];
    $mf_boxstep         =   $db_mf_boxstep_array["Step1"];
    $mf_cardset         =   $row_flashcard["fc_cardset"];
    $mf_flashcard       =   $row_flashcard["fc_id"];
    $mf_frontcontent    =   $row_flashcard["fc_frontcontent"];
    $mf_backcontent     =   $row_flashcard["fc_backcontent"];
    $mf_regtime         =   current_datetime();
    $mf_studytime       =   "";
    $mf_testtime        =   "";

    // 중복체크
    $query = "select ";
    $query .= "mf_id ";
    $query .= "from myflashcard_tb ";
    $query .= "where mf_user = '" . $mf_user . "' ";
    $query .= "and mf_cardset = '" . $mf_cardset . "' ";
    $query .= "and mf_frontcontent = '" . addslashes($mf_frontcontent) . "' ";
    $query .= "and mf_backcontent = '" . addslashes($mf_backcontent) . "' ";

    $result_myflashcard_duplication = db_query($query);
    if ($row_myflashcard_duplication = db_fetch_array($result_myflashcard_duplication)) {           // 중복될 경우
        $flashcard_myflashcard_add = false;

        return $flashcard_myflashcard_add;
        exit;
    }


    // 등록
    $query = "insert into myflashcard_tb ( ";
    $query .= "mf_user, ";
    $query .= "mf_boxstep, ";
    $query .= "mf_cardset, ";
    $query .= "mf_flashcard, ";
    $query .= "mf_frontcontent, ";
    $query .= "mf_backcontent, ";
    $query .= "mf_regtime, ";
    $query .= "mf_studytime, ";
    $query .= "mf_testtime ";
    $query .= ") values ( ";
    $query .= "'" . $mf_user . "', ";
    $query .= "'" . $mf_boxstep . "', ";
    $query .= "'" . $mf_cardset . "', ";
    $query .= "'" . $mf_flashcard . "', ";
    $query .= "'" . addslashes($mf_frontcontent) . "', ";
    $query .= "'" . addslashes($mf_backcontent) . "', ";
    $query .= "'" . $mf_regtime . "', ";
    $query .= "'" . $mf_studytime . "', ";
    $query .= "'" . $mf_testtime . "' ";
    $query .= ")";   
    
    if ($result = db_query($query)) {
        $flashcard_myflashcard_add = true;
    }
    else {
        $flashcard_myflashcard_add = false;
    }
    
    return $flashcard_myflashcard_add;
}

// 플래시카드수정시 내플래시카드 수정
function set_flashcard_myflashcard_update($fc_id) {  
    global $_SESSION;

    $flashcard_myflashcard_update = ""; // 초기화
    
    // 플래시카드 데이터를 읽음
    $query = "select ";
    $query .= "fc_id, ";
    $query .= "fc_cardset, ";
    $query .= "fc_frontcontent, ";
    $query .= "fc_backcontent, ";
    $query .= "fc_order, ";
    $query .= "fc_reguser, ";
    $query .= "fc_regtime, ";
    $query .= "fc_updateuser, ";
    $query .= "fc_updatetime ";
    $query .= "from flashcard_tb ";     // 플래시카드 (Flashcard)
    $query .= "where fc_id = '" . $fc_id . "' ";

    $result_flashcard = db_query($query);

    if (!$row_flashcard = db_fetch_array($result_flashcard)) {
        $flashcard_myflashcard_update = false;

        return $flashcard_myflashcard_update;
        exit;
    }


    // 내플래시카드 (My Flashcard)
    $mf_flashcard       =   $row_flashcard["fc_id"];
    $mf_frontcontent    =   $row_flashcard["fc_frontcontent"];
    $mf_backcontent     =   $row_flashcard["fc_backcontent"];

    // 수정
    $query = "update myflashcard_tb set ";
    $query .= "mf_frontcontent = '" . addslashes($mf_frontcontent) . "', ";
    $query .= "mf_backcontent = '" . addslashes($mf_backcontent) . "' ";
    $query .= "where mf_flashcard = '" . $mf_flashcard . "' ";    
    // $query .= "limit 1 ";
    
    if ($result = db_query($query)) {
        $flashcard_myflashcard_update = true;
    }
    else {
        $flashcard_myflashcard_update = false;
    }
    
    return $flashcard_myflashcard_update;
}

// 내플래시카드 삭제
function set_myflashcard_delete($mf_id) {  

    $myflashcard_delete = "";           // 초기화

    if ($mf_id == "") {
        $myflashcard_delete = false;

        return $myflashcard_delete;
        exit;
    }

    // 삭제
    $query = "delete from myflashcard_tb ";
    $query .= "where mf_id = '" . $mf_id . "' ";
    $query .= "limit 1 ";

    if ($result = db_query($query)) {
        $myflashcard_delete = true;
    }
    else {
        $myflashcard_delete = false;
    }
    
    return $myflashcard_delete;
}

// 학습일시 (Study Time) 설정
function set_myflashcard_studytime($mf_id) {  
    $myflashcard_studytime = "";        // 초기화

    if ($mf_id == "") {
        $myflashcard_studytime = false;

        return $myflashcard_studytime;
        exit;
    }

    // 수정
    $mf_studytime   =   current_datetime();                 // 학습일시 (Study Time)
    
    $query = "update myflashcard_tb set ";
    $query .= "mf_studytime = '" . $mf_studytime . "' ";
    $query .= "where mf_id = '" . $mf_id . "' ";
    $query .= "limit 1 ";
    // echo $query;

    if ($result = db_query($query)) {
        $myflashcard_studytime = true;
    }
    else {
        $myflashcard_studytime = false;
    }
    
    return $myflashcard_studytime;
}

// 시험확인처리 (Check Quiz)
function set_myflashcard_quiz_check($mf_id, $quiz_check) {  
    global $db_mf_boxstep_array;

    $myflashcard_quiz_check = "";       // 초기화

    // 내플래시카드 데이터를 읽음
    $query = "select ";
    $query .= "mf_id, ";
    $query .= "mf_user, ";
    $query .= "mf_boxstep, ";
    $query .= "mf_cardset, ";
    $query .= "mf_frontcontent, ";
    $query .= "mf_backcontent, ";
    $query .= "mf_regtime, ";
    $query .= "mf_studytime, ";
    $query .= "mf_testtime ";
    $query .= "from myflashcard_tb ";   // 내플래시카드 (My Flashcard)
    $query .= "where mf_id = '" . $mf_id . "' ";

    $result_myflashcard = db_query($query);

    if (!$row_myflashcard = db_fetch_array($result_myflashcard)) {
        $myflashcard_quiz_check = false;

        return $myflashcard_quiz_check;
        exit;
    }

    // 기존 박스단계 (Box Step)
    $mf_boxstep_prev = $row_myflashcard["mf_boxstep"];

    // 정답, 오답
    if ($quiz_check == "right") {
        switch ($mf_boxstep_prev) {
            case $db_mf_boxstep_array["Step1"]:
                $mf_boxstep = $db_mf_boxstep_array["Step2"];
                break;

            case $db_mf_boxstep_array["Step2"]:
                $mf_boxstep = $db_mf_boxstep_array["Step3"];
                break;

            case $db_mf_boxstep_array["Step3"]:
                $mf_boxstep = $db_mf_boxstep_array["Step4"];
                break;

            case $db_mf_boxstep_array["Step4"]:
                $mf_boxstep = $db_mf_boxstep_array["Step5"];
                break;

            case $db_mf_boxstep_array["Step5"]:
                $mf_boxstep = $db_mf_boxstep_array["Completion"];
                break;

            case $db_mf_boxstep_array["Completion"]:
                $mf_boxstep = $db_mf_boxstep_array["Completion"];
                break;
            
            default:
                $mf_boxstep = $db_mf_boxstep_array["Step1"];
                break;
        }
    }
    else if ($quiz_check == "wrong") {
        /*
        switch ($mf_boxstep_prev) {
            case $db_mf_boxstep_array["Step1"]:
                $mf_boxstep = $db_mf_boxstep_array["Step1"];
                
                break;

            case $db_mf_boxstep_array["Step2"]:
                $mf_boxstep = $db_mf_boxstep_array["Step1"];
                break;

            case $db_mf_boxstep_array["Step3"]:
                $mf_boxstep = $db_mf_boxstep_array["Step2"];
                break;

            case $db_mf_boxstep_array["Step4"]:
                $mf_boxstep = $db_mf_boxstep_array["Step3"];
                break;

            case $db_mf_boxstep_array["Step5"]:
                $mf_boxstep = $db_mf_boxstep_array["Step4"];
                break;

            case $db_mf_boxstep_array["Completion"]:
                $mf_boxstep = $db_mf_boxstep_array["Step5"];
                break;
            
            default:
                $mf_boxstep = $db_mf_boxstep_array["Step1"];
                break;
        }
        */

        // 틀리면 무조건 Step1로 이동
        $mf_boxstep = $db_mf_boxstep_array["Step1"];
    }
    else {
        $myflashcard_quiz_check = false;

        return $myflashcard_quiz_check;
        exit;
    }

    // 수정
    $mf_testtime    =   current_datetime();                 // 테스트일시 (Test Time)
    
    $query = "update myflashcard_tb set ";
    $query .= "mf_boxstep = '" . $mf_boxstep . "', ";
    $query .= "mf_testtime = '" . $mf_testtime . "' ";
    $query .= "where mf_id = '" . $mf_id . "' ";
    $query .= "limit 1 ";
    // echo $query; exit;

    if ($result = db_query($query)) {
        $myflashcard_quiz_check = true;
    }
    else {
        $myflashcard_quiz_check = false;
    }
    
    return $myflashcard_quiz_check;
}


////////////////////////////////////////////////////////////////////////////////
// DB 데이터 정리

// 위키플래시카드 DB파일 정리 (내플래시카드, 사용자)
function set_wikiflashcard_db_auto_delete() {
    global $SITE_VAR;
    global $db_u_usertype_array;

    ////////////////////////////////////////////////////////////////////////////////
    // 1. 내플래시카드 (My Flashcard)

    // 내플래시카드(myflashcard_tb) 항목을 구한다.
    $query = "select ";
    $query .= "mf_id ";
    $query .= "from myflashcard_tb ";

    $query .= "inner join user_tb ";
    $query .= "on mf_user = u_id ";

    $query .= "where u_usertype = '" . $db_u_usertype_array["Guest"] . "' ";
    
    $query .= "order by mf_id ";
    $query .= "limit 1 ";
    // echo "<br /><br />" . $query; // exit;

    $result_myflashcard = db_query($query);
    if ($row_myflashcard = db_fetch_array($result_myflashcard)) {
        // 삭제
        $query = "delete from myflashcard_tb ";
        $query .= "where mf_id = '" . $row_myflashcard["mf_id"] . "' ";
        $query .= "limit 1 ";
        // echo "<br />query : " . $query; // exit;

        if ($result_delete = db_query($query)) {
            // return true;
        }
        else {
            return false;
        }
    }   


    ////////////////////////////////////////////////////////////////////////////////
    // 2. 사용자 (User)

    $temp_date = date_difference_day(current_date(), -$SITE_VAR["db_save_date"]);

    // 사용자 (User) 항목을 구한다.
    $query = "select ";
    $query .= "u_id ";
    $query .= "from user_tb ";
    $query .= "where u_usertype = '" . $db_u_usertype_array["Guest"] . "' ";
    $query .= "and u_regtime <= '" . $temp_date . "000000' ";
    $query .= "order by u_updatetime, u_regtime, u_id ";
    $query .= "limit 1 ";
    // echo "<br /><br />" . $query; // exit;

    $result_user = db_query($query);
    if ($row_user = db_fetch_array($result_user)) {
        // 삭제
        $query = "delete from user_tb ";
        $query .= "where u_id = '" . $row_user["u_id"] . "' ";
        $query .= "limit 1 ";
        // echo "<br />query : " . $query; // exit;

        if ($result_delete = db_query($query)) {
            // return true;
        }
        else {
            return false;
        }
    }


    ////////////////////////////////////////////////////////////////////////////////
    // 함수 리턴
    return true;
}

?>