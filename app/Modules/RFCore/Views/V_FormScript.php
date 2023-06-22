<script>
    
    var form = $('#<?php echo $formId ?? '';?>');

    $(function () {
        form.on('submit',function (e) {
            
           <?php 
                
                echo $scriptPhone['before'] ?? '';
                echo $action ?? '';
                echo $scriptPhone['after'] ?? '';
            ?>      
        });
    });

    <?php echo $formScript ?? ''; ?>
</script>