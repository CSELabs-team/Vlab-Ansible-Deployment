

<?php

require_once ('registration_process.php');

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="views/jquery/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="views/jquery/js/jquery-css-transform.js"></script>
<script type="text/javascript" src="views/jquery/js/rotate3Di-0.9.js"></script>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="keywords" content="" />
<meta name="description" content="" />

<title>Virtual Lab</title>
<link href="views/styles.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div style="height:100%;">
<!-- <img alt="" src="views/images/bg.jpg" id="background_image" /> -->
<div id="main">
	<div id="header">	
		<!--<div id="logo" ><div id="logo_strip"></div></div>  -->
		 <div id="logo_strip" style="padding-top: 5px;height: 50px;">

			<img alt="" src="views/images/vlab_banner_new.jpg" usemap="#banner" />
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
	

<script type="text/javascript">
  //<!--
	function validateFields(theForm)
	{
		if (theForm.txtUserName.value == "")
		{
		alert ("Please enter Username.");
		theForm.txtUserName.focus();
		return false;
		}

		if (theForm.txtPassword.value == "")
		{
		alert ("Please enter your Password.");
		theForm.txtPassword.focus();
		return false;
		}
	}
  //-->
</script>
  
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
                  src="views/images/nsf_logo.gif"
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
                  src="views/images/cisco_logo.jpg"
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
                  src="views/images/xen_logo_med.gif"
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
                  src="views/images/normal.jpg"
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
			$success = 0;
			$message = "";

			if (isset($_POST["posted"])) {

				$posted = $_POST["posted"];
				if ($posted == "1") {

					$logger = Logger::getLogger('registering');
					try {

					$user = new USER();

					$user->register();
					//$message = "something is wrong";
					//$success = 0;
					$message = $user->getStatusMessage();
					$success = $user->getStatus();
					} catch ( Exception $e ) {
						$logger->error( $e );
					}
					
				}


			} 
			
			if ($success == 1) {
				print $message;
			} else {

		?>
	    	<form method="post" action="./registration.php">
		<input type="hidden" name="posted" value="1" />
		<table>
		<tr>
		<td colspan=2>
			<?php print $message ?>
		</td>
		</tr>
		<tr>
		<td>First name:</td>
		<td><input type="text" name="fname" /></td>
		</tr>
		<tr>
		<td>Last name:</td>
		<td><input type="text" name="lname" /></td>
		</tr>
		<tr>
		<td>Email :</td>
		<td><input type="text" name="email" /></td>
		</tr>
		<tr>
		<td colspan=2>
<!--
		<select name="course">
		<option value="none" selected="selected">Select Course</option>
		<option value="CMP405">CMP 405</option>
		<option value="CS3933">CS 393</option>
		<option value="CS9093">CS 9093</option>
		<option value="CS6233">CS 6233</option>
		<option value="CS6813">CS 6813</option>
		<option value="CS6823">CS 6823</option>
		</select>
-->
<?php
include_once '../glf/include/framework.php';
include_once '../glf/include/API.php';
include_once '../glf/include/DBI.php';

DBConnect();
$FormContent = CreateDropdown("course", GetAllClasses());
echo($FormContent);
DBClose();
?>

		</td>
		</tr>
		<td colspan=2>
		Registration Code:
		<input type="text" name="regcode" />
		</td>
		</tr>
		</tr>
		<td colspan=2>
		<input type="submit" value="Register">
		</td>
		</tr>
		</table>
		</form> 
		<?php
			}
		?>
<p>If you encounter any issues please check with your instructor.</a></p>

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
<!-- <input type="image" src="views/images/header_bg.gif";> -->
</div>
<!-- footer ends -->

</div> <!-- main ends -->
</div>
</body>

</html>

