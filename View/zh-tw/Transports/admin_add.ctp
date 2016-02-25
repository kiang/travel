<?php echo $this->Form->create('Transport', array('type' => 'file')); ?>
<div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'form')); ?></div>
<?php echo $this->Form->end('送出'); ?>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.addForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');