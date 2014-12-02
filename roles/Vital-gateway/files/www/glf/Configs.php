<?php include('vlab-header.inc'); ?>
<?php

include_once 'include/framework.php';
include_once 'include/DBI.php';
include_once 'include/API.php';

$t="vlab_interim";

DBConnect();
DBBegin();
$Goto='DeleteConf.php';
$Content='';
$FormContent='';

$Content='Select a config to delete:<br/>';

function GetConfigData() {
    global $t;
    $cols = "to_char(created, 'DD Mon YYYY HH24:MI') as created, saved_name, id";
    $q = "SELECT $cols FROM $t.section_configs";
    $DB = HailMaryDB($q);
    $lines = array();
    $count = 0;
    
    while ($obj = pg_fetch_object($DB)) {
        $lines[$count] = $obj;
        $count++;
    }
    
    return($lines);
}


/*
$tabledata=GetConfigData();

$table = '<center><table border="1">';
$table .= '<th>Timestamp:</th>';
$table .= '<th>Label:</th>';

foreach ($tabledata as $line) {
    $table .=("<tr><td>$line->created</td> <td>$line->saved_name</td></tr>\n");
}

$table .= '</table></center><p>';
*/

$FormContent .= $table;
$FormContent .= CreateDropDown('ConfigID', GetAllConfigs());
$FormContent .= StickyPost();

$Content .= Form($Goto, "POST", $FormContent);
//echo (Page("Delete Configs", $Content));
echo ($Content);

DBCommit();
DBClose();


?>
<?php include('vlab-footer.inc'); ?>
