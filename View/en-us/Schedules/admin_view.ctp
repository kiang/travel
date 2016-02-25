<div class="Schedules view">
    <h2>檢視行程</h2><hr />
    <div class="span-12">
        <div class="span-2">會員</div>
        <div class="span-9">&nbsp;<?php
if (empty($this->request->data['Member']['id'])) {
    echo '--';
} else {
    echo $this->Html->link($this->request->data['Member']['id'], array(
        'controller' => 'members',
        'action' => 'view',
        $this->request->data['Member']['id']
    ));
}
?></div>
        <div class="span-2">地點</div>
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
        <div class="span-2">行程名稱</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['title']) {
                echo $this->request->data['Schedule']['title'];
            }
?>&nbsp;
        </div>
        <div class="span-2">出發點</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['point_text']) {
                echo $this->request->data['Schedule']['point_text'];
            }
?>&nbsp;
        </div>
        <div class="span-2">會員名稱</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['member_name']) {
                echo $this->request->data['Schedule']['member_name'];
            }
?>&nbsp;
        </div>
        <div class="span-2">出發時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['time_start']) {
                echo $this->request->data['Schedule']['time_start'];
            }
?>&nbsp;
        </div>
        <div class="span-2">參與人數</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['count_joins']) {
                echo $this->request->data['Schedule']['count_joins'];
            }
?>&nbsp;
        </div>
        <div class="span-2">活動天數</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['count_days']) {
                echo $this->request->data['Schedule']['count_days'];
            }
?>&nbsp;
        </div>
        <div class="span-2">Created</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['created']) {
                echo $this->request->data['Schedule']['created'];
            }
?>&nbsp;
        </div>
        <div class="span-2">更新時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Schedule']['modified']) {
                echo $this->request->data['Schedule']['modified'];
            }
?>&nbsp;
        </div>
    </div><div class="span-12 last">&nbsp;</div>
</div>
<hr />
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('Delete', array('action' => 'delete', $this->Form->value('Schedule.id')), null, 'Are you sure you want to delete this?'); ?></li>
        <li><?php echo $this->Html->link('行程列表', array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link('檢視相關單日行程', array('controller' => 'schedule_days', 'action' => 'index', 'Schedule', $this->request->data['Schedule']['id']), array('class' => 'viewItem')); ?></li>
    </ul>
</div>
<div id="SchedulesrelationPanel"></div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'a.viewItem\').click(function() {
        $(\'#SchedulesrelationPanel\').load(this.href);
        return false;
    });
});
');