<?php

// DB
$GLOVAL_DB["host"]  =   "localhost";
$GLOVAL_DB["user"]  =   "wikiflashcard";
$GLOVAL_DB["pass"]  =   "#wiki202005";
$GLOVAL_DB["name"]  =   "wikiflashcard";

// MariaDB 서버에 접속후 데이터베이스를 선택
function db_connect() {
    global $mysqli_connect;
    global $GLOVAL_DB;

    $mysqli_connect = mysqli_connect($GLOVAL_DB["host"], $GLOVAL_DB["user"], $GLOVAL_DB["pass"], $GLOVAL_DB["name"]);

    // character_set
    $mysqli_connect->query ("set names utf8");
    
    return $mysqli_connect;
}

// 데이터베이스에 질의를 전송
function db_query($pQuery, $pConnect = "") {
    global $mysqli_connect;

    $temp_connect = ($pConnect == "") ? $mysqli_connect : $pConnect;
    
    $result = mysqli_query($mysqli_connect, $pQuery);

    return $result;
}

// 결과로부터 열 개수를 반환
function db_num_rows($pResult) {
    /* determine number of rows result set */
    return mysqli_num_rows($pResult);
}

// 최근 INSERT 작업으로부터 생성된 identifier 값을 반환
function db_insert_id() {
    global $mysqli_connect;
 
    return mysqli_insert_id($mysqli_connect);
}

// 결과를 필드이름 색인 또는 숫자 색인으로 된 배열로 반환
function db_fetch_array($pResult) {
    /* associative and numeric array */
    return mysqli_fetch_array($pResult, MYSQLI_BOTH);
}

// result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
function db_free_result($pResult) {
    /* free result set */
    mysqli_free_result($pResult);
}

// DB 접속을 닫음
function db_close() {
    global $mysqli_connect;

    if ($mysqli_connect) {
        /* close connection */
        mysqli_close($mysqli_connect);

        $mysqli_connect = "";
    }    
}


// DB 구조및 관련 배열 변수

/* 공통 배열 항목 ABC순 */
// 진행상태
$db_common_dealstate_array = array (
    "진행"    =>  "1",
    "중단"    =>  "2"
);
$color_common_dealstate_array = array (
    "1" =>  "blue",
    "2" =>  "red"
);

// 수집상태
$db_common_gatherstate_array = array (
    "수집대기"  =>  "1",
    "수집완료"  =>  "2"
);
$color_common_gatherstate_array = array (
    "1" =>  "red",
    "2" =>  "blue"
);

// 처리상태
$db_common_processstate_array = array (
    "미처리"    =>  "1",
    "처리완료"  =>  "2"
);
$color_common_processstate_array = array (
    "1" =>  "red",
    "2" =>  "blue"
);

// 정렬순서
$db_common_sorttype_array = array (
    "Ascend" => "1",
    "Descend" => "2"
);
$color_common_sorttype_array = array (
    "1" =>  "blue",
    "2" =>  "red"
);

// 사용상태
$db_common_usestate_array = array (
    "사용"    =>  "1",
    "중지"    =>  "2"
);
$color_common_usestate_array = array (
    "1" =>  "blue",
    "2" =>  "red"
);

// 공개여부
$db_common_viewtype_array = array (
    "공개"    =>  "1",
    "비공개"   =>  "2"
);
$color_common_viewtype_array = array (
    "1" =>  "blue",
    "2" =>  "red"
);

// 예아니오
$db_common_yesnotype_array = array (
    "예"     =>  "1",
    "아니오" =>  "2"
);
$color_common_yesnotype_array = array (
    "1" =>  "blue",
    "2" =>  "red"
);

/* Cardset (카드세트)
CREATE TABLE cardset_tb (
  cs_id int(11) NOT NULL AUTO_INCREMENT COMMENT '카드세트번호 (Cardset ID)',
  cs_setname varchar(250) NOT NULL DEFAULT '' COMMENT '세트명 (Cardset Name)',
  cs_frontitemname varchar(200) NOT NULL DEFAULT '' COMMENT '앞면항목명 (Front Item Name)',
  cs_backitemname varchar(200) NOT NULL DEFAULT '' COMMENT '뒷면항목명 (Back Item Name)',

  cs_reguser int(11) NOT NULL DEFAULT '0' COMMENT '등록사용자번호 (Writer)',
  cs_regtime varchar(14) NOT NULL DEFAULT '' COMMENT '등록일시 (Reg Time)',
  cs_updateuser int(11) NOT NULL DEFAULT '0' COMMENT '수정사용자번호 (Editor)',  
  cs_updatetime varchar(14) NOT NULL DEFAULT '' COMMENT '수정일시 (Update Time)',
  PRIMARY KEY (cs_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='카드세트 (Cardset)';
ALTER TABLE cardset_tb ADD INDEX (cs_setname);
ALTER TABLE cardset_tb ADD INDEX (cs_frontitemname);
ALTER TABLE cardset_tb ADD INDEX (cs_backitemname);

ALTER TABLE cardset_tb ADD INDEX (cs_reguser);
ALTER TABLE cardset_tb ADD INDEX (cs_regtime);
ALTER TABLE cardset_tb ADD INDEX (cs_updateuser);
ALTER TABLE cardset_tb ADD INDEX (cs_updatetime);
*/

/* Flashcard (플래시카드)
CREATE TABLE flashcard_tb (
  fc_id int(11) NOT NULL AUTO_INCREMENT COMMENT '플래시카드번호 (Flashcard ID)',
  fc_cardset int(11) NOT NULL DEFAULT '0' COMMENT '카드세트번호 (Cardset ID)',
  fc_frontcontent text NULL COMMENT '앞면항목내용 (Front Content)',
  fc_backcontent text NULL COMMENT '뒷면항목내용 (Back Content)',
  fc_order int(11) NOT NULL DEFAULT '0' COMMENT '순서 (Order)',

  fc_reguser int(11) NOT NULL DEFAULT '0' COMMENT '등록사용자번호 (Writer)',  
  fc_regtime varchar(14) NOT NULL DEFAULT '' COMMENT '등록일시 (Reg Time)',
  fc_updateuser int(11) NOT NULL DEFAULT '0' COMMENT '수정사용자번호 (Editor)',
  fc_updatetime varchar(14) NOT NULL DEFAULT '' COMMENT '수정일시 (Update Time)',
  PRIMARY KEY (fc_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='플래시카드 (Flashcard)';
ALTER TABLE flashcard_tb ADD INDEX (fc_cardset);
ALTER TABLE flashcard_tb ADD INDEX (fc_order);

ALTER TABLE flashcard_tb ADD INDEX (fc_reguser);
ALTER TABLE flashcard_tb ADD INDEX (fc_regtime);
ALTER TABLE flashcard_tb ADD INDEX (fc_updateuser);
ALTER TABLE flashcard_tb ADD INDEX (fc_updatetime);
*/

/* Imagefile (이미지파일)
CREATE TABLE imagefile_tb (
  if_id int(11) NOT NULL AUTO_INCREMENT COMMENT '이미지파일번호',
  if_tablename varchar(50) NOT NULL DEFAULT '' COMMENT '테이블명',
  if_tablekeyid int(11) NOT NULL DEFAULT '0' COMMENT '테이블키번호',
  if_name varchar(100) NOT NULL DEFAULT '' COMMENT '첨부파일이름',
  if_filepath varchar(200) NOT NULL DEFAULT '' COMMENT '파일경로',
  if_filename varchar(100) NOT NULL DEFAULT '' COMMENT '파일명',
  if_filesize int(11) NOT NULL DEFAULT '0' COMMENT '파일크기',
  if_note varchar(100) NOT NULL DEFAULT '' COMMENT '비고',

  if_reguser int(11) NOT NULL DEFAULT '0' COMMENT '등록사용자번호',  
  if_regtime varchar(14) NOT NULL DEFAULT '' COMMENT '등록일시',
  PRIMARY KEY (if_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='이미지파일';
ALTER TABLE imagefile_tb ADD INDEX (if_tablename);
ALTER TABLE imagefile_tb ADD INDEX (if_tablekeyid);

ALTER TABLE imagefile_tb ADD INDEX (if_reguser);
ALTER TABLE imagefile_tb ADD INDEX (if_regtime);
*/

/* My Flashcard (내플래시카드)
CREATE TABLE myflashcard_tb (
  mf_id int(11) NOT NULL AUTO_INCREMENT COMMENT '내플래시카드번호 (My Flashcard ID)',
  mf_user int(11) NOT NULL DEFAULT '0' COMMENT '사용자번호 (User)',  
  mf_boxstep tinyint(4) NOT NULL DEFAULT '1' COMMENT '박스단계 (Box Step)',
  mf_cardset int(11) NOT NULL DEFAULT '0' COMMENT '카드세트번호 (Cardset ID)',
  mf_flashcard int(11) NOT NULL DEFAULT '0' COMMENT '플래시카드번호 (Flashcard ID)',
  mf_frontcontent text NULL COMMENT '앞면항목내용 (Front Content)',
  mf_backcontent text NULL COMMENT '뒷면항목내용 (Back Content)',

  mf_regtime varchar(14) NOT NULL DEFAULT '' COMMENT '등록일시 (Reg Time)',
  mf_studytime varchar(14) NOT NULL DEFAULT '' COMMENT '학습일시 (Study Time)',
  mf_testtime varchar(14) NOT NULL DEFAULT '' COMMENT '테스트일시 (Test Time)',
  PRIMARY KEY (mf_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='내플래시카드 (My Flashcard)';
ALTER TABLE myflashcard_tb ADD INDEX (mf_user);
ALTER TABLE myflashcard_tb ADD INDEX (mf_boxstep);
ALTER TABLE myflashcard_tb ADD INDEX (mf_cardset);
ALTER TABLE myflashcard_tb ADD INDEX (mf_flashcard);

ALTER TABLE myflashcard_tb ADD INDEX (mf_regtime);
ALTER TABLE myflashcard_tb ADD INDEX (mf_studytime);
ALTER TABLE myflashcard_tb ADD INDEX (mf_testtime);
*/
// Box Step (박스단계)
$db_mf_boxstep_array = array (
    "Step1" => "1",                     // 1단계
    "Step2" => "2",                     // 2단계
    "Step3" => "3",                     // 3단계
    "Step4" => "4",                     // 4단계
    "Step5" => "5",                     // 5단계

    "Completion" => "9"                 // 완료
);
$color_mf_boxstep_array = array (
    "1" => "black",
    "2" => "red",
    "3" => "purple",
    "4" => "maroon",
    "5" => "blue",

    "9" => "black",

);

// Box Step Recommended Quantity (박스단계 추천수량)
$count_mf_boxstep_array = array (
    "1" => "60",
    "2" => "120",
    "3" => "240",
    "4" => "420",
    "5" => "500"
);

/* User (사용자)
CREATE TABLE user_tb (
  u_id int(11) NOT NULL AUTO_INCREMENT COMMENT '사용자번호 (User ID)',
  u_usertype tinyint(4) NOT NULL DEFAULT '1' COMMENT '사용자구분 (User Type)',
  u_accountid varchar(50) NOT NULL DEFAULT '' COMMENT '계정아이디(이메일) (Account ID)',
  u_ipaddress varchar(20) NOT NULL DEFAULT '' COMMENT 'IP주소 (IP Address)',
  u_referer varchar(500) NOT NULL DEFAULT '' COMMENT '링크주소 (Referer)',

  u_regtime varchar(14) NOT NULL DEFAULT '' COMMENT '등록일시 (Reg Time)',
  u_updatetime varchar(14) NOT NULL DEFAULT '' COMMENT '수정일시 (Update Time)',
  PRIMARY KEY (u_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='사용자 (User)';
ALTER TABLE user_tb ADD INDEX (u_usertype);
ALTER TABLE user_tb ADD UNIQUE (u_accountid);               -- UNIQUE
ALTER TABLE user_tb ADD INDEX (u_ipaddress);

ALTER TABLE user_tb ADD INDEX (u_regtime);
ALTER TABLE user_tb ADD INDEX (u_updatetime);
*/
// User Type (사용자구분)
$db_u_usertype_array = array (
    "Guest" => "1",                     // 손님
    "User"  => "2"                      // 계정
);
$color_u_usertype_array = array (
    "1" => "red",
    "2" => "blue"    
);

// 데이터베이스 접속
db_connect();

?>
