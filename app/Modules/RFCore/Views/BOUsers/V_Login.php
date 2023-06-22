<?php
/**
 * @var $errors array
 * @var $action string
 */
?>
<div class="row h-100 justify-content-center">
    <article class="card-body">
        <h4 class="card-title mb-4 mt-1">Connexion</h4>
        <form id="formLogin" action="<?php echo base_url($action); ?>" method="post">
            <div class="form-group">
                <label>Adresse Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i
                                        class="fa fa-user text-white"></i> </span>
                    </div>
                    <input name="user[email]" class="<?php if (isset($errors['user.email'])) echo "is-invalid";?> form-control" placeholder="Adresse email" type="text"
                           value="<?php echo set_value('user[email]'); ?>">
                    <?php if (isset($errors['user.email'])) echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['user.email']."</div>"; ?>
                </div>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i
                                        class="fa fa-lock text-white"></i> </span>
                    </div>
                    <input name="user[password]" class="<?php if (isset($errors['user.password'])) echo "is-invalid";?> form-control" placeholder="******" type="password">
                    <?php if (isset($errors['user.password'])) {
	                    	echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['user.password']."</div>";
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Connexion</button>
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
</script>

