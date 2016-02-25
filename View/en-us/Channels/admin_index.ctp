<div class="channels index">
    <h2><?php echo __('Channels'); ?></h2>
    <div class="btn-group">
        <?php echo $this->Html->link('<i class="icon-plus"></i> 新增', array('action' => 'add'), array('class' => 'btn', 'escape' => false)); ?>
        <?php echo $this->Html->link('<i class="icon-plus"></i> RSS', array('action' => 'rss'), array('class' => 'btn', 'escape' => false)); ?>
        <?php echo $this->Html->link('<i class="icon-plus"></i> 來源', array('controller' => 'feeds'), array('class' => 'btn', 'escape' => false)); ?>
    </div>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?>	</p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ChannelsAdminListTable">
        <tr>
            <th><?php echo $this->Paginator->sort('Channel.title', '標題'); ?></th>
            <th class="span4"><?php echo $this->Paginator->sort('Channel.summary', '摘要'); ?></th>
            <th><?php echo $this->Paginator->sort('Channel.the_date', '日期'); ?></th>
            <th><?php echo $this->Paginator->sort('Channel.created', '建立時間'); ?></th>
            <th class="actions">操作</th>
        </tr>
        <?php foreach ($channels as $channel): ?>
            <tr>
                <td><?php echo $this->Html->link($channel['Channel']['title'], $channel['Channel']['url'], array('target' => '_blank')); ?>&nbsp;</td>
                <td><?php echo $channel['Channel']['summary']; ?>&nbsp;</td>
                <td><?php echo $channel['Channel']['the_date']; ?>&nbsp;</td>
                <td><?php echo $channel['Channel']['created']; ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link('編輯', array('action' => 'edit', $channel['Channel']['id'])); ?>
                    <?php echo $this->Form->postLink('刪除', array('action' => 'delete', $channel['Channel']['id']), null, __('Are you sure you want to delete # %s?', $channel['Channel']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>