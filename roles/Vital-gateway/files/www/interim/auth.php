<?php


require_once ('UserAuthenticator.php');

class LoggedOutException extends Exception{

}

class Authentication{
  public static $logger;
  
  private $username;
  private $realm;
  private $userAuth;


  public function Authentication( UserAuthenticator $userAuth, $realm ){
    self::$logger->info('Authenticating user in realm: "'.$realm.'"' );
    
    $this->userAuth = $userAuth;
    $this->realm = $realm;
    
    try{
    
      if( !$this->authenticate() ){
        self::$logger->warn('Authenication failed' );
        
        
        throw new Exception('Unauthorized');
      }
    } catch( Exception $e ){
      $this->sendAuthHeaders();
      
      throw $e;
    }
    

  }
  
  public function getUsername(){
    return $this->username;
  }
  public function getUserEmail() {
    echo getUsername.username;
  }
  
  private function authenticate(){
    if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
      self::$logger->info('No Authorization header' );
      
      return false;
    }
    
    
    // analyze the PHP_AUTH_DIGEST variable
    if (!($data = Authentication::http_digest_parse($_SERVER['PHP_AUTH_DIGEST']))) {
      self::$logger->info('Error parsing Authorization header' );
      return false;
    }
      
    if(is_null($this->userAuth->getUserPass($data['username'])) ){
      self::$logger->info('No such user' );
      return false;
    }

    $this->username = $data['username'];
    
    self::$logger->info('Login attempt by '.$this->username );
    
    // generate the valid response
    $A1 = md5($data['username'] . ':' . $this->realm . ':' . $this->userAuth->getUserPass($data['username']));
    $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
    $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
    if ($data['response'] != $valid_response){
      self::$logger->info('Invalid authentication response');
      return false;
    }
    
    if ($data['opaque'] != $this->generateOpaque()) {
      self::$logger->info('Logout mechanism: Opaque is wrong for this session');
      throw new LoggedOutException('Logout mechanism: Opaque is wrong for this session');
      // return false;
    }
    
    return true;
  }
  
  public function sendAuthHeaders(){
    self::$logger->info('Sending authentication headers');
    $token = md5(uniqid(mt_rand(), true));
      header('HTTP/1.1 401 Unauthorized');
      header('WWW-Authenticate: Digest realm="'.'VLAB'.
             '",qop="auth",nonce="'.$token.'",opaque="'.$this->generateOpaque().'"');
  }
  
  private function generateOpaque(){
     return md5(session_id().$this->realm);
  }
  
  // function to parse the http auth header
  private static function http_digest_parse($txt)
  {
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1, 'opaque'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
      $data[$m[1]] = $m[3] ? $m[3] : $m[4];
      unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
  }



}

Authentication::$logger =& Logger::getLogger('Authentication');


?>
