<div id="SubmitsAdminControlPage">
    <h2>會員提供資料管理</h2>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="SubmitsListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Submit.foreign_key', '對象', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Submit.member_id', '提供者', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Submit.is_new', '操作', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Submit.accepted', '接受日期', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($items as $item):
            $class = null;
            if ($i++ % 2 == 1) {
                $class = ' class="even"';
            }
            if (empty($item['Submit']['accepted'])) {
                $accepted = false;
            } else {
                $accepted = true;
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><?php
        echo $item['Submit']['model'];
        echo ' / ' . $item['Submit']['foreign_key'];
        echo ' / ' . $this->Html->link($item['Submit']['foreign_title'], array(
            'controller' => $foreignControllers[$item['Submit']['model']],
            'action' => 'view',
            $item['Submit']['foreign_key'],
            'admin' => false,
        ));
            ?></td>
                <td><?php
                echo $this->Html->link("{$item['Member']['nickname']}({$item['Member']['username']})", array(
                    'controller' => 'members',
                    'action' => 'view',
                    $item['Member']['id']
                ));
            ?></td>
                <td><?php echo ($item['Submit']['is_new'] == 1) ? '新增' : 'Edit'; ?></td>
                <td><?php
                if ($accepted) {
                    echo $item['Submit']['accepted'];
                } else {
                    echo '未處理';
                }
            ?></td>
                <td>
                    <div class="btn-group">
                        <?php
                        echo $this->Html->link('檢視', array('action' => 'view', $item['Submit']['id']), array('class' => 'SubmitControl btn'));
                        if (!$accepted) {
                            echo $this->Html->link('Edit', array('action' => 'edit', $item['Submit']['id']), array('class' => 'SubmitControl btn'));
                            echo $this->Html->link('接受', array('action' => 'accept', $item['Submit']['id']), array('class' => 'btn'));
                        }
                        echo $this->Html->link('Delete', array('action' => 'delete', $item['Submit']['id']), array('class' => 'btn'), 'Are you sure you want to delete this?');
                        ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="SubmitControlPanel"></div>
    <?php
    $scripts = '
$(function() {
    $(\'#SubmitsListTable th a, #SubmitsAdminControlPage div.paging a\').click(function() {
        $(\'#SubmitsAdminControlPage\').load(this.href);
        return false;
    });
    $(\'.SubmitControl\').click(function() {
    	dialogFull(this);
    	return false;
    });
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>