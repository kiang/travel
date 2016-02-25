<div class="oauths index">
    <h2>外部認證</h2>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('member_id'); ?></th>
            <th><?php echo $this->Paginator->sort('provider'); ?></th>
            <th><?php echo $this->Paginator->sort('uid'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($oauths as $oauth): ?>
            <tr>
                <td><?php echo h($oauth['Oauth']['id']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($oauth['Member']['username'], array('controller' => 'members', 'action' => 'view', $oauth['Member']['id'])); ?>
                </td>
                <td><?php echo h($oauth['Oauth']['provider']); ?>&nbsp;</td>
                <td><?php echo h($oauth['Oauth']['uid']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $oauth['Oauth']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $oauth['Oauth']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $oauth['Oauth']['id']), null, __('Are you sure you want to delete # %s?', $oauth['Oauth']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?>	</p>

    <div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>