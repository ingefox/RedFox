<?php
/**
 * @var array $errors
 * @var E_User $user
 * @var array $roles_cb
 * @var string $action
 * @var string $actionButton
 */

use RFCore\Entities\E_User;

?>

<form id="formUser" accept-charset="utf-8" method="POST" >
    <input name="user[id]" type="hidden" value="<?php echo isset($user)? $user->getProperty('id') : set_value('user[id]'); ?>">
    <input name="id" type="hidden" value="<?php echo isset($user)? $user->getProperty('id') : set_value('id'); ?>">

    <div class="d-flex justify-content-around">
        <div class="form-group col-sm-6">
            <label for="userEmail"><?= lang('users.fields.email')?> *</label>
            <div class="input-group">
                <input id='userEmail' name="user[email]" class="<?= (!empty($errors['user.email'])) ? 'is-invalid':'' ?> form-control" type="text" value="<?= set_value('user[email]', isset($user)? $user->getProperty('email') : null);?>" data-validation="required|valid_email">
                <div class="invalid-feedback <?= (!empty($errors['user.email'])) ? '':'d-none' ?>" role="alert"><?= $errors['user.email'] ?></div>
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label for="userPhone"><?= lang('users.fields.phone')?></label>
            <div class="input-group">
                <input id='userPhone' name="user[phone]" class="<?= (!empty($errors['user.phone'])) ? 'is-invalid':'' ?> form-control" type="text" value="<?= set_value('user[phone]', isset($user)? $user->getProperty('phone') : null);?>" data-validation="valid_phone">
                <div class="invalid-feedback <?= (!empty($errors['user.phone'])) ? '':'d-none' ?>" role="alert"><?= $errors['user.phone'] ?></div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-around">

        <div class="form-group col-sm-6">
            <label for="userLastname"><?= lang('users.fields.lastname')?></label>
            <div class="input-group">
                <input id='userLastname' name="user[lastname]" class="<?= (!empty($errors['user.lastname'])) ? 'is-invalid':'' ?> form-control" type="text" value="<?= set_value('user[lastname]', isset($user)? $user->getProperty('lastname') : null);?>">
                <div class="invalid-feedback <?= (!empty($errors['user.lastname'])) ? '':'d-none' ?>" role="alert"><?= $errors['user.lastname'] ?></div>
            </div>
        </div>

        <div class="form-group col-sm-6">
            <label for="userFirstname"><?= lang('users.fields.firstname')?></label>
            <div class="input-group">
                <input id='userFirstname' name="user[firstname]" class="<?= (!empty($errors['user.firstname'])) ? 'is-invalid':'' ?> form-control"  type="text" value="<?= set_value('user[firstname]', isset($user)? $user->getProperty('firstname') : null);?>">
                <div class="invalid-feedback <?= (!empty($errors['user.firstname'])) ? '':'d-none' ?>" role="alert"><?= $errors['user.firstname'] ?></div>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="userRoleSelect"><?= lang('users.fields.roles')?> *</label>
		<select name="user[type]" id="userRoleSelect" class="form-control">
			<?php foreach(ROLES_ARRAY_STR as $key => $label): ?>
				<option value="<?= $key ?>" <?= set_select('user[type]', $key, ($key == (!empty($user) ? $user->getRoles():ROLE_USER))) ?>><?= $label ?></option>
			<?php endforeach; ?>
		</select>

		<div class="invalid-feedback <?= (!empty($errors['user.type'])) ? '':'d-none' ?>" role="alert"><?= $errors['user.type'] ?></div>
    </div>

	<?php if (isset($alert) && isset($type)): ?>
		<div class="col-sm-12" style="margin-top: 20px; margin-bottom: 0; padding-bottom: 0 ">
			<hr>
			<div style="margin-top: 20px; margin-bottom: 20px" class="alert alert-<?= $type ?>"><?= $alert ?></div>
			<div class="form-group text-center" style="margin-bottom: 0">
				<button type="button" data-dismiss="modal" class="btn btn-default btn-secondary btn-block">Fermer</button>
			</div>
		</div>

	<?php else: ?>
		<div class="col-sm-12" style="margin-top: 20px; margin-bottom: 0; padding-bottom: 0 ">
			<hr>
			<div class="form-group text-center" style="margin-top: 20px; margin-bottom: 0">
				<button type="submit" class="btn btn-default btn-secondary btn-block"><?= $actionButton;?></button>
			</div>
		</div>
	<?php endif; ?>

    <input name="submitted" type="hidden" value="true">
</form>

<script>
    var passwordField = $("[name='user[password]']");
    var passConfField = $("[name='user[passConfirm]']");
    var form = $('#formUser');

    $(function () {
        form.on('submit',function (e) {

            e.preventDefault();

            let validation = validateInputs(form);
            let validForm = displayErrors(validation);

            if(validForm) {
                formData = new FormData(this);
                //methode load html
                $.ajax({
                    url: "<?= $action; ?>",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        form.parent().html(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Une erreur s'est produite : " + errorThrown + " \n" + textStatus);
                    }
                });

            }
            //loadHTML('<?php echo base_url($action)?>', form.serialize(), form.parent());
            //e.preventDefault();
        });
    });
</script>
