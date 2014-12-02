<?php


class VM{


  public static $logger;

  const NONE_STATE = -1;
  const START_STATE = 10;
  const STOP_STATE = 11;
  const SAVE_STATE = 12;
  const RESTORE_STATE = 13;
  const RESTART_STATE = 14;
  const REIMAGE_STATE = 16;
  
  
  private $vm_id;
  private $db_conn;
  
  public function VM( $vm_id ) {
  
    $this->vm_id = $vm_id;
    
    global $config;
    
    $this->db_conn = new DBConnection( $config['db_host'], $config['db_name'], $config['db_user'], $config['db_pass'] );

  }
  
  public function getVNCPort(){
    $result = $this->getField('vnc_port');
    
    return $result['vnc_port'];
  }
  
  public function startup(){
    $this->setState(VM::START_STATE);
  }
  
  public function shutdown(){
    $this->setState(VM::STOP_STATE);
  }
  
  public function save(){
    $this->setState(VM::SAVE_STATE);
  }
  
  public function restore(){
    $this->setState(VM::RESTORE_STATE);
  }
  
  public function restart(){
    $this->setState(VM::RESTART_STATE);
  }
  
  public function reimage(){
    $this->setState(VM::REIMAGE_STATE);
  }
  
  public function isStarted(){
    $result = $this->getField('current_state');
    
    return $result['current_state']==VM::START_STATE;
  }
  
  public function cancelPendingState(){
    $this->db_conn->executeQuery( 'UPDATE vlab_interim.vm_resource
      SET requested_state=current_state, inprocess=0
      WHERE vm_id=\'%s\'',
      $this->vm_id);
  }

  public function isVMDisabled(){
    $result = $this->getField( array('disabled'));
    
    return $result['disabled']==1;
  }
  
  public function isStatePending(){
    $result = $this->getField( array('current_state', 'requested_state'));
    
    return $result['current_state']!=$result['requested_state'];
  }
  
  private function getState(){
    $result = $this->getField('current_state');
    
    return $result['current_state'];
  }
  
  private function getRequestedState(){
    $result = $this->getField('requested_state');
    
    return $result['requested_state'];
  }
  
  private function getField( $fields ){
    $result = $this->getFields( $fields );
    
    return $result[0];
  }
  
  public function getVMId(){
    return $this->vm_id;
  }
  
  public function getName(){
    // return $this->vm_id;
    $result = $this->getField( 'vm_name' );
    
    return $result[ 'vm_name' ];
  }

  public function getFriendlyName() {
    $result = $this->getField('vm_friendly_name');
    return $result['vm_friendly_name'];
  }

  public function getBestName() {
    if ($this->getFriendlyName() == NULL) {
      return $this->getName();
    }
    return $this->getFriendlyName();
  }

  private function getFields( $fields ){
    
    $fields = is_array($fields) ? array_map( array($this->db_conn, 'quoteColumn'), $fields) : array($fields);
    
    $fields_str = implode(',',$fields);
    
    
    return $this->db_conn->executeQuery( sprintf('SELECT %s
      FROM vlab_interim.vm_resource
      WHERE vm_id=\'%s\'',//FIXME:change to %d
      $fields_str,
      $this->vm_id ));
  }
  
  
  private function setState( $state ) {
    $this->db_conn->executeQuery( 'UPDATE vlab_interim.vm_resource
      SET requested_state=%d
      WHERE vm_id=\'%s\'',
      $state,
      $this->vm_id);
  }
  
  public function getOperationString(){
    if ( $this->isVMDisabled() )
      return 'Disabled';

    if ( !$this->isStatePending() )
      return $this->isStarted() ? 'Running' : 'Powered off';
    
    $requested_state = $this->getRequestedState();
    switch( $requested_state ){
      case ( VM::NONE_STATE ):{
        throw new Exception('going to NONE_STATE');
      } case ( VM::START_STATE ):{
        return 'Powering on';
      } case ( VM::STOP_STATE ):{
        return 'Powering off';
      } case ( VM::SAVE_STATE ):{
        return 'Saving';
      } case ( VM::RESTORE_STATE ):{
        return 'Restoring';
      } case ( VM::RESTART_STATE ):{
        return 'Restarting';
      } case ( VM::REIMAGE_STATE ):{
        return 'Reimageing';
      } default: {
        throw new Exception( 'Invalid requested state: ' + $requested_state );
      }
      
    }
    
  }
}

VM::$logger =& Logger::getLogger('VM');

?>
