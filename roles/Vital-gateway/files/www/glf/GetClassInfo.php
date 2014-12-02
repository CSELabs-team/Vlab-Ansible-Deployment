<?php include('vlab-header.inc'); ?>
<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'include/DBI.php';
include_once 'conf.php';

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
printLn("");
printLn("Debug above");
printLn("");
*/

/* -------------------------------------------------------------------------- */
//TODO: put this in a conf file with the rest
$t="vlab_interim";
function printLn($line) { print ($line."\n");}

function DisplayUserInfo() {
    global $DBconnection;

    $class_id = GetNumFromPost('ClassID');

    $result = pg_execute($DBconnection, "class_info", array($class_id)) or die ('acct_info query failed: '.pg_last_error());

    $row = pg_fetch_row($result);
    $class_name = $row[0];
    $class_desc = $row[1];
    $regcode = $row[2];

    $result = pg_execute($DBconnection, "counter_info", array("$class_name")) or die ('user_info query failed: '.pg_last_error());

    $row = pg_fetch_row($result);
    $count = $row[0];
    $base = $row[1];
    $size = $row[2];

    $current_count = $count - $base ;
    $high_id = $base + $size;

    printLn("<div align='left'>");
    printLn("<b>Class Name:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$class_desc</em><p>");
    printLn("<b>Registration Code:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$regcode</em><p>");
    printLn("<b>Class size:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$size</em><p>");
    printLn("<b>Currently Registered:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$current_count</em><p>");
    printLn("<b>Student VLAB ID Range:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>student$base - student$high_id</em><p>");
    printLn("</div>");

}

function CheckPreparedExist($prep_name) {

  try{
    $qrParamExist = pg_query_params("SELECT name FROM pg_prepared_statements WHERE name = $1", array($prep_name));
    if($qrParamExist){
        if(pg_num_rows($qrParamExist) != 0){
		return true;
        }else{
		return false;
        }

    }else{
        throw new Exception('Unable to query the database.');
    }
  }catch(Exception $e){
    echo $e->getMessage();
  }
}

function CreateStatement($prep_name, $statement) {
  global $DBconnection;

  if (CheckPreparedExist("$prep_name") == false) {
    $result = pg_prepare($DBconnection, "$prep_name", $statement) or die ('acct_info prep failed '.pg_last_error());
  }

}

function BuildPrepared() {

  CreateStatement("class_info", 
		  'select class, name, registration_code from vlab_interim.class where id=$1');

  CreateStatement("counter_info", 
		  'select count, base, size from vlab_interim.counter where name=$1');

}

/* main ---------------------------------------------------------------- */
DBConnect();

BuildPrepared();
printLn("<h3>Class Information</h3>");
DisplayUserInfo();

DBClose();

?>
<?php include('vlab-footer.inc'); ?>
~
