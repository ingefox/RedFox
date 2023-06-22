<form id="formUpdatePwd" accept-charset="utf-8">
    <input type='hidden' id='reNewPwdId' name='reNewPwdId' value='<?PHP echo $userId;?>'>
    <input type='hidden' id='reNewPwdToken' name='reNewPwdToken' value='<?PHP echo $token;?>'>
    
    <div class="form-group">
        <label>Mot de passe</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fa fa-lock text-white"></i> </div>
            </div>
            <input id="reNewUserPassword" name="reNewUserPassword" class="<?php if (isset($errors['userPassword']))echo "is-invalid";?> form-control" placeholder="******" type="password">
            <?php if (isset($errors['reNewUserPassword'])) echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['reNewUserPassword']."</div>"; ?>
        </div>
    </div>

    <div class="form-group">
        <label>Confirmer le mot de passe</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fas fa-unlock text-white"></i> </div>
            </div>
            <input id="reNewUserPasswordConfirm" name="userPass_confirm" class="<?php if (isset($errors['userPass_confirm']))echo "is-invalid";?> form-control" placeholder="******" type="password">
            <?php if (isset($errors['reNewUserPassword'])) echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['reNewUserPassword']."</div>"; ?>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Mise à jour du mot de passe</button>
    </div>

    <?php if (isset($alert) && isset($type)) echo "<div class=\"alert alert-" . $type . "\" role=\"alert\">".$alert."</div>";?>
</form>
<script>
    var passwordField = $("#reNewUserPassword");
    var pass_confField = $("#reNewUserPasswordConfirm");
    var form = $('#formUpdatePwd');

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
            loadHTML('<?php if(isset($action))echo base_url($action)?>', form.serialize(), form.parent());
            e.preventDefault();
        });
    });
</script>