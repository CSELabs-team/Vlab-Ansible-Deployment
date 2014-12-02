<?php

require_once ('config.entry.php');
require_once ('UserAuthenticator.php');



abstract class SessionConfiguration implements UserAuthenticator{
  
  abstract public function getXEN_SERVER_NAME($vm_id);
  
  abstract public function getVMName( $reflector_id );
  
  abstract public function getVMId( $reflector_id );
  
  abstract public function getReflectorIds( $username );
  
  abstract public function getReflectorPort( $reflector_id );
  
  abstract public function getReflectorPass( $reflector_id );

  abstract public function getVNCPort( $vm_id );
  
  public function getVNCTempDir(){
    global $config;
    
    return $config['vnc_temp_dir'];
  }
  
  public static function getInstance(){
    return SessionConfiguration::$instance;
  }
  
  public static function createInstance( SessionConfiguration $instance){
    SessionConfiguration::$instance = $instance;
  }
  
  private static $instance;
}

class IniSessionConfiguration extends SessionConfiguration {
  
  private $ini_data;
  
  public function SessionConfiguration(){
    $this->ini_data = parse_ini_file('vncsession.conf',true);
    
    
    $this->config =& $config;
    // echo var_dump($this->ini_data);
  }
  
  public function getUserPass( $username ){
    $ini_data = $this->ini_data;
    
    $pass = @$ini_data['user_pass'][$username];
    
    return $pass;
  }
  
  public function getXEN_SERVER_NAME($vm_id){
    return @$this->ini_data['session_xenServerName'][$vm_id];
  }
  
  public function getVMName( $relector_id ){
    return @$this->ini_data['session_vmname'][$relector_id];
  }
  
  public function getVMId( $relector_id ){
    return $relector_id;
  }
  
  public function getVMStartupScript( $vm_id ) {
    return @$this->ini_data['session_startup'][$vm_id];
  }
  
  public function getVMShutdownScript( $vm_id ) {
    return @$this->ini_data['session_shutdown'][$vm_id];
  }
  
  public function getReflectorIds( $username ){
    return @$this->ini_data['user_session'][$username];
    
  }
  
  public function getReflectorPort( $relector_id ) {
    return @$this->ini_data['session_port'][$relector_id];
  }
  
  public function getReflectorPass( $relector_id ) {
    
    return @$this->ini_data['session_pass'][$relector_id];
    
  }

  public function getVNCPort( $relector_id ){
    return @$this->ini_data['session_hostfile'][$relector_id];
  }
  
}

?>