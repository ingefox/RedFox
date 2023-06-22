<?php
/**
 * @var array $errors
 * @var array $roles_cb
 * @var string $action
 * @var string $actionButton
 */
?>

<div class="page-column-content text-center">
	<?php if(isset($result) && $result) : ?>
		<h1 class="page-header form-1st-page"><?= lang('users.register.confirmationTitle') ?></h1>

		<div class="form-group pb-3 text-center">
			<?= lang('users.register.confirmationText') ?>
		</div>
		<br>
		<div class="form-group">
			<a class="btn btn-<?= THEME_COLOR; ?> btn-round" href="<?= base_url(); ?>"><?= lang('emails.steps.3.backToMainPage') ?></a>
		</div>
	<?php elseif(isset($result) && !$result) : ?>
		<h1 class="page-header form-1st-page"><?= lang('users.register.errorTitle') ?></h1>

		<div class="form-group pb-3 text-center">
			<?= lang('users.register.errorText') ?>
		</div>
		<br>
		<div class="form-group">
			<a class="btn btn-<?= THEME_COLOR; ?> btn-round" href="<?= base_url(); ?>"><?= lang('emails.steps.3.backToMainPage') ?></a>
		</div>
	<?php else : ?>

		<h1 class="page-header form-1st-page"><?= lang('users.register.title') ?></h1>
		<h1 class="page-header form-2nd-page"><?= lang('users.register.password') ?></h1>

		<br>
		<form id="formUser" action="<?= $action; ?>" method="post" class="text-left">

			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------ FIRST PAGE ------------------------------------------- -->
			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------------------------------------------------------------- -->

			<div class="form-1st-page">
				<div class="form-row form-group">
					<div class="col-sm-6">
						<label for="firstname"><?= lang('users.fields.firstName') ?>*</label>
						<input type="text" id="firstname" name="user[firstname]" class="<?= isset($errors['user.firstname']) ? 'is-invalid':''?> form-control input-accent" value="<?= set_value('user[firstname]') ?>" data-validation="required">
						<div class="invalid-feedback <?= isset($errors['user.firstname']) ? '':'d-none' ?>" role="alert"><?= $errors['user.firstname'] ?></div>
					</div>
					<div class="col-sm-6">
						<label for="lastname"><?= lang('users.fields.lastName') ?>*</label>
						<input type="text" id="lastname" name="user[lastname]" class="<?= isset($errors['user.lastname']) ? 'is-invalid':''?> form-control input-accent" value="<?= set_value('user[lastname]') ?>" data-validation="required">
						<div class="invalid-feedback <?= isset($errors['user.lastname']) ? '':'d-none' ?>" role="alert"><?= $errors['user.lastname'] ?></div>
					</div>
				</div>

				<br>

				<div class="form-row form-group">
					<div class="col-sm-6">
						<label for="phone"><?= lang('users.fields.phone') ?>*</label>
						<input type="text" id="phone" name="user[phone]" class="<?= isset($errors['user.phone']) ? 'is-invalid':''?> form-control input-accent" value="<?= set_value('user[phone]') ?>" data-validation="required|valid_phone">
						<div class="invalid-feedback <?= isset($errors['user.phone']) ? '':'d-none' ?>" role="alert"><?= $errors['user.phone'] ?></div>
					</div>
					<div class="col-sm-6">
						<label for="email"><?= lang('users.fields.email') ?>*</label>
						<input type="text" id="email" name="user[email]" class="<?= isset($errors['user.email']) ? 'is-invalid':''?> form-control input-accent" value="<?= set_value('user[email]') ?>" data-validation="required|valid_email">
						<div class="invalid-feedback <?= isset($errors['user.email']) ? '':'d-none' ?>" role="alert"><?= $errors['user.email'] ?></div>
					</div>
				</div>

				<br>

				<div class="form-group">
					<?= lang('users.register.notice') ?>
				</div>

				<br>

				<div class="form-group text-center">
					<button type="button" onclick="switchPage(1, 2)" class="btn btn-<?= THEME_COLOR; ?> btn-round"><?= lang('buttons.next') ?></button>
				</div>
			</div>

			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------ SECOND PAGE ------------------------------------------ -->
			<!-- ------------------------------------------------------------------------------------------------- -->
			<!-- ------------------------------------------------------------------------------------------------- -->

			<div class="form-2nd-page">

				<div class="form-row form-group">
					<div class="col-sm-6 offset-sm-3">
						<label for="password"><?= lang('users.fields.password') ?>*</label>
						<input type="password" id="password" name="user[password]" class="<?= isset($errors['user.password']) ? 'is-invalid':''?> form-control input-accent" data-validation="required">
						<div class="invalid-feedback <?= isset($errors['user.password']) ? '':'d-none' ?>" role="alert"><?= $errors['user.password'] ?></div>
					</div>
				</div>

				<div class="form-row form-group">
					<div class="col-sm-6 offset-sm-3">
						<label for="inputPasswordConf"><?= lang('users.fields.passwordConf') ?>*</label>
						<input type="password" id="passwordConf" name="user[passwordConf]" class="<?= isset($errors['user.passwordConf']) ? 'is-invalid':''?> form-control input-accent" data-validation="required">
						<div class="invalid-feedback <?= isset($errors['user.passwordConf']) ? '':'d-none' ?>" role="alert"><?= $errors['user.passwordConf'] ?></div>
					</div>
				</div>
				<br>
				<div class="form-group pb-3 text-center">
					<?= lang('emails.steps.3.passwordNotice') ?>
					<ul class="text-left pt-3 text-height-3 offset-sm-4">
						<li id="pwdCheckLength"><?= lang('emails.steps.3.pwdCheckLength') ?></li>
						<li id="pwdCheckUpper"><?= lang('emails.steps.3.pwdCheckUpper') ?></li>
						<li id="pwdCheckLower"><?= lang('emails.steps.3.pwdCheckLower') ?></li>
						<li id="pwdCheckNumber"><?= lang('emails.steps.3.pwdCheckNumber') ?></li>
					</ul>
					<?= lang('emails.steps.3.passwordNoticeBis') ?>
				</div>

				<?php if (defined('KEY_INDEX_GTU')): ?>
					<div class="form-group custom-control custom-checkbox">
						<div class="col-sm-8 offset-sm-2">
							<input value="1" type="checkbox" id="CGUValidated" name="user[CGUValidated]" class="<?= isset($errors['user.CGUValidated']) ? 'is-invalid':''?> custom-control-input" required>
							<label class="font-bold custom-control-label" for="CGUValidated"><?= lang('users.fields.CGUValidated1') ?><a href="#" onclick="openModal('<?= base_url('WebFox/LegalNotice/display/'.constant('KEY_INDEX_GTU')) ?>','<?= str_replace("'", "\'", lang('legalNotices.editMenu.GTU')) ?>')"><?= lang('users.fields.CGUValidated2') ?></a></label>
							<div class="invalid-feedback <?= isset($errors['user.CGUValidated1']) ? '':'d-none' ?>" role="alert"><?= $errors['user.CGUValidated1'] ?></div>
						</div>
					</div>
				<?php endif;?>
				<br>
				<div class="form-group text-center">
					<button type="button" onclick="switchPage(2, 1)" class="btn btn-default btn-round"><?= lang('buttons.back') ?></button>
					&nbsp;
					&nbsp;
					<button type="submit" class="btn btn-<?= THEME_COLOR; ?> btn-round"><?= lang('buttons.register') ?></button>
				</div>
			</div>
		</form>

	<?php endif ?>
</div>

<script>
	let form = $('#formUser');

	let firstPage = $('.form-1st-page');
	let secondPage = $('.form-2nd-page');

	let phoneNumber = $('#phone');

	let newPassword = $("[name='user\\[password\\]']");
	let newPasswordConf = $("[name='user\\[passwordConf\\]']");

	let pwdCheckLength = $('#pwdCheckLength');
	let pwdCheckUpper = $('#pwdCheckUpper');
	let pwdCheckLower = $('#pwdCheckLower');
	let pwdCheckNumber = $('#pwdCheckNumber');

	// Set variables
	let lowerCase   		= /[a-z]/g;
	let upperCase   		= /[A-Z]/g;
	let numbers     		= /[0-9]/g;
	// let specialCharacter    = /[!,%,&,@,#,$,^,*,?,_,~]/g;

	$('input').on('change keyup', function (){
		$(this).removeClass('is-invalid');
		$(this).parent().find('.invalid-feedback').addClass('d-none');
	});

	/**
	 * Switch the current displayed page
	 */
	function switchPage(current, next)
	{
		if (current < next)
		{
			var validation = validateInputs(firstPage);
			var isValid = displayErrors(validation);

			if (isValid)
			{
				firstPage.toggle();secondPage.toggle();
			}
		}
		else
		{
			firstPage.toggle();secondPage.toggle();
		}
	}

	/**
	 * Verify that a condition is fulfilled and change corresponding text classes
	 * @param text Security requirement explanation text
	 * @param condition Condition to verify (bool)
	 */
	function checkPwdSecurity(text,condition)
	{
		if (condition)
		{
			text.addClass('text-success font-bold');
		}
		else {
			text.removeClass('text-success font-bold');
		}
	}

	newPassword.on('keyup change', function (){
		let pwd = $(this).val();
		checkPwdSecurity(pwdCheckLower,(pwd.match(lowerCase) != null));
		checkPwdSecurity(pwdCheckUpper,(pwd.match(upperCase) != null));
		checkPwdSecurity(pwdCheckNumber,(pwd.match(numbers) != null));
		checkPwdSecurity(pwdCheckLength,(pwd.length >= 7));
	});

	$(function () {
		form.on('submit',
			function(e) {

				if (newPassword.val().length) {
					let pwd = newPassword.val();
					let pwdConf = newPasswordConf.val();

					let pwdValid = true;

					if (
						!(pwd.match(lowerCase) != null)
						|| !(pwd.match(upperCase) != null)
						|| !(pwd.match(numbers) != null)
						|| !(pwd.length >= 7)
					) {
						alert('<?= lang('emails.steps.3.pwdNotSecure') ?>')
						e.preventDefault();
						pwdValid = false;
					}

					if (pwd !== pwdConf)
					{
						alert('<?= lang('users.register.pwdNotMatching') ?>')
						e.preventDefault();
						pwdValid = false;
					}

					if (pwdValid)
					{
						if (verifyPhone(phoneNumber.val(),true)) {
							newPassword.val(sha256(pwd));
							newPasswordConf.val(sha256(pwdConf));
						} else {
							alert("<?= lang('users.register.phoneNotValid') ?>");
							e.preventDefault();
						}
					}
				}
			}
		);
	});


</script>
