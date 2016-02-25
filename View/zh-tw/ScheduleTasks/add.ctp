<?php
$url = array('action' => 'add');
echo $this->Form->create('ScheduleTask', array('type' => 'file', 'url' => $url));
?>
<div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'form')); ?></div>

<?php echo $this->Form->end('送出'); ?>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('列表', array('action' => 'index')); ?></li>
    </ul>
</div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'div.addForm a\').each(function() {
        $(this).parent().load(this.href);
    });
});
');