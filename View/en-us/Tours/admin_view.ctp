<div class="Tours view">
    <h2>檢視旅行社</h2><hr />
    <div class="span-12">

        <div class="span-2">名稱</div>
        <div class="span-9">&nbsp;<?php
if ($this->request->data['Tour']['title']) {
    echo $this->request->data['Tour']['title'];
}
?>&nbsp;
        </div>
        <div class="span-2">住址</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['address']) {
                echo $this->request->data['Tour']['address'];
            }
?>&nbsp;
        </div>
        <div class="span-2">郵遞區號</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['postcode']) {
                echo $this->request->data['Tour']['postcode'];
            }
?>&nbsp;
        </div>
        <div class="span-2">網站</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['website']) {
                echo $this->request->data['Tour']['website'];
            }
?>&nbsp;
        </div>
        <div class="span-2">電話</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['telephone']) {
                echo $this->request->data['Tour']['telephone'];
            }
?>&nbsp;
        </div>
        <div class="span-2">地理緯度</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['latitude']) {
                echo $this->request->data['Tour']['latitude'];
            }
?>&nbsp;
        </div>
        <div class="span-2">地理經度</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['longitude']) {
                echo $this->request->data['Tour']['longitude'];
            }
?>&nbsp;
        </div>
        <div class="span-2">相關行程數量</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['count_schedules']) {
                echo $this->request->data['Tour']['count_schedules'];
            }
?>&nbsp;
        </div>
        <div class="span-2">評論數</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['count_comments']) {
                echo $this->request->data['Tour']['count_comments'];
            }
?>&nbsp;
        </div>
        <div class="span-2">建立時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['created']) {
                echo $this->request->data['Tour']['created'];
            }
?>&nbsp;
        </div>
        <div class="span-2">更新時間</div>
        <div class="span-9">&nbsp;<?php
            if ($this->request->data['Tour']['modified']) {
                echo $this->request->data['Tour']['modified'];
            }
?>&nbsp;
        </div>
    </div><div class="span-12 last">&nbsp;</div>
</div>
<hr />
<div class="actions">
    <ul class="list1">
        <li><?php echo $this->Html->link('刪除', array('action' => 'delete', $this->Form->value('Tour.id')), null, '確定要刪除？'); ?></li>
        <li><?php echo $this->Html->link('旅行社列表', array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link('檢視相關行程', array('controller' => 'schedules', 'action' => 'index', 'Tour', $this->request->data['Tour']['id']), array('class' => 'viewItem')); ?></li>
        <li><?php echo $this->Html->link('檢視相關行程細節', array('controller' => 'schedule_lines', 'action' => 'index', 'Tour', $this->request->data['Tour']['id']), array('class' => 'viewItem')); ?></li>
    </ul>
</div>
<div id="ToursrelationPanel"></div>
<?php
echo $this->Html->scriptBlock('
$(function() {
    $(\'a.viewItem\').click(function() {
        $(\'#ToursrelationPanel\').load(this.href);
        return false;
    });
});
');
?>
