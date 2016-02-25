<div id="ScheduleDaysAdminControlPage">
    <h2>單日行程管理</h2>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ScheduleDaysAdminListTable">
        <thead>
            <tr>
                <?php if (empty($scope['ScheduleDay.schedule_id'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleDay.schedule_id', '行程', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['ScheduleDay.point_id'])): ?>
                    <th><?php echo $this->Paginator->sort('ScheduleDay.point_id', '旅館', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('ScheduleDay.title', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleDay.count_lines', '行程數量', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleDay.note', '備註', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleDay.point_name', '住宿地點', array('url' => $url)); ?></th>
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
                    <?php if (empty($scope['ScheduleDay.schedule_id'])): ?>
                        <td><?php
                if (empty($item['Schedule']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Schedule']['id'], array(
                        'controller' => 'schedules',
                        'action' => 'view',
                        $item['Schedule']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>
                    <?php if (empty($scope['ScheduleDay.point_id'])): ?>
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
                    <?php endif; ?>

                    <td><?php
                if ($item['ScheduleDay']['title']) {
                    echo $item['ScheduleDay']['title'];
                }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleDay']['count_lines']) {
                        echo $item['ScheduleDay']['count_lines'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleDay']['note']) {
                        echo nl2br($item['ScheduleDay']['note']);
                    }
                    ?></td>
                    <td><?php
                    if ($item['ScheduleDay']['point_name']) {
                        echo $item['ScheduleDay']['point_name'];
                    }
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('檢視', array('action' => 'view', $item['ScheduleDay']['id']), array('class' => 'control')); ?>
                        <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['ScheduleDay']['id']), array('class' => 'control')); ?>
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['ScheduleDay']['id']), null, '確定要刪除？'); ?>
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
    $(\'#ScheduleDaysAdminListTable th a, #ScheduleDaysAdminControlPage div.paging a\').click(function() {
        $(\'#ScheduleDaysAdminControlPage\').load(this.href);
        return false;
    });
';
    $scripts .= '});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>