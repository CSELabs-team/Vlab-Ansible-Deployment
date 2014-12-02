<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'conf.php';

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
 */


/* -------------------------------------------------------------------------- */
DBConnect();
DBBegin();

$t = "vlab_interim";

/*
function DB($query) {
 //   writeln($query);
    global $DBconnection;
    $answer = pg_query($DBconnection, $query) or die ('Query failed '.pg_last_error());
    return($answer);
} 

function DeleteClassFromDB($classID) {
    global $t;
    $q = "UPDATE $t.class SET deletep = true where id = $classID";
    DB($q);
}
 */

DeleteClassFromDB(GetClassID());

//$Goto = "postinfo.php";

$Content  = '<h2>DB is updated.</h2>';
$Name = GetClass(GetClassID());
$Content .= "$Name has been scheduled for deletion in about 20 seconds.";

//$Content = 'go <a href=https://vital.poly.edu/glf/DeleteNetworks.php>delete the student networks for this class</a> if any.';

//$FormContent = '';
//$FormContent .= StickyPost();

/* -------------------------------------------------------------------------- */
//$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Write to DB.", $Content));
echo($Content);

DBCommit();
DBClose();

?>
<?php include('vlab-footer.inc'); ?>
