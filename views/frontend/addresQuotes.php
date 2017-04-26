<div>
	<form action="" method="POST" id="payment-form" class="form-horizontal">
		<h3>Editar Dirección</h3>
		<div class="col-sm-6">
	      <label class="control-label" for="textinput">Ciudad</label>
	      <input value="<?php echo trim($arrAdress['0']['city']); ?>" name="city" type="text" class="form-control">
	    </div>
	    <div class="col-sm-6">
	      <label class="control-label" for="textinput">Zip code</label>
	      <input value="<?php echo trim($arrAdress['0']['zipcode']); ?>" name="zipcode" type="text" class="form-control">
	    </div>


	    <!-- Submit -->
	    <div class="control-group col-xs-12">
	      <div class="controls">
	        <center>
	        <input type="submit" name="update_address" value="Modificar" class="btn btn-success"  >
				<a name="home3-btn" class="btn btn-primary" type="submit" href="<?php echo home_url();?>" >Inicio</a>
	        </center>
	      </div>
	    </div>

	</form>
	
</div>