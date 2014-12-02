
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

