<div id="FeedsAdminControlPage">
    <h2>來源</h2>
    <div class="btn-group">
        <?php echo $this->Html->link('<i class="icon-home"></i> 頻道', array('controller' => 'channels'), array('class' => 'btn', 'escape' => false)); ?>
        <?php echo $this->Html->link('<i class="icon-plus"></i> 新增', array('action' => 'add'), array('class' => 'btn control', 'escape' => false)); ?>
    </div>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?>	</p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="FeedsAdminListTable">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('is_active'); ?></th>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('url'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th class="actions">操作</th>
        </tr>
        <?php foreach ($feeds as $feed): ?>
            <tr>
                <td><?php echo h($feed['Feed']['id']); ?>&nbsp;</td>
                <td><?php echo $feed['Feed']['is_active'] == 1 ? '啟用' : '停用'; ?>&nbsp;</td>
                <td><?php echo h($feed['Feed']['title']); ?>&nbsp;</td>
                <td><?php echo h($feed['Feed']['url']); ?>&nbsp;</td>
                <td><?php echo h($feed['Feed']['modified']); ?>&nbsp;</td>
                <td class="actions">
                    <div class="btn-group">
                        <?php echo $this->Html->link('Edit', array('action' => 'edit', $feed['Feed']['id']), array('class' => 'btn control')); ?>
                        <?php echo $this->Form->postLink('Delete', array('action' => 'delete', $feed['Feed']['id']), array('class' => 'btn'), __('Are you sure you want to delete # %s?', $feed['Feed']['id'])); ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <script type="text/javascript">
        $(function() {
            $('#FeedsAdminListTable th a, #FeedsAdminControlPage div.paging a').click(function() {
                $('#FeedsAdminControlPage').load(this.href);
                return false;
            });
            $('#FeedsAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>