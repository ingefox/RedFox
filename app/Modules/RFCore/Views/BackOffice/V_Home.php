<h2>Tableau de bord</h2>
<hr>

<div class="row vertical-divider">
	<div class="col-5">
		<div class="row tableTitle text-danger">
			<h4>Informations système</h4>&nbsp;
		</div>
		<br>
		<div class="form-group row">
			<label for="RFVersion" class="col-sm-5 col-form-label"><strong>Version RedFox</strong></label>
			<div class="col-sm-6">
				<input type="text" style="font-weight: bold" class="form-control" id="RFVersion" readonly value="<?php echo $RFVersion;	?>">
			</div>
		</div>

		<div class="form-group row">
			<label for="RFDebug" class="col-sm-5 col-form-label"><strong>Mode debug</strong></label>
			<div class="col-sm-6">
				<input type="text" style="font-weight: bold" class="form-control <?php echo CI_DEBUG ? 'is-valid':'is-invalid'?>" id="RFDebug" readonly value="<?php echo CI_DEBUG ? 'Activé':'Désactivé'?>">
			</div>
		</div>

		<div class="form-group row">
			<label for="RFModInt" class="col-sm-5 col-form-label"><strong>Module d'intégration</strong></label>
			<div class="col-sm-6">
				<input type="text" style="font-weight: bold" class="form-control" id="RFModInt" readonly value="<?php echo INTEGRATION_BASE_MODULE;?>">
			</div>
		</div>

		<div class="form-group row">
			<label for="RFBaseUrl" class="col-sm-5 col-form-label"><strong>Base URL</strong></label>
			<div class="col-sm-6">
				<input type="text" style="font-weight: bold" class="form-control" id="RFBaseUrl" readonly value="<?php echo base_url();?>">
			</div>
		</div>

		<div class="form-group row">
			<label for="RFProjectId" class="col-sm-5 col-form-label"><strong>Project ID</strong></label>
			<div class="col-sm-6">
				<input type="text" style="font-weight: bold" class="form-control" id="RFProjectId" readonly value="<?php echo PROJECT_ID;?>">
			</div>
		</div>
	</div>
	<div class="col-7">
		<div class="row tableTitle text-primary">
			<h4>Modules installés (<span id="installed-count">0</span>)</h4>&nbsp;
		</div>
		<table id="installed-modules-table" class="table table-striped table-bordered" style="width:100%">
			<thead>
			<tr>
				<th>Nom</th>
				<th>Version</th>
				<th>Description</th>
			</tr>
			</thead>
		</table>
	</div>
</div>

<script>
	let installedTable;

	$(document).ready(function()
		{
			installedTable = $('#installed-modules-table').DataTable({
				ajax: {
					url: "<?php echo base_url("RF-BackOffice/getInstalledModules")?>",
					type: "POST",
					dataType: "json"
				},
				language: {
					url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
				},
				columns: [
					{data: "name"},
					{data: "description"},
					{data: "version"},
				],
				initComplete: function(settings, json) {
					$('#installed-count').html(installedTable.rows().count());
				},
				bLengthChange : false, //thought this line could hide the LengthMenu
				bInfo : false,
			});
		}
	)
</script>
