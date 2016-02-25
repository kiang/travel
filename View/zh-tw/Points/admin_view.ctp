<div class="Points view">
    <h2>檢視地點</h2><hr />
    <div class="span-12">

        <div class="span-2">名稱</div>
        <div class="span-9">&nbsp;<?php
if ($this->request->data['Point']['title']) {
    echo $this->request->data['Point']['title'];
}
?>&nbsp;
        </div>
        <div class="span-2">住址</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['address']) {
                echo $this->request->data['Point']['address'];
            }
?>&nbsp;
        </div>
        <div class="span-2">郵遞區號</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['postcode']) {
                echo $this->request->data['Point']['postcode'];
            }
?>&nbsp;
        </div>
        <div class="span-2">網站</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['website']) {
                echo $this->request->data['Point']['website'];
            }
?>&nbsp;
        </div>
        <div class="span-2">電話</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['telephone']) {
                echo $this->request->data['Point']['telephone'];
            }
?>&nbsp;
        </div>
        <div class="span-2">地理緯度</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['latitude']) {
                echo $this->request->data['Point']['latitude'];
            }
?>&nbsp;
        </div>
        <div class="span-2">地理經度</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['longitude']) {
                echo $this->request->data['Point']['longitude'];
            }
?>&nbsp;
        </div>
        <div class="span-2">相關行程數量</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['count_schedules']) {
                echo $this->request->data['Point']['count_schedules'];
            }
?>&nbsp;
        </div>
        <div class="span-2">評論數</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['count_comments']) {
                echo $this->request->data['Point']['count_comments'];
            }
?>&nbsp;
        </div>
        <div class="span-2">建立時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['created']) {
                echo $this->request->data['Point']['created'];
            }
?>&nbsp;
        </div>
        <div class="span-2">更新時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Point']['modified']) {
                echo $this->request->data['Point']['modified'];
            }
?>&nbsp;
        </div>
    </div><div class="span-12 last">&nbsp;</div>
</div>
<hr />
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('刪除', array('action' => 'delete', $this->Form->value('Point.id')), null, '確定要刪除？'); ?></li>
        <li><?php echo $this->Html->link('地點列表', array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link('檢視相關行程', array('controller' => 'schedules', 'action' => 'index', 'Point', $this->request->data['Point']['id']), array('class' => 'viewItem')); ?></li>
        <li><?php echo $this->Html->link('檢視相關行程細節', array('controller' => 'schedule_lines', 'action' => 'index', 'Point', $this->request->data['Point']['id']), array('class' => 'viewItem')); ?></li>
    </ul>
</div>
<div id="PointsrelationPanel"></div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'a.viewItem\').click(function() {
        $(\'#PointsrelationPanel\').load(this.href);
        return false;
    });
});
');
?>
