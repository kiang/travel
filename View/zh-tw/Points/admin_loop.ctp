<?php
echo $this->Form->create('Point', array('url' => array('action' => 'loop', $id)));
?>
<div class="editForm"><?php echo $this->Html->link(' ', array('action' => 'form', $id)); ?></div>
<?php
echo $this->Form->end('送出');
echo $this->Html->link('刪除', array('action' => 'delete', $id), array('class' => 'btn'));
?>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.editForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');