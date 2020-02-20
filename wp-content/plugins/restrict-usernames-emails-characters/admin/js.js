jQuery(document).ready( function() {
  jQuery('#submit-ftb1-BENrueeg_RUE').on('click',function()  { jQuery(this).val(BENrueeg_RUE_jsParams.wait_a_little); jQuery('#form-BENrueeg_RUE_1').submit(); });
  jQuery('#submit-ftb2-BENrueeg_RUE').on('click',function()  { jQuery(this).val(BENrueeg_RUE_jsParams.wait_a_little); jQuery('#form-BENrueeg_RUE_2').submit(); });
  jQuery('#submit-ftb3-BENrueeg_RUE').on('click',function()  { jQuery(this).val(BENrueeg_RUE_jsParams.wait_a_little); jQuery('#form-BENrueeg_RUE_3').submit(); });
});

jQuery(document).ready( function() {
  setTimeout("jQuery('#message.updated').slideUp('slow');", 3000);
    });

jQuery(document).ready(function() {
   jQuery('#form-BENrueeg_RUE_3,#form-BENrueeg_RUE_2').submit(function() { 
      jQuery(this).ajaxSubmit({
         success: function(){
            jQuery('#BENrueeg_RUE_saveResult').html("<div id='BENrueeg_RUE_saveMessage' class='BENrueeg_RUE_successModal'></div>");
            jQuery('#BENrueeg_RUE_saveMessage').append(BENrueeg_RUE_jsParams.reset_succ).show();
			setTimeout("jQuery('#BENrueeg_RUE_saveMessage').slideUp('slow');", 2500)
			setTimeout("location.reload(true);",2500);
         }
      }); 
      return false; 
   });
});

function BENrueeg_RUE_remv_lines() {
  if (! BENrueeg_RUE_jsParams.is_mu) {
  var line1 = document.getElementById("remv_lines1").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines1").value = line1;	
  }
  var line2 = document.getElementById("remv_lines2").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines2").value = line2;
  var line3 = document.getElementById("remv_lines3").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines3").value = line3;	
  var line4 = document.getElementById("remv_lines4").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines4").value = line4;	
  var line5 = document.getElementById("remv_lines5").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines5").value = line5;	
  var line4_2 = document.getElementById("remv_lines4_2").value.replace(/^\s*\n/gm, '');
  document.getElementById("remv_lines4_2").value = line4_2;	
}

jQuery(document).ready(function(){
var $divNTBvers = jQuery("#BENrueeg_RUE-mm411112-divtoBlink"); 
var backgroundInterval = setInterval(function(){
    $divNTBvers.toggleClass("BENrueeg_RUE-mm411112-backgroundRed");
 },1000)	
}); 

jQuery(document).ready(function() {
	
    jQuery("#BENrueeg_RUE_export__file-sub").click(function() {
var ext = jQuery('#BENrueeg_RUE_jsonfileToUpload').val().split('.').pop().toLowerCase();
//if($.inArray(ext, ['json','png','jpg','jpeg']) == -1) {
if(jQuery.inArray(ext, ['json']) == -1) {
    alert(BENrueeg_RUE_jsParams.msg_valid_json);
} else {
		
    jQuery(document).ajaxStart(function() {
    jQuery('#BENrueeg_RUE_export-loading-div-background').show();
    }).jQuery(document).ajaxStop(function() {
    jQuery('#BENrueeg_RUE_export-loading-div-background').hide();
    });
}	
	});
            jQuery('#BENrueeg_RUE_export__file').ajaxForm(function() {
				
				 jQuery('#BENrueeg_RUE_export-loading-div-background').hide();
                 jQuery( ".BENrueeg_RUE_export__file" ).show(); 
				 jQuery('.BENrueeg_RUE_export__file').delay(4000).slideUp('slow');
				 //window.location.replace(BENrueeg_RUE_jsParams.get_link);
				 setTimeout("location.reload(true);",2000);
                 //location.reload();
            }); 

}); 
