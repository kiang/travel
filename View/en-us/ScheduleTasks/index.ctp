<div id="ScheduleTasksControlPage">
    <h2>待轉行程列表</h2>
    <p>
        <?php
        $url = array();
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table cellpadding="0" cellspacing="0" id="ScheduleTasksListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('ScheduleTask.title', 'Subject', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.url', 'Url', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.creator', '建立人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.created', 'Created', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($items as $item):
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' class="even"';
                }
                ?>
                <tr<?php echo $class; ?>>
                    <td><?php echo $item['ScheduleTask']['title']; ?></td>
                    <td><?php echo $this->Html->link($item['ScheduleTask']['url'], $item['ScheduleTask']['url'], array('target' => '_blank')); ?></td>
                    <td><?php echo $item['Creator']['username']; ?></td>
                    <td><?php echo $item['ScheduleTask']['created']; ?></td>
                    <td class="actions"><?php
            if ($loginMember['id'] == $item['ScheduleTask']['creator'] && empty($item['ScheduleTask']['schedule_id'])) {
                echo $this->Html->link('Edit', array('action' => 'edit', $item['ScheduleTask']['id']), array(
                    'class' => 'ScheduleTasksControl'
                ));
                echo ' ' . $this->Html->link('Delete', array('action' => 'delete', $item['ScheduleTask']['id']), null, 'Are you sure you want to delete this?'
                );
            }
            if (empty($item['ScheduleTask']['schedule_id'])) {
                echo ' ' . $this->Html->link('建立行程', array(
                    'controller' => 'schedules', 'action' => 'add', $item['ScheduleTask']['id']
                        ), array('target' => '_blank'));
            } else {
                echo $this->Html->link('檢視行程', array('controller' => 'schedules', 'action' => 'view',
                    $item['ScheduleTask']['schedule_id']), array('target' => '_blank')
                );
            }
                ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div class="actions">
        <ul class="list1">
            <li><?php echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'ScheduleTasksControl')); ?></li>
        </ul>
    </div>
    <div id="ScheduleTasksControlPanel"></div>
    <?php
    $scripts = '
$(function() {
    $(\'#ScheduleTasksListTable th a, div.paging a, a.ScheduleTasksPageControl\').click(function() {
        $(\'#ScheduleTasksControlPage\').load(this.href);
        return false;
    });
    $(\'a.ScheduleTasksControl\').click(function() {
        dialogFull(this);
        return false;
    });
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>