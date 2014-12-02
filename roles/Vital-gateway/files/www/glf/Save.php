<!--?php include('vlab-header.inc'); ?--> 
<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';

/* Debug: ------------------------------------------------------------------- */
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
/* -------------------------------------------------------------------------- */
$Goto = "Register.php";
$Content = '';
/* -------------------------------------------------------------------------- */
$FormContent = '';
$arr = array(0 => 'no', 1=>'yes');


//$FormContent .= 'Do you wish to save your configuration?<br />';
//$FormContent .= CreateDropDown("save_config", $arr);
$FormContent .= 'Configuration Name (If not saving, leave blank). <br />';
$FormContent .= '<input type="text" name="savename" />';

$FormContent .= StickyPost();

/* -------------------------------------------------------------------------- */
$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Assign Networks to VMs", $Content));
echo($Content);


?>

<!--?php include('vlab-footer.inc'); ?-->
