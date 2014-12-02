<?php include('vlab-header.inc'); ?>

<?php
include_once 'include/framework.php';
include_once 'include/API.php';
include_once 'include/SEC.php';
include_once 'conf.php';

$default_xen_server='Vlab-xen1';

/* Debug: ------------------------------------------------------------------- */
/*
print_r($_POST);
writeln("");
writeln("Debug above");
writeln("");
 */

/* -------------------------------------------------------------------------- */
//$Goto = "postinfo.php";
$Content = '<h2>Done. VMs are being created.</h2>';

//$FormContent = '';

//TODO: put this in a conf file with the rest

function GetVncBase()
{
    // need to lock DB so that there is no conflict between multiple users creating a class.
    global $MAX_NUM_STUDENTS;
    $holes = false;
    $rows = GetVncBaseRows();
    $nrows = count($rows);

    if ($nrows == 0) { //Empty Database
        $ret = $MAX_NUM_STUDENTS;
    } elseif ($nrows == 1) { // only one classs in Database If row 0 >= 500 return 500
        if ($rows[0] > $MAX_NUM_STUDENTS)
                $ret = $MAX_NUM_STUDENTS;
            else
                $ret = $rows[0] + $MAX_NUM_STUDENTS;
    } elseif ($nrows > 1) { // more than two rows. Look for "holes" in array ned to fix for hole in beginning
        for($i = 1; $i<$nrows; $i++) //
             if (($rows[$i]-$rows[$i-1]) > $MAX_NUM_STUDENTS) {  // found hole.
                                                                   //why factor of 2?
                                                                   //try >= $MAX
                $ret = $rows[$i-1] + $MAX_NUM_STUDENTS;
                $holes = true;
                break;

             }
    }
    // no holes add to end of array
    if (!$holes) $ret = $rows[$nrows-1]+$MAX_NUM_STUDENTS;

    return ($ret);
}

class VMSet
{
	var $ID, $MyClass, $HowMany, $VncBase, $VmResource;	

	function VMSet($ID)
	{

		$this->ID 	= $ID;
		$this->MyOS 	= GetOS($this->ID);
		$this->MyClass  = GetClass();
		$this->HowMany  = GetClassSize($this->MyClass);

		/* set up vlab_interim.vnc_base */
		$this->VncBase("vm_name_base",     $this->GetNameBase());	
		$this->VncBase("conf_template",    $this->GetConfTemplate());
		$this->VncBase("course",           $this->MyClass);
		$this->VncBase("vm_friendly_name", $this->GetFriendlyName());
		$this->VncBase("backing_file",     $this->GetBackingFile());
		$this->VncBase("memory", 	   $this->GetMemory());
#		$this->VncBase("vcpu", 	   	   $this->GetVcpu());
		$this->VncBase("createdp", 	   'false');
		$this->VncBase("reimage_file",     $this->GetReimageFile());  
        $this->VncBase("vnc_base",          GetVncBase());
		InsertDB("vlab_interim.vnc_base", $this->VncBase);

		/* set up vlab_interim.vm_resource */
		$this->Resource("requested_state", SetInitialState());   
		$this->Resource("current_state",   SetInitialState()); 
		// timestamp is auto
		// vm_name we need to do in the loop
		// username is unused
		// vm_id is filled in later
		// vlan_id is obsolete
		// vnc_port is filled in later
		// notes are empty
		$this->Resource("memory", $this->VncBase["memory"]);
#		$this->Resource("vcpu", $this->VncBase["vcpu"]);
		$this->Resource("lb_mode", "1");
		$this->Resource("vm_friendly_name", 
			$this->VncBase["vm_friendly_name"]);
		$this->Resource("inprocess", "0");
		$this->Resource("reimage_file", 
		     	$this->VncBase["reimage_file"]); 
		$this->Resource("disabled", "1");
        $this->Resource("vlan_id", "11"); //TODO fix this

		//$this->Debug();
	}
	function VncBase ($key, $value) { $this->VncBase[$key] 	  = $value; }
	function Resource($key, $value) { $this->VmResource[$key] = $value; }
	function GetFriendlyName() 
	{ 
		return(OSidToFriendlyName(GetOSid($this->ID))); 
	} 
	function GetBackingFile()
	{
		return(OSidToBackingFile(GetOSid($this->ID)));
	}
	function GetMemory()
	{
		return(OSidToMemory(GetOSid($this->ID)));	
	}
#	function GetVcpu()
#	{
#		return(OSidToVcpu(GetOSid($this->ID)));	
#	}
	function GetReimageFile()
	{
		$course = $this->VncBase["course"];
		$dir    = $course.'-clean';
		$file   = $this->VncBase["vm_name_base"].'-'.$course.'.qcow';
		return($dir.'/'.$file);
	}
	function GetXenServer()
	{
		$default_xen_server="Vlab-xen1";
		return($default_xen_server);
		#return("Vlab-xen1");
	}	

	function GetNameBase() { return(OSidToNameBase(GetOSid($this->ID))); }
	function GetConfTemplate()
	{
		return(OSidToConfTemplate(GetOSid($this->ID)));
	}
	function WriteResourcesToDB() 
	{
		$this->Resource("disk_image", NULL);
		$base = GetBaseCount();
		//writeln("BaseCount = $base");
		$maxVMs  = GetClassSize();
		//writeln("maxVMs = $maxVMs");
		//writeln("ID = $this->ID");
		/* yes i mean to start at 1- there isn't a vm0 */
		for ($i=1; $i<=$maxVMs; $i++) 
		{
			$StudentNum = $i + $base;
			$this->Resource("createdp", 'f');
			$this->Resource("xen_server", $this->GetXenServer());   
			$VmName =$this->VncBase["vm_name_base"].($StudentNum);
			$DiskImg=$VmName.".qcow";
			$this->Resource("vm_name",    $VmName);
			$this->Resource("disk_image", $DiskImg);
#			$this->Resource("vcpu", $this->VncBase["vcpu"]);
            $this->Resource('vnc_port',   
                $this->VncBase['vnc_base'] + $i);
            $this->Resource('username', "student$StudentNum");
			//writeln("<hr>");
			//print_r($this->VmResource);
			/* right here we need to pass this to the object 
			 * that handles each and every nic for the vif table */
			//writeln('$i = '.$i);
			InsertDB("vlab_interim.vm_resource", $this->VmResource);
			$VMID = VmNameToVmID($VmName);
			$count = GetNumNICs($this->ID);
			for ($j=1; $j<=$count; $j++)
			{
				//writeln("NIC#$j, ID#$this->ID VMID#$VMID S#$StudentNum");
				/* since we started with VM1 not VM0 */
				//l$OffsetFromBase= $i-1;
				$Nic = new Nic( 
					$j, $this->ID, $VMID, 
					$StudentNum, $i, $this->MyClass);
			}	
		}
		$this->Resource("disk_image", NULL);
	}

	function Debug()
	{
		writeln("I am set #".$this->ID." pop: ".$this->HowMany);
		writeln("we run OS: ".$this->MyOS);
		writeln("inserting this to vncbase: ");
		print_r($this->VncBase);
		writeln("");
		writeln("inserting this into vm_resources: ");
		print_r($this->VmResource);	
	}
}

function Hexify($num) { return(base_convert($num, 10, 16)); }
/* this is the table we care about:
 * vlab=# select * from vif ;
 *  vm_id | vif_mac | vif_brdg_id | vif_name 
 *  -------+---------+-------------+----------
 *  (0 rows)
 *  
 *
 *  vm_id comes from the vm_resources table (autogenned).
 *  
 *
 * */
class Nic
{
	var $VIF, $NicNUM, $SetNUM, $VMID, $StudentNUM, $MachineNUM, $Class;
	function Nic($NicNUM, $SetNUM, $VMID, $StudentNUM, $MachineNUM, $Cl)
	{
		/* TODO: implement this  */
		$this->NicNUM 	  = $NicNUM;
		$this->SetNUM     = $SetNUM;
		$this->VMID       = $VMID;
		$this->StudentNUM = $StudentNUM;
		$this->MachineNUM = $MachineNUM;
		$this->Class 	  = $Cl;


		//$this->iface("vm_id",  	    $this->MachineNUM);
		$this->iface("vm_id",  	    $this->VMID);
		$this->iface("vif_mac",	    $this->GetVifMAC()); 
		$this->iface("vif_brdg_id", $this->GetVifBrdgID());
		$this->iface("vif_name",    $this->GetVifName());
		
		InsertDB("vlab_interim.vif", $this->VIF);
	}
	function iface ($key, $value) { $this->VIF[$key] 	  = $value; }
	function GetVifMAC()	/* */
	{
		$byte1to2 = As2Bytes(Hexify($this->StudentNUM));
		$byte3to4 = As2Bytes(Hexify($this->VMID));
		$byte5    = AsByte(Hexify($this->NicNUM));
        //writeln("Snum = $this->StudentNUM, VMID = $this->VMID, NicNUM = $this->NicNUM");
        //writeln("Snum = $byte1to2, VMID = $byte3to4, NicNUM = $byte5");
		return("02:$byte1to2:$byte3to4:$byte5");	
	}
	function GetVifBrdgID()
	{
		//writeln("Set#$this->SetNUM Nic#$this->NicNUM S#$this->StudentNUM");
		$x = GetNetForNic($this->SetNUM, $this->NicNUM);

		//writeln("GetNetForNic($this->SetNUM, $this->NicNUM)=$x");

		$networkID = GetNetForNic($this->SetNUM, $this->NicNUM);

		if ($networkID == 0) 
		{
			//writeln("Net-$this->Class");
			return(BrdgNameToBrdgID("Net-$this->Class")); 	
		}
		else
		{
			$multiplier = $networkID - 1;
			$multiplier *= 2000;
			return(BrdgNameToBrdgID('Net-'.($multiplier + 
				$this->StudentNUM)));
		}
	}
	function GetVifName() { return("$this->VMID-$this->NicNUM"); }
}

class NetSet
{
	var $Offset, $VBridge;

	function NetSet($Off)
	{
		$this->Offset = $Off;
		//$this->Bridge("brdg_name", 'Net-'.GetClass());	
		//InsertDB("vlab_interim.vbridge", $this->VBridge);
		//unset($this->VBridge["brdg_name"]);
		$ClassSize = GetClassSize();
		$BaseCount = GetBaseCount();

		for ($i=1; $i<=$ClassSize; $i++)
		{
			$Name = $this->Offset + $i + $BaseCount;
			$this->Bridge("brdg_name", 'Net-'.$Name);
			InsertDB("vlab_interim.vbridge", $this->VBridge);	
		}	
	}
		
	function Bridge  ($key, $value) { $this->VBridge[$key] 	  = $value; }
}


/* main loop ---------------------------------------------------------------- */
DBConnect();
DBBegin();

$t='vlab_interim';

/*
writeln("Post[Restore] = ".$_POST['Restore']);
writeln("arr key=".array_key_exists($_POST['Restore']));
 */

if (array_key_exists('Restore', $_POST) and 
    (GetStringFromPost('Restore')=='no')) {
    //writeln("saving to section_configs");
    $querries = array();
    $SaveStr = serialize($_POST);
    $SaveName = GetStringFromPost('savename');
    $pcols = '(post_data, saved_name)';
    $pstmt = "INSERT INTO $t.section_configs $pcols VALUES ($1, $2)";
    $querries[0] = "PREPARE savestmt (varchar, varchar) AS $pstmt";
    //writeln($querries[0]);
    //HailMaryDB($querries[0]);
    $querries[1] = "EXECUTE savestmt ('$SaveStr', '$SaveName')";
    //writeln($querries[1]);
    //HailMaryDB($querries[1]);
    array_map('HailMaryDB', $querries);
    $Content .= "<br>Config has been saved.";
    unset($_POST['Restore']);
}

$VMTemplates = array();

/* this section is for things that are class wide */
/* for example we only put in a class net once into the vbridge table */
$tmpArr = array();
$tmpArr["brdg_name"] = 'Net-'.GetClass();
InsertDB("vlab_interim.vbridge", $tmpArr);
unset($tmpArr["brdg_name"]);

function BrdgNameToBrdgID($name)
{
	return(GetOneItem(
		"vlab_interim.vbridge",
		"brdg_id",
		"brdg_name",
		$name));
}
/* start first with the nets in order to populate vlab_interim.vbridge */

/* yes i really mean to start at 0 here */
for ($i=0; $i<GetNumNets(); $i++)
{
	//writeln("In net loop #".$i); 
	$VMNets[$i] = new NetSet($i*2000);
}

/* at this point vlab_interim.vbrige is all populated now we can do 
 * the sets of vms */

/* yes i really mean to start at 1 here */
for ($i=1; $i<=GetNumVms(); $i++) 
{ 
	$VMTemplates[$i] = new VMSet($i); 
	$VMTemplates[$i]->WriteResourcesToDB();
}


//$FormContent .= StickyPost();

/* -------------------------------------------------------------------------- */
//$Content .= Form($Goto, "POST", $FormContent);
//echo(Page("Write to DB.", $Content));
echo($Content);

DBCommit();
DBClose();

?>
<?php include('vlab-footer.inc'); ?>
