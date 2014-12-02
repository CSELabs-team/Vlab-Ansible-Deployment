<?php include('vlab-header.inc'); ?>
<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/DBI.php';


DBConnect();
DBBegin();
$Goto="Restore.php";
$Content='';
$FormContent = '';

$Content .= H3('Restore class to a saved configuration.');
$FormContent .= 'Select class to be changed, <br />your existing machines will be destroyed!:<br/>';

$FormContent .= CreateDropdown("ClassID", GetAllClasses());

$FormContent .= 'Select config to restore from:<br/>';

$FormContent .= CreateDropDown("ConfigID", GetAllConfigs());
$FormContent .= StickyPost();




$Content .= Form($Goto, "POST", $FormContent);

echo(Page("Restore Classes", $Content));
DBCommit();
DBClose();



?>
<?php include('vlab-footer.inc'); ?>
