<?php



require_once ('ReflectorSession.php');
require_once ('DBConnection.php');

//FIXME:only required for bug below
require_once ('VM.php');


require_once ('config.entry.php');

class DBReflectorSession extends ReflectorSession{
  private $reflector_id;
  private $db_conn;

  public function DBReflectorSession( $reflector_id ){
    $this->reflector_id = $reflector_id;
    
    global $config;
    
    $this->db_conn = new DBConnection( $config['db_host'], $config['db_name'], $config['db_user'], $config['db_pass'] );
      
  }
  
  
  
  public function getReflectorId(){
    return $this->reflector_id;
  }
  
  public function getVMId(){
    $result = $this->db_conn->executeQuery('SELECT vm_id FROM vlab_interim.reflector
      WHERE vlab_interim.reflector.reflector_id=%d',$this->reflector_id);
    
    return $result[0]['vm_id'];
  }
  
  public function getPort(){
    $result = $this->db_conn->executeQuery('SELECT reflector_port FROM vlab_interim.reflector WHERE reflector_id=%d',$this->reflector_id);

    return $result[0]['reflector_port'];
  }
  
  public function getPass(){
    $result = $this->db_conn->executeQuery('SELECT reflector_pass FROM vlab_interim.reflector WHERE reflector_id=%d',$this->reflector_id);
    
    return $result[0]['reflector_pass'];
  }
  
  public function setPass( $pass ){
    $this->db_conn->executeQuery( 'UPDATE vlab_interim.reflector
      SET reflector_pass=\'%s\'
      WHERE reflector_id=%d', $pass, $this->reflector_id );
    
  }
  
  public function getXEN_SERVER_NAME(){
    $result = $this->db_conn->executeQuery('SELECT xen_server FROM vlab_interim.vm_resource, vlab_interim.reflector
      WHERE vlab_interim.reflector.vm_id=vlab_interim.vm_resource.vm_id
        AND vlab_interim.reflector.reflector_id=%d',$this->reflector_id);
    
    return $result[0]['xen_server'];
  }
  
  public function getVNCPort(){
    $result = $this->db_conn->executeQuery('SELECT vnc_port FROM vlab_interim.vm_resource, vlab_interim.reflector
      WHERE vlab_interim.reflector.vm_id=vlab_interim.vm_resource.vm_id
        AND vlab_interim.reflector.reflector_id=%d',$this->reflector_id);
    
    return $result[0]['vnc_port'];
  }
  
  protected function checkVMConsistency(){
    // $result = $this->db_conn->executeQuery('SELECT xen_server, vm_name FROM vlab_interim.vm_resource, vlab_interim.reflector
      // WHERE vlab_interim.reflector.vm_id=vlab_interim.vm_resource.vm_id
        // AND vlab_interim.reflector.reflector_id=%d',$this->reflector_id);
    
    // $file_contents = file_get_contents( '/var/tmp/vlab/vm-list.out' );
    
    // $servers = explode( '\n', $file_contents );
    
    // foreach ( $servers as $server ){
      // $server_data = explode( ' ', $server );
      
      // if ( $server_data[0] == $result[0][ 'xen_server' ] &&
        // $server_data[1] == $result[0][ 'vm_name' ] )
        // return true;
    // }
    
    
    // $vm = (new VM( $this->getVMId() ) );
    // $vm->restart();
    
    // return false;
  }
}
?>