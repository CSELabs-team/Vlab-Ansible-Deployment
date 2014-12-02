<?php
include_once 'DBI.php';

$ext = ".qcow";
/*
function SanityCheck()
{ 
	$Content = ''; 
	$dbconn = pg_connect("host=localhost dbname=vlab user=postgres password=pq10a129"); 
	$query = 'SELECT * FROM vlab_interim.machines'; 
	$result = pg_query($query) or die ('Query failed: ' . pg_last_error());
	
	$Content .= "<table>\n"; 
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) 
	{ 
		$Content .= "\t<tr>\n"; 
		foreach ($line as $col_value) 
		{ 
			$Content .= "\t\t<td>$col_value</td>\n"; 
		} 
		$Content .= "\t</tr>\n"; 
	} 
	$Content .= "</table>\n"; 
	return($Content);
}
 */

function writeln($str) { echo($str."<br>"); }

/*
function GetAllClasses()
{ 
	return(GetTwoColumns("vlab_interim.class", "id", "name")); 
	//return(GetTwoColumns("vlab_interim.class", "name", "class"));
}
 */
function NoXSS($string) {
    $len = strlen($string);
    $ret = '';
    //print("size: $len");
    for ($i=0; $i<$len; $i++) {
        $char = $string[$i];
        if (($char == '<') or
            ($char == '>') or
            ($char == '%') or
            ($char == '&') or
            ($char == "'") or
            ($char == '"') or
            ($char == ';') or
            ($char == '-')) { $ret .= 'q'; }
        else ($ret .= $char);
        //print("$string[$i]\n");
    }
    return($ret);
}


$t = "vlab_interim";

function GetAllConfigs()
{
    global $t;
    $q = "SELECT id, to_char(created, 'DD Mon YYYY HH24:MI') as created, saved_name FROM $t.section_configs";
    $answer = HailMaryDB($q);
    $res = Array();
    $name = '';
    $date = '';
    $line;

    /*
    while ($line = pg_fetch_array($answer, null, PGSQL_ASSOC)) {
        foreach ($line as $key => $value) {
            if ($key == 'created') $date = $value;
            else if ($key == 'saved_name') $name = NoXSS($value);
        }
        $res[$date] = "$name: $date";
    }
     */

    while ($obj = pg_fetch_object($answer)) {
        if ($obj->saved_name=='') {
            $name = $obj->created;
        } else { 
            $name = "$obj->saved_name : $obj->created";
        }
        $value = $obj->id;
        $res[$value] = $name;
    }
    return($res);
}

function GetAllClasses()
{
    global $t;
    $q = "SELECT id, name from $t.class WHERE deletep = false;";
    //writeln($q);
    $answer = HailMaryDB($q);
    $res = Array();
    $X = '';
    $Y = '';
    $line;
    while ($line = pg_fetch_array($answer, null, PGSQL_ASSOC)) 
    { 
        foreach ($line as $key => $value) 
        {
            if ($key == "id") $X = $value;
            else if ($key == "name") $Y = $value;
        }
        $res[$X] = $Y;
    }
    return($res);
}

/* uses disk_images, returns all the oses we have set up */
function GetOSes()
{ 
	return(GetTwoColumns( 
		"vlab_interim.disk_images", 
		"id", 
		"use")
	);
}

function OSidToOS($OSid)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"use",
		"id",
		$OSid));
}


function IsClassInVncBase($course)
{
	$Q="SELECT course FROM vlab_interim.vnc_base WHERE course='".$course."'";
	$ret = DBQuery($Q);
	//print_r($ret);
	//print(sizeof($ret));
	return (sizeof($ret));
}

function OSidToConfTemplate($ID)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"conf_template",
		"id",
		$ID));
}

function OSidToBackingFile($ID)
{
    return(GetOneItem(
		"vlab_interim.disk_images",
		"backing_file",
		"id",
		$ID));
}

function OSidToNameBase($ID)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"vm_name_base",
		"id",
		$ID));
}

function NameBaseFromConfTemplate($templ)
{
	return(GetOneItem(
		"vlab_interim.disk_images", 
		"vm_name_base", 
		"conf_template",
		$templ));
	/*
	$Q="SELECT vm_name_base FROM vlab_interim.vnc_base WHERE conf_template='".$templ."';";
	$ret=DBQuery($Q);
	print_r($ret);
	return($ret[0]);
	 */
}	

function FriendlyNameFromConfTemplate($templ)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"use",
		"conf_template",
		$templ));
}	

function OSidToMemory($id)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"ram",
		"id",
		$id));
}

#function OSidToVcpu($id)
#{
#	return(GetOneItem(
#		"vlab_interim.disk_images",
#		"vcpu",
#		"id",
#		$id));
#}

function OSidToFriendlyName($id)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"use",
		"id",
		$id));
}

function ClassToSize($ClassName)
{
	return(GetOneItem(
		"vlab_interim.counter",
		"size",
		"name",
		$ClassName));
}

function ClassToBaseCount($ClassName)
{
	return(GetOneItem(
		"vlab_interim.counter",
		"count",
		"name",
		$ClassName));
}

function ClassIDtoClassName($ClassID)
{ 
	return(GetOneItem(
		"vlab_interim.class",
		"class",	
		"id",
		$ClassID));	
	/*
	$Q = "SELECT class from vlab_interim.class where id='".$ClassID."'"; 
	$ans = DBQuery($Q); 
	return($ans[0]);
	 */
}

function VmNameToVmID($Name)
{
	return(GetOneItem(
		"vlab_interim.vm_resource",
		"vm_id",
		"vm_name",
		$Name));
}

function AsByte ($str)
{
        $len = strlen($str);
        if ($len > 2 || $len < 1) { die ("onebyte: $str: invalid"); }
        elseif ($len == 2)
        {
                return($str);
        }
        elseif ($len == 1)
        {
                return("0$str");
        }
}
function As2Bytes($str)
{
        $len = strlen($str);
        if ($len > 4 || $len < 1) { die("As2Bytes $str: invalid."); }
        elseif ($len <= 2) { return("00:".AsByte($str)); }
        elseif ($len > 2)
        {
                $byte0 = AsByte(substr($str, 0, 2));
                $byte1 = AsByte(substr($str, 2, 2));
                return("$byte0:$byte1");
        }
}

function test($byte0, $byte1, $byte2) { return("02:$byte0:$byte1:$byte2"); }

/*
function GetClassSize($Class)
{
	return(GetOneItem(
		"vlab_interim.counter",
		"size",
		"name",
		$Class));
}
 */

/*
function GetRam($templ)
{
	return(GetOneItem(
		"vlab_interim.disk_images",
		"RAM",
		"conf_template",
		$templ));
}
 */


/* SHELL FUNCTIONS ---------------------------------------------------------- */
function CloneQCow($VMtoClone, $VMbaseName, $start, $end)
{ 
	global $ext;
	for ($i=$start; $i <= $end; $i++) 
	{
		$src  = $VMtoClone.".qcow";
		$dest = $VMbaseName.$i.".qcow";
		copy ($src, $dest) or die("there is a problem with copy!");	
	}
}

function CreateSingleConfig($Class, $VMbaseName, $start, $end)
{
	$Q = "SELECT conf_template from vlab_interim.vnc_base WHERE vm_name_base='".$VMbaseName."' AND course='".$Class."'";
	$TemplateName = DBQuery($Q);
	print($Q);

	print("<br>");

	$Q = "SELECT vnc_base from vlab_interim.vnc_base WHERE vm_name_base = '".$VMbaseName."' AND course='".$Class."'";
	print($Q);

	$VNCbase      = DBQuery($Q);

	print_r($TemplateName);
	print("<br>");
	print_r($VNCbase);
}


function GetNumFromPost($token)
{ 
	//$ret=rtrim((ValidateNumber($_POST[$token]))); 
	$ret=filter_input(INPUT_POST, $token, FILTER_SANITIZE_NUMBER_INT);

	if ($ret == NULL) {
		//print("Invalid parameter<br>");
		return("");
	}

	return($ret); 
}

function GetStringFromPost($token)
{
	// some more filtering will be needed
	$ret=filter_input(INPUT_POST, $token, FILTER_SANITIZE_STRING);

	if ($ret == NULL) {
		//print("Invalid parameter<br>");
		return("");
	}

	return($ret);
}


function GetOSid($i)	{ return(GetNumFromPost('VM'.$i.'OS')); }
function GetOS($i)      { return(OSidToOS(GetOSid($i))); }
function GetNumNICs($i) { return(GetNumFromPost('VM'.$i.'NumNICs')); }

Function GetNetForNic($VM, $NIC) 
{ 
	return(GetNumFromPost('VM'.$VM.'NIC'.$NIC)); 
}

function GetNumVMs()    { return(GetNumFromPost('NumVMs')); }
function GetNumNets()   { return(GetNumFromPost('NumNets')); }
function GetClassID()	{ return(GetNumFromPost('ClassID')); }

function GetClass()     { return(ClassIDtoClassName(GetClassID())); }

function SetInitialState() { return(11); }

function GetClassSize() { return(ClassToSize(GetClass())); }
function GetBaseCount() { return(ClassToBaseCount(GetClass())); }


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

function RestoreClassFromDB($classID) {
    global $t;
    $q = "UPDATE $t.class SET restorep = true where id = $classID";
    DB($q);
}



?>

