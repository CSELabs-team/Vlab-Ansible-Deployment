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

function DisplayUserVMs($user_id) {
    global $DBconnection;

    $result = pg_execute($DBconnection, "vm_list", array($user_id)) or die ('vm_list query failed: '.pg_last_error());

    printLn("<b>Assigned VMs:</b><p><table width=100%>");
    while ($row = pg_fetch_row($result)) {
        printLn("<tr><td align='left'>".$row[0]."</td>");
        if ($row[1] == 10) {
		printLn("<td align='left'><em>running</em></td></tr>");
	}
	elseif ($row[1] == 11) {
		printLn("<td align='left'><em>stopped</em></td></tr>");
	}
	else {
		printLn("<td align='left'><em>other</em></td></tr>");
	}
    }
    printLn("</table>");

}

function resetPwd($username) {
    global $DBconnection;

    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $newpwd = '';
    $length=8;
    for ($p = 0; $p < $length; $p++) {
        $newpwd .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    
    $result = pg_execute($DBconnection, "pwd_reset", array("$newpwd", "$username")) or die ('pwd_reset query failed: '.pg_last_error());

}

function DisplayUserInfo() {
    global $DBconnection;

    $username = GetStringFromPost('username');

    $resetpwd = GetStringFromPost('resetpwd');

    if ($resetpwd == 1) {
	resetPwd($username);
    }

    $result = pg_execute($DBconnection, "acct_info", array("$username")) or die ('acct_info query failed: '.pg_last_error());

    $row = pg_fetch_row($result);
    $user_id = $row[0];
    $user_pass = $row[1];

    $result = pg_execute($DBconnection, "user_info", array($user_id)) or die ('user_info query failed: '.pg_last_error());

    $row = pg_fetch_row($result);
    $fname = $row[0];
    $lname = $row[1];
    $email = $row[2];

    $result = pg_execute($DBconnection, "sftp_info", array($user_id)) or die ('user_info query failed: '.pg_last_error());

    $row = pg_fetch_row($result);
    $sftp_user = $row[0];
    $sftp_pass = $row[1];


    printLn("<div align='left'>");
    printLn("<b>Username:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$username</em><p>");
    printLn("<b>Password:</b><br>");
    printLn("<table><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<em>$user_pass</em></td><td>");
    printLn("<form name='input' action='GetUserInfo.php' method='POST'><input type='hidden' name='username' value='$username'><input type='hidden' name='resetpwd' value='1'><input type='submit' value='reset'/></form></td></table><p>");

    printLn("<b>SFTP Username:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$sftp_user</em><p>");
    printLn("<b>SFTP Password:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$sftp_pass</em><p>");
    printLn("<b>Name:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$fname $lname</em><p>");
    printLn("<b>Email:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>$email</em><p>");

    DisplayUserVMs($user_id);

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

function BuildPrepared() {
  global $DBconnection;

  if (CheckPreparedExist("acct_info") == false) {
    $result = pg_prepare($DBconnection, "acct_info", 'select user_id, user_pass from vlab_interim.user where user_name=$1') or die ('acct_info prep failed '.pg_last_error());
  }

  if (CheckPreparedExist("pwd_reset") == false) {
    $result = pg_prepare($DBconnection, "pwd_reset", 'update vlab_interim.user set user_pass=$1 where user_name=$2') or die ('pwd_reset prep failed '.pg_last_error());
  }

  if (CheckPreparedExist("user_info") == false) {
    $result = pg_prepare($DBconnection, "user_info", 'select fname, lname, email from vlab_interim.email where user_id=$1') or die ('user_info prep failed: '.pg_last_error());
  }

  if (CheckPreparedExist("sftp_info") == false) {
    $result = pg_prepare($DBconnection, "sftp_info", 'select sftp_user, sftp_pass from vlab_interim.sftp where user_id=$1') or die ('sftp_info prep failed: '.pg_last_error());
  }

  if (CheckPreparedExist("vm_list") == false) {
    $result = pg_prepare($DBconnection, "vm_list", 'select vm_name, current_state from vlab_interim.vm_resource where vm_id in (select vm_id from vlab_interim.reflector where reflector_id in (select reflector_id from vlab_interim.user_reflector where user_id = $1))') or die ('vm_list prep failed: '.pg_last_error());
  }


}

/* main loop ---------------------------------------------------------------- */
DBConnect();

BuildPrepared();
printLn("<h3>User Information</h3>");
DisplayUserInfo();

DBClose();

?>
<?php include('vlab-footer.inc'); ?>
~
