
<link rel="stylesheet" href="<?php echo TQT_URL;?>/views/frontend/form/css/bootstrap-min.css">
<link rel="stylesheet" href="<?php echo TQT_URL;?>/views/frontend/form/css/bootstrap-formhelpers-min.css" media="screen">
<link rel="stylesheet" href="<?php echo TQT_URL;?>/views/frontend/form/css/bootstrapValidator-min.css"/>
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo TQT_URL;?>/views/frontend/form/css/bootstrap-side-notes.css" />
<style type="text/css">
.col-centered {
    display:inline-block;
    float:none;
    text-align:left;
    margin-right:-4px;
}
.row-centered {
	margin-left: 9px;
	margin-right: 9px;
}
.cwhite{
  color: white !important;
}
.navbar {
    margin-bottom: 0px !important;
}
.caret:after {
    content: "" !important;
}
.bolder{
  font-weight: bolder;
}
.spantotalcost{
  display: block;
  font-size: 11px;
  font-weight: inherit;
}
input.currency {
    text-align: center;
    padding-right: 15px;
}
.input-group .form-control {
    float: none;
}
.input-group .input-buttons {
    position: relative;
    z-index: 3;
}
label.control-label {
    font-size: 14px;
    font-weight: inherit;
}
</style>
<!-- <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrap-min.js"></script>
<script src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrap-formhelpers-min.js"></script>
<script type="text/javascript" src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrapValidator-min.js"></script>

 -->
<form action="" method="POST" id="payment-form" class="form-horizontal">
  <div class="row row-centered">
  <div class="col-md-6 col-md-offset-3">
  <noscript>
  <div class="bs-callout bs-callout-danger">
    <h4>JavaScript is not enabled!</h4>
    <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
  </div>
  </noscript>
  <?php



?> 


  <fieldset>
  
  <!-- Form Name -->
  <legend>Project Details</legend>
  
  
  <div class="form-group">
    
    <?php if (!empty($arrQuote['name_project'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Nombre del proyecto</label>
      <input value="<?php echo trim($arrQuote['name_project']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>
    <?php 

    if (!empty($arrManufacture)) { 

      foreach ($arrManufacture as $key => $value) { ?>
        <div class="col-sm-6">
          <label class="control-label" for="textinput"><?php echo $value['pregunta'];?></label>
          
           
            <input value="<?php echo $value['respuesta']; if(!empty($value['text'])) echo ': '.$value['text'] ?>" type="text" disabled="disabled" class="form-control">
             
           
        </div>
        
    

    <?php }
    } ?>

    <?php if (!empty($measure)) {  ?>
      <div class="col-sm-6">
        <label class="control-label"><?php _e( 'Medida', 'treitusQuote' ); ?></label>
        <input class="form-control" type="text" name="TqtForm[measure]" disabled="disabled" value="<?php echo $measure['name']; ?>"  />
      </div>
    <?php } ?> 

    <?php if (!empty($arrQuote['files'])) { /*?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Files</label>
      <input value="<?php echo trim($arrQuote['files']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php */} ?>
    <?php if (!empty($arrQuote['piece'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Cantidad de piezas</label>
      <input value="<?php echo trim($arrQuote['piece']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>
    <?php if (!empty($arrQuote['materials'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Material</label>
      <input value="<?php echo trim($arrQuote['materials']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>
    <?php if (!empty($arrQuote['date_needed'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Necesario para fecha</label>
      <input value="<?php echo trim($arrQuote['date_needed']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>

    <?php if (!empty($arrQuote['notes'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Nota</label>
      <input value="<?php echo trim($arrQuote['notes']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>



    <?php if (!empty($arrQuote['city'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput"><?php _e( 'Ciudad', 'treitusQuote' ); ?></label>
      <input value="<?php echo trim($arrQuote['city']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>
    <?php if (!empty($arrQuote['zipcode'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput"><?php _e( 'Codigo ZIP', 'treitusQuote' ); ?></label>
      <input value="<?php echo trim($arrQuote['zipcode']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>
    

    <?php if (!empty($arrQuote['cost'])) { ?>
    <div class="col-sm-6">
      <label class="control-label" for="textinput">Costo proyecto</label>
      
      <div class="input-group">
        <span class="input-group-addon">$USD</span>
        <input value="<?php echo number_format($arrQuote['cost'], 2, ',', '.'); ?>" type="text" disabled="disabled" class="form-control currency">
      </div>
    </div>
    <?php } ?>






    


  </div>
  

  </fieldset>

    
    <!-- Submit -->
    <div class="control-group">
      <div class="controls">
        <center>
          <a name="payment-btn" class="btn btn-primary" type="submit" href="<?php echo home_url();?>" >Inicio</a>
        </center>
      </div>
    </div>
  
</form>