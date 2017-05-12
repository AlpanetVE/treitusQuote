<?php
/**
 * List Tables View
 *
 * @package treitusQuote
 * @subpackage Views
 * @author Tobias Bäthge
 * @since 1.0.0
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * List Tables View class
 * @package treitusQuote
 * @subpackage Views
 * @author Tobias Bäthge
 * @since 1.0.0
 */
class treitusQuoteList extends WP_List_Table {

	private $db;

    public function __construct(){

    	$this->load_dependencies();

    	$this->db = treitusQuote::get_instance();

    	global $status, $page;

		parent::__construct( array(
			'singular'  => 'id_quote',
			'plural'    => 'sites',
			'ajax'      => false,
			'screen'    => $_REQUEST['page']
		) );
    }

    function load_dependencies(){
    	require_once( TQT_ABSPATH . 'classes/class-treitusQuote.php' );
			require_once(TQT_ABSPATH . 'classes/class-emails.php' );
    }

	function get_columns(){
		$columns = array(
			'cb'	=> '<input type="checkbox" />',
			'id'	=> __('id', 'wptg-plugin'),
			'name_project'	=> __('Nombre del Proyecto', 'wptg-plugin'),
			'date_needed'	=> __('Fecha Requerida', 'wptg-plugin'),
			'email'	=> __('Email', 'wptg-plugin')
		);
		return $columns;
	}

    function column_default($item, $name_project){
        return stripslashes($item[$name_project]);
    }

	function column_name_project($item){
		//Build row actions
		$actions = array(
			'edit' => sprintf('<a href="?page=%s&action=%s&id_quote=%s">%s</a>', $_REQUEST['page'],'edit',$item['id'], __('Editar', 'wptg-plugin') )
		);

		//Return the title contents
		return sprintf('%1$s %2$s',
			/*$1%s*/ stripslashes($item['name_project']),
			/*$2%s*/ $this->row_actions($actions)
		);
	}
	function column_AddEvent($item){
		//Build row actions
		$actions = array(
			'edit' => sprintf('<a href="?page=%s&action=%s&id_quote=%s">%s</a>', 'treitusQuote','add',$item['id'], __('Do it', 'wptg-plugin') )
		);

		//Return the title contents
		return sprintf('%1$s',
			/*$1%s*/ $this->row_actions($actions)
		);
	}

	function column_cb($item){
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
		);
	}

    function get_bulk_actions() {
        $actions = array(
            'delete'    => __('Delete', 'wptg-plugin')
        );
        return $actions;
    }

	function prepare_items() {
		$per_page               = 25;
		$hidden                 = array();
		$columns                = $this->get_columns();
		$sortable               = array();
		$curr_page              = $this->get_pagenum();

		$total_items            = $this->db->getCountQuote('1');
		$data                   = $this->db->get_page_itemsQuote($curr_page, $per_page,null,1);

		$this->items            = $data;
		$this->_column_headers  = array($columns, $hidden, $sortable);

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil($total_items/$per_page)
		) );
	}

	function show(){
		echo sprintf('<div class="wrap">');
    	echo sprintf( '<h2>%s <a class="add-new-h2" href="%s">%s</a></h2>', __('Cotizaciones', 'wptg-plugin'), admin_url('admin.php?page=treitusQuote&action=add'), __('Crear Nuevo', 'wptg-plugin') );
        echo sprintf('<form method="GET"><input type="hidden" name="page" value="'.$_GET['page'].'">');
	    $this->prepare_items();
		$this->display();
	    echo sprintf('</form>');
    	echo sprintf('</div>');
	}


	function showForm($id=null){
		wp_enqueue_style('tqt_admin_style');
		wp_enqueue_style('tqt_bootstrap');

		if (!empty($id)) {
			$data=$this->db->getSite($id);
			$savedata = "update";
		}else {
			$data=array();
			$savedata = "insert";
		}

		$name_project	= isset($data[0]['name_project'])?$data[0]['name_project']:'';
		$piece		 	= isset($data[0]['piece'])?$data[0]['piece']:'';
		$materials		= isset($data[0]['materials'])?$data[0]['materials']:'';
		$date_needed	= isset($data[0]['date_needed'])?$data[0]['date_needed']:'';
		$email		 	= isset($data[0]['email'])?$data[0]['email']:'';
		$name 			= isset($data[0]['name'])?$data[0]['name']:'';
		$lastname		= isset($data[0]['lastname'])?$data[0]['lastname']:'';
		$notes		 	= isset($data[0]['notes'])?$data[0]['notes']:'';
		$city		 	= isset($data[0]['city'])?$data[0]['city']:'';
		$zipcode		= isset($data[0]['zipcode'])?$data[0]['zipcode']:'';

		$status		 	= isset($data[0]['status'])?$data[0]['status']:'';
		$company		= isset($data[0]['company'])?$data[0]['company']:'';

		$files		 	= isset($data[0]['files'])?$data[0]['files']:'';
		$files2D		= isset($data[0]['files2D'])?$data[0]['files2D']:'';
		$link_dropbox	= 'https://www.dropbox.com/home/Aplicaciones/TreitusQuote';

		$link_files		= $link_dropbox.$files;
		$link_files2D	= $link_dropbox.$files2D;


		$arrManufacture = $this->db-> getManufactureByQuote( $id);

		$measure 		= $this->db-> getMeasure( $data[0]['id_measure']);

		?>

		<div class="wrap">
		<h1>Cotizaciones</h1>
			<form method="post" action="?page=treitusQuote&action=list" >
				<input type="hidden" name="id_quote" value="<?php echo $id; ?>" >
				<input type="hidden" name="savedata" value="<?php echo $savedata; ?>" >
				<input type="hidden" name="TqtForm[status]" value="2" >
				<div class="postbox ">
				<div class="form-wrap inside">
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Nombre del Proyecto', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[name_project]" disabled="disabled" value="<?php echo $name_project; ?>" id="table-name_project"  />
					</div>
					<?php if (!empty($arrManufacture)) {

					    foreach ($arrManufacture as $key => $value) { ?>
					        <div class="form-field  col-sm-4">
					          <label class="control-label" ><?php echo $value['pregunta'];?></label>
					            <input value="<?php echo $value['respuesta']; if(!empty($value['text'])) echo ': '.$value['text'] ?>" type="text" disabled="disabled" >
					        </div>

				    <?php }
				    }  ?>

				    <?php if (!empty($measure)) {  ?>
					    <div class="form-field col-sm-4">
							<label ><?php _e( 'Medida', 'treitusQuote' ); ?>:</label>
							<input type="text" name="TqtForm[measure]" disabled="disabled" value="<?php echo $measure['name']; ?>"  />
						</div>
					<?php } ?>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Cantidad por pieza', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[piece]" disabled="disabled" value="<?php echo $piece; ?>" id="table-piece"  />
					</div>

					<?php /*<div class="form-field col-sm-4">
						<label ><?php _e( 'Material', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[materials]" disabled="disabled" value="<?php echo $materials; ?>" id="table-materials"  />
					</div> */?>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Fecha', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[date_needed]" disabled="disabled" value="<?php echo $date_needed; ?>" id="table-date_needed"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Correo Electronico', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[email]" disabled="disabled" value="<?php echo $email; ?>" id="table-email"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Nombre', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[name]" disabled="disabled" value="<?php echo $name; ?>" id="table-name"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Apellido', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[lastname]" disabled="disabled" value="<?php echo $lastname; ?>" id="table-lastname"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Notas', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[notes]" disabled="disabled" value="<?php echo $notes; ?>" id="table-notes"  />
					</div>

					<div class="form-field col-sm-4">
						<label ><?php _e( 'Ciudad', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[city]" disabled="disabled" value="<?php echo $city; ?>" id="table-city"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Codigo Postal', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[zipcode]" disabled="disabled" value="<?php echo $zipcode; ?>" id="table-zipcode"  />
					</div>
					<div class="form-field col-sm-4">
						<label ><?php _e( 'Empresa', 'treitusQuote' ); ?>:</label>
						<input type="text" name="TqtForm[company]" disabled="disabled" value="<?php echo $company; ?>" id="table-zipcode"  />
					</div>


					<div class="form-field col-sm-4">
							<label ><?php _e( 'Archivo 3D Adjunto', 'treitusQuote' ); ?>:</label>
						<a href=<?php echo $link_files; ?> target="_blank" ><?php echo basename($files); ?></a>
					</div>

					<?php if (!empty($files2D)) { ?>
						<div class="form-field col-sm-4">
							<label ><?php _e( 'Archivo 2D Adjunto', 'treitusQuote' ); ?>:</label>
							<a href=<?php echo $link_files2D; ?> target="_blank" ><?php echo basename($files2D); ?></a>
						</div>
					<?php } ?>


					<div class="col-xs-12 div-cost">
						<label class="control-label" for="textinput"><?php _e( 'Monto a pagar', 'treitusQuote' ); ?>
						</label>
						<div class="col-sm-4" style="padding: 0px;">
							<div class="input-group">
								<span class="input-group-addon"><?php _e( '$USD', 'treitusQuote' ); ?></span>
								<input required="required" name="TqtForm[cost]" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency" id="valuePay" aria-label="Amount (to the nearest dollar)">
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar y enviar correo"></p>
			</form>
		</div>

		<?php
	}

	function delete(){
		return $this->db->deleteSite($_GET['id_quote']);
	}

	function processData(){

		if (isset($_POST['savedata']) && !empty($_POST['savedata'])){
			$savedata=$_POST['savedata'];
			switch ($savedata) {
	 			case 'insert':
	 				return $this->db->addQuote();
	 			break;
	 			case 'update':
					$url 	= home_url( "/pay-quote/?code=".$_POST['id_quote']);
					$correo = new emails();
					$data 	= $this->db->getDataByQuote($_POST['id_quote']);

					if (!empty($data[0]["id_user"])) {
						$user 		= get_user_by('id',$data[0]["id_user"]);
						$user_email = $user->data->user_email;
					}else{
						$user_email = $data[0]["email"];
					}

					$result = $this->db->editQuote();
					$res	= $correo->sendCostProject($user_email,$data[0]["name"],$data[0]["name_project"],$url);
					return $result;
	 			break;
	 		}
		}
		return false;
	}

	function doAction($action){
		$this->processData();
 		switch ($action) {
 			case 'list':
 				$this->show();
 			break;
 			case 'add':
 				//$this->showForm();
 				$this->show();
 			break;
 			case 'edit':
 				$id=$_GET['id_quote'];
 				$this->showForm($id);
 			break;
 			case 'delete':
 				$this->delete();
 				$this->show();
 			break;
 		}
	}


}
