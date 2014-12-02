<?php

if ( !(include_once ('config.php')) ) {
  echo 'Must supply config file'.(include_once ('config.php'));
  exit();
}


require_once ('log4php/main/php/Logger.php');

Logger::configure('appender_file.properties');

?>