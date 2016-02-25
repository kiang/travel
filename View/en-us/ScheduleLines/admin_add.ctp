<?php
$url = array();
if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array('action' => 'add', $foreignModel, $foreignId);
} else {
    $url = array('action' => 'add');
    $foreignModel = '';
}
echo $this->Form->create('ScheduleLine', array('type' => 'file', 'url' => $url));
?>
<div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'form', 0, $foreignModel)); ?></div>

<?php echo $this->Form->end('Submit'); ?>
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