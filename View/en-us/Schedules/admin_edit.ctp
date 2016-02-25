<?php echo $this->Form->create('Schedule', array('type' => 'file')); ?>
<div class="editForm"><?php echo $this->Html->link(' ', array('action' => 'form', $id)); ?></div>
<?php echo $this->Form->end('Submit'); ?>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('Delete', array('action' => 'delete', $id), null, 'Are you sure you want to delete this?'); ?></li>
        <li><?php echo $this->Html->link('列表', array('action' => 'index')); ?></li>
    </ul>
</div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.editForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');