<?php include('vlab-header.inc'); ?>
<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';
include_once 'conf.php';

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
*/

/* -------------------------------------------------------------------------- */
//TODO: put this in a conf file with the rest
$t="vlab_interim";


function MakeNewImage() {
    global $DBconnection;

    $result = pg_prepare($DBconnection, "add_prep", 'INSERT INTO vlab_interim.disk_images (use, vm_name_base, conf_template, backing_file, ram, vcpu) VALUES ($1, $2, $3, $4, $5, $6)');

    $use = GetStringFromPost('friendly_name');
    $ImageFile = GetStringFromPost('image_file');
    $vm_name_base = basename($ImageFile, '.img');
    $conf_template = "generic.template";
    $backing_file = end( explode( "/", $ImageFile) );
    $memsize = GetNumFromPost('memsize');
    $vcpu = GetNumFromPost('vcpu');

    $result = pg_execute($DBconnection, "add_prep", array("$use", "$vm_name_base", "$conf_template", "$backing_file", "$memsize", "$vcpu")); 

}


/* main loop ---------------------------------------------------------------- */
DBConnect();
//DBBegin();

MakeNewImage();

//$FormContent .= StickyPost();

/* -------------------------------------------------------------------------- */
//$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Write to DB.", $Content));

//DBCommit();
$ImageFile = GetStringFromPost('image_file');
writeln("The base image has been added!<br><p>Please make sure the <b><em>$ImageFile</em></b> file is propegated to all the local xen nodes before use.");
DBClose();

?>
<?php include('vlab-footer.inc'); ?>
~
