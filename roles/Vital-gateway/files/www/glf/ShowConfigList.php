<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';

function GetNet($netnum) {
    if ($netnum == 0) {
        return 'Class Net';
    } else {
        return "Network #$netnum";
    }
}

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
 */
/* -------------------------------------------------------------------------- */
DBConnect();

$Goto = "";
$Content = '';
/* -------------------------------------------------------------------------- */
$NumVMsPerStudent = rtrim(ValidateNumber($_POST['NumVMs']));
$FormContent = '';

//print_r($_POST);


if ($_POST['savename'] != '') {
    $ConfigName = GetStringFromPost('savename');
} else {
    $ConfigName = 'Unnamed Config';
}

$FormContent .= "<b>$ConfigName</b><hr>";

for ($i=1; $i<=$NumVMsPerStudent; $i++) 
{
    //$FormContent .= "<b>Role: ".OSidToOS(GetOSid($i))."</b>";
	$VMSetID 	= "<i>VM #: ".$i."</i><br/> Image: ".GetOS($i);
	$VMSetNumNICs 	= GetNumNICs($i);
	$FormContent .=	 $VMSetID;	
	$NumNets = GetNumNets();

	$FormContent .= '<br><center><table  border="0">';
	$FormContent .= '<tr>';
	for($n=1; $n<=$VMSetNumNICs; $n++)
	{

		$FormContent .= "<td>NIC #".$n.": ";
        $FormContent .= GetNet($_POST["VM".$i."NIC"."$n"]);
		$FormContent .= "</td></tr>";
	}
	$FormContent .= '</tr>';
	$FormContent .= '</table></center>';



	$FormContent .= "<hr>";

}

#$FormContent .= "<b>Press 'submit' if valid.</b><hr>";
#$FormContent .= StickyPost();

DBClose();

/* -------------------------------------------------------------------------- */
#$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Assign Networks to VMs", $Content));
#echo($Content);
echo($FormContent);


?>

<?php include('vlab-footer.inc'); ?>
