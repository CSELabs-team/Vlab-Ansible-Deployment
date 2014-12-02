<?php
error_reporting(E_ALL);

//require_once ('auth.php');
require_once('../log4php/main/php/Logger.php');

Logger::configure('status_log.properties');

class STATUS{

  var $logger;
  var $course;
  var $regcode;
  private $db_conn;
  private $running_vms = 0;
  private $active_users = 0;
  private $total_mem = 0;
  private $allocated_mem = 0;
  private $message = "";


  public function STATUS( ) {

	$this->logger =& Logger::getLogger(get_class($this));
  	//$this->course = $_POST["course"];
        //$this->regcode = $_POST["regcode"];

  }

  public function getActiveUsers( ) {

    $query = sprintf( 'select count(distinct user_id) from vlab_interim.user_reflector where reflector_id in (select reflector_id from vlab_interim.reflector where vm_id in (select vm_id from vlab_interim.vm_resource where current_state=10))');

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception('Error executing query');

    $row = pg_fetch_row($result);
    $this->active_users = $row[0];

    return $this->active_users;

  }

  public function getRunningVMs( ) {

    $query = sprintf( 'SELECT count(*) from vlab_interim.vm_resource where current_state=10');

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception('Error executing query');

    $row = pg_fetch_row($result);
    $this->running_vms = $row[0];

    return $this->running_vms;

  }

  public function getTotalMem( ) {

    $query = sprintf( 'SELECT sum(total_memory) from vlab_interim.servers');

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception('Error executing query');

    $row = pg_fetch_row($result);
    $this->total_mem = $row[0];

    return $this->total_mem;

  }

  public function getAllocatedMem( ) {

    $query = sprintf( 'SELECT sum(memory) from vlab_interim.vm_resource where current_state=10');

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception('Error executing query');

    $row = pg_fetch_row($result);
    $this->allocated_mem = $row[0];

    return $this->allocated_mem;

  }

  public function getStatusMessage( ) {

  	return $this->message;

  }

  public function dbConnect( ){

    $this->db_conn = pg_pconnect("host=localhost dbname=vlab user=postgres password=<YOUR PASSWORD>");
    
    if (!$this->db_conn)
      throw new Exception('Error connecting to db');
    

  }

}

?>

