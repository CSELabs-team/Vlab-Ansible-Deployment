<?php

$config = array();
//A directory with read/write permissions
$config['vnc_temp_dir'] = '/tmp/vlab';
$config['db_host'] = 'localhost';
//The name of the database
$config['db_name'] = 'vlab';
$config['db_user'] = 'postgres';
$config['db_pass'] = 'postgres';
// confirmation email
$config['confirm_email_name'] = '<SENDER NAME FOR CONFIRMATION EMAIL>';
$config['confirm_email_addr'] = '<REPLY EMAIL ADDR FOR CONFIRMATION EMAIL>';
//confirmation email
$config['confirm_email_name'] = 'NAME USED IN EMAIL HEADER';
$config['confirm_email_addr'] = 'EMAIL ADDR USED FOR CONFIRMATION';
// admin web directory relative to the vital webroot, or absolute path
$config['glf_dir'] = './glf';
?>
