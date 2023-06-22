<?php
/**
 * @var array $errors
 * @var array $roles_cb
 * @var E_BOUser $user
 */

use RFCore\Entities\E_BOUser;

?>

<form id="formEditBOUser" accept-charset="utf-8">
    <input name="id" type="hidden" value="<?php echo set_value('id'); ?>">
        <div class="form-group">
            <label>Adresse email</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fa fa-user text-white"></i> </div>
                </div>
                <input name="email" class="<?php if (isset($errors['email']))echo "is-invalid";?> form-control" placeholder="Adresse email" type="email" value="<?php echo (isset($user)) ? $user->getEmail():set_value('email'); ?>">
                <?php if (isset($errors['email']))echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['email']."</div>"; ?>
            </div>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fa fa-lock text-white"></i> </div>
                </div>
                <input name="password" class="<?php if (isset($errors['password']))echo "is-invalid";?> form-control" placeholder="******" type="password">
                <?php if (isset($errors['password'])) echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['password']."</div>"; ?>
            </div>

        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fas fa-unlock text-white"></i> </div>
                </div>
                <input name="pass_confirm" class="<?php if (isset($errors['pass_confirm']))echo "is-invalid";?> form-control" placeholder="******" type="password">
                <?php if (isset($errors['pass_confirm']))echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['pass_confirm']."</div>"; ?>
            </div>

        </div>
        <input name="submitted" type="hidden" value="true">

    <?php if (isset($alert) && isset($type)) echo "<div class=\"alert alert-" . $type . "\" role=\"alert\">".$alert."</div>";?>

    <div class="form-group">
        <button type="submit" class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Modifier</button>
    </div>
</form>

<script>
    var passwordField = $("[name='password']");
    var pass_confField = $("[name='pass_confirm']");
    var form = $('#formEditBOUser');

    $(function () {
        form.on('submit',function (e) {
            if(passwordField.val()!=="")
            {
                let pass = passwordField.val();
                passwordField.val(sha256(pass));
            }
            if(pass_confField.val()!=="")
            {
                let pass_conf = pass_confField.val();
                pass_confField.val(sha256(pass_conf));
            }
            loadHTML('<?php echo base_url('RF-BackOffice/BOUsers/EditUserBO')?>', form.serialize(), form.parent());
            e.preventDefault();
        });
    });
</script>
