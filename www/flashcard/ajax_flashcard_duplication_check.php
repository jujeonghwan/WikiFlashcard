<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

$fc_id              =   trim($_POST["fc_id"]);
$fc_cardset         =   trim($_POST["fc_cardset"]);
$fc_frontcontent    =   trim($_POST["fc_frontcontent"]);
// $fc_backcontent  =   trim($_POST["fc_backcontent"]);

// 중복체크
$query = "select count(*) as total_count ";
$query .= "from flashcard_tb ";
$query .= "where fc_id != '" . $fc_id . "' ";
$query .= "and fc_cardset = '" . $fc_cardset . "' ";
$query .= "and fc_frontcontent = '" . $fc_frontcontent . "' ";
// $query .= "and fc_backcontent = '" . $fc_backcontent . "' ";

$result = db_query($query);
$row = db_fetch_array($result);

if ($row["total_count"] > 0) {
    echo "Y";
}
else {
    echo "N";
}

?>