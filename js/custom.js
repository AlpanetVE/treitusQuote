jQuery(document).ready(function($){

  jQuery("#startProyect").validate(
  {
    rules:
    {
      tqt_proyectname:
      {
        required: true
      },
      tqt_qty:
      {
        required:true,
        number:true
      },
      tqt_date:
      {
        required:true
      },
      tqt_city:
      {
        required:true
      },
      tqt_email:
      {
        required:true,
        email:true
      },
      tqt_name:
      {
        required:true
      },
      tqt_lastname:
      {
        required:true
      },
      tqt_phone:
      {
        required:true,
        number:true
      },
      tqt_zipcode:
      {
        required:true
      },
      tqt_terms:
      {
        required:true
      },

    },
    messages:
    {
      tqt_proyectname:
      {
        required: "Please enter your name project"
      },
      tqt_qty:
      {
        required:"Please enter a quantity"
      },
      tqt_date:
      {
        required:"Please enter a Date for your Project"
      },
      tqt_email:
      {
        required: "Please enter your email address."
      },
      tqt_terms:
      {
        required:"You need to Accept the Terms and Conditions"
      }
    }
  });

  $( "#startProyect" ).submit(function( e ) {

    var isvalidate=$("#startProyect").valid();

    var step = $(this).data('tqt_step');
    
    if (isvalidate) {
      if (step == 'a' && validateFiles() && validateRadios() ) {
        showStep('b');
        e.preventDefault();

      }else if(step == 'b'){
        $(this).find(':input[type="submit"]').prop('disabled', true);
        //submit
      }else{
        e.preventDefault();
      }
    }

  });

  $( "body" ).on( "click", ".back", function(e) {
    showStep('a');
  });


//
function validateFiles(){
  var flag = true;
   $('.filesRequired').each(function(){
        if ($(this).find('input[type="file"]').val()!='') {
          $(this).find('.error').hide();
          flag = true;
        }else{
          $(this).find('.error').show();
          if (flag) {
              $("html, body").animate({ scrollTop: $(this).offset().top -120 }, 1000);
              flag = false;
          }
        }
    });
   return flag;
}
function validateRadios(){
  var flag = true;
   $('.groupRequired').each(function(){

        if ($('input[name='+$(this).data('name')+']:checked').val()) {
          $(this).find('.error').hide();
        }else{
          $(this).find('.error').show();
          if (flag) {
              $("html, body").animate({ scrollTop: $(this).offset().top -120 }, 1000);
              flag = false;
          }
        }
    });

   return flag;
}
$( "body" ).on( "click", ".option-manufacture", function(e) {
 
      var group_child_id = $(this).data('group_child_id');
      var have_text = $(this).data('have_text');
      var group_id = $(this).data('group_id');
      

      $(".option_text_"+group_id).prop('disabled', true);
      if (group_child_id!='') {

        data={
          action:'getCheckManufacture',
          group_id:group_child_id,
        };

        var group_cont = $(this).parent('.radio-option').siblings('.next-group');

        getCheckManufacture(group_cont,data);

      }else if(have_text == '1'){

        $("#group_option_"+$(this).val()).prop('disabled', false).focus();
      }
  })


  data={
    action:'getCheckManufacture',
    group_id:'1'
  };
  getCheckManufacture($('#first-group'),data);

});

function getCheckManufacture(cont,data){
  jQuery.ajax({
    url:"../wp-content/plugins/treitusQuote/treitusQuote-ajax.php",
    data: data,
    type:"POST",
    dataType: 'json',
    success:function(data){
      cont.html(data.html);
    }
  });

}
function showStep(step){
  
  jQuery("#startProyect").data('tqt_step',step);

  jQuery(".step").fadeOut("fast", function(){
      jQuery(".step-"+step).fadeIn("slow");
    });

}