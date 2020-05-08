<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/common/global/global.inc.php");

$cs_id      =   trim($_POST["cs_id"]);
$cs_setname =   trim($_POST["cs_setname"]);

// 중복체크
$query = "select count(*) as total_count ";
$query .= "from cardset_tb ";
$query .= "where cs_id != '" . $cs_id . "' ";
$query .= "and cs_setname = '" . $cs_setname . "' ";

$result = db_query($query);
$row = db_fetch_array($result);

if ($row["total_count"] > 0) {
    echo "Y";
}
else {
    echo "N";
}

?>