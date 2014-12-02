<?php include('vlab-header.inc'); ?>
			

<h3>Enter the Following Information to Create a New Base Image</h3>
<form name="input" action="MakeImage.php" method="POST">
Base Image<sup>*</sup>: <br><select name="image_file">
<option value="">- Select Image -
<?php

$imagedir = '/home/vmdsk/base_images';
$dirPath = dir("$imagedir");
while (($file = $dirPath->read()) !== false)
{
   if( $file != "." && $file != ".." && end(explode(".", $file)) == "img" ) {
   	echo "<option value=\"" . trim($file) . "\">" . $file . "\n";
   }
}
$dirPath->close();
?>
</select><br><p>
Friendly Name: <br><input type="text" name="friendly_name"  /><br /><p>
Memory Size: <br><select name="memsize">
<option value="32">32 MB</option>
<option value="64">64 MB</option>
<option value="128">128 MB</option>
<option value="256">256 MB</option>
<option value="512">512 MB</option>
<option value="1024">1024 MB</option>
</select><br><p>
CPU Count: <br><select name="vcpu">
<option value="1">1</option>
<option value="2">2</option>
<option value="4">4</option>
</select><br><p>
<input type="submit" value="SUBMIT"  />
</form>

<sup>*</sup>base images located in <code><?php echo $imagedir; ?></code>
<?php include('vlab-footer.inc'); ?>


