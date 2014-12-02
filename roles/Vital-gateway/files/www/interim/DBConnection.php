<?php

require_once('config.php');

class DBConnection{
  var $logger;

  public function DBConnection( $db_host, $db_name, $db_user, $db_pass ){
    $this->logger =& Logger::getLogger(get_class($this));
  
    $this->db_conn = pg_pconnect('host='.$db_host.' dbname='.$db_name.' user='.$db_user.' password='.$db_pass);
    
    
    if (!$this->db_conn)
      throw new Exception('Error connecting to db');
  }
  
  
  public function executeQuery( $query ){
    $args = func_get_args();
    
    $args[ 0 ] = '';
    
    $args = array_map( array($this, 'escape'), $args );
    
    $args[ 0 ] = $query;
    
    $query = call_user_func_array( 'sprintf', $args );

    // echo $query.'<br/><br/>';
    $this->logger->debug($query);
  
    $result = pg_query($this->db_conn, $query);
    
    
    if (!$result)
      throw new Exception('Error executing query');
    
    $rtrn = array();
    $rows  = pg_num_rows($result);
    for ( $row = 0; $row < $rows; ++$row ){
      array_push($rtrn,pg_fetch_assoc($result,$row));
    }
    
    // var_dump( $rtrn);
    // echo '<br/><br/>';
    return $rtrn;
  }
  
  public function escape( $string ){
    return pg_escape_string($this->db_conn, $string);
  }
  
  public function quoteColumn( $string ){
    return '"'.($this->escape($string)).'"';
  }
  
  public function quote( $string ){
    return '\''.($this->escape($string)).'\'';
  }
  
  
}
?>