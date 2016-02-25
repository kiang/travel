<div id="ScheduleLinesAdminControlPage">
    <h2>行程細節管理</h2>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ScheduleLinesAdminListTable">
        <thead>
            <tr>
                <?php if (empty($scope['ScheduleLine.schedule_day_id'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleLine.schedule_day_id', '單日行程', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['ScheduleLine.foreign_key'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleLine.foreign_key', 'Points', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['ScheduleLine.activity_id'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleLine.activity_id', '活動', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['ScheduleLine.transport_id'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleLine.transport_id', '交通方式', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('ScheduleLine.transport_name', '交通方式', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleLine.point_name', '地點名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleLine.activity_name', '活動', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleLine.sort', '排序', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleLine.time', '時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleLine.note', '備註', array('url' => $url)); ?></th>
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
                    <?php if (empty($scope['ScheduleLine.schedule_day_id'])): ?>
                        <td><?php
                if (empty($item['ScheduleDay']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['ScheduleDay']['id'], array(
                        'controller' => 'schedule_days',
                        'action' => 'view',
                        $item['ScheduleDay']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>
                    <td><?php
                if (empty($item['Point']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Point']['id'], array(
                        'controller' => 'points',
                        'action' => 'view',
                        $item['Point']['id']
                    ));
                }
                    ?></td>
                    <?php if (empty($scope['ScheduleLine.activity_id'])): ?>
                        <td><?php
                if (empty($item['Activity']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Activity']['id'], array(
                        'controller' => 'activities',
                        'action' => 'view',
                        $item['Activity']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>
                    <?php if (empty($scope['ScheduleLine.transport_id'])): ?>
                        <td><?php
                if (empty($item['Transport']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Transport']['id'], array(
                        'controller' => 'transports',
                        'action' => 'view',
                        $item['Transport']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>

                    <td><?php
                if ($item['ScheduleLine']['transport_name']) {
                    echo $item['ScheduleLine']['transport_name'];
                }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleLine']['point_name']) {
                        echo $item['ScheduleLine']['point_name'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleLine']['activity_name']) {
                        echo $item['ScheduleLine']['activity_name'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleLine']['sort']) {
                        echo $item['ScheduleLine']['sort'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleLine']['time']) {
                        echo $item['ScheduleLine']['time'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleLine']['note']) {
                        echo nl2br($item['ScheduleLine']['note']);
                    }
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('Edit', array('action' => 'edit', $item['ScheduleLine']['id']), array('class' => 'control')); ?>
                        <?php echo $this->Html->link('Delete', array('action' => 'delete', $item['ScheduleLine']['id']), null, 'Are you sure you want to delete this?'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div class="actions">
        <ul class="list1">
            <?php $url = array_merge($url, array('action' => 'add')); ?>
            <li><?php echo $this->Html->link('新增', $url, array('class' => 'control')); ?></li>
        </ul>
    </div>
    <?php
    $scripts = '
$(function() {
    $(\'#ScheduleLinesAdminListTable th a, #ScheduleLinesAdminControlPage div.paging a\').click(function() {
        $(\'#ScheduleLinesAdminControlPage\').load(this.href);
        return false;
    });
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>