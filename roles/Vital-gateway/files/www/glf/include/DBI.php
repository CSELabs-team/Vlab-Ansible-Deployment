<?php

include_once 'conf.php';
/* this should be changed to a select */

$connstr = 
" host=".$config['db_host'].
" dbname=".$config['db_name'].
" user=".$config['db_user'].
" password=".$config['db_pass'];

$DBconnection;

$VncBase = "vlab_interim.vnc_base";

/* TODO: move this to debug.api */
/*
function Querify($table, $dict) {
	$columns = '';
	$values  = '';
	foreach($_POST as $key => $value)
	{
		$columns 
	}	

}
 */

#$Debug = true;
$Debug = false;

function InsertDB($table, $dict)
{
	global $Debug;
	global $DBconnection;
	if ($Debug) 
	{ 
		writeln("InsertDB->"); 
		print_r($dict); 
		writeln("");
	}
	else 
	{
		//writeln("InsertDB->$table"); 
        //writeln("InsertDB::dict: ");
		//print_r($dict); 
		//writeln("");
		//global $connstr;
		//$dbconn = pg_connect($connstr);
		$res = pg_insert($DBconnection, $table, $dict) 
			or die ('Insert failed: '.pg_last_error());
        //writeln("InsertDB->$table success!");
	}
}

function HailMaryDB($query) 
{
    global $DBconnection;
    $dbResult = pg_query($DBconnection, $query)
        or die ('Query mailed: '.pg_last_error());
    return($dbResult);
}


/* fetches 2 columns and returns them as array full of (col1=>col2) */
function GetTwoColumns($table, $col1, $col2)
{
	//echo "GTC:".$col1.",".$col2;
	//global $connstr;
	global $DBconnection;
	$res = array();
	//$dbconn = pg_connect($connstr);
	$query = 'select '.$col1.', '.$col2.' from '.$table.';';
	$result = pg_query($DBconnection, $query) 
		or die ('Query failed: '.pg_last_error());
	$X = '';
	$Y = '';

	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
	{
		foreach ($line as $key => $value)
		{
			//print $key."=>".$value;
			if 	($key == $col1) $X = $value;
			else if ($key == $col2) $Y = $value;
		}
		$res[$X] = $Y;
	}
	//pg_close($dbconn);
	return($res);
}

function variable($varname, $var) { echo($varname.": ".$var."<br>"); }

/*
function VMnameBaseToConfTemplate($VMnameBase, $course)
{
	$TemplateName=DBQuery("select conf_template from vlab_interim.vnc_base where vm_name_base='".$VMnameBase."' and course='".$course."'");
	$VNCbase=DBQuery("select vnc_base from vlab_interim.vnc_base where vm_name_base='".$VMnameBase."' and course='".$course."'";
	
		
}
 */

function DBBegin()
{
    global $DBconnection;
    pg_query($DBconnection, "BEGIN");
}

function DBCommit()
{
    global $DBconnection;
    pg_query($DBconnection, "COMMIT");
}

function DBConnect()
{
	global $connstr;
	global $DBconnection;

	$DBconnection = pg_connect($connstr);
}

function DBClose()
{
	global $DBconnection;
	pg_close($DBconnection);
}

function DBQuery($query)
{
	global $DBconnection;
	//global $connstr;
	//$res = array();
	$result = pg_query($DBconnection, $query) 
		or die ('Query failed: '.pg_last_error());
	$ret = array(); 
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
	{
		foreach ($line as $key => $value) array_push($ret, $value);
	}
	//pg_close($dbconn);
	return($ret);
}

function GetVNCBaseRows(){
     $q ="select vnc_base from vlab_interim.vnc_base order by vnc_base;";
     return(DBQuery($q));
}


/*
function ClassIDtoClassName($ClassID)
{
	$Q = "SELECT class from vlab_interim.class where id='".$ClassID."'";
	$ans = DBQuery($Q);
	return($ans[0]);
}

*/

/* TODO: make sure this can only return one value. */
function GetOneItem($table, $ans, $col, $target)
{
	//print("<hr>");
	$Q="SELECT ".$ans." FROM ".$table." WHERE ".$col."='".$target."';";
	//print("GetOneItem: ".$Q);
	$ret = DBQuery($Q);
	//print_r("GetOneItem: ".$ret." size: ".sizeof($ret)."<br>");
	//print_r($ret[0]);
	//print("<hr>");
	return($ret[0]);
}

function GetMax($table, $column)
{
	$ans=(DBQuery("SELECT max(".$column.") FROM ".$table.";"));
	return($ans[0]);
}


?>


