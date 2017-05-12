<?php
require_once('../../../wp-load.php');
require_once('define.php');
require_once('classes/class-treitusQuote.php');

$GeneratorEvents 	= new treitusQuote();

if ($_POST['action']=='getCheckManufacture') {
	$result		= '';
	$html 		= '';
	$curren_group= '';
	$post_group_child_id   = $_POST['group_id'];

	$ArrayChecks = $GeneratorEvents->getCheckManufacture($post_group_child_id);


	ob_start();



	if ($post_group_child_id=='2' ||  $post_group_child_id=='3,4,5') {
	?>

		<div id="cont-files" >
			<div>
				<label><strong>Cargar archivo 3D</strong></label>
				<label class='error' style='display: none;'>Este campo es requerido</label>
				<div class="box">
					<input type="file" name="file-7" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} archivos seleccionados" accept=".IGES,.IGS,.IV,.STT,.STEP,.OBJ,.STL,.AST,.BMS,.OFF,.PLY,.OCA,.SVG" />
					<label for="file-7">
					<span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
					<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></path></svg> Cargar Archivo</strong>
					</label>
				</div>
				<label class="fileAcept">Acepta .IGES .IGS .IV .STT .STEP .OBJ .STL .AST .BMS .OFF .PLY .OCA .SVG</label>
			</div>
			<?php if ($post_group_child_id=='2') { ?>
				<div class="filesRequired">
					<label><strong>Cargar archivo 2D</strong></label>
					<label class='error' style='display: none;'>Este campo es requerido</label>
					<div class="box filesRequired">
						<input type="file" name="file-8" id="file-8" class="inputfile inputfile-6" data-multiple-caption="{count} archivos seleccionados"  accept=".DXF,.DWG,.PDF,.SVC,.TAR,.JPG,.TIF,.BMP,.CDR" />
						<label for="file-8">
						<span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
						<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></path></svg> Cargar Archivo</strong>
						</label>
					</div>
					<label class="fileAcept">Acepta .DXF .DWG .PDF .SVC .TAR .JPG .TIF .BMP .CDR</label>
				</div>
			<?php } ?>
		</div>
		<script>
		'use strict';

		;( function ( document, window, index )
		{
			var inputs = document.querySelectorAll( '.inputfile' );
			Array.prototype.forEach.call( inputs, function( input )
			{
				var label	 = input.nextElementSibling,
					labelVal = label.innerHTML;

				input.addEventListener( 'change', function( e )
				{
					var fileName = '';
					if( this.files && this.files.length > 1 )
						fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
					else
						fileName = e.target.value.split( '\\' ).pop();

					if( fileName )
						label.querySelector( 'span' ).innerHTML = fileName;
					else
						label.innerHTML = labelVal;
				});

				// Firefox bug fix
				input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
				input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
			});
		}( document, window, 0 ));
		 </script>
	<?php }
	if (!empty($ArrayChecks)){

		foreach ($ArrayChecks as $key => $value) {
			$group_id 		= $value['group_id'];
			$name_group 	= $value['name_group'];
			$name 			= $value['name'];
			$input_text 	= $value['input_text'];
			$id 			= $value['id'];
			$group_child_id = $value['group_child_id'];
			$col 			= $value['col'];
			$input_text 	= $value['input_text'];



			if ($group_id != $curren_group) {
					$curren_group = $group_id;
					echo "<div class='col-xs-12 title-group groupRequired' data-name='group_$group_id'><label>$name_group</label>";
					echo "<label class='error' style='display: none;'>Este campo es requerido</label></div>";
				}
			?>

			<div class="radio-option <?php echo $col; ?>">
					<input  type="radio" name="group_<?php echo $group_id; ?>" value="<?php echo $id; ?>" data-group_child_id="<?php echo $group_child_id; ?>" data-group_id="<?php echo $group_id; ?>" data-have_text='<?php echo $input_text; ?>' class='option-manufacture' id='option_<?php echo $id; ?>'   >
					<label for="option_<?php echo $id; ?>"><?php echo $name; ?></label>

					<?php if ($input_text == '1') { ?>
						<input required="required" disabled='disabled' type='text' name='group_option_<?php echo $id; ?>' id='group_option_<?php echo $id; ?>' class='group_option_text option_text_<?php echo $group_id; ?>'  >
					<?php } ?>

			</div>
	<?php }


	$html = ob_get_clean();
	$html = '<div id="cont-">'.$html.'<div class="next-group"></div>';
	$result = 'true';
	}


	echo json_encode ( array (
        "result" => $result,
        "html" => $html
    ));
}
