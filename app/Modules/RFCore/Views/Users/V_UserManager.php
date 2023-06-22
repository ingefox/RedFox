

<div class="page-content col-sm-12 col-md-10 col-xl-8" style="margin-left: auto; margin-right: auto; min-width: 900px">
	<div class="rf-toolbar">
		<!-- LEFT SIDE -->
		<span class="d-flex">
			<a class="d-flex btn btn-outline-accent" onclick="openModal('<?= base_url('Users/add')?>', 'Ajouter un utilisateur')"><i class="fas fa-user-plus"></i> <?= lang('buttons.add') ?></a>
			<a style="margin-left: 20px" class="d-flex btn btn-outline-accent" onclick="reloadUsersTable()"><i class="fas fa-sync-alt"></i> <?= lang('buttons.refresh') ?></a>
		</span>
		<!-- RIGHT SIDE -->
		<span>
		</span>
	</div>

	<table id="users-table" class="table table-sm table-striped table-bordered" style="width:100%">
		<thead>
			<tr>
				<th><?= lang('users.fields.id') ?></th>
				<th><?= lang('users.fields.email') ?></th>
				<th><?= lang('users.fields.roles') ?></th>
				<th>Actions</th>
			</tr>
		</thead>
	</table>
</div>

<script>
	let userTable;

	$(document).ready(function() {
		userTable = $('#users-table').DataTable({
			"ajax": {
				"url": "<?php echo base_url("Users/getList")?>",
				"type": "POST",
				"dataType": "json"
			},
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
			},
			"columns": [
				{"data": "id"},
				{"data": "email"},
				{"data": "roles_str"}
			],
			"columnDefs": [
				{
					'targets': 3,
					'searchable': false,
					'className': 'dt-center',
					'render': function (data, type, full, meta) {
						let ret = '';

						if (<?= session()->get('id'); ?> !== full['id']) {
							ret = '<button class="btn btn-warning" type="button" onclick="openModal(\'<?= base_url("Users/edit")?>\', \'Modifier un utilisateur\',{\'userId\':\'' + full['id'] + '\'})"><i class="fas fa-user-edit text-white"></i></button>';
							ret += ' <button class="btn btn-danger" type="button" onclick="deleteUser(\'' + full['email'] + '\', '+full['id']+')"><i class="fas fa-trash-alt text-white"></i></button>';
						}
						else {
							ret = '<button class="btn btn-warning" type="button" onclick="openModal(\'<?= base_url("Users/edit")?>\', \'Modifier un utilisateur\',{\'userId\':\'' + full['id'] + '\'})"><i class="fas fa-user-edit text-white"></i></button>';
							ret += ' <button class="btn btn-secondary" type="button" onclick="alert(\'Vous ne pouvez pas supprimer votre compte.\')"><i class="fas fa-trash-alt text-white"></i></button>';
						}

						return ret;
					}
				}
			]
		});
	});

	/**
	 * Delete a user from the DB
	 * @param email {string} Email address of the user to delete
	 * @param id {int} ID of the user to delete
	 */
	function deleteUser(email,id) {
		let answer = window.confirm("Êtes-vous sûr de vouloir supprimer l'utilisateur '" + email + "' ?");
		if (answer) {

			$.ajax({
				url: "<?= base_url('Users/delete') ?>/"+id,
				type: "POST",
				dataType: "json",
				success: function (data) {
					switch (data['status']) {
						case <?= SC_SUCCESS ?>:
							reloadUsersTable();
							displayToast('Utilisateur supprimé', 'L\'utilisateur a été supprimé avec succès', <?= TOAST_OK ?>);
							break;
						default:
							displayToast('Erreur interne', data['reason'] ?? 'Une erreur interne est survenue. Merci de réessayer ultérieurement', <?= TOAST_ERROR ?>);
							break;
					}
				}
			});
		}
	}

	function reloadUsersTable(){
		userTable.ajax.reload();
	}

	$(document).on('hide.bs.modal','#RF_MODAL', function () {
		reloadUsersTable();
	});

</script>

