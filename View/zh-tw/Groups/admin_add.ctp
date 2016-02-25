<div class="groups form">
    <?php echo $this->Form->create('Group', array('url' => array($parentId))); ?>
    <fieldset>
        <legend>新增群組</legend>
        <?php
        echo $this->Form->input('name', array('label' => '名稱：'));
        ?>
    </fieldset>
    <?php echo $this->Form->end('送出'); ?>
</div>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link(__('List'), array('action' => 'index')); ?></li>
    </ul>
</div>
