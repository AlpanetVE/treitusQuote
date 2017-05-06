<?php

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

require TQT_ABSPATH . 'classes/class-emails.php';
require_once 'lib/Dropbox/autoload.php';
require_once 'lib/PHPExcel.php';
require TQT_ABSPATH . 'classes/class-backup.php';

class treitusQuote {
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
		$this->table_quote 					= $wpdb->prefix."tqt_quote";
		$this->table_pay_quote 				= $wpdb->prefix."tqt_pay_quote";
		$this->table_tqt_config 			= $wpdb->prefix."tqt_config";
		$this->table_manufacture			= $wpdb->prefix."tqt_manufacture";
		$this->table_group 					= $wpdb->prefix."tqt_group";
		$this->table_option_manufacture		= $wpdb->prefix."tqt_option_manufacture";
		$this->table_measure				= $wpdb->prefix."tqt_measure";
		$this->table_user_address			= $wpdb->prefix."tqt_user_address";

		$this->tokenDropbox					= 'cMpiDeqAm4AAAAAAAAAAEZgbRaMpBk5E-0JLQ7yOg333E-JZYxHBXf3R70yHnb33';//'gv8lVdHJSdAAAAAAAAAACOdgS-dW1TLv22NrZNhs-xaSr1ul3JtP7LOFHc40sJGq';
		$this->db_version = "1.0";
	}

	/* ACTIVATION
      Only called when plugin is activated */
    function plugin_activation()
	{
        global $wpdb;

		$sql = array();
		$objtreitusQuote= new treitusQuote();
        //Only update database on version update
        $table_quote 				= $objtreitusQuote->table_quote;
		$table_pay_quote 			= $objtreitusQuote->table_pay_quote;
		$table_tqt_config 			= $objtreitusQuote->table_tqt_config;

		$table_manufacture			= $objtreitusQuote->table_manufacture;
		$table_group 				= $objtreitusQuote->table_group;
		$table_option_manufacture 	= $objtreitusQuote->table_option_manufacture;

		$table_measure 				= $objtreitusQuote->table_measure;
		$table_user_address 		= $objtreitusQuote->table_user_address;


		$sql[] = "DROP TABLE IF EXISTS `{$table_quote}`";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_quote}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`id_user` bigint(20) unsigned DEFAULT NULL,
			`name_project` varchar(255) NOT NULL,
			`files` varchar(255) DEFAULT NULL,
			`files2D` varchar(255) DEFAULT NULL,
			`piece` int(10) NOT NULL,
			`materials` varchar(20) NOT NULL,
			`date_needed` date NOT NULL,
			`email` varchar(255) NOT NULL,
			`notes` varchar(255) DEFAULT NULL,
			`name` varchar(25) DEFAULT NULL,
			`lastname` varchar(45) DEFAULT NULL,
			`city` varchar(24) DEFAULT NULL,
			`zipcode` varchar(24) DEFAULT NULL,
			`status` int(1) DEFAULT NULL,
			`cost` decimal(12,5) DEFAULT NULL,
			`id_measure` int(1) DEFAULT NULL,
			`company` varchar(255) DEFAULT NULL)";

		$sql[] = "DROP TABLE IF EXISTS `{$table_pay_quote}`";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_pay_quote}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`id_quote` bigint(20) unsigned DEFAULT NULL,
			`stripe` varchar(20) DEFAULT NULL,
			`value` float(20,5) DEFAULT NULL,
			`status` varchar(255) DEFAULT NULL,
			`date_time` datetime NOT NULL)";

		$sql[] = "DROP TABLE IF EXISTS `{$table_manufacture}`";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_manufacture}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`option_manufacture_id` bigint(20) unsigned DEFAULT NULL,
			`quote_id` bigint(20) unsigned DEFAULT NULL,
			`text` varchar(510) DEFAULT NULL)";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_group}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` varchar(255) DEFAULT NULL,
			`sort` int(11) unsigned DEFAULT NULL,
			`col` varchar(25) DEFAULT NULL)";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_option_manufacture}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`group_id` bigint(20) unsigned DEFAULT NULL,
			`name` varchar(255) DEFAULT NULL,
			`group_child_id` varchar(20) DEFAULT NULL,
			`input_text` int(1) unsigned DEFAULT NULL)";


		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_measure}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` varchar(255) DEFAULT NULL)";


		$sql[]="DROP TABLE IF EXISTS `{$table_user_address}`";

		$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_user_address}` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`city` varchar(24) DEFAULT NULL,
			`zipcode` varchar(24) DEFAULT NULL,
			`user_id` bigint(20) unsigned DEFAULT NULL)";

		/*$sql[] = "CREATE TABLE IF NOT EXISTS `{$table_tqt_config}` (
			`id` int(1) unsigned NOT NULL PRIMARY KEY,
			`drobox` varchar(150) DEFAULT NULL,
			`docusign` varchar(150) DEFAULT NULL,
			`stripe` varchar(150) DEFAULT NULL,
			`encrypted` varchar(150) DEFAULT NULL)";*/

        foreach($sql as $sk => $sv){
        	//var_dump($sv);
			$wpdb->query($sv);
		}

		mkdir(TQT_PATH_UPLOADS, 0777);

    }

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		//No actions needed yet
	}

	public static function get_instance(){
		static $instance = null;
		if($instance == null){
			$instance = new treitusQuote();
		}
		return $instance;
	}


	public function process_data($values,$files){
//var_dump(is_user_logged_in());
		switch ($values['tqt_tipo']) {
			case 'tqt_star_project':
				$post_vars = stripslashes_deep( $_POST );
				if(is_user_logged_in()){
	//				echo "esta logueado?";
					$this->insertQuote($values,$files);
				}
				else {
					if ($this->checkReCatpcha($post_vars['g-recaptcha-response'])) {
						$this->insertQuote($values,$files);
					}else{
						return new WP_Error('captcha', 'Valida el Captcha');
	 				}
				}
				break;
			default:
				# code...
				break;
		}

	}

	/**
	 * Upload file
	 * Return array with [name] file saved
	 * @public
	 */
	public function uploadFile($file){

		if (!isset($file['file-7']['name']) || empty($file['file-7']['name']) || ($file[uploadedfile][size] >20000000)) {
			return '';
		}

		$target_path 	= TQT_PATH_UPLOADS;

		$nameFile 		= $this->sanear_string(basename( date("d-m-Y H:i:s").$file['file-7']['name']));
		$target_path_file = $target_path . $nameFile;
		if(move_uploaded_file($file['file-7']['tmp_name'], $target_path_file)) {
			$arrayFilesNames['file-7'] = $nameFile;
		}


		if (isset($file['file-8']['name']) && !empty($file['file-8']['name'])) {
			$nameFile 	 = $this->sanear_string(basename( date("d-m-Y H:i:s").$file['file-8']['name']));
			$target_path_file = $target_path . $nameFile;
			if(move_uploaded_file($file['file-8']['tmp_name'], $target_path_file)) {
				$arrayFilesNames['file-8'] = $nameFile;
			}
		}

		return $arrayFilesNames;
	}

	public function getAddres($user_id){
		$query="SELECT * FROM $this->table_user_address WHERE user_id = '$user_id'";
		return $this->db->get_results( $query, ARRAY_A );
	}

	public function updateAdress($user_id, $data){
		global $wpdb;
    	$city 		= isset($data['city']) ? $data['city'] : '';
		$zipcode 	= isset($data['zipcode']) ? $data['zipcode'] : '';

		$sql   = "UPDATE `$this->table_user_address` SET
		city = '$city',
		zipcode = '$zipcode'
		WHERE user_id = '{$user_id}'";

		return $wpdb->query($sql);
    }

	public function processAddres($user_id){
    	if (isset($_POST['update_address'])) {
    		$this->updateAdress($user_id, $_POST);
    	}
    	return false;
    }



	public function saveDropbox($files,$nameProject,$nameClient){
		$nameProject=str_replace(" ", "_", $nameProject);
		$nameClient=str_replace(" ", "_", $nameClient);

		$projectVersion = 'Treitus';
		$projectFolder=$nameClient."_".$nameProject;
		$bk = new Backup($this->tokenDropbox,$projectVersion,$projectFolder);


		foreach ($files as $key => $value) {
			$ruta_file	= TQT_PATH_UPLOADS.$value;

			$val=$bk->upload($ruta_file);
			$pathFile[$key]	= str_replace(" ", "_", $val["path"]);
			//$pathFile[$key]	= str_replace(" ", "_", $ruta_file);
		}
		return $pathFile;
	}

public function updateUserQuote($email,$id_user){
	$sql   = "UPDATE `$this->table_quote` SET
	id_user = '$id_user'
	WHERE id = 0 and email = '$email'";
	return $wpdb->query($sql);
}

	public function insertQuote($values,$files){
	    global $wpdb;
		//$sqlInsert=""
		$name_project=$values['tqt_proyectname'];
		$piece=$values['tqt_qty'];
		$materials=$values['tqt_material'];


		if (!empty($values['tqt_email'])) {
			$email=$values['tqt_email'];
			$name=$values['tqt_name'];
			$last_name=$values['tqt_lastname'];
		}else if (is_user_logged_in()) {
			$user = wp_get_current_user();
			$email 	= $user->data->user_email;

			$user_meta 	= get_user_meta($user->ID);
			$name 		= $user_meta["first_name"][0];
			$last_name 	= $user_meta["last_name"][0];


		}

		$notes=$values['tqt_notes'];
		$date_needed=$values['tqt_date'];

		$phone=$values['tqt_phone'];
		$city=$values['tqt_city'];
		$zipcode=$values['tqt_zipcode'];

		$id_measure=$values['group_measure'];
		$company=$values['tqt_company'];

		$user = wp_get_current_user();
		$actual_user	= $user->ID;

		$fileToUpload	= $files;

		$namefile	= $this->uploadFile($fileToUpload);

		$arreglo	= $this->saveDropbox($namefile,$name_project,$name);



		if (isset($arreglo["file-7"])) {
			$pathFile	= $arreglo["file-7"];
			unlink($pathFile);
		}
		if (isset($arreglo["file-8"])) {
			$pathFile2D	= $arreglo["file-8"];
			unlink($pathFile2D);
		}




		// if (isset($namefile["file-7"])) {
		// 	$pathFile	= $namefile["file-7"];
		// 	unlink($pathFile);
		// }
		// if (isset($namefile["file-8"])) {
		// 	$pathFile2D	= $namefile["file-8"];
		// 	unlink($pathFile2D);
		// }


		$respu=$wpdb->query( $wpdb->prepare(
			"
			INSERT INTO $this->table_quote
			(id_user, name_project, files,files2D, piece, materials,
			email, notes, status, date_needed, name, lastname,
			city, zipcode,id_measure, company)
			VALUES ( %d, %s, %s,%s, %d, %s,
			%s, %s, %d, %s, %s, %s,
			%s, %s, %d, %s)
			",
			array(
				$actual_user,
				$name_project,
				$pathFile,
				$pathFile2D,
				$piece,
				$materials,
				$email,
				$notes,
				1,
				$date_needed,
				$name,
				$last_name,
				$city,
				$zipcode,
				$id_measure,
				$company
			)
		));

		$direccion=$this->getAddres($actual_user);

		if(empty($direccion) AND is_user_logged_in()){
			$wpdb->query( $wpdb->prepare(
				"
				INSERT INTO $this->table_user_address
				(	city, zipcode, user_id)
				VALUES (%s,%s,%d)
				",
				array(
					$city,
					$zipcode,
					$actual_user
				)
			));
		}

		 /* $wpdb->show_errors();
	  $wpdb->print_error();*/

		if($respu){
			$quote_id = $wpdb->insert_id;

			$ArrayGroups=$this->getGroup();
			foreach ($ArrayGroups as $key => $value) {
				$option_manufacture_id=$values['group_'.$value['id']];
				if (!empty($option_manufacture_id)) {
					$text = $values['group_option_'.$option_manufacture_id];
					$this->insertManufacture($quote_id, $option_manufacture_id, $text);
				}

			}

			$this->createExcel($values,$quote_id);
			$emailAdmin = $this->getAdminEmail();
			$correo = new emails();

			$val=$correo->sendNotifCliente($email,$name,$name_project);
			$valAdmin =	$correo->sendNotifAdmin($emailAdmin,$name,$name_project);

			$url = home_url( '/notification' );
			wp_redirect( $url );
			exit;
		}

		die('fin');

	}

	public function createExcel($values,$quote_id){

		$respuestas=$this->getManufactureByQuote($quote_id);
		$objPHPExcel = new PHPExcel();


		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Treitus Cotizacion');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Nombre del Proyecto');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Cantidad por Pieza');
		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Material');
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Fecha');
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Notas');
		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Nombre Cliente');
		$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Apellido Cliente');
		$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Telefono');
		$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Correo Electronico');
		$objPHPExcel->getActiveSheet()->setCellValue('A12', 'Ciudad');
		$objPHPExcel->getActiveSheet()->setCellValue('A13', 'Codigo Postal');
		$objPHPExcel->getActiveSheet()->setCellValue('A14', 'Empresa');
		$objPHPExcel->getActiveSheet()->setCellValue('A15', 'Medidas');


		$objPHPExcel->getActiveSheet()->setCellValue('B3', $values['tqt_proyectname']);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', $values['tqt_qty']);
		$objPHPExcel->getActiveSheet()->setCellValue('B5', $values['tqt_material']);
		$objPHPExcel->getActiveSheet()->setCellValue('B6', $values['tqt_date']);
		$objPHPExcel->getActiveSheet()->setCellValue('B7', $values['tqt_notes']);
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $values['tqt_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B9', $values['tqt_lastname']);
		$objPHPExcel->getActiveSheet()->setCellValue('B10', $values['tqt_phone']);
		if (!empty($values['tqt_email'])) {
			$email=$values['tqt_email'];
		}else if (is_user_logged_in()) {
			$user = wp_get_current_user();
			$email 	= $user->data->user_email;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('B11', $email);
		$objPHPExcel->getActiveSheet()->setCellValue('B12', $values['tqt_city']);
		$objPHPExcel->getActiveSheet()->setCellValue('B13', $values['tqt_zipcode']);
		$objPHPExcel->getActiveSheet()->setCellValue('B14', $values['tqt_company']);
		if($values['group_measure']==1){
			$medida='Pulgadas';
		}elseif ($values['group_measure']==2) {
			$medida='Milimetros';
		}else {
			$medida='Centimetros';
		}

		$objPHPExcel->getActiveSheet()->setCellValue('B15', $medida);

		$cont=16;
		foreach ($respuestas as $key => $value) {
			# code...
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, $value['pregunta']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, $value['respuesta']);
			//echo $key. " - " . $value['pregunta']. " : " .$value['respuesta'] ;
		$cont++;
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);

		$styleArray = array(
			'font' => array(
				'bold' => true,
			),
		);

		$style2=array(
		'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '0055ff'),
				),
			),
		);

		$nameProject=str_replace(" ", "_",$values['tqt_proyectname']);
		$nameClient=str_replace(" ", "_", $values['tqt_name']);

		$projectExcel=$nameClient."_".$nameProject;
		$target_path 	= TQT_PATH_UPLOADS;

		$objPHPExcel->getActiveSheet()->getStyle('A3:A20')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('B3:B20')->applyFromArray($style2);
		$objPHPExcel->getActiveSheet()->setTitle('Treitus Quote');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		$objWriter->save($target_path.'/'.$projectExcel.'.xlsx');


		$projectVersion = 'Treitus';
		$bk = new Backup($this->tokenDropbox,$projectVersion,$projectExcel);
		$val=$bk->upload($target_path.$projectExcel.'.xlsx');
		unlink($target_path.$projectExcel.'.xlsx');
	}


	public function insertManufacture($quote_id, $option_manufacture_id, $text){
		global $wpdb;
		$respu=$wpdb->query( $wpdb->prepare(
			"INSERT INTO $this->table_manufacture
			(quote_id, option_manufacture_id, text)
			VALUES ( %d,%d, %s)",
			array(
				$quote_id,
				$option_manufacture_id,
				$text
			)
		));

		return $respu;
	}
	public function getAdminEmail(){
		global $wpdb;
		//$this->db = $wpdb;
		$table_options = $wpdb->prefix."options";

		$admin_email=$wpdb->get_var( "SELECT option_value FROM $table_options WHERE option_name='admin_email'");

		return $admin_email;
	}

	public function getDataByQuote($idQuote){
	$query="SELECT * FROM $this->table_quote WHERE id = $idQuote";
	return $this->db->get_results( $query, ARRAY_A );
	}

	public function get_page_itemsQuote($curr_page, $per_page,$id=null,$status=null){
		$start = (($curr_page-1)*$per_page);
		$query = "SELECT * FROM $this->table_quote where 1";
		if (!empty($status)) {
			$query.=" and status='$status'";
		}

		$query.=" ORDER BY id DESC LIMIT $start, $per_page";
		return $this->db->get_results( $query, ARRAY_A );
	}

	public function get_itemsEvent($curr_page, $per_page=null, $idEvent=null){
		$start = (($curr_page-1)*$per_page);
		$query = "SELECT
					se.id,
					se.id_quote_fun,
					se.name,
					se.opening_hour,
					se.closed_hour,
					se.poster,
					se.`date`,
					se.clothing_type,
					se.ticket_selling,
					se.description,
					sf.name as name_site,
					sf.id as siteid
					FROM
					$this->table_pay_quote AS se
					Inner Join $this->table_quote AS sf ON sf.id = se.id_quote_fun where 1";

					if (!empty($idEvent)) {
						$query.=" and se.id='$idEvent'";
					}

					$query.="  ORDER BY se.id DESC";

					if (!empty($per_page)) {
						$query.=" LIMIT $start, $per_page";
					}

		return $this->db->get_results( $query, ARRAY_A );
	}
	public function getCountQuote($status=null){
		$query="SELECT COUNT(*) FROM $this->table_quote";
		if (!empty($status)) {
			$query.=" and status='$status'";
		}
		$count = $this->db->get_var($query);
		return isset($count)?$count:0;
	}
	public function getCountEvents(){
		$count = $this->db->get_var("SELECT COUNT(*) FROM $this->table_pay_quote");
		return isset($count)?$count:0;
	}
	public function deleteSite($id){
		global $wpdb;

		if(is_array($id))
			$id = sprintf('(%s)', implode(',', $id));
		else {
			$id = sprintf('(%d)', $id);
		}

		$query = "DELETE FROM $this->table_quote WHERE id IN $id";
		return $wpdb->query($query);
	}
	public function deleteEvent($id){
		global $wpdb;

		if(is_array($id))
			$id = sprintf('(%s)', implode(',', $id));
		else {
			$id = sprintf('(%d)', $id);
		}

		$query = "DELETE FROM $this->table_pay_quote WHERE id IN $id";
		return $wpdb->query($query);
	}
	public function getSite($id=''){

		$query = "SELECT * FROM $this->table_quote where 1";
		if (!empty($id)) {
			$query.=" and id='$id'";
		}
		return $this->db->get_results( $query, ARRAY_A );
	}

	public function addQuote(){
		global $wpdb;
		$data=$_POST['TqtForm'];
		if(is_array($data)){
			$results = $wpdb->insert($this->table_quote, array(
				'name'    		=>  isset($data['name']) ? $data['name'] : '',
				'addres'	  	=>	isset($data['addres']) ? $data['addres'] : '',
				'latitude'  	=>	isset($data['latitude']) ? $data['latitude'] : '',
				'longitude' 	=>	isset($data['longitude']) ? $data['longitude'] : '',
				'environment'	=>	isset($data['environment']) ? $data['environment'] : '',
				'closed_hour'	=>	isset($data['closed_hour']) ? $data['closed_hour'] : '',
				'opening_hour' 	=>	isset($data['opening_hour']) ? $data['opening_hour'] : ''
			));
			return $results;
		}
		return false;
	}
	public function addEvent(){
		global $wpdb;
		$data=$_POST['TqtForm'];
		if(is_array($data)){
			$posterNameFile 	= $this->uploadFile();
			$results = $wpdb->insert($this->table_pay_quote, array(
				'id_quote_fun'   =>  isset($data['siteid']) ? $data['siteid'] : '',
				'name'	  		=>	isset($data['name']) ? $data['name'] : '',
				'poster'  		=>	isset($posterNameFile) ? $posterNameFile : '',
				'date'  		=>	isset($data['date']) ? $data['date'] : '',
				'clothing_type' =>	isset($data['clothing_type']) ? $data['clothing_type'] : '',
				'ticket_selling'=>	isset($data['ticket_selling']) ? $data['ticket_selling'] : '',
				'description'	=>	isset($data['description']) ? $data['description'] : '',
				'opening_hour' 	=>	isset($data['opening_hour']) ? $data['opening_hour'] : '',
				'closed_hour'	=>	isset($data['closed_hour']) ? $data['closed_hour'] : ''
			));
			return $results;
		}
		return false;
	}


	function sanear_string($string)
	{

	    $string = trim($string);

	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );

	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );

	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );

	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );

	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );

	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );

	    //Esta parte se encarga de eliminar cualquier caracter extraño
	    $string = str_replace(
	        array("\\", "¨", "º", "-", "~",
	             "", "@", "|", "!",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             " "),
	        '',
	        $string
	    );

	    return $string;
	}

	public function editQuote($id=null){
		global $wpdb;
		$wpdb->flush();
		$id = !is_null($id) ? $id : $_POST['id_quote'];

		if (!empty($id)) {
			$data 		= $_POST['TqtForm'];

			$cost 		= isset($data['cost']) ? $data['cost'] : '';
			$status 	= isset($data['status']) ? $data['status'] : '';

			$sql   = "UPDATE `$this->table_quote` SET
			cost = '$cost',
			status = '$status'
			WHERE id = {$id}";
			return $wpdb->query($sql);
		}
		return false;
	}

	public function editEvent($id=null){
		global $wpdb;
		$wpdb->flush();
		$id = !is_null($id) ? $id : $_POST['id_quote'];

		if (!empty($id)) {
			$data=$_POST['TqtForm'];
			$posterNameFile = $this->uploadFile();
			$id_quote_fun 	= isset($data['siteid']) ? $data['siteid'] : '';
			$name 			= isset($data['name']) ? $data['name'] : '';
			$poster 		= isset($posterNameFile) ? $posterNameFile : '';
			$date 			= isset($data['date']) ? $data['date'] : '';
			$clothing_type 	= isset($data['clothing_type']) ? $data['clothing_type'] : '';
			$ticket_selling = isset($data['ticket_selling']) ? $data['ticket_selling'] : '';
			$description 	= isset($data['description']) ? $data['description'] : '';
			$opening_hour 	= isset($data['opening_hour']) ? $data['opening_hour'] : '';
			$closed_hour 	= isset($data['closed_hour']) ? $data['closed_hour'] : '';


			$sql   = "UPDATE `$this->table_pay_quote` SET
			id_quote_fun 	= '$id_quote_fun',
			name 			= '$name',
			poster 			= '$poster',
			`date` 			= '$date',
			clothing_type 	= '$clothing_type',
			ticket_selling 	= '$ticket_selling',
			description 	= '$description',
			closed_hour 	= '$closed_hour',
			opening_hour 	= '$opening_hour' ";

			if (!empty($poster)) {
				$sql.=" ,poster = '$poster'";
			}

			$sql.="  WHERE ID = {$id}";
			return $wpdb->query($sql);
		}
		return false;
	}

	public static function run() {
		self::$controller =self::load_controller();
	}
	/**
	 * Load a file with require_once(), after running it through a filter.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file   Name of the PHP file with the class.
	 * @param string $folder Name of the folder with $class's $file.
	 */
	public static function load_file( $file, $folder ) {
		$full_path = TQT_ABSPATH . $folder . '/' . $file;

		$full_path = apply_filters( 'tqt_load_file_full_path', $full_path, $file, $folder );
		if ( $full_path ) {
			require_once $full_path;
		}
	}
	/**
	 * Create a new instance of the $controller, which is stored in the "controllers" subfolder.
	 *
	 * @since 1.0.0
	 *
	 * @param string $controller Name of the controller.
	 * @return object Instance of the initialized controller.
	 */
	public static function load_controller() {
		// Controller Base Class.
		 self::load_file( 'class-controller.php', 'classes' );
		 new treitusQuote_Controller();
	}

	public function getProcessQuote( $id, $email, $statusForPay=false, $id_user){

		$query = "SELECT
				id,
				id_user,
				name_project,
				files,
				piece,
				materials,
				date_needed,
				email,
				notes,
				`status`,
				cost,
				name,
				id_measure,
				company,
				city,
				zipcode
				FROM `".$this->table_quote."`
				where 1";

				if (!empty($id)) $query.=" and id = '$id'";

				if (!empty($email)) $query.=" and email = '$email'";

				if ($statusForPay) $query.=" and (status = '2' or status = '3')";

				if (!empty($id_user)) $query.=" and id_user = '$id_user'";

		return $this->db->get_row( $query, ARRAY_A );

	}

	public function getQuoteList( $id, $email, $status=null, $id_user){

		$query = "SELECT
				id,
				id_user,
				name_project,
				files,
				piece,
				materials,
				date_needed,
				email,
				notes,
				`status`,
				cost,
				name
				FROM `".$this->table_quote."`
				where 1";

				if (!empty($id)) $query.=" and id = '$id'";

				if (!empty($email)) $query.=" and email = '$email'";

				if (!empty($status)) $query.=" and status = '$status'";

				if (!empty($id_user)) $query.=" and id_user = '$id_user'";
				//var_dump($query);
		return $this->db->get_results( $query, ARRAY_A );

	}
	public function getQuote( $id=null, $email=null, $statusForPay=false, $id_user=null){

		$query = "SELECT
				id,
				id_user,
				name_project,
				files,
				piece,
				materials,
				date_needed,
				email,
				notes,
				`status`,
				cost,
				name,
				id_measure,
				company,
				city,
				zipcode
				FROM `".$this->table_quote."`
				where 1";

				if (!empty($id)) $query.=" and id = '$id'";

				if (!empty($email)) $query.=" and email = '$email'";

				if ($statusForPay) $query.=" and (status = '2' or status = '3')";

				if (!empty($id_user)) $query.=" and id_user = '$id_user'";
				  //var_dump($query);
		return $this->db->get_row( $query, ARRAY_A );

	}

    public function redirect_user($dir) {
	    $return_url = esc_url( home_url($dir) );
	    wp_redirect( $return_url );
	    exit;
	}

	public function getPayQuoteAction( ) {
    	return $current_page = isset($_POST['stripeToken']) ? 'processForm' : 'showForm';
    }

    public function processPayQuoteAction($values, $arrQuote, $pago, $status, $id_user){
		$error 		= '';
		$success 	= '';
		$costCent	= $pago * 100; //se envia en centimos el valor
		$pagoShow 	= number_format($pago, 2, ',', '.');
		$costCent	= round($costCent, 0);
		$id_quote	= $arrQuote['id'];
		$stripeToken= $values['stripeToken'];
		Stripe::setApiKey(SECRET_KEY_STRIPE);


		try {

			if (!isset($values['stripeToken']))
			  throw new Exception("El Token de Stripe no se generó correctamente");

			$charge =Stripe_Charge::create(array("amount" => $costCent,
			                            "currency" => "usd",
			                            "card" => $stripeToken,
			                            "description" => $values['email'],
			                            "metadata" => array("order_id" => $id_quote)
			                            ));

			$charge=json_decode($charge);

			if ($status=='4')
				$textFinish = '<br>Usted Ya pagó todo el proyecto.';
			else
				$textFinish = '';

			$result = '<div class="alert alert-success">
		            <strong>Perfecto</strong> Su pago fue exitoso.
		            <br>Se te ha enviado un correo.
		            '.$textFinish.'
		    		</div>';
			$result .='<div>
					<a href="'.home_url().'"><button type="button" class="btn btn-success">Finalizar</button></a>
					</div>';

		    $this->insertPayQuote($id_quote, $charge->id, $costCent, 1);
		    $this->updateStatusQuote($id_quote,$status, $id_user);
		    $this->sendMailPay($arrQuote['email'],$arrQuote['name'],$arrQuote['name_project'],$pagoShow);


		}
		catch (Exception $e) {
			$result = '<div class="alert alert-danger">
		    <strong>¡Error!</strong> '.$e->getMessage().'
		    </div>';
		    $result .='<div>
<a href="'.$values['setCod'].'"><button type="button" class="btn btn-primary">Intenta de nuevo</button></a>
<a href="'.home_url().'"><button type="button" class="btn btn-danger">Salir</button></a>
</div>';
		}

		return $result;

    }

    public function insertPayQuote($id_quote, $stripe, $value, $status){
    	global $wpdb;
		$result=$wpdb->query( $wpdb->prepare(
			"INSERT INTO $this->table_pay_quote
			(id_quote, stripe, value, status,date_time)
			VALUES ( %d, %s, %f, %d, %s)",
			array(
				$id_quote,
				$stripe,
				$value,
				$status,
				date('Y-m-d H:i:s')
			)
		));

		return $result;
	}
	public function updateStatusQuote($id, $status, $id_user){
		global $wpdb;
		$wpdb->flush();
		if (!empty($id)) {
			$sql   = "UPDATE `$this->table_quote` SET
			status 	= '$status', id_user = '$id_user' ";
			$sql.="  WHERE id = {$id}";
			return $wpdb->query($sql);
		}
		return false;
	}
	public function getCheckManufacture($group_id){

		$query = "SELECT
					g.id AS group_id,
					g.`name` AS name_group,
					om.`name`,
					om.input_text,
					om.id,
					om.group_child_id,
					g.col
					FROM
					$this->table_group AS g
					INNER JOIN $this->table_option_manufacture AS om ON om.group_id = g.id
					where g.id in ($group_id)
					ORDER BY
					g.sort ASC,
					om.id ASC";
		return $this->db->get_results( $query, ARRAY_A );

	}
	public function getGroup(){

		$query = "SELECT
					g.id
					FROM
					$this->table_group AS g order by g.id";
		return $this->db->get_results( $query, ARRAY_A );

	}
	public function sendMailPay($email,$clientName,$clientProject,$cost){
		$correo 	= new emails();
		$adminEmail = $this->getAdminEmail();
		$cost 		= $cost.' $USD';

		$res		= $correo->sendFinishPay($email,$clientName,$clientProject);
		$res		= $correo->sendNotifAdminPayedQuote($adminEmail,$clientName,$clientProject,$cost);
		return $res;
	}
	public function getSumPayQuote($code){

		$query = "SELECT
				Sum(pq.`value`) AS payed
				FROM `".$this->table_pay_quote."` AS pq
				where pq.id_quote = '$code'";

		return $this->db->get_row( $query, ARRAY_A );

	}
	public function getMeasure($id_measure){

		$query = "SELECT
				m.`name`
				FROM
				$this->table_measure AS m
				WHERE
				m.id = '$id_measure'";


		return $this->db->get_row( $query, ARRAY_A );
	}


	public function getManufactureByQuote($quote_id){

		$query = "SELECT
				g.`name` pregunta,
				om.`name` respuesta,
				m.text
				FROM
				$this->table_manufacture AS m
				INNER JOIN $this->table_option_manufacture AS om ON m.option_manufacture_id = om.id
				INNER JOIN $this->table_group AS g ON om.group_id = g.id
				WHERE
				m.quote_id = '$quote_id'
				ORDER BY
				g.sort ASC";
		return $this->db->get_results( $query, ARRAY_A );

	}

	public function checkReCatpcha($response){
		$bolean=true;
		$result = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret' => '6LeH4A8UAAAAAN0zQ9RRxYSnNjMvIjEnvsMd9Mq3',
					'response' => $response,
					'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null,
				)
			)
		);
		if( !is_wp_error($result) && !empty($result['body']) ) {
			$result = json_decode( $result['body'], true );
			if( isset($result['success']) && !$result['success'] ) {
				$bolean=false;
			}
		}else{
			$bolean=false;
		}

		return $bolean;
	}

}
