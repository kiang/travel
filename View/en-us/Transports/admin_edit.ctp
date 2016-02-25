<?php echo $this->Form->create('Transport', array('type' => 'file')); ?>
<div class="editForm"><?php echo $this->Html->link(' ', array('action' => 'form', $id)); ?></div>
<?php echo $this->Form->end('Submit'); ?>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.editForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');