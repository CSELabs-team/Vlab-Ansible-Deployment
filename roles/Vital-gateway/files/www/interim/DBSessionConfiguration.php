<?php



require_once ('DBConnection.php');

require_once ('SessionConfiguration.php');

class DBSessionConfiguration extends SessionConfiguration{
  private $db_conn;

  public function DBSessionConfiguration(){
    global $config;
    
    $this->db_conn = new DBConnection( $config['db_host'], $config['db_name'], $config['db_user'], $config['db_pass'] );
    
    
  }
  
  public function getFields( $fields, $where_array = array()){
    $where = 'WHERE 1=1';
    while ( list($key, $value) = each($where_array) ) {
      
      $where .= ' AND "'.$this->db_conn->escape($key).'"=\''.$this->db_conn->escape($value).'\'';
    }
  
    return $this->db_conn->executeQuery( sprintf('SELECT %s
      FROM ("vlab_interim".reflector
        join
        ("vlab_interim".user_reflector join "vlab_interim".user using(user_id) ) using(reflector_id) )
          join
          "vlab_interim".vm_resource using(vm_id)
      %s', $this->db_conn->escape($fields),
        $where ));
  }
  
  
  public function getField( $fields, $where_array = array()){
    $result = $this->getFields($fields, $where_array);
    return $result[0];
  }
  
  public function getUserPass( $username ){
    $result = $this->getField('user_pass',array('user_name' => $username) );
    
    return $result['user_pass'];
  }
  
  public function getXEN_SERVER_NAME($vm_id){
    $result = $this->getField('xen_server', array('vm_id' => $vm_id) );
    
    return $result['xen_server'];
  }
  
  public function getVMName( $relector_id ){
    $result = $this->getField('vm_name', array('reflector_id' => $relector_id) );
    
    return $result['vm_name'];
  }
  
  public function getVMId( $relector_id ){
    $result = $this->getField('vm_id', array('reflector_id' => $relector_id) );
    
    return $result['vm_id'];
  }
  
  public function getReflectorIds( $username ){
    $rtrn = array();
    
    $results = $this->getFields('reflector_id', array('user_name' => $username) );
    
    foreach ( $results as $result ){
      array_push( $rtrn, $result['reflector_id'] );
    }
    
    return $rtrn;
  }
  
  public function getReflectorPort( $reflector_id ){
    $result = $this->getField('reflector_port', array('reflector_id' => $reflector_id) );
    
    return $result['reflector_port'];
  }
  
  public function getReflectorPass( $reflector_id ){
    $result = $this->getField('reflector_pass', array('reflector_id' => $reflector_id) );
    
    return $result['reflector_pass'];
  }

  public function getVNCPort( $vm_id ){
    $result = $this->getField('vnc_port', array('vm_id' => $vm_id) );
    
    return $result['vnc_port'];
  }
  
  public function getVNCTempDir(){
    global $config;
    
    return $config['vnc_temp_dir'];
  }
}

?>