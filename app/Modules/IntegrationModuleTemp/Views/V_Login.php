<?php
/**
 * @var $errors array
 * @var $action string
 * @var $role int
 */

if (!isset($role))
{
	$role = ROLE_USER;
}

?>

<form id="formLogin" action="<?php echo site_url($action); ?>" method="post">

	<div class="col-12 col-md-12 col-sm-12 p-0">
		<div class="page-content">
			<div class="row justify-content-center">
				<div class="col-md-4 col-4 col-sm-4 p-0">

					<div class="row">
						<div class="col-12 p-0 m-0 text-center">
							<img src="<?= base_url(PROJECT_LOGO) ?>" alt="" class="hp-logo">
						</div>
					</div>

					<div class="login-title">
							<img src="<?= base_url('/public/img/icons/power-off-solid.svg') ?>" alt="">
							<span>Page de connexion</span>
					</div>

					<hr class="separator">

					<?php if (isset($alert) && isset($type)): ?>
						<div class="login-alert">
							<div class="col">
								<img src="<?= base_url('/public/img/alerts/error.svg') ?>" alt="Icône" width="40px" height="40px">
							</div>
							<div class="col">
								<?= $alert ?>
							</div>
						</div>
					<?php elseif (!empty($errors)): ?>
						<div class="login-alert">
							<div class="col">
								<img src="<?= base_url('/public/img/alerts/error.svg') ?>" alt="Icône" width="40px" height="40px">
							</div>
							<div class="col">
								<?= array_values($errors)[0] ?>
							</div>
						</div>
					<?php endif;?>

					<div class="form-group row">
						<label for="inputEmail" class="col-sm-4 col-form-label">Adresse email</label>
						<div class="col-sm-8">
							<input type="text" class="form-control <?php if (isset($errors['user.email'])) echo "is-invalid";?>" id="inputEmail" name="user[email]" value="<?php echo set_value('user[email]'); ?>">
							<?php if (isset($errors['user.email'])) : ?>
								<div class="invalid-feedback feedback-icon-no-text"><i class="fas fa-exclamation-circle"></i></div>
							<?php endif;?>
						</div>
					</div>

					<div class="form-group row mb-0">
						<label for="inputPassword" class="col-sm-4 col-form-label">Mot de passe</label>
						<div class="col-sm-8">
							<input type="password" class="form-control <?php if (isset($errors['user.password'])) echo "is-invalid";?>" id="inputPassword" name="user[password]">
							<?php if (isset($errors['user.password'])) : ?>
								<div class="invalid-feedback feedback-icon-no-text"><i class="fas fa-exclamation-circle"></i></div>
							<?php endif;?>
						</div>
					</div>

					<div class="form-group row forgotten-pwd-button justify-content-end">
						<a href="javascript:void" onclick="forgottenPwd()">Mot de passe oublié</a>
					</div>

					<div class="form-group row login-buttons justify-content-end">
							<a href="javascript:void" onclick="notRegisteredYet()" class="not-registered-btn">Pas encore inscrit ?</a>
							<button class="btn btn-accent">Connexion</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div hidden>
	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="rememberMe" name="user[rememberMe]" value="1">
		<label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
	</div>
</div>

<script>
	$('#formLogin').submit(
		function(){
			var userPassword = $("[name='user\\[password\\]']");

			if(userPassword.val().length)
			{
				let pass = userPassword.val();
				userPassword.val(sha256(pass));
			}
			return true;
		});

	function forgottenPwd() {
		openModal('<?php echo base_url("Users/forgottenPassword");?>', 'Mot de passe oublié', {'step':1});
	}

	function notRegisteredYet()
	{
		var role = <?= $role ?>;
		unavailableFeature()
		//window.location = "<?//= base_url() ?>//";
	}
</script>

