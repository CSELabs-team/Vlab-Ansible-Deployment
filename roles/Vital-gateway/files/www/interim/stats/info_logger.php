<?php

require_once ('info_db.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="/js/jquery.js"></script>
<!-- <script type="text/javascript" src="/js/dimmer.js"></script>
<script type="text/javascript" src="/js/dimensions.js"></script>
<script type="text/javascript" src="/js/interface.js"></script>
<script type="text/javascript" src="/js/akModal.js"></script> -->

<script type="text/javascript" src="/js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="/js/ui/ui.core.js"></script>
<script type="text/javascript" src="/js/ui.draggable.js"></script>
<script type="text/javascript" src="/js/ui/ui.resizable.js"></script>

<script type="text/javascript" src="/js/ui/ui.dialog.js"></script>

<script type="text/javascript" src="/js/jquery-css-transform.js"></script>
<script type="text/javascript" src="/js/rotate3Di-0.9.js"></script>

<!-- <script type="text/javascript" src="/js/jquery-latest.pack.js"></script>
<script type="text/javascript" src="/js/jquery.datepick.pack.js"></script>
<script type="text/javascript" src="/js/jquery.datepick.js"></script> -->
<script type="text/javascript" src="/js/ui/ui.datepicker.js"></script>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="keywords" content="" />
<meta name="description" content="" />

<title>Virtual Lab</title>
<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
<link type="text/css" href="/css/theme.css" rel="stylesheet" /> 
<link href="/system/application/views/styles.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div style="height:100%;">
<!-- <img alt="" src="/system/application/views/images/bg.jpg" id="background_image" /> -->
<div id="main">
	<div id="header">	
		<!--<div id="logo" ><div id="logo_strip"></div></div>  -->
		 <div id="logo_strip" style="padding-top: 5px;height: 50px;">

			<img alt="" src="/system/application/views/images/vlab_banner_new.jpg" usemap="#banner" />
			<map name="banner" id="banner">
				<area alt="poly page" shape="rect" coords="0,0,142,52" href="http://www.poly.edu" />
				<area alt="" shape="rect" coords="143,1,870,52" href="https://vital.poly.edu/interim" />
				<area alt="" shape="rect" coords="871,1,1001,52" href="http://isis.poly.edu" />
			</map>
		
		</div>

				<div id="fillGap">

		</div>

			</div>
<!-- header ends -->

<!-- content begins -->
<div id="contop"></div>
<div id="content_bg">
  <div id="content">
	

  <table style="padding:2em; height:100%;">

  <tbody>
    <tr style="height:100%;">
      <td style="text-align: center; vertical-align: middle; width:65%;">
        <div id="vlab_page">
          <span style="font-size:large; color:black;">

            <b>Virtual Lab</b>
            <i>is a part of Information Systems and Internet
            Security Laboratory (ISIS) that provides remote access
            to the Information Assurance Lab facility in ISIS.</i>

          </span>

        </div>
        <table style="width:50%; height:94px; margin-left:auto; margin-right:auto; margin-top:15%;">
          <tbody>
            <tr>
              <td>
                <div class="logo" id="nsf_logo">
                  <img alt="NSF Logo"
                  src="/system/application/views/images/nsf_logo.gif"
                  usemap="#nsf_map" />
                  <map name="nsf_map" id="nsf_map">
                    <area alt="NSF Home Page" shape="rect"
                    coords="0,0,48,48"
                    href="http://www.nsf.gov/" />

                  </map>
                </div>
              </td>
              <td>
                <div class="logo" id="cisco_logo">
                  <img alt="CISCO Logo"
                  src="/system/application/views/images/cisco_logo.jpg"
                  usemap="#cisco_map" />
                  <map name="cisco_map" id="cisco_map">
                    <area alt="Cisco Home Page" shape="rect"
                    coords="0,0,48,48"
                    href="http://www.cisco.com/" />
                  </map>

                </div>
              </td>
              <td>
                <div class="logo" id="xen_logo">
                  <img alt="Xen Logo"
                  src="/system/application/views/images/xen_logo_med.gif"
                  usemap="#xen_map" />
                  <map name="xen_map" id="xen_map">
                    <area alt="Xen Home Page" shape="rect"
                    coords="0,0,63,63"
                    href="http://www.xen.org/" />
                  </map>
                </div>

              </td>
              <td>
                <div class="logo" id="gentoo_logo">
                  <img alt="Gentoo Logo"
                  src="/system/application/views/images/normal.jpg"
                  usemap="#gentoo_map" />
                  <map name="gentoo_map" id="gentoo_map">
                    <area alt="Gentoo Home Page" shape="rect"
                    coords="0,0,60,60"
                    href="http://www.gentoo.org/" />
                  </map>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </td>
      <td style="border-left:1px solid gray; vertical-align: middle; width:35%;">

        <div id="right">
            <span id="registration_box" style="display:block;text-align:center;outline:2px solid #E8EEFA;border:3px solid #FFFFFF;padding:10px;margin:10px;background-color:#D8DEEA;">


                <?php

			$logger =& Logger::getLogger('registering');
                        try {

                          $vm_status = new STATUS();

                          $vm_status->dbConnect();
                          $running_vms = $vm_status->getRunningVMs();
                          $active_users = $vm_status->getActiveUsers();
                          $total_mem = ($vm_status->getTotalMem() / 1000000);
                          $formated_total_mem = number_format($total_mem);
                          $allocated_mem = $vm_status->getAllocatedMem();
                          $formated_allocated_mem = number_format($allocated_mem);
                        } catch ( Exception $e ) {
                            $logger->error( $e );
                        }
			
			print "<table width='90%'>";
                        print "<tr><td align='right'>Active Users: </td><td align='right'>$active_users </td></tr>";
                        print "<tr><td align='right'>Running VMs: </td><td align='right'>$running_vms </td></tr>";
                        print "<tr><td align='right'>Allocated Memory: </td><td align='right'> $formated_allocated_mem MB</td></tr>";
                        print "<tr><td align='right'>Total Memory: </td><td align='right'> $formated_total_mem MB</td></tr>";
			print "</table>";

                ?>

            </span>
        </div>
      </td>
    </tr>
  </tbody>
</table>
  

<script type="text/javascript">
//<!--

$('#background_image').hide().ready( function(){
  $('#background_image').fadeIn(1500);
});




$('.inField').show().fadeTo(100,.6).focus( function(){
  //When the field is focused,
  if ( this.canned_data == undefined || this.canned_data == $(this).val() ) {
    //When the field has not yet been set,
    //Set the first value as its canned value.
    this.canned_data = $(this).val();
    
    //Empty the field so the user can type the real value.
    $(this).val('');
    
    //Bring the field into full visual focus.
    $(this).fadeTo(100,1);
  }
}).blur( function(){
  //When the field is blurred,
  if ( this.canned_data != undefined && $(this).val()== '' ){
    //If the field has a canned value, and the current value is not empty,
    
    //Fade back out of visual focus.
    $(this).fadeTo(100,.6);
    
    //Set the value back to the canned data.
    $(this).val( this.canned_data );
    
    //Set the canned data back to nothing
    //FIXME:do we need this?
    // this.canned_data = undefined;
  }
});

$('.pinField').show().fadeTo(100,.6).focus( function(){
  //When the field is focused,
  if ( this.canned_data == undefined || this.canned_data == $(this).val() ) {
    //When the field has not yet been set,
    //Set the first value as its canned value.
    this.canned_data = $(this).val();
    
    //Empty the field so the user can type the real value.
    $(this).val('');
    
    //Bring the field into full visual focus.
    $(this).fadeTo(100,1);
  }
})

// $('#txtUserName').trigger('focus');
$('#txtUserName').focus();

$(document).ready( function(){
  $('.logo').each( function(){
    this.VLAB_pos = $(this).offset();
    $(this).css({opacity:.0});
    $(this).css('position','relative');
    $(this).css('left', '-' + $(document).width() + 'px');

    
    $(this).animate(
      {
        
        left : '' + 0 + 'px',
        opacity : 1.0
      }, 1500
    );
    $(this).rotate3Di(360,500);
  });
});

//-->
</script>

</div>
</div>
<!-- content ends -->


<!-- footer begins -->
<div id="footer">
<br/>
<br/>
<span style="font-size:medium;"><b>Questions | Feedback | Comments </b></span>
<span style="font-size:medium;"><b>Contact: cselabs-team-group@nyu.edu</b></span>
<!-- <input type="image" src="/system/application/views/images/header_bg.gif";> -->
</div>
<!-- footer ends -->

</div> <!-- main ends -->
</div>
</body>

</html>

