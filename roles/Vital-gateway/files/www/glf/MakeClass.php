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
writeln("");
writeln("Debug above");
writeln("");
 */

/* -------------------------------------------------------------------------- */
//$Goto = "postinfo.php";
$Content = '';
//$FormContent = '';

//TODO: put this in a conf file with the rest

$t="vlab_interim";

$buffer = 10; // make this 0 if you dare


function TellDB($query) {
    global $DBconnection;
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

function ExistsAlreadyP($coursenum) {
    global $t;
    global $Content;
    $q = "SELECT deletep FROM $t.class WHERE class = '$coursenum';";
    $ans = GetRows($q);
    $sz  = count($ans);
    //writeln("sz = $sz");
    if ($sz == 0) return(false);
    else return(true);
}

function GetBase() {
    global $buffer; 
    global $t;
    global $NumStudents;
    $q = "SELECT base, base + size as countermax FROM $t.counter order by base+size;";
    $ans = GetRows($q);
    $sz = count($ans);
    //print_r($ans);
    $CounterMax = 'countermax'; 
    $CounterMin = 'base'; 
    $base = -1;
    
    for($x = 0; $x < $sz; $x++) {

        $cur = $ans[$x][$CounterMin];
        $bef = ($x==0) ? 10 : $ans[$x-1][$CounterMax];
        $fits = ($cur - $bef)
            > ($NumStudents + $buffer) ? true : false;
        if ($fits) {
            $base = $bef + $buffer;
            return($base);
        }
    }
    $base = $ans[$sz-1][$CounterMax] + $buffer;
    return($base);
}

function MakeNewClass() {
    global $t;
    global $NumStudents;
    global $ClassName;
    global $CourseNum;
    global $buffer;
    $RegisCode = rand(1000,32767) * rand(1000,32776);
    $cols   = "(class, name, uid, registration_code, enabled)";
    $values = "('$CourseNum', '$ClassName', 1234, $RegisCode, true)";
    $q = "INSERT INTO $t.class $cols VALUES $values;";
    //writeln("$q");
    TellDB($q);
    $cols   = "(count, name, base, size)";
    $base   = GetBase();
    //$count  = $base + $NumStudents - $buffer; 
    $values = "($base, '$CourseNum', $base, $NumStudents)";
    $q = "INSERT INTO $t.counter $cols VALUES $values;";  
    //writeln("$q");
    TellDB($q);
}



/* main loop ---------------------------------------------------------------- */
DBConnect();

$NumStudents = GetNumFromPost('StudentNum');
//$Instructor = GetNumFromPost('InstID');
$ClassName = NoXSS($_POST["ClassName"]);
$CourseNum = $_POST["CourseNum"];

if (ExistsAlreadyP($CourseNum)) {
    $Content .= "This course already exists.  Please delete it first.";
} else {
    DBBegin();
    MakeNewClass();
    $Content .= H3("DB updated, course added.");
    DBCommit();
}

/* -------------------------------------------------------------------------- */
echo($Content);

//writeln("calling db close!");
DBClose();

?>
<?php include('vlab-footer.inc'); ?>
