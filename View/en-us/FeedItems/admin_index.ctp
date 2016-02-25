<div class="feedItems index">
    <h2><?php echo __('Feed Items'); ?></h2>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?>	</p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('feed_id'); ?></th>
            <th><?php echo $this->Paginator->sort('url'); ?></th>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('summary'); ?></th>
            <th><?php echo $this->Paginator->sort('the_date'); ?></th>
            <th><?php echo $this->Paginator->sort('channel_id'); ?></th>
            <th class="actions">操作</th>
        </tr>
        <?php foreach ($feedItems as $feedItem): ?>
            <tr>
                <td><?php echo h($feedItem['FeedItem']['id']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($feedItem['Feed']['title'], array('controller' => 'feeds', 'action' => 'view', $feedItem['Feed']['id'])); ?>
                </td>
                <td><?php echo h($feedItem['FeedItem']['url']); ?>&nbsp;</td>
                <td><?php echo h($feedItem['FeedItem']['title']); ?>&nbsp;</td>
                <td><?php echo h($feedItem['FeedItem']['summary']); ?>&nbsp;</td>
                <td><?php echo h($feedItem['FeedItem']['the_date']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($feedItem['Channel']['title'], array('controller' => 'channels', 'action' => 'view', $feedItem['Channel']['id'])); ?>
                </td>
                <td class="actions">
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $feedItem['FeedItem']['id'])); ?>
                    <?php echo $this->Form->postLink('Delete', array('action' => 'delete', $feedItem['FeedItem']['id']), null, __('Are you sure you want to delete # %s?', $feedItem['FeedItem']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>