<?php
echo $this->Form->create('Point', array('url' => array('action' => 'loop', $id)));
?>
<div class="editForm"><?php echo $this->Html->link(' ', array('action' => 'form', $id)); ?></div>
<?php
echo $this->Form->end('Submit');
echo $this->Html->link('Delete', array('action' => 'delete', $id), array('class' => 'btn'));
?>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.editForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');