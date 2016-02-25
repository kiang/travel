<div id="RanksAdminControlPage">
    <h2>評分管理</h2>
    <p>
        <?php
        $url = array();
        if (!empty($foreignId) && !empty($foreignModel)) {
            $url = array($foreignModel, $foreignId);
        }
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="RanksAdminListTable">
        <thead>
            <tr>
                <?php if (empty($scope['Rank.member_id'])): ?>
                    <th><?php echo $this->Paginator->sort('Rank.member_id', 'Members', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('Rank.model', '關聯Model', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Rank.foreign_key', '關聯鍵', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Rank.rank', '分數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Rank.created', 'Created', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($items as $item):
                $class = null;
                if ($i++ % 2 == 1) {
                    $class = ' class="even"';
                }
                ?>
                <tr<?php echo $class; ?>>
                    <?php if (empty($scope['Rank.member_id'])): ?>
                        <td><?php
                if (empty($item['Member']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Member']['id'], array(
                        'controller' => 'members',
                        'action' => 'view',
                        $item['Member']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>

                    <td><?php
                if ($item['Rank']['model']) {
                    echo $item['Rank']['model'];
                }
                    ?></td>
                    <td><?php
                    if ($item['Rank']['foreign_key']) {
                        echo $item['Rank']['foreign_key'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['Rank']['rank']) {
                        echo $item['Rank']['rank'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['Rank']['created']) {
                        echo $item['Rank']['created'];
                    }
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('Delete', array('action' => 'delete', $item['Rank']['id']), null, 'Are you sure you want to delete this?'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <?php
    $scripts = '
$(function() {
    $(\'#RanksAdminListTable th a, #RanksAdminControlPage div.paging a\').click(function() {
        $(\'#RanksAdminControlPage\').load(this.href);
        return false;
    });
';
    $scripts .= '});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>