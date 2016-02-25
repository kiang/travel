<div class="feedItems form">
    <?php echo $this->Form->create('FeedItem'); ?>
    <fieldset>
        <legend><?php echo __('Admin Edit Feed Item'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('feed_id');
        echo $this->Form->input('url');
        echo $this->Form->input('title');
        echo $this->Form->input('summary');
        echo $this->Form->input('the_date');
        echo $this->Form->input('channel_id');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3>操作</h3>
    <ul>

        <li><?php echo $this->Form->postLink('Delete', array('action' => 'delete', $this->Form->value('FeedItem.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('FeedItem.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Feed Items'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Feeds'), array('controller' => 'feeds', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Feed'), array('controller' => 'feeds', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Channels'), array('controller' => 'channels', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Channel'), array('controller' => 'channels', 'action' => 'add')); ?> </li>
    </ul>
</div>
