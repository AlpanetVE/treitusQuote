<?php
//Events
//TEMPORALMENTE BORRADO PARA USAR CLASE


add_action( 'wp_enqueue_scripts', 'scripts_front' );
add_shortcode( 'tqt_form_start_project', 'tqt_start_project' );
add_shortcode( 'TQT_BTN','tqt_btn');
add_shortcode( 'TQT_STATUS_ORDERS','tqt_status_orders');
add_shortcode( 'TQT_PROCESS_PAY_QUOTE', 'tqt_process_pay_quote' );
add_shortcode( 'tqt_form_thanks_project','tqt_thanks_project');
add_shortcode( 'TQT_DETAIL_QUOTE', 'tqt_detail_quote' );
add_shortcode( 'TQT_ADDRES','tqt_addres');
function scripts_front() {

	wp_register_script('tqt_datepicker', TQT_URL . 'js/jquery.datetimepicker.full.min.js',array('jquery'));
	wp_register_script('tqt_validator', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js',array('jquery'));
	wp_register_script('tqt_custom', TQT_URL . 'js/custom.js',array('jquery','tqt_validator'));
	wp_register_style('tqt_component', TQT_URL . 'css/component.css', array(), '1', 'all');
	wp_register_style('tqt_datetime', TQT_URL . 'css/datetimepicker.css', array(), '1', 'all');
	wp_register_style('tqt_front_style', TQT_URL.'views/frontend/css/style.css' );


	wp_enqueue_script('tqt_datepicker');
	wp_enqueue_script('tqt_custom');
	wp_enqueue_script('tqt_validator');
	wp_enqueue_style('tqt_component');
	wp_enqueue_style('tqt_datetime');
	wp_enqueue_style('tqt_front_style');

	if ( !is_user_logged_in() ) {
	    add_filter('show_admin_bar', '__return_false');
	    remove_action('wp_head', '_admin_bar_bump_cb');
	}
}

function tqt_addres( $atts ) {
	$user 			= wp_get_current_user();
	$id_user		= $user->ID;
	$treitusQuote 	= new treitusQuote();
	$treitusQuote-> processAddres($id_user);
	$arrAdress		= $treitusQuote-> getAddres($id_user);

	if (!empty($arrAdress)) {

		ob_start();
		include TQT_ABSPATH . 'views/frontend/addresQuotes.php';
		$output = ob_get_clean();

	}else{
		$output	= 	'<div class="text-center" style="font-size: 30px;height: 200px;display: table;width: 100%;">
						<span style="display: table-cell;vertical-align: middle;">
							Dirección no encontrada
						</span>
					</div>';
	}


	return $output;
}
function tqt_status_orders( $atts ) { // New function parameter $content is added!
	extract( shortcode_atts( array(
		'classes' 	=> 'big',
		'text'		=> 'Start Now',
		'href'		=> home_url().'/startaproject'//start-project
	), $atts ) );

	$user 				= wp_get_current_user();
	$user_email 		= $user->data->user_email;
	$id_user			= $user->ID;
	$urlPay 			= home_url().'/pay-quote/?code=';
	$urlSee 			= home_url().'/detail-of-quotization/?code=';
	$treitusQuote 		= new treitusQuote();
	$arrQuotePendiente	= $treitusQuote-> getQuoteList( null, null ,1,$id_user);
	$arrQuotePorPagar	= $treitusQuote-> getQuoteList( null, null ,2,$id_user);
	//$arrQuoteProceso	= $treitusQuote-> getQuoteList( null, null ,3,$id_user);
	$arrQuotePorPagarDos= $treitusQuote-> getQuoteList( null, null ,3,$id_user);
	$arrQuoteFinalizado	= $treitusQuote-> getQuoteList( null, null ,4,$id_user);


	if (!empty($arrQuotePendiente) || !empty($arrQuotePorPagar) || !empty($arrQuoteProceso) || !empty($arrQuotePorPagarDos) || !empty($arrQuoteFinalizado)) {

		ob_start();
		include TQT_ABSPATH . 'views/frontend/listQuotes.php';
		$output = ob_get_clean();

	}else{
		$output	= 	'<div class="text-center" style="font-size: 30px;height: 200px;display: table;width: 100%;">
						<span style="display: table-cell;vertical-align: middle;">
							No se encontraron proyectos
						</span>
					</div>';
	}


	return $output;
}
function tqt_btn( $atts ) { // New function parameter $content is added!
	extract( shortcode_atts( array(
		'classes' 	=> 'big',
		'text'		=> 'Empezar ahora',
		'href'		=> home_url().'/startaproject' // start-project
	), $atts ) );
	?>
	<style type="text/css">
		.big{
			font-size: 25px;
			border-radius: 10px;
		}
	</style>
	<?php
	$output	= 	"<div class='text-center'>
					<a href='$href' class='btn btn-primary $classes'>$text</a>
				</div>";

	return $output;
}
function tqt_start_project( $atts ) { // New function parameter $content is added!
	extract( shortcode_atts( array(
	'page' => 'full'
	), $atts ) );
	$user = wp_get_current_user();
$treitusQuote 	= new treitusQuote();
$direccion=$treitusQuote->getAddres($user->ID);
$ciudad = !empty($direccion[0]['city']) ? $direccion[0]['city'] : ' ';
$zipcode = !empty($direccion[0]['zipcode']) ? $direccion[0]['zipcode'] : ' ';

	if ($page=='middle') {
		$class_col_cont	= ' col-sm-6 col-sm-offset-3';
		$class_col_inner= ' col-sm-10 col-sm-offset-1';
	}else{
		$class_col_cont	= ' col-sm-6 col-sm-offset-3 form-quote';
		$class_col_inner= ' col-sm-10 col-sm-offset-1';
	}

	if ( ! is_user_logged_in() && ! is_page( 'login' ) ) {
		$user_confir	= false;
	}else{
		$user_confir	= true;
	}

	ob_start();
	?>

<script>
	jQuery(document).ready(function($){
		 jQuery.datetimepicker.setLocale('es');
		jQuery('#table-date').datetimepicker({
			timepicker:false,
			format:'Y-m-d',
			formatDate:'Y-m-d',
			minDate:'-1970/01/02' // yesterday is minimum date
		});
		jQuery('.continue').attr("disabled", false);

	});
</script>
<style type="text/css">
	.groupRequired .error{
		margin-left: 10px;
	}
</style>
<div class="container">
	<form id="startProyect" name="make_quote" enctype="multipart/form-data" class="" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" data-tqt_step="a">



		<div class="row step step-a <?php echo $class_col_cont;?>">

			<div class="<?php echo $class_col_inner;?>">

				<div class="form-group">
					<label for="tqt_project">Nombre del Proyecto (Requerido)</label>
					<input type="text" name="tqt_proyectname" id="tqt_project" class="form-control" value="">

				</div>

				<div id="first-group"></div>
				<div class="form-group">
					<div class="col-md-12 title-group groupRequired" data-name='group_measure'>
						<label for="tqt_quantity" >Medidas (Requerido)</label>
						<label class='error' style='display: none;'>Este campo es requerido</label>
					</div>
					<div class="col-md-4">
							<input type="radio" name="group_measure" value="1" id="group_measure_1" >
							<label for="group_measure_1">Pulgadas</label>
					</div>
					<div class="col-md-4">
							<input type="radio" name="group_measure" value="2" id="group_measure_2" >
							<label for="group_measure_2">Milimetros</label>
					</div>
					<div class="col-md-4">
							<input type="radio" name="group_measure" value="3" id="group_measure_3" >
							<label for="group_measure_3">Centimetros</label>
					</div>

				</div>
				<div class="form-group">

					<label for="tqt_quantity">Cantidad por pieza (Requerido)</label>
					<input type="text" name="tqt_qty" id="tqt_quantity" class="form-control" value="">

				</div>



				<?php
				 if(!$user_confir){?>
					<div class="form-group">

						<label for="tqt_email">Correo electrónico (Requerido)</label>
						<input type="text" name="tqt_email" id="tqt_email" class="form-control" value="">

					</div>
				<?php }?>
				<div class="form-group">
					<label for="tqt_notes"> Notas </label>
					<textarea name="tqt_notes" id="tqt_notes" rows="4" cols="20" class="form-control" ></textarea>
				</div>

				<input disabled="disabled" type="submit" name="tqt_send"  class="btn btn-primary pull-right continue" value="Continuar">
			</div>

		</div>




		<div class="row step step-b <?php echo $class_col_cont;?>"  style="display: none;" >

			<div class="<?php echo $class_col_inner;?>">
				<div class="form-group">
					<label for="table-date"> Fecha en que se requiere </label>
					<input type="text" name="tqt_date" value="" id="table-date" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="tqt_city">Ciudad donde se requiere </label>
					<input type="text" name="tqt_city" id="tqt_city" class="form-control" value="<?php echo $ciudad; ?>">
				</div>
				<div class="form-group">
					<label for="tqt_zipcode">Código postal</label>
					<input type="text" name="tqt_zipcode" id="tqt_zipcode" class="form-control" value="<?php echo $zipcode; ?>">
				</div>
				<?php
				 if(!$user_confir){?>
					<div class="form-group">
						<label for="tqt_name">Tu nombre (Requerido)</label>
						<input type="text" name="tqt_name" id="tqt_name" class="form-control" value="">
					</div>

					<div class="form-group">
						<label for="tqt_lastname">Tu apellido (Requerido)</label>
						<input type="text" name="tqt_lastname" id="tqt_lastname" class="form-control" value="">
					</div>
				<?php }?>
				<div class="form-group">
					<label for="tqt_company">Empresa</label>
					<input type="text" name="tqt_company" id="tqt_company" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="tqt_phone">Número de teléfono</label>
					<input type="text" name="tqt_phone" id="tqt_phone" class="form-control" value="">
				</div>



				<?php if(!$user_confir){ ?>
					<div class="form-group">
						<script src='https://www.google.com/recaptcha/api.js'></script>
						<div class="g-recaptcha" data-sitekey="6LeH4A8UAAAAAEs6KlRQVvQTFB4MKVYv0QsIs3Vb">
	                    </div>
					</div>
				<?php } ?>


				<div class="checkbox">
					<label for="tqt_terms">Términos y condiciones (Requerido) </label>
					<input type="checkbox" name="tqt_terms" id="tqt_terms" value="terms">
				</div>

				<input type="hidden" name="tqt_tipo" value="tqt_star_project">

				<input type="submit" name="tqt_send"  class="btn btn-primary pull-right" value="Send">
				<span class="btn btn-default pull-right back">Volver</span>

			</div>



		</div>


		</form>
	</div>







</div>


  <?php
  $output = ob_get_clean();
  return $output;
}

function tqt_thanks_project(){
  ob_start();
?>
<div class="container thanks">
  <div class="row form-quote">
    <div class="col-sm-6 col-sm-offset-3 text-center">
        <h2> ¡Gracias! </h2>
        <p>
          <a href="<?php echo home_url() ?>">Inicio</a>
        </p>
    </div>

  </div>

</div>

<?php
$output = ob_get_clean();
return $output;
}
function tqt_detail_quote() {
	extract( shortcode_atts( array(
	'title' => 'Title',
	'no_of_post' => '8',
	'event_look' => 'simple',
	), $atts ) );

	$code	 		= $_GET['code'] ? $_GET['code'] :'';
	$setCod	 		= $code ? get_permalink().'?code='.$code :'';

	$user = wp_get_current_user();
	$user_email 	= $user->data->user_email;// ["user_email"]  $user_info->user_email;
	$id_user		= $user->ID;

	$treitusQuote 	= new treitusQuote();
	$_POST['setCod']= $setCod;

	if ( ! is_user_logged_in() && ! is_page( 'login' ) ) {
		$url='/login/?redirect_to='.urlencode($setCod);
		$treitusQuote->redirect_user( $url );
	    exit;
	}else{

		if (empty($code)) {
			$treitusQuote->redirect_user( '' );
			exit;
		}else{

			$arrQuote=$treitusQuote-> getProcessQuote( $code, null, false, $id_user);
			if (!empty($arrQuote)) {
				$arrPayQuote	= $treitusQuote-> getSumPayQuote( $code);

				$arrManufacture = $treitusQuote-> getManufactureByQuote( $code);

				$cost 	= (float)$arrQuote['cost'];
				$pago	= $cost/2;
				if(empty($arrPayQuote["payed"])){
					$textPago = 'This is the first half of the payment.';
					$status   = 3;
				}
				else{
					$textPago = 'This is the last half of the payment.';
					$status   = 4;
				}

				switch ($treitusQuote->getPayQuoteAction()) {
			        case 'showForm':
			        	ob_start();
						include TQT_ABSPATH . 'views/frontend/form/detail_quote.php';
						$result = ob_get_clean();
			            break;
			    }

			}else{
				$result = '<div class="form-horizontal afterProcess">
							  	<div class="row row-centered center">
							  		<div class="col-md-6 col-md-offset-3 text-center">
							  			<div style="margin: 20px;font-size: 35px;margin-bottom: 110px;">Error! Cotización no encontrada</div>
						  			</div>
						  		</div>
						 	</div>';
			}


		}


	}


   return $result;
}
function tqt_process_pay_quote( $atts ) { // New function parameter $content is added!
	extract( shortcode_atts( array(
	'title' => 'Title',
	'no_of_post' => '8',
	'event_look' => 'simple',
	), $atts ) );

	$code	 		= $_GET['code'] ? $_GET['code'] :'';
	$setCod	 		= $code ? get_permalink().'?code='.$code :'';

	$user = wp_get_current_user();
	$user_email 	= $user->data->user_email;// ["user_email"]  $user_info->user_email;
	$id_user		= $user->ID;

	$treitusQuote 	= new treitusQuote();
	$_POST['setCod']= $setCod;

	if ( ! is_user_logged_in() && ! is_page( 'login' ) ) {
		$url='/login/?redirect_to='.urlencode($setCod);
		$treitusQuote->redirect_user( $url );
	    exit;
	}else{

		if (empty($code)) {
			$treitusQuote->redirect_user( '' );
			exit;
		}else{

			$arrQuote = $treitusQuote-> getQuote( $code, null, true );

			if (empty($arrQuote['id_user']) && $arrQuote['email'] == $user_email) {
				$show = true;
			}else if (!empty($arrQuote['id_user']) && $arrQuote['id_user'] == $id_user) {
				$show = true;
			}else{
				$show = false;
			}

			if (!empty($arrQuote) && $show) {
				$arrPayQuote=$treitusQuote-> getSumPayQuote( $code);
				$cost 	= (float)$arrQuote['cost'];
				$pago	= $cost/2;
				if(empty($arrPayQuote["payed"])){
					$textPago = 'Esta es la primera mitad del pago total.';
					$status   = 3;
				}
				else{
					$textPago = 'Esta es la ultima mitad del pago total.';
					$status   = 4;
				}

				switch ($treitusQuote->getPayQuoteAction()) {
			        case 'showForm':
			        	ob_start();
						include TQT_ABSPATH . 'views/frontend/form/index.php';
						$result = ob_get_clean();
			            break;
			        case 'processForm':
			        	require TQT_ABSPATH . 'views/frontend/form/lib/Stripe.php';

			            $result = $treitusQuote->processPayQuoteAction($_POST, $arrQuote,$pago,$status, $id_user);

			        	ob_start();
			        	include TQT_ABSPATH . 'views/frontend/form/afterProcess.php';
						$result = ob_get_clean();
			            break;
			    }

			}else{
				$result = '<div class="form-horizontal afterProcess">
							  	<div class="row row-centered center">
							  		<div class="col-md-6 col-md-offset-3 text-center">
							  			<div style="margin: 20px;font-size: 35px;margin-bottom: 110px;">Error! Cotización no encontrada</div>
						  			</div>
						  		</div>
						 	</div>';
			}


		}


	}


   return $result;
}


?>
