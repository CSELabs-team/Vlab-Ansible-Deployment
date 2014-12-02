<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';

/* Debug: ------------------------------------------------------------------- */
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
/* -------------------------------------------------------------------------- */


/* -------------------------------------------------------------------------- */
$Content .= Form($Goto, "POST", $FormContent);
echo(Page(CHANGE_ME, $Content));


?>
