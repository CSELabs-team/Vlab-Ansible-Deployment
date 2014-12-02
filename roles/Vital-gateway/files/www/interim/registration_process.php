<?php
#error_reporting(E_ALL);
error_reporting(E_ERROR);

//require_once ('auth.php');
require_once('config.php');

require_once('log4php/main/php/Logger.php');

Logger::configure('registration_log.properties');

class USER{

  var $logger;
  var $course;
  var $regcode;
  var $counter_name;
  private $db_conn;
  private $status = 0;
  private $message = "";


  public function USER( ) {

	$this->logger = Logger::getLogger(get_class($this));
  	$this->course = filter_input(INPUT_POST, "course", FILTER_SANITIZE_NUMBER_INT);
        $this->regcode = filter_input(INPUT_POST, "regcode", FILTER_SANITIZE_NUMBER_INT);

  }
 
  public function register(){

   $this->dbConnect();

   if ($this->course == NULL) {
	$this->status = 0;
	$this->message = "<font color='red'>Invalid course number</font>";
	return;
   }


   	$query = "SELECT registration_code, class FROM vlab_interim.class WHERE id=" . $this->course . "and deletep=false";
  	 $result = pg_query($this->db_conn, $query);
  	 if (!$result)
     	    throw new Exception("Error executing query[$query]");
	
  	 $row = pg_fetch_row($result);
   	$registration_code = $row[0];

	if ($this->regcode == $registration_code) {

		$this->counter_name = $row[1];
	
		$this->status = 1;
		$this->message = "good";
		$_POST['email'] = stripslashes(trim($_POST['email']));
		$tmpEmail=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		if ( filter_var($tmpEmail, FILTER_VALIDATE_EMAIL)  == TRUE) {
			// callSmtp
			$user_id = $this->registerEmail($tmpEmail);
			if ($user_id > 0) {
				$this->sendConfirmation($tmpEmail, $user_id);
				$this->updateUserDB($tmpEmail, $user_id);
				$this->status = 1;
				$this->message = "Registration Complete <br>\n <br> Your account information has been emailed.";
			}
			elseif ($user_id == -1) {
				$this->status = 0;
				$this->message = "<font color='red'>Email address already registered.  <br>Please check your email for registration information.</font>";
			}
			elseif ($user_id == -2) {
				$this->status = 0;
				$this->message = "<font color='red'>Class size exceeded</font>";
			}
			else {
				$this->status = 0;
				$this->message = "<font color='red'>Unable to register Email</font>";
			}
		}
		else{
			//show error
			$this->status = 0;
			$this->message = "<font color='red'>Invalid Email Address</font>";
		}


	} else {

		$this->status = 0;
		$this->message = "<font color='red'>Invalid Registration Code</font>";

	}

   $this->dbClose();

  }

  public function getStatus( ) {

  	return $this->status;

  }

  public function getStatusMessage( ) {

  	return $this->message;

  }

  private function dbConnect( ){

    global $config;
    $connect_str = "host=localhost dbname=".$config['db_name']." user=".$config['db_user']." password=".$config['db_pass'];
    $this->db_conn = pg_pconnect($connect_str);

    if (!$this->db_conn) {
      throw new Exception("Error connecting to db: [$connect_str]");
    }
   
  }

  private function dbClose( ){

    pg_close($this->db_conn);
    $this->prep_created = 0;

  }

  private function sendConfirmation($email, $user_id){

	$subject = 'CSE VLAB Credentials ';
	$message = $this->createEmailMsg($user_id); 
	$headers = 'From: Vlab_Administration <cselabs-team-group@nyu.edu>' . "\r\n" .
    		'Reply-To: cselabs-team-group@nyu.edu' . "\r\n" .
        	'X-Mailer: PHP/' . phpversion();

	mail($email, $subject, $message, $headers);

	$subject = $user_id.': '.$email.' CSE VLAB Credentials ';
	#mail('cselabs-team-group@nyu.edu', $subject, $message, $headers);
	mail('chl432@nyu.edu>', $subject, $message, $headers);


//    return $rtrn;
  }

  private function updateUserDB($email, $user_id){

    //update user email 

    //if you would like to store email, and understand the privacy issues
    //associated with that information, uncomment the following lines

    //$query = sprintf( 'UPDATE vlab_interim.user SET email=\'%s\' WHERE user_id=\'%s\'', $email, $user_id );
    //$result = pg_query($this->db_conn, $query );
    //    if (!$result)
    //	      throw new Exception('Error executing query');

  }

  private function createEmailMsg($user_id) {

    $this->dbConnect();

// get user password
    $query = sprintf( 'SELECT user_pass, user_name FROM vlab_interim.user WHERE user_id=%s', $user_id ); 
      
    $result = pg_query($this->db_conn, $query );

    if (!$result)
      throw new Exception("Error executing query[$query]");
    
    $row = pg_fetch_row($result);
    $user_pass = $row[0];
    $user_name = $row[1];
   

// get sftp password
    $query = sprintf( 'SELECT sftp_pass, sftp_user FROM vlab_interim.sftp WHERE user_id=%s', $user_id ); 
      
    $result = pg_query($this->db_conn, $query );

    if (!$result)
      throw new Exception("Error executing query[$query]");
    
    $row = pg_fetch_row($result);
    $sftp_pass = $row[0];
    $sftp_user = $row[1]; 


    $emailMsg = "Please use the following information to access CSE VLAB:
    
    (https://vital.poly.edu/interim/)
    (sftp://128.238.66.35)

    VLAB & Sftp	
    ------------------------
    UID: ".$user_name."
    PWD: ".$user_pass;

    /*JL 20130416
    VLAB Account
    (https://vital.poly.edu/interim/)
    ------------
    UID: ".$user_name."
    PWD: ".$user_pass."

    sftp Dropbox
    (sftp://128.238.66.35)
    ------------
    UID: ".$sftp_user."
    PWD: ".$sftp_pass;
    JL */
    return $emailMsg;
  }

  private function registerEmail($email) {

    $this->dbConnect();
    /* TODO: we have a table for this now in the db it is vlab_interim.class 
     * it will store the class id, regcode, and if the class is enabled */

    $query = sprintf( 'SELECT user_id FROM vlab_interim.email WHERE course_id=%s and email=\'%s\'', $this->course, $email  );

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception("Error executing query[$query]");
    
    if ( pg_num_rows($result) > 0) {
	// user already in db for class
	return -1;
    }

// get next student number for count
    $query = sprintf( 'SELECT count, base + size as maxcount FROM vlab_interim.counter WHERE name=\'%s\'', $this->counter_name );

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception("Error executing query[$query]");

    $row = pg_fetch_row($result);
    $student_count = $row[0];
    $max_count = $row[1];

    if ( $student_count >= $max_count) {
	// class size exceeded
	return -2;
    }

//update student counter
    $query = sprintf( 'UPDATE vlab_interim.counter SET count=count+1 WHERE name=\'%s\'', $this->counter_name );

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception("Error executing query[$query]");

// get user_id 
    $query = sprintf( 'SELECT user_id FROM vlab_interim.user WHERE user_name=\'student%s\'', $student_count );

    $result = pg_query($this->db_conn, $query );

        if (!$result)
	      throw new Exception("Error executing query[$query]");

    $row = pg_fetch_row($result);
    $user_id = $row[0];

    // insert into email table
    $query = sprintf( 'INSERT INTO vlab_interim.email (fname, lname, email, course_id, user_id) VALUES (\'%s\', \'%s\', \'%s\', %s, %s)',
	      pg_escape_string( $this->db_conn, $_POST['fname']),
	      pg_escape_string( $this->db_conn, $_POST['lname']),
	      pg_escape_string( $this->db_conn, $_POST['email']),
	      $this->course,
	      $user_id);
    $this->logger->debug($query);
    $result = pg_query($this->db_conn, $query );
    if (!$result)
	 throw new Exception("Error executing query[$query]");

    return $user_id;
  }

  private function doAction ($action, $task) {

  }
  
}

?>

