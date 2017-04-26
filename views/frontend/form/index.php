
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
</style>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrap-min.js"></script>
<script src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrap-formhelpers-min.js"></script>
<script type="text/javascript" src="<?php echo TQT_URL;?>/views/frontend/form/js/bootstrapValidator-min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#payment-form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		submitHandler: function(validator, form, submitButton) {
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.card.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val(),
                  			name: $('.card-holder-name').val(),
                  			address_line1: $('.address').val(),
                  			address_city: $('.city').val(),
                  			address_zip: $('.zip').val(),
                  			address_state: $('.state').val(),
                  			address_country: $('.country').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
        },
        fields: {
            street: {
                validators: {
                    notEmpty: {
                        message: 'La calle es requerida y no puede estar vacia'
                    },
					stringLength: {
                        min: 6,
                        max: 96,
                        message: 'La calle debe tener más de 6 y menos de 96 caracteres'
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'La ciudad es requerida y no puede estar vacía.'
                    }
                }
            },
			zip: {
                validators: {
                    notEmpty: {
                        message: 'El codigo postal es obligatorio y no puede estar vacío'
                    },
					stringLength: {
                        min: 3,
                        max: 9,
                        message: 'El codigo postal debe tener más de 3 y menos de 9 caracteres'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'el correo elctrónico no puede estar vacio'
                    },
                    emailAddress: {
                        message: 'este campo no es un correo valido'
                    },
					stringLength: {
                        min: 6,
                        max: 65,
                        message: 'El correo electrónico debe tener más de 6 y menos de 65 caracteres'
                    }
                }
            },
			cardholdername: {
                validators: {
                    notEmpty: {
                        message: 'El titular de la tarjeta es requerido'
                    },
					stringLength: {
                        min: 6,
                        max: 70,
                        message: 'El nombre del titular de la tarjeta debe tener más de 6 y menos de 70 caracteres'
                    }
                }
            },
			cardnumber: {
		selector: '#cardnumber',
                validators: {
                    notEmpty: {
                        message: 'El número de la tarjeta de crédito es requerido'
                    },
					creditCard: {
						message: 'El número de la tarjeta de crédito es invalido'
					},
                }
            },
			expMonth: {
                selector: '[data-stripe="exp-month"]',
                validators: {
                    notEmpty: {
                        message: 'Se requiere el mes de vencimiento'
                    },
                    digits: {
                        message: 'El mes de vencimiento sólo puede contener dígitos'
                    },
                    callback: {
                        message: 'Expired',
                        callback: function(value, validator) {
                            value = parseInt(value, 10);
                            var year         = validator.getFieldElements('expYear').val(),
                                currentMonth = new Date().getMonth() + 1,
                                currentYear  = new Date().getFullYear();
                            if (value < 0 || value > 12) {
                                return false;
                            }
                            if (year == '') {
                                return true;
                            }
                            year = parseInt(year, 10);
                            if (year > currentYear || (year == currentYear && value > currentMonth)) {
                                validator.updateStatus('expYear', 'VALID');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            },
            expYear: {
                selector: '[data-stripe="exp-year"]',
                validators: {
                    notEmpty: {
                        message: 'El año de vencimiento es necesario'
                    },
                    digits: {
                        message: 'El año de vencimiento sólo puede contener dígitos'
                    },
                    callback: {
                        message: 'Expired',
                        callback: function(value, validator) {
                            value = parseInt(value, 10);
                            var month        = validator.getFieldElements('expMonth').val(),
                                currentMonth = new Date().getMonth() + 1,
                                currentYear  = new Date().getFullYear();
                            if (value < currentYear || value > currentYear + 100) {
                                return false;
                            }
                            if (month == '') {
                                return false;
                            }
                            month = parseInt(month, 10);
                            if (value > currentYear || (value == currentYear && month > currentMonth)) {
                                validator.updateStatus('expMonth', 'VALID');
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            },
			cvv: {
		selector: '#cvv',
                validators: {
                    notEmpty: {
                        message: 'El CVV es requerido'
                    },
					cvv: {
                        message: 'El CVV no es valido',
                        creditCardField: 'cardnumber'
                    }
                }
            },
        }
    });
});
</script>
<script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('<?php echo PUBLIC_KEY_STRIPE;?>');

            function stripeResponseHandler(status, response) {

                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
					// show hidden div
					document.getElementById('a_x200').style.display = 'block';
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }


</script>

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

if ($_POST) {

  //el siguiente codigo codigo se ejecutara si el cliente
  //comprovamos que el pago debe ser mayor = a la mitad y menor = al total
  //si es exitosa guardamos el monto, id de la transaccion, fecha y cambiamos estatus de cotizacion
  //si es fracaso entocnes no modificamos nada y colocamos una vista de que fracaso el proceso y un btn de regresar o cerrar
  //todo esta parte la haremos en la clase del shortcode.. y el shortcode se conecta a la clase.. mas nada

  //luego veremos lo de la firma digital
  //y envio de correo al cliente de que su proyecto estara en proceso

}
?>
  <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
  <span class="payment-success">
  <?= $success ?>
  <?= $error ?>
  </span>


  <fieldset>

  <!-- Form Name -->
  <legend>Detalles del Proyecto</legend>


  <div class="form-group">

    <?php if (!empty($arrQuote['name_project'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Nombre del Proyecto</label>
      <input value="<?php echo trim($arrQuote['name_project']); ?>" type="text" disabled="disabled" class="form-control">
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
      <label class="control-label" for="textinput">Cantidad por pieza</label>
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
      <label class="control-label" for="textinput">Fecha requerida </label>
      <input value="<?php echo trim($arrQuote['date_needed']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>

    <?php if (!empty($arrQuote['notes'])) { ?>

    <div class="col-sm-6">
      <label class="control-label" for="textinput">Notas</label>
      <input value="<?php echo trim($arrQuote['notes']); ?>" type="text" disabled="disabled" class="form-control">
    </div>
    <?php } ?>


    <?php if (!empty($arrQuote['cost'])) { ?>
    <div class="col-sm-6">
      <label class="control-label" for="textinput">Costo del Proyecto</label>

      <div class="input-group">
        <span class="input-group-addon">$USD</span>
        <input value="<?php echo number_format($arrQuote['cost'], 2, ',', '.'); ?>" type="text" disabled="disabled" class="form-control currency">
      </div>
    </div>
    <?php } ?>


  </div>


  </fieldset>



<?php /* ?>
  <fieldset>

  <!-- Form Name -->
  <legend>Billing Details</legend>

    <!-- Country -->
  <div class="form-group">
    <label class="col-sm-4 control-label" for="textinput">Country</label>
    <div class="col-sm-6">
      <!--input type="text" name="country" placeholder="Country" class="country form-control"-->
      <div class="country bfh-selectbox bfh-countries" name="country" placeholder="Select Country" data-flags="true" data-filter="true"> </div>
    </div>
  </div>

    <!-- State -->
  <div class="form-group">
    <label class="col-sm-4 control-label" for="textinput">State</label>
    <div class="col-sm-6">
      <input type="text" name="state" maxlength="65" placeholder="State" class="state form-control">
    </div>
  </div>

    <!-- City -->
  <div class="form-group">
    <label class="col-sm-4 control-label" for="textinput">City</label>
    <div class="col-sm-6">
      <input type="text" name="city" placeholder="City" class="city form-control">
    </div>
  </div>

  <!-- Street -->
  <div class="form-group">
    <label class="col-sm-4 control-label" for="textinput">Street</label>
    <div class="col-sm-6">
      <input type="text" name="street" placeholder="Street" class="address form-control">
    </div>
  </div>

  <!-- Postcal Code -->
  <div class="form-group">
    <label class="col-sm-4 control-label" for="textinput">Postal Code</label>
    <div class="col-sm-6">
      <input type="text" name="zip" maxlength="9" placeholder="Postal Code" class="zip form-control">
    </div>
  </div>

  </fieldset>
  <?php */ ?>


  <fieldset>
    <legend>Detalles de la Tarjeta</legend>

    <?php /* >?
    <!-- Card Number -->
    <div class="form-group">
      <label class="col-sm-4 control-label" for="textinput">Amount to be paid
      <span class="spantotalcost">Total cost <span class="bolder"><?php echo number_format($arrQuote['cost'], 2, ',', '.');?> $USD</span> </span>
      </label>
      <div class="col-sm-6">
        <div class="input-group">
          <span class="input-group-addon">$USD</span>
          <input name="valuePay"  type="number" value="1000" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency" id="valuePay" placeholder="value"   aria-label="Amount (to the nearest dollar)">
        </div>

      </div>
    </div><?php */ ?>

    <!-- Card Holder Name -->
    <div class="form-group">
      <label class="col-sm-4 control-label"  for="textinput">Titular de la tarjeta</label>
      <div class="col-sm-6">
        <input type="text" name="cardholdername" maxlength="70" placeholder="Card Holder Name" class="card-holder-name form-control">
      </div>
    </div>

    <!-- Card Number -->
    <div class="form-group">
      <label class="col-sm-4 control-label" for="textinput">Número de la tarjeta</label>
      <div class="col-sm-6">
        <input type="text" id="cardnumber" maxlength="19" placeholder="Card Number" class="card-number form-control">
      </div>
    </div>

    <!-- Expiry-->
    <div class="form-group">
      <label class="col-sm-4 control-label" for="textinput">Fecha de Expiración</label>
      <div class="col-sm-6">
        <div class="form-inline">
          <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control">
            <option value="01" selected="selected">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <span> / </span>
          <select name="select2" data-stripe="exp-year" class="card-expiry-year stripe-sensitive required form-control">
          </select>
          <script type="text/javascript">
            var select = $(".card-expiry-year"),
            year = new Date().getFullYear();

            for (var i = 0; i < 12; i++) {
                select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"))
            }
        </script>
        </div>
      </div>
    </div>

    <!-- CVV -->
    <div class="form-group">
      <label class="col-sm-4 control-label" for="textinput">CVV/CVV2</label>
      <div class="col-sm-3">
        <input type="text" id="cvv" placeholder="CVV" maxlength="4" class="card-cvc form-control">
      </div>
    </div>

    <!-- Important notice -->
    <div class="form-group">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title cwhite">Noticia importante</h3>
      </div>
      <div class="panel-body">
        <p>Se cargará a la tarjeta
        <span class="bolder"><?php echo number_format($pago, 2, ',', '.');?> $USD</span>
        Después de confirmar.<br>
        <?php echo $textPago ?>
        </p>
      </div>
    </div>

    <!-- Submit -->
    <div class="control-group">
      <div class="controls">
        <center>
          <button name="payment-btn" class="btn btn-primary pull-right" type="submit">Pagar</button>
        </center>
      </div>
    </div>
  </fieldset>
</form>
