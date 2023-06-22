<?php
/**
 * @var $categories array
 * @var $errors array
 */

$errors = $errors ?? [];
$action = base_url('Mantis/addIssue');
$features = defined('INT_MANTIS_FEATURE_REFERENCES') ? constant('INT_MANTIS_FEATURE_REFERENCES') : [];
?>

<form action="<?= $action ?>" id="issueForm">

	<!-- FEATURE -->
	<div class="form-group">
		<div class="col-12">
			<label for="issueFeatureSelect">Fonctionnalité concernée *</label>
			<select required data-validation="required" name="issue[feature]" id="issueFeatureSelect" class="<?= (!empty($errors['issue.feature'])) ? 'is-invalid':'' ?> form-control">
				<option></option>
				<optgroup label="Général">
					<option value="Général - Autre">Autre</option>
				</optgroup>

				<?php foreach ($features as $featureCategory => $subFeatures): ?>
					<optgroup label="<?= $featureCategory ?>">
						<?php foreach ($subFeatures as $feature): ?>
							<option value="<?= $featureCategory.' - '.$feature ?>"><?= $feature ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
			<div class="invalid-feedback <?= (!empty($errors['issue.category'])) ? '':'d-none' ?>" role="alert"><?= $errors['issue.feature'] ?></div>
		</div>
	</div>

	<!-- CATEGORY -->
	<div class="form-group">
		<div class="col-12">
			<label for="issueCategorySelect">Catégorie *</label>
			<select required data-validation="required" name="issue[category][id]" id="issueCategorySelect" class="<?= (!empty($errors['issue.category.id'])) ? 'is-invalid':'' ?> form-control">
				<?php foreach ($categories as $category): ?>
					<option value="<?= $category->id ?>"><?= $category->name ?></option>
				<?php endforeach; ?>
			</select>
			<div class="invalid-feedback <?= (!empty($errors['issue.category.id'])) ? '':'d-none' ?>" role="alert"><?= $errors['issue.category.id'] ?></div>
		</div>
	</div>

	<!-- SUMMARY -->
	<div class="form-group">
		<div class="col-12">
			<label for="issueSummary">Résumé *</label>
			<input placeholder="Exemple : &#8220;Erreur rencontrée lors de l'ajout d'un utilisateur&#8221;" data-validation="required" type="text" name="issue[summary]" id="issueSummary" class="<?= (!empty($errors['issue.summary'])) ? 'is-invalid':'' ?> form-control">
			<div class="invalid-feedback <?= (!empty($errors['issue.summary'])) ? '':'d-none' ?>" role="alert"><?= $errors['issue.summary'] ?></div>
		</div>
	</div>

	<!-- DESCRIPTION -->
	<div class="form-group">
		<div class="col-12">
			<label for="issueDescription">Description *</label>
			<textarea required data-validation="required" name="issue[description]" id="issueDescription" class="<?= (!empty($errors['issue.description'])) ? 'is-invalid':'' ?> form-control" rows="5" placeholder="Merci de fournir ici une description précise de votre problème et, si possible, les étapes à réaliser pour le reproduire."></textarea>
			<div class="invalid-feedback <?= (!empty($errors['issue.description'])) ? '':'d-none' ?>" role="alert"><?= $errors['issue.description'] ?></div>
		</div>
	</div>

	<!-- DRAG AND DROP AREA -->
	<div class="form-group">
		<div class="col-12">
			<label for="issueAttachments">Pièces jointes</label>
			<div
				id="issueAttachments"
				ondrop="dropHandler(event);"
				ondragover="dragOverHandler(event);"
				ondragleave="dragLeaveHandler(event);"
				class="drag-and-drop-area clickable"
			>
				<p>Déposez vos pièces jointes dans cette zone</p>
				<ul id="issueAttachmentsList"></ul>
				<button role="button" type="button" id="resetFormBtn" onclick="resetForm()" class="btn btn-sm btn-secondary d-none">Réinitialiser la sélection</button>
			</div>
			<input id="issueFileInput" type="file" class="d-none" onchange="dropHandler(event);">
		</div>
	</div>

	<!-- SUBMIT -->
	<div class="form-group" style="margin-bottom: 0; margin-top: 20px">
		<div class="col-12">
			<hr>
			<button class="btn btn-accent btn-block" style="margin-top: 20px"><i class="fas fa-paper-plane"></i>ENVOYER</button>
		</div>
	</div>
</form>

<script>
	var form 					= $('#issueForm');
	var featureSelect		 	= $('#issueFeatureSelect');
	var categorySelect 			= $('#issueCategorySelect');
	var issueAttachmentsList 	= $('#issueAttachmentsList');
	var issueAttachments 		= $('#issueAttachments');
	var resetFormBtn 			= $('#resetFormBtn');

	var filesList 				= [];

	var issueFormData;

	$(document).ready(function() {
		issueFormData = new FormData();

		// Handling pasted files
		window.addEventListener('paste', e => {
			let files = e.clipboardData.files;

			// If there are files, add them to the form data
			if (files.length) {
				e.preventDefault();
				e.stopPropagation();
				for (let i = 0; i < files.length; i++) {
					addFileToIssue(files[i]);
				}
			}
		});

		issueAttachments.on('click', function(e) {
			// Making sure that the click event was not triggered by the reset button
			if (e.target.id !== 'resetFormBtn') {
				$('#issueFileInput').click();
			}
		});

		// Instantiating tail.select input for feature select
		tail.select('#issueFeatureSelect', {
			deselect: true,
			placeholder: 'Sélectionnez une fonctionnalité...',
			search: true,
			locale: 'fr',
		});

		// If no option is provided in the default group, the first option of the first group is selected instead
		// To prevent that behaviour, a dummy option is added to the default group and removed once the tail.select is instantiated
		$('#issueFeatureSelect').next().find('li[data-group="#"]').remove();

		// Instantiating tail.select input for category select
		tail.select('#issueCategorySelect', {
			deselect: true,
			placeholder: 'Sélectionnez une catégorie...',
			search: true,
			locale: 'fr',
		});
	});

	$(function () {
		form.on('submit',function(e){
			e.preventDefault();

			let validation = validateInputs(form);
			let validForm = displayErrors(validation);

			if(validForm) {

				let formData = form.serializeArray();

				// Adding form data to FormData object
				for(let i = 0; i < formData.length; i++) {
					issueFormData.append(formData[i].name, formData[i].value);
				}

				$.ajax({
					url: "<?= $action; ?>",
					type: "POST",
					dataType: "json",
					data: issueFormData,
					processData: false,  // tell jQuery not to process the data
					contentType: false,  // tell jQuery not to set contentType
					success: function (response) {
						$('#RF_MODAL').modal('hide');
						switch (response['status'])
						{
							case <?= SC_SUCCESS ?>:
								displayToast('Demande de support créée', response['reason'], <?= TOAST_OK ?>);
								break;
							default:
								displayToast('Erreur interne', response['reason'] ?? 'Une erreur interne est survenue. Merci de réessayer ultérieurement', <?= TOAST_ERROR ?>);
								break;
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						displayToast('Erreur interne', "Une erreur s'est produite : \n" + errorThrown + " \n" + textStatus, <?= TOAST_ERROR ?>);
					}
				});
			}
		});
	});

	// ================================
	// ================================
	// DRAG & DROP HANDLING
	// ================================
	// ================================

	/**
	 * Function responsible for adding files to the issue
	 * @param file
	 */
	function addFileToIssue(file)
	{
		filesList.push(file);
		issueFormData.append('issue[attachments][]', file);
		issueAttachmentsList.append('<li>' + file.name + '</li>');
		resetFormBtn.removeClass('d-none');
	}

	function dropHandler(ev) {
		issueAttachments.removeClass('highlight');
		resetFormBtn.removeClass('d-none');

		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();

		// Checking from which element the drop event was triggered (drag & drop area or file input)
		if ((ev.dataTransfer !== undefined) && (ev.dataTransfer !== null))
		{
			// Event triggered from drag & drop area

			if (ev.dataTransfer.items) {
				// Use DataTransferItemList interface to access the file(s)
				[...ev.dataTransfer.items].forEach((item, i) => {
					// If dropped items aren't files, reject them
					if (item.kind === 'file') {
						const file = item.getAsFile();
						addFileToIssue(file);
					}
				});
			} else {
				// Use DataTransfer interface to access the file(s)
				[...ev.dataTransfer.files].forEach((file, i) => {
					addFileToIssue(file);
				});
			}
		}
		else {
			// Event triggered from file input

			// Use target interface to access the file(s)
			[...ev.target.files].forEach((file, i) => {
				addFileToIssue(file);
			});
		}

	}

	function dragOverHandler(ev) {
		issueAttachments.addClass('highlight');

		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();
	}

	function dragLeaveHandler(ev) {
		issueAttachments.removeClass('highlight');

		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();
	}

	/**
	 * Reset the files selection of the form
	 */
	function resetForm() {
		issueAttachmentsList.empty();
		issueFormData = new FormData();
		resetFormBtn.addClass('d-none');
		filesList = [];
	}
</script>
