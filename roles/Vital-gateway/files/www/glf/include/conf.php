<?php
/*?TODO here is where you configure how many students max the trade off is
 * more max students, less vms you can use, due to port # restrictions */
$MAX_NUM_STUDENTS = 500;

$config = array();

//A directory with read/write permissions
$config['vnc_temp_dir'] = '/tmp/vlab';

$config['xenserver'] = 'Vlab-xen1';

$config['db_host'] = 'localhost';

//The name of the database
$config['db_name'] = 'vlab';

$config['db_user'] = 'postgres';

$config['db_pass'] = 'postgres';

?>
