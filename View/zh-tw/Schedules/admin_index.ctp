<div id="SchedulesAdminControlPage">
    <h2>行程管理</h2>
    <div class="btn-group">
        <?php
        echo $this->Html->link('重新計算天數', array('action' => 'index', 'recountDays'), array('class' => 'btn'));
        echo $this->Html->link('匯入資料', array('action' => 'pull'), array('class' => 'btn'));
        echo $this->Html->link('活動', '/admin/activities', array('class' => 'btn'));
        echo $this->Html->link('交通', '/admin/transports', array('class' => 'btn'));
        echo $this->Html->link('記事', '/admin/schedule_notes', array('class' => 'btn'));
        ?>
    </div>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="SchedulesAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Schedule.title', '行程名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.member_name', '會員名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.count_views', '點閱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.count_days', '活動天數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.count_points', '景點數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.time_start', '出發時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Schedule.modified', '更新時間', array('url' => $url)); ?></th>
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
                    <td><?php
                    if(!empty($item['Schedule']['is_draft'])) {
                        echo '[私]';
                    }
                    echo $item['Schedule']['title'];
                    ?></td>
                    <td><?php
                    if (!empty($item['Schedule']['member_id'])) {
                        echo $this->Html->link($item['Schedule']['member_name'], '/members/view/' . $item['Schedule']['member_id'], array(
                            'target' => '_blank'
                        ));
                    } else {
                        echo $item['Schedule']['member_name'];
                    }
                ?></td>
                    <td><?php echo $item['Schedule']['count_views']; ?></td>
                    <td><?php echo $item['Schedule']['count_days']; ?></td>
                    <td><?php echo $item['Schedule']['count_points']; ?></td>
                    <td><?php echo $item['Schedule']['time_start']; ?></td>
                    <td><?php echo $item['Schedule']['modified']; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('檢視', '/schedules/view/' . $item['Schedule']['id'], array('target' => '_blank')); ?>
                        <?php echo $this->Html->link('區域', '/areas/getList/Schedule/' . $item['Schedule']['id'], array('target' => '_blank')); ?>
                        <?php echo $this->Html->link('Delete', array('action' => 'delete', $item['Schedule']['id']), null, 'Are you sure you want to delete this?'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <?php
    $scripts = '
$(function() {
    $(\'#SchedulesAdminListTable th a, #SchedulesAdminControlPage div.paging a\').click(function() {
        $(\'#SchedulesAdminControlPage\').load(this.href);
        return false;
    });
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>