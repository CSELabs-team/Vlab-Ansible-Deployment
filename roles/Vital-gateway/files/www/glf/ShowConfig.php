<?php include('vlab-header.inc'); ?>

<?php

include_once 'include/framework.php';
include_once 'include/DBI.php';
include_once 'include/API.php';
include_once 'include/SEC.php';

$t = 'vlab_interim';

DBConnect();
DBBegin();

$Content = '';


//$ConfigID = $_POST['ConfigID']; // TODO sanitize this 
$ConfigID = GetNumFromPost('ConfigID'); // TODO sanitize this 
$Class    = GetNumFromPost('ClassID');

ShowConfig($ConfigID, $Class);

function ShowConfig($CID, $Class) {
    global $t;
    unset($_POST);
    $q = "SELECT post_data FROM $t.section_configs WHERE id=$CID;";
    //writeln($q);
    $ans = HailMaryDB($q);
    $objArr = array();
    $count = 0;

    while ($obj = pg_fetch_object($ans)) { 
        $objArr[$count] = $obj;
        $count ++;
    }
    if ($count > 1) 
        die ("<h1>Error: two configs at the same time? Inconceivable!</h1>");

    $o = $objArr[0];
    $configstr = unserialize($o->post_data);
    $configstr['ClassID'] = $Class;
    $configstr['Restore'] = 'true'; 
    print("ConfigSTR:");
    //print_r($configstr);
    $_POST = $configstr;
    //$_POST['Restore'] = 'true';
    $CheckCompleted 
        = "<form action='ShowConfigList.php' method='post' name='RegForm'>\n" 
        .StickyPost(). 
        //"<input type='hidden' name='Restore' value='true'>\n".
        "</form> <script language='javascript' type='text/javascript'>\n".  
        "document.RegForm.submit(); </script>\n" .  
        "Showing configuration...".  
        "<noscript><input type='submit' value='Check'></noscript>';";
    writeln($CheckCompleted);
}


DBCommit();
DBClose();


?>
<?php include('vlab-footer.inc'); ?>
