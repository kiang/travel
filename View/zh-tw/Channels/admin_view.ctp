<div class="channels view">
    <h2><?php echo __('Channel'); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Member'); ?></dt>
        <dd>
            <?php echo $this->Html->link($channel['Member']['username'], array('controller' => 'members', 'action' => 'view', $channel['Member']['id'])); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Url'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['url']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Title'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['title']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Summary'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['summary']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('The Date'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['the_date']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created'); ?></dt>
        <dd>
            <?php echo h($channel['Channel']['created']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3>操作</h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Channel'), array('action' => 'edit', $channel['Channel']['id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Channel'), array('action' => 'delete', $channel['Channel']['id']), null, __('Are you sure you want to delete # %s?', $channel['Channel']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Channels'), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Channel'), array('action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Channel Links'), array('controller' => 'channel_links', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Channel Link'), array('controller' => 'channel_links', 'action' => 'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php echo __('Related Channel Links'); ?></h3>
    <?php if (!empty($channel['ChannelLink'])): ?>
        <table cellpadding = "0" cellspacing = "0">
            <tr>
                <th><?php echo __('Id'); ?></th>
                <th><?php echo __('Channel Id'); ?></th>
                <th><?php echo __('Model'); ?></th>
                <th><?php echo __('Foreign Key'); ?></th>
                <th><?php echo __('Foreign Title'); ?></th>
                <th class="actions">操作</th>
            </tr>
            <?php
            $i = 0;
            foreach ($channel['ChannelLink'] as $channelLink):
                ?>
                <tr>
                    <td><?php echo $channelLink['id']; ?></td>
                    <td><?php echo $channelLink['channel_id']; ?></td>
                    <td><?php echo $channelLink['model']; ?></td>
                    <td><?php echo $channelLink['foreign_key']; ?></td>
                    <td><?php echo $channelLink['foreign_title']; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), array('controller' => 'channel_links', 'action' => 'view', $channelLink['id'])); ?>
                        <?php echo $this->Html->link('編輯', array('controller' => 'channel_links', 'action' => 'edit', $channelLink['id'])); ?>
        <?php echo $this->Form->postLink('刪除', array('controller' => 'channel_links', 'action' => 'delete', $channelLink['id']), null, __('Are you sure you want to delete # %s?', $channelLink['id'])); ?>
                    </td>
                </tr>
        <?php endforeach; ?>
        </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Channel Link'), array('controller' => 'channel_links', 'action' => 'add')); ?> </li>
        </ul>
    </div>
</div>
