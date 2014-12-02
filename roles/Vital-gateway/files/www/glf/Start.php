<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/DBI.php';

DBConnect();
$Goto = "GetClassSetupInfo.php";

$Content = '';

$Content .= H3("Create Student Networks");


$FormContent  = 'Config Name (You can leave this blank.)';
$FormContent .= '<input type="text" name="savename" /><br />';


$FormContent .= Line(WebLn("Select Class: "));
$FormContent .= CreateDropdown("ClassID", GetAllClasses());

$Content .= Form($Goto, "POST", $FormContent);

echo($Content);
DBClose();

?>


<?php include('vlab-footer.inc'); ?>
