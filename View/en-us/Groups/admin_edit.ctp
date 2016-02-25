<div class="groups form">
    <?php echo $this->Form->create('Group'); ?>
    <fieldset>
        <legend>編輯群組</legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name', array('label' => '名稱：'));
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('Delete', array('action' => 'delete', $this->Form->value('Group.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Group.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List'), array('action' => 'index', $this->Form->value('Group.parent_id'))); ?></li>
    </ul>
</div>
