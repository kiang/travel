<div class="scheduleNotes index">
    <h2>行程記事</h2>
    <p><?php
echo $this->Paginator->counter(array(
    'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
));
?></p> 
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('ScheduleNote.schedule_id', '行程'); ?></th>
            <th><?php echo $this->Paginator->sort('ScheduleNote.schedule_day_id', '天'); ?></th>
            <th><?php echo $this->Paginator->sort('ScheduleNote.schedule_line_id', '行程列'); ?></th>
            <th><?php echo $this->Paginator->sort('ScheduleNote.title', '標題'); ?></th>
            <th><?php echo $this->Paginator->sort('ScheduleNote.body', '內容'); ?></th>
            <th class="actions">操作</th>
        </tr>
        <?php foreach ($scheduleNotes as $scheduleNote): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link($scheduleNote['Schedule']['title'], '/schedules/view/' . $scheduleNote['Schedule']['id'], array('target' => '_blank')); ?>
                </td>
                <td>
                    <?php echo $this->Html->link('第' . $scheduleNote['ScheduleDay']['sort'] . '天', '/schedules/view/' . $scheduleNote['Schedule']['id'] . '/' . $scheduleNote['ScheduleDay']['id'], array('target' => '_blank')); ?>
                </td>
                <td>
                    <?php echo $scheduleNote['ScheduleLine']['point_name']; ?>
                </td>
                <td><?php echo h($scheduleNote['ScheduleNote']['title']); ?>&nbsp;</td>
                <td><?php echo h($scheduleNote['ScheduleNote']['body']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Form->postLink('刪除', array('action' => 'delete', $scheduleNote['ScheduleNote']['id']), null, '確定要刪除？'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>

    <div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>