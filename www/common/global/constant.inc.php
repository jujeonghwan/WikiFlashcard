<?php

////////////////////////////////////////////////////////////////////////////////////////////////////
// 사이트 설정

$SITE_VAR["host"] = "wikiflashcard.com";
$SITE_VAR["domain"] = "www.wikiflashcard.com";

$SITE_VAR["url"] = "http://www.wikiflashcard.com/";
$SITE_VAR["title"] = "[WikiFlashcard] www.WikiFlashcard.com (Free Flashcard / Learning Card / Quiz Box)";                     
$SITE_VAR["keywords"] = "WikiFlashcard Flashcard Learning Card Quiz Box";                 
$SITE_VAR["description"] = $SITE_VAR["keywords"] . $SITE_VAR["title"];

$SITE_VAR["name"] = "WikiFlashcard";
$SITE_VAR["home_name"] = "WikiFlashcard";
$SITE_VAR["logofile"] = "WikiFlashcard.png";                // 로고파일
$SITE_VAR["logotitle"] = $SITE_VAR["title"];

$SITE_VAR["president"] = "JuJeongHwan";
$SITE_VAR["mobile"] = "+1-226-792-4376";
$SITE_VAR["email"] = "jujeonghwan@gmail.com";
$SITE_VAR["init_date"] = "20170301";
$SITE_VAR["copyright_begin_year"] = "2017";

$SITE_VAR["image_domain"] = "www.wikiflashcard.com";

$SITE_VAR["db_save_date"] = 3;      

////////////////////////////////////////////////////////////////////////////////////////////////////
// 사용자 설정

// 사용자
$USER_VAR["jujeonghwan_user_id"] = 2;
$USER_VAR["choiyoungsil_user_id"] = 39;


////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지 관련

// PAGE
$PAGE_VAR["page_count"] =   10;
$PAGE_VAR["list_count"] =   20;

$LIST_COUNT_ARRAY = array (
    "20개"   =>  "20",
    "50개"   =>  "50",
    "100개"  =>  "100",
    "200개"  =>  "200",
    "500개"  =>  "500",
    "1000개" =>  "1000"
);


// 디버깅 관련
$DEBUG_MODE = false;


// 접속 차단할 IP주소
$BLOCK_IP_ADDRESS_ARRAY = array (
    // "121.130.82.6",
    "-"
);



////////////////////////////////////////////////////////////////////////////////////////////////////
// 공통 설정


////////////////////////////////////////////////////////////////////////////////////////////////////
// Social Media Buttons

$ADDTHIS_SHARING_BUTTONS_SCRIPT = '
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <div class="addthis_sharing_toolbox">
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56dd660806a65409"></script>
        </div>
';


////////////////////////////////////////////////////////////////////////////////////////////////////
// Menu

// Menu
$MENU_ARRAY = array (

    "WikiFlashcard" => array (
        /*
        "Home" => array (
            "WikiFlashcard Main" => "/index.php",
            "Study Flashcard" => "/study/flashcard_study.php"
        ),
        */

        "Cardset" => array (
            "WikiFlashcard Main" => "/index.php",
            // "Cardset List" => "/cardset/cardset_list.php",
            "View Cardset" => "/cardset/cardset_view.php",
            "Add Cardset" => "/cardset/cardset_add.php",
            "Edit Cardset" => "/cardset/cardset_edit.php",
            "Study Cardset" => "/study/flashcard_study.php"
        ),

        "Flashcard" => array (
            "Flashcard List" => "/flashcard/flashcard_list.php",
            "View Flashcard" => "/flashcard/flashcard_view.php",
            "Add Flashcard" => "/flashcard/flashcard_add.php",
            "Edit Flashcard" => "/flashcard/flashcard_edit.php"
        ),

        "My FlashCard" => array (
            "My Flashcard List" => "/myflashcard/myflashcard_list.php",
            "Study My Flashcard" => "/myflashcard/myflashcard_study.php"
        ),
        
        "My QuizBox" => array (
            "QuizBox Flashcard List" => "/quizbox/quizbox_myflashcard_list.php",
            "QuizBox Flashcard Quiz" => "/quizbox/quizbox_myflashcard_quiz.php",
            "QuizBox Flashcard Quiz Check" => "/quizbox/quizbox_myflashcard_quiz_check.php"
        ),

        "Multi Search" => array (
            "Multi Search List" => "/multisearch/multisearch_list.php"
        )
    )
    
);



////////////////////////////////////////////////////////////////////////////////////////////////////
// Search Link

// Search Link URL
$SEARCHLINK_URL_ARRAY = array (
    "Google Search" => "https://www.google.com/search?q={query}",
    "Google Image Search" => "https://www.google.com/search?q={query}&tbm=isch",
    
    "--------Korean--------" => "",    
    "Naver English-Korean Dictionary" => "http://endic.naver.com/search.nhn?sLn=kr&searchOption=all&query={query}",
    "Naver Dictionary" => "http://dic.naver.com/search.nhn?sLn=kr&searchOption=all&query={query}",    
    // "Naver Korean Dictionary" => "http://krdic.naver.com/search.nhn?query={query}&kind=all",
    // "Naver Korean Hanja Dictionary" => "http://hanja.naver.com/search?query={query}",
    // "Naver Chinese-Korean Dictionary" => "http://zhdict.naver.com/#/search?query={query}",
    // "Naver Japanese-Korean Dictionary" => "http://jpdic.naver.com/search.nhn?range=all&q={query}&sm=jpd_hty",
    
    "Longman English-Korean Dictionary" => "https://www.ldoceonline.com/dictionary/english-korean/{query}",  
    "Korean Wikipedia" => "https://ko.wikipedia.org/wiki/{query}",

    "--------English--------" => "",
    "Wikipedia (English)" => "https://en.wikipedia.org/wiki/{query}",

    "Oxford Learners Dictionaries" => "http://www.oxfordlearnersdictionaries.com/definition/english/{query}",
    "Longman Dictionary" => "https://www.ldoceonline.com/dictionary/{query}",
    
    "Word Reference (Definition)" => "http://www.wordreference.com/definition/{query}",
    "Word Reference (Synonyms)" => "http://www.wordreference.com/synonyms/{query}",
    "Word Reference (English Usage)" => "http://www.wordreference.com/EnglishUsage/{query}",
    "Word Reference (English Collocations)" => "http://www.wordreference.com/EnglishCollocations/{query}",

    "Etymology Dictionary" => "http://www.etymonline.com/index.php?allowed_in_frame=0&search={query}",
    "How Many Syllables" => "https://www.howmanysyllables.com/words/{query}",

    // "--------Japanese--------" => "",
    // "Longman English-Japanese Dictionary" => "https://www.ldoceonline.com/dictionary/english-japanese/{query}",    
    // "Longman Japanese-English Dictionary" => "https://www.ldoceonline.com/dictionary/japanese-english/{query}",

    // "--------Spanish--------" => "",    
    // "Longman English-Spanish Dictionary" => "https://www.ldoceonline.com/dictionary/english-spanish/{query}",
    // "Longman Spanish-English Dictionary" => "https://www.ldoceonline.com/dictionary/spanish-english/{query}",

    "========End========" => ""
);


// Multi Search Type
$MULTISEARCH_TYPE_ARRAY = array (
    // "Google" => "https://www.google.com/search?q={query}",
    // "Google Image" => "https://www.google.com/search?q={query}&tbm=isch",

    // "Oxford" => "http://www.oxfordlearnersdictionaries.com/definition/english/{query}_1",
    // "Longman" => "https://www.ldoceonline.com/dictionary/{query}",
    
    "Naver(Dic)" => "http://dic.naver.com/search.nhn?sLn=kr&searchOption=all&query={query}",        

    "WR(Definition)" => "http://www.wordreference.com/definition/{query}",
    "WR(Synonyms)" => "http://www.wordreference.com/synonyms/{query}",
    "WR(Usage)" => "http://www.wordreference.com/EnglishUsage/{query}",
    "WR(Collocations)" => "http://www.wordreference.com/EnglishCollocations/{query}",

    "Etymology" => "http://www.etymonline.com/index.php?allowed_in_frame=0&search={query}",
    "Syllables" => "http://www.howmanysyllables.com/words/{query}",

    // "Naver(En-Ko)" => "http://endic.naver.com/search.nhn?sLn=kr&searchOption=all&query={query}",
    // "Longman(En-Ko)" => "http://www.ldoceonline.com/dictionary/english-korean/{query}",  

    // "Wikipedia" => "https://en.wikipedia.org/wiki/{query}",
    // "Wikipedia(Ko)" => "https://ko.wikipedia.org/wiki/{query}",
);


////////////////////////////////////////////////////////////////////////////////////////////////////
// Path

// 기본 경로
$PATH_VAR["default_url"] = "/";

// 사용자 기본 경로
$PATH_VAR["user_default_url"] = $PATH_VAR["default_url"];
// 사용자 로그인 경로
$PATH_VAR["user_login_url"] = "/login/";
// 사용자 로그아웃 경로
$PATH_VAR["user_logout_url"] = "/login/logout.php";


// 파일이미지경로
$PATH_VAR["home_path"] = "/home/users/wst001/www";                              // 홈디렉터리

$PATH_VAR["logo_url"] = "/files/logo";                                          // 로코 파일
$PATH_VAR["logo_path"] = $_SERVER["DOCUMENT_ROOT"] . $PATH_VAR["logo_url"];


////////////////////////////////////////////////////////////////////////////////////////////////////
// 배열상수

// 요일
$WDATE_ARRAY = array (
    "일" =>  "0",
    "월" =>  "1",
    "화" =>  "2",
    "수" =>  "3",
    "목" =>  "4",
    "금" =>  "5",
    "토" =>  "6"
);

// 월
$MONTH_ARRAY = array (
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12"
);

// 일
$DAY_ARRAY = array (
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23",
    "24" => "24",
    "25" => "25",
    "26" => "26",
    "27" => "27",
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31"
);

// 시
$HOUR_ARRAY = array (
    "00" => "00",
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23"
);

// 분
$MINUTE_ARRAY = array (
    "00" => "00",
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23",
    "24" => "24",
    "25" => "25",
    "26" => "26",
    "27" => "27",
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31",
    "32" => "32",
    "33" => "33",
    "34" => "34",
    "35" => "35",
    "36" => "36",
    "37" => "37",
    "38" => "38",
    "39" => "39",
    "40" => "40",
    "41" => "41",
    "42" => "42",
    "43" => "43",
    "44" => "44",
    "45" => "45",
    "46" => "46",
    "47" => "47",
    "48" => "48",
    "49" => "49",
    "50" => "50",
    "51" => "51",
    "52" => "52",
    "53" => "53",
    "54" => "54",
    "55" => "55",
    "56" => "56",
    "57" => "57",
    "58" => "58",
    "59" => "59"
);

// 초
$SECOND_ARRAY = array (
    "00" => "00",
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23",
    "24" => "24",
    "25" => "25",
    "26" => "26",
    "27" => "27",
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31",
    "32" => "32",
    "33" => "33",
    "34" => "34",
    "35" => "35",
    "36" => "36",
    "37" => "37",
    "38" => "38",
    "39" => "39",
    "40" => "40",
    "41" => "41",
    "42" => "42",
    "43" => "43",
    "44" => "44",
    "45" => "45",
    "46" => "46",
    "47" => "47",
    "48" => "48",
    "49" => "49",
    "50" => "50",
    "51" => "51",
    "52" => "52",
    "53" => "53",
    "54" => "54",
    "55" => "55",
    "56" => "56",
    "57" => "57",
    "58" => "58",
    "59" => "59"
);



// 인코딩 확인
$ENCODING_TYPE_ARRAY = array (
    "ASCII",
    "EUC-KR",
    "UTF-8"
);

?>