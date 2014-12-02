<?php
#error_reporting(E_ALL);

#require_once('log4php/main/php/Logger.php');

#Logger::configure('reflectorsession_log.properties');

abstract class ReflectorSession{

#  var $logger;
  
  abstract public function getXEN_SERVER_NAME();
  
  abstract public function getVNCPort();
  
  abstract public function getReflectorId();
  
  abstract public function getPort();
  
  abstract public function getPass();
  
  abstract public function getVMId();
  
  abstract public function setPass( $pass );
  
  public function getPid(){
    return intval(@file_get_contents($this->getPIDFile()));
  }
  
  public function getVNCFile(){
    return $this->getTmpDir().'/'.intval($this->getReflectorId()).'.vnc';
  }
  
  public function getPIDFilePrefix(){
    return $this->getTmpDir().'/pid';
  }
  
  public function getPassFile(){
    return $this->getTmpDir().'/'.intval($this->getReflectorId()).'.pwd';
  }
  
  public function getTmpDir(){
    global $config;
    
    return $config['vnc_temp_dir'].'/vncpwd';
  }
  
  public function getPIDFile(){
    return $this->getPIDFilePrefix().'.'.intval($this->getPort());
  }
  
  public function stop(){
    
#    $this->logger =& Logger::getLogger(get_class($this));

    $pid = $this->getPid();
  
    if ( $pid > 0 ){
#      exec( sprintf('kill %d',
#        $pid) );
      $exec_string = sprintf('kill %d',
        $pid);
      file_put_contents( '/var/log/vlab/reflectorsession.log', $exec_string );
      exec( $exec_string );

      exec( sprintf('rm -f %s %s', 
        escapeshellarg($this->getPIDFile()),
        escapeshellarg($this->getVNCFile())) );
    }
    
##    exec( sprintf( 'kill -9 `lsof -t -i :%d`',
##      intval($this->getPort()) ) );
    $exec_string = sprintf( 'kill -9 `lsof -t -i :%d`',
      intval($this->getPort()) );
#    $stale_proc = sprintf('`lsof -t -i :%d`', intval($this->getPort()));
#    if ( strlen($stale_proc) > 1 ) {
#      $exec_string = sprintf( 'kill -9 %d', $stale_proc);
      file_put_contents( '/var/log/vlab/reflectorsession.log', $exec_string );
      exec( $exec_string );
#    }

  }
  
  protected abstract function checkVMConsistency();
  
  public function start(){

#    $this->logger =& Logger::getLogger(get_class($this));
  
    //Kludge for weird bugs we've been having; remove when bug is fixed
    $this->checkVMConsistency();
    
    $pass = $this->getPass();
    $pwd_file = $this->getPassFile();
    $vnc_file = $this->getVNCFile();
    
    file_put_contents( $pwd_file, $pass."\n".$pass );
    file_put_contents( $this->getVNCFile(),  $this->getXEN_SERVER_NAME().':'.$this->getVNCPort());
    
    $output = array();
    $rtrn = 0;
#    exec( sprintf('/home/vlab_scp/bin/vncreflector -p %s -l %d -i %s %s',
#        escapeshellarg($pwd_file),
#        (intval($this->getPort())),
#        escapeshellarg($this->getPIDFilePrefix()),
#        escapeshellarg($vnc_file)));

/* Old reflector        
    $exec_string = sprintf('/home/vlab_scp/bin/vncreflector -p %s -l %d -i %s %s',
        escapeshellarg($pwd_file),
        (intval($this->getPort())),
        escapeshellarg($this->getPIDFilePrefix()),
        escapeshellarg($vnc_file));
#    $this->logger->debug($exec_string);
        file_put_contents( '/var/log/vlab/reflectorsession.log', $exec_string );
        exec( $exec_string );
*/

#     $noVNC_websockify = sprintf('nohup python /var/www/interim/noVNC/utils/websockify.py '.intval($this->getPort()).' '.$this->getXEN_SERVER_NAME().':'.intval($this->getVNCPort()+5900).' > /dev/null 2>&1 &');   
    $noVNC_websockify = sprintf('nohup sh /var/www/interim/noVNC/utils/launch.sh --listen '.intval($this->getPort()).' --vnc '.$this->getXEN_SERVER_NAME().':'.intval($this->getVNCPort()+5900).' > /dev/null 2>&1 &');
	// noVNC example ./websockify.py 8000 192.168.2.21:5900
        exec($noVNC_websockify);
  }
  
  public function restart(){
    $this->stop();
    $this->start();
  }
  
}

?>
