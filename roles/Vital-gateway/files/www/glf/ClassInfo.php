<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/DBI.php';

DBConnect();
$Goto = "GetClassInfo.php";

$Content = '';

$Content .= H3("Get Class Info");
$Content .= Line(WebLn("Select Class: "));

$FormContent = CreateDropdown("ClassID", GetAllClasses());

$Content .= Form($Goto, "POST", $FormContent);

//echo(Page("Great Leap Forward", $Content));
echo($Content);
DBClose();

?>


<?php include('vlab-footer.inc'); ?>
