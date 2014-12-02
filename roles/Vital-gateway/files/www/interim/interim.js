
// $('body').hide();
$(document).ready(function(){
  $('.body_div').hide().fadeIn(500);
  // $('.body_div').hide().fadeIn(2000);
  $('.vlab_tool_box > ul > li').fadeTo( 0, .7 ).hover( function(){
    $(this).stop().fadeTo( 300, 1 );
  }, function(){
    $(this).stop().fadeTo( 300, .7 );
  });
  
  $('.vlab_tool_box').fadeTo( 0, .7 ).hover( function(){
    $(this).stop().fadeTo( 300, 1 );
  }, function(){
    $(this).stop().fadeTo( 300, .7 );
  });
  
  
  // $('.double_check').click( function(evt) {
    // var anchor = this;
    
    // if ( anchor.go_thru == undefined ) {
      
      // $('<p class="dialog">Are you sure? This operation might make you lose data.</p>').dialog({modal: true,
        // show: 'puff',
        // hide: 'puff',
        // buttons: {  "Cancel": function(){
          // $(this).dialog("close");
        // }, "Continue": function(){
          // $(this).dialog("close");
          // anchor.go_thru = true;
          // $(anchor).removeClass('double_check').click();
        // }}
      // });
      // return false;
    // }
    // return true;
  // });
  
  // $('.busy_widget').
});

$(window).unload(function(){
  // $('.body_div').hide();
  // alert('unloading');
});


