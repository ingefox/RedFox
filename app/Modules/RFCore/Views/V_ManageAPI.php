<div class="row tableTitle">
        <h3>Gestion des API </h3>&nbsp;
        <br>
        <button class="btn btn-success" style="margin-bottom: 10px;margin-left: 10px;" onclick="reloadAPITable()"><i class="fas fa-sync-alt text-white"></i> Actualiser</button>
</div>

<div class="table-responsive">
        <table id="API-table" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>Clé</th>
                <th>Description</th>
                <th>Valeur</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
</div>
<script>
    let apiTable;

    $(document).ready(function() {
        apiTable = $('#API-table').DataTable({
            "ajax": {
                "url": "<?php echo base_url("RF-BackOffice/getAPIList")?>",
                "type": "POST",
                "dataType": "json",
				"responsive": true
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            },
            "columns": [
                {"data": "id"},
                {"data": "key"},
                {"data": "description"},
                {"data": "value"}
            ],
            "columnDefs": [
                {
                    'targets': 4,
                    'searchable': false,
                    'className': 'dt-center',
                    'render': function (data, type, full, meta) {
                        let apiData = "{'id':"+full['id']+",'key':'"+full['key']+"','description':'"+full['description'].replace(/'/g, "\\'")+"','value':'"+full['value']+"'}";
                        return '<button class="btn btn-warning" type="button" onclick="openModal(\'<?php echo base_url("RF-BackOffice/editAPI")?>\', \'Modifier une API\','+apiData+')"><i class="fas fa-edit text-white"></i></button>';
                    }
                }
            ]
        });
    } );

    function reloadAPITable(){
        apiTable.ajax.reload();
    }

    $(document).on('hide.bs.modal','#RF_MODAL', function () {
        reloadAPITable();
    })

</script>
