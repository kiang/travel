<div class="ScheduleDays view">
    <h2>檢視單日行程</h2>
    <div class="span-12">
        <div class="span-2">行程</div>
        <div class="span-9">&nbsp;<?php
if (empty($this->request->data['Schedule']['id'])) {
    echo '--';
} else {
    echo $this->Html->link($this->request->data['Schedule']['id'], array(
        'controller' => 'schedules',
        'action' => 'view',
        $this->request->data['Schedule']['id']
    ));
}
?></div>
        <div class="span-2">旅館</div>
        <div class="span-9">&nbsp;<?php
            if (empty($this->request->data['Point']['id'])) {
                echo '--';
            } else {
                echo $this->Html->link($this->request->data['Point']['id'], array(
                    'controller' => 'points',
                    'action' => 'view',
                    $this->request->data['Point']['id']
                ));
            }
?></div>

        <div class="span-2">名稱</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['ScheduleDay']['title']) {
                echo $this->request->data['ScheduleDay']['title'];
            }
?>&nbsp;
        </div>
        <div class="span-2">行程數量</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['ScheduleDay']['count_lines']) {
                echo $this->request->data['ScheduleDay']['count_lines'];
            }
?>&nbsp;
        </div>
        <div class="span-2">備註</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['ScheduleDay']['note']) {
                echo nl2br($this->request->data['ScheduleDay']['note']);
            }
?>&nbsp;
        </div>
        <div class="span-2">住宿地點</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['ScheduleDay']['point_name']) {
                echo $this->request->data['ScheduleDay']['point_name'];
            }
?>&nbsp;
        </div>
    </div><div class="span-12 last">&nbsp;</div>
</div>
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('刪除', array('action' => 'delete', $this->Form->value('ScheduleDay.id')), null, '確定要刪除？'); ?></li>
        <li><?php echo $this->Html->link('單日行程列表', array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link('檢視相關行程細節', array('controller' => 'schedule_lines', 'action' => 'index', 'ScheduleDay', $this->request->data['ScheduleDay']['id']), array('class' => 'viewItem')); ?></li>
    </ul>
</div>
<div id="ScheduleDaysrelationPanel"></div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'a.viewItem\').click(function() {
        $(\'#ScheduleDaysrelationPanel\').load(this.href);
        return false;
    });
});
');
?>
