<?php include('vlab-header.inc'); ?>
<?php

include_once 'include/framework.php';
include_once 'include/DBI.php';
include_once 'include/API.php';

//print_r($_POST);

$confid = GetNumFromPost('ConfigID');

$t='vlab_interim';
function DelConfig($id) {
    global $t;
    $q = "DELETE FROM $t.section_configs WHERE id ='$id';";
    //writeln($q);
    DBConnect();
    DBBegin();
    HailMaryDB($q);
    DBCommit();
    DBClose();
}

DelConfig($confid);
echo("Config $confid is deleted.");


?>
<?php include('vlab-footer.inc'); ?>
