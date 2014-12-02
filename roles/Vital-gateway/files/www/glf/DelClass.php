<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/DBI.php';

DBConnect();
$t = "vlab_interim";

function TellDB($query) {
    global $DBconnection;
    //writeln("TestDB DBconnection = $DBconnection");
    //writeln("class" . get_class($DBconnection));
    $dbResult = pg_query($DBconnection, $query)
        or die ('Query failed: '.pg_last_error());
    return($dbResult);
}

function GetRows($query) {
    $answer = TellDB($query);
    //writeln("query = $query");
    $rows = Array();
    $i = 0;
    while ($row = pg_fetch_assoc($answer)) {
        $rows[$i] = $row;
        $i += 1;
    }
    return($rows);
}

function GetAvailClasses() {
    global $t; 
    $q = "SELECT id, name FROM $t.class WHERE deletep = false;";  
    $ans = GetRows($q);
    $droplist = array();
    $sz = count($ans);
    for ($i=0; $i<$sz; $i++) {
        $tmp = $ans[$i];
        $key = $tmp['id'];
        $val = $tmp['name'];
        $droplist[$key] = $val;
    }
    //print_r($droplist);
    return($droplist);
}

$Goto = "DelClassFromDB.php";

$Content = '';

$Content .= H3("Select the Class You <br> Want To Delete <br>(VMs and student Networks will also be deleted)");
$Content .= Line(WebLn("Select Class: "));
//$FormContent = CreateDropdown("ClassID", GetAllClasses());
$FormContent = CreateDropdown("ClassID", GetAvailClasses());

$FormContent .= StickyPost();

$Content .= Form($Goto, "POST", $FormContent);

//echo(Page("Delete Class", $Content));
echo($Content);
DBClose();

?>

<?php include('vlab-footer.inc'); ?>
    
