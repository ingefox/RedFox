<?php
/**
 * @var array $errors
 * @var array $roles_cb
 */
?>
<div class="row h-100 justify-content-center">
    <form id="formEditAPI" accept-charset="utf-8">
        <div class="modal-body">
            <input hidden name="id" value="<?php echo set_value('id'); ?>">
            <div class="form-group">
                <label>Clé</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fas fa-at text-white"></i> </span>
                    </div>
                    <input name="key" class="form-control" placeholder="Clé" type="text" value="<?php echo $key ?? set_value('key'); ?>">
                </div>
                <?php if (isset($errors['key']))echo "<div class=\"alert alert-danger form-alert\" role=\"alert\">".$errors['key']."</div>"; ?>
            </div>
            <div class="form-group">
                <label>Description</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fa fa-lock text-white"></i> </span>
                    </div>
                    <input name="description" class="form-control" placeholder="Description" type="text" value="<?php echo (set_value('description') == "null") ? '':set_value('description'); ?>">
                </div>
                <?php if (isset($errors['description'])) echo "<div class=\"alert alert-danger form-alert\" role=\"alert\">".$errors['description']."</div>"; ?>

            </div>
            <div class="form-group">
                <label>Valeur</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-<?php echo THEME_COLOR; ?>"> <i class="fas fa-unlock text-white"></i> </span>
                    </div>
                    <input name="value" class="form-control" placeholder="Valeur" type="text" value="<?php echo (set_value('value') == "null") ? '':set_value('value'); ?>">
                </div>
                <?php if (isset($errors['value']))echo "<div class=\"alert alert-danger form-alert\" role=\"alert\">".$errors['value']."</div>"; ?>

                <input name="submitted" type="hidden" value="true">
            </div>

        <?php if (isset($alert) && isset($type)) echo "<div class=\"alert alert-" . $type . "\" role=\"alert\">".$alert."</div>";?>

        <div class="form-group">
            <button type="submit" class="btn btn-<?php echo THEME_COLOR; ?> btn-block">Modifier</button>
        </div>
    </form>
</div>
<script>
    var form = $('#formEditAPI');
    $(function () {
        form.on('submit',function (e) {
            loadHTML('<?php echo base_url("RF-BackOffice/editAPI")?>', form.serialize(), form.parent());
            e.preventDefault();
        });
    });
</script>
