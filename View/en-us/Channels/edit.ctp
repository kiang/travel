<div class="channels form">
    <?php echo $this->Form->create('Channel'); ?>
    <fieldset>
        <legend><?php echo __('Edit Channel'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('member_id');
        echo $this->Form->input('url');
        echo $this->Form->input('title');
        echo $this->Form->input('summary');
        echo $this->Form->input('the_date');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3>操作</h3>
    <ul>

        <li><?php echo $this->Form->postLink('Delete', array('action' => 'delete', $this->Form->value('Channel.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Channel.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Channels'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Channel Links'), array('controller' => 'channel_links', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Channel Link'), array('controller' => 'channel_links', 'action' => 'add')); ?> </li>
    </ul>
</div>
