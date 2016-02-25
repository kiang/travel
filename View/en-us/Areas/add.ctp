<?php
if (!empty($areaControlMessage)) {
    echo $areaControlMessage;
} else {
    ?>
    <h3>Add another area</h3>
    <?php
    echo $this->Form->create('Area', array('url' => array('action' => 'add', $model, $foreignKey)));
    echo $this->Form->hidden($model . '.area_id', array('id' => $model . 'Area1'));
    ?>
    <div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'getForm', $model)); ?></div>
    <?php echo $this->Form->end('Submit'); ?>
    <script type="text/javascript">
        <!--
        $(function() {
            $('div.addForm a').each(function() {
                $(this).parent().load(this.href);
            });
            var submitted = false;
            $('form#AreaAddForm').submit(function() {
                if(false === submitted) {
                    submitted = true;
                    $('#areaControlMessage').hide();
                    $.post('<?php echo $this->Html->url(); ?>', $(this).serializeArray(), function(pageData) {
                        if(pageData == 'done') {
                            $('div#AreasGetList').load('<?php echo $this->Html->url(array('action' => 'getList', $model, $foreignKey)); ?>');
                        } else {
                            $('#areaControlMessage').html(pageData);
                            $('#areaControlMessage').show();
                            submitted = false;
                        }
                    });
                }
                return false;
            });
        });
        // -->
    </script><?php
}
?>