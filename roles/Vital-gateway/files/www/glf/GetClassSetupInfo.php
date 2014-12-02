<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/DBI.php';
include_once 'include/SEC.php';


/*TODO print out the class you are making changes on from the db */
//print_r($_POST);

$MAX_NETWORKS = 10;
$MAX_VMS      = 10;


$class = rtrim(ValidateNumber($_POST['ClassID']));


$Goto = "ConfigVMs.php";
//$Goto = "ConfigNICs.php";

$Content = '';


$Content .=H3("Select number of VMs and Networks per Student");
$Content .=Line("");
$FormContent = '';

$FormContent .= Line(WebLn("Number of VMs per student?"));
$FormContent .= CreateDropdown("NumVMs",  NumberArray($MAX_VMS));
$FormContent .= Line(WebLn("Number of networks per student? <br>(Excluding the default network)"));
$FormContent .= CreateDropdown("NumNets", NumberArray($MAX_NETWORKS));

$FormContent .= StickyPost();

$Content .= Form($Goto, "POST", $FormContent);

echo($Content);

?>

<?php include('vlab-footer.inc'); ?>
