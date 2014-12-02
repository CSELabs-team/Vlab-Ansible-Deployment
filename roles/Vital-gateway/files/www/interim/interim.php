<?php

require_once ('config.entry.php');
require_once('config.php');

require_once ('DBSessionConfiguration.php');

require_once ('auth.php');
require_once ('UserAuthenticator.php');
require_once ('DBReflectorSession.php');
require_once ('VM.php');




abstract class Widget{
  /**
   * A widget's primary function is to render itself in the correct place.
   * To render itself, a widget should return the HTML that represents the widget's contents
   */
  abstract public function render();
  
  abstract public function head();
  abstract public function foot();
}

abstract class SimpleWidget extends Widget{
  public function head(){
    return '';
  }
  public function foot(){
    return '';
  }
}

class VNCApplet extends SimpleWidget{
  public function VNCApplet( ReflectorSession $reflectorSession ){
    $this->reflectorSession = $reflectorSession;
  }
  
  
  public function render(){
    //Below is for noVNC version
     // return '<a href="https://vital.poly.edu/interim/noVNC/vnc_auto.html?host=vital.poly.edu&port='.$this->reflectorSession->getPort().'&encrypt=0&true_color=1&local_cursor=1&shared=1">Click to view your VM</a> ';
     /*return'
        <font color="red"><strong>"If keyboard does not respond, please click on dark grey area to restore it." <a href="http://vital.poly.edu:6080/vnc_auto.html?host=vital.poly.edu&port='.$this->reflectorSession->getPort().'&encrypt=0&true_color=1&local_cursor=1&shared=1">If you are unable to view your VM, please click me</a></strong></font><iframe src="//vital.poly.edu/interim/noVNC/vnc_auto.html?host=vital.poly.edu&port='.$this->reflectorSession->getPort().'&encrypt=0&true_color=1&local_cursor=1&shared=1" width="1050" height="800"></iframe> 
           ';*/
    /* return '
	<H2><a href="http://vital.poly.edu:6080/vnc_auto.html?host=vital.poly.edu&port='.$this->reflectorSession->getPort().'&encrypt=0&true_color=1&local_cursor=1&shared=1">Click here to view VM</a></H2>
	';
   */
       return '
        <H2><a href="http://vital.poly.edu:'.$this->reflectorSession->getPort().'/vnc_auto.html">Click here to view VM</a></H2>
	';
        
   /*  return '<applet archive="VncViewer.jar"
      code="VncViewer.class" width="1200" height="1060">
      <param name="PORT" value="'.$this->reflectorSession->getPort().'"/>
      <param name="PASSWORD" value="'.$this->reflectorSession->getPass().'"/>
      <param name="Offer relogin" value="No"/>
      <param name="Open new window" value="No"/>
      <param name="Scaling factor" value="auto"/>
      <h1>
      Get java
      </h1>
    </applet>';
  */
  }
  
  private $reflectorSession;
}

class VNCFlash extends SimpleWidget{
  public function VNCFlash( $reflectorSession ){
    $this->reflectorSession = $reflectorSession;
  }
  
  public function render(){
    return '
<object width="550" height="400"
  classid="clasid:D27CDB6E-AE6D-11cf-96B8-444553540000"
  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">
  <param name="movie" value="FVNC.swf">
  <param name="allowScriptAccess" value="sameDomain" />
  <embed src="FVNC.swf" width="550" height="400" quality="high"
    play="true"
    autoconnect="true"
    bgcolor="#FFFFFF"
    width="432"
    height="330"
    name="myMovieName"
    align=""
    type="application/x-shockwave-flash"
    pluginspage="http://www.macromedia.com/go/getflashplayer"
    host="'.$_SERVER['SERVER_NAME'].'"
    port="'.$this->reflectorSession->getPort().'"
    password="'.$this->reflectorSession->getPass().'"
    >

  </embed>
</object>
';

  }
  
  private $reflectorSession;
}




class ListWidget extends ComplexWidget{
  public function ListWidget(){
  
  }
  
  public function render(){
    $rtrn = '<ul class="list_widget">';
    foreach( $this->widgets as $widget ) {
      $rtrn .= '<li>'.$widget->render().'</li>';
    }
    
    $rtrn .= '</ul>';
    return $rtrn;
  }
}

abstract class EmbeddedWidget extends Widget{
  protected $widget;
  public function EmbeddedWidget( $widget ) {
    $this->widget = $widget;
  }
  
  public function head(){
    return $widget->head();
  }
  
  public function foot(){
    return $widget->foot();
  }
}

class ToolBox extends ListWidget{
  private $title;
  
  public function ToolBox( Widget $title ){
    
    parent::ListWidget();
    
    $this->title = $title;
  }
  
  public function addTool( Widget $tool ) {
    $this->addWidget( $tool );
  }
  
  public function render(){
    if ( !$this->isEmpty() ){
      $rtrn = '<div class="vm_controller_widget vlab_tool_box">'
        .'<h3>'.$this->title->render().'</h3>'
        .parent::render()
        .'</div>';
      
      return $rtrn;
    }
      return '';
  }
}

// class HREF{
  // public function HREF(){
  
  // }
  
  // public function toString(){
    
  // }
  
  // public function addVariable( $name, $value=null ){
    
  // }
// }

// class Anchor extends SimpleWidget{
  // public Anchor($content, HREF $href){
  
  // }
  
  // public function render(){
    // return '<a class="anchor" href="'.$this->href.'">'.$this->content->render().'<a>';
  // }
// }

class VMToolBox extends ToolBox{
  // private $reflectorSession;
  
  public function VMToolBox( ReflectorSession $reflectorSession ){
    parent::ToolBox(new HTMLWidget('VM Control'));
    
    $reflectorId = $reflectorSession->getReflectorId();
    $vm = new VM($reflectorSession->getVMId());
    
    if ( !$vm->isStatePending() ) {
      //NOTE:strat ordered these stuff killed
      if ( !$vm->isStarted() ) {
        $this->addTool(new HTMLWidget('<a href="?startup&amp;reflector='.$reflectorId.'">Power On</a>'));
      } else {
        // $this->addTool(new HTMLWidget('<a href="?restart">Restart</a>'));
        $this->addTool(new HTMLWidget('<a class="double_check" href="?shutdown&amp;reflector='.$reflectorId.'">Power Off</a>'));
      }
      
      $this->addTool(new HTMLWidget('<a href="?save&amp;reflector='.$reflectorId.'">Save</a>'));
      $this->addTool(new HTMLWidget('<a class="double_check" href="?restore&amp;reflector='.$reflectorId.'">Restore</a>'));
#      $this->addTool(new HTMLWidget('<a href="#" onclick="return confirmReimage('.$reflectorId.', \''.$vm->getName().'\'); return false;">Reimage</a>'));
      $this->addTool(new HTMLWidget('<a href="javascript:confirmReimage('.$reflectorId.', \''.$vm->getName().'\', \''.$vm->getBestName().'\');">Reimage</a>'));
    }
  }
}

class VMStatusBox extends SimpleWidget{
  private $vm;
  
  public function VMStatusBox( VM $vm ){
    $this->vm = $vm;
  }
  
  private function getVMStatus(){
    return $this->vm->getOperationString();
  }
  
  public function render(){
    $class = $this->vm->isStatePending() ? 'power_semi' : ( $this->vm->isStarted() ? 'power_on' : 'power_off' );
    $class = $this->vm->isVMDisabled() ? 'vm_disabled' : $class;
    $rtrn = '';
    $rtrn .= '<div class="vlab_tool_box vlab_status_box"><h3>VM Status</h3>';
    $rtrn .= '<table style="width:100%;">';
    
    
    $rtrn .= '<tr><td>'.$this->vm->getBestName().'</th></tr>';
    $rtrn .= '<tr><td colspan="2" class="'.$class.'">'.$this->vm->getOperationString().'</td></tr>';
    
    $rtrn .= '</table></div>';
    
    return $rtrn;
  }
  
}

class ReflectorSessionBusyBox extends Widget{
  private $reflectorSession;

   public function ReflectorSessionBusyBox( ReflectorSession $reflectorSession ){
    $this->reflectorSession =& $reflectorSession;
  }

  public function render(){
   $reflectorId = $this->reflectorSession->getReflectorId();
    $rtrn = '<div class="busy_widget">Please wait ... <a href="?cancel_request&amp;reflector='.$reflectorId.'">cancel</a></div>';
    
    return $rtrn;
  }
  
  public function head(){
    return '<meta http-equiv="refresh" content="3;" />';
  }
  
  public function foot(){
    return '';
  }
}

class BusyBox extends Widget{
  public function BusyBox(){

  }

  public function render(){
    $rtrn = '<div class="busy_widget">Please wait ... </div>';
    
    return $rtrn;
  }
  
  public function head(){
    return '<meta http-equiv="refresh" content="3;" />';
  }
  
  public function foot(){
    return '';
  }
}


abstract class ComplexWidget extends Widget{
  protected $widgets = array();
  
  public function ComplexWidget(){
    
  }
  
  public function addWidget( $widget ) {
    array_push($this->widgets,$widget);
  }
  
  
  public function isEmpty(){
    return count($this->widgets)==0;
  }
  
  public function head(){
    $rtrn = '';
    foreach( $this->widgets as $widget ){
      $rtrn .= $widget->head();
    }
    return $rtrn;
  }
  
  public function foot(){
    $rtrn = '';
    foreach( $this->widgets as $widget ){
      $rtrn .= $widget->foot();
    }
    return $rtrn;
  }
}

class VerticalTabledComplexWidget extends ComplexWidget{
  public function VerticalTabledComplexWidget(){
    parent::ComplexWidget();
  }

  public function render(){
    $rtrn = '<table class="widget_array">';
    
    foreach( $this->widgets as $widget ){
      $rtrn .= '<tr><td>'.$widget->render().'</td></tr>';
    }
    
    $rtrn .= '</table>';
    
    return $rtrn;
  }
}

class HorizontalComplexWidget extends ComplexWidget{
  public function VerticalTabledComplexWidget(){
    parent::ComplexWidget();
  }

  public function render(){
    $rtrn = '<div>';
    
    foreach( $this->widgets as $widget ){
      $rtrn .= '<div style="float:left;">'.$widget->render().'</div>';
    }
    
    $rtrn .= '</div>';
    
    return $rtrn;
  }
}

class InterimWidget extends ComplexWidget{
  private $tools;
  private $content;

  public function InterimWidget( $tools, $content ){
    parent::ComplexWidget();
    $this->tools = $tools;
    $this->content = $content;
    
    parent::addWidget($tools);
    parent::addWidget($content);
  }
  
  public function render(){
    $rtrn = '<div class="body_div">';
    
    $rtrn .= '<table style="width:100%;">
    <tr>
      <td style="">
        <div class="body_tools">'
        .$this->tools->render()
.'</div>
      </td>
      <td style="width:81%;">
        <div class="body_center">'
.$this->content->render()
.'</div>
      </td>
      <td style="width:3%;">
        <div class="">'
.'</div>
      </td>
    </tr>
  </table>';
    $rtrn .= '</div>';
    
    return $rtrn;
  }
}

class HTMLWidget extends SimpleWidget{
  private $html;
  public function HTMLWidget($html){
    $this->html = $html;
  }
  
  public function render(){
    return $this->html;
  }
}



class InterimDashboard extends InterimWidget{
#  public function InterimDashboard( $tools, $content, $username ){
  public function InterimDashboard( $tools, $content ){

    $userToolBox = new ToolBox( new HTMLWidget('Tools') );

#    if ($username == 'student167') {
#     $userToolBox->addTool( new HTMLWidget('<a class="double_check" href="create.php">Create VM</a>') );
#    }

    $userToolBox->addTool( new HTMLWidget('<a class="double_check" href="?logout">Log Out</a>') );
    $userToolBox->addTool( new HTMLWidget('<a href="" target="_self">Refresh Console</a>') );

    $tools->addWidget( $userToolBox );
 
    parent::InterimWidget( $tools, $content );
  }
}

class InterimVNCSessionBusyBox extends InterimDashboard{
  public function InterimVNCSessionBusyBox($vncReflector){
    $tools = new VerticalTabledComplexWidget();
    $content = new VerticalTabledComplexWidget();
    
    $content->addWidget( new ReflectorSessionBusyBox($vncReflector) );
    
#    parent::InterimDashboard( $tools, $content, $username);
    parent::InterimDashboard( $tools, $content);
  }
}

class InterimVNCSessionDashboard extends InterimDashboard{
  public function InterimVNCSessionDashboard( VM $vm, ReflectorSession $reflectorSession ){
  
    $tools = new VerticalTabledComplexWidget();
    $content = new VerticalTabledComplexWidget();
    
    parent::InterimDashboard( $tools, $content );
    
    if ( $vm->isStarted() ){
    
      $reflectorSession->setPass(substr(sha1('some interesting salt. ASFDFSDWERWEREWREWREWREWRWERWERWERRGFGVC'+mt_rand()), 0, 8 ));
      
      $reflectorSession->restart();

      $vncApplet = new VNCApplet( $reflectorSession );
      
      $content->addWidget( $vncApplet );
    }
    
    
    $vmToolBox = new VMToolBox( $reflectorSession );
    $vmStatusBox = new VMStatusBox( $vm );
    
    $tools->addWidget( $vmStatusBox );
    $tools->addWidget( $vmToolBox );
  }
}

class ReflectorSessionControlWidget extends SimpleWidget{
  private $reflectorSession;
  
  public function ReflectorSessionControlWidget( ReflectorSession $reflectorSession ){
    $this->reflectorSession =& $reflectorSession;
  }
  
  public function render(){
    $vm_id = $this->reflectorSession->getVMId();
    $reflectorId = $this->reflectorSession->getReflectorId();
    $vm = new VM($vm_id);
    
    $vmStatusBox = new VMStatusBox($vm);
    
    $rtrn = '<div class="reflector_session_control_widget vlab_tool_box">'
      //.'<h3 style="text-align:center;margin:0;">VM: '.$vm->getName().'</h3>'
      .$vmStatusBox->render()
      .'<ul>'
      .'<li><a target="_blank" href="?reflector='.$reflectorId.'">View</a></li>';
      
    if ( !$vm->isStatePending() ){
      if ( !$vm->isStarted() )
        $rtrn .= '<li><a target="_blank" href="?startup&amp;reflector='.$reflectorId.'">Power On</a></li>';
      else
        $rtrn .= '<li><a target="_blank" href="?stop&amp;reflector='.$reflectorId.'">Power off</a></li>';
        
      $rtrn .= '<li><a target="_blank" href="?save&amp;reflector='.$reflectorId.'">Save</a></li>';
      $rtrn .= '<li><a target="_blank" href="?restore&amp;reflector='.$reflectorId.'">Restore</a></li>';
      $rtrn .= '<li><a target="_blank" href="?reimage&amp;reflector='.$reflectorId.'">Reimage</a></li>';
    }
    
    $rtrn .= '</ul></div>';
    
    // $rtrn .= 
    return $rtrn;
  }
  
  public function head(){
    $vm_id = $this->reflectorSession->getVMId();
    $reflectorId = $this->reflectorSession->getReflectorId();
    $vm = new VM($vm_id);

    if ( $vm->isStatePending() ){
      return '<meta http-equiv="refresh" content="3;" />';
    }
    
    return '';
  }
}

class InterimMultiVMDashboard extends InterimDashboard{
  public function InterimMultiVMDashboard( $reflectorSessions, $username ){
  
    $tools = new VerticalTabledComplexWidget();
    $content = new HorizontalComplexWidget();
    
    parent::InterimDashboard( $tools, $content, $username );
 
    foreach ( $reflectorSessions as $reflectorSession ) {
      $content->addWidget( new ReflectorSessionControlWidget($reflectorSession) );
    }
  }
  
}

/*
class InterimVNCSessionDashboard extends InterimWidget{
  public function InterimVNCSessionDashboard( $vm, $reflectorSession ){
  
    $content = new VerticalTabledComplexWidget();
    $tools = new VerticalTabledComplexWidget();

    if ( $vm->isStatePending() ) {
      $busyBox = new BusyBox();
      
      $content->addWidget( $busyBox );
    } else if ( $vm->isStarted()  ){


      $reflectorSession->setPass(substr(sha1('some interesting salt. ASFDFSDWERWEREWREWREWREWRWERWERWERRGFGVC'+mt_rand()), 0, 8 ));
      
      $reflectorSession->restart();

      $vncApplet = new VNCApplet( $reflectorSession );
      
      $content->addWidget( $vncApplet );
      
    }


    $vmToolBox = new VMToolBox( $vm );
    $vmStatusBox = new VMStatusBox( $vm );

    $userToolBox = new ToolBox( new HTMLWidget('Tools') );
    $userToolBox->addTool( new HTMLWidget('<a class="double_check" href="?logout">Logout</a>') );
    $userToolBox->addTool( new HTMLWidget('<a href="interim.php" target="_self">Refresh Console</a>') );


    $tools->addWidget( $userToolBox );
    $tools->addWidget( $vmStatusBox );
    $tools->addWidget( $vmToolBox );
    
    parent::InterimWidget( $tools, $content );
  }
}
*/

function display( Widget $widget ){
  echo sprintf(file_get_contents('testing.template.html'), $widget->head(), $widget->render(), $widget->foot() );
}

function main(){
  ob_start();
  $logger =& Logger::getLogger('::main()');


  session_start();
  
  $session_first_time = false;
  if ( !@$_SESSION['initialized'] ){
    $logger->info('Creating session');
    $_SESSION['initialized'] = true;
    $session_first_time = true;
  }
  
  $logger->info('Session has begun; session_id: '.session_id());
  
  $logger->info('REQUEST_URI: ' . $_SERVER['REQUEST_URI'] );
  $logger->info('HTTP_REFERER: '. (isset($_SERVER['HTTP_REFERER']) ? ($_SERVER['HTTP_REFERER']) : 'none') );
  $logger->info('HTTP_USER_AGENT: '.$_SERVER['HTTP_USER_AGENT']);
  
  

  $realm = 'VLAB';

  $sessionConf = SessionConfiguration::getInstance();

  
  
  // if ( isset($_GET['go_home']) ){
    // $logger->info('User is requesting to be redirected to home page');
    // header( 'Location: .' );
    // exit();
  // }
  

  




  $authentication = null;

  try {
    //Authenticate user
    $authentication = new Authentication( $sessionConf, $realm );
    
    $logger->info('User is authenticated');
    
    if ( isset($_GET['logged_out']) ){
      //If the user is supposed to be logged out
      $logger->info('User is using logout method');
      
      //Redirect the user to the main page with anonymous user/pass
      $logger->info('Redirect the user with anonymous credentials');
      header( 'Location: https://anonymous:anonymous@'.$_SERVER['SERVER_NAME'].'/interim.php?go_home' );
      
      
      exit();
    }
    if ( isset($_GET['go_home']) ){
      header( 'Location: .' );
      exit();
    }
    
  } catch (Exception $e){
    //If the user failed authentication
    $logger->warn('User has failed authentication');
    
    if ( isset($_GET['logout']) ){
      header( 'Location: .');
    }
  
    echo '<html><head><title>Unauthorized</title></head><body><h1>Unauthorized</h1><p>Please go to the <a href="./">home page</a>.</p>
     </body></html>';
    exit();
  }


  $username = $authentication->getUsername();	
  
  $vncReflectorIds = $sessionConf->getReflectorIds($username);
  
  $vncReflectorId = null;
  
  if ( isset( $_GET['reflector'] ) ) {
    $vncReflectorId = $_GET['reflector'];
    
    if ( in_array( $vncReflectorId, $vncReflectorIds ) ) {
      $vncReflectorIds = array( $vncReflectorId );
    }
  }
  
  
  
  $vncReflectorSessions = array();
  foreach ( $vncReflectorIds as $vncReflectorId ) {
    array_push( $vncReflectorSessions, new DBReflectorSession( $vncReflectorId ) );
  }
  
  $vm_id = null;
  
  if ( isset( $_GET['vm'] ) ) {
    $vm_id = $_GET['vm'];

    foreach ($vncReflectorSessions as $vncReflectorSession){
      if ( $vncReflectorSession->getVMId() == $vm_id ){
        $vncReflectorSessions = array( $vncReflectorSession );
        break;
      }
    }
  }
  
  
  if ( isset($_GET['logout']) ){
    $logger->info('User is requesting to be logged out');
    
    $logger->info('Destroying session');
    session_destroy();
    session_unset();
    setcookie (session_name(), '', -1, '/');
    
    foreach ($vncReflectorSessions as $vncReflectorSession){
      $vncReflectorSession->stop();
    }
    
    header( 'Location: ?logged_out' );
    exit();
  }
  
  
  
  
  // $reflectorSession = new DBReflectorSession($vncReflectorId);

  // $vm = new VM( $reflectorSession->GetVMId() );
  $vms = array();
  foreach ($vncReflectorSessions as $vncReflectorSession){
    array_push( $vms, new VM( $vncReflectorSession->getVMId() ) );
  }
  
  if ( isset($_GET['restart']) ){
    foreach( $vms as $vm ){
      $vm->restart();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['startup']) ){
    foreach( $vms as $vm ){
      $vm->startup();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['cancel_request']) ){
    
    foreach( $vms as $vm ){
      $vm->cancelPendingState();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['shutdown']) ){
    foreach ($vncReflectorSessions as $vncReflectorSession){
      $vncReflectorSession->stop();
    }
    
    foreach( $vms as $vm ){
      $vm->shutdown();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['save']) ){
    foreach( $vms as $vm ){
      $vm->save();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['restore']) ){
    foreach( $vms as $vm ){
      $vm->restore();
    }
    
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( isset($_GET['reimage']) ){
    foreach( $vms as $vm ){
      $vm->reimage();
    }
     
    header( 'Location: ?'.($vncReflectorId ? 'reflector='.$vncReflectorId : '' ) );
    exit();
  }

  if ( count( $vncReflectorSessions ) > 1 ){
    
    display( new InterimMultiVMDashboard($vncReflectorSessions, $username) );
  
  } else {
    $vncReflectorSession = $vncReflectorSessions[ 0 ];
    $vm = new VM( $vncReflectorSession->getVMId() );
    
    if ( $vm->isStatePending() ) {
      display( new InterimVNCSessionBusyBox($vncReflectorSession) );
    } else {
      display( new InterimVNCSessionDashboard($vm, $vncReflectorSession) );
    }
  }
}


try{
  $logger =& Logger::getLogger('main');
  SessionConfiguration::createInstance( new DBSessionConfiguration() );
  
  $logger->debug('starting main()');
  main();
} catch ( Exception $e ){
  $logger->error( $e );
}

?>
