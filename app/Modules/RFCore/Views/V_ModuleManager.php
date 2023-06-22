<div class="row tableTitle">
            <h3 >Modules disponibles (<span id="available-count">0</span>)</h3>&nbsp;
            <button class="btn btn-success float-right" style="margin-bottom: 10px; margin-left: 10px" onclick="reloadAvailableTable()"><i class="fas fa-sync-alt text-white"></i> Actualiser</button>
</div>
<table id="available-modules-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Note de version</th>
                        <th>Dépendances RF</th>
                        <th>Dépendances Projet</th>
                        <th>Actions</th>
                    </tr>
                </thead>
</table>
<hr>
<div class="row tableTitle">
            <h3 >Modules installés (<span id="installed-count">0</span>)</h3>&nbsp;
             <button class="btn btn-success float-right" style="margin-bottom: 10px; margin-left: 10px" onclick="reloadInstalledTable()"><i class="fas fa-sync-alt text-white"></i> Actualiser</button>
</div>
<table id="installed-modules-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Note de version</th>
                        <th>Dépendances RF</th>
                        <th>Dépendances Projet</th>
                        <th>Actions</th>
                    </tr>
                </thead>
</table>
<hr>
<div class="row tableTitle">
            <h3 >Mises à jour disponibles (<span id="update-count">0</span>)</h3>&nbsp;
            <button class="btn btn-success float-right" style="margin-bottom: 10px; margin-left: 10px" onclick="reloadUpdateTable()"><i class="fas fa-sync-alt text-white"></i> Actualiser</button>
</div>
<table id="update-modules-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Version</th>
                    <th>Note de version</th>
                    <th>Dépendances RF</th>
                    <th>Dépendances Projet</th>
                    <th>Actions</th>
                </tr>
                </thead>
</table>

<script>
        let availableTable;
        let installedTable;
        let updateTable;

        $(document).ready(function() {
            availableTable = $('#available-modules-table').DataTable({
                "ajax": {
                    "url": "<?php echo base_url("RF-BackOffice/getAvailableModules")?>",
                    "type": "POST",
                    "dataType": "json"
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
                },
                "columns": [
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "version"},
                    {"data": "release_note"},
                    {"data": "RF_dependencies"},
                    {"data": "Project_dependencies"}
                ],
                "columnDefs":[
                    {
                        'targets' : 6,
                        'searchable': false,
                        'className': 'dt-center',
                        'render': function (data, type, full, meta){
                            return '<button class="btn btn-success" type="button" onclick="installModule(\'' + full['name'] + '\',\''+ full['version']+'\','+ '\'RF\')"><i class="fas fa-download text-white"></i></button>';
                        }
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#available-count').html(availableTable.rows().count());
                }
            });

            installedTable = $('#installed-modules-table').DataTable({
                "ajax": {
                    "url": "<?php echo base_url("RF-BackOffice/getInstalledModules")?>",
                    "type": "POST",
                    "dataType": "json"
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
                },
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "version"},
                    {"data": "release_note"},
                    {"data": "RF_dependencies"},
                    {"data": "Project_dependencies"}
                ],
                "columnDefs":[
                    {
                        'targets' : 7,
                        'searchable': false,
                        'className': 'dt-center',
                        'render': function (data, type, full, meta){
                            return '<button class="btn btn-danger" type="button" onclick="uninstallModule(\'' + full['name'] + '\', \'' + full['id'] + '\')"><i class="fas fa-trash-alt text-white"></i></button>';
                        }
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#installed-count').html(installedTable.rows().count());
                }
            });

            updateTable = $('#update-modules-table').DataTable({
                "ajax": {
                    "url": "<?php echo base_url("RF-BackOffice/getUpdateModules")?>",
                    "type": "POST",
                    "dataType": "json"
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
                },
                "columns": [
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "version"},
                    {"data": "release_note"},
                    {"data": "RF_dependencies"},
                    {"data": "Project_dependencies"}
                ],
                "columnDefs":[
                    {
                        'targets' : 6,
                        'searchable': false,
                        'className': 'dt-center',
                        'render': function (data, type, full, meta){
                            return '<button class="btn btn-warning" type="button" onclick="updateModule(\'' + full['name'] + '\',\''+ full['version']+'\','+ '\'RF\')"><i class="fas fa-download text-white"></i></button>';
                        }
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#update-count').html(updateTable.rows().count());
                }
            });

        } );

        function reloadAvailableTable(){
            availableTable.ajax.reload(function ( json ) {
                $('#available-count').html(json.data.length);
            }, false);
        }
        function reloadInstalledTable(){
            installedTable.ajax.reload(function ( json ) {
                $('#installed-count').html(json.data.length);
            }, false);
        }
        function reloadUpdateTable(){
            updateTable.ajax.reload(function ( json ) {
                $('#update-count').html(json.data.length);
            }, false);
        }

        function installModule(moduleName,moduleVersion,moduleType) {
            var answer = window.confirm("Êtes-vous sûr de vouloir installer le module " + moduleName + " ?");
            if (answer) {
                let data = {'name':moduleName,
                'version':moduleVersion,
                'type':moduleType};
                let options = {
                    'url' : '<?php echo base_url("RF-BackOffice/installModule")?>',
                    'type' : 'post',
                    "dataType": "json",
                    "data" : data,
                    'callBack' : function (ret) {
                        if (ret['status'] === "ok") {
                            alert(ret['response']['status']);
                            reloadInstalledTable();
                            reloadAvailableTable();
                        }
                        else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
                    }
                };
                sendAjax(options);
            }
        }

        function uninstallModule(moduleName, moduleID) {
            var answer = window.confirm("Êtes-vous sûr de vouloir désinstaller le module " + moduleName + " ?");
            if (answer) {
                let data = {'name':moduleName};
                let options = {
                    'url' : '<?php echo base_url("RF-BackOffice/uninstallModule")?>',
                    'type' : 'post',
                    "dataType": "json",
                    "data" : data,
                    'callBack' : function (ret) {
                        if (ret['status'] === "ok") {
                            alert(ret['response']['message']);
                            reloadInstalledTable();
                            reloadAvailableTable();
                        }
                        else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
                    }
                };
                sendAjax(options)
            }
        }

        function updateModule(moduleName,moduleVersion,moduleType) {
            var answer = window.confirm("Êtes-vous sûr de vouloir mettre à jour le module " + moduleName + " ?");
            if (answer) {
                let data = {'name':moduleName,
                    'version':moduleVersion,
                    'type':moduleType};
                let options = {
                    'url' : '<?php echo base_url("RF-BackOffice/updateModule")?>',
                    'type' : 'post',
                    "dataType": "json",
                    "data" : data,
                    'callBack' : function (ret) {
                        if (ret['status'] === "ok") {
                            alert(ret['response']['status']);
                            reloadInstalledTable();
                            reloadAvailableTable();
                            reloadUpdateTable();
                        }
                        else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
                    }
                };
                sendAjax(options);
            }
        }
	</script>
