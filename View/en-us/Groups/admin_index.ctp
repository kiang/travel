<div class="groups index">
    <h2>群組管理</h2>
    <div class="btn-group"><?php
if ($parentId > 0) {
    echo $this->Html->link('上一層', array('action' => 'index', $upperLevelId), array('class' => 'btn'));
}
echo $this->Html->link('新增', array('action' => 'add', $parentId), array('class' => 'btn'));
echo $this->Html->link('Members', array('controller' => 'members'), array('class' => 'btn'));
?></div>
    <p>
        <?php echo $this->Paginator->counter(array('format' => '第 %page% 頁 / 共 %pages% 頁')); ?>
    </p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id', '編號'); ?></th>
                <th><?php echo $this->Paginator->sort('name', '名稱'); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($groups as $group):
            $class = null;
            if ($i++ % 2 == 1) {
                $class = ' class="even"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td>
                    <?php echo $group['Group']['id']; ?>
                </td>
                <td>
                    <?php echo $group['Group']['name']; ?>
                </td>
                <td class="actions">
                    <div class="btn-group"></div>
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $group['Group']['id']), array('class' => 'btn')); ?>
                    <?php echo $this->Html->link('Delete', array('action' => 'delete', $group['Group']['id']), array('class' => 'btn'), 'Are you sure you want to delete this?'); ?>
                    <?php echo $this->Html->link('子群組', array('action' => 'index', $group['Group']['id']), array('class' => 'btn')); ?>
                    <?php echo $this->Html->link('設定權限', array('action' => 'acos', $group['Group']['id']), array('class' => 'btn')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="paging"><?php echo $this->element('paginator'); ?></div>