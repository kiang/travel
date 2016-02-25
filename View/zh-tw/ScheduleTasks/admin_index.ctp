<div id="ScheduleTasksAdminControlPage">
    <h2>待轉行程管理</h2><hr />
    <?php
    if (!isset($url)) {
        $url = array();
    }
    ?>
    <div class="span-6"><?php
    echo $this->Paginator->counter(array('format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'));
    ?></div>
    <div class="span-18 last">
        <?php
        echo $this->Form->create('ScheduleTask', array('type' => 'get', 'url' => array_merge($url, array('action' => 'index'))));
        echo $this->Form->text('keyword', array('class' => 'span-10', 'value' => $keyword));
        echo $this->Form->submit('查詢', array('div' => false));
        echo $this->Form->end();
        ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ScheduleTasksAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('ScheduleTask.schedule_id', '行程', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.url', '網址', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.title', '標題', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.creator', '建立人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.dealer', '處理人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('ScheduleTask.dealt', '處理時間', array('url' => $url)); ?></th>
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
                    <td><?php echo $item['ScheduleTask']['schedule_id']; ?></td>
                    <td><?php echo $item['ScheduleTask']['url']; ?></td>
                    <td><?php echo $item['ScheduleTask']['title']; ?></td>
                    <td><?php echo $item['ScheduleTask']['creator']; ?></td>
                    <td><?php echo $item['ScheduleTask']['dealer']; ?></td>
                    <td><?php echo $item['ScheduleTask']['created']; ?></td>
                    <td><?php echo $item['ScheduleTask']['dealt']; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['ScheduleTask']['id']), array('class' => 'ScheduleTasksAdminControl')); ?>
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['ScheduleTask']['id']), null, '確定要刪除？'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div class="actions">
        <ul class="list1">
            <?php $url = array_merge($url, array('action' => 'add')); ?>
            <li><?php echo $this->Html->link('新增', $url, array('class' => 'ScheduleTasksAdminControl')); ?></li>
        </ul>
    </div>
    <div id="ScheduleTasksAdminControlPanel"></div>
    <?php
    $scripts = '
$(function() {
    $(\'#ScheduleTasksAdminListTable th a, #ScheduleTasksAdminControlPage div.paging a\').click(function() {
        $(\'#ScheduleTasksAdminControlPage\').load(this.href);
        return false;
    });
    $(\'a.ScheduleTasksAdminControl\').click(function() {
        dialogFull(this);
        return false;
    });
';
    $scripts .= '});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>