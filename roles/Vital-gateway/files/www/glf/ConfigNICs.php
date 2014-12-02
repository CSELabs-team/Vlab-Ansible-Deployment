<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
 */
/* -------------------------------------------------------------------------- */
DBConnect();

$Goto = "Confirm.php";
$Content = '';
/* -------------------------------------------------------------------------- */
$NumVMsPerStudent = rtrim(ValidateNumber($_POST['NumVMs']));
$FormContent = '';

$NumSetsOfVMs = rtrim(ValidateNumber($_POST['NumVMs']));

function NetArray($max)
{
	$array = array();
	for ($i = 0; $i <= $max; $i++) 
	{ 
		$array[$i] = ($i == 0) ? 'Class Net' : "Network $i"; 
	}
	return($array);
}

for ($i=1; $i<=$NumSetsOfVMs; $i++) 
{
	$VMSetID 	= "<b>VM #: ".$i."</b><p> Image: ".GetOS($i);
	$VMSetNumNICs 	= GetNumNICs($i);
	$FormContent .=	 $VMSetID;	
	$NumNets = GetNumNets();

	$FormContent .= '<br><center><table  border="0">';
	$FormContent .= '<tr>';
	for($n=1; $n<=$VMSetNumNICs; $n++)
	{

		$FormContent .= "<td>NIC #".$n.": ";

		$FormContent .= 
			CreateDropDown("VM".$i."NIC".$n, NetArray($NumNets)); 
		$FormContent .= "</td></tr>";
	}
	$FormContent .= '</tr>';
	$FormContent .= '</table></center>';



	$FormContent .= "<hr>";

}

$_POST['Restore']='no';
$FormContent .= StickyPost();

DBClose();

/* -------------------------------------------------------------------------- */
$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Assign Networks to VMs", $Content));
echo($Content);


?>

<?php include('vlab-footer.inc'); ?>
