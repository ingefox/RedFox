<div class="form-row vertical-divider">
	<div class="col-6">
            <h2>Gestion du schéma</h2>
		<br>
		<div class="form-group">
			<button id="validateBtn" onclick='validateSchema()' class="btn btn-success btn-block">Valider le Schema</button>
		</div>
		<div class="form-group">
			<button id="updateBtn" onclick='updateSchema()' class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Mettre à jour le Schema</button>
		</div>
        </div>

	<div class="col-6">
            <h2>Exporter la base de données</h2>
		<br>

		<div class="form-group form-inline">
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text bg-<?php echo THEME_COLOR; ?> text-white">Format</div>
				</div>
				<select id="format" onclick='hideShow()' class="form-control">
					<option value="sql">SQL</option>
					<option value="csv">CSV</option>
                </select>
				<div class="input-group-append">
					<div class="input-group-text">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" id="structure" name="cb_type_export">
							<label class="form-check-label" for="structure">Structure</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" id="data" name="cb_type_export">
							<label class="form-check-label" for="data">Données</label>
						</div>
            </div>
            </div>
            </div>
        </div>

		<div class="form-group">
			<button class="btn btn-<?php echo THEME_COLOR; ?> float-center btn-block" onclick="exportDatabase()">
				Exporter
			</button>
		</div>
    </div>
</div>

<script>

    function hideShow()
    {
		let cb = document.getElementById('cb');
		let format = document.getElementById('format');
		let selectedValue = format[format.selectedIndex].value;
		if(selectedValue === 'sql')
        {
            cb.style.display = 'block';
        }
        else
        {
            cb.style.display = 'none';
        }
    }

    function validateSchema() {
        let answer = window.confirm("Valider le schema ?");
        if (answer) {
            let options = {
                'url' : '<?php echo base_url("RF-BackOffice/validateSchema")?>',
                'type' : 'post',
                "dataType": "json",
                'callBack' : function (ret) {
                    if (ret['status'] === 'ok') {
                        let title = 'Erreur';
                        if(ret['response']['data']['status'] === true) title = "Succès";
                        openModalText(title, ret['response']['data']['message']);
                    }
                    else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
                }
            };
            sendAjax(options)
        }
    }

    function updateSchema() {
        let answer = window.confirm("Mettre à jour le schema ?");
        if (answer) {
            let options = {
                'url' : '<?php echo base_url("RF-BackOffice/updateSchema")?>',
                'type' : 'post',
                'dataType': 'json',
                'callBack' : function (ret) {
                    if (ret['status'] === 'ok') {
                        let title = 'Erreur';
                        if(ret['response']['data']['status'] === true) title = "Succès";
                        openModalText(title, ret['response']['data']['message']);
                    }
                    else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
                }
            };
            sendAjax(options);
        }
    }


    function exportDatabase() {
		if (document.getElementById('format').value === 'sql') {
			let structure = document.getElementById("structure");
			let data = document.getElementById("data");

            //if nothing is checked
			if (structure.checked === false && data.checked === false) {
                alert("Attention!\nSelectionnez au moins une chose à exporter!");
			} else {
                //what to export
				let param = '?';

				if (structure.checked) param += 'structure=true&';
                    else param += 'structure=false&';

				if (data.checked) param += 'data=true';
				else param += 'data=false';

				window.location.assign('<?php echo base_url("RF-BackOffice/exportDbSql")?>' + param);
               
            }
		} else
        {
			//CSV
            window.location.assign('<?php echo base_url("RF-BackOffice/exportDbCsv")?>');
        }
    }
</script>
