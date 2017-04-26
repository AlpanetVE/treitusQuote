<?php


defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class emails {
	/**
	 * treitusQuote version.
	 *
	 * Increases whenever a new plugin version is released.
	 *
	 * @since 1.0.0
	 * @const string
	 */
	const version = '1.0.0';

	/**
	 * treitusQuote internal plugin version ("options scheme" version).
	 *
	 * Increases whenever the scheme for the plugin options changes, or on a plugin update.
	 *
	 * @since 1.0.0
	 * @const int
	 */
	const db_version = 32;

	/**
	 * treitusQuote "table scheme" (data format structure) version.
	 *
	 * Increases whenever the scheme for a $table changes,
	 * used to be able to update plugin options and table scheme independently.
	 *
	 * @since 1.0.0
	 * @const int
	 */
	const table_scheme_version = 3;


	/**
	 * Instance of the controller.
	 *
	 * @since 1.0.0
	 * @var treitusQuote_*_Controller
	 */
	public static $controller;

	/**
	 * Actions that have a view and admin menu or nav tab menu entry.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $view_actions = array();

	private $db;

	function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->table_quote = $wpdb->prefix."tqt_quote";
		$this->table_pay_quote = $wpdb->prefix."tqt_pay_quote";
		$this->table_tqt_config = $wpdb->prefix."tqt_config";
		$this->db_version = "1.0";

    	$this->headers = array('Content-Type: text/html; charset=UTF-8','From: noreply@treitus.com');
	}

public function header(){
	$logo = TQT_URL."images/logo.png";

  $txt = "<!DOCTYPE html>
      <html lang='es'><body style='margin: 0; padding: 0;'>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td style='padding: 10px 0 30px 0;'>
				<table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 1px solid #cccccc; border-collapse: collapse;'>
					<tr>
						<td align='center' bgcolor='#0C4158' style='padding: 40px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;'>
							<img src=".$logo." alt='Treitus' style='display: block;' />
						</td>
					</tr>";
		return $txt;
}




public function footer($version='1'){
	$fb = TQT_URL."images/facebook-icon.png";
	$ln = TQT_URL."images/linkedin-icon.png";

		$txt = "<tr>
						<td bgcolor='#34CAB8' style='padding: 30px 30px 30px 30px;'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td style='color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;' width='75%'>
										&copy; Treitus 2016<br/>
										<p> Parque Tecnológico Orión <br/>
									 Av. H. Colegio Militar 4709, Nombre de Dios, <br/>
									  31150 Chihuahua, Chih. México </p>
									</td>
									<td align='right' width='25%'>
										<table border='0' cellpadding='0' cellspacing='0'>
											<tr>
												<td style='font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;'>
													<a href='https://www.linkedin.com/company/treitus' style='color: #ffffff;'>
														<img src=".$ln." alt='Linkendin' style='display: block;' border='0' />
													</a>
												</td>
												<td style='font-size: 0; line-height: 0;' width='20'>&nbsp;</td>
												<td style='font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;'>
													<a href='https://www.facebook.com/treitus/' style='color: #ffffff;'>
														<img src=".$fb." alt='Facebook' style='display: block;' border='0' />
													</a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>";
		return $txt;
	}

public function sendNotifCliente($clientEmail,$clientName,$clientProject){
$subject='Solicitud de Cotización';
$to=$clientEmail;
$contenido="<tr><td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
										<p> Hola <b>  ".$clientName."</b> </p>
										<p> <b> Su solicitud ha sido recibida </b>  </p>
									</td>
								</tr>
								<tr>
									<td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
									Bienvenido(a) y gracias por usar treitus,
									 nuestro equipo empezará a estudiar los requerimientos del proyecto <b> ".$clientProject ." </b>, en los próximos días le enviaremos la cotización.
									</td>
								</tr>
							</table>
						</td>
					</tr>";
$body= $this->header().$contenido.$this->footer();

return wp_mail( $to, $subject, $body, $this->headers );

}

public function sendNotifAdmin($adminEmail,$clientName,$clientProject){
	$subject='Nueva Cotización';
	$to=$adminEmail;
	$contenido="<tr><td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
								<table border='0' cellpadding='0' cellspacing='0' width='100%'>
									<tr>
										<td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
										<p><b>Un nuevo proyecto ha llegado</b> </p>
										</td>
									</tr>
									<tr>
										<td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
										El Cliente <b> ".$clientName." </b> , ha solicitado una cotización.
											<br> El nombre del proyecto es <b> ".$clientProject ." </b>.
											<br> Los archivos adjuntos ya están en tu cuenta de Dropbox
										</td>
									</tr>
								</table>
							</td>
						</tr>";
	$body= $this->header().$contenido.$this->footer();

	return wp_mail( $to, $subject, $body, $this->headers );
}
public function sendNotifAdminPayedQuote($adminEmail,$clientName,$clientProject,$cost){
	$subject='Cotización aceptada';
	$to=$adminEmail;
	$contenido="<tr><td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
								<table border='0' cellpadding='0' cellspacing='0' width='100%'>
									<tr>
										<td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
										<p><b>Nuevo Pago Realizado</b> </p>
										</td>
									</tr>
									<tr>
										<td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
											El Cliente <b>".$clientName."</b>, ha hecho un pago por <b> ".$cost." </b>.
											<br> El nombre del proyecto es <b> ".$clientProject ." </b>.
										</td>
									</tr>
								</table>
							</td>
						</tr>";
	$body= $this->header().$contenido.$this->footer();

	return wp_mail( $to, $subject, $body, $this->headers );
}

public function sendFinishPay($clientEmail,$clientName,$clientProject){
	$subject='Operación exitosa';
	$to=$clientEmail;
	$contenido="<tr> <td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
								<table border='0' cellpadding='0' cellspacing='0' width='100%'>
									<tr>
										<td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
											<p><b>Operación exitosa</b> <br><br>
											La fabricación de su producto está en marcha.
											 </p>
										</td>
									</tr>
									<tr>
										<td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
											Felicidades ".$clientName." <br>
											<br> Tu Proyecto <b> ".$clientProject ." </b> esta en marcha.
											<br> Le informaremos cuando este terminado.
										</td>
									</tr>
								</table>
							</td>
						</tr>";
	$body= $this->header().$contenido.$this->footer();

	return wp_mail( $to, $subject, $body, $this->headers );
}

public function sendCostProject($clientEmail,$clientName,$clientProject,$link){
	$subject='Costo de Proyecto Nuevo';
	$to=$clientEmail;
	$contenido="<tr> <td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
										<p><b>Tenemos el costo de tu proyecto</b> <br>
										 </p>
									</td>
								</tr>
								<tr>
									<td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
									Hola ".$clientName." <br>
										<br>Tu proyecto <b> ".$clientProject ." </b> ha sido revisado y tenemos el precio.
									<br> mira los detalles en  <a href=".$link.">este enlace.</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>";

	$body= $this->header().$contenido.$this->footer();

	return wp_mail( $to, $subject, $body, $this->headers );
}


}
