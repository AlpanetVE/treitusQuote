	<style type="text/css">
		.table-striped td {
		    vertical-align: middle !important;
		}
		h3 {
			margin: 15px 5px 5px 0px;
			color: black;
		}
		a.btn, a.btn:hover {
		    color: white;
		    min-width: auto;
		}
	</style>
	<div>
		<?php
		if (!empty($arrQuotePendiente)) {
			?>
			<h3>Pendiente</h3>
			<table class="table table-striped" border="0" align="center" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th>Nombre del Proyecto</th>
					<th>Email</th>
					<th>Pieza</th>
					<th>Fecha Requerida</th>
					<th></th>
				</tr>
			</thead>
			<?php
			foreach ($arrQuotePendiente as $cont => $val) { ?>
				<tr>
					<td>
						<?php echo $val['name_project'];?>
					</td>
					<td>
						<?php echo $val['email'];?>
					</td>
					<td>
						<?php echo $val['piece'];?>
					</td>
					<td>
						<?php echo $val['date_needed'];?>
					</td>
					<td>
						<a class="btn btn-info" href="<?php echo $urlSee.$val['id'];?>">Info</a> 
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
		}
		if (!empty($arrQuotePorPagar)) {
			?>
			<h3>Cotización</h3>
			<table class="table table-striped" border="0" align="center" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th>Nombre del Proyecto</th>
					<th>Email</th>
					<th>Pieza</th>
					<th>Costo</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<?php
			foreach ($arrQuotePorPagar as $cont => $val) { ?>
				<tr>
					<td>
						<?php echo $val['name_project'];?>
					</td>
					<td>
						<?php echo $val['email'];?>
					</td>
					<td>
						<?php echo $val['piece'];?>
					</td>
					<td>
						<?php echo number_format($val['cost'], 2, ',', '.').' $USD';?>
					</td>
					<td>						
						<a class="btn btn-info" href="<?php echo $urlSee.$val['id'];?>">Info</a> 
					</td>
					<td>
						<a class="btn btn-primary" href="<?php echo $urlPay.$val['id'];?>">Pagar</a>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
		}
		if (!empty($arrQuoteProceso)) {/*
			?>
			<h3>Processing</h3>
			<table class="table table-striped" border="0" align="center" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th>Name Project</th>
					<th>Email</th>
					<th>Piece</th>
					<th>Cost</th>
				</tr>
			</thead>
			<?php
			foreach ($arrQuoteProceso as $cont => $val) { ?>
				<tr>
					<td>
						<?php echo $val['name_project'];?>
					</td>
					<td>
	<	a class="btn btn-info" href="<?php echo $urlSee.$val['id'];?>"> <?phpInfo</a>	</td>
					<td>
						<?php echo $val['email'];?>
					</td>
					<td>
						<?php echo $val['piece'];?>
					</td>
					<td>
						<?php echo number_format($val['cost'], 2, ',', '.').' $USD';?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
		*/}
		if (!empty($arrQuotePorPagarDos)) {
			?>
			<h3>Proyecto en proceso</h3>
			<table class="table table-striped" border="0" align="center" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th>Nombre del Proyecto</th>
					<th>Email</th>
					<th>Pieza</th>
					<th>Costo</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<?php
			foreach ($arrQuotePorPagarDos as $cont => $val) { ?>
				<tr>
					<td>
						<?php echo $val['name_project'];?>
					</td>
					<td>
						<?php echo $val['email'];?>
					</td>
					<td>
						<?php echo $val['piece'];?>
					</td>
					<td>
						<?php echo number_format($val['cost'], 2, ',', '.').' $USD';?>
					</td>
					<td>
						<a class="btn btn-info" href="<?php echo $urlSee.$val['id'];?>">Info</a> 
					</td>
					<td>
						<a class="btn btn-primary" href="<?php echo $urlPay.$val['id'];?>">Pagar</a> 
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
		}
		if (!empty($arrQuoteFinalizado)) {
			?>
			<h3>Registro</h3>
			<table class="table table-striped" border="0" align="center" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th>Nombre del Proyecto</th>
					<th>Email</th>
					<th>Pieza</th>
					<th>Costo</th>
					<th></th>
				</tr>
			</thead>
			<?php
			foreach ($arrQuoteFinalizado as $cont => $val) { ?>
				<tr>
					<td>
						<?php echo $val['name_project'];?>
					</td>
					<td>
						<?php echo $val['email'];?>
					</td>
					<td>
						<?php echo $val['piece'];?>
					</td>
					<td>
						<?php echo number_format($val['cost'], 2, ',', '.').' $USD';?>
					</td>					
					<td>
						<a class="btn btn-info" href="<?php echo $urlSee.$val['id'];?>">Info</a> 
					</td>

				</tr>
			<?php } ?>
		</table>
		<?php
		}


		?>
	</div>
