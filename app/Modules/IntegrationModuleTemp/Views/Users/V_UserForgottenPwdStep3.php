<?php
/**
 * @var string $action
 * @var string $token
 */
?>

<article class="card-body" style="padding: 0">
	<h4 class="card-title mb-4 mt-1">Réinitialisation de mot de passe</h4>
	<form id="formPwdReset" action="<?php echo site_url($action); ?>" method="post">

		<input type="hidden" name="pwdReset[token]" value="<?php echo $token;?>">
		<input type="hidden" name="step" value="4">

		<div class="form-group">
			<label>Nouveau mot de passe</label>
			<div class="input-group">
				<div class="input-group-prepend">
                            <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i
									class="fa fa-lock text-white"></i> </span>
				</div>
				<input name="pwdReset[password]" class="<?php if (isset($errors['pwdReset.password'])) echo "is-invalid";?> form-control" placeholder="******" type="password">
				<?php if (isset($errors['pwdReset.password'])) {
					echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['pwdReset.password']."</div>";
				} ?>
			</div>
		</div>

		<div class="form-group">
			<label>Confirmation</label>
			<div class="input-group">
				<div class="input-group-prepend">
                            <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i
									class="fa fa-lock text-white"></i> </span>
				</div>
				<input name="pwdReset[passwordConf]" class="<?php if (isset($errors['pwdReset.passwordConf'])) echo "is-invalid";?> form-control" placeholder="******" type="password">
				<?php if (isset($errors['pwdReset.passwordConf'])) {
					echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['pwdReset.passwordConf']."</div>";
				} ?>
			</div>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Modifier</button>
		</div>

	</form>

	<?php
	if (isset($alert) && isset($type)) {
		$alertDisp = "<div class=\"alert alert-" . $type . "\" role=\"alert\">";
		$alertDisp .= $alert;
		$alertDisp .= "</div>";
		echo $alertDisp;
	}
	?>
</article>

<script>
	$('#formPwdReset').submit(
		function(){
			var newPassword = $("[name='pwdReset\\[password\\]']");

			if(newPassword.val().length)
			{
				let pass = newPassword.val();
				newPassword.val(sha256(pass));
			}
			return true;
		});
</script>
