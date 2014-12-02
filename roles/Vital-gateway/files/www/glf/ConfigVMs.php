<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';


//print_r($_POST);
//
DBConnect();

$NumVMsPerStudent = rtrim(ValidateNumber($_POST['NumVMs']));
/* we set this to 4, that's the max we want to give anyway */
$MAX_NICS     = 4; //rtrim(ValidateNumber($_POST['NumNets']));

$Goto = "ConfigNICs.php";

$Content = '';
$FormContent = '';

for ($i=1; $i<=$NumVMsPerStudent; $i++) 
{
	//echo("loop #".$i."<br>");
	$FormContent .= WebLn(BoldLine("VM # ".$i));
	//$FormContent .= Line('<label for="formOS">USE: </label>');
	$FormContent .= Line(WebLn('<br>VM image:'));
	$FormContent .= (CreateDropDown("VM".$i."OS", GetOSes()));
	$FormContent .= Line(WebLn('Number of NICs on VM #'.$i));
	$FormContent 
		.= (CreateDropDown("VM".$i."NumNICs", NumberArray($MAX_NICS)));
	$FormContent .= Line("<hr>");
}

$FormContent .= StickyPost();


$Content .= Form($Goto, "POST", $FormContent);

/* invoke */
//echo(Page("Configure the VMs", $Content));
echo($Content);

DBClose();
?>

<?php include('vlab-footer.inc'); ?>
